<?php
$this->pageTitle = 'Expiration Report';
$this->breadcrumbs = array(
    'Reports' => array('expiration'),
    'Expiration Report',
);

if (empty($_REQUEST['item'])) {
    $item = '';
} else {
    $item = $_REQUEST['item'];
}
if (empty($_REQUEST['store'])) {
    $store = '';
} else {
    $store = $_REQUEST['store'];
}
if (empty($_REQUEST['expiry'])) {
    $expiry = 0;
} else {
    $expiry = $_REQUEST['expiry'];
}
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-bar-chart-o fa-fw "></i> 
            Reports
            <span>>
                Expiration Report
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
                    <h2>Expiration Report</h2>
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
                                    <?php echo CHtml::dropDownList('item', isset($_REQUEST['item']) ? CHtml::encode($_REQUEST['item']) : $item, CHtml::listData(Product::model()->findAll(array('condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'All Products', 'class' => 'select2')); ?>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <?php echo CHtml::dropDownList('store', isset($_REQUEST['store']) ? CHtml::encode($_REQUEST['store']) : $store, CHtml::listData(Store::model()->findAll(array('condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'All Stores', 'class' => 'select2')); ?>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <?php echo CHtml::dropDownList('expiry', isset($_REQUEST['expiry']) ? CHtml::encode($_REQUEST['expiry']) : $store, array('0' => 'Expired', '7' => 'Within one week', '14' => 'Within two weeks', '30' => 'Within one month', '60' => 'Within two months', '90' => 'Within three months', '182' => 'Within six months'), array('class' => 'select2')); ?>
                                </label>
                            </section>
                            <section class="col col-1">
                                <?php echo CHtml::htmlButton('<i class="fa fa-search"></i> ' . Yii::t('Common', 'search'), array('type' => 'submit', 'class' => 'btn btn-primary btn-sm btn-block')); ?>
                            </section>
                            <section class="col col-1">
                                <?php echo CHtml::link('<i class="fa fa-print"></i> ' . Yii::t('Report', 'print'), array('report/expirationprint', 'item' => (int) @$item, 'store' => (int) @$store, 'expiry' => @$expiry), array('class' => 'btn btn-info btn-sm btn-block', 'target' => '_blank')); ?>
                            </section> 
                        </div>
                        <?php $this->endWidget(); ?>
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