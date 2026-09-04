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
</style>
<?php $this->pageTitle = 'Stock Issue'; ?>
<div class="row" style="margin-bottom:10px;font-size: 14px;">
    <div style="float:left; width:150px;">
        <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/img/rishilpi_logo.png', 'Logo', array('alt' => 'Logo', 'class' => '', 'title' => '', 'style' => '')); ?>
    </div>
    <div style="float:left; width:250px;margin-top:25px;">
        <div style="font-size: 16px;">
            <?php echo Yii::app()->params['topTag']; ?><br />
            <?php echo Yii::app()->params['adminName']; ?><br />
            <?php echo Yii::app()->params['bottomTag']; ?>
        </div>
    </div>   
</div>
<div class="clearfix"></div>
<div>
    <div style="float:left; width:300px;">
        <h5>STOCK ISSUE</h5>
    </div>
    <div style="float:left;">
        ISSUE No.: <?php echo $parent->issue_number; ?><br />
        DATE: <?php echo User::get_date($parent->issue_date); ?>
    </div>   
</div>
<div class="clearfix" style="margin-top:10px;"></div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'stock-issue-grid',
    'dataProvider' => $model->searchIssue(@$_REQUEST['id']),
    'afterAjaxUpdate' => 'reloadPageSetUp',
    'htmlOptions' => array('class' => ''),
    'itemsCssClass' => 'table table-hover font-size',
    'template' => '{items}',
    'enableSorting' => false,
    'columns' => array(
        array(
            'name' => 'item',
            'value' => 'Product::getItemName($data->item)',
            'htmlOptions' => array('style' => "text-align:left;"),
            'footer' => 'TOTAL',
            'footerHtmlOptions' => array('class' => 'text-left text-bold-cus'),
        ),
//        array(
//            'name' => 'reference',
//            'value' => 'StockIssueParent::getReferenceRequisitionNo($data->reference)',
//            'htmlOptions' => array('style' => ''),
//        ),
//        array(
//            'name' => 'store',
//            'value' => 'Store::get_store($data->store)',
//            'htmlOptions' => array('style' => "text-align:left;"),
//        ),
        array(
            'name' => 'batch',
            'value' => 'Batch::getBatch($data->batch)',
            'htmlOptions' => array('style' => "text-align:left;"),
        ),
        array(
            'name' => 'quantity',
            'value' => 'Product::number_format($data->quantity,2)',
            'htmlOptions' => array('style' => "text-align:right;"),
            'headerHtmlOptions' => array('style' => "text-align:right;"),
        ),
        array(
            'name' => 'rate',
            'value' => 'Product::number_format_currency($data->rate,2,Yii::app()->session->get(\'currency\'))',
            'htmlOptions' => array('style' => "text-align:right;"),
            'headerHtmlOptions' => array('style' => "text-align:right;"),
        ),
        array(
            'name' => 'amount',
            'value' => 'Product::number_format_currency($data->amount,2,Yii::app()->session->get(\'currency\'))',
            'htmlOptions' => array('style' => "text-align:right;"),
            'headerHtmlOptions' => array('style' => "text-align:right;"),
            'footer' => $model->getTotalFooter($model->searchIssue(@$_REQUEST['id'])->getData(), 'amount'),
            'footerHtmlOptions' => array('class' => 'text-right text-bold-cus'),
        ),
    ),
));
?>
<div style="margin-top:10px;border: 1px solid #999; padding: 5px; text-align: right; font-size: 14px; text-transform: uppercase;">   
    Grand Total: <?php echo Product::number_format_currency(StockIssue::getTotalAmount($parent->id), 2, Yii::app()->session->get('currency')); ?>
</div>
<div class="invoice-footer" style="margin-top: 20px;">
    <div class="row">
        <div class="col-sm-12">
            <div class="payment-methods">
                <?php
                if (!empty($parent->comments)) {
                    echo '<h5>Comments</h5>';
                    echo '<p>' . $parent->comments . '</p>';
                }
                ?>               
            </div>
        </div>     
    </div>
    <p style="color:#999;"><?php echo Yii::app()->params['print_note']; ?></p>
</div>