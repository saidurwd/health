<?php
/* @var $this ServiceController */
/* @var $model Service */
/* @var $form CActiveForm */
?>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'service-form',
        // Please note: When you enable ajax validation, make sure the corresponding
        // controller action is handling ajax validation correctly.
        // There is a call to performAjaxValidation() commented in generated controller code.
        // See class documentation of CActiveForm for details on this.
        'enableAjaxValidation' => false,
        'htmlOptions' => array('class' => 'smart-form'),
    ));
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
        <div class="row">
            <section class="col col-6">
                <label class="select">
                    <?php echo $form->labelEx($model, 'parent'); ?>
                    <?php echo $form->dropDownList($model, 'parent', CHtml::listData(Service::model()->findAll(array('condition' => 'parent IS NULL OR parent=0', "order" => "path")), 'id', 'title'), array('empty' => 'Select a Parent', 'class' => 'select2')); ?>
                    <?php echo $form->error($model, 'parent'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'title'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'title', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Service')); ?>
                    <?php echo $form->error($model, 'title'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'rate'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'rate', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Rate')); ?>
                    <?php echo $form->error($model, 'rate'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-3">
                <label class="label"><?php echo $form->labelEx($model, 'rate_status'); ?></label>
                <label class="select">
                    <?php echo $form->dropDownList($model, 'rate_status', array('Auto' => 'Auto', 'Manual' => 'Manual'), array('class' => 'select2')); ?>
                    <?php echo $form->error($model, 'rate_status'); ?>
                </label>
            </section>
            <section class="col col-3">
                <label class="label"><?php echo $form->labelEx($model, 'discount'); ?></label>
                <label class="select">
                    <?php echo $form->dropDownList($model, 'discount', array('No' => 'No', 'Yes' => 'Yes'), array('class' => 'select2')); ?>
                    <?php echo $form->error($model, 'discount'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-3">
                <label class="label"><?php echo $form->labelEx($model, 'service_type'); ?></label>
                <label class="select">
                    <?php echo $form->dropDownList($model, 'service_type', array('Consultation' => 'Consultation', 'Service' => 'Service'), array('empty' => 'Select Service Type', 'class' => 'select2')); ?>
                    <?php echo $form->error($model, 'service_type'); ?>
                </label>
            </section>
            <section class="col col-3">
                <label class="label"><?php echo $form->labelEx($model, 'service_grade'); ?></label>
                <label class="select">
                    <?php echo $form->dropDownList($model, 'service_grade', CHtml::listData(PatientGrade::model()->findAll(array('condition' => '')), 'id', 'title'), array('empty' => 'Select a Grade', 'class' => 'form-control')); ?>
                    <?php echo $form->error($model, 'service_grade'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-3">
                <label class="label"><?php echo $form->labelEx($model, 'ordering'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'ordering', array('maxlength' => 11, 'class' => 'col-sm-12', 'placeholder' => 'Ordering')); ?>
                    <?php echo $form->error($model, 'ordering'); ?>
                </label>
            </section>
            <section class="col col-3">
                <label class="label"><?php echo $form->labelEx($model, 'status'); ?></label>
                <label class="select">
                    <?php echo $form->dropDownList($model, 'status', array('Active' => 'Active', 'Inactive' => 'Inactive'), array('class' => 'select2')); ?>
                    <?php echo $form->error($model, 'status'); ?>
                </label>
            </section>
        </div>
    </fieldset>
    <footer>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
    </footer>
    <?php $this->endWidget(); ?>
</div><!-- form -->