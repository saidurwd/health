<?php
$this->pageTitle = 'Stock Summary';
$this->breadcrumbs = array(
    'Reports' => array('stocksummary'),
    'Stock Summary',
);

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
                Stock Summary
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
                    <h2>Stock Summary</h2>
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
                                <label class="select">
                                    <?php echo CHtml::dropDownList('store', isset($_REQUEST['store']) ? CHtml::encode($_REQUEST['store']) : $store, CHtml::listData(Store::model()->findAll(array('condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'All Stores', 'class' => 'select2')); ?>
                                </label>
                            </section>
                            <section class="col col-1">
                                <?php echo CHtml::htmlButton('<i class="fa fa-search"></i> ' . Yii::t('Common', 'search'), array('type' => 'submit', 'class' => 'btn btn-primary btn-sm btn-block')); ?>
                            </section>
                            <section class="col col-1">
                                <?php echo CHtml::link('<i class="fa fa-print"></i> ' . Yii::t('Report', 'print'), array('report/stocksummaryprint', 'cid' => (int) $cid, 'itemid' => (int) $itemid, 'store' => $store), array('class' => 'btn btn-info btn-sm btn-block', 'target' => '_blank')); ?>
                            </section> 
                        </div>
                        <?php $this->endWidget(); ?>
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