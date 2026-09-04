<?php
/* @var $this StockIssueController */
/* @var $model StockIssue */
/* @var $form CActiveForm */
?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'stock-issue-grid',
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
            'header' => 'Expiry',
            'value' => 'Batch::getBatch($data->batch)',
            'htmlOptions' => array('style' => "text-align:left;"),
        ),
        array(
            'name' => 'quantity',
            'value' => 'CHTML::textField("quantity_".$data->id,$data->quantity,array("class"=>"form-control", "onchange"=>"saveadjustment($data->id, this.value, \'quantity\')"))',
            'type' => 'raw',
            'htmlOptions' => array('style' => "text-align:right;width:120px;"),
        ),
        array(
            'header' => 'Unit',
            'type' => 'raw',
            'value' => 'Product::getItemUOM($data->item)',
            'htmlOptions' => array('class' => "text-center width-100"),
        ),
        array(
            'name' => 'rate',
            'value' => 'Product::number_format_currency($data->rate,2,Yii::app()->session->get(\'currency\'))',
            'type' => 'raw',
            'htmlOptions' => array('style' => "text-align:right;width:120px;"),
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
    'id' => 'stock-issue-form',
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
                <?php echo StockRequisition::getItemList('StockIssue', 'item'); ?>
                <?php echo $form->error($model, 'item'); ?>
            </label>
        </section>
        <section class="col col-2">
            <label class="select">
                <?php echo StockRequisition::getStoreList('StockIssue', 'store', 'Select a Store'); ?>
                <?php echo $form->error($model, 'store'); ?>
            </label>
        </section> 
        <section class="col col-2">
            <label class="select">
                <?php echo StockRequisition::getBatchList('StockIssue', 'batch'); ?>
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
            <?php echo CHtml::htmlButton('<i class="fa fa-plus"></i> Add', array('type' => 'submit', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'newIssue();')); ?> 
        </section>
    </div>
</fieldset>
<?php $this->endWidget(); ?>
<?php
$formParent = $this->beginWidget('CActiveForm', array(
    'id' => 'stock-issue-parent-form',
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
    function newIssue() {
        if ($("#StockIssue_item").val() == "" || $("#StockIssue_store").val() == "" || $("#StockIssue_batch").val() == "" || $("#StockIssue_quantity").val() == "") {
            errorNotificationBig('Information Box!', '<ul><li>Please select an Item.</li><li>Please select a Store.</li><li>Please select a Batch.</li><li>Please enter Quantity.</li></ul>');
            return false;
        }
        var formData = new FormData($('#stock-issue-form')[0]);
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("stockIssue/add"); ?>',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                //alert("succes:" + data);
                if (data != "false")
                {
                    $('#stock-issue-grid').yiiGridView('update');
                    $('#stock-issue-form').each(function () {
                        this.reset();
                    });
                    $('#StockIssue_item,#StockIssue_store,#StockIssue_batch').select2('val', '').trigger('change');
                }
            },
            error: function (data) { // if error occured
                alert("Error occured. Please try again");
                $('#stock-issue-form').each(function () {
                    this.reset();
                });
                //alert(data);
            },
            dataType: 'html'
        });
    }
    function saveadjustment(id, adjustment, type) {
        //alert(id+"-"+adjustment+"-"+type);
        //return id_string;
        if (id != "" && adjustment != "")
        {
            $.ajax({
                type: "GET",
                url: "<?php print $this->createUrl('stockIssue/adjustment'); ?>",
                data: "id=" + id + "&adjustment=" + adjustment + "&type=" + type,
                cache: false,
                async: false,
                success: function (result) {
                    $('#stock-issue-grid').yiiGridView('update');
                },
                error: function (result) {
                    //alert(result);
                    alert("some error occured. Please try again.");
                }
            });
        }
    }
</script>