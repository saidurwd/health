<?php
/* @var $this ThanaController */
/* @var $model Thana */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'thana-form',
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
            <label class="label"><?php echo $form->labelEx($model, 'city'); ?></label>
            <label class="input">
                <?php echo $form->dropDownList($model, 'city', CHtml::listData(City::model()->findAll(array('condition' => '')), 'id', 'title'), array('empty' => 'Select a City', 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'city'); ?>
            </label>
        </section>
    </div>
    <div class="row">
        <section class="col col-6">
            <label class="label"><?php echo $form->labelEx($model, 'district'); ?></label>
            <label class="input">
                <?php echo $form->dropDownList($model, 'district', CHtml::listData(District::model()->findAll(array('condition' => '')), 'id', 'title'), array('empty' => 'Select a District', 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'district'); ?>
            </label>
        </section>
    </div>
    <div class="row">
        <section class="col col-6">
            <label class="label"><?php echo $form->labelEx($model, 'title'); ?></label>
            <label class="input">
                <?php echo $form->textField($model, 'title', array('maxlength' => 150, 'class' => 'col-sm-12', 'placeholder' => 'Thana')); ?>
                <?php echo $form->error($model, 'title'); ?>
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