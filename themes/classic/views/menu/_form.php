<?php
/* @var $this MenuController */
/* @var $model Menu */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'menu-form',
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
            <?php echo $form->labelEx($model, 'parent'); ?>
        </label>
        <div class="col-md-8">
            <?php
            if ($model->isNewRecord) {
                echo Menu::get_menu_new();
            } else {
                echo Menu::get_menu_update($model->parent);
            }
            ?> 
            <?php echo $form->error($model, 'parent', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'title'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'title', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Menu Name')); ?>
            <?php echo $form->error($model, 'title', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'controller'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'controller', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Controller')); ?>
            <?php echo $form->error($model, 'controller', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'url'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'url', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'URL')); ?>
            <?php echo $form->error($model, 'url', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'icon'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'icon', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Icon')); ?>
            <?php echo $form->error($model, 'icon', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'ordering'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'ordering', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Ordering')); ?>
            <?php echo $form->error($model, 'ordering', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'status'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->dropDownList($model, 'status', array('0' => 'Inactive', '1' => 'Active'), array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'status', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'group'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->dropDownList($model, 'group', CHtml::listData(UserGroup::model()->findAll(array('condition' => '')), 'id', 'title'), array('multiple' => true, 'class' => 'select2')); ?>
            <?php echo $form->error($model, 'group', array('class' => 'text-danger')); ?>
        </div>
    </div>
</fieldset>
<div class="modal-footer">   
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' => 'btn btn-primary', 'id' => $model->id)); ?>
    <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
</div>
<?php $this->endWidget(); ?>