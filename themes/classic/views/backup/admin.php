<?php
/* @var $this BackupController */
/* @var $model Backup */
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
            <i class="fa fa-home fa-fw "></i>
            Database Backup
            <span>>
                Manage
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="">
            <li class="sparks-info">
                <h5> </h5>
            </li>
        </ul>
    </div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="">
    <!-- row -->
    <div class="row">
        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-home"></i> </span>
                    <h2>Database Backup</h2>
                    <div class="widget-toolbar">
                        <?php echo CHtml::link('<i class="fa fa-random"></i> EXPORT DATABASE', array('exportdatabase'), array('data-rel' => 'tooltip', 'title' => 'Export database', 'data-placement' => 'bottom', 'class' => 'btn btn-xs btn-primary')); ?>
                        <?php //echo CHtml::link('<i class="fa fa-random"></i> BACKUP', array('create'), array('data-rel' => 'tooltip', 'title' => 'Database Backup', 'data-placement' => 'bottom', 'class' => 'btn btn-xs btn-primary')); ?>
                    </div>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">

                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'backup-grid',
                            'dataProvider' => $model->search(),
                            'filter' => $model,
                            'afterAjaxUpdate' => 'reloadPageSetUp',
                            'htmlOptions' => array('class' => ''),
                            'itemsCssClass' => 'table table-bordered table-striped table-hover smart-form',
                            'template' => '{items}{pager}',
                            'emptyText' => 'No result found.',
                            'summaryText' => "{start} - {end} of {count} result",
                            'pager' => array(
                                'htmlOptions' => array(
                                    'class' => 'pagination',
                                ),
                                'header' => '',
                                'selectedPageCssClass' => 'active',
                            ),
                            'pagerCssClass' => 'widget-footer',
                            'columns' => array(
                                array(
                                    'name' => 'created_on',
                                    'type' => 'raw',
                                    'value' => 'User::get_date($data->created_on)',
                                    'filter' => CHtml::activeTextField($model, 'created_on', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')),
                                    'htmlOptions' => array('style' => "text-align:left;width:150px;"),
                                ),
                                array(
                                    'name' => 'attachment',
                                    'type' => 'raw',
                                    'value' => '$data->attachment',
                                    'filter' => CHtml::activeTextField($model, 'attachment', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => 'text-left'),
                                ),
                                array(
                                    'name' => 'created_by',
                                    'type' => 'raw',
                                    'value' => 'User::get_full_name($data->created_by)',
                                    'filter' => CHtml::activeDropDownList($model, 'created_by', CHtml::listData(User::model()->findAll(array('condition' => '', 'order' => 'full_name')), 'id', 'full_name'), array('empty' => 'All', 'class' => 'select2')),
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'header' => 'Action',
                                    'class' => 'CButtonColumn',
                                    'htmlOptions' => array('class' => "text-center width-50"),
                                    'template' => '{download} {delete}',
                                    'buttons' => array(
                                        'download' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'url' => 'yii::app()->createUrl("/backup/download", array("id"=>$data["id"]))',
                                            'options' => array('class' => 'btn btn-xs btn-warning fa fa-download', 'rel' => 'tooltip', 'data-original-title' => 'Download'),
                                        ),
                                        'delete' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'options' => array('class' => 'btn btn-xs btn-danger fa fa-times'),
                                        ),
                                    ),
                                ),
                            ),
                        )
                        );
                        ?>
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </article>
        <!-- WIDGET END -->
    </div>
    <!-- end row -->
</section>
<!-- end widget grid -->