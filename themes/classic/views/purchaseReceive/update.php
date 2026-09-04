<?php
/* @var $this PurchaseReceiveController */
/* @var $model PurchaseReceive */
$this->pageTitle = 'Edit Purchase Receive';
$this->breadcrumbs = array(
    'Purchase Receives' => array('admin'),
    PurchaseReceiveParent::getData(@$_REQUEST['id'], "receive_number") => array('view', 'id' => @$_REQUEST['id']),
    Yii::t('Common', 'update'),
);
Yii::app()->clientScript->registerScript('reload-script', "
function reloadPageSetUp() {
        pageSetUp();
    }
", CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-shopping-cart fa-fw "></i> 
            Purchase
            <span>>
                Edit Purchase Receive
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">   
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-home"></i></span>MANAGE', array('admin'), array('class' => 'btn btn-labeled btn-primary')); ?>
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-plus"></i></span> NEW', array('create'), array('class' => 'btn btn-labeled btn-primary')); ?>       
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-external-link-square"></i></span>DETAILS', array('view', 'id' => $_REQUEST['id']), array('class' => 'btn btn-labeled btn-primary')); ?>
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
                    <span class="widget-icon"> <i class="fa fa-home"></i> </span>
                    <h2><strong>Receive #: </strong><?php echo PurchaseReceiveParent::getData(@$_REQUEST['id'], "receive_number"); ?>, <strong>Receive Date: </strong><?php echo User::get_date_time(PurchaseReceiveParent::getData(@$_REQUEST['id'], "receive_date")); ?>, <strong>Receive By: </strong><?php echo User::get_full_name(PurchaseReceiveParent::getData(@$_REQUEST['id'], "receive_by")); ?></h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'purchase-receive-grid',
                            'dataProvider' => $model->searchReceive(@$_REQUEST['id']),
                            'afterAjaxUpdate' => 'reloadPageSetUp',
                            'htmlOptions' => array('class' => ''),
                            'itemsCssClass' => 'table table-bordered table-striped table-condensed table-hover',
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
                                    'name' => 'reference',
                                    'value' => 'PurchaseReceiveParent::getReferenceOrderNo($data->reference)',
                                    'htmlOptions' => array('style' => ''),
                                ),
                                array(
                                    'name' => 'item',
                                    'value' => 'Product::getItemName($data->item)',
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'name' => 'store',
                                    'value' => 'Store::get_stores_grid("PurchaseReceivePR","store",$data->store,$data->id)',
                                    'type' => 'raw',
                                    'htmlOptions' => array('style' => "text-align:left;width:150px;"),
                                ),
                                array(
                                    'name' => 'quantity',
                                    'value' => 'CHTML::textField("quantity_".$data->id,$data->quantity,array("class"=>"form-control", "onchange"=>"saveadjustment($data->id, this.value, \'quantity\')"))',
                                    'type' => 'raw',
                                    'htmlOptions' => array('style' => "text-align:right;width:120px;"),
                                ),
                                array(
                                    'header' => 'UOM',
                                    'type' => 'raw',
                                    'value' => 'Product::getItemUOM($data->item)',
                                    'htmlOptions' => array('class' => "text-center width-50"),
                                ),
                                array(
                                    'name' => 'rate',
                                    'value' => 'CHTML::textField("rate_".$data->id,$data->rate,array("class"=>"form-control", "onchange"=>"saveadjustment($data->id, this.value, \'rate\')"))',
                                    'type' => 'raw',
                                    'htmlOptions' => array('style' => "text-align:right;width:120px;"),
                                ),
                                array(
                                    'name' => 'total_amount',
                                    'value' => 'Product::number_format_currency($data->total_amount,2,Yii::app()->session->get(\'currency\'))',
                                    'htmlOptions' => array('style' => "text-align:right;width:150px;"),
                                ),
                                array(
                                    'name' => 'buy_rate',
                                    'value' => 'Product::number_format_currency($data->buy_rate,2,Yii::app()->session->get(\'currency\'))',
                                    'type' => 'raw',
                                    'htmlOptions' => array('style' => "text-align:right;width:120px;"),
                                ),
                                array(
                                    'name' => 'buy_amount',
                                    'value' => 'Product::number_format_currency($data->buy_amount,2,Yii::app()->session->get(\'currency\'))',
                                    'htmlOptions' => array('style' => "text-align:right;width:150px;"),
                                ),
                                array(
                                    'name' => 'expiry',
                                    'header' => 'Expiry',
                                    'value'=>'PurchaseReceive::getBatchData($data->batch,"expiry")',
                                    'type' => 'raw',
                                    'htmlOptions' => array('style' => "text-align:left;width:120px;"),
                                ),
                                array(
                                    'header' => 'Files',
                                    'type' => 'raw',
                                    'value' => 'PurchaseReceive::fileUpload($data->id)',
                                    'htmlOptions' => array('style' => "text-align:center;width:120px;"),
                                ),
                                array(
                                    'header' => 'Actions',
                                    'class' => 'CButtonColumn',
                                    'htmlOptions' => array('style' => "text-align:center;width:50px;", 'class' => ''),
                                    'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
                                    'template' => '{delete}',
                                    'buttons' => array(
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
                        <?php echo $this->renderPartial('_form_update', array('model' => $model, 'modelParent' => $modelParent, 'batch' => $batch, 'document' => $document)); ?>
                        <?php $this->renderPartial('_loadpoupdate', array('loadpo' => $loadpo)); ?>
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