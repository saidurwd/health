<?php
/* @var $this ProductController */
/* @var $model Product */
/* @var $form CActiveForm */
?>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'product-form',
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
                <label class="select">
                    <?php echo $form->labelEx($model, 'category'); ?>
                    <?php echo ProductCategory::getProductCategory('Product', 'category', $model->category); ?>
                    <?php echo $form->error($model, 'category'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'title'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'title', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Product')); ?>
                    <?php echo $form->error($model, 'title'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'product_code'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'product_code', array('maxlength' => 12, 'class' => 'col-sm-12', 'placeholder' => 'Product Code')); ?>
                    <?php echo $form->error($model, 'product_code'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="select">
                    <?php echo $form->labelEx($model, 'unit'); ?>
                    <?php echo $form->dropDownList($model, 'unit', CHtml::listData(Unit::model()->findAll(array('condition' => '', "order" => "full_name")), 'id', 'full_name'), array('empty' => 'Select a Unit', 'class' => 'select2')); ?>
                    <?php echo $form->error($model, 'unit'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'threshold_value'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'threshold_value', array('maxlength' => 12, 'class' => 'col-sm-12', 'placeholder' => 'Threshold Value')); ?>
                    <?php echo $form->error($model, 'threshold_value'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'minimum_storage_limit'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'minimum_storage_limit', array('maxlength' => 12, 'class' => 'col-sm-12', 'placeholder' => 'Minimum Storage Limit')); ?>
                    <?php echo $form->error($model, 'minimum_storage_limit'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'description'); ?></label>
                <label class="input">
                    <?php echo $form->textArea($model, 'description', array('rows' => 3, 'cols' => 50, 'class' => 'col-sm-12')); ?>
                    <?php echo $form->error($model, 'description'); ?>
                </label>
            </section>
        </div>
    </fieldset>   
    <footer>
        <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
    </footer>
    <?php $this->endWidget(); ?>
</div><!-- form -->