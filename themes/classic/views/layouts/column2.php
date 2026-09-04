<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>
<?php User::get_alert_message(); ?>
<?php echo $content; ?>
<div class="modal fade" id="newData" role="dialog" aria-labelledby="newDataLabel" data-backdrop="static" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">            
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                    &times;
                </button>
                <h4 class="modal-title" id="myModalLabel">Terms & Conditions</h4>
            </div>
            <div class="modal-body custom-scroll terms-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">
                    Cancel
                </button>
                <button type="button" class="btn btn-primary" id="i-agree">
                    <i class="fa fa-check"></i> I Agree
                </button>
                <button type="button" class="btn btn-danger pull-left" id="print">
                    <i class="fa fa-print"></i> Print
                </button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?php
Yii::app()->clientScript->registerScript('modal', "
        $(document).ready(function() {
            $('#newData').on('hidden.bs.modal', function () {
              $(this).removeData('bs.modal');
            });
        });
    ", CClientScript::POS_END);
?>
<?php $this->endContent(); ?>