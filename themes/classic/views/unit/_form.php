<?php
/* @var $this UnitController */
/* @var $model Unit */
/* @var $form CActiveForm */
?>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'unit-form',
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
                <label class="label"><?php echo $form->labelEx($model, 'full_name'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'full_name', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Full Name')); ?>
                    <?php echo $form->error($model, 'full_name'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'formal_name'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'formal_name', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Formal Name')); ?>
                    <?php echo $form->error($model, 'formal_name'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'decimal_place'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'decimal_place', array('maxlength' => 4, 'class' => 'col-sm-12', 'placeholder' => 'Decimal Place')); ?>
                    <?php echo $form->error($model, 'decimal_place'); ?>
                </label>
            </section>
        </div>
    </fieldset>
    <footer>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
    </footer>
    <?php $this->endWidget(); ?>
</div><!-- form -->