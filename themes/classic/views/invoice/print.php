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
<?php $this->pageTitle = 'Invoice'; ?>
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
        PATIENT: <?php echo Patient::getData($parent->patient, 'name'); ?><br />
        PAYMENT: <?php echo $parent->payment_status; ?><br />
        CATEGORY: <?php echo PatientCategoryNew::getData($parent->patient_category_new, 'alias'); ?>
    </div>
    <div style="float:left;">
        Sub CATEGORY: <?php echo PatientCategory::getData($parent->patient_category, 'alias'); ?><br />
        INVOICE#: <?php echo $parent->invoice_number; ?><br />
        DATE: <?php echo User::get_date($parent->invoice_date); ?>
    </div>   
</div>
<div class="clearfix" style="margin-top:10px;"></div>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'invoice-grid',
    'dataProvider' => $model->searchInvoice(@$_REQUEST['id']),
    'afterAjaxUpdate' => 'reloadPageSetUp',
    'htmlOptions' => array('class' => ''),
    'itemsCssClass' => 'table table-hover font-size',
    'template' => '{items}',
    'enableSorting' => false,
    'columns' => array(
        array(
            'header' => 'Product/Service',
            'name' => 'item',
            'type' => 'html',
//            'value' => 'Product::getItemName($data->item).Service::getData($data->service,"title")',
            'value' => 'Product::getItemName($data->item).Service::get_full_path($data->service)',
            'htmlOptions' => array('style' => "text-align:left;"),
            'footer' => 'TOTAL',
            'footerHtmlOptions' => array('class' => 'text-left text-bold-cus'),
        ),
        array(
            'name' => 'note',
            'value' => '$data->note',
            'type' => 'raw',
            'htmlOptions' => array('style' => "text-align:left;"),
        ),
        array(
            'name' => 'quantity',
            'value' => 'Product::number_format($data->quantity,2)',
            'htmlOptions' => array('style' => "text-align:center;width:100px;"),
            'headerHtmlOptions' => array('style' => "text-align:center;width:100px;"),
        ),
        array(
            'name' => 'rate',
            'value' => 'Product::number_format_currency($data->rate,2,Yii::app()->session->get(\'currency\'))',
            'htmlOptions' => array('style' => "text-align:right;width:100px;"),
            'headerHtmlOptions' => array('style' => "text-align:right;width:100px;"),
        ),
        array(
            'name' => 'discount',
            'value' => 'Product::number_format_currency($data->discount,2,Yii::app()->session->get(\'currency\'))',
            'htmlOptions' => array('style' => "text-align:right;width:100px;"),
            'headerHtmlOptions' => array('style' => "text-align:right;width:100px;"),
            'footer' => $model->getTotalFooter($model->searchInvoice(@$_REQUEST['id'])->getData(), 'discount'),
            'footerHtmlOptions' => array('class' => 'text-right text-bold-cus'),
        ),
        array(
            'name' => 'amount',
            'value' => 'Product::number_format_currency($data->amount,2,Yii::app()->session->get(\'currency\'))',
            'htmlOptions' => array('style' => "text-align:right;width:150px;"),
            'headerHtmlOptions' => array('style' => "text-align:right;width:150px;"),
            'footer' => $model->getTotalFooter($model->searchInvoice(@$_REQUEST['id'])->getData(), 'amount'),
            'footerHtmlOptions' => array('class' => 'text-right text-bold-cus'),
        ),
    ),
));
?>       
<div style="margin-top:10px;border: 1px solid #999; padding: 5px; text-align: right; font-size: 14px; text-transform: uppercase;">   
    Grand Total: <?php echo Product::number_format_currency_round(Invoice::getTotalAmount($parent->id), 0, Yii::app()->session->get('currency')); ?>
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