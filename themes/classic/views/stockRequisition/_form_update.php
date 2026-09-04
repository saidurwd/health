<?php
/* @var $this StockRequisitionController */
/* @var $model StockRequisition */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'stock-requisition-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'smart-form',
        'enctype' => 'multipart/form-data',
        'onsubmit' => "return false;", /* Disable normal form submit */
    //'onkeypress' => " if(event.keyCode == 13){ newRequisition(); } " /* Do ajax call when user presses enter key */
    ),
        ));
?>
<?php echo $form->hiddenField($model, 'parent', array('value' => @$_REQUEST['id'])); ?>
<fieldset>
    <div class="row">        
        <section class="col col-2">
            <label class="select">
                <?php echo StockRequisition::getItemList('StockRequisition', 'item'); ?>
                <?php echo $form->error($model, 'item'); ?>
            </label>
        </section> 
        <section class="col col-2">
            <label class="select">
                <?php echo StockRequisition::getStoreList('StockRequisition', 'store', 'Select a Store'); ?>
                <?php echo $form->error($model, 'store'); ?>
            </label>
        </section> 
        <section class="col col-2">
            <label class="select">
                <?php echo StockRequisition::getBatchList('StockRequisition', 'batch'); ?>
                <?php echo $form->error($model, 'batch'); ?>
            </label>
        </section> 
        <section class="col col-1">
            <label class="input">
                <?php echo $form->textField($model, 'quantity', array('maxlength' => 20, 'class' => 'col-sm-12', 'placeholder' => 'Quantity')); ?>
                <?php echo $form->error($model, 'quantity'); ?>
            </label>
        </section>
        <section class="col col-1">
            <?php echo CHtml::htmlButton('<i class="fa fa-plus"></i> Add', array('type' => 'submit', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'newRequisition();')); ?> 
        </section>
    </div>
</fieldset>
<?php $this->endWidget(); ?>
<?php
$formParent = $this->beginWidget('CActiveForm', array(
    'id' => 'stock-requisition-parent-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'smart-form'),
        ));
?>
<fieldset>
    <div class="row">
        <section class="col col-2">
            <label class="input">
                <?php echo $formParent->dropDownList($modelParent, 'status', CHtml::listData(TransectionStatus::model()->findAll(array('condition' => 'transection_type=3', 'order' => '')), 'status_id', 'status_title'), array('class' => 'select2')); ?>
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
    function newRequisition()
    {
        var formData = new FormData($('#stock-requisition-form')[0]);
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("stockRequisition/add"); ?>',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                //alert("succes:" + data);
                if (data != "false")
                {
                    $('#stock-requisition-grid').yiiGridView('update');
                    $('#stock-requisition-form').each(function () {
                        this.reset();
                    });
                    $('#StockRequisition_item,#StockRequisition_store,#StockRequisition_batch').select2('val', '').trigger('change');
                }
            },
            error: function (data) { // if error occured
                alert("Error occured. Please try again");
                $('#stock-requisition-form').each(function () {
                    this.reset();
                });
                //alert(data);
            },
            dataType: 'html'
        });
    }
</script>