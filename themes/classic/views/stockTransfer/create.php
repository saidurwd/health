<?php
/* @var $this StockTransferController */
/* @var $model StockTransfer */
$this->pageTitle = 'New Stock Transfer';
$this->breadcrumbs=array(
	'Stock Transfers'=>array('admin'),
	'Create',
);
Yii::app()->clientScript->registerScript('chained', '
        $("#StockTransfer_store_from").chained("#StockTransfer_item");
        $("#StockTransfer_batch").chained("#StockTransfer_item, #StockTransfer_store_from");
    ', CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-shopping-cart fa-fw "></i> 
            Stock
            <span>>
                New Transfer
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">   
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-home"></i></span>MANAGE', array('admin'), array('class' => 'btn btn-labeled btn-primary')); ?>        
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
                    <span class="widget-icon"> <i class="fa fa-plus"></i> </span>
                    <h2>New Transfer</h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php echo $this->renderPartial('_form', array('model' => $model, 'modelGrid' => $modelGrid, 'modelParent' => $modelParent, 'batch' => $batch)); ?>
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