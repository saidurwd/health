<?php
/* @var $this PurchaseOrderController */
/* @var $model PurchaseOrder */
$this->pageTitle = 'Purchase Order Details';
$this->breadcrumbs = array(
    'Purchase Orders' => array('admin'),
    PurchaseOrderParent::getData(@$_REQUEST['id'], "order_number"),
);
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-shopping-cart fa-fw "></i> 
            Purchase
            <span>>
                Order Details
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">   
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-home"></i></span>MANAGE', array('admin'), array('class' => 'btn btn-labeled btn-primary')); ?>
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-plus"></i></span> NEW', array('create'), array('class' => 'btn btn-labeled btn-primary')); ?>       
        <?php //echo CHtml::link('<span class="btn-label"><i class="fa fa-pencil"></i></span>EDIT', array('update', 'id' => $_REQUEST['id']), array('class' => 'btn btn-labeled btn-primary')); ?>
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
                    <h2><strong>Order #: </strong><?php echo PurchaseOrderParent::getData(@$_REQUEST['id'], "order_number"); ?>, <strong>Order Date: </strong><?php echo User::get_date_time(PurchaseOrderParent::getData(@$_REQUEST['id'], "order_date")); ?>, <strong>Order By: </strong><?php echo User::get_full_name(PurchaseOrderParent::getData(@$_REQUEST['id'], "order_by")); ?></h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'purchase-order-grid',
                            'dataProvider' => $model->searchOrder(@$_REQUEST['id']),
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
                                    'header' => 'Category',
                                    'value' => 'ProductCategory::getData(Product::getData($data->item,"category"), "title")',
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),                                
                                array(
                                    'name' => 'item',
                                    'value' => 'Product::getItemName($data->item)',
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'header' => 'Catalogue',
                                    'value' => 'Product::getValues($data->item,"product_code")',
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'name' => 'quantity',
                                    'value' => 'Product::number_format($data->quantity,2) ." ". Product::getItemUOM($data->item)',
                                    'htmlOptions' => array('style' => "text-align:right;width:100px;"),
                                ),
                            ),
                        ));
                        ?>
                        <h3>Comments</h3>
                        <p><?php echo PurchaseOrderParent::getData(@$_REQUEST['id'], 'comments'); ?></p>
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