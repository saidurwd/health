<script type="text/javascript">
<!--
    window.print();
//-->
</script>
<?php $this->pageTitle = 'Stock Requisition'; ?>
<div class="widget-body no-padding">
    <div class="padding-10">
        <div class="pull-left">
            <?php echo '<div class="company-header">'.Yii::app()->params['adminName'].'</div>'; ?>
            <?php echo Yii::app()->params['adminAddress']; ?>
        </div>
        <div class="pull-right">
            <h1 class="font-400">Stock Requisition</h1>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-sm-9">                               
            </div>
            <div class="col-sm-3">                
                <div>
                    <div>
                        <strong>STATUS :</strong>
                        <span class="pull-right"> <?php echo TransectionStatus::getStatus($parent->status, 3); ?> </span>
                    </div>
                </div>
                <div>
                    <div>
                        <strong>REQUISITION NO :</strong>
                        <span class="pull-right"> <?php echo $parent->requisition_number; ?> </span>
                    </div>
                </div>
                <div>
                    <div class="font-md">
                        <strong>REQUISITION DATE :</strong>
                        <span class="pull-right"> <i class="fa fa-calendar"></i> <?php echo User::get_date($parent->requisition_date); ?> </span>
                    </div>
                </div>
                <br />
                <div class="well well-sm  bg-color-darken txt-color-white no-border">
                    <div class="fa-lg">
                        Total Amount :
                        <span class="pull-right"> <?php echo StockRequisition::getTotalAmount($parent->id); ?> </span>
                    </div>
                </div>
                <br />
            </div>
        </div>
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'stock-requisition-grid',
            'dataProvider' => $model->searchRequisition(@$_REQUEST['id']),
            'afterAjaxUpdate' => 'reloadPageSetUp',
            'htmlOptions' => array('class' => ''),
            'itemsCssClass' => 'table table-hover',
            'template' => '{items}',
            'enableSorting' => false,
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
                    'value' => 'Product::number_format($data->quantity,2)',
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
        <div class="invoice-footer">
            <div class="row">
                <div class="col-sm-7">
                    <div class="payment-methods">
                        <h5>Comments</h5>
                        <p><?php echo $parent->comments; ?></p>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="invoice-sum-total pull-right">
                        <h3><strong>Total: <span class="text-success"><?php echo StockRequisition::getTotalAmount($parent->id); ?></span></strong></h3>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <p class="note">Generated on <?php echo date('l jS \of F Y h:i:s A'); ?></p>
                </div>
            </div>
        </div>
    </div>
</div>