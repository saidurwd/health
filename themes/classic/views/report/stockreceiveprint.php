<?php
$this->pageTitle = 'Stock Receive';
$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
$cid = $_REQUEST['cid'];
$itemid = $_REQUEST['itemid'];
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
<h3 style="text-align: left;">Stock Receive: <?php echo User::get_date($start_date); ?> to <?php echo User::get_date($end_date); ?></h3>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>Receive#</th>
            <th>Date</th>
            <th>Supplier</th>
            <th>Store</th>
            <th>Product</th>
            <th>Lot No.</th>
            <th>Expiry</th>
            <th>Quantity</th>
            <th>Rate</th>
            <th>Amount</th>
            <th>Available</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $array = Report::stockReceiveReport($cid, $itemid, $start_date, $end_date);
        $total = count($array);
        foreach ($array as $key => $value) {
            echo '<tr>';
            echo '<td>' . $value["receive_number"] . '</td>';
            echo '<td>' . User::get_date($value["receive_date"]) . '</td>';
            echo '<td>' . $value["supplier"] . '</td>';
            echo '<td>' . Store::get_full_path($value["storeid"]) . '</td>';
            echo '<td>' . $value["item"] . '</td>';
            echo '<td>' . $value["batch"] . '</td>';
            echo '<td>' . User::get_date($value["expiry"]) . '</td>';
            echo '<td>' . round($value["quantity"],2) . ' ' . Product::getItemUOM($value["itemid"]) . '</td>';
            echo '<td class="text-right">' . Product::number_format_currency($value["rate"], 2, Yii::app()->session->get("currency")) . '</td>';
            echo '<td class="text-right">' . Product::number_format_currency($value["total"], 2, Yii::app()->session->get("currency")) . '</td>';
            echo '<td>' . StockSummary::availableQty($value["storeid"], $value["itemid"], $value["batchid"]) . ' ' . Product::getItemUOM($value["itemid"]) . '</td>';
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