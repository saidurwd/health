<?php
$this->pageTitle = 'Controllers';
$this->breadcrumbs = array(
    'Configuration',
    'Controllers' => array('admin'),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('acl-controller-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-home fa-fw "></i> 
            Controllers 
            <span>> 
                Manage
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">

    </div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="">
    <!-- row -->
    <div class="row">
        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-4" data-widget-editbutton="false" data-widget-colorbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-home"></i> </span>
                    <h2>Controllers</h2>
                    <div class="widget-toolbar">
                        <!-- add: non-hidden - to disable auto hide -->
                        <div class="btn-group">
                            <button class="btn dropdown-toggle btn-xs btn-success" data-toggle="dropdown">
                                Actions <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right js-status-update">
                                <li>
                                    <?php echo CHtml::link('<i class="fa fa-plus"></i> New Controller', array('create')); ?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget edit box -->
                    <div class="jarviswidget-editbox">
                        <!-- This area used as dropdown edit box -->
                    </div>
                    <!-- end widget edit box -->
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <div class="table-responsive">
                            <div class="search-form" style="display:none">
                                <?php
                                $this->renderPartial('_search', array(
                                    'model' => $model,
                                ));
                                ?>
                            </div><!-- search-form -->
                            <?php
                            $this->widget('zii.widgets.grid.CGridView', array(
                                'itemsCssClass' => 'table table-striped table-hover',
                                'template' => '{items}{pager}',
                                'pager' => array(
                                    'htmlOptions' => array(
                                        'class' => 'pagination',
                                    ),
                                    'header' => '',
                                    'selectedPageCssClass' => 'active',
                                ),
                                'pagerCssClass' => 'dt-row dt-bottom-row',
                                'id' => 'acl-controller-grid',
                                'dataProvider' => $model->search(),
                                'filter' => $model,
                                'columns' => array(
                                    array(
                                        'name' => 'title',
                                        'type' => 'raw',
                                        'value' => 'CHtml::link(CHtml::encode($data->title), array("aclAction/actions","cid"=>$data->id))',
                                        'filter' => CHtml::activeTextField($model, 'title', array('class' => 'form-control')),
                                        'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Title'),
                                    ),
                                    array(
                                        'name' => 'controller',
                                        'type' => 'raw',
                                        'value' => 'CHtml::link(CHtml::encode($data->controller), array("aclAction/actions","cid"=>$data->id))',
                                        'filter' => CHtml::activeTextField($model, 'controller', array('class' => 'form-control')),
                                        'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Acceass'),
                                    ),
                                    array(
                                        'name' => 'status',
                                        'value' => '$data->status?Yii::t(\'app\',\'Active\'):Yii::t(\'app\', \'Inactive\')',
                                        'filter' => CHtml::activeDropDownList($model, 'status', array('0' => 'Inactive', '1' => 'Active'), array('empty' => 'All', 'class' => 'form-control')),
                                        'htmlOptions' => array('style' => "text-align:center;"),
                                    ),
                                    array(
                                        'header' => 'Actions',
                                        'type' => 'raw',
                                        'value' => 'AclController::get_actions($data->id)',
                                        'htmlOptions' => array('style' => "text-align:right;width:70px;", 'title' => 'Manage Actions!'),
                                    ),
                                    array(
                                        'header' => 'Actions',
                                        'class' => 'CButtonColumn',
                                        'htmlOptions' => array('style' => "text-align:center;width:100px;", 'class' => ''),
                                        'template' => '{view} {update} {delete}',
                                        'buttons' => array(
                                            'update' => array(
                                                'label' => '',
                                                'imageUrl' => '',
                                                'options' => array('class' => 'btn btn-xs btn-primary fa fa-pencil', 'rel' => 'tooltip', 'data-original-title' => 'Edit'),
                                            ),
                                            'view' => array(
                                                'label' => '',
                                                'imageUrl' => '',
                                                'options' => array('class' => 'btn btn-xs btn-info fa fa-search', 'rel' => 'tooltip', 'data-original-title' => 'View'),
                                            ),
                                            'delete' => array(
                                                'label' => '',
                                                'imageUrl' => '',
                                                'options' => array('class' => 'btn btn-xs btn-danger fa fa-trash-o', 'rel' => 'tooltip', 'data-original-title' => 'Delete'),
                                            ),
                                        ),
                                    ),
                                ),
                            ));
                            ?>
                        </div>
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
