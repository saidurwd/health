<?php
$this->pageTitle = 'Stock Summary';
$cid = $_REQUEST['cid'];
$itemid = $_REQUEST['itemid'];
$store = $_REQUEST['store'];
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
<h3 style="text-align: left;">Stock Summary Report</h3>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>Category</th>
            <th>Product</th>
            <th>In Qty.</th>
            <th class="text-right">In Amount</th>
            <th>Out Qty.</th>
            <th class="text-right">Out Amount</th>            
            <th>Available Qty.</th>
            <th class="text-right">Avail. Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $array = Report::stockSummaryReport($cid, $itemid, $store);
        $total = count($array);
        foreach ($array as $key => $value) {
            echo '<tr>';
            echo '<td>' . $value["category"] . '</td>';
            echo '<td>' . $value["item"] . '</td>';
            echo '<td>' . $value["in_quantity"] . '</td>';
            echo '<td class="text-right">' . Product::number_format_currency($value["in_amount"], 2, Yii::app()->session->get("currency")) . '</td>';
            echo '<td>' . $value["out_quantity"] . '</td>';
            echo '<td class="text-right">' . Product::number_format_currency($value["out_amount"], 2, Yii::app()->session->get("currency")) . '</td>';
            echo '<td>' . $value["avl_quantity"] . '</td>';
            echo '<td class="text-right">' . Product::number_format_currency($value["avl_amount"], 2, Yii::app()->session->get("currency")) . '</td>';
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