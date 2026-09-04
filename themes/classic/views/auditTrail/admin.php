<?php
/* @var $this AuditTrailController */
/* @var $model AuditTrail */
$this->pageTitle = 'Audit Trail - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Audit Trails' => array('admin'),
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
            Audit Trails
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
                    <h2>Audit Trails</h2>       
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'audit-trail-grid',
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
                                    'name' => 'user_id',
                                    'type' => 'raw',
                                    'value' => 'CHtml::link(CHtml::encode(User::get_full_name($data->user_id)), array("/user/view","id"=>$data->user_id))',
                                    'filter' => CHtml::activeDropDownList($model, 'user_id', CHtml::listData(User::model()->findAll(array('condition' => '', 'order' => 'full_name')), 'id', 'full_name'), array('empty' => 'All', 'class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;width:250px;", 'title' => 'Name'),
                                ),
                                array(
                                    'name' => 'login_time',
                                    'type' => 'raw',
                                    'value' => 'User::get_date_time($data->login_time)',
                                    'filter' => CHtml::activeTextField($model, 'login_time', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;width:250px;", 'title' => 'Login time'),
                                ),
                                array(
                                    'name' => 'logout_time',
                                    'type' => 'raw',
                                    'value' => 'User::get_date_time($data->logout_time)',
                                    'filter' => CHtml::activeTextField($model, 'logout_time', array('class' => 'form-control')),
                                    'htmlOptions' => array('style' => "text-align:left;width:250px;", 'title' => 'Logout time'),
                                ),
                                array(
                                    'header' => 'Duration',
                                    'type' => 'raw',
                                    'value' => 'AuditTrail::returnInterval($data->login_time,$data->logout_time)',
                                ),
                                array(
                                    'header' => '',
                                    'class' => 'CButtonColumn',
                                    'htmlOptions' => array('style' => "text-align:center;width:40px;", 'class' => ''),
                                    'template' => '{delete}',
                                    'buttons' => array(
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