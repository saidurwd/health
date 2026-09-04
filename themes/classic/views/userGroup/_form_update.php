<!-- EDIT Modal -->
<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="modalEditLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="modalEditLabel">EDIT GROUP</h4>
            </div>
            <?php
            $form = $this->beginWidget('CActiveForm', array(
                'id' => 'group-update-form',
                'enableAjaxValidation' => true,
                'enableClientValidation' => true,
                'method' => 'post',
                'action' => array("userGroup/update"),
                'htmlOptions' => array(
                    'class' => 'form-horizontal',
                    'onsubmit' => "return false;", /* Disable normal form submit */
                    'onkeypress' => " if(event.keyCode == 13){ update(); } " /* Do ajax call when user presses enter key */
                ),
            ));
            ?>
            <?php echo $form->hiddenField($model, 'id', array()); ?>
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
                <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Update', array('class' => 'btn btn-primary', 'onclick' => 'update();')); ?>
                <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
            </div>
            <?php $this->endWidget(); ?>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->