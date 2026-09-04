<?php
$this->pageTitle = Yii::t('Report', 'Consumption');
$this->breadcrumbs = array(
    Yii::t('Report', 'reports'),
    Yii::t('Report', 'Consumption')
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
if (empty($_POST['projectid'])) {
    $pid = 0;
} else {
    $pid = $_POST['projectid'];
}
if (empty($_POST['categoryid'])) {
    $cid = 0;
} else {
    $cid = $_POST['categoryid'];
}
if (empty($_POST['item'])) {
    $item = 0;
} else {
    $item = $_POST['item'];
}
if (empty($_POST['assignmentid'])) {
    $assid = 0;
} else {
    $assid = $_POST['assignmentid'];
}
if (empty($_POST['userid'])) {
    $uid = 0;
} else {
    $uid = $_POST['userid'];
}
?>
<script type="text/javascript" charset="utf-8">
    $(function () {
        $("#assignmentid").chained("#projectid");
    });
</script>  
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-bar-chart-o fa-fw "></i> 
            <?php echo Yii::t('Report', 'reports'); ?>
            <span>>
                <?php echo Yii::t('Report', 'Consumption'); ?>
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
                    <h2><?php echo Yii::t('Report', 'Consumption'); ?></h2>
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
                                    <?php echo ItemCategory::get_item_category_search($cid); ?>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <?php echo CHtml::dropDownList('item', isset($_REQUEST['item']) ? CHtml::encode($_REQUEST['item']) : $item, CHtml::listData(Item::model()->findAll(array('select' => 'id, CONCAT(item_name," [",item_code,"]") AS item_name', 'condition' => 'company_id=' . (int) Yii::app()->user->companyid, 'order' => 'item_name')), 'id', 'item_name'), array('empty' => 'All Items', 'class' => 'select2')); ?>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <?php echo Project::get_project_search($pid, 'projectid'); ?>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <?php echo Assignment::get_related_assignment_search($assid, 'assignmentid'); ?>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <?php echo CHtml::dropDownList('userid', isset($_REQUEST['userid']) ? CHtml::encode($_REQUEST['userid']) : '', CHtml::listData(Client::model()->findAll(array('select' => 'id, CONCAT(full_name," ",last_name) AS full_name', 'condition' => 'company_id=' . (int) Yii::app()->user->companyid, 'order' => 'full_name')), 'id', 'full_name'), array('empty' => 'All Users', 'class' => 'select2')); ?>
                                </label>
                            </section>
                            <section class="col col-1">
                                <div class="input-group">
                                    <?php echo CHtml::textField('start_date', isset($_REQUEST['start_date']) ? CHtml::encode($_REQUEST['start_date']) : $start_date, array('class' => 'form-control datepicker', 'placeholder' => 'Start Date', 'data-dateformat' => 'yy-mm-dd')); ?>
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </section>
                            <section class="col col-1">
                                <div class="input-group">
                                    <?php echo CHtml::textField('end_date', isset($_REQUEST['end_date']) ? CHtml::encode($_REQUEST['end_date']) : $end_date, array('class' => 'form-control datepicker', 'placeholder' => 'End Date', 'data-dateformat' => 'yy-mm-dd')); ?>                                    
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </section>                            
                        </div>
                        <div class="row">
                            <section class="col col-10">

                            </section>
                            <section class="col col-1">
                                <?php echo CHtml::htmlButton('<i class="fa fa-search"></i> ' . Yii::t('Common', 'search'), array('type' => 'submit', 'class' => 'btn btn-primary btn-sm btn-block')); ?>
                            </section>
                            <section class="col col-1">
                                <?php echo CHtml::link('<i class="fa fa-print"></i> ' . Yii::t('Report', 'print'), array('report/consumptionprint', 'pid' => (int) $pid, 'cid' => (int) $cid, 'item' => $item, 'assid' => (int) $assid, 'uid' => (int) $uid, 'start_date' => $start_date, 'end_date' => $end_date), array('class' => 'btn btn-info btn-sm btn-block', 'target' => '_blank')); ?>
                            </section>
                        </div>
                        <?php $this->endWidget(); ?>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Issue#</th>
                                    <th>Date</th>
                                    <th>Batch</th>
                                    <th>Store</th>
                                    <th>Project</th>
                                    <th>Assignment</th>
                                    <th>Item</th>
                                    <th>Quantity</th>
                                    <th>Rate</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $array = Report::consumptionReport($cid, $item, $pid, $assid, $uid, $start_date, $end_date);
                                $total = count($array);
                                foreach ($array as $key => $value) {
                                    echo '<tr>';
                                    echo '<td>' . $value["issue_number"] . '</td>';
                                    echo '<td>' . Client::get_date($value["issue_date"]) . '</td>';
                                    echo '<td>' . $value["batch"] . '</td>';
                                    echo '<td>' . Store::get_full_path($value["storeid"]) . '</td>';
                                    echo '<td>' . Project::get_full_path($value["project"]) . '</td>';
                                    echo '<td>' . Assignment::get_title($value["assignment"]) . '</td>';
                                    echo '<td>' . $value["item"] . '</td>';
                                    echo '<td>' . $value["quantity"] . ' ' . $value["uom"] . '</td>';
                                    echo '<td class="text-right">' . Item::number_format_currency($value["rate"], 2, Yii::app()->session->get("currency")) . '</td>';
                                    echo '<td class="text-right">' . Item::number_format_currency($value["total"], 2, Yii::app()->session->get("currency")) . '</td>';
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