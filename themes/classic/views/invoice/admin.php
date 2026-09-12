<?php
/* @var $this InvoiceController */
/* @var $model Invoice */
$this->pageTitle = 'Invoices';
$this->breadcrumbs = array(
    'Invoices' => array('admin'),
    'Manage',
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#invoice-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
Yii::app()->clientScript->registerScript('setup', "
function reloadPageSetUp() {
        pageSetUp();
    }
", CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-shopping-cart fa-fw "></i>
            Invoice
            <span>>
                Manage
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-plus"></i></span> NEW', array('create'), array('class' => 'btn btn-labeled btn-primary')); ?>
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-search"></i></span>Advanced Search', '#', array('class' => 'btn btn-labeled btn-success search-button')); ?>
    </div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="">
    <!-- row -->
    <div class="row">
        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false" data-widget-colorbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-home"></i> </span>
                    <h2>Invoices</h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body padding-bottom-5">
                        <div class="search-form" style="display:none">
                            <?php
                            $this->renderPartial('_search', array(
                                'model' => $model,
                            ));
                            ?>
                        </div><!-- search-form -->
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'invoice-parent-grid',
                            'dataProvider' => $model->search(),
                            'filter' => $model,
                            'afterAjaxUpdate' => 'reloadPageSetUp',
                            'htmlOptions' => array('class' => ''),
                            'itemsCssClass' => 'table table-bordered table-striped table-hover',
                            'template' => '{items}{pager}{summary}',
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
                                    'name' => 'patient',
                                    'type' => 'raw',
                                    //                                    'value' => 'Patient::getData($data->patient,"name")',
                                    'value' => 'CHtml::link(Patient::getData($data->patient,"name"), array("patient/view","id"=>$data->patient),array("target"=>"_blank"))',
                                    'filter' => CHtml::activeDropDownList($model, 'patient', CHtml::listData(Patient::model()->findAll(array('select' => 'id, CONCAT(name," [",pat_id,"]") AS name', 'condition' => '', 'order' => 'name')), 'id', 'name'), array('empty' => 'All', 'class' => 'select2')),
                                    'htmlOptions' => array('class' => 'text-left'),
                                ),
                                array(
                                    'name' => 'invoice_number',
                                    'type' => 'raw',
                                    //                                    'value' => '$data->invoice_number',
                                    'value' => 'CHtml::link($data->invoice_number, array("view","id"=>$data->id))',
                                    'filter' => CHtml::activeTextField($model, 'invoice_number', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'name' => 'invoice_date',
                                    'type' => 'raw',
                                    'value' => 'User::get_date($data->invoice_date)',
                                    'filter' => CHtml::activeTextField($model, 'invoice_date', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')),
                                    'htmlOptions' => array('style' => "text-align:left;width:150px;"),
                                ),
                                array(
                                    'type' => 'raw',
                                    'header' => "# of Items",
                                    'value' => 'Invoice::getNumberOfItems($data->id)',
                                    'htmlOptions' => array('style' => "text-align:center;width:100px;"),
                                ),
                                array(
                                    'name' => 'total_amount',
                                    'type' => 'raw',
                                    'value' => 'Product::number_format_currency($data->total_amount,2,Yii::app()->session->get(\'currency\'))',
                                    'filter' => CHtml::activeTextField($model, 'total_amount', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => "text-right", 'style' => 'width:150px;'),
                                ),
                                array(
                                    'name' => 'invoice_by',
                                    'type' => 'raw',
                                    'value' => 'User::get_full_name($data->invoice_by)',
                                    'filter' => CHtml::activeDropDownList($model, 'invoice_by', CHtml::listData(User::model()->findAll(array('condition' => '', 'order' => 'full_name')), 'id', 'full_name'), array('empty' => 'All', 'class' => 'select2')),
                                    'htmlOptions' => array('style' => "text-align:left;width:200px;"),
                                ),
                                array(
                                    'name' => 'payment_status',
                                    'header' => 'Payment',
                                    'value' => '$data->payment_status',
                                    'filter' => CHtml::activeDropDownList($model, 'payment_status', array('Paid' => 'Paid', 'Unpaid' => 'Unpaid'), array('empty' => 'All', 'class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:center;width:100px"),
                                ),
                                array(
                                    'name' => 'status',
                                    'type' => 'raw',
                                    'value' => 'TransectionStatus::getStatus($data->status,5)',
                                    'filter' => CHtml::activeDropDownList($model, 'status', CHtml::listData(TransectionStatus::model()->findAll(array('condition' => 'user_view=1 AND transection_type=5', "order" => "id")), 'status_id', 'status_title'), array('empty' => 'All', 'class' => 'select2')),
                                    'htmlOptions' => array('style' => "text-align:left;width:100px"),
                                ),
                                array(
                                    'header' => 'Actions',
                                    'class' => 'CButtonColumn',
                                    'htmlOptions' => array('style' => "text-align:left;width:125px;", 'class' => ''),
                                    'template' => '{view} {update} {delete} {edit} {print} {rollback}',
                                    'buttons' => array(
                                        'view' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'url' => 'yii::app()->createUrl("invoice/view", array("id"=>$data["id"]))',
                                            'options' => array('class' => 'btn btn-xs btn-info fa fa-search', 'rel' => 'tooltip', 'data-original-title' => 'View'),
                                        ),
                                        'update' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'visible' => 'InvoiceParent::visibleActions($data->id)',
                                            'options' => array('class' => 'btn btn-xs btn-primary fa fa-pencil', 'rel' => 'tooltip', 'data-original-title' => 'Edit'),
                                        ),
                                        'rollback' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'visible' => 'InvoiceParent::visibleRollbackActions($data->id)',
                                            'url' => 'yii::app()->createUrl("invoice/rollback", array("id"=>$data["id"]))',
                                            'options' => array('class' => 'btn btn-xs btn-warning fa fa-edit', 'rel' => 'tooltip', 'data-original-title' => 'Rollback'),
                                        ),
                                        'delete' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'visible' => 'InvoiceParent::visibleActions($data->id)',
                                            'url' => 'yii::app()->createUrl("invoice/remove", array("id"=>$data["id"]))',
                                            'options' => array('class' => 'btn btn-xs btn-danger fa fa-trash-o', 'rel' => 'tooltip', 'data-original-title' => 'Delete'),
                                        ),
                                        'edit' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'visible' => 'InvoiceParent::visibleActionEdit($data->id)',
                                            'url' => 'yii::app()->createUrl("invoice/edit", array("id"=>$data["id"]))',
                                            'options' => array('class' => 'btn btn-xs btn-warning fa fa-edit', 'rel' => 'tooltip', 'data-original-title' => 'Special Edit'),
                                        ),
                                        'print' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'url' => 'yii::app()->createUrl("invoice/print", array("id"=>$data["id"]))',
                                            'options' => array('class' => 'btn btn-xs btn-primary fa fa-print', 'rel' => 'tooltip', 'data-original-title' => 'Print', 'target' => '_blank'),
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