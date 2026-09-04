<?php
$this->pageTitle = 'Period Wise Stock';
$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
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
<h3 style="text-align: left;">
    Period Wise Stock Report: <?php echo User::get_date($start_date); ?> to <?php echo User::get_date($end_date); ?>
    <?php
    if ($cid > 0)
        print '; Category: ' . ProductCategory::getData($cid, 'title');
    if ($itemid > 0)
        print '; Product: ' . Product::getData($itemid, 'title');
    if ($store > 0)
        print '; Store: ' . Store::getData($store, 'title');
    ?>
</h3>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th colspan="2">&nbsp;</th>                               
            <th style="text-align:center;">OPENING BALANCE</th>                                    
            <th colspan="2" style="text-align:center;">IN</th>   
            <th colspan="2" style="text-align:center;">OUT</th>            
            <th style="text-align:center;">CLOSING BALANCE</th>
        </tr>
        <tr>
            <th>Category</th>
            <th>Product</th>                                    
            <th>Opening Qty.</th>                                    
            <th>In Qty.</th>
            <th class="text-right">In Amount</th>
            <th>Out Qty.</th>
            <th class="text-right">Out Amount</th>            
            <th>Closing Qty.</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $array = Report::periodWiseStockReport($cid, $itemid, $store, $start_date, $end_date);
        $total = count($array);
        foreach ($array as $key => $value) {
            echo '<tr>';
            echo '<td>' . $value["category"] . '</td>';
            echo '<td>' . $value["item"] . '</td>';
            echo '<td>' . number_format($value["opening_quantity"], 2, '.', ',') . '</td>';
            echo '<td>' . number_format($value["in_quantity"], 2, '.', ',') . '</td>';
            echo '<td class="text-right">' . Product::number_format_currency($value["in_amount"], 2, Yii::app()->session->get("currency")) . '</td>';
            echo '<td>' . number_format($value["out_quantity"], 2, '.', ',') . '</td>';
            echo '<td class="text-right">' . Product::number_format_currency($value["out_amount"], 2, Yii::app()->session->get("currency")) . '</td>';
            echo '<td>' . number_format($value["closing_quantity"], 2, '.', ',') . '</td>';
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