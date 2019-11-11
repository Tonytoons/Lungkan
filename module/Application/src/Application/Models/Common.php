<?php
namespace Application\Models;
use Zend\Json\Json;
use Zend\Cache\StorageFactory;
use Zend\Cache\Storage\Adapter\Memcached;
use Zend\Cache\Storage\StorageInterface;
#mail#
 
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
##AWS##

//require 'vendor/aws/aws-autoloader.php';
//use Aws\Ses\SesClient;
/*--s3--*/
/*
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;  
*/ 
use Application\Models\Upload;

class Common
{ 
    private $ip;
    private $host;
    private $config;
    private $cacheTime;
    private $now;
    //protected $cacheTime;  
    
    function __construct() 
    { 
        $this->cacheTime = 360;
        $this->now = date('Y-m-d H:i');
        $this->ip = '';
        if (getenv('HTTP_CLIENT_IP'))
        {
            $this->ip = getenv('HTTP_CLIENT_IP');
        }
        else if(getenv('HTTP_X_FORWARDED_FOR'))
        {
            $this->ip = getenv('HTTP_X_FORWARDED_FOR');
        }
        else if(getenv('HTTP_X_FORWARDED'))
        {
            $this->ip = getenv('HTTP_X_FORWARDED');
        }
        else if(getenv('HTTP_FORWARDED_FOR'))
        {
            $this->ip = getenv('HTTP_FORWARDED_FOR');
        }
        else if(getenv('HTTP_FORWARDED'))
        {
            $this->ip = getenv('HTTP_FORWARDED');
        }
        else if(getenv('REMOTE_ADDR'))
        {
            $this->ip = getenv('REMOTE_ADDR');
        }
        else
        {
            $this->ip = 'UNKNOWN';
        }
        $this->config = include __DIR__ . '../../../../config/module.config.php';
        $this->host  = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'];
        $this->config = include __DIR__ . '../../../../config/module.config.php';
        $this->urlFile = $this->config['amazon_s3']['urlFile'].'/'.$this->up_forder;
    } 
    
    
    function dateFormat($strDate='')
	{
		if($this->lang=='th'){
    		$strYear = date("Y",strtotime($strDate))+543;
    		$strMonth= date("n",strtotime($strDate));
    		$strDay= date("j",strtotime($strDate));
    		$strHour= date("H",strtotime($strDate));
    		$strMinute= date("i",strtotime($strDate));
    		$strSeconds= date("s",strtotime($strDate));
    		$strMonthCut = Array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
    		$strMonthThai=$strMonthCut[$strMonth];
    		return "$strDay $strMonthThai $strYear"; 
		}else{
		    return date("d M Y", strtotime($strDate));
		}
	}
	
	function slugify($name){ 
        //return preg_replace('/[^a-zA-Z0-9&-]/', '-', trim($name));
        $slug = preg_replace('@[\s!:;_\?=\\\+\*/%&#]+@', '-', $name);  
          //this will replace all non alphanumeric char with '-'
    	$slug = mb_strtolower($slug, 'UTF-8');
    	      //convert string to lowercase
    	$slug = str_replace("'", '-', trim($slug, '-'));
    	return $slug;      
    }
    
    
    function day_diff($date1, $date2){
        $date1=date_create($date1);
        $date2=date_create($date2);
        $diff=date_diff($date1,$date2);
        return $diff; 
    }
    
    
    function outputCSV($data, $file_name='file.csv') {
       # output headers so that the file is downloaded rather than displayed
        header("Content-Type: text/csv");
        header("Content-Disposition: attachment; filename=$file_name");
        # Disable caching - HTTP 1.1
        header("Cache-Control: no-cache, no-store, must-revalidate");
        # Disable caching - HTTP 1.0
        header("Pragma: no-cache");
        # Disable caching - Proxies
        header("Expires: 0");
    
        # Start the ouput
        $output = fopen("php://output", "w");
        
         # Then loop through the rows
        foreach ($data as $row) {
            # Add the rows to the body
            fputcsv($output, $row); // here you can change delimiter/enclosure
        } 
        # Close the stream off
        fclose($output);
    }  
    
    
    
    function sendMail($subject, $fromName, $fromEmail, $toName, $toEmail, $body, $bccName='', $bccEmail='')
    {
        try 
        {
            $message = new Message();
            $html = new MimePart($body);
            $html->type = "text/html";
            
            $body = new MimeMessage();
            $body->setParts(array($html));
            
            $message = new Message(); 
            $message->setBody($body);
            $message->addFrom($fromEmail, $fromName);
            $message->setSubject($subject);
            
            if(is_array($toEmail)){  
                foreach($toEmail as $k=>$val){ 
                    //echo trim($val)."<br>"; 
                    $message->addTo(trim($val), $toName); 
                } 
                //exit;
            }else{
                $message->addTo($toEmail, $toName);
                /*
                $message->addTo($toEmail, $toName)
                    ->addFrom($fromEmail, $fromName)
                    //->addBcc("contact@wezenit.com")
                    ->setSubject($subject);
                */
            } 
             
            if(!empty($this->config['email_config']['smtp_config']['bcc_email'])){   
                $message->addBcc($this->config['email_config']['smtp_config']['bcc_email']);
            }
            
            // Setup SMTP transport using LOGIN authentication
            $transport = new SmtpTransport();
            
            $options   = new SmtpOptions($this->config['email_config']['smtp_config']);
            
            $transport->setOptions($options);
            $transport->send($message);
            
            return 200;
        } 
        catch (\Exception $e) 
        {  
            return htmlentities($e->getMessage());         
        }
    }
    
################################################################################

    public function UploadS3($FILES,  $folder='general', $resize=1200){ 
        $Config = $this->config['amazon_s3'];  
        $filename = ''; 
        // $s3 = new S3Client($Config['config']);     
        //// Upload an object to Amazon S3   
        //$bucket = $Config['bucket'];//'starter-kit-rockstar';    
        try   
        {   
            $filename = explode(".", $FILES["name"]);   
            $filenameext = strtolower(pathinfo( $FILES['name'] , PATHINFO_EXTENSION));    
            $filename = md5(time().uniqid());     
            $SourceFile = $FILES['tmp_name']; 
            $pathUpload = 'public/img/'.$folder;  
            if(!empty($FILES["name"])){ 
        		$upload = new Upload($FILES); 
        		if ($upload->uploaded) {      
        		   $upload->file_new_name_body   = $filename; 
        		   $upload->dir_auto_create 	 = true;
        		   $upload->dir_auto_chmod		 = true;
        		   $upload->file_overwrite		 = true;    
        		   $upload->file_new_name_ext	 = $filenameext;   
        		   $upload->image_resize         = true; 
        		   $upload->image_x              = $resize;     
        		   $upload->image_ratio_y        = true;     
        		   $upload->process($pathUpload); 
        		   if ($upload->processed) {       
        		      $upload->clean();       
        		      $SourceFile = $pathUpload."/".$filename."." . $filenameext;
        		   }
        		}     
        	}       
              
        } catch (S3Exception $e) {    
            // Catch an S3 specific exception.
            echo "<pre>";
            echo $e->getMessage();
            echo "</pre>";
            exit;    
        } 
        return $filename."." .$filenameext;  
    }
    
    function UploadS3Base64($data_img,  $folder='general')
    {
        try   
        {
            if (!preg_match('/data:([^;]*);base64,(.*)/', $data_img, $matches)) {
                die("error");
            } 
            
            $content = str_replace('data:image/', '', $matches[0]);
            $content = str_replace('data:application/', '', $content);
            $content = explode(";", $content);
            //$image_base64 = base64_decode($content[1]); 
            $extension = $content[0];   
            $fname = md5(time().uniqid());    
            $img_name = $fname.'.'.$extension;    
            $filepath = 'public/img/'.$folder.'/'.$img_name; 
             
            $image_parts = explode(";base64,", $data_img);
            $image_base64 = base64_decode($image_parts[1]);
            file_put_contents($filepath, $image_base64);  
            return($img_name);      
            
            /*
            $content = str_replace('data:image/', '', $matches[0]);
            $content = str_replace('data:application/', '', $content);
            $content = explode(";", $content);
            $content = $content[0];
            $pname = $this->id.gmdate('YmdHis').rand(0000, 9999);
            $img_name = $pname.'.'.$content;
            $filenameext = $content;
            if($img_name)
            {
                $s3 = new S3Client($this->config['amazon_s3']['config']);    
                $bucket = $this->config['amazon_s3']['bucket'];
                $result = $s3->putObject(array(
                    'Bucket' => $bucket, 
                    'Key' => $fd.'/'.$img_name,  
                    'ACL' => 'public-read',     
                    'SourceFile' => $contract_img,    
                    'Expires'=> (string)(1000+(int)date("Y")),                       
                    'ContentType'=>'image/'.$filenameext,      
                )); 
            }
            return($img_name);
            */
        } catch (S3Exception $e) {    
            // Catch an S3 specific exception.
            echo "<pre>";
            echo $e->getMessage();
            echo "</pre>";
            exit;    
        } 
    }
    
    
    /* 
    public function UploadS3($FILES, $folder='blog', $resize=480){ 
        $Config = $this->config['amazon_s3']; 
        $filename = '';  
        $s3 = new S3Client($Config['config']);     
        // Upload an object to Amazon S3 
        $bucket = $Config['bucket'];//'starter-kit-rockstar';    
        try   
        {  
            $filename = explode(".", $FILES["name"]);  
            $filenameext = strtolower($filename[count($filename)-1]);
            $filename = 'img_' . time();
            if($folder=='contract') $filename = 'file_' . time();    
            $SourceFile = $FILES['tmp_name']; 
            $pathUpload = 'public/temp'; 
            if(!empty($FILES["name"])){ 
        		$upload = new Upload($FILES); 
        		if ($upload->uploaded) {      
        		   $upload->file_new_name_body   = $filename; 
        		   $upload->dir_auto_create 	 = true;
        		   $upload->dir_auto_chmod		 = true;
        		   $upload->file_overwrite		 = true;    
        		   $upload->file_new_name_ext	 = $filenameext;   
        		   $upload->image_resize         = true;
        		   $upload->image_x              = $resize;   
        		   $upload->image_ratio_y        = true;     
        		   $upload->process($pathUpload); 
        		   if ($upload->processed) {      
        		      $upload->clean();       
        		      $SourceFile = $pathUpload."/".$filename."." . $filenameext;
        		   }else{ 
				     //print_r($upload->error);
				     //exit(0);     
				   } 
        		}     
        	}       
            
            $result = $s3->putObject(array(
                'Bucket' => $bucket, 
                'Key' => $folder.'/'.$filename."." .$filenameext,  
                'ACL' => 'public-read',     
                'SourceFile' => $SourceFile,    
                //'CacheControl'=>'max-age=3600',         
                'Expires'=> (string)(1000+(int)date("Y")),                       
                'ContentType'=>'image/'.$filenameext,      
            ));       
            @unlink($SourceFile);     
        } catch (S3Exception $e) {    
            // Catch an S3 specific exception.
            echo "<pre>";
            echo $e->getMessage();
            echo "</pre>";
            exit;    
        } 
        return $filename."." .$filenameext;  
    }
     */
     
    public function DeleteS3($keyname=''){ 
        
        @unlink('public/img/'.$keyname);     
         
        /*
        if(!empty($keyname)){
            $s3 = new S3Client($this->config['amazon_s3']['config']);   
            // Upload an object to Amazon S3 
            $bucket = $Config['bucket'];//'starter-kit-rockstar';
            
            $result = $s3->deleteObject(array( 
                'Bucket' => $bucket,
                'Key'    => $keyname
            ));
        } 
        */
    } 
    
    
    function maMemCache($time, $namespace)
    {
    	$cache = StorageFactory::factory([
										    'adapter' => [
										        'name' => 'filesystem',
										        'options' => [
										            'namespace' => $namespace,
										            'ttl' => $time,
										        ],
										    ],
										    'plugins' => [
										        // Don't throw exceptions on cache errors
										        'exception_handler' => [
										            'throw_exceptions' => true
										        ],
										        'Serializer',
										    ],
										]);
		return($cache);
	} 
	
	
	function sendEmail($subject, $fromName, $fromEmail, $toName, $toEmail, $body, $bccName='', $bccEmail='')
	{
	    /*
	    $curl = curl_init();
        $name = $_POST['name']; 
        $email = $_POST['email']; 
        $subject = $_POST['subject']; 
        $message = $_POST['message'];
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.sendgrid.com/v3/mail/send",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          
          CURLOPT_POSTFIELDS => "{\n  \"personalizations\": [\n    {\n      \"to\": [\n        {\n          \"email\": \"[Email Address to send Contact to]\"\n        }\n      ],\n      \"subject\": \"New Contact\"\n    }\n  ],\n  \"from\": {\n    \"email\": \"[FROM EMAIL]\"\n  },\n  \"content\": [\n    {\n      \"type\": \"text/html\",\n      \"value\": \"$name<br>$email<br>$subject<br>$message\"\n    }\n  ]\n}",
          CURLOPT_HTTPHEADER => array(
            "authorization: Bearer ",
            "cache-control: no-cache",
            "content-type: application/json"
          ),
        ));
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        header('Location: thanks.html');
        if ($err) {
          echo "cURL Error #:" . $err;
        } else {
          echo $response;
        } */
	}
	
	
    
}
?>
