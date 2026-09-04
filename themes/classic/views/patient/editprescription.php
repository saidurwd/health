<?php
/* @var $this PatientController */
/* @var $model Patient */
$this->pageTitle = 'Edit Prescription';
$this->breadcrumbs = array(
    'Prescriptions' => array('view', 'id' => $model->patient),
    $model->pre_number => array('view', 'id' => $model->patient),
    'Update',
);
?>
<div class="row">
    <div class="col-xs-12 col-sm-7 col-md-7 col-lg-4">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-home fa-fw "></i> 
            Prescriptions 
            <span>> 
                Edit Prescription
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
                    <span class="widget-icon"> <i class="fa fa-plus"></i> </span>
                    <h2>Edit Prescription</h2>	
                    <div class="widget-toolbar">
                        <?php echo CHtml::link('<i class="fa fa-home"></i> BACK', array('view', 'id' => $model->patient), array('data-rel' => 'tooltip', 'title' => 'Back', 'data-placement' => 'bottom', 'class' => 'btn btn-xs btn-primary')); ?>
                        <?php echo CHtml::link('<i class="fa fa-print"></i> PRINT', array('patient/prescription', 'id' => $model->patient, 'preid' => $model['id']), array('target' => '_blank', 'data-rel' => 'tooltip', 'title' => 'Print', 'data-placement' => 'bottom', 'class' => 'btn btn-xs btn-info')); ?>
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
                        <?php $this->renderPartial('_prescription', array('model' => $model, 'modelData' => $modelData, 'modelGrid' => $modelGrid)); ?>
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