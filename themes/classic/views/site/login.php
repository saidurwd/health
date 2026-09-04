<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle = 'Login - ' . Yii::app()->name;
?>
<div id="content" class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-8 hidden-xs hidden-sm">
            <?php User::get_alert_message(); ?>
            <h1 class="txt-color-red login-header-big"><?php echo Yii::app()->params['adminName']; ?></h1>
            <div class="hero">
                <div class="pull-left login-desc-box-l">
                    <h4 class="paragraph-header"><?php echo Yii::app()->params['tagLine']; ?></h4>
                </div>
                <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/demo/forms_icon.png" class="pull-right display-image" alt="" style="width:340px">
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-4">
            <div class="well no-padding">
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'login-form',
                    'enableClientValidation' => true,
                    'htmlOptions' => array('id' => 'login-form', 'class' => 'smart-form client-form'),
                    'clientOptions' => array(
                        'validateOnSubmit' => true,
                    ),
                ));
                ?>
                <header>APPLICATION SIGN IN</header>
                <fieldset>
                    <div class="row">
                        <section class="col col-12">
                            <?php echo $form->errorSummary($model, '<i class="fa fa-bell text-danger"></i> Please fix the following input errors:', '', array('class' => 'text-danger', 'style' => 'padding-left:20px;')); ?>
                        </section>
                    </div>
                    <section>
                        <label class="label">E-mail</label>
                        <label class="input"> <i class="icon-append fa fa-user"></i>
                            <?php echo $form->textField($model, 'username'); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-user txt-color-teal"></i> Please enter email address/username</b></label>
                    </section>
                    <section>
                        <label class="label">Password</label>
                        <label class="input"> <i class="icon-append fa fa-lock"></i>
                            <?php echo $form->passwordField($model, 'password'); ?>
                            <b class="tooltip tooltip-top-right"><i class="fa fa-lock txt-color-teal"></i> Enter your password</b> </label>
                        <div class="note">
                            <?php echo CHtml::link('Forgot password?', array('/recovery/recovery')); ?>
                        </div>
                    </section>
                    <section>        
                        <label class="checkbox">
                            <?php echo $form->checkBox($model, 'rememberMe', array('checked' => 'checked')); ?>
                            <i></i><?php echo $form->label($model, 'rememberMe', array()); ?></label>
                    </section>
                </fieldset>
                <footer>
                    <?php echo CHtml::submitButton('Sign in', array('class' => 'btn btn-primary')); ?>
                </footer>
                <?php $this->endWidget(); ?>
            </div>
        </div>               
    </div>
</div>