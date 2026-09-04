<?php
/* @var $this StockIssueController */
/* @var $model StockIssue */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'stock-issue-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'smart-form',
        'enctype' => 'multipart/form-data',
        'onsubmit' => "return false;", /* Disable normal form submit */
    ),
        ));
?>
<?php echo $form->hiddenField($model, 'parent', array('value' => @$_REQUEST['id'])); ?>
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
        <section class="col col-2">
            <label class="input">
                <?php echo $formParent->dropDownList($modelParent, 'status', CHtml::listData(TransectionStatus::model()->findAll(array('condition' => 'transection_type=4', 'order' => '')), 'status_id', 'status_title'), array('class' => 'select2')); ?>
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
    function newIssue()
    {
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