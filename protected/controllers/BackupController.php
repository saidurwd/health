<?php

class BackupController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
            'postOnly + delete', // we only allow deletion via POST request
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

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('*'),
                'users' => array('*'),
            ),
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array('create', 'admin', 'delete', 'download','exportdatabase'),
                'users' => array('@'),
            ),
            array(
                'allow', // allow admin user to perform 'admin' and 'delete' actions
                'actions' => array('admin', 'delete'),
                'users' => array('admin'),
            ),
            array(
                'deny', // deny all users
                'users' => array('*'),
            ),
        );
    }

    public function actionDownload($id)
    {
        $this->render(
            'download',
            array(
                'model' => $this->loadModel($id),
            )
        );
    }

    public function actionExportdatabase()
    {
        set_time_limit(0);
        $path = Yii::app()->basePath . '/../uploads/backups';
        preg_match("/dbname=([^;]*)/", Yii::app()->db->connectionString, $dbnames);
        preg_match("/host=([^;]*)/", Yii::app()->db->connectionString, $hosts);

        // Database configuration
        $host = $hosts[1];
        $username = Yii::app()->db->username;
        $password = Yii::app()->db->password;
        $database_name = $dbnames[1];

        // Get connection object and set the charset
        $conn = mysqli_connect($host, $username, $password, $database_name);
        $conn->set_charset("utf8");


        // Get All Table Names From the Database
        $tables = array();
        $sql = "SHOW TABLES";
        $result = mysqli_query($conn, $sql);

        while ($row = mysqli_fetch_row($result)) {
            $tables[] = $row[0];
        }

        $sqlScript = "SET foreign_key_checks = 0;";

        foreach ($tables as $table) {
            // Prepare SQLscript for creating table structure
            $query = "SHOW CREATE TABLE $table";
            $result = mysqli_query($conn, $query);
            $row = mysqli_fetch_row($result);

            $sqlScript .= "\n\n" . $row[1] . ";\n\n";


            $query = "SELECT * FROM $table";
            $result = mysqli_query($conn, $query);

            $columnCount = mysqli_num_fields($result);

            // Prepare SQLscript for dumping data for each table
            for ($i = 0; $i < $columnCount; $i ++) {
                while ($row = mysqli_fetch_row($result)) {
                    $sqlScript .= "INSERT INTO $table VALUES(";
                    for ($j = 0; $j < $columnCount; $j ++) {
                        if (isset($row[$j])) {
                            $sqlScript .= "'" . addslashes($row[$j]) . "'";
                        } else {
                            $sqlScript .= "''";
                        }
                        if ($j < ($columnCount - 1)) {
                            $sqlScript .= ',';
                        }
                    }
                    $sqlScript .= ");\n";
                }
            }

            $sqlScript .= "\n";
        }
        $sqlScript .= "SET foreign_key_checks = 1;";

        if(!empty($sqlScript))
        {
            // Save the SQL script to a backup file
            $filename = $database_name . '_backup_' . time() . '.sql';
            $backup_file_name = $path.'/'.$filename;
            // Save .sql data
            $model = new Backup;
            $model->attachment = $filename;
            $model->created_by = Yii::app()->user->id;
            $model->created_on = new CDbExpression('NOW()');
            $model->save();

            //return $backup_file_name;
            $fileHandler = fopen($backup_file_name, 'w+');
            $number_of_lines = fwrite($fileHandler, $sqlScript);
            fclose($fileHandler);

            $zip = new ZipArchive();
            $zipFileName = $database_name . '_backup_' . time() . '.zip';

            // Save zip data
            $model = new Backup;
            $model->attachment = $zipFileName;
            $model->created_by = Yii::app()->user->id;
            $model->created_on = new CDbExpression('NOW()');
            $model->save();

            $zip->open($path. '/' . $zipFileName, ZipArchive::CREATE);
            $zip->addFile($backup_file_name, $database_name . '_backup_' . time() . '.sql');
            $zip->close();
        }
        //return $zipFileName;
        Yii::app()->user->setFlash('success', 'Database was backed up successfully!');
        $this->redirect(array('admin'));
    }

    /**
     * Backup Database.
     */
    public function actionCreate()
    {
        set_time_limit(0);
        $model = new Backup;
        $path = Yii::app()->basePath . '/../uploads/backups';
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        $filename = time() . '.sql';
        Helpers::backupDatabase($path . '/' . $filename);
        $model->attachment = $filename;
        $model->created_by = Yii::app()->user->id;
        $model->created_on = new CDbExpression('NOW()');
        $model->save();
        Yii::app()->user->setFlash('success', 'Database was backed up successfully!');
        $this->redirect(array('admin'));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        $model = Backup::model()->findByPk($id);
        unlink(Yii::app()->basePath."/../uploads/backups/".$model->attachment);

        $this->loadModel($id)->delete();

        // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
        if (!isset($_GET['ajax']))
            $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Backup('search');
        $model->unsetAttributes();  // clear any default values
        if (isset($_GET['Backup']))
            $model->attributes = $_GET['Backup'];

        $this->render(
            'admin',
            array(
                'model' => $model,
            )
        );
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer $id the ID of the model to be loaded
     * @return Backup the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Backup::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param Backup $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'backup-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

}
