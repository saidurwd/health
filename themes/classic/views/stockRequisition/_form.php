<?php
/* @var $this StockRequisitionController */
/* @var $model StockRequisition */
/* @var $form CActiveForm */
?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'stock-requisition-grid',
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
            'name' => 'store',
            'value' => 'Store::get_store($data->store)',
            'htmlOptions' => array('style' => "text-align:left;"),
        ),
        array(
            'name' => 'batch',
            'header' => 'Lot No.',
            'value' => 'Batch::getBatch($data->batch)',
            'htmlOptions' => array('style' => "text-align:left;"),
        ),
        array(
            'name' => 'quantity',
            'value' => 'Product::number_format($data->quantity,2)." ".Product::getItemUOM($data->item)',
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
<?php echo $form->hiddenField($model, 'parent', array('value' => 0)); ?>
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
        <section class="col col-2">
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
    function newRequisition() {
        if ($("#StockRequisition_item").val() == "" || $("#StockRequisition_store").val() == "" || $("#StockRequisition_batch").val() == "" || $("#StockRequisition_quantity").val() == "") {
            errorNotificationBig('Information Box!', '<ul><li>Please select an Item.</li><li>Please select a Store.</li><li>Please select a Batch.</li><li>Please enter Quantity.</li></ul>');
            return false;
        }
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
                $('#purchase-order-form').each(function () {
                    this.reset();
                });
                //alert(data);
            },
            dataType: 'html'
        });
    }
</script>