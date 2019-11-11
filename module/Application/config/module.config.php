<?php

return array( 
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                    ),
                ),
            ),
            
            'index' => array( 
                'type' => 'Segment',
                'options' => array( 
                    'route'    => '/[:lang[/][:action[/][:id[/]]]]',
                    'constraints' => array(
                        'lang'   => '[a-zA-Z]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        //'id' => ''
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action' => 'index',
                        'id' => '',
                        'lang' => 'en',
                    ),
                ),
            ), 
             
            'admin' => array(
                'type' => 'Segment',
                'options' => array( 
                    'route'    => '/admin[/][:action[/][:id[/]]]',
                    'constraints' => array( 
                        //'lang'   => '[a-zA-Z]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9_-]*[a-zA-Z0-9ก-ฮ]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Admin', 
                        'action' => 'index',   
                        //'lang' => 'th',
                        'id' => '',
                    ),
                ),
            ),
            
            /*
            'xxx' => array(
                'type' => 'Segment',
                'options' => array(
                    'route'    => '/engine/[:lang/[:action[/][:id/]]]',
                    'constraints' => array(
                        'lang'   => '[a-zA-Z]*',
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9_-]*[a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Xxx',
                        'action' => 'index',
                        'id' => '',
                        'lang' => 'th',
                    ),
                ),
            ),*/
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
            'viewhelpermanager' =>'ViewHelperManager' 
            
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array( 
            //add controller
            'Application\Controller\Index' => 'Application\Controller\IndexController',
            'Application\Controller\Admin' => 'Application\Controller\AdminController', 
            //'Application\Controller\Xxx' => 'Application\Controller\XxxController',
        ),
    ),
     
    'view_manager' => array(
        'base_path' => '/',
        'doctype' => 'HTML5',
        'template_map' => array( 
            #index
            'application/index/index' => __DIR__ . '/../view/index/index.phtml',
            'application/index/login' => __DIR__ . '/../view/index/index.phtml',
            'application/index/register' => __DIR__ . '/../view/index/index.phtml',
            'application/index/forgotpass' => __DIR__ . '/../view/index/index.phtml',
            'application/index/create' => __DIR__ . '/../view/index/index.phtml',
            'application/index/edit' => __DIR__ . '/../view/index/index.phtml',
            'application/index/setting' => __DIR__ . '/../view/index/index.phtml',
            'application/index/view' => __DIR__ . '/../view/index/index.phtml', 
            'application/index/invite' => __DIR__ . '/../view/index/index.phtml',
            'application/index/invitelist' => __DIR__ . '/../view/index/index.phtml', 
            'application/index/particpates' => __DIR__ . '/../view/index/index.phtml', 
            'application/index/contactinfo' => __DIR__ . '/../view/index/index.phtml',
            'application/index/transaction' => __DIR__ . '/../view/index/index.phtml',
            'application/index/thankyou' => __DIR__ . '/../view/index/index.phtml', 
            'application/index/list' => __DIR__ . '/../view/index/index.phtml', 
            'application/index/mypot' => __DIR__ . '/../view/index/index.phtml',
            'application/index/profile' => __DIR__ . '/../view/index/index.phtml', 
            'application/index/transferfund' => __DIR__ . '/../view/index/index.phtml', 
            'application/index/managepot' => __DIR__ . '/../view/index/index.phtml', 
            'application/index/cronjob' => __DIR__ . '/../view/index/index.phtml', 
            'application/index/exportxls' => __DIR__ . '/../view/index/index.phtml',
            'application/index/projects' => __DIR__ . '/../view/index/index.phtml',
            'application/index/how-to-work' => __DIR__ . '/../view/index/index.phtml',
            'application/index/terms' => __DIR__ . '/../view/index/index.phtml',
            'application/index/privacy' => __DIR__ . '/../view/index/index.phtml',
            'application/index/about' => __DIR__ . '/../view/index/index.phtml',
            'application/index/contact' => __DIR__ . '/../view/index/index.phtml',
            'application/index/category' => __DIR__ . '/../view/index/index.phtml',
            'application/index/category-list' => __DIR__ . '/../view/index/index.phtml',
            'application/index/uploads' => __DIR__ . '/../view/index/index.phtml',
            'application/index/test' => __DIR__ . '/../view/index/index.phtml',
            'application/index/loginline' => __DIR__ . '/../view/index/index.phtml',
                   
            #layout
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
			#404
			'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
            
            #admin
            'application/admin/index' => __DIR__ . '/../view/admin/main.phtml', 
            'application/admin/login' => __DIR__ . '/../view/admin/main.phtml',
            'application/admin/admin' => __DIR__ . '/../view/admin/main.phtml', 
            'application/admin/category' => __DIR__ . '/../view/admin/main.phtml',
            'application/admin/news' => __DIR__ . '/../view/admin/main.phtml',
            'application/admin/delimg' => __DIR__ . '/../view/admin/main.phtml',
            'application/admin/banners' => __DIR__ . '/../view/admin/main.phtml',
            //'application/admin/boypdf' => __DIR__ . '/../view/admin/main.phtml',
            'application/admin/subcategory' => __DIR__ . '/../view/admin/main.phtml', 
              
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        /*
        'strategies' => array(
            'ViewJsonStrategy', // register JSON renderer strategy
            'ViewFeedStrategy', // register Feed renderer strategy
        ),*/
    ),
    
    
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    //DB 
    //'Zend\Db', 
    'Db' => array( 
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=prizzit;host=prizzit.cunwk2xmw0nf.ap-southeast-1.rds.amazonaws.com',   
        'driver_options' => array(    
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
        'username' => 'prizzit',    
        'password' => 'prizzit69',     
    ),
    'service_manager' => array( 
        'factories' => array(
            'translator' => 'Zend\\I18n\\Translator\\TranslatorServiceFactory',
            'Zend\\Db\\Adapter\\Adapter' => 'Zend\\Db\\Adapter\\AdapterServiceFactory',
        ),
        'abstract_factories' => [
        \Zend\Db\Adapter\AdapterAbstractServiceFactory::class,
        ],
    ),
    'languages' => array(    
        '1' =>['code'=>'en','name'=>'English','label'=>'English'],   
        '2' =>['code'=>'th','name'=>'Thai','label'=>'ภาษาไทย'],
    ), 
    'bootstrap' => array(     
        'fontend' =>include 'bootstrap.php',    
        'backend' =>include 'bootstrap_admin.php',
    ),
    'amazon_s3' => [   
                    //'s3URL'=>'https://s3.eu-central-1.amazonaws.com/starter-kit-rockstar',
                    //'s3URL'=>'https://files.renovly.com',
                    'urlFile'=>'https://s3-ap-southeast-1.amazonaws.com/longkun-prizzit',
                    'bucket'=>'longkun-prizzit',    
                    'config'=>[    
                                'version' => 'latest', 
                                'region' => 'ap-southeast-1',
                                'credentials' => [
                                        'key' => 'AKIAJ54LBTADWKGQQGMQ',
                                        'secret' => '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'
                                    ]
                               ]
                    ],
    'email_config' => array(      
        'smtp_config'=>array( 
            'name'              => 'LUNGKHUN',
            'host'              => 'smtp.sendgrid.net',
            'port'              => 587,
            'connection_class'  => 'login',
            'connection_config' => array(  
                'username' => 'boygpsn', 
                'password' => '123qwe123',  
                'ssl'      => 'tls',  
            ),   
        ),      
        'email_contact'=>'boy@gpsn.co.th',
        'cc_email'=>'',  
        'bcc_email'=>''
    ),  
    'omise_config' => array(      
        'public_key' => 'pkey_test_548dlesk5fxpbb2ii5p', 
        'secret_key' => 'skey_test_53w1xpczd1sah0jct8d',
        'api_url' => 'https://api.omise.co/'
    ),    
    'line_config' => array(         
        'client_id' => '1568207616',  
        'client_secret' => '4c690a93c8550742828bf6ce6b0daafb', 
        'callback_url' => 'https://'.$_SERVER['HTTP_HOST'].'/th/loginline/?act=callback'
    ),     
     
    'service_charge'=>7, //service charge 7%
    'transfer_fee'=>30, //Transfer fee 30 thb.
    
    //https://www.omise.co/supported-banks
    'banks'=>array( 
        //Thailand
        'th'=>array( 
            'scb'=>'Siam Commercial Bank',
            'ktb'=>'Krungthai Bank',
            'kbank'=>'Kasikornbank',
            'bbl'=>'Bangkok Bank',
            'bay'=>'Bank of Ayudhya (Krungsri)',
            'tmb'=>'TMB Bank',
            'lhb'=>'Land and Houses Bank',
            'kk'=>'Kiatnakin Bank',
            'cimb'=>'CIMB Thai Bank',
            'citi'=>'Citibank',
            'uob'=>'United Overseas Bank (Thai)',
        ),  
        //Singapore
        'sg'=>array(
            '7047'=>'Bangkok Bank',
            '7056'=>'Bank Negara Indonesia',
            '7065'=>'Bank of America',
            '7083'=>'Bank of China',
            '7092'=>'Bank of East Asia',
            '7108'=>'Bank of India',
            '7126'=>'The Bank of Tokyo-Mitsubishi UFJ',
            '7135'=>'Crédit Agricole CIB',
            '7144'=>'Standard Chartered',
            '7153'=>'JPMorgan Chase',
            '7171'=>'DBS Bank',
            '7199'=>'Far Eastern Bank Ltd',
            '7214'=>'Citibank NA',
            '7232'=>'HSBC',
            '7241'=>'Indian Bank',
            '7250'=>'Indian Overseas Bank'
        )
    ) 
);  