<?php
/* @var $this InvoiceController */
/* @var $model Invoice */
/* @var $form CActiveForm */
?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'invoice-grid',
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
            'header' => 'Product/Service',
            'name' => 'item',
            'value' => 'Product::getItemName($data->item).Service::getData($data->service,"title")',
            'htmlOptions' => array('style' => "text-align:left;"),
            'footer' => 'TOTAL',
            'footerHtmlOptions' => array('class' => 'text-left text-bold-cus'),
        ),
        array(
            'name' => 'store',
            'value' => 'Store::get_store($data->store)',
            'htmlOptions' => array('style' => "text-align:left;"),
        ),
        array(
            'name' => 'batch',
            'header' => 'Expiry',
            //'value' => 'Batch::getBatch($data->batch)',
            'value' => 'Batch::getData($data->batch,"title")',
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
            'name' => 'discount',
            'value' => 'Product::number_format_currency($data->discount,2,Yii::app()->session->get(\'currency\'))',
            'htmlOptions' => array('style' => "text-align:right;width:100px;"),
            'footer' => $modelGrid->getTotalFooter($modelGrid->search()->getData(), 'discount'),
            'footerHtmlOptions' => array('class' => 'text-right text-bold-cus'),
        ),
        array(
            'name' => 'amount',
            'value' => 'Product::number_format_currency($data->amount,2,Yii::app()->session->get(\'currency\'))',
            'htmlOptions' => array('style' => "text-align:right;width:150px;"),
            'footer' => $modelGrid->getTotalFooter($modelGrid->search()->getData(), 'amount'),
            'footerHtmlOptions' => array('class' => 'text-right text-bold-cus'),
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
Yii::app()->clientScript->registerScript('show_hide', "
    $(document).ready(function(){
        $('#divService').hide();
        $('#divDiscounttype').hide();
        $('#divDiscountamount').hide();
        $('#divRateStatus').hide();
        $('#divNote').hide();
        $('#Invoice_servicetype').change(function(){        
            if (this.value == 'Service'){
                $('#divItem').hide();
                $('#divStore').hide();
                $('#divBatch').hide();
                $('#divService').show();
                $('#divDiscounttype').show();
                $('#divDiscountamount').show();
            }
            if (this.value == 'Medicine'){
                $('#divItem').show();
                $('#divStore').show();
                $('#divBatch').show();
                $('#divService').hide();
                $('#divDiscounttype').hide();
                $('#divDiscountamount').hide();
            }
        });
        $('#Invoice_service').change(function(){            
            if ('" . Service::getRateStatus('Manual') . "'){
                $('#divRateStatus').show();
                $('#divNote').show();
            } else {
                $('#divRateStatus').hide();
                $('#divNote').hide();
            }
        });
    });
	$('#invoice-parent-form').submit(function (e) {
		$('#btnSubmit').attr('disabled', true);
		return true;
	});
");
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'invoice-form',
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
        <section class="col col-1">
            <label class="select">
                <?php echo $form->dropDownList($model, 'servicetype', array('Medicine' => 'Medicine', 'Service' => 'Service'), array('class' => 'select2')); ?>
                <?php echo $form->error($model, 'servicetype'); ?>
            </label>
        </section>
        <section class="col col-2" id="divService">
            <label class="select">
                <?php //echo $form->dropDownList($model, 'service', CHtml::listData(Service::model()->findAll(array('condition' => 'status="Active"')), 'id', 'title'), array('empty' => 'Select a Service', 'class' => 'select2')); ?>
                <?php echo Service::getServiceCategory('Invoice', 'service', $model->service); ?>
                <?php echo $form->error($model, 'service'); ?>
            </label>
        </section>
        <section class="col col-2" id="divItem">
            <label class="select">
                <?php echo StockRequisition::getItemList('Invoice', 'item'); ?>
                <?php echo $form->error($model, 'item'); ?>
            </label>
        </section>
        <section class="col col-2" id="divStore">
            <label class="select">
                <?php echo StockRequisition::getStoreList('Invoice', 'store', 'Select a Store'); ?>
                <?php echo $form->error($model, 'store'); ?>
            </label>
        </section> 
        <section class="col col-2" id="divBatch">
            <label class="select">
                <?php echo StockRequisition::getBatchList('Invoice', 'batch'); ?>
                <?php echo $form->error($model, 'batch'); ?>
            </label>
        </section>
        <section class="col col-2" id="divDiscounttype">
            <label class="select">
                <?php echo $form->dropDownList($model, 'discounttype', array('Percentage' => 'Percentage', 'Cash' => 'Cash'), array('class' => 'select2')); ?>
                <?php echo $form->error($model, 'discounttype'); ?>
            </label>
        </section>
        <section class="col col-2" id="divDiscountamount">
            <label class="input">
                <?php echo $form->textField($model, 'discountamount', array('maxlength' => 4, 'class' => 'col-sm-12', 'placeholder' => 'Percentage/Cash')); ?>
                <?php echo $form->error($model, 'discountamount'); ?>
            </label>
        </section>
        <section class="col col-1">
            <label class="input">
                <?php echo $form->textField($model, 'quantity', array('maxlength' => 20, 'class' => 'col-sm-12', 'placeholder' => 'Quantity')); ?>
                <?php echo $form->error($model, 'quantity'); ?>
            </label>
        </section>
        <section class="col col-1"  id="divRateStatus">
            <label class="input">
                <?php echo $form->textField($model, 'rate', array('maxlength' => 20, 'class' => 'col-sm-12', 'placeholder' => 'Rate')); ?>
                <?php echo $form->error($model, 'rate'); ?>
            </label>
        </section>
        <section class="col col-2"  id="divNote">
            <label class="input">
                <?php echo $form->textField($model, 'note', array('maxlength' => 400, 'class' => 'col-sm-12', 'placeholder' => 'Note')); ?>
                <?php echo $form->error($model, 'note'); ?>
            </label>
        </section>
        <section class="col col-1">
            <?php echo CHtml::htmlButton('<i class="fa fa-plus"></i> Add', array('type' => 'submit', 'class' => 'btn btn-primary btn-sm btn-block', 'onclick' => 'newInvoice();')); ?> 
        </section>
    </div>
</fieldset>
<?php $this->endWidget(); ?>
<?php
$formParent = $this->beginWidget('CActiveForm', array(
    'id' => 'invoice-parent-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'smart-form'),
        ));
?>
<fieldset>
    <div class="row">
        <section class="col col-3">
            <label class="select">
                <?php echo $formParent->dropDownList($modelParent, 'patient', CHtml::listData(Patient::model()->findAll(array('select' => 'id, CONCAT(name," [",pat_id,"]") AS name', 'condition' => '', 'order' => 'id DESC')), 'id', 'name'), array('empty' => 'Select a Patient', 'class' => 'select2')); ?>
                <?php echo $formParent->error($modelParent, 'patient'); ?>
            </label>
        </section>
        <section class="col col-2">
            <label class="select">
                <?php echo PatientPrescription::getPrescriptionList('InvoiceParent', 'prescription', $modelParent->prescription, 'Select a Prescription'); ?>
                <?php echo $formParent->error($model, 'prescription'); ?>
            </label>
        </section> 
        <section class="col col-2">
            <label class="select">
                <?php echo $formParent->dropDownList($modelParent, 'payment_status', array('Paid' => 'Paid', 'Unpaid' => 'Unpaid'), array('class' => 'select2')); ?>
                <?php echo $formParent->error($modelParent, 'payment_status'); ?>
            </label>
        </section> 
        <?php //echo PatientCategory::getPatientCategory('InvoiceParent', 'patient_category', $modelParent->patient_category); ?>
        <?php //echo PatientCategory::getPatientCategoryForm('InvoiceParent', 'patient_category', $modelParent->patient_category); ?>
        <?php //echo $formParent->error($modelParent, 'patient_category'); ?>
        <section class="col col-3">
            <label class="input">
                <?php echo $formParent->textField($modelParent, 'comments', array('maxlength' => 1000, 'class' => 'col-sm-12', 'placeholder' => 'Comments')); ?>
                <?php echo $formParent->error($modelParent, 'comments'); ?>
            </label>
        </section>
        <section class="col col-12">
            <label class="label">
                <?php echo $formParent->error($modelParent, 'error_message'); ?>
            </label>
        </section>
    </div>
</fieldset>    
<footer>
    <?php echo CHtml::htmlButton('SAVE INVOICE', array('type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'btnSubmit')); ?>  
    <button onclick="window.history.back();" class="btn btn-default" type="button">
        Back
    </button>
</footer>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    function newInvoice() {
        if ($("#Invoice_servicetype").val() == "Medicine") {
            if ($("#Invoice_item").val() == "" || $("#Invoice_store").val() == "" || $("#Invoice_batch").val() == "" || $("#Invoice_quantity").val() == "") {
                errorNotificationBig('Information Box!', '<ul><li>Please select an Item.</li><li>Please select a Store.</li><li>Please select a Batch.</li><li>Please enter Quantity.</li></ul>');
                return false;
            }
        }
        if ($("#Invoice_servicetype").val() == "Service") {
            if ($("#Invoice_service").val() == "" || $("#Invoice_discountamount").val() == "" || $("#Invoice_quantity").val() == "") {
                errorNotificationBig('Information Box!', '<ul><li>Please select a Service.</li><li>Please enter discount Percentage/Cash.</li><li>Please enter Quantity.</li></ul>');
                return false;
            }
        }

        var formData = new FormData($('#invoice-form')[0]);
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("invoice/add"); ?>',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                //alert("succes:" + data);
                if (data != "false")
                {
                    $('#invoice-grid').yiiGridView('update');
                    $('#invoice-form').each(function () {
                        this.reset();
                    });
                    $('#Invoice_service,#Invoice_item,#Invoice_store,#Invoice_batch').select2('val', '').trigger('change');
                }
            },
            error: function (data) { // if error occured
                alert("Error occured. Please try again");
                $('#invoice-form').each(function () {
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
                url: "<?php print $this->createUrl('invoice/adjustment'); ?>",
                data: "id=" + id + "&adjustment=" + adjustment + "&type=" + type,
                cache: false,
                async: false,
                success: function (result) {
                    $('#invoice-grid').yiiGridView('update');
                },
                error: function (result) {
                    //alert(result);
                    alert("some error occured. Please try again.");
                }
            });
        }
    }
</script>