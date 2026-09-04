<?php
$form = $this->beginWidget('CActiveForm', array(
    'action' => Yii::app()->createUrl($this->route),
    'method' => 'get',
        ));
?>
<?php echo $form->textField($model, 'controller', array('class' => 'span5', 'maxlength' => 150)); ?>
<div class="form-actions">
    <?php echo CHtml::submitButton('Search'); ?>
</div>

<?php $this->endWidget(); ?>
