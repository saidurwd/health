<?php
/* @var $this PatientController */
/* @var $model Patient */
/* @var $form CActiveForm */
?>
<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'prescription-medicine-grid',
    'dataProvider' => $modelGrid->search($model->id),
    'htmlOptions' => array('class' => ''),
    'itemsCssClass' => 'table table-bordered table-striped table-hover',
    'template' => '{items}{pager}',
    'pager' => array(
        'htmlOptions' => array(
            'class' => 'pagination',
        ),
        'header' => '',
        'selectedPageCssClass' => 'active',
    ),
    'pagerCssClass' => 'widget-footer',
    'columns' => array(
        array(
            'name' => 'servicetype',
            'value' => '$data->servicetype',
            'htmlOptions' => array('style' => "text-align:left;"),
        ),
        array(
            'name' => 'product',
            'value' => '$data->product',
            'htmlOptions' => array('style' => "text-align:left;"),
        ),
        array(
            'name' => 'instruction',
            'value' => '$data->instruction',
            'htmlOptions' => array('style' => "text-align:left;"),
        ),
        array(
            'name' => 'no_of_days',
            'value' => '$data->no_of_days',
            'htmlOptions' => array('style' => "text-align:center;"),
        ),
        array(
            'header' => 'Actions',
            'class' => 'CButtonColumn',
            'htmlOptions' => array('style' => "text-align:center;width:50px;", 'class' => ''),
            'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
            'template' => '{delete}',
            'buttons' => array(
                'delete' => array(
                    'label' => '',
                    'imageUrl' => '',
                    'url' => 'yii::app()->createUrl("patient/removemedicine", array("id"=>$data["id"]))',
                    'options' => array('class' => 'btn btn-xs btn-danger fa fa-trash-o', 'rel' => 'tooltip', 'data-original-title' => 'Delete'),
                ),
            ),
        ),
    ),
));
?>
<?php
$formData = $this->beginWidget('CActiveForm', array(
    'id' => 'prescription-medicine-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => array(
        'class' => 'smart-form',
        'enctype' => 'multipart/form-data',
        'onsubmit' => "return false;", /* Disable normal form submit */
    //'onkeypress' => " if(event.keyCode == 13){ newRequisition(); } " /* Do ajax call when user presses enter key */
    ),
        ));
?>
<?php
if ($model->isNewRecord) {
    echo $formData->hiddenField($modelData, 'parent', array('value' => 0));
} else {
    echo $formData->hiddenField($modelData, 'parent', array('value' => $model->id));
}
?>
<fieldset>
    <div class="row">   
        <section class="col col-2">
            <label class="select">
                <?php echo StockRequisition::getItemList('PrescriptionMedicine', 'product'); ?>
                <?php echo $formData->error($modelData, 'product'); ?>
            </label>
        </section>
        <section class="col col-2">
            <label class="select">
                <?php //echo $formData->dropDownList($modelData, 'instruction', array('1+1+1 AFTER MEAL' => '1+1+1 AFTER MEAL', '1+1+0 AFTER MEAL' => '1+1+0 AFTER MEAL', '1+0+1 AFTER MEAL' => '1+0+1 AFTER MEAL', '1+0+0 AFTER MEAL' => '1+0+0 AFTER MEAL', '0+1+1 AFTER MEAL' => '0+1+1 AFTER MEAL', '0+0+1 AFTER MEAL' => '0+0+1 AFTER MEAL', '1+1+1 BRFORE MEAL' => '1+1+1 BRFORE MEAL', '1+1+0 BRFORE MEAL' => '1+1+0 BRFORE MEAL', '1+0+1 BRFORE MEAL' => '1+0+1 BRFORE MEAL', '1+0+0 BRFORE MEAL' => '1+0+0 BRFORE MEAL', '0+1+1 BRFORE MEAL' => '0+1+1 BRFORE MEAL', '0+0+1 BRFORE MEAL' => '0+0+1 BRFORE MEAL'), array('empty' => 'Select a Instruction', 'class' => 'select2')); ?>
                <?php echo $formData->dropDownList($modelData, 'instruction', CHtml::listData(Instruction::model()->findAll(array('condition' => 'status="Active"')), 'title', 'title'), array('empty' => 'Select an Instruction', 'class' => 'select2')); ?>
                <?php echo $formData->error($modelData, 'instruction'); ?>
            </label>
        </section> 
        <section class="col col-1">
            <label class="input">
                <?php echo $formData->textField($modelData, 'no_of_days', array('maxlength' => 20, 'class' => 'col-sm-12', 'placeholder' => 'No of Days')); ?>
                <?php echo $formData->error($modelData, 'no_of_days'); ?>
            </label>
        </section>
        <section class="col col-1">
            <?php echo CHtml::htmlButton('<i class="fa fa-plus"></i> Add', array('type' => 'submit', 'class' => 'btn btn-primary btn-sm', 'onclick' => 'newIssue();')); ?> 
        </section>
    </div>
</fieldset>
<?php $this->endWidget(); ?>
<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'prescription-form',
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
        <section class="col col-4">
            <label class="label"><?php echo $form->labelEx($model, 'diagnosis'); ?></label>
            <label class="input">
                <?php echo $form->dropDownList($model, 'diagnosis', CHtml::listData(Disease::model()->findAll(array('condition' => '')), 'id', 'title'), array('empty' => 'Select a Disease', 'class' => 'select2')); ?>
                <?php echo $form->error($model, 'diagnosis'); ?>
            </label>
        </section>
        <section class="col col-4">
            <label class="label"><?php echo $form->labelEx($model, 'cc'); ?></label>
            <label class="input">
                <?php echo $form->textField($model, 'cc', array('maxlength' => 250, 'class' => 'col-sm-12', 'placeholder' => 'C/C')); ?>
                <?php echo $form->error($model, 'cc'); ?>
            </label>
        </section>   
        <section class="col col-4">
            <label class="label"><?php echo $form->labelEx($model, 'temp'); ?></label>
            <label class="input">
                <?php echo $form->textField($model, 'temp', array('maxlength' => 250, 'class' => 'col-sm-12', 'placeholder' => 'Temp')); ?>
                <?php echo $form->error($model, 'temp'); ?>
            </label>
        </section>
    </div>
    <div class="row">
        <section class="col col-4">
            <label class="label"><?php echo $form->labelEx($model, 'oe'); ?></label>
            <label class="input">
                <?php echo $form->textField($model, 'oe', array('maxlength' => 250, 'class' => 'col-sm-12', 'placeholder' => 'O/E')); ?>
                <?php echo $form->error($model, 'oe'); ?>
            </label>
        </section>
        <section class="col col-4">
            <label class="label"><?php echo $form->labelEx($model, 'bp'); ?></label>
            <label class="input">
                <?php echo $form->textField($model, 'bp', array('maxlength' => 250, 'class' => 'col-sm-12', 'placeholder' => 'B/P')); ?>
                <?php echo $form->error($model, 'bp'); ?>
            </label>
        </section>
        <section class="col col-4">
            <label class="label"><?php echo $form->labelEx($model, 'pulse'); ?></label>
            <label class="input">
                <?php echo $form->textField($model, 'pulse', array('maxlength' => 250, 'class' => 'col-sm-12', 'placeholder' => 'Pulse')); ?>
                <?php echo $form->error($model, 'pulse'); ?>
            </label>
        </section>
    </div>
    <div class="row">        
        <section class="col col-6">
            <label class="label"><?php echo $form->labelEx($model, 'advice'); ?></label>
            <label class="input">
                <?php echo $form->textField($model, 'advice', array('maxlength' => 250, 'class' => 'col-sm-12', 'placeholder' => 'Advice')); ?>
                <?php echo $form->error($model, 'advice'); ?>
            </label>
        </section>
        <section class="col col-6">
            <label class="label"><?php echo $form->labelEx($model, 'rx'); ?></label>
            <label class="input">
                <?php echo $form->textArea($model, 'rx', array('rows' => 2, 'class' => 'form-control')); ?>
                <?php echo $form->error($model, 'rx'); ?>
            </label>
        </section>
    </div>
</fieldset>   
<footer>
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save', array('class' => 'btn btn-primary')); ?>
</footer>
<?php $this->endWidget(); ?>
<script type="text/javascript">
    function newIssue() {
        var formData = new FormData($('#prescription-medicine-form')[0]);
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("patient/addmedicine"); ?>',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                //alert("succes:" + data);
                if (data != "false")
                {
                    $('#prescription-medicine-grid').yiiGridView('update');
                    $('#prescription-medicine-form').each(function () {
                        this.reset();
                    });
                    $('#PrescriptionMedicine_product,#PrescriptionMedicine_instruction,#PrescriptionMedicine_no_of_days').select2('val', '').trigger('change');
                }
            },
            error: function (data) { // if error occured
                alert("Error occured. Please try again");
                $('#prescription-medicine-form').each(function () {
                    this.reset();
                });
                //alert(data);
            },
            dataType: 'html'
        });
    }
</script>