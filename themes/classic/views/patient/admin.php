<?php
/* @var $this PatientController */
/* @var $model Patient */
$this->pageTitle = 'Patients - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Patients' => array('admin'),
    'Manage',
);

Yii::app()->clientScript->registerScript('reload-pageSetUp', "
    function reloadPageSetUp() {
        pageSetUp();
    }
    ", CClientScript::POS_END);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#patient-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-home fa-fw "></i> 
            Patients
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
                    <h2>Patients</h2>   
                    <div class="widget-toolbar">
                        <?php echo CHtml::link('<i class="fa fa-plus"></i> NEW', array('create'), array('data-rel' => 'tooltip', 'title' => 'New', 'data-placement' => 'bottom', 'class' => 'btn btn-xs btn-primary')); ?>
                    </div>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php //echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
                        <div class="search-form" style="display:none">
                            <?php
                            $this->renderPartial('_search', array(
                                'model' => $model,
                            ));
                            ?>
                        </div><!-- search-form -->
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'patient-grid',
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
                                    'filter' => CHtml::activeTextField($model, 'created_on', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'name' => 'category_new',
                                    'value' => '$data->category_new0->alias',
                                    'filter' => CHtml::activeTextField($model, 'category_new', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'name' => 'category',
                                    'value' => '$data->category0->alias',
                                    'filter' => CHtml::activeTextField($model, 'category', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'name' => 'pat_id',
                                    'type' => 'raw',
//                                    'value' => '$data->pat_id',
                                    'value' => 'CHtml::link($data->pat_id, array("view","id"=>$data->id))',
                                    'filter' => CHtml::activeTextField($model, 'pat_id', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'name' => 'name',
                                    'type' => 'raw',
//                                    'value' => '$data->name',
                                    'value' => 'CHtml::link($data->name, array("view","id"=>$data->id))',
                                    'filter' => CHtml::activeTextField($model, 'name', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'name' => 'mobile',
                                    'type' => 'raw',
                                    'value' => '$data->mobile',
                                    'filter' => CHtml::activeTextField($model, 'mobile', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'name' => 'age',
                                    'type' => 'raw',
                                    'value' => '($data->birth_date && $data->birth_date != "0000-00-00") ? Patient::getAge($data->birth_date) : $data->age . " " . $data->age_type',
                                    'filter' => CHtml::activeTextField($model, 'age', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'name' => 'sex',
                                    'type' => 'raw',
                                    'value' => '$data->sex',
                                    'filter' => CHtml::activeTextField($model, 'sex', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'name' => 'blood_groop',
                                    'type' => 'raw',
                                    'value' => '$data->blood_groop',
                                    'filter' => CHtml::activeTextField($model, 'blood_groop', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'name' => 'national_id',
                                    'type' => 'raw',
                                    'value' => '$data->national_id',
                                    'filter' => CHtml::activeTextField($model, 'national_id', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'name' => 'address',
                                    'type' => 'raw',
                                    'value' => '$data->address . (isset($data->thana0) ? ", " . $data->thana0->title : "") . (isset($data->district0) ? ", " . $data->district0->title : "")',
                                    'filter' => CHtml::activeTextField($model, 'address', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'header' => 'Actions',
                                    'class' => 'CButtonColumn',
                                    'htmlOptions' => array('class' => "text-center width-100"),
                                    'template' => '{view} {update} {delete}',
                                    'buttons' => array(
                                        'view' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'options' => array('class' => 'btn btn-xs btn-info fa fa-search'),
                                        ),
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