<?php
$this->pageTitle = 'Stock Receive';
$this->breadcrumbs = array(
    'Reports' => array('stockreceive'),
    'Stock Receive',
);

$ts = strtotime(date('Y-m-d'));
$start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
$start_date = date('Y-m-d', $start);
$end_date = date('Y-m-d', strtotime('next saturday', $start));
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
if (empty($_POST['categoryid'])) {
    $cid = 0;
} else {
    $cid = $_POST['categoryid'];
}
if (empty($_POST['itemid'])) {
    $itemid = 0;
} else {
    $itemid = $_POST['itemid'];
}
?>
<script type="text/javascript" charset="utf-8">
    $(function () {
        $("#itemid").chained("#categoryid");
    });
</script>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-bar-chart-o fa-fw "></i> 
            Reports
            <span>>
                Stock Receive
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
                    <h2>Stock Receive</h2>
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
                                    <?php echo ProductCategory::getProductCategorySearch($cid); ?>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <?php echo Product::get_related_item_search('itemid', $itemid); ?>
                                </label>
                            </section>
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
                            <section class="col col-1">
                                <?php echo CHtml::htmlButton('<i class="fa fa-search"></i> ' . Yii::t('Common', 'search'), array('type' => 'submit', 'class' => 'btn btn-primary btn-sm btn-block')); ?>
                            </section>
                            <section class="col col-1">
                                <?php echo CHtml::link('<i class="fa fa-print"></i> ' . Yii::t('Report', 'print'), array('stockreceiveprint', 'cid' => (int) $cid, 'itemid' => (int) $itemid, 'start_date' => $start_date, 'end_date' => $end_date), array('class' => 'btn btn-info btn-sm btn-block', 'target' => '_blank')); ?>
                            </section>
                        </div>
                        <?php $this->endWidget(); ?>
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
                                    echo '<td>' . $value["quantity"] . ' ' . Product::getItemUOM($value["itemid"]) . '</td>';
                                    echo '<td class="text-right">' . Product::number_format_currency($value["rate"], 2, Yii::app()->session->get("currency")) . '</td>';
                                    echo '<td class="text-right">' . Product::number_format_currency($value["total"], 2, Yii::app()->session->get("currency")) . '</td>';
                                    echo '<td>' . StockSummary::availableQty($value["storeid"], $value["itemid"], $value["batchid"]) . ' ' . Product::getItemUOM($value["itemid"]) . '</td>';
                                    echo '</tr>';
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