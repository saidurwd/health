<?php
/* @var $this PatientController */
/* @var $model Patient */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'category_new'); ?>
		<?php echo $form->dropDownList($model,'category_new', CHtml::listData(PatientCategoryNew::model()->findAll(array('condition' => 'parent=0 OR parent IS NULL', 'order' => 'title')), 'id', 'title'), array('empty' => 'All', 'class' => 'select2')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'mobile'); ?>
		<?php echo $form->textField($model,'mobile',array('size'=>60,'maxlength'=>150)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sex'); ?>
		<?php echo $form->dropDownList($model,'sex', array('' => 'All', 'Male' => 'Male', 'Female' => 'Female'), array('class' => 'select2')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'blood_groop'); ?>
		<?php echo $form->dropDownList($model,'blood_groop', array('' => 'All', 'O−' => 'O−', 'O+' => 'O+', 'A−' => 'A−', 'A+' => 'A+', 'B−' => 'B−', 'B+' => 'B+', 'AB−' => 'AB−', 'AB+' => 'AB+'), array('class' => 'select2')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'admission'); ?>
		<?php echo $form->dropDownList($model,'admission', array('' => 'All', 'No' => 'No', 'Yes' => 'Yes'), array('class' => 'select2')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'referred'); ?>
		<?php echo $form->textField($model,'referred',array('size'=>60,'maxlength'=>250)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
		<?php echo CHtml::link('Reset', array('admin'), array('class' => 'btn btn-default')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
