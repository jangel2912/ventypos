<?php

f

// uncomment the following to define a path alias

// Yii::setPathOfAlias('local','path/to/local-folder');



// This is the main Web application configuration. Any writable

// CWebApplication properties can be configured here.

return array(

	'timeZone' => 'America/Bogota',

	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',

	'name'=>'Reencol',

         'theme' => 'ace',

         'language'=>'es',

    

    'controllerMap' => array(

		// ...

		'barcodegenerator' => array(

			'class' => 'ext.barcodegenerator.BarcodeGeneratorController',

		),

	),



	// preloading 'log' component

	'preload'=>array('log'),



	// autoloading model and component classes

	'import'=>array(

		'application.models.*',

		'application.components.*',

                'application.modules.user.models.*',

                'application.modules.user.components.*',

                 'application.modules.rights.*',

                 'application.modules.rights.components.*',

                  'application.extensions.*',

	),



	'modules'=>array(

		// uncomment the following to enable the Gii tool

		'gii'=>array(

			'class'=>'system.gii.GiiModule',

			'password'=>'gii',

			// If removed, Gii defaults to localhost only. Edit carefully to taste.

			'ipFilters'=>array('127.0.0.1','::1'),

                        'generatorPaths' => array('application.modules.gii')

		),

                   'user'=>array(

                'tableUsers' => 'users',

                'tableProfiles' => 'profiles',

                'tableProfileFields' => 'profiles_fields',

                     # encrypting method (php hash function)

                'hash' => 'md5',

 

                # send activation email

                'sendActivationMail' => true,

 

                # allow access for non-activated users

                'loginNotActiv' => false,

 

                # activate user on registration (only sendActivationMail = false)

                'activeAfterRegister' => false,

 

                # automatically login from registration

                'autoLogin' => true,

 

                # registration path

                'registrationUrl' => array('/user/registration'),

 

                # recovery password path

                'recoveryUrl' => array('/user/recovery'),

 

                # login form path

                'loginUrl' => array('/user/login'),

 

                # page after login

                'returnUrl' => array('/user/profile'),

 

                # page after logout

                'returnLogoutUrl' => array('/user/login'),

        ),

 

        //Modules Rights

   'rights'=>array(

 

                'superuserName'=>'Admin', // Name of the role with super user privileges. 

               'authenticatedName'=>'Authenticated',  // Name of the authenticated user role. 

               'userIdColumn'=>'id', // Name of the user id column in the database. 

               'userNameColumn'=>'username',  // Name of the user name column in the database. 

               'enableBizRule'=>true,  // Whether to enable authorization item business rules. 

               'enableBizRuleData'=>true,   // Whether to enable data for business rules. 

               'displayDescription'=>true,  // Whether to use item description instead of name. 

               'flashSuccessKey'=>'RightsSuccess', // Key to use for setting success flash messages. 

               'flashErrorKey'=>'RightsError', // Key to use for setting error flash messages. 

 

               'baseUrl'=>'/rights', // Base URL for Rights. Change if module is nested. 

               'layout'=>'themes/ace/views/layouts/main.php',//'rights.views.layouts.main',  // Layout to use for displaying Rights. 

              'appLayout'=>'themes/ace/views/layouts/main.php' , // Application layout. 

               //'cssFile'=>'rights.css', // Style sheet file to use for Rights.

      

               'install'=>false,  // Whether to enable installer. 

               'debug'=>false, 

        ),

	),



	// application components

	'components'=>array(

		'user'=>array(

                'class'=>'RWebUser',

                // enable cookie-based authentication

                'allowAutoLogin'=>true,

                'loginUrl'=>array('/user/login'),

        ),

        'authManager'=>array(

                'class'=>'RDbAuthManager', 

            'connectionID'=>'db', 

            'defaultRoles'=>array('Authenticated', 'Guest'), 

            'assignmentTable'=>'authassignment', 

            'itemChildTable'=>'authitemchild', 

            'itemTable'=>'authitem',

            'rightsTable' => 'rights',

        ),

		// uncomment the following to enable URLs in path-format

		/*

		'urlManager'=>array(

			'urlFormat'=>'path',

			'rules'=>array(

				'<controller:\w+>/<id:\d+>'=>'<controller>/view',

				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',

				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',

			),

		),

		*/

		

		// uncomment the following to use a MySQL database

		

		'db'=>array(

			'connectionString' => 'mysql:host=200.93.191.182;dbname=db_reencol',

			'emulatePrepare' => true,

			'username' => 'userweb',

			'password' => 'passweb',

			'charset' => 'utf8',

		),

            

            

            'ePdf' => array(



			'class'			=> 'ext.yii-pdf.EYiiPdf',



			'params'		=> array(



				'mpdf'	   => array(



					'librarySourcePath' => 'application.vendor.mpdf.*',



					'constants'			=> array(



						'_MPDF_TEMP_PATH' => Yii::getPathOfAlias('application.runtime'),



					),



					'class'=>'mpdf', // the literal class filename to be loaded from the vendors folder.



					'defaultParams'	  => array( // More info: http://mpdf1.com/manual/index.php?tid=184



						'mode'				=> '', //  This parameter specifies the mode of the new document.



						'format'			=> 'A4', // format A4, A5, ...



						'default_font_size' => 0, // Sets the default document font size in points (pt)



						'default_font'		=> '', // Sets the default font-family for the new document.



						'mgl'				=> 5, // margin_left. Sets the page margins for the new document.



						'mgr'				=> 5, // margin_right



						'mgt'				=> 5, // margin_top



						'mgb'				=> 5, // margin_bottom



						'mgh'				=> 9, // margin_header



						'mgf'				=> 9, // margin_footer



						'orientation'		=> 'P', // landscape or portrait orientation



					)



				)

                                   ,'HTML2PDF' => array(

					'librarySourcePath' => 'application.vendor.html2pdf.*',

					'classFile'			=> 'html2pdf.class.php', // For adding to Yii::$classMap

					'defaultParams'	  => array( // More info: http://wiki.spipu.net/doku.php?id=html2pdf:en:v4:accueil

						'orientation' => 'P', // landscape or portrait orientation

						'format'	  => 'A4', // format A4, A5, ...

						'language'	  => 'en', // language: fr, en, it ...

						'unicode'	  => true, // TRUE means clustering the input text IS unicode (default = true)

						'encoding'	  => 'UTF-8', // charset encoding; Default is UTF-8

						'marges'	  => array(5, 5, 5, 8), // margins by default, in order (left, top, right, bottom)

					)

				)



			),



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

	'params'=>array(

		// this is used in contact page

		'adminEmail'=>'webmaster@example.com',

	),

);