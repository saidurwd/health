<?php
/* @var $this InvoiceController */
/* @var $model Invoice */

$this->pageTitle = 'Edit Invoice';
$this->breadcrumbs = array(
    'Invoices' => array('admin'),
    InvoiceParent::getData(@$_REQUEST['id'], "invoice_number") => array('view', 'id' => @$_REQUEST['id']),
    'Update',
);
Yii::app()->clientScript->registerScript('reload-script', "
    function reloadPageSetUp() {
        pageSetUp();
    }
", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('chained', '
        $("#InvoiceParent_prescription").chained("#InvoiceParent_patient");
    ', CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-shopping-cart fa-fw "></i> 
            Invoices
            <span>>
                Edit Invoice
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">   
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-home"></i></span>MANAGE', array('admin'), array('class' => 'btn btn-labeled btn-primary')); ?>
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-plus"></i></span> NEW', array('create'), array('class' => 'btn btn-labeled btn-primary')); ?>       
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-external-link-square"></i></span>DETAILS', array('view', 'id' => $_REQUEST['id']), array('class' => 'btn btn-labeled btn-primary')); ?>
    </div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="">
    <!-- row -->
    <div class="row">
        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false" data-widget-colorbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-home"></i> </span>
                    <h2><strong>Invoice#: </strong><?php echo InvoiceParent::getData(@$_REQUEST['id'], "invoice_number"); ?>, <strong>Invoice Date: </strong><?php echo User::get_date_time(InvoiceParent::getData(@$_REQUEST['id'], "invoice_date")); ?>, <strong>Order By: </strong><?php echo User::get_full_name(InvoiceParent::getData(@$_REQUEST['id'], "invoice_by")); ?></h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'invoice-grid',
                            'dataProvider' => $model->searchInvoice(@$_REQUEST['id']),
                            'afterAjaxUpdate' => 'reloadPageSetUp',
                            'htmlOptions' => array('class' => ''),
                            'itemsCssClass' => 'table table-bordered table-striped table-condensed table-hover',
                            'template' => '{items}{pager}',
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
                                    'header' => 'UOM',
                                    'type' => 'raw',
                                    'value' => 'Product::getItemUOM($data->item)',
                                    'htmlOptions' => array('class' => "text-center width-100"),
                                ),
                                array(
                                    'name' => 'rate',
                                    'value' => 'Product::number_format_currency($data->rate,2,Yii::app()->session->get(\'currency\'))',
                                    'htmlOptions' => array('style' => "text-align:right;width:100px;"),
                                ),
                                array(
                                    'name' => 'discount',
                                    'value' => 'Product::number_format_currency($data->discount,2,Yii::app()->session->get(\'currency\'))',
                                    'htmlOptions' => array('style' => "text-align:right;width:100px;"),
                                    'footer' => $model->getTotalFooter($model->searchInvoice(@$_REQUEST['id'])->getData(), 'discount'),
                                    'footerHtmlOptions' => array('class' => 'text-right text-bold-cus'),
                                ),
                                array(
                                    'name' => 'amount',
                                    'value' => 'Product::number_format_currency($data->amount,2,Yii::app()->session->get(\'currency\'))',
                                    'htmlOptions' => array('style' => "text-align:right;width:150px;"),
                                    'footer' => $model->getTotalFooter($model->searchInvoice(@$_REQUEST['id'])->getData(), 'amount'),
                                    'footerHtmlOptions' => array('class' => 'text-right text-bold-cus'),
                                ),
                            ),
                        ));
                        ?>
                        <?php
                        $formParent = $this->beginWidget('CActiveForm', array(
                            'id' => 'invoice-parent-form',
                            'enableAjaxValidation' => false,
                            'htmlOptions' => array('class' => 'smart-form'),
                        ));
                        ?>
                        <fieldset>
                            <div class="row">      
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
                                <section class="col col-2">
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
                                        <?php //echo PatientCategory::getPatientCategory('InvoiceParent', 'patient_category', $modelParent->patient_category); ?>
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
                            <?php echo CHtml::htmlButton('UPDATE INVOICE', array('type' => 'submit', 'class' => 'btn btn-primary')); ?>  
                            <button onclick="window.history.back();" class="btn btn-default" type="button">
                                Back
                            </button>
                        </footer>
                        <?php $this->endWidget(); ?>
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </article>
        <!-- WIDGET END -->
    </div>
    <!-- end row -->
</section>
<!-- end widget grid -->
<script type="text/javascript">
    function saveadjustment(id, adjustment, type) {
        //alert(id+"-"+adjustment+"-"+type);
        //return id_string;
        if (id != "" && adjustment != "")
        {
            $.ajax({
                type: "GET",
                url: "<?php print $this->createUrl('invoice/adjustmentEdit'); ?>",
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