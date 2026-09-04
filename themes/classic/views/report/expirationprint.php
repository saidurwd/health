<?php
$this->pageTitle = 'Expiration Report';
$item = $_REQUEST['item'];
$store = $_REQUEST['store'];
$expiry = $_REQUEST['expiry'];
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
<h3>Expiration Report</h3>
<div class="clearfix"></div>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>Product</th>
            <th>Batch</th>
            <th>Quantity</th>
            <th>Store</th>
            <th class="text-right">Rate</th>
            <th class="text-right">Total Price</th>            
            <th>Expiry</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $array = Report::expirationReport($item, $store, $expiry);
        $total = count($array);
        foreach ($array as $key => $value) {
            echo '<tr>';
            echo '<td>' . $value["product"] . '</td>';
            echo '<td>' . Batch::getBatch($value["batch"]) . '</td>';
            echo '<td>' . $value["quantity"] . ' ' . $value["unit"] . '</td>';
            echo '<td>' . Store::get_full_path($value["storeid"]) . '</td>';
            echo '<td class="text-right">' . Product::number_format_currency($value["rate"], 2, Yii::app()->session->get("currency")) . '</td>';
            echo '<td class="text-right">' . Product::number_format_currency($value["amount"], 2, Yii::app()->session->get("currency")) . '</td>';
            echo '<td>' . User::get_date($value["expiry"]) . '</td>';
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
