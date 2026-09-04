<?php
/* @var $this PurchaseOrderController */
/* @var $model PurchaseOrder */
/* @var $form CActiveForm */
?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'purchase-order-grid',
    'dataProvider' => $modelGrid->search(),
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
            'header' => 'Catalogue',
            'value' => 'Product::getData($data->item,"product_code")',
            'htmlOptions' => array('style' => "text-align:left;"),
        ),
        array(
            'name' => 'quantity',
            'value' => 'Product::number_format($data->quantity,2) ." ". Product::getItemUOM($data->item)',
            'htmlOptions' => array('style' => "text-align:right;width:100px;"),
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
<?php echo $form->hiddenField($model, 'parent', array('value' => 0)); ?>
<fieldset>
    <div class="row">
        <section class="col col-2">
            <label class="select">
                <?php echo Product::getItemList('PurchaseOrder', 'item'); ?>
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
    function newOrder() {
        if ($("#PurchaseOrder_item").val() == "" || $("#PurchaseOrder_quantity").val() == "") {
            errorNotificationBig('Information Box!', '<ul><li>Please select an Item.</li><li>Please enter Quantity.</li></ul>');
            return false;
        }
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