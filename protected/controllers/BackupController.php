<?php

class BackupController extends Controller
{
    public $layout = '//layouts/column2';
    const RETENTION_DAYS = 30;

    public function filters()
    {
        return array(
            'accessControl',
            'postOnly + delete',
        );
    }

    protected function beforeAction($action)
    {
        $access = $this->checkAccess(Yii::app()->controller->id, Yii::app()->controller->action->id);
        if ($access == 1) {
            return true;
        } else {
            Yii::app()->user->setFlash('error', "You are not authorized to perform this action!");
            $this->redirect(array('/site/noaccess'));
        }
    }

    public function accessRules()
    {
        return array(
            array('allow', 'actions' => array('*'), 'users' => array('*')),
            array('allow', 'actions' => array('admin', 'create', 'delete', 'download', 'exportdatabase', 'restore', 'cleanup'), 'users' => array('@')),
            array('allow', 'actions' => array('admin', 'delete', 'restore', 'cleanup'), 'users' => array('admin')),
            array('deny', 'users' => array('*')),
        );
    }

    public function actionAdmin()
    {
        $model = new Backup('search');
        $model->unsetAttributes();
        if (isset($_GET['Backup']))
            $model->attributes = $_GET['Backup'];

        $criteria = new CDbCriteria;
        if ($model->id) {
            $criteria->compare('t.id', $model->id);
        }
        if ($model->attachment) {
            $criteria->compare('t.attachment', $model->attachment, true);
        }
        if ($model->created_on) {
            $criteria->compare('t.created_on', $model->created_on, true);
        }
        if ($model->created_by) {
            $criteria->compare('t.created_by', $model->created_by);
        }
        $criteria->order = 't.created_on DESC, t.id DESC';

        $rawData = Backup::model()->findAll($criteria);
        $dataProvider = new CArrayDataProvider($rawData, array(
            'pagination' => array('pageSize' => Yii::app()->params['pageSize']),
            'sort' => array('defaultOrder' => 'created_on DESC, id DESC')
        ));

        $this->render('admin', array(
            'model' => $model,
            'dataProvider' => $dataProvider,
            'stats' => $this->getBackupStats(),
        ));
    }

    public function actionCreate($type = 'gzip')
    {
        set_time_limit(0);
        $startTime = microtime(true);

        try {
            $engine = new BackupEngine;
            $engine->setBackupPath(Yii::app()->basePath . '/../uploads/backups');

            $backup = $engine->createBackup($type);

            $duration = round(microtime(true) - $startTime, 2);

            Yii::app()->user->setFlash('success',
                'Database backed up successfully! ' .
                $backup->tables_count . ' tables exported in ' . $duration . 's. ' .
                'File: ' . Backup::formatBytes($backup->file_size)
            );
        } catch (Exception $e) {
            Yii::app()->user->setFlash('error', 'Backup failed: ' . $e->getMessage());
        }

        $this->redirect(array('admin'));
    }

    public function actionExportdatabase($type = 'gzip')
    {
        return $this->actionCreate($type);
    }

    public function actionRestore($id)
    {
        $model = $this->loadModel($id);
        $filePath = Yii::app()->basePath . '/../uploads/backups/' . $model->attachment;

        if (!is_file($filePath) || !file_exists($filePath)) {
            Yii::app()->user->setFlash('error', "Backup file not found: " . $model->attachment);
            $this->redirect(array('admin'));
        }

        if ($model->status !== Backup::STATUS_SUCCESS) {
            Yii::app()->user->setFlash('error', "Cannot restore from a failed backup.");
            $this->redirect(array('admin'));
        }

        $startTime = microtime(true);

        try {
            if ($model->type === Backup::TYPE_GZIP) {
                $sqlContent = gzdecode(file_get_contents($filePath));
            } elseif ($model->type === Backup::TYPE_ZIP) {
                $zip = new ZipArchive();
                $zip->open($filePath);
                $sqlFileName = $this->getSqlFileNameFromZip($zip);
                $sqlContent = $zip->getFromIndex($zip->locateName($sqlFileName));
                $zip->close();
            } else {
                $sqlContent = file_get_contents($filePath);
            }

            if (empty($sqlContent)) {
                throw new Exception('Backup file is empty or corrupted.');
            }

            $statements = $this->parseSqlStatements($sqlContent);

            $connection = Yii::app()->db;
            $connection->createCommand('SET FOREIGN_KEY_CHECKS = 0;')->execute();

            foreach ($statements as $statement) {
                if (!empty($statement)) {
                    $connection->createCommand($statement)->execute();
                }
            }

            $connection->createCommand('SET FOREIGN_KEY_CHECKS = 1;')->execute();

            $duration = round(microtime(true) - $startTime, 2);

            Yii::app()->user->setFlash('success',
                'Database restored successfully in ' . $duration . 's. ' .
                count($statements) . ' statements executed.'
            );
        } catch (Exception $e) {
            Yii::app()->user->setFlash('error', 'Restore failed: ' . $e->getMessage());
        }

        $this->redirect(array('admin'));
    }

    public function actionCleanup($days = null)
    {
        $days = $days ? (int) $days : self::RETENTION_DAYS;
        $deleted = Backup::cleanOldBackups($days);

        Yii::app()->user->setFlash('success',
            'Cleanup completed. ' . $deleted . ' backup(s) older than ' . $days . ' days were deleted.'
        );
        $this->redirect(array('admin'));
    }

    public function actionDownload($id)
    {
        $model = $this->loadModel($id);
        $filePath = Yii::app()->basePath . '/../uploads/backups/' . $model->attachment;

        if (empty($model->attachment) || !is_file($filePath) || !file_exists($filePath)) {
            Yii::app()->user->setFlash('error', "The file <strong>" . $model->attachment . "</strong> does not exist");
            $this->redirect(array('admin'));
        }

        $content = file_get_contents($filePath);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($model->attachment) . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        ob_clean();
        flush();
        echo $content;
        exit;
    }

    public function actionDelete($id)
    {
        $model = Backup::model()->findByPk($id);
        if ($model) {
            $filePath = Yii::app()->basePath . '/../uploads/backups/' . $model->attachment;
            if (is_file($filePath) && file_exists($filePath)) {
                @unlink($filePath);
            }
        }

        $this->loadModel($id)->delete();

        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    private function getBackupStats()
    {
        $stats = array(
            'total_backups' => 0,
            'total_size' => 0,
            'last_backup' => null,
            'success_count' => 0,
            'failed_count' => 0,
        );

        $stats['total_backups'] = (int) Yii::app()->db->createCommand('SELECT COUNT(*) FROM {{backup}}')->queryScalar();
        $stats['total_size'] = (int) Yii::app()->db->createCommand('SELECT IFNULL(SUM(file_size),0) FROM {{backup}}')->queryScalar();
        $stats['last_backup'] = Yii::app()->db->createCommand('SELECT MAX(created_on) FROM {{backup}}')->queryScalar();
        $stats['success_count'] = (int) Yii::app()->db->createCommand('SELECT COUNT(*) FROM {{backup}} WHERE status=:status')->bindValue(':status', Backup::STATUS_SUCCESS)->queryScalar();
        $stats['failed_count'] = (int) Yii::app()->db->createCommand('SELECT COUNT(*) FROM {{backup}} WHERE status=:status')->bindValue(':status', Backup::STATUS_FAILED)->queryScalar();

        return $stats;
    }

    private function getSqlFileNameFromZip(ZipArchive $zip)
    {
        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if (preg_match('/\.sql$/i', $name)) {
                return $name;
            }
        }
        throw new Exception('No SQL file found inside ZIP archive.');
    }

    private function parseSqlStatements($sqlContent)
    {
        $statements = array();
        $current = '';
        $inString = false;
        $stringChar = '';
        $length = strlen($sqlContent);

        for ($i = 0; $i < $length; $i++) {
            $char = $sqlContent[$i];

            if ($inString) {
                $current .= $char;
                if ($char === $stringChar && ($i === 0 || $sqlContent[$i - 1] !== '\\')) {
                    $inString = false;
                }
            } else {
                if ($char === "'" || $char === '"') {
                    $inString = true;
                    $stringChar = $char;
                    $current .= $char;
                } elseif ($char === ';') {
                    $statement = trim($current);
                    if (!empty($statement)) {
                        $statements[] = $statement;
                    }
                    $current = '';
                } else {
                    $current .= $char;
                }
            }
        }

        $remaining = trim($current);
        if (!empty($remaining)) {
            $statements[] = $remaining;
        }

        return $statements;
    }

    public function loadModel($id)
    {
        $model = Backup::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'backup-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
