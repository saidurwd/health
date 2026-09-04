<?php
/* @var $this PurchaseReceiveController */
/* @var $model PurchaseReceive */
$this->pageTitle = 'Purchase Receives';
$this->breadcrumbs = array(
    'Purchase' => array('admin'),
    'Receives',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#purchase-receive-grid').yiiGridView('update', {
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
                Receive
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
                    <h2>Purchase Receive</h2>
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
                            'id' => 'purchase-receive-parent-grid',
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
                                    'header' => "References",
                                    'type' => 'raw',
                                    'value' => 'PurchaseReceive::getReferences($data->id)',
                                    'htmlOptions' => array('class' => "text-left"),
                                ),
                                array(
                                    'name' => 'receive_number',
                                    'type' => 'raw',
                                    'value' => '$data->receive_number',
                                    'filter' => CHtml::activeTextField($model, 'receive_number', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'name' => 'receive_date',
                                    'type' => 'raw',
                                    'value' => 'User::get_date($data->receive_date)',
                                    'filter' => CHtml::activeTextField($model, 'receive_date', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')),
                                    'htmlOptions' => array('style' => "text-align:left;width:150px;"),
                                ),
                                array(
                                    'type' => 'raw',
                                    'header' => '# of Items',
                                    'value' => 'PurchaseReceive::getNumberOfItems($data->id)',
                                    'htmlOptions' => array('style' => "text-align:center;width:100px;"),
                                ),
                                array(
                                    'type' => 'raw',
                                    'header' => 'Buy Price',
                                    'value' => 'PurchaseReceive::getTotalAmountBuy($data->id)',
                                    'htmlOptions' => array('style' => "text-align:right;width:120px;"),
                                ),
                                array(
                                    'type' => 'raw',
                                    'header' => 'Sale Price',
                                    'value' => 'PurchaseReceive::getTotalAmount($data->id)',
                                    'htmlOptions' => array('style' => "text-align:right;width:120px;"),
                                ),
                                array(
                                    'name' => 'receive_by',
                                    'type' => 'raw',
                                    'value' => 'User::get_full_name($data->receive_by)',
                                    'filter' => CHtml::activeDropDownList($model, 'receive_by', CHtml::listData(User::model()->findAll(array('condition' => '', 'order' => 'full_name')), 'id', 'full_name'), array('empty' => 'All', 'class' => 'select2')),
                                    'htmlOptions' => array('style' => "text-align:left;width:200px;"),
                                ),
                                array(
                                    'name' => 'supplier',
                                    'type' => 'raw',
                                    'value' => 'Vendor::get_vendor($data->supplier)',
                                    'filter' => CHtml::activeDropDownList($model, 'supplier', CHtml::listData(Vendor::model()->findAll(array('condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'All', 'class' => 'select2')),
                                    'htmlOptions' => array('style' => "text-align:left;width:200px;"),
                                ),
                                array(
                                    'name' => 'status',
                                    'type' => 'raw',
                                    'value' => 'TransectionStatus::getStatus($data->status,2)',
                                    'filter' => CHtml::activeDropDownList($model, 'status', CHtml::listData(TransectionStatus::model()->findAll(array('condition' => 'user_view=1 AND transection_type=2', "order" => "id")), 'status_id', 'status_title'), array('empty' => 'All', 'class' => 'select2')),
                                    'htmlOptions' => array('style' => "text-align:center;width:100px"),
                                ),
                                array(
                                    'header' => 'Actions',
                                    'class' => 'CButtonColumn',
                                    'htmlOptions' => array('style' => "text-align:left;width:125px;", 'class' => ''),
                                    'template' => '{view} {update} {delete} {edit} {print}',
                                    'buttons' => array(
                                        'update' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'visible' => 'PurchaseReceiveParent::visibleActions($data->id)',
                                            'options' => array('class' => 'btn btn-xs btn-primary fa fa-pencil', 'rel' => 'tooltip', 'data-original-title' => 'Edit'),
                                        ),
                                        'view' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'options' => array('class' => 'btn btn-xs btn-info fa fa-search', 'rel' => 'tooltip', 'data-original-title' => 'View'),
                                        ),
                                        'delete' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'visible' => 'PurchaseReceiveParent::visibleActions($data->id)',
                                            'url' => 'yii::app()->createUrl("purchaseReceive/remove", array("id"=>$data["id"]))',
                                            'options' => array('class' => 'btn btn-xs btn-danger fa fa-trash-o', 'rel' => 'tooltip', 'data-original-title' => 'Delete'),
                                        ),
                                        'edit' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'visible' => 'PurchaseReceiveParent::visibleActionEdit($data->id)',
                                            'url' => 'yii::app()->createUrl("purchaseReceive/edit", array("id"=>$data["id"]))',
                                            'options' => array('class' => 'btn btn-xs btn-warning fa fa-edit', 'rel' => 'tooltip', 'data-original-title' => 'Special Edit'),
                                        ),
                                        'print' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'url' => 'yii::app()->createUrl("purchaseReceive/print", array("id"=>$data["id"]))',
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