<?php
/* @var $this InvoiceController */
/* @var $model InvoiceParent */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'invoice_number'); ?>
		<?php echo $form->textField($model,'invoice_number',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'patient'); ?>
		<?php echo $form->dropDownList($model,'patient', CHtml::listData(Patient::model()->findAll(array('select' => 'id, CONCAT(name," [",pat_id,"]") AS name', 'condition' => '', 'order' => 'name')), 'id', 'name'), array('empty' => 'All', 'class' => 'select2')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->dropDownList($model,'status', CHtml::listData(TransectionStatus::model()->findAll(array('condition' => 'user_view=1 AND transection_type=5', "order" => "id")), 'status_id', 'status_title'), array('empty' => 'All', 'class' => 'select2')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'payment_status'); ?>
		<?php echo $form->dropDownList($model,'payment_status', array('' => 'All', 'Paid' => 'Paid', 'Unpaid' => 'Unpaid'), array('class' => 'select2')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'invoice_by'); ?>
		<?php echo $form->dropDownList($model,'invoice_by', CHtml::listData(User::model()->findAll(array('condition' => '', 'order' => 'full_name')), 'id', 'full_name'), array('empty' => 'All', 'class' => 'select2')); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'invoice_date'); ?>
		<?php echo $form->textField($model,'invoice_date',array('size'=>60,'maxlength'=>250,'class'=>'datepicker','data-dateformat'=>'yy-mm-dd')); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
		<?php echo CHtml::link('Reset', array('admin'), array('class' => 'btn btn-default')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->
