<?php
/* @var $this UserStatusController */
/* @var $model UserStatus */
/* @var $form CActiveForm */
?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'user-status-form',
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
    'htmlOptions' => array('class' => 'form-horizontal'),
        ));
?>
<fieldset>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'title'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'title', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Status')); ?>
            <?php echo $form->error($model, 'title', array('class' => 'text-danger')); ?>
        </div>
    </div>
</fieldset>
<div class="modal-footer">   
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' => 'btn btn-primary', 'id' => $model->id)); ?>
    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
</div>
<?php $this->endWidget(); ?>