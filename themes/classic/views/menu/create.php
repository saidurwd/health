<?php
/* @var $this MenuController */
/* @var $model Menu */
$this->pageTitle = 'New menu - ' . Yii::app()->name;
$this->breadcrumbs=array(
	'Menus'=>array('admin'),
	'New',
);
Yii::app()->clientScript->registerScript('search', "
    pageSetUp();
", CClientScript::POS_END);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="newDataLabel">NEW MENU</h4>
</div>
<div class="modal-body">
    <?php $this->renderPartial('_form', array('model' => $model)); ?>
</div>