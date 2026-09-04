<?php
$this->pageTitle = 'Patient by Disease';
$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
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
<h3 style="text-align: left;">Patient by Disease: <?php echo User::get_date($start_date); ?> to <?php echo User::get_date($end_date); ?></h3>
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