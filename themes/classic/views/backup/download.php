<?php

$file = Yii::app()->basePath . '/../uploads/backups/' . $model->attachment;
if (empty($model->attachment)) {
    Yii::app()->user->setFlash('error', "The file <strong>" . $model->attachment . "</strong> does not exist");
    $this->redirect(array('admin'));
}

if ((!is_file($file)) && (!file_exists($file))) {
    Yii::app()->user->setFlash('error', "The file <strong>" . $model->attachment . "</strong> does not exist");
    $this->redirect(array('admin'));
}
$content = file_get_contents($file);
header('Content-Description: File Transfer');
header("Content-type: application/octet-stream");
header('Content-Disposition: attachment; filename="' . basename($model->attachment) . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header("Content-Length: " . filesize($file));
ob_clean();
flush();
echo $content;
exit;
?>