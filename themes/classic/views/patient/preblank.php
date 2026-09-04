<?php
/* @var $this InvoicesController */
/* @var $model Invoices */
?>
<div class="row" style="margin-bottom: 10px;font-size: 16px;">
    <div style="float:left; width:150px;">
        <?php echo CHtml::image(Yii::app()->theme->baseUrl . '/img/rishilpi_logo.png', 'Logo', array('alt' => 'Logo', 'class' => '', 'title' => '', 'style' => '')); ?>
    </div>
    <div style="float:left; width:350px;margin-top:25px;">
        <div style="font-size: 16px;">
            <?php echo Yii::app()->params['topTag']; ?><br />
            <?php echo Yii::app()->params['adminName']; ?><br />
            <?php echo Yii::app()->params['bottomTag']; ?>
        </div>
    </div>   
    <div style="float:left;width:200px;text-align:right;">
        <?php echo PatientPrescription::getData($_REQUEST['preid'], 'pre_number') . '<br />'; ?>
        <?php echo 'PID: ' . $model->pat_id . '<br />'; ?>
        <?php echo 'REF#: ' . $model->ref_no . '<br />'; ?>
        <?php echo 'CATEGORY: ' . PatientCategory::getData($model->category, 'alias'); ?>
    </div>
</div>
<div style="clear:both;border-bottom:1px solid #666;"></div>
<div style="margin-top:10px; font-size:16px;">
    <div style="float:left;width:230px;"><strong>Name:</strong> <?php echo $model->name; ?></div>   
    <div style="float:left;width:180px;"><strong>Age:</strong> <?php echo Patient::getPatiantAge($model->id); ?></div>
    <div style="float:left;width:90px;"><strong>Sex:</strong> <?php echo $model->sex; ?></div>
    <div style="float:left;"><strong>Date:</strong> <?php echo date("M j, Y", strtotime(date('Y-m-d'))); ?></div>
</div>
<div style="clear:both;"></div>
<div style="margin-top:10px;font-size:16px;">
    <div style="float:left;width:400px;"><strong>Address:</strong> <?php echo Patient::getPatiantAddress($model->id); ?></div>
    <div style="float:left;"><strong>Diagnosis:</strong> <?php //echo Disease::getData(PatientPrescription::getData($_REQUEST['preid'], 'diagnosis'), 'title');  ?></div>    
</div>
<div style="clear:both;"></div>
<div style="margin-top: 20px;">
    <div style="float:left;width:250px; border-right: 1px solid #999;">
        <h3 style="margin-bottom: 50px;">C/C: <?php echo PatientPrescription::getData($_REQUEST['preid'], 'cc'); ?></h3>
        <h3 style="margin-bottom: 50px;">O/E: <?php echo PatientPrescription::getData($_REQUEST['preid'], 'oe'); ?></h3>
        <h3 style="margin-bottom: 50px;">B/P: <?php echo PatientPrescription::getData($_REQUEST['preid'], 'bp'); ?></h3>
        <h3 style="margin-bottom: 50px;">Pulse: <?php echo PatientPrescription::getData($_REQUEST['preid'], 'pulse'); ?></h3>
        <h3 style="margin-bottom: 50px;">Temp: <?php echo PatientPrescription::getData($_REQUEST['preid'], 'temp'); ?></h3>
        <h3 style="margin-bottom: 50px;">Advice: <?php echo PatientPrescription::getData($_REQUEST['preid'], 'advice'); ?></h3>
        <h3 style="margin-bottom: 50px;">Admission: <?php echo PatientPrescription::getData($_REQUEST['preid'], 'admission'); ?></h3>
    </div>
    <div style="width:425px;float:left;margin-left: 10px;">
        <h1 style="padding-left: 10px;">Rx</h1>
        <p><?php echo PatientPrescription::getData($_REQUEST['preid'], 'rx'); ?></p>       
    </div>
</div>
<div style="clear:both;"></div>
<div class="row" style="border-top:1px solid #666; padding-top:20px; color:#333;">
    <div class="col-sm-12">
        <div style="font-size: 14px;">
            <?php echo Yii::app()->params['adminAddress']; ?><br />
            Email: <?php echo Yii::app()->params['rishilpiEmail']; ?><br />
            Physician visit time: <?php echo Yii::app()->params['physician_visit_time']; ?><br />
        </div>
    </div>   
</div>
<script type="text/javascript">
    setTimeout(function () {
        window.print();
    }, 5000); //giving 5 sec loading time.
</script>