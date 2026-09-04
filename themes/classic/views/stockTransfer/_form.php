<?php
/* @var $this StockTransferController */
/* @var $model StockTransfer */
/* @var $form CActiveForm */
?>

<?php
Yii::app()->clientScript->registerScript('reinstallDatePicker', "
    function reinstallDatePicker(id, data) {
        //$('[id^=batch_]').datepicker({dateFormat: 'yy-mm-dd'});
        $('.datepicker').datepicker({dateFormat: 'yy-mm-dd'});
    }    
");
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'stock-transfer-grid',
    'dataProvider' => $modelGrid->search(),
    'afterAjaxUpdate' => 'reinstallDatePicker',
    'htmlOptions' => array('class' => ''),
    'itemsCssClass' => 'table table-bordered table-striped table-hover',
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
            'name' => 'store_from',
            'value' => 'Store::get_store($data->store_from)',
            'type' => 'raw',
            'htmlOptions' => array('style' => "text-align:left;width:300px;"),
        ),
        array(
            'name' => 'store_to',
            'value' => 'Store::get_store($data->store_to)',
            'type' => 'raw',
            'htmlOptions' => array('style' => "text-align:left;width:300px;"),
        ),
        array(
            'name' => 'quantity',
            'value' => 'Product::number_format($data->quantity,2) ." ". Product::getItemUOM($data->item)',
            'type' => 'raw',
            'htmlOptions' => array('style' => "text-align:right;width:120px;"),
        ),
        array(
            'name' => 'rate',
            'value' => 'Product::number_format($data->rate,2)',
            'type' => 'raw',
            'htmlOptions' => array('style' => "text-align:right;width:120px;"),
        ),
        array(
            'name' => 'total_amount',
            'value' => 'Product::number_format_currency($data->total_amount,2,Yii::app()->session->get(\'currency\'))',
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
            'header' => 'Actions',
            'class' => 'CButtonColumn',
            'htmlOptions' => array('style' => "text-align:center;width:50px;", 'class' => ''),
            'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
            'template' => '{delete}',
            'buttons' => array(
                'delete' => array(
                    'label' => '',
                    'imageUrl' => '',
                    'options' => array('class' => 'btn btn-xs btn-danger fa fa-trash-o', 'rel' => 'tooltip', 'data-original-title' => 'Delete'),
                ),
            ),
        ),
    ),
));
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'stock-transfer-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'smart-form',
        'enctype' => 'multipart/form-data',
        'onsubmit' => "return false;", /* Disable normal form submit */
    //'onkeypress' => " if(event.keyCode == 13){ newReceive(); } " /* Do ajax call when user presses enter key */
    ),
        ));
?>
<?php echo $form->hiddenField($model, 'parent', array('value' => 0)); ?>
<fieldset>
    <div class="row">
        <section class="col col-2">
            <label class="select">
                <?php echo StockRequisition::getItemList('StockTransfer', 'item'); ?>
                <?php echo $form->error($model, 'item'); ?>
            </label>
        </section>
        <section class="col col-2">
            <label class="select">
                <?php echo StockRequisition::getStoreList('StockTransfer', 'store_from', 'Select From Store'); ?>
                <?php echo $form->error($model, 'store_from'); ?>
            </label>
        </section>
        <section class="col col-2">
            <label class="select">
                <?php echo StockRequisition::getBatchList('StockTransfer', 'batch'); ?>
                <?php echo $form->error($model, 'batch'); ?>
            </label>
        </section>  
        <section class="col col-1">
            <label class="input">
                <?php echo $form->textField($model, 'quantity', array('maxlength' => 20, 'class' => 'col-sm-12', 'placeholder' => 'Quantity')); ?>
                <?php echo $form->error($model, 'quantity'); ?>
            </label>
        </section>
        <section class="col col-2">
            <label class="select">
                <?php echo Store::get_parents("StockTransfer", "store_to", $model->store_to); ?>
                <?php echo $form->error($model, 'store_to'); ?>
            </label>
        </section>
        <section class="col col-1">
            <?php echo CHtml::htmlButton('<i class="fa fa-plus"></i> Add', array('type' => 'submit', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'newReceive();')); ?> 
        </section>
    </div>
</fieldset>
<?php $this->endWidget(); ?>
<?php
$formParent = $this->beginWidget('CActiveForm', array(
    'id' => 'stock-transfer-parent-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'smart-form', 'enctype' => 'multipart/form-data',),
        ));
?>
<fieldset>
    <div class="row">
        <section class="col col-4">
            <label class="input">
                <?php echo $formParent->textField($modelParent, 'comments', array('maxlength' => 1000, 'class' => 'col-sm-12', 'placeholder' => 'Comments')); ?>
                <?php echo $formParent->error($modelParent, 'comments'); ?>
            </label>
        </section>
        <section class="col col-4">
            <label class="label">
                <?php echo $formParent->error($modelParent, 'error_message'); ?>
            </label>
        </section>
    </div>    
</fieldset>   
<footer>
    <?php echo CHtml::htmlButton('Submit', array('type' => 'submit', 'class' => 'btn btn-primary')); ?>  
    <button onclick="window.history.back();" class="btn btn-default" type="button">
        Back
    </button>
</footer>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    function newReceive() {
        if ($("#StockTransfer_item").val() == "" || $("#StockTransfer_store_from").val() == "" || $("#StockTransfer_quantity").val() == "" || $("#StockTransfer_batch").val() == "" || $("#StockTransfer_store_to").val() == "") {
            errorNotificationBig('Information Box!', '<ul><li>Please select an Item</li><li>Please select a From Store</li><li>Please enter Quantity</li><li>Please select Lot Number</li><li>Please select a To Store</li></ul>');
            return false;
        }
        var formData = new FormData($('#stock-transfer-form')[0]);
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("stockTransfer/add"); ?>',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                //alert("succes:" + data);
                if (data != "false")
                {
                    $('#stock-transfer-grid').yiiGridView('update');
                    $('#stock-transfer-form').each(function () {
                        this.reset();
                    });
                    $('#StockTransfer_item,#StockTransfer_store_from,#StockTransfer_batch,#StockTransfer_store_to').select2('val', '').trigger('change');
                    successNotificationSmall('Success', 'Item was added to Stock Transfer.');
                }
            },
            error: function (data) { // if error occured
                alert("Error occured. Please try again");
                $('#stock-transfer-form').each(function () {
                    this.reset();
                });
                //alert(data);
            },
            dataType: 'html'
        });
    }
</script>