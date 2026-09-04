<?php
$this->pageTitle = 'Income by Medicine';
$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
$product = $_REQUEST['product'];
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
    Income by Medicine: <?php echo User::get_date($start_date); ?> to <?php echo User::get_date($end_date); ?>
    <?php
    if ($product != '')
        print '; Product: ' . Product::getData($product, 'title');
    ?>
</h3>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>Product</th>
            <th>Sale Quantity</th>
            <th>Buy Amount</th>
            <th>Sale Amount</th>
            <th>Income</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $array = Report::MedicineIncomeReport($start_date, $end_date, $product);
        $total = count($array);
        $total_qty = 0;
        $total_buy_amount = 0;
        $total_amount = 0;
        $total_income = 0;
        foreach ($array as $key => $value) {
            $buy_rate = StockRequisition::genarateItemBuyRate($value["item"], 0, 0);
            $buy_amount = ($buy_rate * $value["quantity"]);
            $income = ($value["sale_amount"] - $buy_amount);
            echo '<tr>';
            echo '<td>' . Product::getData($value["item"], 'title') . '</td>';
            echo '<td>' . round($value["quantity"], 2) . '</td>';
            echo '<td class="text-right">' . Product::number_format_currency($buy_amount, 2, Yii::app()->session->get("currency")) . '</td>';
            echo '<td class="text-right">' . Product::number_format_currency($value["sale_amount"], 2, Yii::app()->session->get("currency")) . '</td>';
            echo '<td class="text-right">' . Product::number_format_currency($income, 2, Yii::app()->session->get("currency")) . '</td>';
            echo '</tr>';
            $total_qty += $value["quantity"];
            $total_buy_amount += $buy_amount;
            $total_amount += $value["sale_amount"];
            $total_income += $income;
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th class="text-right">TOTAL: </th>
            <th><?php echo $total_qty; ?></th>
            <th class="text-right"><?php echo Product::number_format_currency($total_buy_amount, 0, Yii::app()->session->get("currency")); ?></th>
            <th class="text-right"><?php echo Product::number_format_currency($total_amount, 0, Yii::app()->session->get("currency")); ?></th>
            <th class="text-right"><?php echo Product::number_format_currency($total_income, 0, Yii::app()->session->get("currency")); ?></th>
        </tr>
    </tfoot>
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