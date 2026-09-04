<?php
$this->pageTitle = 'Patient Register Physiotherapy';
$this->breadcrumbs = array(
    'Reports' => array('register'),
    'Patient Register Physiotherapy',
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
if (empty($_POST['category'])) {
    $category = 0;
} else {
    $category = $_POST['category'];
}
if (empty($_POST['category_new'])) {
    $category_new = 0;
} else {
    $category_new = $_POST['category_new'];
}
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-bar-chart-o fa-fw "></i> 
            Reports
            <span>>
                Patient Register Physiotherapy
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
                    <h2>Patient Register Physiotherapy</h2>
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
                            <section class="col col-1">
                                <?php echo CHtml::htmlButton('<i class="fa fa-search"></i> ' . Yii::t('Common', 'search'), array('type' => 'submit', 'class' => 'btn btn-primary btn-sm btn-block')); ?>
                            </section>
                            <section class="col col-1">
                                <?php echo CHtml::link('<i class="fa fa-print"></i> Print', array('registerphysioprint', 'start_date' => $start_date, 'end_date' => $end_date, 'category' => $category, 'category_new' => $category_new), array('class' => 'btn btn-info btn-sm btn-block', 'target' => '_blank')); ?>
                            </section>
                        </div>
                        <?php $this->endWidget(); ?>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>SL#</th>
                                    <th>Date</th>
                                    <th>Patient ID</th>
                                    <th>Category</th>
                                    <th>Sub Category</th>
                                    <th>Patient Name</th>
                                    <th>Ref. No</th>
                                    <th>Grade</th>
                                    <th>Age</th>
                                    <th>Sex</th>
                                    <th>Guardian Name</th>
                                    <th>Problem</th>
                                    <th>Address</th>
                                    <th>Contact No.</th>
                                    <th>Reg. Fee</th>
                                    <th>Invoice#</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $array = Report::patientRegisterPhysioReport($start_date, $end_date, $category, $category_new);
                                $total = count($array);
                                $i = 1;
                                foreach ($array as $key => $value) {
                                    echo '<tr>';
                                    echo '<td class="text-center">' . $i . '</td>';
                                    echo '<td>' . User::get_date($value["invoice_date"]) . '</td>';
                                    echo '<td>' . $value["pid"] . '</td>';
                                    echo '<td>' . PatientCategoryNew::get_full_path($value["category_new"]) . '</td>';
                                    echo '<td>' . PatientCategory::get_full_path($value["category"]) . '</td>';
                                    echo '<td>' . $value["pname"] . '</td>';
                                    echo '<td>' . $value["ref_no"] . '</td>';
                                    echo '<td>' . PatientGrade::getData($value["patient_grade"], 'title') . '</td>';
                                    echo '<td>' . $value["age"] . '</td>';
                                    echo '<td>' . $value["sex"] . '</td>';
                                    echo '<td>' . $value["emergency_person"] . '</td>';
                                    echo '<td>' . $value["problem"] . '</td>';
                                    echo '<td>' . Patient::getPatiantAddress($value["id"]) . '</td>';
                                    echo '<td>' . $value["mobile"] . '</td>';
                                    echo '<td class="text-right">' . Product::number_format_currency($value["amount"], 2, Yii::app()->session->get("currency")) . '</td>';
                                    echo '<td class="text-left">' . $value["invoice_number"] . '</td>';
                                    echo '</tr>';
                                    $i++;
                                }
                                ?>
                            </tbody>
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