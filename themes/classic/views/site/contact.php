<?php
/* @var $this SiteController */
/* @var $model ContactForm */
/* @var $form TbActiveForm */

$this->pageTitle = Yii::app()->name . ' - Contact Us';
$this->breadcrumbs = array(
    'Contact',
);
$this->menu = array(
    array('label' => 'Home', 'icon' => 'home', 'url' => array('#'), 'active' => true),
    array('label' => 'Library', 'icon' => 'book', 'url' => array('#')),
    array('label' => 'Application', 'icon' => 'pencil', 'url' => array('#')),
    array('label' => 'Profile', 'icon' => 'user', 'url' => array('#')),
    array('label' => 'Settings', 'icon' => 'cog', 'url' => array('#')),
    array('label' => 'Help', 'icon' => 'flag', 'url' => array('#')),
);
?>
<h2>Contact Us</h2>
<?php if (Yii::app()->user->hasFlash('contact')): ?>

    <?php
    $this->widget('bootstrap.widgets.TbAlert', array(
        'alerts' => array('contact'),
    ));
    ?>
<?php else: ?>
    <p>
        If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.
    </p>
    <div class="form">
        <?php
        $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
            'id' => 'contact-form',
            'type' => 'horizontal',
            'enableClientValidation' => true,
            'clientOptions' => array(
                'validateOnSubmit' => true,
            ),
                ));
        ?>
        <p class="note">Fields with <span class="required">*</span> are required.</p>
        <?php echo $form->errorSummary($model); ?>
        <?php echo $form->textFieldRow($model, 'name'); ?>
        <?php echo $form->textFieldRow($model, 'email'); ?>
        <?php echo $form->textFieldRow($model, 'subject'); ?>
        <?php echo $form->textAreaRow($model, 'body', array('rows' => 6, 'class' => 'span6')); ?>
        <?php if (CCaptcha::checkRequirements()): ?>
            <?php
            echo $form->captchaRow($model, 'verifyCode', array(
                'hint' => 'Please enter the letters as they are shown in the image above.<br/>Letters are not case-sensitive.',
            ));
            ?>
        <?php endif; ?>
        <div class="form-actions">
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => 'Submit',
                'type' => 'primary', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size' => '', // null, 'large', 'small' or 'mini'
                'buttonType' => 'submit', //link, button, submit, submitLink, reset, ajaxLink, ajaxButton and ajaxSubmit
            ));
            ?>
            <?php
            $this->widget('bootstrap.widgets.TbButton', array(
                'label' => 'Reset',
                'type' => 'info', // null, 'primary', 'info', 'success', 'warning', 'danger' or 'inverse'
                'size' => '', // null, 'large', 'small' or 'mini'
                'buttonType' => 'reset', //link, button, submit, submitLink, reset, ajaxLink, ajaxButton and ajaxSubmit
            ));
            ?>
        </div>
        <?php $this->endWidget(); ?>
    </div><!-- form -->
<?php endif; ?>