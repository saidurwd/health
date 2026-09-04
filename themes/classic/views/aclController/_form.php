<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'acl-controller-form',
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
                <?php echo $form->textField($model, 'controller', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Controller')); ?>
                <?php echo $form->error($model, 'controller'); ?>
            </label>
        </section>
    </div>
    <div class="row">
        <section class="col col-6">
            <label class="input">
                <?php echo $form->textField($model, 'title', array('maxlength' => 100, 'class' => 'col-sm-12', 'placeholder' => 'Title')); ?>
                <?php echo $form->error($model, 'title'); ?>
            </label>
        </section>
    </div>
    <div class="row">
        <section class="col col-6">
            <label class="select">
                <?php echo $form->dropDownList($model, 'status', array('1' => 'Yes', '0' => 'No'), array('class' => 'col-sm-12')); ?>
                <?php echo $form->error($model, 'status'); ?>
            </label>
        </section>
    </div>
</fieldset>
<footer>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
</footer>
<?php $this->endWidget(); ?>