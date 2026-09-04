<?php
$this->pageTitle = 'Invoice By Prescription';
$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
$patient = $_REQUEST['patient'];
$type = $_REQUEST['type'];
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
<h3 style="text-align: left;">
    <?php
    if ($patient != '') {
        print Patient::getData($patient, 'name');
    } else {
        print 'Patient ';
    }
    ?>
    Invoice By Prescription: <?php echo User::get_date($start_date); ?> to <?php echo User::get_date($end_date); ?></h3>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>SL#</th>                                                                        
            <th>Date</th>
            <th>Patient ID</th>
            <th>Prescription#</th>    
            <th>Invoice#</th>
            <th>Payment Status</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $array = Report::InvoiceByPrescription($start_date, $end_date, $patient, $type);
        $total = count($array);
        $total_amount = 0;
        $i = 1;
        foreach ($array as $key => $value) {
            echo '<tr>';
            echo '<td class="text-center">' . $i . '</td>';
            echo '<td>' . User::get_date($value["created_on"]) . '</td>';
            echo '<td class="text-left">' . $value["pid"] . '</td>';
            echo '<td class="text-left">' . $value["pre_number"] . '</td>';
            echo '<td class="text-left">' . $value["invoice_number"] . '</td>';
            echo '<td>' . $value["payment_status"] . '</td>';
            echo '<td class="text-right">' . Product::number_format_currency($value["total_amount"], 2, Yii::app()->session->get("currency")) . '</td>';
            echo '</tr>';
            $total_amount += $value["total_amount"];
            $i++;
        }
        ?>
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6" class="text-right">TOTAL: </th>
            <th class="text-right"><?php echo Product::number_format_currency($total_amount, 0, Yii::app()->session->get("currency")); ?></th>
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