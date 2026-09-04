<!-- CREATE Modal -->
<div class="modal fade" id="modalCreate" tabindex="-1" role="dialog" aria-labelledby="modalCreateLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="modalCreateLabel"><i class="fa fa-refresh"></i> LOAD ITEM FROM PURCHASE ORDER</h4>
            </div>
            <div class="modal-body custom-scroll load-po-scroll">
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'loadpo-grid',
                    'dataProvider' => $loadpo->search_purchase_receive(),
                    'filter' => $loadpo,
                    'htmlOptions' => array('class' => ''),
                    'itemsCssClass' => 'table table-bordered table-striped table-hover table-responsive',
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
                            'id' => 'selectedPOIds',
                            'class' => 'CCheckBoxColumn',
                            'selectableRows' => 2,
                            'value' => '$data->id',
                            'htmlOptions' => array('style' => "text-align:center;width:30px;"),
                        ),
                        array(
                            'name' => 'parent0.order_number',
                            'value' => '$data->parent0->order_number',
                            'filter' => CHtml::activeTextField($loadpo, 'parentOrderNumber', array('class' => 'form-control')),
                            'htmlOptions' => array('style' => "text-align:left;"),
                        ),
                        array(
                            'name' => 'item',
                            'value' => '$data->item0->title',
                            'filter' => CHtml::activeDropDownList($loadpo, 'item', CHtml::listData(Product::model()->findAll(array('select' => 'id, CONCAT(title," - ",product_code) AS title', 'condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'All', 'class' => 'select2')),
                            'htmlOptions' => array('style' => "text-align:left;width:150px;"),
                        ),
                        array(
                            'name' => 'quantity',
                            //'value' => 'Product::number_format($data->quantity,2)',
                            'value' => 'Product::number_format(PurchaseOrder::getAvailableQuantity($data->id),2)',
                            'filter' => CHtml::activeTextField($loadpo, 'quantity', array('class' => 'form-control')),
                            'htmlOptions' => array('style' => "text-align:right;width:100px;"),
                        ),
                        array(
                            'name' => 'parent0.supplier',
                            'value' => 'Vendor::get_vendor($data->parent0->supplier)',
                            'filter' => CHtml::activeDropDownList($loadpo, 'parentSupplier', CHtml::listData(Vendor::model()->findAll(array('condition' => '', 'order' => 'title')), 'id', 'title'), array('empty' => 'All', 'class' => 'select2')),
                            'htmlOptions' => array('style' => "text-align:left;"),
                        ),
                        array(
                            'name' => 'created_on',
                            'type' => 'raw',
                            'value' => 'User::get_date($data->created_on)',
                            'filter' => CHtml::activeTextField($loadpo, 'created_on', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')),
                            'htmlOptions' => array('style' => "text-align:left;width:150px;"),
                        ),
                        array(
                            'type' => 'raw',
                            'value' => 'CHtml::link("<i class=\'fa fa-plus\'></i>","javascript:void(0)",array("class"=>"btn btn-primary btn-xs", "onclick"=>"addToPurchaseReceive($data->id)"))',
                            'htmlOptions' => array('class' => 'text-center', 'style' => "width:40px;"),
                        ),
                    ),
                ));
                ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm pull-left" id="add-selected" onclick="moveSelected();"><i class="fa fa-plus"></i> ADD SELECTED</button>
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
    function addToPurchaseReceive(pono)
    {
        $.ajax({
            type: 'GET',
            url: '<?php echo Yii::app()->createAbsoluteUrl("purchaseReceive/addpo"); ?>',
            data: {id: pono},
            success: function (data) {
                //console.log(data);
                if (data != "false")
                {
                    //console.log(data);
                    $('#loadpo-grid').yiiGridView('update');
                    $('#purchase-receive-grid').yiiGridView('update');
                    $('.select2').select2();
                }
            },
            error: function (data) { // if error occured
                alert("Error occured. Please try again");
            },
            dataType: 'html'
        });
    }

    function renderPurchaseOrder()
    {
        $('#modalCreate').modal({
            show: true,
        });
    }

    function moveSelected() {
        var selectedIDs = [];
        $("[id^=selectedPOIds_]").each(function () {
            if ($(this).is(":checked")) {
                selectedIDs.push($(this).val());
            }
        });
        if (selectedIDs != "")
        {
            $.ajax({
                type: "GET",
                url: "<?php echo $this->createUrl('purchaseReceive/addselectedpo'); ?>",
                data: "selectedIDs=" + selectedIDs,
                cache: false,
                async: false,
                success: function (result) {
                    $('#loadpo-grid').yiiGridView('update');
                    $('#purchase-receive-grid').yiiGridView('update');
                    $('.select2').select2();
                },
                error: function (result) {
                    //alert(result);
                    alert("some error occured. Please try again.");
                },
                dataType: 'html'
            });
        }
    }
</script>
