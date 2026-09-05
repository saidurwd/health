<?php
/* @var $this InvoiceController */
/* @var $model Invoice */
/* @var $modelGrid Invoice */
/* @var $modelParent InvoiceParent */
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'invoice-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'smart-form',
        'enctype' => 'multipart/form-data',
        'onsubmit' => "return false;",
    ),
));
?>
<?php echo $form->hiddenField($model, 'parent', array('value' => 0)); ?>

<div class="panel-group smart-accordion-default" id="invoice-form-accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle" data-toggle="collapse" data-parent="#invoice-form-accordion" href="#invoice-item-info">
                    <i class="fa fa-cart-plus fa-fw"></i> Add Invoice Item
                </a>
            </h4>
        </div>
        <div id="invoice-item-info" class="panel-collapse collapse in">
            <div class="panel-body">
                <div class="row">
                    <section class="col col-2">
                        <label class="select">
                            <?php echo $form->labelEx($model, 'servicetype'); ?>
                            <?php echo $form->dropDownList($model, 'servicetype', array('Medicine' => 'Medicine', 'Service' => 'Service'), array('class' => 'select2')); ?>
                            <?php echo $form->error($model, 'servicetype'); ?>
                        </label>
                    </section>
                    <section class="col col-2" id="divService">
                        <label class="select">
                            <?php echo $form->labelEx($model, 'service'); ?>
                            <?php echo Service::getServiceCategory('Invoice', 'service', $model->service); ?>
                            <?php echo $form->error($model, 'service'); ?>
                        </label>
                    </section>
                    <section class="col col-2" id="divItem">
                        <label class="select">
                            <?php echo $form->labelEx($model, 'item'); ?>
                            <?php echo StockRequisition::getItemList('Invoice', 'item'); ?>
                            <?php echo $form->error($model, 'item'); ?>
                        </label>
                    </section>
                    <section class="col col-2" id="divStore">
                        <label class="select">
                            <?php echo $form->labelEx($model, 'store'); ?>
                            <?php echo StockRequisition::getStoreList('Invoice', 'store', 'Select a Store'); ?>
                            <?php echo $form->error($model, 'store'); ?>
                        </label>
                    </section>
                    <section class="col col-2" id="divBatch">
                        <label class="select">
                            <?php echo $form->labelEx($model, 'batch'); ?>
                            <?php echo StockRequisition::getBatchList('Invoice', 'batch'); ?>
                            <?php echo $form->error($model, 'batch'); ?>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-2" id="divDiscounttype">
                        <label class="select">
                            <?php echo $form->labelEx($model, 'discounttype'); ?>
                            <?php echo $form->dropDownList($model, 'discounttype', array('Percentage' => 'Percentage', 'Cash' => 'Cash'), array('class' => 'select2')); ?>
                            <?php echo $form->error($model, 'discounttype'); ?>
                        </label>
                    </section>
                    <section class="col col-2" id="divDiscountamount">
                        <label class="input">
                            <?php echo $form->labelEx($model, 'discountamount'); ?>
                            <?php echo $form->textField($model, 'discountamount', array('maxlength' => 4, 'class' => 'col-sm-12', 'placeholder' => 'Percentage/Cash')); ?>
                            <?php echo $form->error($model, 'discountamount'); ?>
                        </label>
                    </section>
                    <section class="col col-1">
                        <label class="input">
                            <?php echo $form->labelEx($model, 'quantity'); ?>
                            <?php echo $form->textField($model, 'quantity', array('maxlength' => 20, 'class' => 'col-sm-12', 'placeholder' => 'Quantity')); ?>
                            <?php echo $form->error($model, 'quantity'); ?>
                        </label>
                    </section>
                    <section class="col col-1" id="divRateStatus">
                        <label class="input">
                            <?php echo $form->labelEx($model, 'rate'); ?>
                            <?php echo $form->textField($model, 'rate', array('maxlength' => 20, 'class' => 'col-sm-12', 'placeholder' => 'Rate')); ?>
                            <?php echo $form->error($model, 'rate'); ?>
                        </label>
                    </section>
                    <section class="col col-2" id="divNote">
                        <label class="input">
                            <?php echo $form->labelEx($model, 'note'); ?>
                            <?php echo $form->textField($model, 'note', array('maxlength' => 400, 'class' => 'col-sm-12', 'placeholder' => 'Note')); ?>
                            <?php echo $form->error($model, 'note'); ?>
                        </label>
                    </section>
                    <section class="col col-1">
                        <label class="label">&nbsp;</label>
                        <label class="input">
                            <?php echo CHtml::htmlButton('<i class="fa fa-plus"></i> Add', array('type' => 'submit', 'class' => 'btn btn-primary btn-sm btn-block', 'onclick' => 'newInvoice();')); ?>
                        </label>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endWidget(); ?>

<div class="widget-body no-padding">
    <?php
    $this->widget('zii.widgets.grid.CGridView', array(
        'id' => 'invoice-grid',
        'dataProvider' => $modelGrid->search(),
        'htmlOptions' => array('class' => ''),
        'itemsCssClass' => 'table table-bordered table-striped table-hover',
        'template' => '{items}{pager}{summary}',
        'pager' => array(
            'htmlOptions' => array('class' => 'pagination'),
            'header' => '',
            'selectedPageCssClass' => 'active',
        ),
        'pagerCssClass' => 'widget-footer',
        'columns' => array(
            array(
                'header' => 'Product/Service',
                'name' => 'item',
                'value' => '(empty($data->item0) ? "N/A" : $data->item0->title).(empty($data->service0) ? "" : $data->service0->title)',
                'htmlOptions' => array('style' => "text-align:left;"),
                'footer' => 'TOTAL',
                'footerHtmlOptions' => array('class' => 'text-left text-bold-cus'),
            ),
            array(
                'name' => 'store',
                'value' => '(empty($data->store0) ? "N/A" : $data->store0->title)',
                'htmlOptions' => array('style' => "text-align:left;"),
            ),
            array(
                'name' => 'batch',
                'header' => 'Expiry',
                'value' => '(empty($data->batch0) ? "N/A" : $data->batch0->title)',
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
                'value' => '(empty($data->item0) || empty($data->item0->unit0) ? "N/A" : $data->item0->unit0->formal_name)',
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
                'footer' => isset($totalDiscount) ? $totalDiscount : '',
                'footerHtmlOptions' => array('class' => 'text-right text-bold-cus'),
            ),
            array(
                'name' => 'amount',
                'value' => 'Product::number_format_currency($data->amount,2,Yii::app()->session->get(\'currency\'))',
                'htmlOptions' => array('style' => "text-align:right;width:150px;"),
                'footer' => isset($totalAmount) ? $totalAmount : '',
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
</div>

<?php
$formParent = $this->beginWidget('CActiveForm', array(
    'id' => 'invoice-parent-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array('class' => 'smart-form'),
));
?>

<div class="panel-group smart-accordion-default" id="invoice-parent-accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h4 class="panel-title">
                <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#invoice-parent-accordion" href="#invoice-parent-info">
                    <i class="fa fa-file-text fa-fw"></i> Invoice Information
                </a>
            </h4>
        </div>
        <div id="invoice-parent-info" class="panel-collapse collapse in">
            <div class="panel-body">
                <div class="row">
                    <section class="col col-3">
                        <label class="select">
                            <?php echo $formParent->labelEx($modelParent, 'patient'); ?>
                            <?php
                            $cacheKey = 'PatientList_dropdown';
                            $patientList = Yii::app()->cache->get($cacheKey);
                            if ($patientList === false) {
                                $patientList = CHtml::listData(Patient::model()->findAll(array('select' => 'id, CONCAT(name," [",pat_id,"]") AS name', 'condition' => '', 'order' => 'id DESC')), 'id', 'name');
                                Yii::app()->cache->set($cacheKey, $patientList, 600);
                            }
                            echo $formParent->dropDownList($modelParent, 'patient', $patientList, array('empty' => 'Select a Patient', 'class' => 'select2'));
                            ?>
                            <?php echo $formParent->error($modelParent, 'patient'); ?>
                        </label>
                    </section>
                    <section class="col col-2">
                        <label class="select">
                            <?php echo $formParent->labelEx($modelParent, 'prescription'); ?>
                            <?php echo PatientPrescription::getPrescriptionList('InvoiceParent', 'prescription', $modelParent->prescription, 'Select a Prescription'); ?>
                            <?php echo $formParent->error($model, 'prescription'); ?>
                        </label>
                    </section>
                    <section class="col col-2">
                        <label class="select">
                            <?php echo $formParent->labelEx($modelParent, 'payment_status'); ?>
                            <?php echo $formParent->dropDownList($modelParent, 'payment_status', array('Paid' => 'Paid', 'Unpaid' => 'Unpaid'), array('class' => 'select2')); ?>
                            <?php echo $formParent->error($modelParent, 'payment_status'); ?>
                        </label>
                    </section>
                    <section class="col col-5">
                        <label class="input">
                            <?php echo $formParent->labelEx($modelParent, 'comments'); ?>
                            <?php echo $formParent->textField($modelParent, 'comments', array('maxlength' => 1000, 'class' => 'col-sm-12', 'placeholder' => 'Comments')); ?>
                            <?php echo $formParent->error($modelParent, 'comments'); ?>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="label">
                            <?php echo $formParent->error($modelParent, 'error_message'); ?>
                        </label>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

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
                if (data != "false")
                {
                    $('#invoice-grid').yiiGridView('update');
                    $('#invoice-form').each(function () {
                        this.reset();
                    });
                    $('#Invoice_service,#Invoice_item,#Invoice_store,#Invoice_batch').select2('val', '').trigger('change');
                }
            },
            error: function (data) {
                alert("Error occured. Please try again");
                $('#invoice-form').each(function () {
                    this.reset();
                });
            },
            dataType: 'html'
        });
    }
    function saveadjustment(id, adjustment, type) {
        if (id != "" && adjustment != "")
        {
            $.ajax({
                type: "GET",
                url: "<?php print $this->createUrl('invoice/adjustment'); ?>",
                data: "id=" + id + "&adjustment=" + adjustment + "&type=" + type,
                cache: false,
                success: function (result) {
                    $('#invoice-grid').yiiGridView('update');
                },
                error: function (result) {
                    alert("some error occured. Please try again.");
                }
            });
        }
    }
</script>
