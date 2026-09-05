<?php
$this->pageTitle = 'Patient Category';
$start_date = isset($_REQUEST['start_date']) ? $_REQUEST['start_date'] : date('Y-m-1');
$end_date = isset($_REQUEST['end_date']) ? $_REQUEST['end_date'] : date('Y-m-t');
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/highcharts.js', CClientScript::POS_END);
$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/highchart404/modules/exporting.js', CClientScript::POS_END);
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
<h3 style="text-align: left;">Patient Category: <?php echo User::get_date($start_date); ?> to <?php echo User::get_date($end_date); ?></h3>
<?php
$array = Report::patientAttendanceAge($start_date, $end_date);
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
            <td style="text-align:center;"><?php echo $array[0]['AGE_GROUP_1']; ?></td>
            <td style="text-align:center;"><?php echo $array[1]['AGE_GROUP_1']; ?></td>
            <td style="text-align:center;"><?php echo ($array[0]['AGE_GROUP_1'] + $array[1]['AGE_GROUP_1']); ?></td>
            <td style="text-align:center;"><?php echo ROUND((($array[0]['AGE_GROUP_1'] + $array[1]['AGE_GROUP_1']) * 100) / ($array[0]['total'] + $array[1]['total']), 2); ?>%</td>
        </tr>
        <tr>
            <td style="text-align:center;">2</td>
            <td>6 - 14 Years</td>
            <td style="text-align:center;"><?php echo $array[0]['AGE_GROUP_2']; ?></td>
            <td style="text-align:center;"><?php echo $array[1]['AGE_GROUP_2']; ?></td>
            <td style="text-align:center;"><?php echo ($array[0]['AGE_GROUP_2'] + $array[1]['AGE_GROUP_2']); ?></td>
            <td style="text-align:center;"><?php echo ROUND((($array[0]['AGE_GROUP_2'] + $array[1]['AGE_GROUP_2']) * 100) / ($array[0]['total'] + $array[1]['total']), 2); ?>%</td>
        </tr>
        <tr>
            <td style="text-align:center;">3</td>
            <td>15 - 24 Years</td>
            <td style="text-align:center;"><?php echo $array[0]['AGE_GROUP_3']; ?></td>
            <td style="text-align:center;"><?php echo $array[1]['AGE_GROUP_3']; ?></td>
            <td style="text-align:center;"><?php echo ($array[0]['AGE_GROUP_3'] + $array[1]['AGE_GROUP_3']); ?></td>
            <td style="text-align:center;"><?php echo ROUND((($array[0]['AGE_GROUP_3'] + $array[1]['AGE_GROUP_3']) * 100) / ($array[0]['total'] + $array[1]['total']), 2); ?>%</td>
        </tr>
        <tr>
            <td style="text-align:center;">4</td>
            <td>Above 25 Years</td>
            <td style="text-align:center;"><?php echo $array[0]['AGE_GROUP_4']; ?></td>
            <td style="text-align:center;"><?php echo $array[1]['AGE_GROUP_4']; ?></td>
            <td style="text-align:center;"><?php echo ($array[0]['AGE_GROUP_4'] + $array[1]['AGE_GROUP_4']); ?></td>
            <td style="text-align:center;"><?php echo ROUND((($array[0]['AGE_GROUP_4'] + $array[1]['AGE_GROUP_4']) * 100) / ($array[0]['total'] + $array[1]['total']), 2); ?>%</td>
        </tr>
    </tbody>
    <tfoot>
        <tr>
            <th style="text-align:center;"></th>
            <th style="text-align:right;">TOTAL: </th>
            <th style="text-align:center;"><?php echo $array[0]['total']; ?></th>
            <th style="text-align:center;"><?php echo $array[1]['total']; ?></th>
            <th style="text-align:center;"><?php echo ($array[0]['total'] + $array[1]['total']); ?></th>
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
                                ['Male 0 - 5 Years', <?php echo $array[0]['AGE_GROUP_1']; ?>],
                                ['Female 0 - 5 Years', <?php echo $array[1]['AGE_GROUP_1']; ?>],
                                ['Male 6 - 14 Years', <?php echo $array[0]['AGE_GROUP_2']; ?>],
                                ['Female 6 - 14 Years', <?php echo $array[1]['AGE_GROUP_2']; ?>],
                                ['Male 15 - 24 Years', <?php echo $array[0]['AGE_GROUP_3']; ?>],
                                ['Female 15 - 24 Years', <?php echo $array[1]['AGE_GROUP_3']; ?>],
                                ['Male Above 25 Years', <?php echo $array[0]['AGE_GROUP_4']; ?>],
                                ['Female Above 25 Years', <?php echo $array[1]['AGE_GROUP_4']; ?>]
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
        $total = count($arraySex);
        $total_patient = 0;
        $i = 1;
        foreach ($arraySex as $key => $value) {
            echo '<tr>';
            echo '<td style="text-align:center;width:100px;">' . $i . '</td>';
            echo '<td style="text-align:left;">' . $value["sex"] . '</td>';
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
                            data: [
                                ['<?php echo $arraySex[0]['sex']; ?>', <?php echo $arraySex[0]['total']; ?>],
                                ['<?php echo $arraySex[1]['sex']; ?>', <?php echo $arraySex[1]['total']; ?>],
                            ]
                        }]
                });
            });
        </script>
        <div id="containerSex" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
    </div>
</div>
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