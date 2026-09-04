<?php
/* @var $this InvoicesController */
/* @var $model Invoices */
?>
<div class="row" style="margin-bottom:20px;font-size: 16px;">
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
<div style="clear:both;border-bottom:1px solid #666;"></div>
<div style="background-color:#666;color:#FFF;height:20px;text-align:center;font-size:16px;text-transform:uppercase;padding:2px 0px;">Health Card</div>
<div style="margin-top:20px;text-transform:capitalize;">
    <div style="float:left;width:220px;"><strong>Date:</strong> <?php echo date("F j, Y", strtotime($model->created_on)); ?></div>
    <div style="float:left;"><strong>No.:</strong> <?php echo $model->pat_id; ?></div>
    <div style="clear:both;"></div>
    <div style="float:left;"><strong>Name:</strong> <?php echo $model->name; ?></div>
    <div style="clear:both;"></div>
    <div style="float:left;width:220px;"><strong>Sex:</strong> <?php echo $model->sex; ?></div>
    <div style="float:left;"><strong>Age:</strong> <?php echo Patient::getPatiantAge($model->id); ?></div>    
    <div style="clear:both;"></div>
    <div style="float:left;"><strong>Address:</strong> <?php echo Patient::getPatiantAddress($model->id); ?></div>
</div>
<div style="clear:both;"></div>
<div class="row" style="border-top:1px solid #666;padding-top:10px;color:#333; margin-top:10px;">
    <div class="col-sm-12">
        <div style="font-size: 14px;">
            <?php echo Yii::app()->params['adminAddress']; ?><br />
            Email: <?php echo Yii::app()->params['rishilpiEmail']; ?><br />
            Physician visit time: <?php echo Yii::app()->params['physician_visit_time']; ?><br />
        </div>
    </div>   
</div>
<script type="text/javascript">
<!--
    window.print();
//-->
</script>