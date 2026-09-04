<style>
    body{
        font-size: 13px;
        line-height: 25px;
        text-transform: capitalize;
    }
    .font-size{
        font-size: 13px;
    }
</style>
<?php $this->pageTitle = 'Rehabilitation Services'; ?>
<div class="row" style="margin-bottom:10px;">
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
<div class="clearfix"></div>
<hr style="margin-top:5px; border-top: 1px solid #666;" />
<h2 style="text-align: center; text-decoration: underline;">Patient Particular</h2>
<table border="0" width="100%" style="border:0px;">
    <tr>
        <td style="width:50%;">
            <strong>DATE:</strong> <?php echo date('M j, Y'); ?><br />
            <strong>NAME:</strong> <?php echo $model->name; ?><br />
            <strong>ADDRESS:</strong> <?php echo $model->address; ?><br />
            <strong>No.:</strong> <?php echo $model->pat_id; ?><br />
            <strong>Ref. No:</strong> <?php echo $model->ref_no; ?>  
        </td>
        <td>
            <strong>Age:</strong> <?=Patient::getPatiantAge($model->id)?><br />            
            <strong>SEX:</strong> <?php echo $model->sex; ?><br />
            <strong>MOBILE NO:</strong> <?php echo $model->mobile; ?><br />
            <strong>REFERRED BY:</strong> <?php echo $model->referred; ?>
        </td>
    </tr>
</table>
<hr style="margin-top:10px; border-top: 1px solid #666;" />

<div style="margin-top:30px;padding: 5px; text-align: left;">   
    <h5 style="margin-bottom:50px;">Chief Complaints:</h5>
    <h5>Services:</h5>
    <ol>
        <li>Orthopedic and Neurology Physiotherapy (Adult)</li>
        <li>Pediatric Physiotherapy</li>
        <li>Occupational Therapy</li>
        <li>Special Education</li>
        <li>Combined (PT + OT + Sp. Ed.)</li>
        <li>CBR (Assasuni; Tala; Kalaroa; Keshabpur and Khulna Center)</li>
    </ol>
    <h5 style="margin-bottom:50px; margin-top: 40px;">Referred to:</h5>
    <h5 style="margin-top: 30px;">Advice:</h5>
    <h5 style="margin-top: 30px; text-align: right;">Signature</h5>
</div>
<hr style="margin-top:85px; border-top: 1px solid #666;" />
<div class="invoice-footer" style="margin-top: 20px;">
    <p style="color:#999;">
        <?php echo Yii::app()->params['adminAddress']; ?><br />
        <span style="text-transform:lowercase;"><?php echo 'Email: ' . Yii::app()->params['rishilpiEmail']; ?></span><br />
        Physician visit time: <?php echo Yii::app()->params['physician_visit_time_2']; ?>
    </p>
</div>
<script type="text/javascript">
    setTimeout(function () {
        window.print();
    }, 5000); //giving 5 sec loading time.
</script>