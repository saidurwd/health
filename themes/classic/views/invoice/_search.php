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
		<select id="search_InvoiceParent_patient" name="InvoiceParent[patient]" class="select2 select2-ajax" data-select-width="100%" style="width:100%">
			<option value="">All</option>
			<?php if (!empty($model->patient) || !empty($_GET['InvoiceParent']['patient'])): ?>
				<?php $pid = !empty($model->patient) ? $model->patient : $_GET['InvoiceParent']['patient']; ?>
				<?php $patient = Patient::model()->findByPk($pid); ?>
				<?php if ($patient): ?>
					<option value="<?php echo $patient->id; ?>" selected="selected"><?php echo CHtml::encode($patient->name . ' [' . $patient->pat_id . ']'); ?></option>
				<?php endif; ?>
			<?php endif; ?>
		</select>
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

<script type="text/javascript">
function initSearchPatientSelect2() {
    var $patient = $('#search_InvoiceParent_patient');
    if ($patient.length && !$patient.data('select2')) {
        $patient.select2({
            placeholder: 'All',
            minimumInputLength: 1,
            allowClear: true,
            width: '100%',
            ajax: {
                url: '" . Yii::app()->createUrl('patient/autocomplete') . "',
                dataType: 'json',
                quietMillis: 100,
                data: function (term, page) {
                    return { q: term };
                },
                results: function (data, page) {
                    return { results: data };
                }
            },
            initSelection: function(element, callback) {
                var id = $(element).val();
                if (id !== '') {
                    var url = '" . Yii::app()->createUrl('patient/autocomplete') . "';
                    $.getJSON(url, { id: id }, function(data) {
                        if (data && data.length > 0) {
                            callback(data[0]);
                        }
                    });
                }
            }
        });
    }
}

$(document).ready(function(){
    initSearchPatientSelect2();
});
</script>

</div><!-- search-form -->
