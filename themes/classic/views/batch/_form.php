<?php
/* @var $this BatchController */
/* @var $model Batch */
/* @var $form CActiveForm */
?>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'batch-form',
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
                    <?php echo $form->textField($model, 'title', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Batch')); ?>
                    <?php echo $form->error($model, 'title'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'manufacturing'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'manufacturing', array('maxlength' => 50, 'class' => 'col-sm-12', 'placeholder' => 'Manufacturing')); ?>
                    <?php echo $form->error($model, 'manufacturing'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'expiry'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'expiry', array('maxlength' => 50, 'class' => 'col-sm-12', 'placeholder' => 'Expiry')); ?>
                    <?php echo $form->error($model, 'expiry'); ?>
                </label>
            </section>
        </div>
    </fieldset>
    <footer>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
    </footer>
    <?php $this->endWidget(); ?>
</div><!-- form -->