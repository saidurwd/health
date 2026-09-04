<?php
/* @var $this PurchaseReceiveController */
/* @var $model PurchaseReceive */
$this->pageTitle = 'New Purchase Receive';
$this->breadcrumbs = array(
    'Purchase Receives' => array('admin'),
    'Create',
);

$noofItem = Product::countTotalItems();
if ($noofItem <= 0) {
    Yii::app()->user->setFlash('error', "You have no Product. Please create a Product first.");
    $this->redirect(array('/product/create'));
}
$noofVendor = Vendor::countTotalVendors();
if ($noofVendor <= 0) {
    Yii::app()->user->setFlash('error', "You have no Supplier. Please create a Supplier first.");
    $this->redirect(array('/vendor/create'));
}
$noofStore = Store::countTotalStore();
if ($noofStore <= 0) {
    Yii::app()->user->setFlash('error', "You have no Store. Please create a Store first.");
    $this->redirect(array('/store/create'));
}
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-shopping-cart fa-fw "></i> 
            Purchase
            <span>>
                New Receive
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">   
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-home"></i></span>MANAGE', array('admin'), array('class' => 'btn btn-labeled btn-primary')); ?>
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-download"></i></span> LOAD FROM PO', 'javascript:void(0)', array('onclick' => 'renderPurchaseOrder();', 'class' => 'btn btn-labeled btn-primary')); ?>        
    </div>
</div>
<!-- widget grid -->
<section id="widget-grid" class="">
    <!-- row -->
    <div class="row">
        <!-- NEW WIDGET START -->
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <!-- Widget ID (each widget will need unique ID)-->
            <div class="jarviswidget" id="wid-id-0" data-widget-editbutton="false" data-widget-colorbutton="false">
                <header>
                    <span class="widget-icon"> <i class="fa fa-plus"></i> </span>
                    <h2>New Receive</h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php //echo $this->renderPartial('_form', array('model' => $model, 'models' => $models, 'batch' => $batch, 'comments' => $comments)); ?>
                        <?php echo $this->renderPartial('_form', array('model' => $model, 'modelGrid' => $modelGrid, 'modelParent' => $modelParent, 'batch' => $batch, 'document' => $document)); ?>
                        <?php $this->renderPartial('_loadpo', array('loadpo' => $loadpo)); ?>
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
<div id="editData" ></div>
<script type="text/javascript">
    function renderFileUpload(id)
    {
        $.ajax({
            type: 'GET',
            url: '<?php echo Yii::app()->createAbsoluteUrl("purchaseReceive/upload"); ?>',
            data: {id: id},
            success: function (data) {
                //console.log(data);
                if (data != "false")
                {
                    $('#editData').html(data);
                    $('#modalCreateUpload').modal({show: true});
                }
            },
            error: function (data) { // if error occured
                alert("Error occured. Please try again");
            },
            dataType: 'html'
        });
    }
</script>
