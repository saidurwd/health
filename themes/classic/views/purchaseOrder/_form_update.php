<?php
/* @var $this PurchaseOrderController */
/* @var $model PurchaseOrder */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'purchase-order-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'smart-form',
        'enctype' => 'multipart/form-data',
        'onsubmit' => "return false;", /* Disable normal form submit */
    //'onkeypress' => " if(event.keyCode == 13){ newOrder(); } " /* Do ajax call when user presses enter key */
    ),
        ));
?>
<?php echo $form->hiddenField($model, 'parent', array('value' => @$_REQUEST['id'])); ?>
<fieldset>
    <div class="row">
        <section class="col col-2">
            <label class="select">
                <?php echo $form->dropDownList($model, 'item', CHtml::listData(Product::model()->findAll(array('select' => 'id, CONCAT(title," - ",product_code) AS title', 'condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'Select a Product', 'class' => 'select2')); ?>
                <?php echo $form->error($model, 'item'); ?>
            </label>
        </section>
        <section class="col col-2">
            <label class="input">
                <?php echo $form->textField($model, 'quantity', array('maxlength' => 20, 'class' => 'col-sm-12', 'placeholder' => 'Quantity')); ?>
                <?php echo $form->error($model, 'quantity'); ?>
            </label>
        </section>
        <section class="col col-2">
            <?php echo CHtml::htmlButton('<i class="fa fa-plus"></i> Add', array('type' => 'submit', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'newOrder();')); ?> 
        </section>
    </div>
</fieldset>
<?php $this->endWidget(); ?>
<?php
$formParent = $this->beginWidget('CActiveForm', array(
    'id' => 'purchase-order-parent-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'smart-form'),
        ));
?>
<fieldset>
    <div class="row">
        <section class="col col-2">
            <label class="input">
                <?php echo $formParent->dropDownList($modelParent, 'status', CHtml::listData(TransectionStatus::model()->findAll(array('condition' => 'transection_type=1', 'order' => '')), 'status_id', 'status_title'), array('class' => 'select2')); ?>
                <?php echo $formParent->error($modelParent, 'status'); ?>
            </label>
        </section>
        <section class="col col-2">
            <label class="input">
                <?php echo $formParent->dropDownList($modelParent, 'supplier', CHtml::listData(Vendor::model()->findAll(array('condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'Select a Supplier', 'class' => 'select2')); ?>
                <?php echo $formParent->error($modelParent, 'supplier'); ?>
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
    function newOrder()
    {
        var formData = new FormData($('#purchase-order-form')[0]);
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("purchaseOrder/add"); ?>',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                //alert("succes:" + data);
                if (data != "false")
                {
                    $('#purchase-order-grid').yiiGridView('update');
                    $('#purchase-order-form').each(function () {
                        this.reset();
                    });
                    $('#PurchaseOrder_item').select2('val', '').trigger('change');
                }
            },
            error: function (data) { // if error occured
                alert("Error occured. Please try again");
                $('#purchase-order-form').each(function () {
                    this.reset();
                });
                //alert(data);
            },
            dataType: 'html'
        });
    }
</script>