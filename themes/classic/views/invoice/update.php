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
        $("#Invoice_store").chained("#Invoice_item");
        $("#Invoice_batch").chained("#Invoice_item, #Invoice_store");
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
                    <h2><strong>Invoice#: </strong><?php echo InvoiceParent::getData(@$_REQUEST['id'], "invoice_number"); ?>, <strong>Invoice Date: </strong><?php echo User::get_date_time(InvoiceParent::getData(@$_REQUEST['id'], "invoice_date")); ?>, <strong>Invoice By: </strong><?php echo User::get_full_name(InvoiceParent::getData(@$_REQUEST['id'], "invoice_by")); ?></h2>
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
                                    'htmlOptions' => array('style' => "text-align:right;width:100px;"),
                                ),
                                array(
                                    'name' => 'discount',
                                    'value' => 'Product::number_format_currency($data->discount,2,Yii::app()->session->get(\'currency\'))',
                                    'htmlOptions' => array('style' => "text-align:right;width:100px;"),
                                    'footer' => Invoice::getTotalDiscount(@$_REQUEST['id']),
                                    'footerHtmlOptions' => array('class' => 'text-right text-bold-cus'),
                                ),
                                array(
                                    'name' => 'amount',
                                    'value' => 'Product::number_format_currency($data->amount,2,Yii::app()->session->get(\'currency\'))',
                                    'htmlOptions' => array('style' => "text-align:right;width:150px;"),
                                    'footer' => Invoice::getTotalAmount(@$_REQUEST['id']),
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
                        <?php echo $this->renderPartial('_form_update', array('model' => $model, 'modelParent' => $modelParent)); ?>
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