<?php
/* @var $this PurchaseReceiveController */
/* @var $model PurchaseReceive */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'purchase-receive-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'smart-form',
        'enctype' => 'multipart/form-data',
        'onsubmit' => "return false;", /* Disable normal form submit */
    //'onkeypress' => " if(event.keyCode == 13){ newReceive(); } " /* Do ajax call when user presses enter key */
    ),
        ));
?>
<?php echo $form->hiddenField($model, 'parent', array('value' => @$_REQUEST['id'])); ?>
<fieldset>
    <div class="row">
        <section class="col col-2">
            <label class="select">
                <?php //echo $form->dropDownList($model, 'item', CHtml::listData(Product::model()->findAll(array('select' => 'id, CONCAT(title," - ",product_code) AS title', 'condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'Select an Item', 'class' => 'select2')); ?>
                <?php echo Product::getItemList('PurchaseReceive', 'item'); ?>
                <?php echo $form->error($model, 'item'); ?>
            </label>
        </section>
        <section class="col col-2">
            <label class="select">
                <?php //echo CHtml::activeDropDownList($model, 'store', CHtml::listData(Store::model()->findAll(array('condition' => '', "order" => "title")), 'id', 'title'), array('empty' => 'Select a Store', 'class' => 'select2')); ?>
                <?php echo Store::get_parents("PurchaseReceive", "store", $model->store); ?>
                <?php echo $form->error($model, 'store'); ?>
            </label>
        </section>
        <section class="col col-1">
            <label class="input">
                <?php echo $form->textField($model, 'quantity', array('maxlength' => 20, 'class' => 'col-sm-12', 'placeholder' => 'Quantity')); ?>
                <?php echo $form->error($model, 'quantity'); ?>
            </label>
        </section>
        <section class="col col-1">
            <label class="input">
                <?php echo $form->textField($model, 'buy_rate', array('maxlength' => 18, 'class' => 'col-sm-12', 'placeholder' => 'Buy Rate')); ?>
                <?php echo $form->error($model, 'buy_rate'); ?>
            </label>
        </section>
        <section class="col col-1">
            <label class="input">
                <?php echo $form->textField($model, 'rate', array('maxlength' => 18, 'class' => 'col-sm-12', 'placeholder' => 'Sale Rate')); ?>
                <?php echo $form->error($model, 'rate'); ?>
            </label>
        </section>
        <section class="col col-2">
            <label class="input">
                <div class="input-group">
                    <?php echo $form->textField($batch, 'expiry', array('class' => 'col-sm-12 datepicker', 'placeholder' => 'Expiry', 'data-dateformat' => 'yy-mm-dd')); ?>
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div> 
                <?php echo $form->error($batch, 'expiry'); ?>
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
    'id' => 'purchase-order-parent-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'smart-form', 'enctype' => 'multipart/form-data'),
        ));
?>
<fieldset>
    <div class="row">
        <section class="col col-2">
            <label class="input">
                <?php echo $formParent->dropDownList($modelParent, 'status', CHtml::listData(TransectionStatus::model()->findAll(array('condition' => 'transection_type=2', 'order' => '')), 'status_id', 'status_title'), array('class' => 'select2')); ?>
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
    <div class="row">
        <section class="col col-12">
            <?php
            $this->widget('CMultiFileUpload', array(
                'model' => $document,
                'attribute' => 'doc_file',
                'accept' => 'jpg|gif|png|doc|docx|pdf|xl|xls|csv|rtf|odt|rtf|tex|txt|ppt|pptx|zip|7z|rar|bzip2|gzip|tar',
                'options' => array(
                // 'onFileSelect'=>'function(e, v, m){ alert("onFileSelect - "+v) }',
                // 'afterFileSelect'=>'function(e, v, m){ alert("afterFileSelect - "+v) }',
                // 'onFileAppend'=>'function(e, v, m){ alert("onFileAppend - "+v) }',
                // 'afterFileAppend'=>'function(e, v, m){ alert("afterFileAppend - "+v) }',
                // 'onFileRemove'=>'function(e, v, m){ alert("onFileRemove - "+v) }',
                // 'afterFileRemove'=>'function(e, v, m){ alert("afterFileRemove - "+v) }',
                ),
                'denied' => 'File is not allowed',
                'duplicate' => 'Already Selected',
                'max' => 10, // max 10 files
                'remove' => '<i class="fa fa-times btn btn-danger btn-xs"></i>',
                'htmlOptions' => array(
                    'enctype' => 'multipart/form-data',
                    'class' => 'col-sm-12',
                ),
            ));
            ?>
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
    function newReceive()
    {
        if ($("#PurchaseReceive_item").val() == "" || $("#PurchaseReceive_store").val() == "" || $("#PurchaseReceive_quantity").val() == "" || $("#PurchaseReceive_rate").val() == "" || $("#Batch_expiry").val() == "") {
            errorNotificationBig('Information Box!', '<ul><li>Please select an Item</li><li>Please select a Store</li><li>Please enter Quantity</li><li>Please enter Rate</li><li>Please select Expiry</li></ul>');
            return false;
        }
        var formData = new FormData($('#purchase-receive-form')[0]);
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("purchaseReceive/add"); ?>',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                //alert("succes:" + data);
                if (data != "false")
                {
                    $('#purchase-receive-grid').yiiGridView('update');
                    $('#purchase-receive-form').each(function () {
                        this.reset();
                    });
                    $('#PurchaseReceive_item, #PurchaseReceive_store').select2('val', '').trigger('change');
                    successNotificationSmall('Success', 'Item was added to Purchase Receive.');
                }
            },
            error: function (data) { // if error occured
                alert("Error occured. Please try again");
                $('#purchase-receive-form').each(function () {
                    this.reset();
                });
                //alert(data);
            },
            dataType: 'html'
        });
    }
    function saveadjustment(id, adjustment, type) {
        //alert(id+"-"+adjustment+"-"+type);
        //alert(lotno);
        if (id != "" && adjustment != "")
        {
            $.ajax({
                type: "GET",
                url: "<?php print $this->createUrl('purchaseReceive/adjustment'); ?>",
                data: "id=" + id + "&adjustment=" + adjustment + "&type=" + type,
                cache: false,
                async: false,
                success: function (result) {
                    //alert(result);
                    //$('#purchase-receive-grid').yiiGridView('update');
                    $(".reload" + id).load('<?php echo Yii::app()->getRequest()->getUrl(); ?> .reload' + id);
                    setTimeout(function () {
                        $('.select2').select2();
                    }, 10000);
                },
                error: function (result) {
                    //alert(result);
                    alert("some error occured. Please try again.");
                }
            });
        }
    }
</script>
