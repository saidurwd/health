<!-- CREATE Modal -->
<div class="modal fade" id="modalCreateUpload" tabindex="-1" role="dialog" aria-labelledby="modalCreateUploadLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="modalCreateUploadLabel"><i class="fa fa-upload"></i> UPLOAD FILES FOR <span class="txt-color-green"><?php echo Product::getItemName($model->item); ?></span></h4>
            </div>
            <div class="modal-body custom-scroll load-po-scroll">
                <?php
                $form_document = $this->beginWidget('CActiveForm', array(
                    'id' => 'purchase-receive-document-form',
                    'enableAjaxValidation' => false,
                    'htmlOptions' => array(
                        'class' => 'smart-form',
                        'enctype' => 'multipart/form-data',
                        'onsubmit' => "return false;", /* Disable normal form submit */
                    //'onkeypress' => " if(event.keyCode == 13){ upload(); } " /* Do ajax call when user presses enter key */
                    ),
                ));
                ?>
                <?php echo $form_document->hiddenField($modelDocument, 'receive_number', array('value' => $model->id)); ?>
                <fieldset>
                    <section class="col col-6">
                        <div class="input">
                            <?php echo $form_document->textField($modelDocument, 'doc_title', array('class' => 'input-sm', 'placeholder' => 'Document Title')); ?>
                            <?php echo $form_document->error($modelDocument, 'doc_title', array('class' => 'text-danger')); ?>  
                        </div>
                    </section>
                    <section class="col col-6">
                        <div class="input input-file">
                            <span class="button"><?php echo $form_document->fileField($modelDocument, 'doc_file', array('onchange' => 'this.parentNode.nextSibling.value = this.value')); ?>Choose Files</span><input placeholder="Choose files To Upload" readonly="" type="text">
                        </div>
                    </section>                           
                </fieldset>
                <?php echo CHtml::submitButton('Submit', array('onclick' => 'upload();', 'id' => 'FileUpload', 'style' => 'display:none;')); ?>
                <?php $this->endWidget(); ?> 
                <?php
                $this->widget('zii.widgets.grid.CGridView', array(
                    'id' => 'purchase-receive-document-grid',
                    'dataProvider' => $modelList->search($model->id),
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
                            'name' => 'doc_title',
                            'value' => '$data->doc_title',
                            'filter' => CHtml::activeTextField($modelList, 'doc_title', array('class' => 'form-control')),
                            'htmlOptions' => array('style' => "text-align:left;"),
                        ),
                        array(
                            'name' => 'created_by',
                            'type' => 'raw',
                            'value' => 'User::get_full_name($data->created_by)',
                            'filter' => CHtml::activeDropDownList($modelList, 'created_by', CHtml::listData(User::model()->findAll(array('condition' => '', "order" => "full_name")), 'id', 'full_name'), array('empty' => 'All', 'class' => 'select2')),
                            'htmlOptions' => array('style' => "text-align:left; width:200px;"),
                        ),
                        array(
                            'name' => 'created_on',
                            'value' => 'User::get_date($data->created_on)',
                            'filter' => CHtml::activeTextField($modelList, 'created_on', array('class' => 'form-control datepicker', 'data-dateformat' => 'yy-mm-dd')),
                            'htmlOptions' => array('style' => "text-align:left;width:150px;"),
                        ),
                        array(
                            'header' => Yii::t('Project', 'Download'),
                            'class' => 'CButtonColumn',
                            'htmlOptions' => array('style' => "text-align:center;width:100px;", 'class' => ''),
                            'afterDelete' => 'function(link,success,data){ if(success) $("#statusMsg").html(data); }',
                            'template' => '{download} {delete}',
                            'buttons' => array(
                                'download' => array(
                                    'label' => '',
                                    'imageUrl' => '',
                                    'url' => 'yii::app()->createUrl("purchaseReceive/downloadfile", array("id"=>$data["id"]))',
                                    'options' => array('class' => 'btn btn-xs btn-warning fa fa-download', 'rel' => 'tooltip', 'data-original-title' => 'Download'),
                                ),
                                'delete' => array(
                                    'label' => '',
                                    'imageUrl' => '',
                                    'url' => 'yii::app()->createUrl("purchaseReceive/deletefile", array("id"=>$data["id"]))',
                                    'options' => array('class' => 'btn btn-xs btn-danger fa fa-trash-o', 'rel' => 'tooltip', 'data-original-title' => 'Delete'),
                                ),
                            ),
                        ),
                    ),
                ));
                ?>                
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-sm" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
    $("#PurchaseReceiveDocument_doc_file").change(function () {
        $('#FileUpload').click();
    });
    function upload()
    {
        //var data = $("#project-document-form").serialize();
        var formData = new FormData($('#purchase-receive-document-form')[0]);
        $.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("purchaseReceive/docupload"); ?>',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                //alert("succes:" + data);
                if (data != "false")
                {
                    $("#purchase-receive-document-grid").load('<?php echo Yii::app()->getRequest()->getUrl(); ?> #purchase-receive-document-grid');
                    $('#purchase-receive-document-form').each(function () {
                        this.reset();
                    });
                    successNotificationSmall('Document was uploaded!', '');
                }
            },
            error: function (data) { // if error occured
                errorNotificationSmall('Error occured. Please try again!', '');
                $('#purchase-receive-document-form').each(function () {
                    this.reset();
                });
                //alert(data);
            },
            dataType: 'html'
        });
    }
</script>