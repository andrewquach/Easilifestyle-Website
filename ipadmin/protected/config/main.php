<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
Yii::app()->setTimeZone('Asia/Singapore');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'EasiLifestyle Web Admin',

	// preloading 'log' component
	//'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.helpers.*',
		'application.extensions.CAdvancedArBehavior',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
		/*
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		*/
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'gii'=>'gii',
            	'gii/<controller:\w+>'=>'gii/<controller>',
            	'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
				
				'service/<action:\w+>/<id:\d+>/<text:\w+>/<start:\d+>/<limit:\d+>'=>'service/<action>',
				'service/<action:\w+>/<id:\d+>/<start:\d+>/<limit:\d+>'=>'service/<action>',
				'service/<action:\w+>/<start:\d+>/<limit:\d+>'=>'service/<action>',
				'service/<action:\w+>/<id:\d+>/<udid:\w+>'=>'service/<action>',
			),
			'caseSensitive'=>true,
		),
		
		'localtime'=>array(
       		'class'=>'LocalTime',
        ),
		
		// uncomment the following to use a MySQL database
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=ipapp',
			'emulatePrepare' => true,
			'username' => 'ipapp',
			'password' => 'HR5Lts748nVARYL9',
			'charset' => 'utf8',
			'tablePrefix' => 'tbl',
			'schemaCachingDuration'=>3600,
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/error',
        ),
        
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				
				array(
					'class'=>'CWebLogRoute',
				),
				
			),
		),
		
		'format'=>array(
			'datetimeFormat' => 'd/m/Y h:i:s a',
			'dateFormat' => 'd/m/Y',
		),
		
		'image'=>array(
			'class'=>'application.extensions.image.CImageComponent',
			// GD or ImageMagick
			'driver'=>'GD',
			// ImageMagick setup path
			'params'=>array('directory'=>'/opt/local/bin'),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'gmapapikey'=>'ABQIAAAA42uOg4Oh0Cp9di6sAadoGRT1e1myAr8O7yFrxFAfRO8sjxBQ6xQqTJQMwvgz0cDM-dHUaLXptYUmHA',
		'siteurl'=>'http://www.easilifestyle.com/ipadmin',
		'adminEmail'=>'j251282@gmail.com',
		'thumbwidth'=>100,
		'thumbheight'=>100,
		'promotionwidth'=>200,
		'promotionheight'=>200,
		'productwidth'=>250,
		'productheight'=>250,
		'JSDateFormat'=>'dd/mm/yy',
		'ckeditor'=>array(
			array("name"=>'document', "items"=>array('Source' )),
			array("name"=>'clipboard', "items"=>array('Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' )),
			'/',
			array("name"=>'basicstyles', "items"=>array('Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ) ),
			array("name"=>'paragraph', "items"=>array('NumberedList','BulletedList','-','Outdent','Indent','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ) ),
			array("name"=>'links', "items"=>array('Link','Unlink') ),
			array("name"=>'insert', "items"=>array('Image','Table','HorizontalRule' ) ),
			'/',
			array("name"=>'styles', "items"=>array('Styles','Format','Font','FontSize' ) ),
			array("name"=>'colors', "items"=>array('TextColor','BGColor' )),
		)
	),
);