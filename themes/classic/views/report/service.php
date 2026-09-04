<?php
$this->pageTitle = 'Rehabilitation Service Bill Report';
$this->breadcrumbs = array(
    'Reports' => array('sales'),
    'Rehabilitation Service Bill Report',
);

$start_date = date('Y-m-d');
$end_date = date('Y-m-d');
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
if (empty($_POST['category'])) {
    $category = NULL;
} else {
    $category = $_POST['category'];
}
if (empty($_POST['category_new'])) {
    $category_new = NULL;
} else {
    $category_new = $_POST['category_new'];
}
if (empty($_POST['service'])) {
    $service = NULL;
} else {
    $service = $_POST['service'];
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
                Rehabilitation Service Bill Report
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
                    <h2>Rehabilitation Service Bill Report</h2>
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
                                    <?php echo CHtml::textField('start_date', isset($_REQUEST['start_date']) ? CHtml::encode($_REQUEST['start_date']) : $start_date, array('class' => 'col-sm-12 datepicker', 'placeholder' => 'Start Date', 'data-dateformat' => 'yy-mm-dd')); ?>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <?php echo CHtml::textField('end_date', isset($_REQUEST['end_date']) ? CHtml::encode($_REQUEST['end_date']) : $end_date, array('class' => 'col-sm-12 datepicker', 'placeholder' => 'End Date', 'data-dateformat' => 'yy-mm-dd')); ?>                                    
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <?php echo PatientCategoryNew::getPatientCategorySearch($category_new); ?>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <?php echo PatientCategory::getPatientCategorySearch($category); ?>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <?php echo Service::getServiceCategorySearch($service); ?>
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
                                <?php echo CHtml::link('<i class="fa fa-print"></i> Print', array('serviceprint', 'start_date' => $start_date, 'end_date' => $end_date, 'category' => $category, 'category_new' => $category_new, 'service' => $service, 'status' => $status), array('class' => 'btn btn-info btn-sm btn-block', 'target' => '_blank')); ?>
                            </section>
                        </div>
                        <?php $this->endWidget(); ?>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Patient#</th>
                                    <th>Reference#</th>
                                    <th>Grade</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Name</th>
                                    <th>Note</th>
                                    <th>Consultation/Admission</th>
                                    <th>Service</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody>                               
                                <?php
                                $array = Report::serviceBillReport($start_date, $end_date, $category, $category_new, $service, $status);
                                $total = count($array);
                                $total_srv = 0;
                                $total_med = 0;
                                $total_amount = 0;
                                foreach ($array as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . User::get_date($value["created_on"]) . '</td>';
                                    echo '<td>' . $value["pat_id"] . '</td>';
                                    echo '<td>' . $value["ref_no"] . '</td>';
                                    echo '<td>' . PatientGrade::getData($value["patient_grade"], 'title') . '</td>';
                                    echo '<td>' . PatientCategoryNew::getData($value["category_new"], 'alias') . '</td>';
                                    echo '<td>' . PatientCategory::getData($value["category"], 'alias') . '</td>';
                                    echo '<td>' . $value["name"] . '</td>';
                                    echo '<td>' . $value["note"] . '</td>';
                                    echo '<td class="text-right">' . Product::number_format_currency($value["amount_consultation"], 2, Yii::app()->session->get("currency")) . '</td>';
                                    echo '<td class="text-right">' . Product::number_format_currency($value["amount_service"], 2, Yii::app()->session->get("currency")) . '</td>';
                                    echo '<td class="text-right">' . Product::number_format_currency(($value["amount_consultation"] + $value["amount_service"]), 2, Yii::app()->session->get("currency")) . '</td>';
                                    echo '</tr>';
                                    $total_srv += $value["amount_consultation"];
                                    $total_med += $value["amount_service"];
                                    $total_amount += ($value["amount_consultation"] + $value["amount_service"]);
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="8" class="text-right">TOTAL: </th>
                                    <th class="text-right"><?php echo Product::number_format_currency($total_srv, 0, Yii::app()->session->get("currency")); ?></th>
                                    <th class="text-right"><?php echo Product::number_format_currency($total_med, 0, Yii::app()->session->get("currency")); ?></th>
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