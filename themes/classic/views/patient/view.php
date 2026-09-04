<?php
/* @var $this PatientController */
/* @var $model Patient */
$this->pageTitle = 'Patient Details';
$this->breadcrumbs = array(
    'Patients' => array('admin'),
    $model->name,
);
Yii::app()->clientScript->registerScript('setup', "
function reloadPageSetUp() {
        pageSetUp();
    }
", CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-shopping-cart fa-fw "></i> 
            Patients
            <span>>
                Patient Details
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9 text-right">   
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-home"></i></span>MANAGE', array('admin'), array('class' => 'btn btn-labeled btn-primary')); ?>
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-plus"></i></span> NEW', array('create'), array('class' => 'btn btn-labeled btn-primary')); ?>       
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-pencil"></i></span>EDIT', array('update', 'id' => $_REQUEST['id']), array('class' => 'btn btn-labeled btn-primary')); ?>
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-plus"></i></span> NEW PRESCRIPTION', array('newprescription', 'id' => $model->id), array('class' => 'btn btn-labeled btn-primary')); ?> 
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-print"></i></span> HEALTH CARD', array('card', 'id' => $model->id), array('target' => '_blank', 'class' => 'btn btn-labeled btn-info')); ?>  
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-print"></i></span> REHABILITATION', array('rehabilitation', 'id' => $model->id), array('target' => '_blank', 'class' => 'btn btn-labeled btn-info')); ?>  
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-print"></i></span> REGISTRATION', array('registration', 'id' => $model->id), array('target' => '_blank', 'class' => 'btn btn-labeled btn-info')); ?>  
        <?php //echo CHtml::link('<span class="btn-label"><i class="fa fa-print"></i></span> PRESCRIPTION', array('prescription', 'id' => $model->id), array('target' => '_blank', 'class' => 'btn btn-labeled btn-info')); ?> 
    </div>
</div>
<div class="jarviswidget" id="wid-id-8" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false" role="widget">
    <header role="heading">
        <h2>Patient Details</h2>
        <ul class="nav nav-tabs pull-right in">
            <li class=""><a data-toggle="tab" href="#TAB1" aria-expanded="false">HOME</a></li>
            <li class="active"><a data-toggle="tab" href="#TAB2" aria-expanded="true">PRESCRIPTION</a></li>
            <li class=""><a data-toggle="tab" href="#TAB3" aria-expanded="true">INVOICES</a></li>
        </ul>
        <span class="jarviswidget-loader"><i class="fa fa-refresh fa-spin"></i></span></header>
    <!-- widget div-->
    <div role="content">
        <!-- widget edit box -->
        <div class="jarviswidget-editbox">
            <!-- This area used as dropdown edit box -->
        </div>
        <!-- end widget edit box -->
        <!-- widget content -->
        <div class="widget-body">
            <div class="tab-content">
                <div class="tab-pane" id="TAB1">
                    <?php
                    $this->widget('zii.widgets.CDetailView', array(
                        'htmlOptions' => array('class' => 'table table-bordered table-striped table-hover'),
                        'data' => $model,
                        'attributes' => array(
                            array(
                                'name' => 'category_new',
                                'type' => 'raw',
                                'value' => $model->category_new0->alias,
                            ),
                            array(
                                'name' => 'category',
                                'type' => 'raw',
                                'value' => $model->category0->alias,
                            ),
                            array(
                                'name' => 'pat_id',
                                'type' => 'raw',
                                'value' => $model->pat_id,
                            ),
                            array(
                                'name' => 'ref_no',
                                'type' => 'raw',
                                'value' => $model->ref_no,
                            ),
                            array(
                                'name' => 'name',
                                'type' => 'raw',
                                'value' => $model->name,
                            ),
                            array(
                                'name' => 'age',
                                'type' => 'raw',
                                'value' => Patient::getPatiantAge($model->id),
                            ),
                            'sex',
                            array(
                                'name' => 'birth_date',
                                'type' => 'raw',
                                'value' => User::get_date($model->birth_date),
                            ),
                            'blood_groop',
                            'marital_status',
                            'email',
                            'national_id',
                            'spouse',
                            'occupation',
                            'religion',
                            'address',
                            array(
                                'name' => 'thana',
                                'type' => 'raw',
                                'value' => Thana::getData($model->thana, 'title'),
                                'htmlOptions' => array('style' => "text-align:left;"),
                            ),
                            array(
                                'name' => 'district',
                                'type' => 'raw',
                                'value' => District::getData($model->district, 'title'),
                                'htmlOptions' => array('style' => "text-align:left;"),
                            ),
                            array(
                                'name' => 'country',
                                'type' => 'raw',
                                'value' => Country::getData($model->country, 'title'),
                                'htmlOptions' => array('style' => "text-align:left;"),
                            ),
                            'mobile',
                            'emergency_name',
                            'emergency_relation',
                            'emergency_contact',
                            array(
                                'name' => 'created_on',
                                'type' => 'raw',
                                'value' => User::get_date_time($model->created_on),
                            ),
                            array(
                                'name' => 'created_by',
                                'type' => 'raw',
                                'value' => User::get_full_name($model->created_by),
                                'htmlOptions' => array('style' => "text-align:left;"),
                            ),
                        ),
                    ));
                    ?>
                </div>
                <div class="tab-pane active" id="TAB2">
                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'id' => 'prescription-grid',
                        'dataProvider' => $model_prescription->search_patient($model->id),
                        'filter' => $model_prescription,
                        'afterAjaxUpdate' => 'reloadPageSetUp',
                        'htmlOptions' => array('class' => ''),
                        'itemsCssClass' => 'table table-bordered table-striped table-hover',
                        'template' => '{items}{pager}{summary}',
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
                                'name' => 'pre_number',
                                'type' => 'raw',
                                'value' => '$data->pre_number',
                                'filter' => CHtml::activeTextField($model_prescription, 'pre_number', array('class' => 'form-control')),
                                'htmlOptions' => array('class' => 'text-left'),
                            ),
                            array(
                                'name' => 'diagnosis',
                                'type' => 'raw',
                                'value' => 'Disease::getData($data->diagnosis,"title")',
                                'filter' => CHtml::activeDropDownList($model_prescription, 'diagnosis', CHtml::listData(Disease::model()->findAll(array('condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'All', 'class' => 'select2')),
                                'htmlOptions' => array('style' => "text-align:left;width:200px;"),
                            ),
                            array(
                                'name' => 'cc',
                                'type' => 'raw',
                                'value' => '$data->cc',
                                'filter' => CHtml::activeTextField($model_prescription, 'cc', array('class' => 'form-control')),
                                'htmlOptions' => array('class' => 'text-left'),
                            ),
                            array(
                                'name' => 'oe',
                                'type' => 'raw',
                                'value' => '$data->oe',
                                'filter' => CHtml::activeTextField($model_prescription, 'oe', array('class' => 'form-control')),
                                'htmlOptions' => array('class' => 'text-left'),
                            ),
                            array(
                                'name' => 'bp',
                                'type' => 'raw',
                                'value' => '$data->bp',
                                'filter' => CHtml::activeTextField($model_prescription, 'bp', array('class' => 'form-control')),
                                'htmlOptions' => array('class' => 'text-left'),
                            ),
                            array(
                                'name' => 'pulse',
                                'type' => 'raw',
                                'value' => '$data->pulse',
                                'filter' => CHtml::activeTextField($model_prescription, 'pulse', array('class' => 'form-control')),
                                'htmlOptions' => array('class' => 'text-left'),
                            ),
                            array(
                                'name' => 'temp',
                                'type' => 'raw',
                                'value' => '$data->temp',
                                'filter' => CHtml::activeTextField($model_prescription, 'temp', array('class' => 'form-control')),
                                'htmlOptions' => array('class' => 'text-left'),
                            ),
                            array(
                                'name' => 'created_on',
                                'type' => 'raw',
                                'value' => 'User::get_date($data->created_on)',
                                'filter' => CHtml::activeTextField($model_prescription, 'created_on', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')),
                                'htmlOptions' => array('style' => "text-align:left;width:150px;"),
                            ),
                            array(
                                'header' => 'Actions',
                                'class' => 'CButtonColumn',
                                'htmlOptions' => array('style' => "text-align:left;width:125px;", 'class' => ''),
                                'template' => '{update} {delete} {print} {preblank}',
                                'buttons' => array(
                                    'update' => array(
                                        'label' => '',
                                        'imageUrl' => '',
                                        'url' => 'yii::app()->createUrl("patient/editprescription", array("id"=>$data["id"]))',
                                        'options' => array('class' => 'btn btn-xs btn-primary fa fa-pencil', 'rel' => 'tooltip', 'data-original-title' => 'Edit'),
                                    ),
                                    'delete' => array(
                                        'label' => '',
                                        'imageUrl' => '',
                                        'url' => 'yii::app()->createUrl("patient/remove", array("id"=>$data["id"]))',
                                        'options' => array('class' => 'btn btn-xs btn-danger fa fa-trash-o', 'rel' => 'tooltip', 'data-original-title' => 'Delete'),
                                    ),
                                    'print' => array(
                                        'label' => '',
                                        'imageUrl' => '',
                                        'url' => 'yii::app()->createUrl("patient/prescription", array("id"=>$data["patient"],"preid"=>$data["id"]))',
                                        'options' => array('class' => 'btn btn-xs btn-primary fa fa-print', 'rel' => 'tooltip', 'data-original-title' => 'Print', 'target' => '_blank'),
                                    ),
                                    'preblank' => array(
                                        'label' => '',
                                        'imageUrl' => '',
                                        'url' => 'yii::app()->createUrl("patient/preblank", array("id"=>$data["patient"],"preid"=>$data["id"]))',
                                        'options' => array('class' => 'btn btn-xs btn-warning fa fa-print', 'rel' => 'tooltip', 'data-original-title' => 'Blank Prescription', 'target' => '_blank'),
                                    ),
                                ),
                            ),
                        ),
                    ));
                    ?>
                </div>
                <div class="tab-pane" id="TAB3">
                    <?php
                    $this->widget('zii.widgets.grid.CGridView', array(
                        'id' => 'invoice-parent-grid',
                        'dataProvider' => $model_invoice->search_patient($model->id),
                        'filter' => $model_invoice,
                        'afterAjaxUpdate' => 'reloadPageSetUp',
                        'htmlOptions' => array('class' => ''),
                        'itemsCssClass' => 'table table-bordered table-striped table-hover',
                        'template' => '{items}{pager}{summary}',
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
                                'name' => 'patient',
                                'type' => 'raw',
                                'value' => 'Patient::getData($data->patient,"name")',
                                'filter' => CHtml::activeDropDownList($model_invoice, 'patient', CHtml::listData(Patient::model()->findAll(array('condition' => '', 'order' => 'name')), 'id', 'name'), array('empty' => 'All', 'class' => 'select2')),
                                'htmlOptions' => array('class' => 'text-left'),
                            ),
                            array(
                                'name' => 'invoice_number',
                                'type' => 'raw',
//                                'value' => '$data->invoice_number',
                                'value' => 'CHtml::link($data->invoice_number, array("invoice/view","id"=>$data->id),array("target"=>"_blank"))',
                                'filter' => CHtml::activeTextField($model_invoice, 'invoice_number', array('class' => 'form-control')),
                                'htmlOptions' => array('style' => "text-align:left;"),
                            ),
                            array(
                                'name' => 'invoice_date',
                                'type' => 'raw',
                                'value' => 'User::get_date($data->invoice_date)',
                                'filter' => CHtml::activeTextField($model_invoice, 'invoice_date', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')),
                                'htmlOptions' => array('style' => "text-align:left;width:150px;"),
                            ),
                            array(
                                'type' => 'raw',
                                'header' => "# of Items",
                                'value' => 'Invoice::getNumberOfItems($data->id)',
                                'htmlOptions' => array('style' => "text-align:center;width:100px;"),
                            ),
                            array(
                                'name' => 'total_amount',
                                'type' => 'raw',
                                'value' => 'Product::number_format_currency($data->total_amount,2,Yii::app()->session->get(\'currency\'))',
                                'filter' => CHtml::activeTextField($model_invoice, 'total_amount', array('class' => 'form-control')),
                                'htmlOptions' => array('class' => "text-right", 'style' => 'width:150px;'),
                            ),
                            array(
                                'name' => 'invoice_by',
                                'type' => 'raw',
                                'value' => 'User::get_full_name($data->invoice_by)',
                                'filter' => CHtml::activeDropDownList($model_invoice, 'invoice_by', CHtml::listData(User::model()->findAll(array('condition' => '', 'order' => 'full_name')), 'id', 'full_name'), array('empty' => 'All', 'class' => 'select2')),
                                'htmlOptions' => array('style' => "text-align:left;width:200px;"),
                            ),
                            array(
                                'name' => 'status',
                                'type' => 'raw',
                                'value' => 'TransectionStatus::getStatus($data->status,5)',
                                'filter' => CHtml::activeDropDownList($model_invoice, 'status', CHtml::listData(TransectionStatus::model()->findAll(array('condition' => 'user_view=1 AND transection_type=5', "order" => "id")), 'status_id', 'status_title'), array('empty' => 'All', 'class' => 'select2')),
                                'htmlOptions' => array('style' => "text-align:left;width:100px"),
                            ),
                            array(
                                'header' => 'Actions',
                                'class' => 'CButtonColumn',
                                'htmlOptions' => array('style' => "text-align:left;width:125px;", 'class' => ''),
                                'template' => '{view} {update} {delete} {edit} {print}',
                                'buttons' => array(
                                    'view' => array(
                                        'label' => '',
                                        'imageUrl' => '',
                                        'url' => 'yii::app()->createUrl("invoice/view", array("id"=>$data["id"]))',
                                        'options' => array('class' => 'btn btn-xs btn-info fa fa-search', 'rel' => 'tooltip', 'data-original-title' => 'View'),
                                    ),
                                    'update' => array(
                                        'label' => '',
                                        'imageUrl' => '',
                                        'visible' => 'InvoiceParent::visibleActions($data->id)',
                                        'options' => array('class' => 'btn btn-xs btn-primary fa fa-pencil', 'rel' => 'tooltip', 'data-original-title' => 'Edit'),
                                    ),
                                    'delete' => array(
                                        'label' => '',
                                        'imageUrl' => '',
                                        'visible' => 'InvoiceParent::visibleActions($data->id)',
                                        'url' => 'yii::app()->createUrl("invoice/remove", array("id"=>$data["id"]))',
                                        'options' => array('class' => 'btn btn-xs btn-danger fa fa-trash-o', 'rel' => 'tooltip', 'data-original-title' => 'Delete'),
                                    ),
                                    'edit' => array(
                                        'label' => '',
                                        'imageUrl' => '',
                                        'visible' => 'InvoiceParent::visibleActionEdit($data->id)',
                                        'url' => 'yii::app()->createUrl("invoice/edit", array("id"=>$data["id"]))',
                                        'options' => array('class' => 'btn btn-xs btn-warning fa fa-edit', 'rel' => 'tooltip', 'data-original-title' => 'Special Edit'),
                                    ),
                                    'print' => array(
                                        'label' => '',
                                        'imageUrl' => '',
                                        'url' => 'yii::app()->createUrl("invoice/print", array("id"=>$data["id"]))',
                                        'options' => array('class' => 'btn btn-xs btn-primary fa fa-print', 'rel' => 'tooltip', 'data-original-title' => 'Print', 'target' => '_blank'),
                                    ),
                                ),
                            ),
                        ),
                    ));
                    ?>
                </div>
            </div>
        </div>
        <!-- end widget content -->
    </div>
    <!-- end widget div -->
</div>