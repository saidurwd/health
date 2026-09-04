<?php
/* @var $this PurchaseReceiveController */
/* @var $model PurchaseReceive */
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
    'id' => 'purchase-receive-grid',
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
            'name' => 'reference',
            'value' => 'PurchaseReceiveParent::getReferenceOrderNo($data->reference)',
            'htmlOptions' => array('style' => ''),
        ),
        array(
            'name' => 'item',
            'value' => 'Product::getItemName($data->item)',
            'htmlOptions' => array('style' => "text-align:left;"),
        ),
        array(
            'name' => 'store',
            'value' => 'Store::get_stores_grid("PurchaseReceivePR","store",$data->store,$data->id)',
            'type' => 'raw',
            'htmlOptions' => array('style' => "text-align:left;width:150px;"),
        ),
        array(
            'name' => 'quantity',
            'value' => 'CHTML::textField("quantity_".$data->id,$data->quantity,array("class"=>"form-control", "onchange"=>"saveadjustment($data->id, this.value, \'quantity\')"))',
            'type' => 'raw',
            'htmlOptions' => array('style' => "text-align:right;width:120px;"),
        ),
        array(
            'header' => 'UOM',
            'type' => 'raw',
            'value' => 'Product::getItemUOM($data->item)',
            'htmlOptions' => array('class' => "text-center width-50"),
        ),
        array(
            'name' => 'rate',
            'value' => 'CHTML::textField("rate_".$data->id,$data->rate,array("class"=>"form-control", "onchange"=>"saveadjustment($data->id, this.value, \'rate\')"))',
            'type' => 'raw',
            'htmlOptions' => array('style' => "text-align:right;width:120px;"),
        ),
        array(
            'name' => 'total_amount',
            'value' => 'Product::number_format_currency($data->total_amount,2,Yii::app()->session->get(\'currency\'))',
            'htmlOptions' => array('style' => "text-align:right;width:150px;"),
        ),
        array(
            'name' => 'buy_rate',
            'value' => 'Product::number_format_currency($data->buy_rate,2,Yii::app()->session->get(\'currency\'))',
            'type' => 'raw',
            'htmlOptions' => array('style' => "text-align:right;width:120px;"),
        ),
        array(
            'name' => 'buy_amount',
            'value' => 'Product::number_format_currency($data->buy_amount,2,Yii::app()->session->get(\'currency\'))',
            'htmlOptions' => array('style' => "text-align:right;width:150px;"),
        ),
        array(
            'name' => 'expiry',
            'header' => 'Expiry',
            'value'=>'PurchaseReceive::getBatchData($data->batch,"expiry")',
            'type' => 'raw',
            'htmlOptions' => array('style' => "text-align:left;width:120px;"),
        ),
        array(
            'header' => 'Files',
            'type' => 'raw',
            'value' => 'PurchaseReceive::fileUpload($data->id)',
            'htmlOptions' => array('style' => "text-align:center;width:120px;"),
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
<?php echo $form->hiddenField($model, 'parent', array('value' => 0)); ?>
<fieldset>
    <div class="row">
        <section class="col col-2">
            <label class="select">
                <?php echo Product::getItemList('PurchaseReceive', 'item'); ?>
                <?php echo $form->error($model, 'item'); ?>
            </label>
        </section>

        <section class="col col-2">
            <label class="select">
                <?php //echo CHtml::activeDropDownList($model, 'store', CHtml::listData(Store::model()->findAll(array('condition' => '', "order" => "path")), 'id', 'alias'), array('empty' => 'Select a Store', 'class' => 'select2')); ?>
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
    'htmlOptions' => array('class' => 'smart-form', 'enctype' => 'multipart/form-data',),
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
    function newReceive() {
        if ($("#PurchaseReceive_item").val() == "" || $("#PurchaseReceive_store").val() == "" || $("#PurchaseReceive_quantity").val() == "" || $("#PurchaseReceive_buy_rate").val() == "" || $("#PurchaseReceive_rate").val() == "" || $("#Batch_expiry").val() == "") {
            errorNotificationBig('Information Box!', '<ul><li>Please select an Item</li><li>Please select a Store</li><li>Please enter Quantity</li><li>Please enter Buy Rate</li><li>Please enter Sale Rate</li><li>Please select Expiry</li></ul>');
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
    function finalValidation() {
        if ($("[id^=batch_]").val() == "") {
            errorNotificationBig('Error Box!', '<ul><li>Expiry cannot be blank.</li></ul>');
            $("form").submit(function (e) {
                e.preventDefault();
            });
            return false;
        } else {
            return true;
        }
    }
</script>
