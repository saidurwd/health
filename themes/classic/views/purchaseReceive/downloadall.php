<?php

$file_path = getcwd() . '/uploads/store/';
$id = $_REQUEST['id'];
$file_names = PurchaseReceiveDocument::model()->findAll(array('condition' => 'receive_number=' . (int) $id));
$total_files = count($file_names);

function zipFilesAndDownload($file_names, $archive_file_name, $file_path) {
    $zip = new ZipArchive();
    //create the file and throw the error if unsuccessful
    if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE) !== TRUE) {
        exit("cannot open <$archive_file_name>\n");
    }
    //add each files of $file_name array to archive
    foreach ($file_names as $files) {
        $zip->addFile($file_path . $files['doc_file'], $files['doc_file']);
    }
    $zip->close();
    $zipped_size = filesize($archive_file_name);
    header("Content-Description: File Transfer");
    header("Content-type: application/zip");
    header("Content-Type: application/force-download"); // some browsers need this
    header("Content-Disposition: attachment; filename=$archive_file_name");
    header('Expires: 0');
    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
    header('Pragma: public');
    header("Content-Length:" . " $zipped_size");
    ob_clean();
    flush();
    readfile("$archive_file_name");
    unlink("$archive_file_name"); // Now delete the temp file (some servers need this option)
    exit;
}

$archive_file_name = $id . '.zip';
if ($total_files > 0) {
    zipFilesAndDownload($file_names, $archive_file_name, $file_path);
} else {
    Yii::app()->user->setFlash('error', "This Purchase Receive have no document's for download yet!");
    $this->redirect(array('purchaseReceive/view', 'id' => $id));
}
?>
