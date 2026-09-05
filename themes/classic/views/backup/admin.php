<?php
/* @var $this BackupController */
/* @var $model Backup */
/* @var $stats array */

$this->pageTitle = 'Database Backup - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Backups' => array('admin'),
    'Manage',
);

Yii::app()->clientScript->registerScript('reload-pageSetUp', "
    function reloadPageSetUp() {
        pageSetUp();
    }
    ", CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-database fa-fw"></i>
            Database Backup
            <span>>
                Manage
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="">
            <li class="sparks-info">
                <h5>Total Backups <span class="txt-color-blue"><?php echo CHtml::encode($stats['total_backups']); ?></span></h5>
            </li>
            <li class="sparks-info">
                <h5>Storage Used <span class="txt-color-orange"><?php echo Backup::formatBytes($stats['total_size']); ?></span></h5>
            </li>
        </ul>
    </div>
</div>

<div class="row">
    <div class="col-sm-12 col-md-12 col-lg-12">
        <div class="well well-sm well-light">
            <div class="row">
                <div class="col-sm-3 col-md-3 col-lg-3">
                    <div class="text-center">
                        <h3 class="no-margin"><?php echo CHtml::encode($stats['total_backups']); ?></h3>
                        <small>Total Backups</small>
                    </div>
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3">
                    <div class="text-center">
                        <h3 class="no-margin txt-color-green"><?php echo CHtml::encode($stats['success_count']); ?></h3>
                        <small>Successful</small>
                    </div>
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3">
                    <div class="text-center">
                        <h3 class="no-margin txt-color-red"><?php echo CHtml::encode($stats['failed_count']); ?></h3>
                        <small>Failed</small>
                    </div>
                </div>
                <div class="col-sm-3 col-md-3 col-lg-3">
                    <div class="text-center">
                        <h3 class="no-margin txt-color-orange"><?php echo Backup::formatBytes($stats['total_size']); ?></h3>
                        <small>Storage Used</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-database"></i> </span>
                <h2>Backup Management</h2>
                <div class="widget-toolbar">
                    <?php echo CHtml::link('<i class="fa fa-download"></i> EXPORT (GZIP)', array('exportdatabase', 'type' => 'gzip'), array('data-rel' => 'tooltip', 'title' => 'Export database with GZIP compression (recommended)', 'data-placement' => 'bottom', 'class' => 'btn btn-xs btn-primary')); ?>
                    <?php echo CHtml::link('<i class="fa fa-file-archive-o"></i> EXPORT (ZIP)', array('exportdatabase', 'type' => 'zip'), array('data-rel' => 'tooltip', 'title' => 'Export database as ZIP archive', 'data-placement' => 'bottom', 'class' => 'btn btn-xs btn-primary')); ?>
                    <?php echo CHtml::link('<i class="fa fa-file-code-o"></i> EXPORT (SQL)', array('exportdatabase', 'type' => 'sql'), array('data-rel' => 'tooltip', 'title' => 'Export database as plain SQL (uncompressed)', 'data-placement' => 'bottom', 'class' => 'btn btn-xs btn-primary')); ?>
                    <?php echo CHtml::link('<i class="fa fa-trash-o"></i> CLEANUP OLD', array('cleanup'), array('data-rel' => 'tooltip', 'title' => 'Delete backups older than ' . BackupController::RETENTION_DAYS . ' days', 'data-placement' => 'bottom', 'class' => 'btn btn-xs btn-warning', 'confirm' => 'Are you sure you want to delete backups older than ' . BackupController::RETENTION_DAYS . ' days?')); ?>
                </div>
            </header>
            <div>
                <div class="widget-body no-padding">
                    <?php if (Yii::app()->user->hasFlash('success')): ?>
                        <div class="alert alert-success fade in">
                            <button class="close" data-dismiss="alert">×</button>
                            <i class="fa-fw fa fa-check"></i>
                            <?php echo Yii::app()->user->getFlash('success'); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (Yii::app()->user->hasFlash('error')): ?>
                        <div class="alert alert-danger fade in">
                            <button class="close" data-dismiss="alert">×</button>
                            <i class="fa-fw fa fa-times"></i>
                            <?php echo Yii::app()->user->getFlash('error'); ?>
                        </div>
                    <?php endif; ?>
                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'id' => 'backup-grid',
                        'dataProvider' => $dataProvider,
                        'filter' => $model,
                        'afterAjaxUpdate' => 'reloadPageSetUp',
                        'htmlOptions' => array('class' => ''),
                        'itemsCssClass' => 'table table-bordered table-striped table-hover smart-form',
                        'template' => '{items}{pager}',
                        'emptyText' => 'No backup records found. Click EXPORT to create your first backup.',
                        'summaryText' => "{start} - {end} of {count} backup(s)",
                        'pager' => array(
                            'htmlOptions' => array('class' => 'pagination'),
                            'header' => '',
                            'selectedPageCssClass' => 'active',
                        ),
                        'pagerCssClass' => 'widget-footer',
                        'columns' => array(
                            array(
                                'name' => 'created_on',
                                'type' => 'raw',
                                'value' => 'User::get_date_time($data->created_on)',
                                'filter' => CHtml::activeTextField($model, 'created_on', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')),
                                'htmlOptions' => array('style' => "text-align:left;width:180px;"),
                            ),
                            array(
                                'name' => 'attachment',
                                'type' => 'raw',
                                'value' => 'CHtml::encode($data->attachment) . "<br/><small class=\"text-muted\">" . Backup::formatBytes($data->file_size) . "</small>"',
                                'filter' => CHtml::activeTextField($model, 'attachment', array('class' => 'form-control')),
                                'htmlOptions' => array('class' => 'text-left'),
                            ),
                            array(
                                'name' => 'type',
                                'type' => 'raw',
                                'value' => 'strtoupper($data->type)',
                                'filter' => CHtml::activeDropDownList($model, 'type', array("sql" => "SQL", "zip" => "ZIP", "gzip" => "GZIP"), array('empty' => 'All', 'class' => 'select2')),
                                'htmlOptions' => array('style' => "text-align:center;width:80px;"),
                            ),
                            array(
                                'name' => 'status',
                                'type' => 'raw',
                                'value' => '$data->status == Backup::STATUS_SUCCESS ? "<span class=\"label label-success\">Success</span>" : "<span class=\"label label-danger\">Failed</span>"',
                                'filter' => CHtml::activeDropDownList($model, 'status', array("" => "All", "success" => "Success", "failed" => "Failed"), array('class' => 'select2')),
                                'htmlOptions' => array('style' => "text-align:center;width:90px;"),
                            ),
                            array(
                                'name' => 'duration',
                                'type' => 'raw',
                                'value' => '$data->duration',
                                'filter' => CHtml::activeTextField($model, 'duration', array('class' => 'form-control')),
                                'htmlOptions' => array('style' => "text-align:center;width:80px;"),
                            ),
                            array(
                                'name' => 'tables_count',
                                'type' => 'raw',
                                'value' => '$data->tables_count',
                                'filter' => CHtml::activeTextField($model, 'tables_count', array('class' => 'form-control')),
                                'htmlOptions' => array('style' => "text-align:center;width:70px;"),
                            ),
                            array(
                                'name' => 'checksum',
                                'type' => 'raw',
                                'value' => 'CHtml::encode($data->checksum)',
                                'filter' => false,
                                'htmlOptions' => array('style' => "font-family:monospace;font-size:11px;"),
                            ),
                            array(
                                'name' => 'created_by',
                                'type' => 'raw',
                                'value' => 'User::get_full_name($data->created_by)',
                                'filter' => CHtml::activeDropDownList($model, 'created_by', CHtml::listData(User::model()->findAll(array('condition' => '', 'order' => 'full_name')), 'id', 'full_name'), array('empty' => 'All', 'class' => 'select2')),
                                'htmlOptions' => array('style' => "text-align:left;"),
                            ),
                            array(
                                'header' => 'Actions',
                                'class' => 'CButtonColumn',
                                'htmlOptions' => array('class' => "text-center width-120"),
                                'template' => '{restore} {download} {delete}',
                                'buttons' => array(
                                    'restore' => array(
                                        'label' => '',
                                        'imageUrl' => '',
                                        'url' => 'yii::app()->createUrl("/backup/restore", array("id"=>$data["id"]))',
                                        'options' => array('class' => 'btn btn-xs btn-success fa fa-undo', 'rel' => 'tooltip', 'data-original-title' => 'Restore this backup', 'confirm' => 'WARNING: Restoring will overwrite the current database. Are you absolutely sure?'),
                                        'visible' => 'Yii::app()->user->name === "admin"',
                                    ),
                                    'download' => array(
                                        'label' => '',
                                        'imageUrl' => '',
                                        'url' => 'yii::app()->createUrl("/backup/download", array("id"=>$data["id"]))',
                                        'options' => array('class' => 'btn btn-xs btn-warning fa fa-download', 'rel' => 'tooltip', 'data-original-title' => 'Download backup'),
                                    ),
                                    'delete' => array(
                                        'label' => '',
                                        'imageUrl' => '',
                                        'options' => array('class' => 'btn btn-xs btn-danger fa fa-times', 'rel' => 'tooltip', 'data-original-title' => 'Delete backup'),
                                    ),
                                ),
                            ),
                        ),
                    ));
                    ?>
                </div>
                <!-- end widget content -->
            </div>
            <!-- end widget div -->
        </div>
        <!-- end widget -->
    </div>
</div>
