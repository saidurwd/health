<?php
$this->pageTitle = 'Patient by Disease';
$this->breadcrumbs = array(
    'Reports' => array('disease'),
    'Patient by Disease',
);

$start_date = date('Y-m-1');
$end_date = date('Y-m-t');
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
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-bar-chart-o fa-fw "></i> 
            Reports
            <span>>
                Patient by Disease
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
                    <h2>Patient by Disease</h2>
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
                            <section class="col col-1">
                                <?php echo CHtml::htmlButton('<i class="fa fa-search"></i> ' . Yii::t('Common', 'search'), array('type' => 'submit', 'class' => 'btn btn-primary btn-sm btn-block')); ?>
                            </section>
                            <section class="col col-1">
                                <?php echo CHtml::link('<i class="fa fa-print"></i> Print', array('diseaseprint', 'start_date' => $start_date, 'end_date' => $end_date), array('class' => 'btn btn-info btn-sm btn-block', 'target' => '_blank')); ?>
                            </section>
                        </div>
                        <?php $this->endWidget(); ?>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="text-align:center;">SL NO.</th>
                                    <th style="text-align:center;">Name of Disease</th>
                                    <th style="text-align:center;">No of Patient</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $array = Report::diseaseReport($start_date, $end_date);
                                $total = count($array);
                                $total_patient = 0;
                                $total_amount = 0;
                                $i = 1;
                                foreach ($array as $key => $value) {
                                    echo '<tr>';
                                    echo '<td style="text-align:center;width:100px;">' . $i . '</td>';
                                    echo '<td style="text-align:left;">' . Disease::getData($value["diagnosis"], "title") . '</td>';
                                    echo '<td style="text-align:center;">' . $value["total"] . '</td>';
                                    echo '</tr>';
                                    $total_patient += $value["total"];
                                    $i++;
                                }
                                ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="text-align:center;"></th>
                                    <th style="text-align:center;">TOTAL: </th>
                                    <th style="text-align:center;"><?php echo $total_patient; ?></th>
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