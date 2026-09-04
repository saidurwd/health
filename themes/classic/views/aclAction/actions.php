<?php
$this->pageTitle = 'ACL Actions';
$this->breadcrumbs = array(
    'Configuration',
    'ACL Actions' => array('actions', 'cid' => $_GET['cid']),
    getControllerName($_GET['cid']),
);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-home fa-fw "></i> 
            ACL Actions 
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
            <div class="jarviswidget jarviswidget-color-darken" id="wid-id-1" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-table"></i> </span>
                    <h2>Controller Actions (<?php echo AclController::get_controller($_GET['cid']); ?>)</h2>
                    <div class="widget-toolbar">
                        <!-- add: non-hidden - to disable auto hide -->
                        <div class="btn-group">
                            <button class="btn dropdown-toggle btn-xs btn-success" data-toggle="dropdown">
                                Actions <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right js-status-update">
                                <li>
                                    <?php echo CHtml::link('<i class="fa fa-plus"></i> New Action', array('create', 'cid' => $_GET['cid'])); ?>
                                </li>
                                <li>
                                    <?php echo CHtml::link('<i class="fa fa-home"></i> Manage Controllers', array('aclController/admin')); ?>
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
                            <?php
                            $this->widget('zii.widgets.grid.CGridView', array(
                                'itemsCssClass' => 'table table-striped table-hover',
                                'template' => '{items}{summary}{pager}',
                                'id' => 'acl-action-grid',
                                'dataProvider' => $model->actions($_GET['cid']),
                                'filter' => $model,
                                'pager' => array(
                                    'htmlOptions' => array(
                                        'class' => 'pagination',
                                    ),
                                    'header' => '',
                                    'selectedPageCssClass' => 'active',
                                ),
                                'pagerCssClass' => 'dt-row dt-bottom-row',
                                'columns' => array(
                                    array(
                                        'name' => 'title',
                                        'type' => 'raw',
                                        'value' => '$data->title',
                                        'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Title'),
                                    ),
                                    array(
                                        'name' => 'controller_id',
                                        'type' => 'raw',
                                        'value' => 'getControllerName($data->controller_id)',
                                        'htmlOptions' => array('style' => "text-align:left;", 'title' => 'Controller'),
                                    ),
                                    'action',
                                    array(
                                        'class' => 'CButtonColumn',
                                        'template' => '{view}{update}{delete}',
                                        'htmlOptions' => array('style' => "text-align:center;width:10%;", 'title' => 'Actions', 'class' => ''),
                                        'buttons' => array
                                            (
                                            'view' => array
                                                (
                                                'label' => 'View',
                                                'url' => 'yii::app()->createUrl("aclAction/view", array("id"=>"$data->id","cid"=>"$_GET[cid]"))',
                                                'options' => array('class' => 'view')
                                            ),
                                            'update' => array
                                                (
                                                'label' => 'Update',
                                                'url' => 'yii::app()->createUrl("aclAction/update", array("id"=>"$data->id","cid"=>"$_GET[cid]"))',
                                                'options' => array('class' => 'edit'),
                                            ),
                                            'delete' => array
                                                (
                                                'label' => 'Delete',
                                                'url' => 'yii::app()->createUrl("aclAction/delete", array("id"=>"$data->id","cid"=>"$_GET[cid]"))',
                                                'options' => array('class' => 'delete'),
                                            ),
                                        ),
                                    ),
                                ),
                            ));

                            /**
                             * Retrieves Controller name by ID.
                             * @return string.
                             */
                            function getControllerName($id) {
                                $returnValue = Yii::app()->db->createCommand()
                                        ->select('controller')
                                        ->from('{{acl_controller}}')
                                        ->where('id=:id', array(':id' => $id))
                                        ->queryScalar();

                                return $returnValue;
                            }
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