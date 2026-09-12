<?php
/* @var $this InvoiceController */
/* @var $model Invoice */
/* @var $form CActiveForm */
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
    ),
        ));
?>
<?php echo $form->hiddenField($model, 'parent', array('value' => @$_REQUEST['id'])); ?>
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
        <section class="col col-1">
            <label class="input">
                <?php echo $formParent->dropDownList($modelParent, 'status', CHtml::listData(TransectionStatus::model()->findAll(array('condition' => 'transection_type=5', 'order' => '')), 'status_id', 'status_title'), array('class' => 'select2')); ?>
                <?php echo $formParent->error($modelParent, 'status'); ?>
            </label>
        </section>
        <section class="col col-2">
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
        <section class="col col-1">
            <label class="select">
                <?php echo $formParent->dropDownList($modelParent, 'payment_status', array('Paid' => 'Paid', 'Unpaid' => 'Unpaid'), array('class' => 'select2')); ?>
                <?php echo $formParent->error($modelParent, 'payment_status'); ?>
            </label>
        </section> 
        <section class="col col-2">
            <label class="select">
                <?php echo PatientCategoryNew::getPatientCategoryForm('InvoiceParent', 'patient_category_new', $modelParent->patient_category_new); ?>
                <?php echo $formParent->error($modelParent, 'patient_category_new'); ?>
            </label>
        </section>   
        <section class="col col-2">
            <label class="select">
                <?php echo PatientCategory::getPatientCategoryForm('InvoiceParent', 'patient_category', $modelParent->patient_category); ?>
                <?php echo $formParent->error($modelParent, 'patient_category'); ?>
            </label>
        </section> 
        <section class="col col-2">
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
    <?php echo CHtml::htmlButton('UPDATE INVOICE', array('type' => 'submit', 'class' => 'btn btn-primary', 'id' => 'btnSubmit', 'onclick' => 'return updateInvoice();')); ?>  
    <button onclick="window.history.back();" class="btn btn-default" type="button">
        Back
    </button>
</footer>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    function updateInvoice()
    {
        if ($("#InvoiceParent_patient_category").val() == "") {
            errorNotificationBig('Information Box!', '<ul><li>Please select a patient category.</li></ul>');
            return false;
        }
    }

    function newInvoice()
    {
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