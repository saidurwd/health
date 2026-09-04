<?php
/* @var $this UserController */
/* @var $model User */

$this->pageTitle = 'Users - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Users' => array('admin'),
    'Manage',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

Yii::app()->clientScript->registerScript('reload-pageSetUp', "
    function reloadPageSetUp() {
        pageSetUp();
    }
    ", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('re-install-date-picker', "
    function reinstallDatePicker(id, data) {
        $('#register_date').datepicker();
        $('#lastvisit').datepicker();
        pageSetUp();
    }
    ", CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-home fa-fw "></i> 
            Users
            <span>>
                Manage
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">
        <ul id="sparks" class="">
            <li class="sparks-info">
                <h5> </h5>
            </li>
        </ul>
    </div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="">
    <!-- row -->
    <div class="row">
        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-home"></i> </span>
                    <h2>Users</h2>                           
                    <div class="widget-toolbar">
                        <?php echo CHtml::link('<i class="fa fa-search"></i>', '#', array('class' => 'search-button btn btn-sm btn-primary', 'data-placement' => 'bottom', 'rel' => 'tooltip', 'data-original-title' => 'Search')); ?>                        
                    </div>
                    <div class="widget-toolbar">
                        <?php echo CHtml::link('<i class="fa fa-plus"></i>', array('create'), array('data-toggle' => 'modal', 'data-target' => '#newData', 'class' => 'btn btn-sm btn-primary', 'data-placement' => 'bottom', 'rel' => 'tooltip', 'data-original-title' => 'New')); ?>
                    </div>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <div class="search-form" style="display:none">
                            <?php
                            $this->renderPartial('_search', array(
                                'model' => $model,
                            ));
                            ?>
                        </div><!-- search-form -->
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'user-grid',
                            'dataProvider' => $model->search(),
                            'filter' => $model,
                            'afterAjaxUpdate' => 'reloadPageSetUp',
                            'htmlOptions' => array('class' => ''),
                            'itemsCssClass' => 'table table-bordered table-striped table-hover smart-form',
                            'template' => '{items}{pager}',
                            'emptyText' => 'No result found.',
                            'summaryText' => "{start} - {end} of {count} result",
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
                                    'name' => 'full_name',
                                    'type' => 'raw',
                                    'value' => '$data->full_name',
                                    'filter' => CHtml::activeTextField($model, 'full_name', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Name'),
                                ),
                                array(
                                    'name' => 'username',
                                    'type' => 'raw',
                                    'value' => '$data->username',
                                    'filter' => CHtml::activeTextField($model, 'username', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Username'),
                                ),
                                array(
                                    'name' => 'email',
                                    'type' => 'raw',
                                    'value' => '$data->email',
                                    'filter' => CHtml::activeTextField($model, 'email', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Email'),
                                ),
                                array(
                                    'name' => 'register_date',
                                    'type' => 'raw',
                                    'value' => 'User::get_date($data->register_date)',
                                    'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array('model' => $model, 'attribute' => 'register_date', 'htmlOptions' => array('id' => 'register_date', 'size' => '10', 'class' => 'form-control'), 'i18nScriptFile' => 'jquery.ui.datepicker-en.js', 'defaultOptions' => array('showOn' => 'focus', 'dateFormat' => 'yy-mm-dd', 'showOtherMonths' => true, 'selectOtherMonths' => true, 'changeMonth' => true, 'changeYear' => true, 'showButtonPanel' => false,)), true),
                                    'htmlOptions' => array('style' => "text-align:left;width:100px;", 'title' => 'Register date'),
                                ),
                                array(
                                    'name' => 'lastvisit',
                                    'type' => 'raw',
                                    'value' => 'User::get_date($data->lastvisit)',
                                    'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array('model' => $model, 'attribute' => 'lastvisit', 'htmlOptions' => array('id' => 'lastvisit', 'size' => '10', 'class' => 'form-control'), 'i18nScriptFile' => 'jquery.ui.datepicker-en.js', 'defaultOptions' => array('showOn' => 'focus', 'dateFormat' => 'yy-mm-dd', 'showOtherMonths' => true, 'selectOtherMonths' => true, 'changeMonth' => true, 'changeYear' => true, 'showButtonPanel' => false,)), true),
                                    'htmlOptions' => array('style' => "text-align:left;width:100px;", 'title' => 'last visit'),
                                ),
                                array(
                                    'name' => 'group_id',
                                    'type' => 'raw',
                                    'value' => 'UserGroup::get_group($data->group_id)',
                                    'filter' => CHtml::activeDropDownList($model, 'group_id', CHtml::listData(UserGroup::model()->findAll(array('condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'All', 'class' => 'select2')),
                                    'htmlOptions' => array('style' => "text-align:left;width:100px;", 'title' => 'Group'),
                                ),
                                array(
                                    'name' => 'status',
                                    'value' => '$data->status?Yii::t(\'app\',\'Active\'):Yii::t(\'app\', \'Inactive\')',
                                    'filter' => CHtml::activeDropDownList($model, 'status', array('0' => 'Inactive', '1' => 'Active'), array('empty' => 'All', 'class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:center;"),
                                ),
                                array(
                                    'header' => '',
                                    'class' => 'CButtonColumn',
                                    'htmlOptions' => array('style' => "text-align:center;width:80px;", 'class' => ''),
                                    'template' => '{update} {edit} {delete}',
                                    'buttons' => array(
                                        'edit' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'url' => 'Yii::app()->createUrl("/user/edit", array("id"=>$data["id"]))',
                                            'options' => array('class' => 'btn btn-xs btn-warning fa fa-key', 'data-toggle' => 'modal', 'data-target' => '#newData'),
                                        ),
                                        'update' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'options' => array('class' => 'btn btn-xs btn-info fa fa-pencil', 'data-toggle' => 'modal', 'data-target' => '#newData'),
                                        ),
                                        'view' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'options' => array('class' => 'btn btn-xs btn-info fa fa-search', 'data-toggle' => 'modal', 'data-target' => '#newData'),
                                        ),
                                        'delete' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'options' => array('class' => 'btn btn-xs btn-danger fa fa-times'),
                                        ),
                                    ),
                                ),
                            ),
                        ));
                        ?>                        
                    </div>
                    <!-- end widget content -->
                </div>
                <!-- end widget div -->
            </div>
            <!-- end widget -->
        </article>
        <!-- WIDGET END -->
    </div>
    <!-- end row -->
</section>
<!-- end widget grid -->


