<?php
$this->pageTitle = 'Set user access';
$this->breadcrumbs = array(
    'Groups' => array('/userGroup/admin'),
    'Set user access',
);
Yii::app()->clientScript->registerScript('Check', "
$(function() {
        $('.checkall_1').on('click', function() {
            $(this).closest('fieldset').find(':checkbox').prop('checked', this.checked);
        });
    });
");
$getGroup = $_GET['id'];
?> 
<div class="row">
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-lock fa-fw "></i> 
            Access Manager 
            <span>> 
                Manage
            </span>
        </h1>
    </div>
    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 text-right">
        <?php echo CHtml::link('<span class="btn-label"><i class="fa fa-home"></i></span> User Group', array('userGroup/admin'), array('class' => 'btn btn-labeled btn-primary')); ?>
    </div>
</div>
<!-- start row -->
<div class="row">
    <!-- NEW WIDGET START -->
    <article class="col-sm-12 col-md-12 col-lg-12">
        <!-- Widget ID (each widget will need unique ID)-->
        <div class="jarviswidget jarviswidget-color-blueLight" id="wid-id-10" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-togglebutton="false" data-widget-deletebutton="false" data-widget-fullscreenbutton="false" data-widget-custombutton="false" data-widget-sortable="false">
            <header>
                <span class="widget-icon"> <i class="fa fa-users"></i> </span>
                <h2><?php echo UserGroup::get_group($_GET['id']); ?></h2>
                <div class="widget-toolbar">
                    <?php echo CHtml::link('<i class="fa fa-remove"></i> Deny all', '#', array('onclick' => 'accessall(1,' . $_GET['id'] . ')', 'data-placement' => 'bottom', 'title' => '', 'rel' => 'tooltip', 'data-original-title' => 'Deny all')); ?>
                </div>
                <div class="widget-toolbar">
                    <?php echo CHtml::link('<i class="fa fa-check-square-o"></i> Access all', '#', array('onclick' => 'accessall(2,' . $_GET['id'] . ')', 'data-placement' => 'bottom', 'title' => '', 'rel' => 'tooltip', 'data-original-title' => 'Access all')); ?>
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
                    <div class="panel-group smart-accordion-default" id="accordion">
                        <?php
                        $i = 1;
                        $controller_array = AclController::model()->findAll(array('condition' => 'status=1', 'order' => 'title'));
                        foreach ($controller_array as $key => $values) {
                            echo '<div class="panel panel-default">';
                            echo '<div class="panel-heading">';
                            if ($i == 1) {
                                echo '<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-' . $values["id"] . '" href="#collapseOne-' . $values["id"] . '"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> ' . $values["title"] . ' </a></h4>';
                            } else {
                                echo '<h4 class="panel-title"><a data-toggle="collapse" data-parent="#accordion-' . $values["id"] . '" href="#collapseOne-' . $values["id"] . '" class="collapsed"> <i class="fa fa-fw fa-plus-circle txt-color-green"></i> <i class="fa fa-fw fa-minus-circle txt-color-red"></i> ' . $values["title"] . ' </a></h4>';
                            }
                            echo '</div>';
                            if ($i == 1) {
                                echo '<div id="collapseOne-' . $values["id"] . '" class="panel-collapse collapse in">';
                            } else {
                                echo '<div id="collapseOne-' . $values["id"] . '" class="panel-collapse collapse">';
                            }
                            echo '<div class="panel-body">';
                            echo '<div style="margin-bottom:10px;">';
                            echo CHtml::link('<i class="fa fa-check-square-o"></i> Access all', '#', array('onclick' => 'accessallc(2,' . $_GET['id'] . ',"' . $values["controller"] . '")', 'data-placement' => 'bottom', 'title' => '', 'rel' => 'tooltip', 'data-original-title' => 'Access all', 'style' => 'margin-right:20px;'));
                            echo CHtml::link('<i class="fa fa-remove"></i> Deny all', '#', array('onclick' => 'accessallc(1,' . $_GET['id'] . ',"' . $values["controller"] . '")', 'data-placement' => 'bottom', 'title' => '', 'rel' => 'tooltip', 'data-original-title' => 'Deny all'));
                            echo '</div>';
                            $acl_array = Acl::model()->findAll(array('condition' => 'group_id=' . (int) $getGroup . ' AND controller="' . $values["controller"] . '"', 'order' => 'controller, actions ASC'));                            
                            foreach ($acl_array as $keys => $valuess) {
                                ?>
                                <div class="row">
                                    <article class="col-sm-4">
                                        <span class="onoffswitch-title">
                                            <i class="fa fa-check"></i> <?php echo $valuess["action_title"]; ?>
                                        </span>
                                    </article>
                                    <article class="col-sm-8">
                                        <div id="link_<?php echo $valuess["id"]; ?>">
                                            <span class="onoffswitch">
                                                <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="autoopen<?php echo $valuess["id"]; ?>" <?php if ($valuess["access"] == 1) { ?>checked="checked" onclick="turnoff(<?php print $valuess["id"]; ?>)"<?php } else { ?> onclick="turnon(<?php print $valuess["id"]; ?>)" <?php } ?>>
                                                <label class="onoffswitch-label" for="autoopen<?php echo $valuess["id"]; ?>"> 
                                                    <span class="onoffswitch-inner" data-swchon-text="ON" data-swchoff-text="OFF"></span> 
                                                    <span class="onoffswitch-switch"></span> 
                                                </label> 
                                            </span>
                                        </div>
                                    </article>                                        
                                </div>                                
                                <?php
                            }
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            $i++;
                        }
                        ?>                        
                    </div>
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
<script type="text/javascript">
    function turnon(id)
    {
        var id_string = 1;
        var tlink = document.getElementById("link_" + id);
        tlink.innerHTML = "<img src='<?php echo Yii::app()->theme->baseUrl; ?>/img/loading.gif'>";
        //return id_string;
        $.ajax({
            type: "GET",
            url: "<?php print $this->createUrl('userGroup/turnon'); ?>",
            data: "id=" + id,
            cache: false,
            async: false,
            success: function (result) {
                tlink.innerHTML = "<span class=\"onoffswitch\"><input type=\"checkbox\" onclick=\"turnoff('" + id + "');\"  checked id=\"autoopen" + id + "\" class=\"onoffswitch-checkbox\"><label for=\"autoopen" + id + "\" class=\"onoffswitch-label\"><div data-swchoff-text=\"OFF\" data-swchon-text=\"ON\" class=\"onoffswitch-inner\"></div><div class=\"onoffswitch-switch\"></div></label></span>";
            },
            error: function (result) {
                //alert(result);
                alert("some error occured, please try again later");
            }
        });
    }

    function turnoff(id)
    {
        var id_string = 1;
        var tlink = document.getElementById("link_" + id);
        tlink.innerHTML = "<img src='<?php echo Yii::app()->theme->baseUrl; ?>/img/loading.gif'>";
        //return id_string;
        $.ajax({
            type: "GET",
            url: "<?php print $this->createUrl('userGroup/turnoff'); ?>",
            data: "id=" + id,
            cache: false,
            async: false,
            success: function (result) {
                tlink.innerHTML = "<span class=\"onoffswitch\"><input type=\"checkbox\" onclick=\"turnon('" + id + "');\"  id=\"autoopen" + id + "\" class=\"onoffswitch-checkbox\"><label for=\"autoopen" + id + "\" class=\"onoffswitch-label\"><div data-swchoff-text=\"OFF\" data-swchon-text=\"ON\" class=\"onoffswitch-inner\"></div><div class=\"onoffswitch-switch\"></div></label></span>";
            },
            error: function (result) {
                //alert(result);
                alert("some error occured, please try again later");
            }
        });
    }

    function accessall(id, gid)
    {
        $.ajax({
            type: "GET",
            url: "<?php print $this->createUrl('userGroup/accessall'); ?>",
            data: "id=" + id + "&group_id=" + gid,
            cache: false,
            async: false,
            success: function (result) {
                window.location.href = window.location.href;
            },
            error: function (result) {
                //alert(result);
                alert("some error occured, please try again later");
            }
        });
    }

    function accessallc(id, gid, c)
    {
        $.ajax({
            type: "GET",
            url: "<?php print $this->createUrl('userGroup/accessallc'); ?>",
            data: "id=" + id + "&group_id=" + gid + "&cntrl=" + c,
            cache: false,
            async: false,
            success: function (result) {
                window.location.href = window.location.href;
            },
            error: function (result) {
                alert("some error occured, please try again later");
            }
        });
    }
</script>