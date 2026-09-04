<?php
$this->pageTitle = 'Visitors - ' . Yii::app()->name;
$this->breadcrumbs = array(
    'Visitors' => array('admin'),
    'Manage',
);
Yii::app()->clientScript->registerScript('re-install-date-picker', "
function reinstallDatePicker(id, data) {
    $('#datepicker1').datepicker();
    $('#datepicker2').datepicker();
}
");
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
            Visitors
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
                    <h2>Visitors</h2>   
                    <div class="widget-toolbar">
                        <?php echo CHtml::link('<i class="fa fa-times"></i>', array('truncate'), array('data-rel' => 'tooltip', 'title' => 'Truncate Data', 'data-placement' => 'bottom', 'class' => 'btn btn-xs btn-danger')); ?>
                    </div>
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
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'name' => 'user_name',
                                    'type' => 'raw',
                                    'value' => '$data->user_name',
                                    'filter' => CHtml::activeTextField($model, 'user_name', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'name' => 'page_title',
                                    'type' => 'raw',
                                    'value' => '$data->page_title',
                                    'filter' => CHtml::activeTextField($model, 'page_title', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'name' => 'page_link',
                                    'type' => 'raw',
                                    'value' => '$data->page_link',
                                    'filter' => CHtml::activeTextField($model, 'page_link', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'name' => 'server_time',
                                    'value' => 'User::get_date_time($data->server_time)',
                                    'filter' => $this->widget('zii.widgets.jui.CJuiDatePicker', array('model' => $model, 'attribute' => 'server_time', 'htmlOptions' => array('id' => 'datepicker2', 'size' => '10', 'class' => 'form-control'), 'i18nScriptFile' => 'jquery.ui.datepicker-en.js', 'defaultOptions' => array('showOn' => 'focus', 'dateFormat' => 'yy-mm-dd', 'showOtherMonths' => true, 'selectOtherMonths' => true, 'changeMonth' => true, 'changeYear' => true, 'showButtonPanel' => false,)), true),
                                    'htmlOptions' => array('style' => "text-align:center;"),
                                ),
                                array(
                                    'name' => 'browser',
                                    'type' => 'raw',
                                    'value' => '$data->browser',
                                    'filter' => CHtml::activeTextField($model, 'browser', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
                                ),
                                array(
                                    'name' => 'visitor_ip',
                                    'type' => 'raw',
                                    'value' => '$data->visitor_ip',
                                    'filter' => CHtml::activeTextField($model, 'visitor_ip', array('class' => 'form-control')),
                                    'htmlOptions' => array('class' => ''),
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