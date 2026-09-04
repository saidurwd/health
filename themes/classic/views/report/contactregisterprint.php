<?php
$this->pageTitle = 'Physiotherapy Patient Contact Register';
$start_date = $_REQUEST['start_date'];
$end_date = $_REQUEST['end_date'];
$category = $_REQUEST['category'];
$category_new = $_REQUEST['category_new'];
$admission = $_REQUEST['admission'];
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
    Physiotherapy Patient Contact Register: <?php echo User::get_date($start_date); ?> to <?php echo User::get_date($end_date); ?>
    <?php
    if ($category_new != '')
        print '; Category: ' . PatientCategoryNew::getData($category_new, 'alias');
    if ($category != '')
        print '; Sub Category: ' . PatientCategory::getData($category, 'alias');
    ?>
</h3>
<table class="table table-bordered table-striped table-hover">
    <thead>
        <tr>
            <th>SL#</th>
            <th>Date</th>
            <th>Patient ID</th>
            <th>Category</th>
            <th>Sub Category</th>
            <th>Patient Name</th>
            <th>Ref. No</th>
            <th>Age</th>
            <th>Sex</th>
            <th>Problem</th>
            <th>Address</th>
            <th>Contact No.</th> 
            <th>Referred</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $array = Report::patientContactRegister($start_date, $end_date, $category, $category_new, $admission);
        $total = count($array);
        $i = 1;
        foreach ($array as $key => $value) {
            echo '<tr>';
            echo '<td class="text-center">' . $i . '</td>';
            echo '<td>' . User::get_date($value["created_on"]) . '</td>';
            echo '<td>' . $value["pid"] . '</td>';
            echo '<td>' . PatientCategoryNew::get_full_path($value["category_new"]) . '</td>';
            echo '<td>' . PatientCategory::get_full_path($value["category"]) . '</td>';
            echo '<td>' . $value["pname"] . '</td>';
            echo '<td>' . $value["ref_no"] . '</td>';
            echo '<td>' . $value["age"] . '</td>';
            echo '<td>' . $value["sex"] . '</td>';
            echo '<td>' . $value["problem"] . '</td>';
            echo '<td>' . Patient::getPatiantAddress($value["id"]) . '</td>';
            echo '<td>' . $value["mobile"] . '</td>';
            echo '<td>' . $value["referred"] . '</td>';
            echo '</tr>';
            $i++;
        }
        ?>
    </tbody>
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