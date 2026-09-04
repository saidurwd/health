<?php
/* @var $this UserGroupController */
/* @var $model UserGroup */
/* @var $form CActiveForm */
?>
<!-- CREATE Modal -->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="modalCreateLabel">NEW GROUP</h4>
            </div>
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'user-group-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => true,
                'method' => 'post',
                'action' => array("userGroup/create"),
                'htmlOptions' => array(
                    'class' => 'form-horizontal',
                    'onsubmit' => "return false;", /* Disable normal form submit */
                    'onkeypress' => " if(event.keyCode == 13){ create(); } " /* Do ajax call when user presses enter key */
                ),
                'clientOptions' => array(
                    'validateOnType' => true,
                    'validateOnSubmit' => true,
                    'afterValidate' => 'js:function(form, data, hasError) {if (!hasError){ create(); }}'
                ),
            ));
            ?>
            <div class="modal-body">                
                <fieldset>
                    <div class="form-group">
                        <label class="col-md-4 control-label">
                            <?php echo $form->labelEx($model, 'title'); ?>
                        </label>
                        <div class="col-md-8">
                            <?php echo $form->textField($model, 'title', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Group')); ?>
                            <?php echo $form->error($model, 'title', array('class' => 'text-danger')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-4 control-label">
                            <?php echo $form->labelEx($model, 'details'); ?>
                        </label>
                        <div class="col-md-8">
                            <?php echo $form->textArea($model, 'details', array('rows' => 2, 'cols' => 50, 'class' => 'form-control', 'placeholder' => 'Details')); ?>
                            <?php echo $form->error($model, 'details', array('class' => 'text-danger')); ?>
                        </div>
                    </div>
                </fieldset>                                
            </div>
            <div class="modal-footer">   
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' => 'btn btn-primary')); ?>
                <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
            </div>
            <?php $this->endWidget(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
    function create()
    {
        var data = $("#user-group-form").serialize();
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("userGroup/create"); ?>',
            data: data,
            success: function (data) {
                //alert("succes:"+data); 
                if (data != "false")
                {
                    $('#modalCreate').modal('hide');
                    $.fn.yiiGridView.update('user-group-grid', {
                    });
                }
            },
            error: function (data) { // if error occured
                alert("Error occured. Please try again");
                //alert(data);
            },
            dataType: 'html'
        });
    }

    function renderCreateForm()
    {
        $('#user-group-form').each(function () {
            this.reset();
        });
        //$('#modalCreate').modal('hide');
        $('#modalCreate').modal({
            show: true,
        });
    }
</script>