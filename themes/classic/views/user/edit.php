<?php
/* @var $this UserController */
/* @var $model User */

Yii::app()->clientScript->registerScript('search', "
    pageSetUp();
", CClientScript::POS_END);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
        &times;
    </button>
    <h4 class="modal-title" id="newDataLabel">CHANGE PASSWORD</h4>
</div>
<div class="modal-body">
    <?php $this->renderPartial('_edit', array('model' => $model)); ?>
</div>