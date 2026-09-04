<?php
$this->pageTitle = 'Sales Report';
$this->breadcrumbs = array(
    'Reports' => array('sales'),
    'Sales Report',
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
if (empty($_POST['type'])) {
    $type = NULL;
} else {
    $type = $_POST['type'];
}
if (empty($_POST['service'])) {
    $service = NULL;
} else {
    $service = $_POST['service'];
}
if (empty($_POST['product'])) {
    $product = NULL;
} else {
    $product = $_POST['product'];
}
if (empty($_POST['category'])) {
    $category = NULL;
} else {
    $category = $_POST['category'];
}
if (empty($_POST['status'])) {
    $status = NULL;
} else {
    $status = $_POST['status'];
}
?>
<?php
//Yii::app()->clientScript->registerScript('show_hide', "
//    $(document).ready(function(){
//        $('#divService').hide();
//        $('#divProduct').hide();
//        $('#type').change(function(){
//            if (this.value == 'Service'){
//                $('#divProduct').hide();
//                $('#divService').show();
//            } else if (this.value == 'Product'){
//                $('#divProduct').show();
//                $('#divService').hide();
//            } else {
//                $('#divProduct').hide();
//                $('#divService').hide();
//            }
//        });
//    });
//");
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-bar-chart-o fa-fw "></i> 
            Reports
            <span>>
                Sales Report
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
                    <h2>Sales Report</h2>
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
                                    <?php echo CHtml::dropDownList('type', isset($_REQUEST['type']) ? CHtml::encode($_REQUEST['type']) : $type, array('Medicine' => 'Medicine', 'Service' => 'Service'), array('empty' => 'Select a Type', 'class' => 'select2')); ?>
                                </label>
                            </section>
                            <section class="col col-2" id="divService">
                                <label class="select">
                                    <?php echo Service::getServiceCategoryReport('service', $service); ?>
                                </label>
                            </section>
                            <section class="col col-2" id="divProduct">
                                <label class="select">
                                    <?php echo Product::getProductCategoryReport('product', $product); ?>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <?php echo PatientCategory::getPatientCategorySearch($category); ?>
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
                                <?php echo CHtml::link('<i class="fa fa-print"></i> Print', array('salesprint', 'start_date' => $start_date, 'end_date' => $end_date, 'type' => $type, 'service' => $service, 'product' => $product, 'category' => $category, 'status' => $status), array('class' => 'btn btn-info btn-sm btn-block', 'target' => '_blank')); ?>
                            </section>
                        </div>
                        <?php $this->endWidget(); ?>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Invoice#</th>
                                    <th>PID</th>
                                    <th>Product/Service</th>
                                    <th>Sold</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $array = Report::salesReport($start_date, $end_date, $type, $service, $product, $category, $status);
                                $total = count($array);
                                $total_qty = 0;
                                $total_amount = 0;
                                foreach ($array as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . User::get_date($value["created_on"]) . '</td>';
                                    echo '<td>' . $value["invoice_number"] . '</td>';
                                    echo '<td>' . Patient::getData($value["patient"], 'pat_id') . '</td>';
                                    if ($value["servicetype"] == 'Medicine') {
                                        echo '<td>' . Product::getData($value["item"], 'title') . '</td>';
                                    } else {
                                        echo '<td>' . Service::getData($value["service"], 'title') . '</td>';
                                    }
                                    echo '<td>' . round($value["sold"], 2) . '</td>';
                                    echo '<td class="text-right">' . Product::number_format_currency($value["amount"], 2, Yii::app()->session->get("currency")) . '</td>';
                                    echo '</tr>';
                                    $total_qty += $value["sold"];
                                    $total_amount += $value["amount"];
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="4" class="text-right">TOTAL: </th>
                                    <th><?php echo $total_qty; ?></th>
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