<?php
/* @var $this UserGroupController */
/* @var $model UserGroup */
$this->pageTitle = 'User Groups - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'User Groups' => array('admin'),
    'Manage',
);

Yii::app()->clientScript->registerScript('reload-pageSetUp', "
    function reloadPageSetUp() {
        pageSetUp();
    }
    ", CClientScript::POS_END);
?>

<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-home fa-fw "></i> 
            User Groups
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
                    <h2>User Groups</h2>       
                    <div class="widget-toolbar">
                        <?php echo CHtml::link('<i class="fa fa-plus"></i>', 'javascript:void(0);', array('onclick' => 'renderCreateForm();', 'class' => 'btn btn-sm btn-primary', 'data-placement' => 'bottom', 'rel' => 'tooltip', 'data-original-title' => 'New')); ?>
                    </div>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'user-group-grid',
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
                                    'name' => 'title',
                                    'type' => 'raw',
                                    'value' => '$data->title',
                                    'filter' => CHtml::activeTextField($model, 'title', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Title'),
                                ),
                                array(
                                    'name' => 'details',
                                    'type' => 'raw',
                                    'value' => '$data->details',
                                    'filter' => CHtml::activeTextField($model, 'details', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Details'),
                                ),
                                array(
                                    'type' => 'raw',
                                    'value' => '"<a href=\'javascript:void(0);\' onclick=\'renderUpdateForm(".$data->id.")\' class=\'btn btn-xs btn-info fa fa-pencil\'><i class=\'icon-pencil\'></i></a>"',
                                    'htmlOptions' => array('style' => 'width:25px;')
                                ),
                                array(
                                    'header' => '',
                                    'class' => 'CButtonColumn',
                                    'htmlOptions' => array('style' => "text-align:center;width:50px;", 'class' => ''),
                                    'template' => '{access} {delete}',
                                    'afterDelete' => 'function(link,success,data){ if(success) $("#content").html(data); }',
                                    'buttons' => array(
                                        'access' => array(
                                            'label' => '',
                                            'imageUrl' => '',
                                            'url' => 'Yii::app()->createUrl("userGroup/access", array("id"=>$data->id))',
                                            'options' => array('class' => 'btn btn-xs btn-primary fa fa-lock', 'rel' => 'tooltip', 'data-original-title' => Yii::t('UserGroup', 'Set_user_access')),
                                        ),
//                                        'update' => array(
//                                            'label' => '',
//                                            'imageUrl' => '',
//                                            'url' => '',
//                                            //'url' => 'Yii::app()->createUrl("/comment/update", array("id"=>$data["id"]))',
//                                            'options' => array('class' => 'btn btn-xs btn-info fa fa-pencil', 'onclick' => 'renderUpdateForm(".$data[id].")'),
//                                        ),
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
<?php $this->renderPartial('_form', array('model' => $model)); ?>            
<?php $this->renderPartial('ajax'); ?>            