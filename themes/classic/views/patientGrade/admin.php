<?php
/* @var $this PatientGradeController */
/* @var $model PatientGrade */

$this->pageTitle = 'Patient Grade - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Patient Grades' => array('admin'),
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
            Patient Grades
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
                    <h2>Patient Grades</h2>
                    <div class="widget-toolbar">
                        <?php echo CHtml::link('<i class="fa fa-plus"></i> NEW', array('create'), array('data-rel' => 'tooltip', 'title' => 'New', 'data-placement' => 'bottom', 'class' => 'btn btn-xs btn-primary')); ?>
                    </div>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'patient-type-grid',
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
                                    'name' => 'title',
                                    'type' => 'raw',
                                    'value' => '$data->title',
                                    'filter' => CHtml::activeTextField($model, 'title', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'name' => 'remarks',
                                    'type' => 'raw',
                                    'value' => '$data->remarks',
                                    'filter' => CHtml::activeTextField($model, 'remarks', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'name' => 'status',
                                    'value' => '$data->status',
                                    'filter' => CHtml::activeDropDownList($model, 'status', array('Active' => 'Active', 'Inactive' => 'Inactive'), array('empty' => 'All', 'class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:center;"),
                                ),
                                array(
                                    'header' => 'Actions',
                                    'class' => 'CButtonColumn',
                                    'htmlOptions' => array('class' => "text-center width-50"),
                                    'template' => '{update} {delete}',
                                    'buttons' => array(
                                        'update' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'options' => array('class' => 'btn btn-xs btn-primary fa fa-pencil'),
                                        ),
                                        'delete' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'options' => array('class' => 'btn btn-xs btn-danger fa fa-times'),
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
        </article>
        <!-- WIDGET END -->
    </div>
    <!-- end row -->
</section>
<!-- end widget grid -->