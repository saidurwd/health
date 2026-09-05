<?php
/* @var $this PatientController */
/* @var $model Patient */
/* @var $form CActiveForm */
?>
<?php
//Yii::app()->clientScript->registerScript('show_hide', "
//    $(document).ready(function(){
//        //$('#ESPNONESP').hide();
//        $('#Patient_category').change(function(){
//            if (this.value == '3'){
//                $('#Patient_ref_no').val('');
//                $('#ESPNONESP').hide();                   
//            } else {
//                $('#ESPNONESP').show();
//            }
//        });
//    });
//");
?>
<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'patient-form',
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'smart-form'),
    ));
    Yii::app()->clientScript->registerScript('chained', '
    $(document).ready(function(){
        $("#Patient_district").chained("#Patient_country");
        $("#Patient_thana").chained("#Patient_district");
    });
', CClientScript::POS_END);
    ?>

    <fieldset>
        <div class="row">
            <section class="col col-12">
                <p class="note">Fields with <span class="required">*</span> are required.</p>
            </section>
        </div>
        <div class="row">
            <section class="col col-12">
                <?php echo $form->errorSummary($model, '<i class="fa fa-bell text-danger"></i> Please fix the following input errors:', '', array('class' => 'text-danger', 'style' => 'padding-left:20px;')); ?>
            </section>
        </div>  

        <div class="panel-group smart-accordion-default" id="patient-form-accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#patient-form-accordion" href="#patient-info">
                            <i class="fa fa-user fa-fw"></i> Patient Information
                        </a>
                    </h4>
                </div>
                <div id="patient-info" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <div class="row">
                            <section class="col col-3">
                                <label class="label"><?php echo $form->labelEx($model, 'category_new'); ?></label>
                                <label class="input">
                                    <?php echo PatientCategoryNew::getPatientCategoryForm('Patient', 'category_new', $model->category_new); ?>
                                    <?php echo $form->error($model, 'category_new'); ?>
                                </label>
                            </section>
                            <section class="col col-3">
                                <label class="label"><?php echo $form->labelEx($model, 'category'); ?></label>
                                <label class="input">
                                    <?php echo PatientCategory::getPatientCategoryForm('Patient', 'category', $model->category); ?>
                                    <?php echo $form->error($model, 'category'); ?>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="label"><?php echo $form->labelEx($model, 'ref_no'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'ref_no', array('maxlength' => 50, 'class' => 'col-sm-12', 'placeholder' => 'Reference No')); ?>
                                    <?php echo $form->error($model, 'ref_no'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'name'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'name', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Name')); ?>
                                    <?php echo $form->error($model, 'name'); ?>
                                </label>
                            </section>
                        </div>        
                        <div class="row">
                            <section class="col col-3">
                                <label class="label"><?php echo $form->labelEx($model, 'age'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'age', array('maxlength' => 6, 'class' => 'col-sm-12', 'placeholder' => 'Age')); ?>
                                    <?php echo $form->error($model, 'age'); ?>
                                </label>
                            </section>
                            <section class="col col-1">
                                <label class="label"><?php echo $form->labelEx($model, '&nbsp;'); ?></label>
                                <label class="select">
                                    <?php echo $form->dropDownList($model, 'age_type', array('Year' => 'Year', 'Month' => 'Month'), array('class' => 'select2')); ?>
                                    <?php echo $form->error($model, 'age_type'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'birth_date'); ?></label>
                                <label class="input">
                                    <div class="input-group">
                                        <?php echo $form->textField($model, 'birth_date', array('class' => 'col-sm-12 datepicker', 'placeholder' => 'Date of Birth', 'data-dateformat' => 'yy-mm-dd')); ?>
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <?php echo $form->error($model, 'birth_date'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'blood_groop'); ?></label>
                                <label class="select">
                                    <?php echo $form->dropDownList($model, 'blood_groop', array('O-' => 'O-', 'O+' => 'O+', 'A-' => 'A-', 'A+' => 'A+', 'B-' => 'B-', 'B+' => 'B+', 'AB-' => 'AB-', 'AB+' => 'AB+'), array('empty' => 'Select a Blood Group', 'class' => 'col-sm-12')); ?>
                                    <?php echo $form->error($model, 'blood_groop'); ?>
                                </label>
                            </section>
                        </div>
                        <div class="row">            
                            <section class="col col-3">
                                <label class="label"><?php echo $form->labelEx($model, 'patient_grade'); ?></label>
                                <label class="input">
                                    <?php echo $form->dropDownList($model, 'patient_grade', CHtml::listData(PatientGrade::model()->findAll(array('condition' => '')), 'id', 'title'), array('empty' => 'Select a Grade', 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'patient_grade'); ?>
                                </label>
                            </section>
                            <section class="col col-1">
                                <label class="label"><?php echo $form->labelEx($model, 'admission'); ?></label>
                                <label class="input">
                                    <?php echo $form->dropDownList($model, 'admission', array('No' => 'No', 'Yes' => 'Yes'), array('class' => 'select2')); ?>
                                    <?php echo $form->error($model, 'admission'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'problem'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'problem', array('maxlength' => 400, 'class' => 'col-sm-12', 'placeholder' => 'Problem')); ?>
                                    <?php echo $form->error($model, 'problem'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'referred'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'referred', array('maxlength' => 250, 'class' => 'col-sm-12', 'placeholder' => 'Referred')); ?>
                                    <?php echo $form->error($model, 'referred'); ?>
                                </label>
                            </section>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#patient-form-accordion" href="#personal-info">
                            <i class="fa fa-id-card fa-fw"></i> Personal Information
                        </a>
                    </h4>
                </div>
                <div id="personal-info" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="row">
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'marital_status'); ?></label>
                                <label class="select">
                                    <?php echo $form->dropDownList($model, 'marital_status', array('Married' => 'Married', 'Unmarried' => 'Unmarried', 'Others' => 'Others'), array('class' => 'col-sm-12')); ?>
                                    <?php echo $form->error($model, 'marital_status'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'email'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'email', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Email')); ?>
                                    <?php echo $form->error($model, 'email'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'national_id'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'national_id', array('maxlength' => 50, 'class' => 'col-sm-12', 'placeholder' => 'National ID')); ?>
                                    <?php echo $form->error($model, 'national_id'); ?>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'spouse'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'spouse', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Spouse')); ?>
                                    <?php echo $form->error($model, 'spouse'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'occupation'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'occupation', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Occupation')); ?>
                                    <?php echo $form->error($model, 'occupation'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'religion'); ?></label>
                                <label class="select">
                                    <?php echo $form->dropDownList($model, 'religion', array('Muslim' => 'Muslim', 'Hindu' => 'Hindu', 'Christian' => 'Christian', 'Buddhist' => 'Buddhist', 'No Religion' => 'No Religion'), array('empty' => 'Select a Religion', 'class' => 'col-sm-12')); ?>
                                    <?php echo $form->error($model, 'religion'); ?>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'mobile'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'mobile', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Mobile')); ?>
                                    <?php echo $form->error($model, 'mobile'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'address'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'address', array('maxlength' => 250, 'class' => 'col-sm-12', 'placeholder' => 'Address')); ?>
                                    <?php echo $form->error($model, 'address'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'country'); ?></label>
                                <label class="input">
                                    <?php echo $form->dropDownList($model, 'country', CHtml::listData(Country::model()->findAll(array('condition' => 'status="Active"')), 'id', 'title'), array('empty' => 'Select a Country', 'class' => 'form-control')); ?>
                                    <?php echo $form->error($model, 'country'); ?>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'district'); ?></label>
                                <label class="input">
                                    <?php echo District::getRelatedDistrictCountry('Patient', 'district', $model->district, 'form-control'); ?>
                                    <?php echo $form->error($model, 'district'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'thana'); ?></label>
                                <label class="input">
                                    <?php echo Thana::getRelatedThanaDistrict('Patient', 'thana', $model->thana, 'form-control'); ?>
                                    <?php echo $form->error($model, 'thana'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'sex'); ?></label>
                                <label class="select">
                                    <?php echo $form->dropDownList($model, 'sex', array('Male' => 'Male', 'Female' => 'Female'), array('empty' => 'Select a Sex', 'class' => 'col-sm-12')); ?>
                                    <?php echo $form->error($model, 'sex'); ?>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'village'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'village', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Village')); ?>
                                    <?php echo $form->error($model, 'village'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'post'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'post', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Post Office')); ?>
                                    <?php echo $form->error($model, 'post'); ?>
                                </label>
                            </section>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#patient-form-accordion" href="#guardian-info">
                            <i class="fa fa-users fa-fw"></i> Guardian Particular
                        </a>
                    </h4>
                </div>
                <div id="guardian-info" class="panel-collapse collapse">
                    <div class="panel-body">
                        <div class="row">
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'emergency_name'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'emergency_name', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Guardian Name')); ?>
                                    <?php echo $form->error($model, 'emergency_name'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'guardian_occupation'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'guardian_occupation', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Guardian Occupation')); ?>
                                    <?php echo $form->error($model, 'guardian_occupation'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'no_of_family_member'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'no_of_family_member', array('maxlength' => 50, 'class' => 'col-sm-12', 'placeholder' => 'Nr. of family member')); ?>
                                    <?php echo $form->error($model, 'no_of_family_member'); ?>
                                </label>
                            </section>
                        </div>
                        <div class="row">
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'earning_member'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'earning_member', array('maxlength' => 50, 'class' => 'col-sm-12', 'placeholder' => 'Earning Member')); ?>
                                    <?php echo $form->error($model, 'earning_member'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'earning_source'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'earning_source', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Earning Source')); ?>
                                    <?php echo $form->error($model, 'earning_source'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'emergency_relation'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'emergency_relation', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Relation')); ?>
                                    <?php echo $form->error($model, 'emergency_relation'); ?>
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="label"><?php echo $form->labelEx($model, 'emergency_contact'); ?></label>
                                <label class="input">
                                    <?php echo $form->textField($model, 'emergency_contact', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Contact')); ?>
                                    <?php echo $form->error($model, 'emergency_contact'); ?>
                                </label>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </fieldset>   
    <footer>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
    </footer>
    <?php $this->endWidget(); ?>
</div><!-- form -->
