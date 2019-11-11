<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Application\Models\Users;
use Zend\Json\Json; 
use Zend\View\Model\JsonModel;

use Zend\Cache\StorageFactory;
use Zend\Cache\Storage\Adapter\Memcached;
use Zend\Cache\Storage\StorageInterface;
use Zend\Cache\Storage\AvailableSpaceCapableInterface;
use Zend\Cache\Storage\FlushableInterface;
use Zend\Cache\Storage\TotalSpaceCapableInterface;


 
use Zend\Session\Container;
use Zend\Session\SessionManager; 
  
/********** Models ********/
use Application\Models\Admin;  
use Application\Models\Upload;

use Application\Models\Category; 
use Application\Models\News;
use Application\Models\Images;
use Application\Models\Banners; 
use Application\Models\Subcategory;

/*  
require 'vendor/mpdf/autoload.php'; 
use Mpdf\Mpdf;    
*/

/*--s3--*/

require 'vendor/aws/aws-autoloader.php';
use Aws\S3\S3Client; 
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;

/*
$this->params()->fromPost('paramname');   // From POST
$this->params()->fromQuery('paramname');  // From GET
$this->params()->fromRoute('paramname');  // From RouteMatch
$this->params()->fromHeader('paramname'); // From header
$this->params()->fromFiles('paramname');
*/
class AdminController extends AbstractActionController
{
################################################################################ 
    public function __construct()
    {
        $this->cacheTime = 36000;
        $this->now = date("Y-m-d H:i:s");
        $this->eth = 'คุณไม่สามารถเข้าถึง API ได้ค่ะ!';
        $this->een = 'Sorry, you can not to access API!';
        $this->config = include __DIR__ . '../../../../config/module.config.php';
        $this->adapter = new Adapter($this->config['Db']);
    }
     
    
################################################################################   
    public function basic() 
    {
        $view = new ViewModel(); 
        //Route
        $view->lang = $this->params()->fromRoute('lang', 'th');
        $view->action = $this->params()->fromRoute('action', 'index');
        $view->id = $this->params()->fromRoute('id', '');
        $view->page = $this->params()->fromQuery('page', 1);
        $view->act = $this->params()->fromQuery('act', 'detail');  
        $view->action = $this->getEvent()->getRouteMatch()->getParam('action', 'NA');
        $view->langID = $this->params()->fromQuery('langID', 1);  
        $session = new Container('admin');
        $view->admin = $session;  
        $view->Config = $this->config;  
           
        $view->baseUrl = $this->url('home'); 
        $view->FullLink = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $view->urlFile = $this->config['amazon_s3']['urlFile'];
        $view->languages = $this->config['languages']; 
        /*   
        $view->ar_status = $this->config['contract_status'];    
        */ 
        //$session = new Contract(); 
        //$view->bodyClass()->addClass('something');
        
        $bootstrap = @$this->config['bootstrap']['backend']; 
        $view->SEO = [ 
                        'title'=>$bootstrap['site_name'], 
                        'keywords'=>$bootstrap['meta_data']['keywords'],
                        'description'=>$bootstrap['meta_data']['description'],
                        'image'=>'', 
                        'url'=>$view->FullLink,
                        'domain'=>$_SERVER['HTTP_HOST']
                     ]; 
                     
        $view->scripts = (array)$bootstrap['scripts'];  
        $view->stylesheets = (array)$bootstrap['stylesheets']; 
        
        return $view;       
    } 
  
    
################################################################################   
    public function indexAction() 
    {  
        
        
        $view = $this->basic();  
        //exit;   
        $login = $this->getLogin(); 
        
        if(empty($login)){     
            return $this->redirect()->toRoute('admin', ['action'=>'login']); 
        }
        $container = new Container('admin');
        //echo $container->id;
        $view->content='dashboard';  
        return $view; 
    }
  

################################################################################   
    public function loginAction()  
    {    
        $view = $this->basic();   
        $email = $this->params()->fromPost('email', '');   
        $password = $this->params()->fromPost('password', '');
        $view->error=0; 
        if(!empty($email) && !empty($password)){ //exit; 
            $adapter = $this->adapter;  
            $sql = "SELECT id, name, level, image FROM admin WHERE email='".$email."' AND password ='".md5($email.$password)."' AND status='1' LIMIT 1";
            $statement = $adapter->query($sql);      
            $results = $statement->execute(); 
            $row = $results->current(); 
            if($row['id'] && $row['name']){    
                $this->setSession($row);   
                return $this->redirect()->toRoute('admin', ['action'=>'index']);
            }else{ 
                $view->error=1;  
            }  
        }   
        $login = $this->getLogin(); 
        if(!empty($login)){  
            return $this->redirect()->toRoute('admin', ['action'=>'index']);
        }  
        $view->email=$email;  
        $view->content='login'; 
        return $view;  
    }
    

################################################################################   
    public function getLogin()  
    {   
        $container = new Container('admin');
        $login = false;
        $sid = $container->id;
        if(!empty($sid))
        {
           $login = true; 
        }
        return $login;    
    }
    
################################################################################   
    public function setSession($session=array())  
    {  
        $container = new Container('admin'); 
        foreach( $session as $key=>$value )
        {
            $container->$key = $value;
            //$container->$key = preg_match('/^[a-z0-9]{32}$/', $container->$key);
        }  
    } 
    
   
################################################################################   
    public function logoutAction() 
    {  
        $container = new Container('admin'); 
        unset($container->id); 
        unset($container->name);  
        $container->id = '';
        $container->name = '';  
        //session_destroy(); 
        return $this->redirect()->toRoute('admin', ['action'=>'login']);
    } 
    
    public function makeJSON($data)
    {
        $json = json_encode($data);
        return ($json);
    }
    
################################################################################   
    public function adminAction() 
    {  
        $view = $this->basic(); 
        $id = $this->params()->fromQuery('id', '');
        $task = $this->params()->fromQuery('task', '');
        $draw = $this->params()->fromQuery('draw', 0); 
		$pagestart = $this->params()->fromQuery('start', 0);
		$pageshow = $this->params()->fromQuery('length', 50); 
		$search = $this->params()->fromQuery('search', '%'); 
		$data = $this->params()->fromPost('data', []); 
		$status = $this->params()->fromPost('status', 0); 
		$data['status']=$status; 
		$email = $this->params()->fromPost('email', ''); 
		$view->id = $id;  
		
		if(is_array($search))$search=$search=$search['value'];
		 
        $view->task = $task; 
        
        $login = $this->getLogin();  
        if(empty($login)){ 
            return $this->redirect()->toRoute('admin', ['action'=>'login']); 
        }   
         
        $adapter = $this->adapter; 
        $Model= new Admin($adapter, $view->lang, $view->action, $view->id, $pagestart, $pageshow);
        
        
        //if(!empty($data['name']))echo $task; print_r($data); exit; 
        
        
        if($task=='list'){   
            $data = $Model->getList($search);   
    		$ar_data = array('draw'=>$draw,'recordsTotal'=>$data['total'],'recordsFiltered'=>$data['total'],"data"=>$data['data']); 
    		echo $this->makeJSON($ar_data);  
            exit;  
        }elseif($task=='add' && count($data)>0 && !empty($data['name']) && !empty($data['email'])){ 
            $id = $Model->getNextId();  
            $view->id = $id;  
            $data['id'] = $id; 
            $data['password'] = md5($data['email'].$data['password']);
            //print_r($data); exit;
            $Model->add($data);  
             
            if(!empty($_FILES['pic']['name'])){ 
                $filename = $this->UploadS3($view->Config['amazon_s3'], $_FILES['pic'], 'admin', 100); 
                if(!empty($filename)){ 
                    $Model->updateIMG($view->id, $filename);  
                } 
			} 
			return $this->redirect()->toRoute('admin', ['action'=>'admin']);
            //exit; 
        }elseif($task=='edit' && !empty($view->id) && count($data)>0 && !empty($data['name']) && !empty($data['email'])){
             
            $Model->edit($data); //print_r($data);exit;  
            if(!empty($data['password'])) $Model->updatePassword($data['password']);
            
            if(!empty($_FILES['pic']['name'])){ 
                $filename = $this->UploadS3($view->Config['amazon_s3'], $_FILES['pic'], 'admin', 100);  
                if(!empty($filename)){
                    $detail = $Model->getDetail($view->id);   
                    if(!empty($detail['img'])){  
                        $this->DeleteS3($view->Config['amazon_s3'], 'admin/'.$detail['img']);  
                    }  
                    
                    $session = new Container('admin');  
                     
                    if($session['id']==$view->id) $this->setSession(array('image' => $filename)); 
                    
                    $Model->updateIMG($view->id, $filename);  
                } 
			} 
			 
            $view->detail = $Model->getDetail($view->id);   
            return $this->redirect()->toRoute('admin',['action'=>'admin'],['query'=>['task'=>'edit', 'id'=>$view->id]]); 
            
        }elseif($task=='edit' && !empty($view->id)){ 
            $view->detail = $Model->getDetail($view->id);   
        }elseif($task=='del' && !empty($view->id)){
            $detail = $Model->getDetail($view->id);    
            if(!empty($detail['img'])){      
                $this->DeleteS3($view->Config['amazon_s3'], 'admin/'.$detail['img']); 
            }   
            $Model->del();
            return $this->redirect()->toRoute('admin',['action'=>'admin']); 
        }elseif($task=='checkEmail' && !empty($email)){
            echo $Model->checkEmail($email);       
            exit;  
        }  
       
       
        $view->content = $task; 
         
        return $view;
    } 
    
    
    ################################################################################   
    public function categoryAction() 
    {  
        
        $view = $this->basic(); 
        $view->page_name = 'Category'; 
        
        $id = $this->params()->fromQuery('id', '');
        $task = $this->params()->fromQuery('task', '');
        $draw = $this->params()->fromQuery('draw', 0); 
		$pagestart = $this->params()->fromQuery('start', 0);
		$pageshow = $this->params()->fromQuery('length', 50); 
		$search = $this->params()->fromQuery('search', '%'); 
		$data = $this->params()->fromPost('data', []); 
		$status = $this->params()->fromPost('status', 0); 
		$data['status']=$status; 
		$email = $this->params()->fromPost('email', '');  
		$view->id = $id;  
		 
		//$this->headTitle()->append($view->page_name);
		
		if(is_array($search))$search=$search=$search['value'];
		 
        $view->task = $task; 
        
        $login = $this->getLogin(); 
        if(empty($login)){ 
            return $this->redirect()->toRoute('admin', ['action'=>'login']); 
        }  
         
        $adapter = $this->adapter; 
        $Model= new Category($adapter, $view->lang, $view->action, $view->id, $pagestart, $pageshow);
         
        if($task=='list'){   
            $data = $Model->getList($search);    
    		$ar_data = array('draw'=>$draw,'recordsTotal'=>$data['total'],'recordsFiltered'=>$data['total'],"data"=>$data['data']); 
    		echo $this->makeJSON($ar_data);  
            exit;  
        }else if($task=='add' && count($data)>0 && !empty($data['name_en']) && !empty($data['name_th'])){ 
            $id = $Model->getNextId();  
            $view->id = $id;  
            $data['id'] = $id; 
            ///print_r($data); exit;
            $Model->add($data);  
            //exit; 
            if(!empty($_FILES['pic']['name'])){   
                $filename = $this->UploadS3($view->Config['amazon_s3'], $_FILES['pic'], strtolower($view->page_name), 695);  
                if(!empty($filename)){ 
                    $Model->updateIMG($view->id, $filename);  
                } 
			} 
			return $this->redirect()->toRoute('admin', ['action'=>$view->action]);
            //exit; 
        }else if($task=='edit' && !empty($view->id) && count($data)>0 && !empty($data['name_en']) && !empty($data['name_th'])){
            $Model->edit($data);
            if(!empty($_FILES['pic']['name'])){ 
                $filename = $this->UploadS3($view->Config['amazon_s3'], $_FILES['pic'], strtolower($view->page_name), 695);  
                if(!empty($filename)){
                    $detail = $Model->getDetail($view->id);   
                    if(!empty($detail['img'])){  
                        $this->DeleteS3($view->Config['amazon_s3'], strtolower($view->page_name).'/'.$detail['img']);  
                    }   
                    //$this->setSession(array('image' => $filename));   
                    $Model->updateIMG($view->id, $filename);  
                } 
			}
            $view->detail = $Model->getDetail($view->id);   
            return $this->redirect()->toRoute('admin',['action'=>$view->action],['query'=>['task'=>'edit', 'id'=>$view->id]]); 
            
        }else if($task=='edit' && !empty($view->id)){ 
            $view->detail = $Model->getDetail($view->id);    
        }else if($task=='del' && !empty($view->id)){
            $detail = $Model->getDetail($view->id);      
            if(!empty($detail['img'])){      
                $this->DeleteS3($view->Config['amazon_s3'], strtolower($view->page_name).'/'.$detail['img']);  
            }   
            $Model->del(); 
            return $this->redirect()->toRoute('admin',['action'=>$view->action]); 
        }else if($task=='reoder' && !empty($view->id)){
            unset($data['status']); 
            $data['sort_order'] = $this->params()->fromQuery('sortby', 1); 
            $Model->edit($data);  
            exit;   
        }
        
        $view->content = $task;  
         
        return $view;
    }
    
    ################################################################################   
    public function subcategoryAction() 
    {  
        
        $view = $this->basic(); 
        $view->page_name = 'Subcategory';  
        
        $id = $this->params()->fromQuery('id', '');
        $task = $this->params()->fromQuery('task', '');
        $draw = $this->params()->fromQuery('draw', 0); 
		$pagestart = $this->params()->fromQuery('start', 0);
		$pageshow = $this->params()->fromQuery('length', 50); 
		$search = $this->params()->fromQuery('search', '%'); 
		$data = $this->params()->fromPost('data', []); 
		$status = $this->params()->fromPost('status', 0); 
		$data['status']=$status; 
		$email = $this->params()->fromPost('email', '');  
		$view->id = $id;   
		  
		//$this->headTitle()->append($view->page_name);
		
		if(is_array($search))$search=$search=$search['value'];
		 
        $view->task = $task; 
        
        $login = $this->getLogin(); 
        if(empty($login)){ 
            return $this->redirect()->toRoute('admin', ['action'=>'login']); 
        }  
         
        $adapter = $this->adapter;   
        $Model= new Subcategory($adapter, $view->lang, $view->action, $view->id, $pagestart, $pageshow);
        $Category= new Category($adapter, $view->lang, $view->action, $view->id, $pagestart, $pageshow);
        
        if($task=='list'){   
            $data = $Model->getList($search);    
    		$ar_data = array('draw'=>$draw,'recordsTotal'=>$data['total'],'recordsFiltered'=>$data['total'],"data"=>$data['data']); 
    		echo $this->makeJSON($ar_data);  
            exit;  
        }else if($task=='add' && count($data)>0 && !empty($data['name_en']) && !empty($data['name_th'])){ 
            $id = $Model->getNextId();  
            $view->id = $id;  
            $data['id'] = $id; 
            $Model->add($data);  
            if(!empty($_FILES['pic']['name'])){   
                $filename = $this->UploadS3($view->Config['amazon_s3'], $_FILES['pic'], strtolower($view->page_name), 695);  
                if(!empty($filename)){ 
                    $Model->updateIMG($view->id, $filename);  
                } 
			} 
			return $this->redirect()->toRoute('admin', ['action'=>$view->action]);
            //exit; 
        }else if($task=='edit' && !empty($view->id) && count($data)>0 && !empty($data['name_en']) && !empty($data['name_th'])){
            $Model->edit($data);
            if(!empty($_FILES['pic']['name'])){ 
                $filename = $this->UploadS3($view->Config['amazon_s3'], $_FILES['pic'], strtolower($view->page_name), 695);  
                if(!empty($filename)){
                    $detail = $Model->getDetail($view->id);   
                    if(!empty($detail['img'])){  
                        $this->DeleteS3($view->Config['amazon_s3'], strtolower($view->page_name).'/'.$detail['img']);  
                    }   
                    //$this->setSession(array('image' => $filename));   
                    $Model->updateIMG($view->id, $filename);  
                } 
			}
            $view->detail = $Model->getDetail($view->id);   
            return $this->redirect()->toRoute('admin',['action'=>$view->action],['query'=>['task'=>'edit', 'id'=>$view->id]]); 
            
        }else if($task=='edit' && !empty($view->id)){ 
            $view->detail = $Model->getDetail($view->id);    
        }else if($task=='del' && !empty($view->id)){
            $detail = $Model->getDetail($view->id);      
            if(!empty($detail['img'])){      
                $this->DeleteS3($view->Config['amazon_s3'], strtolower($view->page_name).'/'.$detail['img']);  
            }   
            $Model->del(); 
            return $this->redirect()->toRoute('admin',['action'=>$view->action]); 
        }else if($task=='reoder' && !empty($view->id)){
            unset($data['status']); 
            $data['sort_order'] = $this->params()->fromQuery('sortby', 1); 
            $Model->edit($data);  
            exit; 
        }
         
        $view->cate = array(); 
        if($task=='add' || $task=='edit'){ 
            $cate = $Category->getListCate();
            //print_r($cate); exit;
            $view->cate = $cate;    
        }
         
        $view->content = $task;  
         
        return $view;
    }
    
    
    ################################################################################   
    public function newsAction() 
    {  
        
        $view = $this->basic();  
        $view->page_name = 'News'; 
        
        $id = $this->params()->fromQuery('id', '');
        $task = $this->params()->fromQuery('task', '');
        $draw = $this->params()->fromQuery('draw', 0); 
		$pagestart = $this->params()->fromQuery('start', 0);
		$pageshow = $this->params()->fromQuery('length', 50); 
		$search = $this->params()->fromQuery('search', '%'); 
		$data = $this->params()->fromPost('data', []); 
		$status = $this->params()->fromPost('status', 0); 
		$data['status']=$status; 
		$email = $this->params()->fromPost('email', '');  
		$view->id = $id;  
		 
		//$this->headTitle()->append($view->page_name);
		$removeImage = $this->params()->fromPost('removeImage', '');
		
		if(is_array($search))$search=$search=$search['value'];
		 
        $view->task = $task; 
        
        $login = $this->getLogin(); 
        if(empty($login)){ 
            return $this->redirect()->toRoute('admin', ['action'=>'login']); 
        }  
         
        $adapter = $this->adapter; 
        $Model= new News($adapter, $view->lang, $view->action, $view->id, $pagestart, $pageshow);
        $Category= new Category($adapter, $view->lang, $view->action, $view->id, $pagestart, $pageshow);
        
        if($task=='list'){   
            $data = $Model->getList($search);    
    		$ar_data = array('draw'=>$draw,'recordsTotal'=>$data['total'],'recordsFiltered'=>$data['total'],"data"=>$data['data']); 
    		echo $this->makeJSON($ar_data);  
            exit;  
        }else if($task=='add' && count($data)>0 && !empty($data['name_en']) && !empty($data['name_th'])){ 
            $id = $Model->getNextId();  
            $view->id = $id;  
            $data['id'] = $id; 
            
            $content_th = [];
            $content_en = [];
            
            $img_path = 'public/img/'.strtolower($view->page_name).'/';
            
            $count_en = 1;
            foreach ($data['description_en'] as $key=>$value) { 
                if(strpos($value, 'data:image') !== false){  
                    $image_parts = explode(";base64,", $value); 
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = md5($id.uniqid()). '.png'; 
                	$file =  $img_path.$fileName;   
                    file_put_contents($file, $image_base64); 
                	$content_en['image_'.$count_en] = $fileName; 
                	$count_en++;
                }else if(!empty($value)){
                    $content_en['text_'.$count_en] = $value;
                    $count_en++;
                }
            }
            
            $count_th = 1;
            foreach ($data['description_th'] as $key=>$value) {
                if(strpos($value, 'data:image') !== false){
                    $image_parts = explode(";base64,", $value); 
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = md5($id.uniqid()). '.png'; 
                	$file =  $img_path.$fileName;    
                    file_put_contents($file, $image_base64); 
                	$content_th['image_'.$count_th] = $fileName;  
                	$count_th++; 
                }else if(!empty($value)){  
                    $content_th['text_'.$count_th] = $value;
                    $count_th++;  
                } 
            } 
             
            $data['description_en'] = json_encode($content_en);  
            $data['description_th'] = json_encode($content_th); 
             
            $Model->add($data);  
            if(!empty($_FILES['pic']['name'])){   
                $filename = $this->UploadS3($view->Config['amazon_s3'], $_FILES['pic'], strtolower($view->page_name), 695);  
                if(!empty($filename)){ 
                    $Model->updateIMG($view->id, $filename);  
                } 
			} 
			return $this->redirect()->toRoute('admin', ['action'=>$view->action]);
            //exit; 
        }else if($task=='edit' && !empty($view->id) && count($data)>0 && !empty($data['name_en']) && !empty($data['name_th'])){
            
            $content_th = [];
            $content_en = [];
            
            $img_path = 'public/img/'.strtolower($view->page_name).'/'; 
            
            $count_en = 1;
            foreach ($data['description_en'] as $key=>$value) { 
                if(strpos($value, 'data:image') !== false){  
                    $image_parts = explode(";base64,", $value); 
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = md5($id.uniqid()). '.png'; 
                	$file =  $img_path.$fileName;   
                    file_put_contents($file, $image_base64); 
                	$content_en['image_'.$count_en] = $fileName; 
                	$count_en++;
                }else if(!empty($value)){
                    $file_name=array('jpg','png','gif');
                    $arrFile = pathinfo($value); 				
					$file_ext = $arrFile['extension'];
                    if(in_array($file_ext, $file_name)){
                        $content_en['image_'.$count_en] = $value;
                    }else{
                        $content_en['text_'.$count_en] = $value; 
                    }
                    $count_en++;
                }
            }
            
            $count_th = 1;
            foreach ($data['description_th'] as $key=>$value) {
                if(strpos($value, 'data:image') !== false){
                    $image_parts = explode(";base64,", $value); 
                    $image_type_aux = explode("image/", $image_parts[0]);
                    $image_type = $image_type_aux[1];
                    $image_base64 = base64_decode($image_parts[1]);
                    $fileName = md5($id.uniqid()). '.png'; 
                	$file =  $img_path.$fileName;    
                    file_put_contents($file, $image_base64); 
                	$content_th['image_'.$count_th] = $fileName;   
                	$count_th++; 
                }else if(!empty($value)){ 
                    $file_name=array('jpg','png','gif');
                    $arrFile = pathinfo($value); 				
					$file_ext = $arrFile['extension'];
                    if(in_array($file_ext, $file_name)){
                        $content_th['image_'.$count_th] = $value; 
                    }else{
                        $content_th['text_'.$count_th] = $value; 
                    } 
                    //$content_th['text_'.$count_th] = $value;
                    $count_th++;  
                } 
            }  
             
            $data['description_en'] = json_encode($content_en);  
            $data['description_th'] = json_encode($content_th); 
             
            $Model->edit($data);
            if(!empty($_FILES['pic']['name'])){ 
                $filename = $this->UploadS3($view->Config['amazon_s3'], $_FILES['pic'], strtolower($view->page_name), 695);   
                if(!empty($filename)){
                    $detail = $Model->getDetail($view->id);   
                    if(!empty($detail['img'])){  
                        $this->DeleteS3($view->Config['amazon_s3'], strtolower($view->page_name).'/'.$detail['img']);  
                    }   
                   // $this->setSession(array('image' => $filename));   
                    $Model->updateIMG($view->id, $filename);  
                } 
			}
			if(!empty($removeImage)){
			    $image = explode(',', $removeImage); 
			    foreach ($image as $value) {  
			        $this->DeleteS3($view->Config['amazon_s3'], strtolower($view->page_name).'/'.trim($value)); 
			    }
			}
            $view->detail = $Model->getDetail($view->id);   
            return $this->redirect()->toRoute('admin',['action'=>$view->action],['query'=>['task'=>'edit', 'id'=>$view->id]]); 
            
        }else if($task=='edit' && !empty($view->id)){ 
            $view->detail = $Model->getDetail($view->id);    
        }else if($task=='del' && !empty($view->id)){
            $detail = $Model->getDetail($view->id);      
            if(!empty($detail['img'])){      
                $this->DeleteS3($view->Config['amazon_s3'], strtolower($view->page_name).'/'.$detail['img']);  
            }   
            $Model->del(); 
            return $this->redirect()->toRoute('admin',['action'=>$view->action]); 
        }else if($task=='reoder' && !empty($view->id)){
            unset($data['status']);
            $data['sort_order'] = $this->params()->fromQuery('sortby', 1); 
            $Model->edit($data);  
            exit; 
        }
        $view->cate = array(); 
        if($task=='add' || $task=='edit'){ 
            $cate = $Category->getListCate();
            $view->cate = $cate;  
        }
         
        $view->content = $task;  
         
        return $view;
    }
################################################################################   
    public function uploadImageAction()  
    {  
        $view = $this->basic(); 
        $login = $this->getLogin(); 
        if(empty($login)){
            return $this->redirect()->toRoute('admin',['action'=>'login']); 
        } 
        
        $pagename = $this->params()->fromPost('actionpage', 'general');   
        
        if(!empty($_FILES['image']['name'])){   
    		$filename = $this->UploadS3($view->Config['amazon_s3'], $_FILES['image'], 'general', 695); 
    		$adapter = $this->adapter; 
    		$Model= new Images($adapter, $view->lang, $view->action, $view->id, 0, 50); 
    		$id = $Model->getNextId();   
            $view->id = $id;    
            $data['id'] = $id;     
            $data['page'] = $pagename;    
            $data['filename'] = $filename;     
            $Model->add($data);        
    		$pathImg = '/images/general/'.$filename;   
    		print_r($pathImg);    
    	}
    	exit;
    }       
    
    /*
    public function UploadS3($Config,  $FILES,  $folder='general', $resize=695){       
        $filename = '';
        // $s3 = new S3Client($Config['config']);    
        //// Upload an object to Amazon S3   
        //$bucket = $Config['bucket'];//'starter-kit-rockstar';    
        try   
        {  
            $filename = explode(".", $FILES["name"]);   
            $filenameext = strtolower(pathinfo( $FILES['name'] , PATHINFO_EXTENSION));    
            $filename = md5(time());   
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
    */
     
    public function UploadS3($Config,  $FILES,  $folder='general', $resize=695){      
        $filename = '';
        //$view->Config['amazon_s3'];
         
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
     
     
    public function DeleteS3($Config, $keyname=''){ 
        
        //@unlink('public/img/'.$keyname);    
         
        
        if(!empty($keyname)){
            $s3 = new S3Client($Config['config']);   
            // Upload an object to Amazon S3 
            $bucket = $Config['bucket'];//'starter-kit-rockstar';
            
            $result = $s3->deleteObject(array( 
                'Bucket' => $bucket,
                'Key'    => $keyname
            ));
        }  
    }
    
    
    public function delimgAction(){  
        
        $view = $this->basic();  
        $id = $this->params()->fromPost('id', ''); 
        $image = $this->params()->fromPost('image', ''); 
        $pagename = $this->params()->fromPost('actionpage', 'general');
        $del = ''; 
        $adapter = $this->adapter; 
        $view->id = $id;  
		$Model= new Images($adapter, $view->lang, $view->action, $view->id, 0, 50); 
        if(!empty($id)){ 
    		$detail = $Model->getDetail($id);   
    		if(!empty($detail['filename'])){
    		    $del = 'general/'.$detail['filename'];
    		    $Model->del($id);   
    		}
        }else if(!empty($image)){       
    	    $image2 = pathinfo($image);
    	    $detail = $Model->getDetailByImage($image2['basename']); 
    	    if(!empty($detail['id'])){  
    	        $Model->del($detail['id']);
    	        $del = 'general/'.$image2['basename'];  
    	    }
    	} 
    	 
    	if(!empty($del)){ 
    	    $this->DeleteS3($view->Config['amazon_s3'], $del); 
    	}  
    	
    	print_r(json_encode(['status'=>200,'file'=>$del])); 
    	exit;
        
       
    }
    
    
    ################################################################################   
    public function bannersAction()  
    {  
        
        $view = $this->basic(); 
        $view->page_name = 'Banners'; 
        
        $id = $this->params()->fromQuery('id', '');
        $task = $this->params()->fromQuery('task', '');
        $draw = $this->params()->fromQuery('draw', 0); 
		$pagestart = $this->params()->fromQuery('start', 0);
		$pageshow = $this->params()->fromQuery('length', 50); 
		$search = $this->params()->fromQuery('search', '%'); 
		$data = $this->params()->fromPost('data', []); 
		$status = $this->params()->fromPost('status', 0); 
		$data['status']=$status; 
		$email = $this->params()->fromPost('email', '');  
		$view->id = $id;  
		 
		//$this->headTitle()->append($view->page_name);
		
		if(is_array($search))$search=$search=$search['value'];
		 
        $view->task = $task; 
        
        $login = $this->getLogin(); 
        if(empty($login)){ 
            return $this->redirect()->toRoute('admin', ['action'=>'login']); 
        }  
         
        $adapter = $this->adapter;  
        $Model= new Banners($adapter, $view->lang, $view->action, $view->id, $pagestart, $pageshow);
         
        if($task=='list'){   
            $data = $Model->getList($search);    
    		$ar_data = array('draw'=>$draw,'recordsTotal'=>$data['total'],'recordsFiltered'=>$data['total'],"data"=>$data['data']); 
    		echo $this->makeJSON($ar_data);  
            exit;  
        }else if($task=='add' && count($data)>0 && !empty($data['name']) && !empty($_FILES['pic']['name'])){ 
            $id = $Model->getNextId();  
            $view->id = $id;   
            $data['id'] = $id;  
            $Model->add($data);  
            if(!empty($_FILES['pic']['name'])){   
                $filename = $this->UploadS3($view->Config['amazon_s3'], $_FILES['pic'], strtolower($view->page_name), 695);  
                if(!empty($filename)){ 
                    $Model->updateIMG($view->id, $filename);  
                } 
			} 
			return $this->redirect()->toRoute('admin', ['action'=>$view->action]);
            //exit; 
        }else if($task=='edit' && !empty($view->id) && count($data)>0 && !empty($data['name'])){ 
            $Model->edit($data);
            if(!empty($_FILES['pic']['name'])){ 
                $filename = $this->UploadS3($view->Config['amazon_s3'], $_FILES['pic'], strtolower($view->page_name), 695);  
                if(!empty($filename)){
                    $detail = $Model->getDetail($view->id);    
                    if(!empty($detail['img'])){  
                        $this->DeleteS3($view->Config['amazon_s3'], strtolower($view->page_name).'/'.$detail['img']);  
                    }   
                    //$this->setSession(array('image' => $filename));   
                    $Model->updateIMG($view->id, $filename);  
                } 
			}
            $view->detail = $Model->getDetail($view->id);   
            return $this->redirect()->toRoute('admin',['action'=>$view->action],['query'=>['task'=>'edit', 'id'=>$view->id]]); 
            
        }else if($task=='edit' && !empty($view->id)){ 
            $view->detail = $Model->getDetail($view->id);    
        }else if($task=='del' && !empty($view->id)){
            $detail = $Model->getDetail($view->id);      
            if(!empty($detail['img'])){      
                $this->DeleteS3($view->Config['amazon_s3'], strtolower($view->page_name).'/'.$detail['img']);  
            }   
            $Model->del(); 
            return $this->redirect()->toRoute('admin',['action'=>$view->action]); 
        }else if($task=='reoder' && !empty($view->id)){
            unset($data['status']);
            $data['sort_order'] = $this->params()->fromQuery('sortby', 1); 
            $Model->edit($data);  
            exit; 
        }
        
        $view->content = $task;  
         
        return $view;
    }
    
    
    /*
    
    public function boypdfAction(){ 
        $view = $this->basic();
        
        $mpdf = new Mpdf();  
        $mpdf->WriteHTML('<h1>Hello world!</h1>');
        $file = "public/pdf/".date("YmdHis").".pdf"; 
        $mpdf->Output($file); 
        if (file_exists($file)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="'.basename($file).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($file));
            readfile($file);
            @unlink($file); 
            exit;
        } 
        exit;  
    }
    
    */
    
    
    
    
    
}



















