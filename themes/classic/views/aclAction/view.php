<?php
$this->pageTitle = 'ACL action details';
$this->breadcrumbs = array(
    'Configuration',
    'ACL Actions' => array('actions', 'cid' => $_GET['cid']),
    AclController::get_controller($_GET['cid']) . ' - Details',
);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user-md fa-fw "></i> 
            ACL Actions 
            <span>> 
                Details ACL Action
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-5 col-md-5 col-lg-8">

    </div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="">
    <!-- START ROW -->
    <div class="row">
        <!-- NEW COL START -->
        <article class="col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-4" data-widget-editbutton="false" data-widget-custombutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-edit"></i> </span>
                    <h2>Action Details (<?php echo $model->action; ?>)</h2>	
                    <div class="widget-toolbar">
                        <!-- add: non-hidden - to disable auto hide -->
                        <div class="btn-group">
                            <button class="btn dropdown-toggle btn-xs btn-success" data-toggle="dropdown">
                                Actions <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu pull-right js-status-update">
                                <li>
                                    <?php echo CHtml::link('<i class="fa fa-home"></i> Manage Actions', array('actions', 'cid' => $_GET['cid'])); ?>
                                </li>
                                <li>
                                    <?php echo CHtml::link('<i class="fa fa-plus"></i> New Action', array('create', 'cid' => $_GET['cid'])); ?>
                                </li>
                                <li>
                                    <?php echo CHtml::link('<i class="fa fa-pencil"></i> Edit Action', array('update', 'id' => $model->id, 'cid' => $_GET['cid'])); ?>
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
                        <?php
                        $this->widget('zii.widgets.CDetailView', array(
                            'htmlOptions' => array('class' => 'table table-striped table-hover'),
                            'data' => $model,
                            'attributes' => array(
                                'id',
                                array(
                                    'name' => 'controller_id',
                                    'type' => 'raw',
                                    'value' => $model->getControllerName($model->controller_id),
                                ),
                                array(
                                    'name' => 'title',
                                    'type' => 'raw',
                                    'value' => $model->title,
                                ),
                                'action',
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
        <!-- END COL -->		
    </div>
    <!-- END ROW -->
</section>
<!-- end widget grid -->