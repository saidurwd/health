<?php
$this->pageTitle = 'Patient Register Physiotherapy';
?>
<div class="row" style="margin-bottom:10px;font-size: 14px;">
    <div style="float:left; width:150px;">
        <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/img/rishilpi_logo.png', 'Logo', array('alt' => 'Logo', 'class' => '', 'title' => '', 'style' => '')); ?>
    </div>
    <div style="float:left; width:250px;margin-top:25px;">
        <div style="font-size: 16px;">
            <?php echo Yii::app()->params['topTag']; ?><br />
            <?php echo Yii::app()->params['adminName']; ?><br />
            <?php echo Yii::app()->params['bottomTag']; ?>
        </div>
    </div>   
</div>
<hr />
<div class="clearfix"></div>
<h3 style="text-align: left;">All Services</h3>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'service-grid',
    'dataProvider' => $model->search_print(),
    'afterAjaxUpdate' => 'reloadPageSetUp',
    'htmlOptions' => array('class' => ''),
    'itemsCssClass' => 'table table-bordered table-striped table-hover smart-form',
    'template' => '{items}',
    'emptyText' => 'No result found.',
    'summaryText' => "{start} - {end} of {count} result",
    'columns' => array(
        array(
            'name' => 'parent',
            'value' => 'Service::getData($data->parent,"title")',
            'filter' => CHtml::activeDropDownList($model, 'parent', CHtml::listData(Service::model()->findAll(array('condition' => 'parent IS NULL OR parent=0', "order" => "path")), 'id', 'title'), array('empty' => 'All', 'class' => 'select2')),
            'htmlOptions' => array('style' => "text-align:left;"),
            'sortable'=>false, 
        ),
        array(
            'name' => 'title',
            'type' => 'raw',
            //'value' => '$data->title',
            'value' => 'Service::get_full_path($data->id)',
            'filter' => CHtml::activeTextField($model, 'title', array('class' => 'form-control')),
            'htmlOptions' => array('class' => ''),
            'sortable'=>false, 
        ),
        array(
            'name' => 'rate',
            'type' => 'raw',
            'value' => '$data->rate',
            'filter' => CHtml::activeTextField($model, 'rate', array('class' => 'form-control')),
            'htmlOptions' => array('class' => ''),
            'sortable'=>false, 
        ),
        array(
            'name' => 'discount',
            'value' => '$data->discount',
            'filter' => CHtml::activeDropDownList($model, 'discount', array('No' => 'No', 'Yes' => 'Yes'), array('empty' => 'Select Discount', 'class' => 'form-control')),
            'htmlOptions' => array('style' => "text-align:center;"),
            'sortable'=>false, 
        ),
        array(
            'name' => 'service_type',
            'value' => '$data->service_type',
            'filter' => CHtml::activeDropDownList($model, 'service_type', array('Consultation' => 'Consultation', 'Service' => 'Service'), array('empty' => 'All', 'class' => 'form-control')),
            'htmlOptions' => array('style' => "text-align:left;"),
            'sortable'=>false, 
        ),
        array(
            'name' => 'service_grade',
            'value' => 'PatientGrade::getData($data->service_grade,"title")',
            'filter' => CHtml::activeDropDownList($model, 'service_grade', CHtml::listData(PatientGrade::model()->findAll(array('condition' => '', "order" => "title")), 'id', 'title'), array('empty' => 'All', 'class' => 'select2')),
            'htmlOptions' => array('style' => "text-align:left;"),
            'sortable'=>false, 
        ),
    ),
));
?>
<div class="invoice-footer space-top-10">
    <div class="row">
        <div class="col-sm-12 text-right">
            <p class="note"><?php echo Yii::app()->params['print_note']; ?></p>
        </div>
    </div>
</div>
<script type="text/javascript">
    setTimeout(function () {
        window.print();
    }, 5000); //giving 5 sec loading time.
</script>
<style>
    body{
        font-size: 12px;
    }
    .font-size{
        font-size: 12px;
    }
    .text-center{
        text-align: center;
    }
</style>