<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Health Program Software',
    'defaultController' => 'dashboard',
    //Default theme
    'theme' => 'classic',
    //Default time zone
    'timeZone' => 'Asia/Dhaka',
    //Default source language
    'sourceLanguage' => 'en_us',
    // preloading 'log' component
    'preload' => array('log'),
    // autoloading model and component classes
    'import' => array(
        'application.models.*',
        'application.helpers.*',
        'application.components.*',
    ),
    'modules' => array(
        // uncomment the following to enable the Gii tool
        'gii' => array(
            'class' => 'system.gii.GiiModule',
            'password' => 'admin',
            // If removed, Gii defaults to localhost only. Edit carefully to taste.
            'ipFilters' => array('127.0.0.1', '::1'),
        ),
    ),
    // application components
    'components' => array(
        //        'clientScript' => array(
        //            'packages' => array(
        //                'jquery' => array(
        //                    'baseUrl' => '//ajax.googleapis.com/ajax/libs/jquery/2.0.3/',
        //                    'js' => array('jquery.min.js'),
        //                    'coreScriptPosition' => CClientScript::POS_HEAD,
        //                ),
        //                'jquery.ui' => array(
        //                    'baseUrl' => '//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/',
        //                    'js' => array('jquery-ui.min.js'),
        //                    'depends' => array('jquery'),
        //                    'coreScriptPosition' => CClientScript::POS_BEGIN,
        //                ),
        //            ),
        //        ),

        'image' => array(
            'class' => 'application.extensions.image.CImageComponent',
            // GD or ImageMagick
            'driver' => 'GD',
            // ImageMagick setup path
            'params' => array('directory' => '/opt/local/bin'),
        ),
        'user' => array(
            // enable cookie-based authentication
            'allowAutoLogin' => true,
            'loginUrl' => array('/site/login'),
        ),
        'session' => array(
            'class' => 'CDbHttpSession',
            'connectionID' => 'db',
            'sessionName' => 'PROGETTOUOMO',
            'autoCreateSessionTable' => false,
            'sessionTableName' => 'os_yiisession',
            'cookieMode' => 'only',
            'timeout' => 3600,
        ),
        // uncomment the following to enable URLs in path-format
        /*'urlManager' => array(
            'urlFormat' => 'path',
            'showScriptName' => false,
            'urlSuffix' => '.html',
            'rules' => array(
                'defaultController' => 'login',
                '<action>' => 'site/<action>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ),
        ),*/
        // database settings are configured in database.php
        'db' => require(dirname(__FILE__) . '/database.php'),
        'cache' => array(
            'class' => 'CDbCache',
            'connectionID' => 'db',
            'cacheTableName' => 'os_cache',
        ),
        'errorHandler' => array(
            // use 'site/error' action to display errors
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            // uncomment the following to show log messages on web pages
            /*
              array(
              'class'=>'CWebLogRoute',
              ),
             */
            ),
        ),
    ),
    // application-level parameters that can be accessed
    // using Yii::app()->params['paramName']
    'params' => array(
        // this is used in contact page
        'topTag' => 'Progetto Uomo',
        'adminName' => 'Rishilpi International Onlus',
        'bottomTag' => 'Rishilpi Health Program',
        'adminAddress' => 'Gopinathpur, Binerpota, Post box 8, Satkhira 9400. Mobile: +880 1715 608768 (Adult), +880 1715 608 793 (Child)',
        'tagLine' => 'Rishilpi International is a non government, humanitarian organization working for untouchable and outcaste community.<br />Rishilpi International has been registered as NGO-AB. The Registration Number of RISHILPI is 215, dated 24 february 1987.<br />The Registration Authority is the NGO Affairs Bureau of the Government of Bangladesh.',
        'physician_visit_time' => '9:00 AM to 1:30 PM (Friday and Government holidays are closed)',
        'physician_visit_time_2' => '9:00 AM to 4:00 PM (Friday and Government holidays are closed)',
        'print_note' => 'Note: This is Auto Generated Document. No Signature is Required. Generated on ' . date('l jS \of F Y h:i:s A') . '.',
        'RATEMETHODE' => 'LIFO', //ACTUAL, LIFO, FIFO, AVERAGE
        'adminEmail' => 'info@domain.com',
        'noreply' => 'noreply@domain.com',
        'rishilpiEmail' => 'rishilpi.pm-health@rishilpibd.org',
        'AdultChildMobile' => 'Adult Mobile: +880 1715 608786. Child Mobile: +880 1715 608 793',
        'TherapyUnit' => 'Physiotherapy/Occupational Therapy Unit',
        'discountMedicine' => 10,
        'discountService' => 10,
        'pageSize' => 25,
        'pageSize10' => 10,
        'pageSize20' => 20,
        'pageSize30' => 30,
        'pageSize40' => 40,
        'pageSize50' => 50,
        'pageSize100' => 100,
        'pageSize500' => 500,
        'pageSize1000' => 1000,
    ),
);
