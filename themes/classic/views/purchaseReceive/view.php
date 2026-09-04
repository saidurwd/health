<?php
/* @var $this PurchaseReceiveController */
/* @var $model PurchaseReceive */
$this->pageTitle = 'Purchase Receive Details';
$this->breadcrumbs = array(
    'Purchase Receives' => array('admin'),
    $parent->receive_number,
);
$documents = StoreDocument::model()->findAll(array('condition' => 'transection_type=2 AND transection_id=' . (int) $parent->id));
$total_documents = count($documents);
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-shopping-cart fa-fw "></i> 
            Purchase
            <span>>
                Receive Details
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">   
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-home"></i></span>MANAGE', array('admin'), array('class' => 'btn btn-labeled btn-primary')); ?>
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-plus"></i></span> NEW', array('create'), array('class' => 'btn btn-labeled btn-primary')); ?>       
        <?php
        if ($parent->status == 0) {
            echo CHtml::link('<span class="btn-label"><i class="fa fa-pencil"></i></span>EDIT', array('update', 'id' => $parent->id), array('class' => 'btn btn-labeled btn-primary'));
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
                    <h2><strong>Receive #: </strong><?php echo $parent->receive_number; ?>, <strong>Receive Date: </strong><?php echo User::get_date_time($parent->receive_date); ?>, <strong>Receive By: </strong><?php echo User::get_full_name($parent->receive_by); ?></h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'purchase-receive-grid',
                            'dataProvider' => $model->searchReceive($parent->id),
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
                                    'name' => 'reference',
                                    'value' => 'PurchaseReceiveParent::getReferenceOrderNo($data->reference)',
                                    'htmlOptions' => array('style' => "width:150px;"),
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
                                    'name' => 'total_amount',
                                    'value' => 'Product::number_format_currency($data->total_amount,2,Yii::app()->session->get(\'currency\'))',
                                    'htmlOptions' => array('style' => "text-align:right;width:150px;"),
                                ),
                                array(
                                    'name' => 'buy_rate',
                                    'value' => 'Product::number_format_currency($data->buy_rate,2,Yii::app()->session->get(\'currency\'))',
                                    'type' => 'raw',
                                    'htmlOptions' => array('style' => "text-align:right;width:120px;"),
                                ),
                                array(
                                    'name' => 'buy_amount',
                                    'value' => 'Product::number_format_currency($data->buy_amount,2,Yii::app()->session->get(\'currency\'))',
                                    'htmlOptions' => array('style' => "text-align:right;width:150px;"),
                                ),
                                array(
                                    'name' => 'title',
                                    'header' => 'Lot No.',
                                    'value' => 'PurchaseReceive::getBatchData($data->batch,"title")',
                                    'type' => 'raw',
                                    'htmlOptions' => array('style' => "width:120px;"),
                                ),
                                array(
                                    'name' => 'expiry',
                                    'header' => 'Expiry',
                                    'value' => 'PurchaseReceive::getBatchData($data->batch,"expiry")',
                                    'type' => 'raw',
                                    'htmlOptions' => array('style' => "text-align:left;width:120px;"),
                                ),
                                array(
                                    'header' => 'Files',
                                    'type' => 'raw',
                                    'value' => 'PurchaseReceive::fileDownload($data->id)',
                                    'htmlOptions' => array('style' => "text-align:center;width:120px;"),
                                ),
                            ),
                        ));
                        ?>
                        <?php
                        if ($total_documents > 0) {
                            echo '<h3>Documents</h3>';
                            echo '<ul>';
                            foreach ($documents as $key => $value) {
                                echo '<li>' . CHtml::link($value['doc_title'], array('purchaseReceive/download', 'id' => $value['id'])) . '</li>';
                            }
                            echo '</ul>';
                        }
                        ?>
                        <h3>Comments</h3>
                        <p><?php echo $parent->comments; ?></p>
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