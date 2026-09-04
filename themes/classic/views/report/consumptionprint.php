<?php
$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
$pid = $_REQUEST['pid'];
$item = $_REQUEST['item'];
$cid = $_REQUEST['cid'];
$assid = $_REQUEST['assid'];
$uid = $_REQUEST['uid'];
?>
<div class="row">
    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">
        <?php //echo Company::get_logo_report(Yii::app()->user->companyid); ?>
    </div>
    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">
        <?php echo Company::get_address_report(Yii::app()->user->companyid); ?>
    </div>
</div>
<h4 style="text-align: left;"><?php echo Yii::t('Report', 'Consumption'); ?> <?php echo Client::get_date($start_date); ?> to <?php echo Client::get_date($end_date); ?></h4>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>Issue#</th>
            <th>Date</th>
            <th>Batch</th>
            <th>Store</th>
            <th>Project</th>
            <th>Assignment</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Rate</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $array = Report::consumptionReport($cid, $item, $pid, $assid, $uid, $start_date, $end_date);
        $total = count($array);
        foreach ($array as $key => $value) {
            echo '<tr>';
            echo '<td>' . $value["issue_number"] . '</td>';
            echo '<td>' . Client::get_date($value["issue_date"]) . '</td>';
            echo '<td>' . $value["batch"] . '</td>';
            echo '<td>' . Store::get_full_path($value["storeid"]) . '</td>';
            echo '<td>' . Project::get_full_path($value["project"]) . '</td>';
            echo '<td>' . Assignment::get_title($value["assignment"]) . '</td>';
            echo '<td>' . $value["item"] . '</td>';
            echo '<td>' . $value["quantity"] . ' ' . $value["uom"] . '</td>';
            echo '<td>' . Item::number_format_currency($value["rate"], 2, Yii::app()->session->get("currency")) . '</td>';
            echo '<td>' . Item::number_format_currency($value["total"], 2, Yii::app()->session->get("currency")) . '</td>';
            echo '</tr>';
        }
        ?>
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
    .text-center{
        text-align: center;
    }
</style>