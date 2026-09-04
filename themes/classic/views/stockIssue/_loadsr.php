<!-- CREATE Modal -->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="modalCreateLabel"><i class="fa fa-refresh"></i> LOAD ITEM FROM STOCK REQUISITION</h4>
            </div>
            <div class="modal-body">
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'loadsr-grid',
                    'dataProvider' => $loadsr->search_stock_requisition(),
                    'filter' => $loadsr,
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
                            'name' => 'parent0.requisition_number',
                            'value' => '$data->parent0->requisition_number',
                            'filter' => CHtml::activeTextField($loadsr, 'parentRequisitionNumber', array('class' => 'form-control')),
                            'htmlOptions' => array('style' => "text-align:left;"),
                        ),
                        array(
                            'name' => 'item',
                            'value' => '$data->item0->title',
                            'filter' => CHtml::activeDropDownList($loadsr, 'item', CHtml::listData(Product::model()->findAll(array('select' => 'id, CONCAT(title," - ",product_code) AS title', 'condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'All', 'class' => 'form-control')),
                            'htmlOptions' => array('style' => "text-align:left;width:150px;"),
                        ),
                        array(
                            'name' => 'store',
                            'value' => 'Store::get_store($data->store)',
                            'filter' => Store::get_parents('StockRequisition', 'store', $loadsr->store),
                            'htmlOptions' => array('style' => "text-align:left;"),
                        ),
                        array(
                            'name' => 'batch',
                            'header' => 'Expiry',
                            'value' => 'Batch::getBatch($data->batch)',
                            'filter' => CHtml::activeTextField($loadsr, 'batch', array('class' => 'form-control')),
                            'htmlOptions' => array('style' => "text-align:left;"),
                        ),
                        array(
                            'name' => 'quantity',
                            //'value' => 'Product::number_format($data->quantity,2)',
                            'value' => 'Product::number_format(StockRequisition::getAvailableQuantity($data->id),2)',
                            'filter' => CHtml::activeTextField($loadsr, 'quantity', array('class' => 'form-control')),
                            'htmlOptions' => array('style' => "text-align:right;width:100px;"),
                        ),
                        array(
                            'name' => 'rate',
                            'value' => 'Product::number_format_currency($data->rate,2,Yii::app()->session->get(\'currency\'))',
                            'filter' => CHtml::activeTextField($loadsr, 'rate', array('class' => 'form-control')),
                            'type' => 'raw',
                            'htmlOptions' => array('style' => "text-align:right;width:120px;"),
                        ),
                        array(
                            'name' => 'amount',
                            'value' => 'Product::number_format_currency($data->amount,2,Yii::app()->session->get(\'currency\'))',
                            'filter' => CHtml::activeTextField($loadsr, 'amount', array('class' => 'form-control')),
                            'htmlOptions' => array('style' => "text-align:right;width:150px;"),
                        ),
                        array(
                            'name' => 'created_on',
                            'type' => 'raw',
                            'value' => 'User::get_date($data->created_on)',
                            'filter' => CHtml::activeTextField($loadsr, 'created_on', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')),
                            'htmlOptions' => array('style' => "text-align:left;width:150px;"),
                        ),
                        array(
                            'type' => 'raw',
                            'value' => 'CHtml::link("<i class=\'fa fa-plus\'></i>","javascript:void(0)",array("class"=>"btn btn-primary btn-xs", "onclick"=>"addToStockIssue($data->id)"))',
                            'htmlOptions' => array('class' => 'text-center', 'style' => "width:40px;"),
                        ),
                    ),
                ));
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
    function addToStockIssue(reqno)
    {
        $.ajax({
            type: 'GET',
            url: '<?php echo Yii::app()->createAbsoluteUrl("stockIssue/addsr"); ?>',
            data: {id: reqno},
            success: function (data) {
                //console.log(data);
                if (data != "false")
                {
                    //console.log(data);
                    $("#loadsr-grid").load('<?php echo Yii::app()->getRequest()->getUrl(); ?> #loadsr-grid');
                    $("#stock-issue-grid").load('<?php echo Yii::app()->getRequest()->getUrl(); ?> #stock-issue-grid');
                }
            },
            error: function (data) { // if error occured
                alert("Error occured. Please try again");
            },
            dataType: 'html'
        });
    }

    function renderStockRequisition()
    {
        $('#modalCreate').modal({
            show: true,
        });
    }
</script>
