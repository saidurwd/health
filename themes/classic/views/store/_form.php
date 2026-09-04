<?php
/* @var $this StoreController */
/* @var $model Store */
/* @var $form CActiveForm */
?>

<div class="form">
    <?php
    $form = $this->beginWidget('CActiveForm', array(
        'id' => 'store-form',
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
                    <?php echo $form->labelEx($model, 'parent'); ?>
                    <?php echo Store::getStores('Store', 'parent', $model->parent); ?>
                    <?php echo $form->error($model, 'parent'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'title'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'title', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Store')); ?>
                    <?php echo $form->error($model, 'title'); ?>
                </label>
            </section>
        </div>
        <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'location'); ?></label>
                <label class="input">
                    <?php echo $form->textField($model, 'location', array('maxlength' => 4, 'class' => 'col-sm-12', 'placeholder' => 'Location')); ?>
                    <?php echo $form->error($model, 'location'); ?>
                </label>
            </section>
        </div>
    <div class="row">
            <section class="col col-6">
                <label class="label"><?php echo $form->labelEx($model, 'incharge'); ?></label>
                <label class="input">
                    <?php echo $form->dropDownList($model, 'incharge', CHtml::listData(User::model()->findAll(array('condition' => '', "order" => "full_name")), 'id', 'full_name'), array('empty' => 'Select a User', 'class' => 'select2')); ?>
                    <?php echo $form->error($model, 'incharge'); ?>
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