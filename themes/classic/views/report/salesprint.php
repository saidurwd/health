<?php
$this->pageTitle = 'Sales Report';
$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
$type = $_REQUEST['type'];
$service = $_REQUEST['service'];
$product = $_REQUEST['product'];
$category = $_REQUEST['category'];
$status = $_REQUEST['status'];
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
    Sales Report: <?php echo User::get_date($start_date); ?> to <?php echo User::get_date($end_date); ?>
    <?php
    if ($type != '')
        print '; Type: ' . $type;
    if ($type == 'Service' && $service != '')
        print '; Service: ' . Service::getData($service, 'title');
    if ($type == 'Product' && $product != '')
        print '; Product: ' . Product::getData($product, 'title');
    if ($category != '')
        print '; Category: ' . PatientCategory::getData($category, 'alias');
    if ($status != '')
        print '; Status: ' . $status;
    ?>
</h3>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th style="text-align: center;">SL#</th>
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
        $i = 1;
        foreach ($array as $key => $value) {
            echo '<tr>';
            echo '<td style="text-align: center;">' . $i . '</td>';
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
            $i++;
        }
        ?>
        <tr>
            <th colspan="5" class="text-right">TOTAL: </th>
            <th><?php echo $total_qty; ?></th>
            <th class="text-right"><?php echo Product::number_format_currency($total_amount, 0, Yii::app()->session->get("currency")); ?></th>
        </tr>
    </tbody>
</table>
<div class="invoice-footer space-top-10">
    <div class="row">
        <div class="col-sm-12 text-right">
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
</style>