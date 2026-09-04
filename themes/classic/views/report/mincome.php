<?php
$this->pageTitle = 'Income by Medicine';
$this->breadcrumbs = array(
    'Reports' => array('sales'),
    'Income by Medicine',
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
if (empty($_POST['product'])) {
    $product = NULL;
} else {
    $product = $_POST['product'];
}
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-bar-chart-o fa-fw "></i> 
            Reports
            <span>>
                Income by Medicine
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
                    <h2>Income by Medicine</h2>
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
                            <section class="col col-2" id="divProduct">
                                <label class="select">
                                    <?php echo Product::getProductCategoryReport('product', $product); ?>
                                </label>
                            </section>                           
                            <section class="col col-1">
                                <?php echo CHtml::htmlButton('<i class="fa fa-search"></i> ' . Yii::t('Common', 'search'), array('type' => 'submit', 'class' => 'btn btn-primary btn-sm btn-block')); ?>
                            </section>
                            <section class="col col-1">
                                <?php echo CHtml::link('<i class="fa fa-print"></i> Print', array('mincomeprint', 'start_date' => $start_date, 'end_date' => $end_date, 'product' => $product), array('class' => 'btn btn-info btn-sm btn-block', 'target' => '_blank')); ?>
                            </section>
                        </div>
                        <?php $this->endWidget(); ?>
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