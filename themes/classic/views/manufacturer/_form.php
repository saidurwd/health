<?php
/* @var $this ManufacturerController */
/* @var $model Manufacturer */
/* @var $form CActiveForm */
?>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'manufacturer-form',
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
                <p class="note">Fields with <span class="required text-danger">*</span> are required.</p>
            </section>
        </div>
        <div class="row">
            <section class="col col-12">
                <?php echo $form->errorSummary($model, '<i class="fa fa-bell text-danger"></i> Please fix the following input errors:', '', array('class' => 'text-danger', 'style' => 'padding-left:20px;')); ?>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'title'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'title', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Manufacturer')); ?>
                    <?php echo $form->error($model, 'title'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'email'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'email', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Email')); ?>
                    <?php echo $form->error($model, 'email'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'phone'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'phone', array('maxlength' => 100, 'class' => 'col-sm-12', 'placeholder' => 'Phone')); ?>
                    <?php echo $form->error($model, 'phone'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'mobile'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'mobile', array('maxlength' => 100, 'class' => 'col-sm-12', 'placeholder' => 'Mobile')); ?>
                    <?php echo $form->error($model, 'mobile'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'address'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'address', array('maxlength' => 100, 'class' => 'col-sm-12', 'placeholder' => 'Address')); ?>
                    <?php echo $form->error($model, 'address'); ?>
                </label>
            </section>
        </div>
    </fieldset>
    <footer>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
    </footer>
    <?php $this->endWidget(); ?>
</div><!-- form -->