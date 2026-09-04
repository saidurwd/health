<?php
/* @var $this StockRequisitionController */
/* @var $model StockRequisition */
$this->pageTitle = 'Stock Requisition Details';
$this->breadcrumbs = array(
    'Stock Requisition' => array('admin'),
    $parent->requisition_number,
);
$array = StockRequisition::model()->findAll(array('condition' => 'parent=' . (int) $parent->id . ' AND converted=1'));
$total = count($array);
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-shopping-cart fa-fw "></i> 
            Stock
            <span>>
                Requisition Details
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">   
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-home"></i></span>MANAGE', array('admin'), array('class' => 'btn btn-labeled btn-primary')); ?>
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-plus"></i></span> NEW', array('create'), array('class' => 'btn btn-labeled btn-primary')); ?>       
        <?php
        if ($parent->status == 0) {
            echo CHtml::link('<span class="btn-label"><i class="fa fa-pencil"></i></span>EDIT', array('update', 'id' => @$_REQUEST['id']), array('class' => 'btn btn-labeled btn-primary'));
        }
        if ($parent->status == 1 && $total == 0) {
            echo CHtml::link('<span class="btn-label"><i class="fa fa-arrows-alt"></i></span> MAKE ME ISSUE', array('convertissue', 'id' => @$_REQUEST['id']), array('class' => 'btn btn-labeled btn-success'));
        }
        ?>
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
                    <h2><strong>Requisition #: </strong><?php echo StockRequisitionParent::getData(@$_REQUEST['id'], "requisition_number"); ?>, <strong>Requisition Date: </strong><?php echo User::get_date_time(StockRequisitionParent::getData(@$_REQUEST['id'], "requisition_date")); ?>, <strong>Order By: </strong><?php echo User::get_full_name(StockRequisitionParent::getData(@$_REQUEST['id'], "requisition_by")); ?></h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'stock-requisition-grid',
                            'dataProvider' => $model->searchRequisition(@$_REQUEST['id']),
                            'afterAjaxUpdate' => 'reloadPageSetUp',
                            'htmlOptions' => array('class' => ''),
                            'itemsCssClass' => 'table table-bordered table-striped table-condensed table-hover',
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
                                    'name' => 'item',
                                    'value' => 'Product::getItemName($data->item)',
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'name' => 'store',
                                    'value' => 'Store::get_store($data->store)',
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'name' => 'batch',
                                    'value' => 'Batch::getBatch($data->batch)',
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'name' => 'quantity',
                                    'value' => 'Product::number_format($data->quantity,2)." ".Product::getItemUOM($data->item)',
                                    'htmlOptions' => array('style' => "text-align:right;width:100px;"),
                                ),
                                array(
                                    'name' => 'rate',
                                    'value' => 'Product::number_format_currency($data->rate,2,Yii::app()->session->get(\'currency\'))',
                                    'htmlOptions' => array('style' => "text-align:right;width:100px;"),
                                ),
                                array(
                                    'name' => 'amount',
                                    'value' => 'Product::number_format_currency($data->amount,2,Yii::app()->session->get(\'currency\'))',
                                    'htmlOptions' => array('style' => "text-align:right;width:150px;"),
                                ),
                            ),
                        ));
                        ?>
                        <h3>Comments</h3>
                        <p><?php echo StockRequisitionParent::getData(@$_REQUEST['id'], 'comments'); ?></p>
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
