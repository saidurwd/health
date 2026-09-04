<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<?php
$form = $this->beginWidget('CActiveForm', array(
    'id' => 'user-form',
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnSubmit' => true,
    ),
   'htmlOptions' => array('class' => 'form-horizontal', 'enctype' => 'multipart/form-data'),
        ));
?>
<fieldset>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'full_name'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'full_name', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Name')); ?>
            <?php echo $form->error($model, 'full_name', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'username'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'username', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Username')); ?>
            <?php echo $form->error($model, 'username', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'email'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->textField($model, 'email', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Email')); ?>
            <?php echo $form->error($model, 'email', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <?php if ($model->isNewRecord) { ?>
        <div class="form-group">
            <label class="col-md-4 control-label">
                <?php echo $form->labelEx($model, 'password'); ?>
            </label>
            <div class="col-md-8">
                <?php echo $form->passwordField($model, 'password', array('maxlength' => 150, 'class' => 'form-control', 'placeholder' => 'Password')); ?>
                <?php echo $form->error($model, 'password', array('class' => 'text-danger')); ?>
            </div>
        </div>
    <?php } ?>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'group_id'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->dropDownList($model, 'group_id', CHtml::listData(UserGroup::model()->findAll(array('condition' => '')), 'id', 'title'), array('empty' => '-select-', 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'group_id', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'department'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->dropDownList($model, 'department', CHtml::listData(Department::model()->findAll(array('condition' => '')), 'id', 'title'), array('empty' => '-select-', 'class' => 'form-control')); ?>
            <?php echo $form->error($model, 'department', array('class' => 'text-danger')); ?>
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'status'); ?>
        </label>
        <div class="col-md-8">
            <?php echo $form->dropDownList($model, 'status', array('0' => 'Inactive', '1' => 'Active'), array('class' => 'form-control')); ?>
            <?php echo $form->error($model, 'status', array('class' => 'text-danger')); ?>
        </div>
    </div>
 
    
    
        <div class="form-group">
        <label class="col-md-4 control-label">
            <?php echo $form->labelEx($model, 'photo'); ?>
        </label>
        <div class="col-md-8">
            <div class="button"><?php echo $form->fileField($model, 'photo'); ?>
            <?php  
			
			
if($model->id!="")
{			                     
    $sphoto = Yii::app()->db->createCommand()
            ->select('photo')
            ->from('{{user}}')
            ->where("id=".$model->id)
            ->queryScalar();


if($sphoto=="")
$sphoto="Jellyfish.jpg";

?>


                        <br><br><img class="online" alt="<?php echo User::get_full_name($model->id); ?>" src="<?php echo Yii::app()->baseUrl; ?>/uploads/user/thumb/<?php print $sphoto; ?>" width="50">
 <?php } ?>                       
                        
                        
        </div>
    </div>

    
</fieldset>
<div class="modal-footer">   
    <?php echo CHtml::submitButton($model->isNewRecord ? 'Submit' : 'Save', array('class' => 'btn btn-primary', 'id' => $model->id)); ?>
    <button data-dismiss="modal" class="btn btn-default" onclick="location.reload();" type="button">Cancel</button>
</div>
<?php $this->endWidget(); ?>