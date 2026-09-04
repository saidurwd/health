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
    </head>
    <body class="">
        <div id="content">
            <?php echo $content; ?>
        </div>
        <!-- END SHORTCUT AREA -->
        <!--================================================== -->
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

        <script type="text/javascript">
            // DO NOT REMOVE : GLOBAL FUNCTIONS!
            pageSetUp();
        </script>
    </body>
</html>