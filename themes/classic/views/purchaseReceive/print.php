<?php $this->pageTitle = 'Purchase Receive'; ?>
<div class="row" style="margin-bottom:10px;font-size: 14px;">
    <div style="float:left; width:150px;">
        <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/img/rishilpi_logo.png', 'Logo', array('alt' => 'Logo', 'class' => '', 'title' => '', 'style' => '')); ?>
    </div>
    <div style="float:left;margin-top:25px;">
        <div style="font-size: 16px;">
            <?php echo Yii::app()->params['topTag']; ?><br />
            <?php echo Yii::app()->params['adminName']; ?><br />
            <?php echo Yii::app()->params['bottomTag']; ?>
        </div>
    </div>   
</div>
<div class="clearfix"></div>
<div class="widget-body no-padding">
    <div class="padding-10">
        <h5>Purchase Receive</h5>
        <div class="row">
            <div class="col-sm-9">
                <?php echo Vendor::get_address_details($parent->supplier); ?>                
            </div>
            <div class="col-sm-3">                
                <div>
                    <div>
                        <strong>STATUS :</strong>
                        <span class="pull-right"> <?php echo TransectionStatus::getStatus($parent->status, 2); ?> </span>
                    </div>
                </div>
                <div>
                    <div>
                        <strong>RECEIVE NO :</strong>
                        <span class="pull-right"> <?php echo $parent->receive_number; ?> </span>
                    </div>
                </div>
                <div>
                    <div class="font-md">
                        <strong>RECEIVE DATE :</strong>
                        <span class="pull-right"> <i class="fa fa-calendar"></i> <?php echo User::get_date($parent->receive_date); ?> </span>
                    </div>
                </div>
                <br />
            </div>
        </div>
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'purchase-receive-grid',
            'dataProvider' => $model->searchReceive(@$_REQUEST['id']),
            'afterAjaxUpdate' => 'reloadPageSetUp',
            'htmlOptions' => array('class' => ''),
            'itemsCssClass' => 'table table-bordered table-striped table-hover',
            'template' => '{items}',
            'enableSorting' => false,
            'columns' => array(
                array(
                    'name' => 'item',
                    'value' => 'Product::getItemName($data->item)',
                    'htmlOptions' => array('style' => "text-align:left;"),
                ),
                array(
                    'name' => 'reference',
                    'value' => 'PurchaseReceiveParent::getReferenceOrderNo($data->reference)',
                    'htmlOptions' => array(),
                ),
                array(
                    'name' => 'quantity',
                    'value' => 'Product::number_format($data->quantity,2)',
                    'htmlOptions' => array('class'=>'text-right'),
                ),
                array(
                    'header'=>'Expiry',
                    'name' => 'batch',
                    'value' => 'User::get_date(Batch::getExpiryDate($data->batch))',
                    'htmlOptions' => array(),
                ),
                array(
                    'name' => 'buy_rate',
                    'value' => 'Product::number_format_currency($data->buy_rate,2,Yii::app()->session->get(\'currency\'))',
                    'htmlOptions' => array('class'=>'text-right'),
                ),
                array(
                    'name' => 'buy_amount',
                    'value' => 'Product::number_format_currency($data->buy_amount,2,Yii::app()->session->get(\'currency\'))',
                    'htmlOptions' => array('class'=>'text-right'),
                ),
                array(
                    'name' => 'rate',
                    'value' => 'Product::number_format_currency($data->rate,2,Yii::app()->session->get(\'currency\'))',
                    'htmlOptions' => array('class'=>'text-right'),
                ),                
                array(
                    'name' => 'total_amount',
                    'value' => 'Product::number_format_currency($data->total_amount,2,Yii::app()->session->get(\'currency\'))',
                    'htmlOptions' => array('class'=>'text-right'),
                ),                
            ),
        ));
        $documents = StoreDocument::model()->findAll(array('condition' => 'transection_type=2 AND transection_id=' . (int) $parent->id));
        $total_documents = count($documents);
        ?>  
        <div class="invoice-footer">
            <div class="row">
                <div class="col-sm-7">
                    <div class="payment-methods">
                        <?php
                        if ($total_documents > 0) {
                            echo '<h3>Documents</h3>';
                            echo '<ul>';
                            foreach ($documents as $key => $value) {
                                echo '<li>' . $value['doc_title'] . '</li>';
                            }
                            echo '</ul>';
                        }
                        ?>
                        <h5>Comments</h5>
                        <p><?php echo $parent->comments; ?></p>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="invoice-sum-total pull-right">
                        <h3><strong>Total Sale Amount <span class="text-success"><?php echo PurchaseReceive::getTotalAmount($parent->id); ?></span></strong>, <strong> Purchase Amount<span class="text-success"><?php echo PurchaseReceive::getTotalAmountBuy($parent->id); ?></span></strong></h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="invoice-footer space-top-10">
    <div class="row">
        <div class="col-sm-12 text-right">
            <p class="note"><?php echo Yii::app()->params['print_note']; ?></p>
        </div>
    </div>
</div>
<script type="text/javascript">
<!--
    window.print();
//-->
</script>
<style>
    body{
        font-size: 10px;
    }
    .font-size{
        font-size: 10px;
    }
	.text-right{
		text-align: right;
	}
</style>