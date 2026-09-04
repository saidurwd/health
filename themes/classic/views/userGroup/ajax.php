<div id="editData" >

</div>
<script type="text/javascript">
    function update()
    {
        var data = $("#group-update-form").serialize();
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("userGroup/update"); ?>',
            data: data,
            success: function (data) {
                if (data != "false")
                {
                    $('#modalEdit').modal('hide');
                    $.fn.yiiGridView.update('user-group-grid', {
                    });
                }
            },
            error: function (data) { // if error occured
                alert(JSON.stringify(data));
            },
            dataType: 'html'
        });

    }
    function renderUpdateForm(id)
    {
        //$('#modal').modal('hide');
        var data = "id=" + id;
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo Yii::app()->createAbsoluteUrl("userGroup/update"); ?>',
            data: data,
            success: function (data) {
                // alert("succes:"+data); 
                $('#editData').html(data);
                $('#modalEdit').modal('show');
            },
            error: function (data) { // if error occured
                alert(JSON.stringify(data));
                alert("Error occured. Please try again");
            },
            dataType: 'html'
        });

    }
</script>