<?php
/* @var $this StockIssueController */
/* @var $model StockIssue */

$this->pageTitle = 'Edit Stock Issue';
$this->breadcrumbs = array(
    'Stock Issue' => array('admin'),
    StockIssueParent::getData(@$_REQUEST['id'], "issue_number") => array('view', 'id' => @$_REQUEST['id']),
    Yii::t('Common', 'update'),
);
Yii::app()->clientScript->registerScript('reload-script', "
    function reloadPageSetUp() {
        pageSetUp();
    }
", CClientScript::POS_END);
Yii::app()->clientScript->registerScript('chained', '
        $("#StockIssue_store").chained("#StockIssue_item");
        $("#StockIssue_batch").chained("#StockIssue_item, #StockIssue_store");
    ', CClientScript::POS_END);
?>
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-shopping-cart fa-fw "></i> 
            Stock Issue
            <span>>
                Edit Issue
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">   
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-home"></i></span>MANAGE', array('admin'), array('class' => 'btn btn-labeled btn-primary')); ?>
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-plus"></i></span> NEW', array('create'), array('class' => 'btn btn-labeled btn-primary')); ?>       
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-external-link-square"></i></span>DETAILS', array('view', 'id' => $_REQUEST['id']), array('class' => 'btn btn-labeled btn-primary')); ?>
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
                    <h2><strong>Issue #: </strong><?php echo StockIssueParent::getData(@$_REQUEST['id'], "issue_number"); ?>, <strong>Issue Date: </strong><?php echo User::get_date_time(StockIssueParent::getData(@$_REQUEST['id'], "issue_date")); ?>, <strong>Order By: </strong><?php echo User::get_full_name(StockIssueParent::getData(@$_REQUEST['id'], "issue_by")); ?></h2>
                </header>
                <!-- widget div-->
                <div>
                    <!-- widget content -->
                    <div class="widget-body no-padding">
                        <?php
                        $this->widget('zii.widgets.grid.CGridView', array(
                            'id' => 'stock-issue-grid',
                            'dataProvider' => $model->searchIssue(@$_REQUEST['id']),
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
                                    'name' => 'item',
                                    'value' => 'Product::getItemName($data->item)',
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'name' => 'store',
                                    'value' => 'Store::get_store($data->store)',
                                    'htmlOptions' => array('style' => "text-align:left;"),
                                ),
                                array(
                                    'name' => 'batch',
                                    'value' => 'Batch::getBatch($data->batch)',
                                    'htmlOptions' => array('style' => "text-align:left;"),
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
                                    'htmlOptions' => array('class' => "text-center width-100"),
                                ),
                                array(
                                    'name' => 'rate',
                                    'value' => 'Product::number_format_currency($data->rate,2,Yii::app()->session->get(\'currency\'))',
                                    'htmlOptions' => array('style' => "text-align:right;width:100px;"),
                                ),
                                array(
                                    'name' => 'amount',
                                    'value' => 'Product::number_format_currency($data->amount,2,Yii::app()->session->get(\'currency\'))',
                                    'htmlOptions' => array('style' => "text-align:right;width:150px;"),
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
<script type="text/javascript">
    function saveadjustment(id, adjustment, type) {
        //alert(id+"-"+adjustment+"-"+type);
        //return id_string;
        if (id != "" && adjustment != "")
        {
            $.ajax({
                type: "GET",
                url: "<?php print $this->createUrl('stockIssue/adjustmentEdit'); ?>",
                data: "id=" + id + "&adjustment=" + adjustment + "&type=" + type,
                cache: false,
                async: false,
                success: function (result) {
                    $('#stock-issue-grid').yiiGridView('update');
                },
                error: function (result) {
                    //alert(result);
                    alert("some error occured. Please try again.");
                }
            });
        }
    }
</script>