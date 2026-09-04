<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'user-form',
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
            <?php echo $form->labelEx($model, 'password'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->passwordField($model, 'password', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Password')); ?>
            <?php echo $form->error($model, 'password', array('class' => 'text-danger')); ?>
        </div>
    </div>
</fieldset>
<div class="modal-footer">   
    <?php echo CHtml::submitButton('Submit', array('class' => 'btn btn-primary', 'id' => $model->id)); ?>
    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
</div>
<?php $this->endWidget(); ?>