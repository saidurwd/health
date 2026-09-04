<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'acl-action-form',
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
            <label class="input">
                <?php echo $form->textField($model, 'title', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Action Title')); ?>
                <?php echo $form->error($model, 'title'); ?>
            </label>
        </section>
    </div>
    <div class="row">
        <section class="col col-6">
            <label class="input">
                <?php echo $form->textField($model, 'action', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Action')); ?>
                <?php echo $form->error($model, 'action'); ?>
            </label>
        </section>
    </div>
</fieldset>
<?php echo $form->hiddenField($model, 'controller_id', array('value' => $_GET['cid'], 'class' => 'span5', 'maxlength' => 150)); ?>
<footer>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
</footer>
<?php $this->endWidget(); ?>