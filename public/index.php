<?php
try
{
    set_time_limit(3600);
    $contentHeader = ob_get_contents();
    header("Content-Type: text/html; charset=utf-8");
    header("cache-control: must-revalidate");
    $offset = 60 * 60;
    $expire = "expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
    header($expire);
    header('Vary: Accept-Encoding');
    header("Access-Control-Allow-Origin: *");
    error_reporting(E_ALL|E_STRICT);
 	ini_set('display_errors', '1');
	date_default_timezone_set('Asia/Bangkok');
 	mb_internal_encoding("UTF-8");
    
    chdir(dirname(__DIR__));
    // Decline static file requests back to the PHP built-in webserver
    if (php_sapi_name() === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
        return false;
    } 
    
    if (strpos($_SERVER['HTTP_HOST'], 'www') === false && $_SERVER['HTTP_HOST'] =='prizzit.com')
    { echo 'rrr';
       header("Location:"."https://www.".$_SERVER['HTTP_HOST']. $_SERVER['REQUEST_URI']);
       exit();   
    }     

    if($_SERVER['REQUEST_URI']=='/'){  
        header("Location:"."https://".$_SERVER['HTTP_HOST'].'/th/');
        exit(); 
    }
    
    // Setup autoloading
    require 'init_autoloader.php';
   
    // Run the application!
    Zend\Mvc\Application::init(require 'config/application.config.php')->run();
}
catch (\Exception $e)
{
    print_r($e);
}