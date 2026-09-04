<?php
/* @var $this StockIssueController */
/* @var $model StockIssue */
$this->pageTitle = 'New Stock Issue';
$this->breadcrumbs = array(
    'Stock Issues' => array('admin'),
    'Create',
);
Yii::app()->clientScript->registerScript('chained', '
        $("#StockIssue_store").chained("#StockIssue_item");
        $("#StockIssue_batch").chained("#StockIssue_item, #StockIssue_store");
    ', CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-shopping-cart fa-fw "></i> 
            Stock Issues
            <span>>
                New Issue
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">   
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-home"></i></span>MANAGE', array('admin'), array('class' => 'btn btn-labeled btn-primary')); ?>
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-download"></i></span> LOAD FROM SR', 'javascript:void(0)', array('onclick' => 'renderStockRequisition();', 'class' => 'btn btn-labeled btn-primary')); ?>        
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
                    <h2>New Issue</h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php echo $this->renderPartial('_form', array('model' => $model, 'modelGrid' => $modelGrid, 'modelParent' => $modelParent)); ?>
                        <?php $this->renderPartial('_loadsr', array('loadsr' => $loadsr)); ?>
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