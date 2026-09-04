<?php
$this->pageTitle = 'Patient Invoices';
$this->breadcrumbs = array(
    'Reports' => array('patinvoice'),
    'Patient Invoices',
);

$start_date = date('Y-m-01');
$end_date = date('Y-m-t');
if (empty($_POST['start_date'])) {
    $start_date = $start_date;
} else {
    $start_date = $_POST['start_date'];
}
if (empty($_POST['end_date'])) {
    $end_date = $end_date;
} else {
    $end_date = $_POST['end_date'];
}
if (empty($_POST['patient'])) {
    $patient = NULL;
} else {
    $patient = $_POST['patient'];
}
if (empty($_POST['status'])) {
    $status = NULL;
} else {
    $status = $_POST['status'];
}
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-bar-chart-o fa-fw "></i> 
            Reports
            <span>>
                Patient Invoices
            </span>
        </h1>
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
                    <span class="widget-icon"> <i class="fa fa-bar-chart-o"></i> </span>
                    <h2>Patient Invoices</h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body">
                        <?php
                        $form = $this->beginWidget('CActiveForm', array(
                            'action' => Yii::app()->createUrl($this->route),
                            'method' => 'post',
                            'htmlOptions' => array('class' => 'smart-form'),
                        ));
                        ?>
                        <div class="row">                            
                            <section class="col col-2">
                                <label class="input">
                                    <i class="icon-append fa fa-calendar"></i>
                                    <?php echo CHtml::textField('start_date', isset($_REQUEST['start_date']) ? CHtml::encode($_REQUEST['start_date']) : $start_date, array('class' => 'col-sm-12 datepicker', 'placeholder' => 'Start Date', 'data-dateformat' => 'yy-mm-dd')); ?>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <i class="icon-append fa fa-calendar"></i>
                                    <?php echo CHtml::textField('end_date', isset($_REQUEST['end_date']) ? CHtml::encode($_REQUEST['end_date']) : $end_date, array('class' => 'col-sm-12 datepicker', 'placeholder' => 'End Date', 'data-dateformat' => 'yy-mm-dd')); ?>                                    
                                </label>
                            </section>   
                            <section class="col col-3">
                                <label class="select">
                                    <?php echo CHtml::dropDownList('patient', isset($_REQUEST['patient']) ? CHtml::encode($_REQUEST['patient']) : $patient, CHtml::listData(Patient::model()->findAll(array('select' => 'id,CONCAT(name," [",pat_id,"]") AS name', 'condition' => '', 'order' => 'name')), 'id', 'name'), array('empty' => 'All Patient', 'class' => 'select2')); ?>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <?php echo CHtml::dropDownList('status', isset($_REQUEST['status']) ? CHtml::encode($_REQUEST['status']) : $status, array('Paid' => 'Paid', 'Unpaid' => 'Unpaid'), array('empty' => 'All Status', 'class' => 'select2')); ?>
                                </label>
                            </section>
                            <section class="col col-1">
                                <?php echo CHtml::htmlButton('<i class="fa fa-search"></i> ' . Yii::t('Common', 'search'), array('type' => 'submit', 'class' => 'btn btn-primary btn-sm btn-block')); ?>
                            </section>
                            <section class="col col-1">
                                <?php echo CHtml::link('<i class="fa fa-print"></i> Print', array('patinvoiceprint', 'start_date' => $start_date, 'end_date' => $end_date, 'patient' => $patient, 'status' => $status), array('class' => 'btn btn-info btn-sm btn-block', 'target' => '_blank')); ?>
                            </section>
                        </div>
                        <?php $this->endWidget(); ?>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>SL#</th>                                    
                                    <th>Invoice#</th>
                                    <th>Date</th>        
                                    <th>Payment Status</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $array = Report::patientInvoiceReport($start_date, $end_date, $patient, $status);
                                $total = count($array);
                                $total_amount = 0;
                                $i = 1;
                                foreach ($array as $key => $value) {
                                    echo '<tr>';
                                    echo '<td class="text-center">' . $i . '</td>';
                                    echo '<td class="text-left">' . $value["invoice_number"] . '</td>';
                                    echo '<td>' . User::get_date($value["invoice_date"]) . '</td>';
                                    echo '<td>' . $value["payment_status"] . '</td>';
                                    echo '<td class="text-right">' . Product::number_format_currency($value["total_amount"], 2, Yii::app()->session->get("currency")) . '</td>';
                                    echo '</tr>';
                                    $total_amount += $value["total_amount"];
                                    $i++;
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">TOTAL: </th>
                                    <th class="text-right"><?php echo Product::number_format_currency($total_amount, 0, Yii::app()->session->get("currency")); ?></th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="summary space-top-10">Displaying <?php echo $total; ?> results.</div>
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