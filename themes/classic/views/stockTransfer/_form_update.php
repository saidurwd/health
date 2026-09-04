<?php
/* @var $this StockTransferController */
/* @var $model StockTransfer */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'stock-transfer-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'smart-form',
        'enctype' => 'multipart/form-data',
        'onsubmit' => "return false;", /* Disable normal form submit */
    ),
        ));
?>
<?php echo $form->hiddenField($model, 'parent', array('value' => $modelParent->id)); ?>
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
                <?php echo $form->error($model, 'store'); ?>
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
    'htmlOptions' => array('class' => 'smart-form'),
        ));
?>
<fieldset>
    <div class="row">
        <section class="col col-2">
            <label class="input">
                <?php echo $formParent->dropDownList($modelParent, 'status', CHtml::listData(TransectionStatus::model()->findAll(array('condition' => 'transection_type=6', 'order' => '')), 'status_id', 'status_title'), array('class' => 'select2')); ?>
                <?php echo $formParent->error($modelParent, 'status'); ?>
            </label>
        </section>
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