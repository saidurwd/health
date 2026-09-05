<?php
$this->pageTitle = 'Patient Category';
$this->breadcrumbs = array(
    'Reports' => array('category'),
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
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/highcharts.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/modules/exporting.js', CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-bar-chart-o fa-fw "></i> 
            Reports
            <span>>
                Patient Category
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
                    <h2>Patient Category</h2>
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
                                <?php echo CHtml::link('<i class="fa fa-print"></i> Print', array('categoryprint', 'start_date' => $start_date, 'end_date' => $end_date), array('class' => 'btn btn-info btn-sm btn-block', 'target' => '_blank')); ?>
                            </section>
                        </div>
                        <?php $this->endWidget(); ?>
                        <?php
                        $array = Report::patientAttendanceAge($start_date, $end_date);
                        $male = isset($array[0]) ? $array[0] : array('AGE_GROUP_1' => 0, 'AGE_GROUP_2' => 0, 'AGE_GROUP_3' => 0, 'AGE_GROUP_4' => 0, 'total' => 0);
                        $female = isset($array[1]) ? $array[1] : array('AGE_GROUP_1' => 0, 'AGE_GROUP_2' => 0, 'AGE_GROUP_3' => 0, 'AGE_GROUP_4' => 0, 'total' => 0);
                        $totalAll = $male['total'] + $female['total'];
                        ?>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th colspan="6" style="text-align:left;">Patient Attendance (Age Analysis)</th>                                    
                                </tr>
                                <tr>
                                    <th style="text-align:center;width:100px;">SL NO.</th>
                                    <th style="text-align:left;">Particulars</th>
                                    <th style="text-align:center;">Male Patients</th>
                                    <th style="text-align:center;">Female Patients</th>                                    
                                    <th style="text-align:center;">Total</th>
                                    <th style="text-align:center;">Patients Ratio</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style="text-align:center;">1</td>
                                    <td>0 - 5 Years</td>
                                    <td style="text-align:center;"><?php echo $male['AGE_GROUP_1']; ?></td>
                                    <td style="text-align:center;"><?php echo $female['AGE_GROUP_1']; ?></td>
                                    <td style="text-align:center;"><?php echo ($male['AGE_GROUP_1'] + $female['AGE_GROUP_1']); ?></td>
                                    <td style="text-align:center;"><?php echo $totalAll > 0 ? ROUND((($male['AGE_GROUP_1'] + $female['AGE_GROUP_1']) * 100) / $totalAll, 2) : 0; ?>%</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;">2</td>
                                    <td>6 - 14 Years</td>
                                    <td style="text-align:center;"><?php echo $male['AGE_GROUP_2']; ?></td>
                                    <td style="text-align:center;"><?php echo $female['AGE_GROUP_2']; ?></td>
                                    <td style="text-align:center;"><?php echo ($male['AGE_GROUP_2'] + $female['AGE_GROUP_2']); ?></td>
                                    <td style="text-align:center;"><?php echo $totalAll > 0 ? ROUND((($male['AGE_GROUP_2'] + $female['AGE_GROUP_2']) * 100) / $totalAll, 2) : 0; ?>%</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;">3</td>
                                    <td>15 - 24 Years</td>
                                    <td style="text-align:center;"><?php echo $male['AGE_GROUP_3']; ?></td>
                                    <td style="text-align:center;"><?php echo $female['AGE_GROUP_3']; ?></td>
                                    <td style="text-align:center;"><?php echo ($male['AGE_GROUP_3'] + $female['AGE_GROUP_3']); ?></td>
                                    <td style="text-align:center;"><?php echo $totalAll > 0 ? ROUND((($male['AGE_GROUP_3'] + $female['AGE_GROUP_3']) * 100) / $totalAll, 2) : 0; ?>%</td>
                                </tr>
                                <tr>
                                    <td style="text-align:center;">4</td>
                                    <td>Above 25 Years</td>
                                    <td style="text-align:center;"><?php echo $male['AGE_GROUP_4']; ?></td>
                                    <td style="text-align:center;"><?php echo $female['AGE_GROUP_4']; ?></td>
                                    <td style="text-align:center;"><?php echo ($male['AGE_GROUP_4'] + $female['AGE_GROUP_4']); ?></td>
                                    <td style="text-align:center;"><?php echo $totalAll > 0 ? ROUND((($male['AGE_GROUP_4'] + $female['AGE_GROUP_4']) * 100) / $totalAll, 2) : 0; ?>%</td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th style="text-align:center;"></th>
                                    <th style="text-align:right;">TOTAL: </th>
                                    <th style="text-align:center;"><?php echo $male['total']; ?></th>
                                    <th style="text-align:center;"><?php echo $female['total']; ?></th>
                                    <th style="text-align:center;"><?php echo $totalAll; ?></th>
                                    <th style="text-align:center;">100.00%</th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="row" style="margin: 10px 0px;">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <script type="text/javascript">
                                    $(function () {
                                        $('#containerAge').highcharts({
                                            chart: {
                                                type: 'pie',
                                                options3d: {
                                                    enabled: true,
                                                    alpha: 45,
                                                    beta: 0
                                                }
                                            },
                                            title: {
                                                text: 'Age Wise Patients Category'
                                            },
                                            tooltip: {
                                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                            },
                                            credits: {
                                                enabled: false
                                            },
                                            plotOptions: {
                                                pie: {
                                                    allowPointSelect: true,
                                                    cursor: 'pointer',
                                                    depth: 35,
                                                    dataLabels: {
                                                        enabled: true,
                                                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                                        style: {
                                                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                                        }
                                                    }
                                                }
                                            },
                                            series: [{
                                                    type: 'pie',
                                                    name: 'Age',
                                                    data: [
                                                        ['Male 0 - 5 Years', <?php echo $male['AGE_GROUP_1']; ?>],
                                                        ['Female 0 - 5 Years', <?php echo $female['AGE_GROUP_1']; ?>],
                                                        ['Male 6 - 14 Years', <?php echo $male['AGE_GROUP_2']; ?>],
                                                        ['Female 6 - 14 Years', <?php echo $female['AGE_GROUP_2']; ?>],
                                                        ['Male 15 - 24 Years', <?php echo $male['AGE_GROUP_3']; ?>],
                                                        ['Female 15 - 24 Years', <?php echo $female['AGE_GROUP_3']; ?>],
                                                        ['Male Above 25 Years', <?php echo $male['AGE_GROUP_4']; ?>],
                                                        ['Female Above 25 Years', <?php echo $female['AGE_GROUP_4']; ?>]
                                                    ]
                                                }]
                                        });
                                    });
                                </script>
                                <div id="containerAge" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                            </div>
                        </div>
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th colspan="3" style="text-align:left;">Patient Attendance (Sex Analysis)</th>                                    
                                </tr>
                                <tr>
                                    <th style="text-align:center;">SL NO.</th>
                                    <th style="text-align:left;">Particulars</th>
                                    <th style="text-align:center;">No of Patients</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $arraySex = Report::patientAttendanceSex($start_date, $end_date);
                                $total_patient = 0;
                                $i = 1;
                                foreach ($arraySex as $key => $value) {
                                    echo '<tr>';
                                    echo '<td style="text-align:center;width:100px;">' . $i . '</td>';
                                    echo '<td style="text-align:left;">' . htmlspecialchars($value["sex"]) . '</td>';
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
                                    <th style="text-align:right;">TOTAL: </th>
                                    <th style="text-align:center;"><?php echo $total_patient; ?></th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class="row" style="margin: 10px 0px;">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
                                <script type="text/javascript">
                                    $(function () {
                                        $('#containerSex').highcharts({
                                            chart: {
                                                type: 'pie',
                                                options3d: {
                                                    enabled: true,
                                                    alpha: 45,
                                                    beta: 0
                                                }
                                            },
                                            title: {
                                                text: 'Sex Wise Patients Category'
                                            },
                                            tooltip: {
                                                pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                                            },
                                            credits: {
                                                enabled: false
                                            },
                                            plotOptions: {
                                                pie: {
                                                    allowPointSelect: true,
                                                    cursor: 'pointer',
                                                    depth: 35,
                                                    dataLabels: {
                                                        enabled: true,
                                                        format: '<b>{point.name}</b>: {point.percentage:.1f} %',
                                                        style: {
                                                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                                                        }
                                                    }
                                                }
                                            },
                                            series: [{
                                                    type: 'pie',
                                                    name: 'Sex',
                                                    data: <?php echo json_encode(array_map(function($row){ return array($row['sex'], (int)$row['total']); }, $arraySex)); ?>
                                                }]
                                        });
                                    });
                                </script>
                                <div id="containerSex" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
                            </div>
                        </div>
                        <!-- end widget content -->
                    </div>
                    <!-- end widget div -->
                </div>
            </div>
            <!-- end widget -->
        </article>
        <!-- WIDGET END -->
    </div>
    <!-- end row -->
</section>
<!-- end widget grid -->