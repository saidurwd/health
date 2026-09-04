<style>
    body{
        font-size: 13px;
        line-height: 30px;
        text-transform: capitalize;
    }
    .font-size{
        font-size: 13px;
    }
</style>
<?php $this->pageTitle = 'Patient Registration Form'; ?>
<div class="row" style="margin-bottom:10px;font-size: 14px;">
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
</div>
<div class="clearfix"></div>
<hr style="margin-top:10px; border-top: 1px solid #666;" />
<h2 style="text-align: center; text-decoration: underline;">Patient Registration Form</h2>
<div style="margin-top:30px;padding: 5px; text-align: left; font-size: 14px;">
    <h5><?php echo $model->category_new0->alias; ?></h5>
    <table border="0" width="100%" style="border:0px;">
        <tr>
            <td style="width:34%;"><strong>DATE:</strong> <?php echo date('M j, Y'); ?></td>
            <td style="width:33%;"><strong>Ref. No:</strong> <?php echo $model->ref_no; ?> </td>
            <td style="width:33%;"><strong>Grade:</strong> <?php echo PatientGrade::getData($model->patient_grade, 'title'); ?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td colspan="2"><strong>No.:</strong> <?php echo $model->pat_id; ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Name of Patient:</strong> <?php echo $model->name; ?></td>
            <td><strong>Age:</strong> <?php echo $model->age . ' ' . $model->age_type; ?></td>
        </tr>
        <tr>
            <td colspan="3"><strong>Name of Husband/Father:</strong> <?php echo $model->emergency_name; ?></td>
        </tr>
    </table>
    <table border="0" width="100%" style="border:0px;">
        <tr>
            <td style="width:50%;"><strong>Village:</strong> <?php echo $model->village; ?></td>
            <td><strong>Post Office:</strong> <?php echo $model->post; ?></td>
        </tr>
        <tr>
            <td><strong>P.S:</strong> <?php echo Thana::getData($model->thana, 'title'); ?></td>
            <td><strong>Dist:</strong> <?php echo District::getData($model->district, 'title'); ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Contact No:</strong> <?php echo $model->mobile; ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Occupation:</strong> <?php echo $model->occupation; ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Guardian Occupation:</strong> <?php echo $model->guardian_occupation; ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong>No. of family member:</strong> <?php echo $model->no_of_family_member; ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Earning Member:</strong> <?php echo $model->earning_member; ?></td>
        </tr>
        <tr>
            <td colspan="2"><strong>Earning Source:</strong> <?php echo $model->earning_source; ?></td>
        </tr>
    </table>
</div>
<div style="margin-top: 100px;">
    <p style="color:#666;">I have read, fully understand and agree to payment, consent for treatment. I hereby declare that the Information provided above is true, correct and complete.</p>
</div>
<div style="margin-top: 150px;">
    <table border="0" width="100%" style="border:0px;">
        <tr>
            <td style="width:25%; text-align: left; border-top: 1px dotted #666;">Signature of patient</td>
            <td style="width:50%;">&nbsp;</td>
            <td style="width:25%; text-align: right; border-top: 1px dotted #666;">Authorized (In-Charge)</td>
        </tr>
    </table>
</div>
<script type="text/javascript">
    setTimeout(function () {
        window.print();
    }, 5000); //giving 5 sec loading time.
</script>