<script type="text/javascript">
<!--
    window.print();
//-->
</script>
<?php $this->pageTitle = 'Purchase Order'; ?>
<div class="widget-body no-padding">
    <div class="padding-10">
        <div class="pull-left">
            <?php echo '<div class="company-header">'.Yii::app()->params['adminName'].'</div>'; ?>
            <?php echo Yii::app()->params['adminAddress']; ?>
        </div>
        <div class="pull-right">
            <h1 class="font-400">Purchase Order</h1>
        </div>
        <div class="clearfix"></div>
        <div class="row">
            <div class="col-sm-9">
                <?php echo Vendor::get_address_details($parent->supplier); ?>                
            </div>
            <div class="col-sm-3">                
                <div>
                    <div>
                        <strong>STATUS :</strong>
                        <span class="pull-right"> <?php echo TransectionStatus::getStatus($parent->status, 1); ?> </span>
                    </div>
                </div>
                <div>
                    <div>
                        <strong>ORDER NO :</strong>
                        <span class="pull-right"> <?php echo $parent->order_number; ?> </span>
                    </div>
                </div>
                <div>
                    <div class="font-md">
                        <strong>ORDER DATE :</strong>
                        <span class="pull-right"> <i class="fa fa-calendar"></i> <?php echo User::get_date($parent->order_date); ?> </span>
                    </div>
                </div>
                <br>
                <br>
            </div>
        </div>
        <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'purchase-order-grid',
            'dataProvider' => $model->searchOrder(@$_REQUEST['id']),
            'htmlOptions' => array('class' => ''),
            'itemsCssClass' => 'table table-hover',
            'enableSorting' => false,
            'template' => '{items}',
            'columns' => array(
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
                    'value' => 'Product::number_format($data->quantity,2)',
                    'htmlOptions' => array('style' => "text-align:right;width:100px;"),
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