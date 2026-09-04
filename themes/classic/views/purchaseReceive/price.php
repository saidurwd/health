<?php
/* @var $this PurchaseReceiveController */
/* @var $model PurchaseReceive */
$this->pageTitle = 'Buy & Sale Price Comparison';
$this->breadcrumbs = array(
    'Buy & Sale Price Comparison' => array('price')
);
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#purchase-receive-price-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
Yii::app()->clientScript->registerScript('re-install-date-picker', "
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
                Buy & Sale Price Comparison
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">   

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
                    <h2>Buy & Sale Price Comparison</h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'purchase-receive-price-grid',
                            'dataProvider' => $model->searchReceivePrice(),
                            'filter' => $model,
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
                                    'filter' => CHtml::activeDropDownList($model, 'item', CHtml::listData(Product::model()->findAll(array('condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'All', 'class' => 'select2')),
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'name' => 'buy_rate',
                                    'value' => 'Product::number_format_currency($data->buy_rate,2,Yii::app()->session->get(\'currency\'))',
                                    'filter' => CHtml::activeTextField($model, 'buy_rate', array('class' => 'form-control')),
                                    'type' => 'raw',
                                    'htmlOptions' => array('style' => "text-align:right;"),
                                ),
                                array(
                                    'name' => 'rate',
                                    'value' => 'Product::number_format_currency($data->rate,2,Yii::app()->session->get(\'currency\'))',
                                    'filter' => CHtml::activeTextField($model, 'rate', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:right;"),
                                ),
                                array(
                                    'name' => 'batch',
                                    'header' => 'Lot No.',
                                    'value' => 'Batch::getBatch($data->batch)',
                                    'filter' => CHtml::activeTextField($model, 'batch', array('class' => 'form-control')),
                                    'type' => 'raw',
                                    'htmlOptions' => array(),
                                ),
                                array(
                                    'name' => 'batch',
                                    'header' => 'Expiry',
                                    'value' => 'User::get_date(Batch::getData($data->batch,"expiry"))',
                                    'filter' => CHtml::activeTextField($model, 'batch', array('class' => 'form-control')),
                                    'type' => 'raw',
                                    'htmlOptions' => array(),
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