<?php
/* @var $this UserController */
/* @var $model User */
$this->pageTitle = $model->full_name . ' - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Users' => array('admin'),
    $model->full_name,
);
?>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-list-alt fa-fw "></i> 
            User
            <span>>
                <?php echo $model->full_name; ?>
            </span>
        </h1>
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
                    <span class="widget-icon"> <i class="fa fa-tasks"></i> </span>
                    <h2><?php echo $model->full_name; ?></h2>
                    <div class="widget-toolbar">
                        <?php echo CHtml::link('<i class="fa fa-plus"></i>', array('create'), array('data-toggle' => 'modal', 'data-target' => '#newData', 'class' => 'btn btn-sm btn-primary', 'data-placement' => 'bottom', 'rel' => 'tooltip', 'data-original-title' => 'New')); ?>
                    </div>                    
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        $this->widget('zii.widgets.CDetailView', array(
                            'htmlOptions' => array('class' => 'table table-striped table-hover'),
                            'data' => $model,
                            'attributes' => array(
                                'id',
                                'full_name',
                                'username',
                                'email',
                                array(
                                    'name' => 'register_date',
                                    'type' => 'raw',
                                    'value' => User::get_date_time($model->register_date),
                                ),
                                array(
                                    'name' => 'lastvisit',
                                    'type' => 'raw',
                                    'value' => User::get_date_time($model->lastvisit),
                                ),
                                array(
                                    'name' => 'group_id',
                                    'type' => 'raw',
                                    'value' => UserGroup::get_group($model->group_id),
                                ),
                                array(
                                    'name' => 'department',
                                    'type' => 'raw',
                                    'value' => Department::getData($model->department,'title'),
                                ),
                                array(
                                    'name' => 'status',
                                    'type' => 'raw',
                                    'value' => UserStatus::get_status($model->status),
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