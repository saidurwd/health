<?php
/* @var $this StockRequisitionController */
/* @var $model StockRequisition */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
    'htmlOptions' => array('class' => 'smart-form'),
        ));
?>
<fieldset>
    <div class="row">
        <section class="col col-2">
            <label class="input">
                <?php echo $form->labelEx($model, 'requisition_number'); ?>
                <?php echo $form->textField($model, 'requisition_number', array('size' => 60, 'maxlength' => 250, 'class' => 'col-sm-12', 'placeholder' => 'Requisition #')); ?>
                <?php echo $form->error($model, 'requisition_number'); ?>
            </label>
        </section>
        <section class="col col-2">
            <label class="select">
                <?php echo $form->labelEx($model, 'status'); ?>
                <?php echo $form->dropDownList($model, 'status', CHtml::listData(TransectionStatus::model()->findAll(array('condition' => 'user_view=1 AND transection_type=3', "order" => "id")), 'status_id', 'status_title'), array('empty' => 'All Status', 'class' => 'select2')); ?>
                <?php echo $form->error($model, 'status'); ?>
            </label>
        </section>
    </div>
</fieldset>
<footer>
    <?php echo CHtml::resetButton(Yii::t('Common', 'reset'), array('class' => 'btn btn-warning')); ?>
    <?php echo CHtml::submitButton(Yii::t('Common', 'search'), array('class' => 'btn btn-primary')); ?>           
</footer>
<?php $this->endWidget(); ?>