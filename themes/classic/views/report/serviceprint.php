<?php
$this->pageTitle = 'Rehabilitation Service Bill Report';
$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
$category = $_REQUEST['category'];
$category_new = $_REQUEST['category_new'];
$status = $_REQUEST['status'];
$service = $_REQUEST['service'];
?>
<div class="row" style="margin-bottom:10px;font-size: 14px;">
    <div style="float:left; width:150px;">
        <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/img/rishilpi_logo.png', 'Logo', array('alt' => 'Logo', 'class' => '', 'title' => '', 'style' => '')); ?>
    </div>
    <div style="float:left; width:250px;margin-top:25px;">
        <div style="font-size: 16px;">
            <?php echo Yii::app()->params['topTag']; ?><br />
            <?php echo Yii::app()->params['adminName']; ?><br />
            <?php echo Yii::app()->params['bottomTag']; ?>
        </div>
    </div>   
</div>
<hr />
<div class="clearfix"></div>
<h3 style="text-align: left;">
    Rehabilitation Service Bill Report: <?php echo User::get_date($start_date); ?> to <?php echo User::get_date($end_date); ?>
    <?php
    if ($category_new != '')
        print '; Category: ' . PatientCategoryNew::getData($category_new, 'alias');
    if ($category != '')
        print '; Sub Category: ' . PatientCategory::getData($category, 'alias');
    if ($service != '')
        print '; Service: ' . Service::getData($service, 'title');
    if ($status != '')
        print '; Status: ' . $status;
    ?>
</h3>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Date</th>
            <th>Patient ID</th>
            <th>Reference#</th>
            <th>Grade</th>
            <th>Category</th>
            <th>Sub Category</th>
            <th>Name</th>
            <th>Note</th>
            <th>Consultation/Admission</th>
            <th>Service</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $array = Report::serviceBillReport($start_date, $end_date, $category, $category_new, $service, $status);
        $total = count($array);
        $total_srv = 0;
        $total_med = 0;
        $total_amount = 0;
        $i = 1;
        foreach ($array as $key => $value) {
            echo '<tr>';
            echo '<td class="text-center">' . $i . '</td>';
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
            $total_amount += $value["amount_consultation"] + $value["amount_service"];
            $i++;
        }
        ?>
        <tr>
            <th colspan="9" class="text-right">GRAND TOTAL: </th>
            <th class="text-right"><?php echo Product::number_format_currency($total_srv, 0, Yii::app()->session->get("currency")); ?></th>
            <th class="text-right"><?php echo Product::number_format_currency($total_med, 0, Yii::app()->session->get("currency")); ?></th>
            <th class="text-right"><?php echo Product::number_format_currency($total_amount, 0, Yii::app()->session->get("currency")); ?></th>
        </tr>
    </tbody>
</table>
<div class="invoice-footer space-top-10">
    <div class="row">
        <div class="col-sm-6">
            Displaying <?php echo $total; ?> results.
        </div>
        <div class="col-sm-6 text-right">
            <p class="note"><?php echo Yii::app()->params['print_note']; ?></p>
        </div>
    </div>
</div>
<script type="text/javascript">
    setTimeout(function () {
        window.print();
    }, 5000); //giving 5 sec loading time.
</script>
<style>
    body{
        font-size: 10px;
    }
    .font-size{
        font-size: 10px;
    }
    .text-center{
        text-align: center;
    }
</style>