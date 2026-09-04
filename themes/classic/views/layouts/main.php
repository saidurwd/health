<!DOCTYPE html>
<html lang="en-us">	
    <head>
        <meta charset="utf-8">
        <title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <meta name="description" content="">
        <meta name="author" content="Optimo Solution">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <!-- #CSS Links -->
        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/font-awesome.min.css">
        <!-- SmartAdmin Styles : Please note (smartadmin-production.css) was created using LESS variables -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/smartadmin-production.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/smartadmin-skins.min.css">
        <!-- Demo purpose only: goes with demo.js, you can delete this css when designing your own WebApp -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/demo.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/styles.css">
        <!-- #FAVICONS -->
        <link rel="shortcut icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/favicon/favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/favicon/favicon.ico" type="image/x-icon">
        <!-- #GOOGLE FONT -->
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,700italic,300,400,700">
        <!-- #APP SCREEN / ICONS -->
        <!-- Specifying a Webpage Icon for Web Clip 
                 Ref: https://developer.apple.com/library/ios/documentation/AppleApplications/Reference/SafariWebContent/ConfiguringWebApplications/ConfiguringWebApplications.html -->
        <link rel="apple-touch-icon" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/splash/sptouch-icon-iphone.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/splash/touch-icon-ipad.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/splash/touch-icon-iphone-retina.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/splash/touch-icon-ipad-retina.png">
        <!-- iOS web-app metas : hides Safari UI Components and Changes Status Bar Appearance -->
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <!-- Startup image for web apps -->
        <link rel="apple-touch-startup-image" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/splash/ipad-landscape.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)">
        <link rel="apple-touch-startup-image" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/splash/ipad-portrait.png" media="screen and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)">
        <link rel="apple-touch-startup-image" href="<?php echo Yii::app()->theme->baseUrl; ?>/img/splash/iphone.png" media="screen and (max-device-width: 320px)">
        <script>
            if (!window.jQuery) {
                document.write('<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/libs/jquery-2.0.2.min.js"><\/script>');
            }
        </script>
    </head>
    <body class="<?php echo @$this->bodyClass; ?>">
        <!-- #HEADER -->
        <header id="header">
            <div id="logo-group">
                <!-- PLACE YOUR LOGO HERE -->
                <span id="logo"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/logo.png" alt="<?php echo Yii::app()->name; ?>" title="<?php echo Yii::app()->name; ?>"></span>
                <!-- END LOGO PLACEHOLDER -->
                <!-- Note: The activity badge color changes when clicked and resets the number to 0
                Suggestion: You may want to set a flag when this happens to tick off all checked messages / notifications -->
                <span id="activity" class="activity-dropdown"> <i class="fa fa-user"></i> <b class="badge"> <?php //echo Issue::getTotalIssueUnread();  ?> </b> </span>
                <!-- AJAX-DROPDOWN : control this dropdown height, look and feel from the LESS variable file -->
                <div class="ajax-dropdown">
                    <!-- the ID links are fetched via AJAX to the ajax container "ajax-notifications" -->
                    <div class="btn-group btn-group-justified" data-toggle="buttons">
                        <label class="btn btn-default">
                            <input type="radio" name="activity" id="<?php //echo Yii::app()->createAbsoluteUrl("project/preview");  ?>">
                            Project (<?php //echo Project::getTotalProjectUnread();  ?>) </label>
                        <label class="btn btn-default">
                            <input type="radio" name="activity" id="<?php //echo Yii::app()->createAbsoluteUrl("issue/preview");  ?>">
                            Issue (<?php //echo Issue::getTotalIssueUnread();  ?>) </label>
                    </div>
                    <!-- notification content -->
                    <div class="ajax-notifications custom-scroll">
                        <div class="alert alert-transparent">
                            <h4>Click a button to show messages here</h4>
                            This blank page message helps protect your privacy, or you can show the first message here automatically.
                        </div>
                        <i class="fa fa-lock fa-4x fa-border"></i>
                    </div>
                    <!-- end notification content -->
                    <!-- footer: refresh area -->
                    <span> Last updated on: <?php echo date("F j, Y, g:i a"); ?>
                        <button type="button" data-loading-text="<i class='fa fa-refresh fa-spin'></i> Loading..." class="btn btn-xs btn-default pull-right">
                            <i class="fa fa-refresh"></i>
                        </button> 
                    </span>
                    <!-- end footer -->
                </div>
                <!-- END AJAX-DROPDOWN -->
            </div>
            <!-- #PROJECTS: projects dropdown -->
            <div class="project-context hidden-xs">
                <span class="label">QUICK ACTIONS:</span>
                <span class="project-selector dropdown-toggle" data-toggle="dropdown"><i class="fa fa-plus"></i> ADD</span>
                <!-- Suggestion: populate this list with fetch and push technique -->
                <ul class="dropdown-menu">
                    <li><?php echo CHtml::link('+ USER', array('user/create'), array('data-toggle' => 'modal', 'data-target' => '#newData')); ?></li>                  
                    <li class="divider"></li>
                    <li><a href="javascript:void(0);"><i class="fa fa-power-off"></i> CLEAR</a></li>
                </ul>
                <!-- end dropdown-menu-->
            </div>
            <!-- end projects dropdown -->
            <!-- #TOGGLE LAYOUT BUTTONS -->
            <!-- pulled right: nav area -->
            <div class="pull-right">
                <!-- collapse menu button -->
                <div id="hide-menu" class="btn-header pull-right">
                    <span> <a href="javascript:void(0);" data-action="toggleMenu" title="Collapse Menu"><i class="fa fa-reorder"></i></a> </span>
                </div>
                <!-- end collapse menu -->
                <!-- #MOBILE -->
                <!-- Top menu profile link : this shows only when top menu is active -->
                <ul id="mobile-profile-img" class="header-dropdown-list hidden-xs padding-5">
                    <li class="">
                        <a href="#" class="dropdown-toggle no-margin userdropdown" data-toggle="dropdown"> 
                            <img src="<?php echo Yii::app()->theme->baseUrl; ?>/img/avatars/sunny.png" alt="John Doe" class="online" />  
                        </a>
                        <ul class="dropdown-menu pull-right">
                            <li>
                                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"><i class="fa fa-cog"></i> Setting</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0"> <i class="fa fa-user"></i> <u>P</u>rofile</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="toggleShortcut"><i class="fa fa-arrow-down"></i> <u>S</u>hortcut</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);" class="padding-10 padding-top-0 padding-bottom-0" data-action="launchFullscreen"><i class="fa fa-arrows-alt"></i> Full <u>S</u>creen</a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="javascript:void(0);" class="padding-10 padding-top-5 padding-bottom-5" data-action="userLogout"><i class="fa fa-sign-out fa-lg"></i> <strong><u>L</u>ogout</strong></a>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!-- logout button -->
                <div id="logout" class="btn-header transparent pull-right">
                    <span> <?php echo CHtml::link('<i class="fa fa-sign-out"></i>', array('site/logout'), array('title' => 'Sign Out', 'data-action' => 'userLogout', 'data-logout-msg' => 'You can improve your security further after logging out by closing this opened browser')); ?> </span>
                </div>
                <!-- end logout button -->
                <!-- search mobile button (this is hidden till mobile view port) -->
                <div id="search-mobile" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0)" title="Search"><i class="fa fa-search"></i></a> </span>
                </div>
                <!-- end search mobile button -->
                <!-- #SEARCH -->
                <!-- input: search field -->
                <?php
                $form = $this->beginWidget('CActiveForm', array(
                    'id' => 'issue-search-form',
                    'enableAjaxValidation' => false,
                    'method' => 'post',
                    'action' => array("search"),
                    'htmlOptions' => array('class' => 'header-search pull-right'),
                ));
                ?>
                <input id="issueid" type="text" name="issueid" placeholder="Search...">
                <button type="submit">
                    <i class="fa fa-search"></i>
                </button>
                <a href="javascript:void(0);" id="cancel-search-js" title="Cancel Search"><i class="fa fa-times"></i></a>
                <?php $this->endWidget(); ?>
                <!-- end input: search field -->
                <!-- fullscreen button -->
                <div id="fullscreen" class="btn-header transparent pull-right">
                    <span> <a href="javascript:void(0);" data-action="launchFullscreen" title="Full Screen"><i class="fa fa-arrows-alt"></i></a> </span>
                </div>
                <!-- end fullscreen button -->
            </div>
            <!-- end pulled right: nav area -->
        </header>
        <!-- END HEADER -->
        <!-- #NAVIGATION -->
        <!-- Left panel : Navigation area -->
        <!-- Note: This width of the aside area can be adjusted through LESS variables -->
        <aside id="left-panel">
            <!-- User info -->
            <div class="login-info">
                <span> <!-- User image size is adjusted inside CSS, it should stay as is --> 
                    <a href="javascript:void(0);" id="show-shortcut" data-action="toggleShortcut">
                        <?php echo User::get_profile_picture(Yii::app()->user->id); ?>          
                        <span>
                            <?php echo User::get_full_name(Yii::app()->user->id); ?>
                        </span>
                        <i class="fa fa-angle-down"></i>
                    </a> 
                </span>
            </div>
            <!-- end user info -->
            <!-- NAVIGATION : This navigation is also responsive
            To make this navigation dynamic please make sure to link the node
            (the reference to the nav > ul) after page load. Or the navigation
            will not initialize.
            -->
            <nav>
                <?php Menu::get_menu(); ?>
            </nav>
            <span class="minifyme" data-action="minifyMenu"> <i class="fa fa-arrow-circle-left hit"></i> </span>
        </aside>
        <!-- END NAVIGATION -->
        <!-- #MAIN PANEL -->
        <div id="main" role="main">
            <!-- RIBBON -->
            <div id="ribbon">
                <span class="ribbon-button-alignment"> 
                    <span id="refresh" class="btn btn-ribbon" data-action="resetWidgets" data-title="refresh" rel="tooltip" data-placement="bottom" data-original-title="<i class='text-warning fa fa-warning'></i> Warning! This will reset all your widget settings." data-html="true" data-reset-msg="Would you like to RESET all your saved widgets and clear LocalStorage?"><i class="fa fa-refresh"></i></span> 
                </span>
                <!-- breadcrumb -->
                <ol class="breadcrumb">
                    <!-- This is auto generated -->
                    <?php
                    if (isset($this->breadcrumbs)):
                        ?>
                        <?php
                        $this->widget('zii.widgets.CBreadcrumbs', array(
                            'links' => $this->breadcrumbs,
                        ));
                        ?><!-- breadcrumbs -->
                    <?php endif ?>
                </ol>
                <!-- end breadcrumb -->
            </div>
            <!-- END RIBBON -->
            <!-- #MAIN CONTENT -->
            <div id="content">
                <?php echo $content; ?>
            </div>
            <!-- END #MAIN CONTENT -->
        </div>
        <!-- END #MAIN PANEL -->
        <!-- #PAGE FOOTER -->
        <div class="page-footer">
            <div class="row">
                <div class="col-xs-12 col-sm-6">
                    <span class="txt-color-white">Copyright &copy; <?php echo Yii::app()->name; ?> <?php echo date('Y'); ?>. Developed by <?php echo CHtml::link('Optimo Solution', 'http://www.optimosolution.com', array('target' => '_blank')); ?></span>
                </div>
                <div class="col-xs-6 col-sm-6 text-right hidden-xs">
                    <div class="txt-color-white inline-block">
                        <i class="txt-color-blueLight hidden-mobile">Last account activity <i class="fa fa-clock-o"></i> <strong><?php echo date("M j Y, g:i:s A"); ?></strong> </i>
                    </div>
                    <!-- end div-->
                </div>
                <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- END FOOTER -->
        <!-- #SHORTCUT AREA : With large tiles (activated via clicking user name tag)
                 Note: These tiles are completely responsive, you can add as many as you like -->
        <div id="shortcut">
            <ul>                                
                <li><?php echo CHtml::link('<span class="iconbox"> <i class="fa fa-user fa-4x"></i> <span>My Profile</span> </span>', array('/user/view', 'id' => Yii::app()->user->id), array('title' => 'My Profile', 'class' => 'jarvismetro-tile big-cubes selected bg-color-pinkDark')); ?></li>
                <li><?php echo CHtml::link('<span class="iconbox"> <i class="fa fa-sign-out fa-4x"></i> <span>Logout</span> </span>', array('/site/logout'), array('title' => 'Logout', 'data-action' => 'userLogout', 'data-logout-msg' => 'You can improve your security further after logging out by closing this opened browser', 'class' => 'jarvismetro-tile big-cubes bg-color-orangeDark')); ?></li>
            </ul>
        </div>
        <!-- END SHORTCUT AREA -->        
        <script>
            if (!window.jQuery.ui) {
                document.write('<script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/libs/jquery-ui-1.10.3.min.js"><\/script>');
            }
        </script>
        <!-- PACE LOADER - turn this on if you want ajax loading to show (caution: uses lots of memory on iDevices)
        <script data-pace-options='{ "restartOnRequestAfter": true }' src="js/plugin/pace/pace.min.js"></script>-->
        <!-- #PLUGINS -->
        <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
        <!-- IMPORTANT: APP CONFIG -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/app.config.js"></script>
        <!-- JS TOUCH : include this plugin for mobile drag / drop touch events-->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/plugin/jquery-touch/jquery.ui.touch-punch.min.js"></script> 
        <!-- BOOTSTRAP JS -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap/bootstrap.min.js"></script>
        <!-- CUSTOM NOTIFICATION -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/notification/SmartNotification.min.js"></script>
        <!-- JARVIS WIDGETS -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/smartwidgets/jarvis.widget.min.js"></script>
        <!-- EASY PIE CHARTS -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/plugin/easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
        <!-- SPARKLINES -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/plugin/sparkline/jquery.sparkline.min.js"></script>
        <!-- JQUERY VALIDATE -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/plugin/jquery-validate/jquery.validate.min.js"></script>
        <!-- JQUERY MASKED INPUT -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/plugin/masked-input/jquery.maskedinput.min.js"></script>
        <!-- JQUERY SELECT2 INPUT -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/plugin/select2/select2.min.js"></script>
        <!-- JQUERY UI + Bootstrap Slider -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/plugin/bootstrap-slider/bootstrap-slider.min.js"></script>
        <!-- browser msie issue fix -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/plugin/msie-fix/jquery.mb.browser.min.js"></script>
        <!-- FastClick: For mobile devices: you can disable this in app.js -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/plugin/fastclick/fastclick.min.js"></script>
        <!--[if IE 8]>
                <h1>Your browser is out of date, please update your browser by going to www.microsoft.com/download</h1>
        <![endif]-->
        <!-- Demo purpose only -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/demo.min.js"></script>
        <!-- MAIN APP JS FILE -->
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/app.min.js"></script>
        <!-- ENHANCEMENT PLUGINS : NOT A REQUIREMENT -->          
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.chained.js"></script>
        <script type="text/javascript">
            // DO NOT REMOVE : GLOBAL FUNCTIONS!
            pageSetUp();
        </script>
        <script type="text/javascript">
            // PAGE RELATED SCRIPTS
            $('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
            $('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').on('click', function (e) {
                var children = $(this).parent('li.parent_li').find(' > ul > li');
                if (children.is(':visible')) {
                    children.hide('fast');
                    $(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
                } else {
                    children.show('fast');
                    $(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
                }
                e.stopPropagation();
            });
        </script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.livequery.js"></script>   
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/plugin/ckeditor/ckeditor.js"></script>    
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/plugin/bootstrap-tags/bootstrap-tagsinput.min.js"></script>
        <script src="<?php echo Yii::app()->theme->baseUrl; ?>/js/mmscript.js"></script>
    </body>
</html>