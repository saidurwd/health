<?php
/* @var $this PurchaseOrderController */
/* @var $model PurchaseOrder */
$this->pageTitle = 'Purchase Orders';
$this->breadcrumbs = array(
    'Purchase' => array('admin'),
    'Orders',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#purchase-order-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#datepicker_act_start_date').datepicker();
    $('#datepicker_act_end_date').datepicker();
}
function reloadPageSetUp() {
        pageSetUp();
    }
", CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-shopping-cart fa-fw "></i> 
            Purchase
            <span>>
                Orders
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
                    <h2>Purchase Orders</h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body">
                        <div class="search-form" style="display:none">
                            <?php
                            $this->renderPartial('_search', array(
                                'model' => $model,
                            ));
                            ?>
                        </div><!-- search-form -->

                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'purchase-order-parent-grid',
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
                                    'name' => 'order_number',
                                    'type' => 'raw',
                                    'value' => '$data->order_number',
                                    'filter' => CHtml::activeTextField($model, 'order_number', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'name' => 'order_date',
                                    'type' => 'raw',
                                    'value' => 'User::get_date($data->order_date)',
                                    'filter' => CHtml::activeTextField($model, 'order_date', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')),
                                    'htmlOptions' => array('style' => "text-align:left;width:150px;"),
                                ),
                                array(
                                    'type' => 'raw',
                                    'header' => "# of Items",
                                    'value' => 'PurchaseOrder::getNumberOfItems($data->id)',
                                    'htmlOptions' => array('style' => "text-align:center;width:100px;"),
                                ),
                                array(
                                    'name' => 'order_by',
                                    'type' => 'raw',
                                    'value' => 'User::get_full_name($data->order_by)',
                                    'filter' => CHtml::activeDropDownList($model, 'order_by', CHtml::listData(User::model()->findAll(array('condition' => '', 'order' => 'full_name')), 'id', 'full_name'), array('empty' => 'All', 'class' => 'select2')),
                                    'htmlOptions' => array('style' => "text-align:left;width:200px;"),
                                ),
                                array(
                                    'name' => 'supplier',
                                    'type' => 'raw',
                                    'value' => 'Vendor::getData($data->supplier,"title")',
                                    'filter' => CHtml::activeDropDownList($model, 'supplier', CHtml::listData(Vendor::model()->findAll(array('condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'All', 'class' => 'select2')),
                                    'htmlOptions' => array('style' => "text-align:left;width:200px;"),
                                ),
                                array(
                                    'name' => 'status',
                                    'type' => 'raw',
                                    'value' => 'TransectionStatus::getStatus($data->status,1)',
                                    'filter' => CHtml::activeDropDownList($model, 'status', CHtml::listData(TransectionStatus::model()->findAll(array('condition' => 'user_view=1 AND transection_type=1', "order" => "id")), 'status_id', 'status_title'), array('empty' => 'All', 'class' => 'select2')),
                                    'htmlOptions' => array('style' => "text-align:left;width:100px"),
                                ),
                                array(
                                    'header' => 'Actions',
                                    'class' => 'CButtonColumn',
                                    'htmlOptions' => array('style' => "text-align:left;width:125px;", 'class' => ''),
                                    'template' => '{view} {update} {delete} {print}',
                                    'buttons' => array(
                                        'view' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'url' => 'yii::app()->createUrl("purchaseOrder/view", array("id"=>$data["id"]))',
                                            'options' => array('class' => 'btn btn-xs btn-info fa fa-search', 'rel' => 'tooltip', 'data-original-title' => 'View'),
                                        ),
                                        'update' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'visible' => 'PurchaseOrderParent::visibleActions($data->id)',
                                            'options' => array('class' => 'btn btn-xs btn-primary fa fa-pencil', 'rel' => 'tooltip', 'data-original-title' => 'Edit'),
                                        ),
                                        'delete' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'visible' => 'PurchaseOrderParent::visibleActions($data->id)',
                                            'url' => 'yii::app()->createUrl("purchaseOrder/remove", array("id"=>$data["id"]))',
                                            'options' => array('class' => 'btn btn-xs btn-danger fa fa-trash-o', 'rel' => 'tooltip', 'data-original-title' => 'Delete'),
                                        ),
                                        'print' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'url' => 'yii::app()->createUrl("purchaseOrder/print", array("id"=>$data["id"]))',
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