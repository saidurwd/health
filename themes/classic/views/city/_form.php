<?php
/* @var $this CityController */
/* @var $model City */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'city-form',
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
            <label class="label"><?php echo $form->labelEx($model, 'country'); ?></label>
            <label class="input">
                <?php echo $form->dropDownList($model, 'country', CHtml::listData(Country::model()->findAll(array('condition' => '')), 'id', 'title'), array('empty' => 'Select a Country', 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'country'); ?>
            </label>
        </section>
    </div>
    <div class="row">
        <section class="col col-6">
            <label class="label"><?php echo $form->labelEx($model, 'state'); ?></label>
            <label class="input">
                <?php echo $form->dropDownList($model, 'state', CHtml::listData(State::model()->findAll(array('condition' => '')), 'id', 'title'), array('empty' => 'Select a State', 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'state'); ?>
            </label>
        </section>
    </div>
    <div class="row">
        <section class="col col-6">
            <label class="label"><?php echo $form->labelEx($model, 'title'); ?></label>
            <label class="input">
                <?php echo $form->textField($model, 'title', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'City')); ?>
                <?php echo $form->error($model, 'title'); ?>
            </label>
        </section>
    </div>
    <div class="row">
        <section class="col col-6">
            <label class="label"><?php echo $form->labelEx($model, 'city_2_code'); ?></label>
            <label class="input">
                <?php echo $form->textField($model, 'city_2_code', array('maxlength' => 2, 'class' => 'col-sm-12', 'placeholder' => 'Code 2')); ?>
                <?php echo $form->error($model, 'city_2_code'); ?>
            </label>
        </section>
    </div>
    <div class="row">
        <section class="col col-6">
            <label class="label"><?php echo $form->labelEx($model, 'city_3_code'); ?></label>
            <label class="input">
                <?php echo $form->textField($model, 'city_3_code', array('maxlength' => 3, 'class' => 'col-sm-12', 'placeholder' => 'Code 3')); ?>
                <?php echo $form->error($model, 'city_3_code'); ?>
            </label>
        </section>
    </div>
    <div class="row">
        <section class="col col-6">
            <label class="label"><?php echo $form->labelEx($model, 'status'); ?></label>
            <label class="select">
                <?php echo $form->dropDownList($model, 'status', array('Active' => 'Active', 'Inactive' => 'Inactive'), array('class' => 'input-xs')); ?>
                <?php echo $form->error($model, 'status'); ?>
            </label>
        </section>
    </div>
</fieldset>
<footer>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
</footer>
<?php $this->endWidget(); ?>
