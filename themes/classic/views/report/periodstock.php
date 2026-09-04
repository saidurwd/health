<?php
$this->pageTitle = 'Period Wise Stock';
$this->breadcrumbs = array(
    'Reports' => array('periodstock'),
    'Period Wise Stock',
);

//$start_date = date('Y-m-d');
$start_date = date('Y-m-1');
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
if (empty($_POST['categoryid'])) {
    $cid = '';
} else {
    $cid = $_POST['categoryid'];
}
if (empty($_POST['itemid'])) {
    $itemid = '';
} else {
    $itemid = $_POST['itemid'];
}
if (empty($_POST['store'])) {
    $store = 0;
} else {
    $store = $_POST['store'];
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
                Period Wise Stock
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
                    <h2>Period Wise Stock</h2>
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
                                <label class="select">
                                    <?php echo CHtml::dropDownList('store', isset($_REQUEST['store']) ? CHtml::encode($_REQUEST['store']) : $store, CHtml::listData(Store::model()->findAll(array('condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'All Stores', 'class' => 'select2')); ?>
                                </label>
                            </section>
                            <section class="col col-1">
                                <?php echo CHtml::htmlButton('<i class="fa fa-search"></i> ' . Yii::t('Common', 'search'), array('type' => 'submit', 'class' => 'btn btn-primary btn-sm btn-block')); ?>
                            </section>
                            <section class="col col-1">
                                <?php echo CHtml::link('<i class="fa fa-print"></i> ' . Yii::t('Report', 'print'), array('report/periodstockprint', 'cid' => (int) $cid, 'itemid' => (int) $itemid, 'store' => $store, 'start_date' => $start_date, 'end_date' => $end_date,), array('class' => 'btn btn-info btn-sm btn-block', 'target' => '_blank')); ?>
                            </section> 
                        </div>
                        <?php $this->endWidget(); ?>
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