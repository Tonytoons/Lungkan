<?php
namespace Application\Controller;
/*--s3--*/

require 'vendor/aws/aws-autoloader.php';
use Aws\Ses\SesClient;
use Aws\S3\S3Client;
use Aws\Exception\AwsException;
use Aws\S3\Exception\S3Exception;  
 
//exit; 
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

use Zend\Json\Json;
use Zend\View\Model\JsonModel;
use Application\Models\Upload;

use Zend\Cache\StorageFactory;
use Zend\Cache\Storage\Adapter\Memcached;
use Zend\Cache\Storage\StorageInterface;
use Zend\Cache\Storage\AvailableSpaceCapableInterface;
use Zend\Cache\Storage\FlushableInterface;
use Zend\Cache\Storage\TotalSpaceCapableInterface;


use Zend\Session\Container;
use Zend\Session\SessionManager; 

#mail#
use Zend\Mail\Message; 
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;  

use Application\Models\Users; 
use Application\Models\Category;
use Application\Models\Subcategory;
use Application\Models\Moneypot; 
use Application\Models\Omise;  
use Application\Models\Transactions;
use Application\Models\Transferfund;
use Application\Models\Invite;
use Application\Models\Images;  
use Application\Models\Pagination; 
use Application\Models\Line;
use Application\Models\Albums; 
 
/*
$this->params()->fromPost('paramname');   // From POST
$this->params()->fromQuery('paramname');  // From GET
$this->params()->fromRoute('paramname');  // From RouteMatch
$this->params()->fromHeader('paramname'); // From header
$this->params()->fromFiles('paramname');
*/
/*  Setting SEO  
$view->SEO = [
                'title'=>'xxx',
                'keywords'=>'xx,xxx,xx',
                'description'=>'xx xxx xx xxx',
                'url'=>'https://renovly.co',
                'image'=>'https://renovly.co/img/xxx.jpg',
                'domain'=>'renovly.co',
                'fb_app_id'=>'128202497713838', //fb:app_id  
                'locale'=>'fr_FR', //og:locale
                'creator'=>'@Renovly', //twitter:creator
             ];  
*/ 


 

class IndexController extends AbstractActionController
{
    ################################################################################ 
    public function __construct()
    {
        $this->cacheTime = 36000;
        $this->now = date("Y-m-d H:i:s");
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
        $view->act = $this->params()->fromQuery('act', '');   
        $view->action = $this->getEvent()->getRouteMatch()->getParam('action', 'NA');
        $view->pay_error = $this->params()->fromQuery('error', '');  
        //$view->langID = $this->params()->fromQuery('langID', 1); 
        
        
        if(!is_numeric($view->id)){ 
           $arr_id = explode('-', $view->id);
           $view->id = @$arr_id[0];  
        }
          
        $view->pagestart = (int)$this->params()->fromQuery('start', 0);
        $view->pageshow = (int)$this->params()->fromQuery('length', 21);
    		
        $session = new Container('Users');
        $Users = $session;
        if(!empty($Users->name)){  
            if($view->lang=='th'){
                $session->name = $Users->name_th;
            }else{  
                $session->name = $Users->name_en;
            }
        }
        $view->Users = $session; 
        
        $view->baseUrl = $this->url('home'); 
        $view->FullLink = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "s://") . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $view->webURL = (isset($_SERVER['HTTPS']) ? "https" : "https") . "://".$_SERVER['HTTP_HOST'];  
        $view->urlFile = $this->config['amazon_s3']['urlFile']; 
        $view->languages = $this->config['languages']; 
        $view->transfer_fee = $this->config['transfer_fee']; 
        $bootstrap = @$this->config['bootstrap']['fontend'];
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
        $view->stylesheets_custom = (array)$bootstrap['stylesheets_custom'];  
        
        $view->error = ($view->lang=='')?'อ๊ะ! บางอย่างผิดพลาด. กรุณาลองอีกครั้ง.':'Oops! Something went wrong. Please try again.';
         
        //$view->pageshow = 21;    
        $view->pagestart = ($view->pageshow*($view->page-1)); 
        
        $view->pagination = ''; 
        
        return $view;       
    } 
    
    ################################################################################
    public function indexAction() 
    {
        try
        { 
            $view = $this->basic();   
            //$view->scripts['foot'] = '';
            
            $Subcategory= new Subcategory($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            $view->cateList = $Subcategory->getListHomeSubCate(100); 
            //$view->scripts = ''; 
            //print_r($view->Users->id); exit; 
            return $view; 
        }
        catch( Exception $e )
        {
            print_r($e); 
        }
    }
    
    ################################################################################
    public function loginAction() 
    {
        try
        {
            $view = $this->basic();
            
            $login = $this->getLogin(); 
            if(!empty($login)){      
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            $name = $this->params()->fromPost('name', ''); 
            $email = $this->params()->fromPost('email', ''); 
            $password = $this->params()->fromPost('password', ''); 
            $facebook_id = $this->params()->fromPost('facebook_id', ''); 
            
            
            $Model= new Users($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $Line= new Line(); 
            
            
            
            
            if($view->act=='login'){    
                
                $id = $Model->checkLogin($email, $password, $facebook_id); 
                //echo $id; exit;
                if(empty($id)){
                    $rs = array('status'=>400,'items'=>($view->lang=='')?'ไม่พบผู้ใช้ในระบบ':'No user found in the system.');
                }else{     
                    $detail = $Model->getDetail($id); 
                    $this->setSession($detail);     
                    $rs = array('status'=>200,'items'=>($view->lang=='')?'คุณเข้าสู่ระบบเรียบร้อยแล้ว':'You have successfully login.','id'=>$id);   
                }     
                print_r(json_encode($rs)); 
                exit;   
            }else if($view->act=='loginFB'){    
                
                $id = $Model->checkLogin($email, $password, $facebook_id); 
               
                if(empty($id) && !empty($facebook_id)){  
                    $id = $Model->getNextId();
                    $add['id'] = $id;  
                    $add['email'] = trim($email);
                    $add['name_en'] = $name; 
                    $add['name_th'] = $name; 
                    //$add['password'] = md5(trim($email).$password);
                    //$add['active'] = '1';    
                    $add['facebook_id'] = $facebook_id;  
                    $Model->add($add);  
                    $detail = $Model->getDetail($id); 
                    $this->setSession($detail);  
                    $rs = array('status'=>200,'items'=>($view->lang=='')?'คุณเข้าสู่ระบบเรียบร้อยแล้ว':'You have successfully login.','id'=>$id); 
                }else{         
                    $detail = $Model->getDetail($id);
                    if($detail['active']=='1'){  
                        if(empty($detail['facebook_id'])){ 
                            $edit['facebook_id'] = $facebook_id;  
                            $Model->edit($edit, $id);  
                            $detail['facebook_id'] = $facebook_id;
                        } 
                        $this->setSession($detail);     
                        $rs = array('status'=>200,'items'=>($view->lang=='')?'คุณเข้าสู่ระบบเรียบร้อยแล้ว':'You have successfully login.','id'=>$id);  
                    }else{ 
                        $rs = array('status'=>400,'items'=>($view->lang=='')?'ผู้ใช้ถูกบล็อกในระบบ':'User blocked in the system');
                    }
                }      
                print_r(json_encode($rs)); 
                exit;   
            }
            
            
            $view->content = 'login';
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }
    
    ################################################################################
    public function loginLineAction() 
    {
        try
        {
            $view = $this->basic();
            
            $login = $this->getLogin(); 
            if(!empty($login)){      
               return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            $name = $this->params()->fromPost('name', ''); 
            $email = $this->params()->fromPost('email', ''); 
            $password = $this->params()->fromPost('password', ''); 
            $facebook_id = $this->params()->fromPost('facebook_id', ''); 
            $code = $this->params()->fromQuery('code', ''); 
            $state = $this->params()->fromQuery('state', '');
            $return_url = $this->params()->fromQuery('return_url', '');
            
            
            $Model= new Users($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $Line= new Line(); 
            
            $view->content = 'login'; 
            
            if(!empty($return_url)) $_SESSION['return_url'] = $return_url; 
            
            if($view->act=='login'){
                
                $Line->authorize();   
                
            }else if($view->act=='callback' && !empty($code) && !empty($state)){   
                //unset($_GET['act']);
                $view->content = 'register_line';
                if(!empty($view->Users->access_token)){
                    $user = $Line->userProfile($view->Users->access_token,true);
                    $view->userDetail = $user; 
                }else{
                    $dataToken = $Line->requestAccessToken($_GET, true); 
                    if(!empty($dataToken['access_token'])){
                        $this->setSession($dataToken);  
                        $user = $Line->userProfile($dataToken['access_token'],true);
                        $view->userDetail = $user;
                    }
                    
                }
                
                if(!empty($view->userDetail['email'])){
                    $id = $Model->getNextId();
                    $add['id'] = $id;  
                    $add['email'] = trim($view->userDetail['email']);
                    $add['name_en'] = $view->userDetail['displayName']; 
                    $add['name_th'] = $view->userDetail['displayName']; 
                    $add['line_userId'] = $view->userDetail['userId'];  
                    $add['line_pictureUrl'] = $view->userDetail['pictureUrl'];
                    $Model->add($add);   
                    $detail = $Model->getDetail($id);  
                    $this->setSession($detail); 
                    if(!empty($_SESSION['return_url'])){
                        return $this->redirect()->toUrl($_SESSION['return_url'].'?rd='.rand(100,1000));  
                    }
                    return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));  
                }  
                //print_r($_SESSION['return_url']); exit;
                $user_id = $Model->checkLineLogin($view->userDetail['userId']);  
                 
                if(!empty($user_id)){   
                    $Model->edit(['line_pictureUrl'=>$view->userDetail['pictureUrl']], $user_id);  
                    $detail = $Model->getDetail($user_id);    
                    $this->setSession($detail);    
                    if(!empty($_SESSION['return_url'])){ 
                        return $this->redirect()->toUrl($_SESSION['return_url'].'?rd='.rand(100,1000));  
                    }
                    return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));  
                }else{
                    return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));  
                } 
                
            }else if($view->act=='register'){  
                
                $rs = array('status'=>400,'items'=>$view->error,'id'=>0);  
                $user = $Line->userProfile($view->Users->access_token,true);
                $user_id = $Model->checkLineLogin($user['userId']); 
                if(!empty($user_id)){ 
                    $Model->edit(['line_pictureUrl'=>$view->userDetail['pictureUrl']], $user_id);    
                    $detail = $Model->getDetail($user_id);    
                    $this->setSession($detail);   
                    $rs = array('status'=>200,'items'=>($view->lang=='')?'คุณได้ลงทะเบียนเรียบร้อยแล้ว':'You have successfully register.','id'=>$user_id);  
                }else if($user_id==0){   
                    $id = $Model->getNextId();
                    $add['id'] = $id;   
                    $add['email'] = trim($email);
                    $add['name_en'] = $name; 
                    $add['name_th'] = $name; 
                    $add['line_userId'] = $user['userId'];  
                    $add['line_pictureUrl'] = $user['pictureUrl'];
                    $Model->add($add);   
                    $detail = $Model->getDetail($id);  
                    $this->setSession($detail);
                    $rs = array('status'=>200,'items'=>($view->lang=='')?'คุณได้ลงทะเบียนเรียบร้อยแล้ว':'You have successfully register.','id'=>$id); 
                }
                print_r(json_encode($rs)); 
                exit; 
            } 
            
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }

    ################################################################################
    public function registerAction() 
    {
        try
        {
            $view = $this->basic();
            
    		$login = $this->getLogin(); 
            if(!empty($login)){       
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            } 
    		
            $name = $this->params()->fromPost('name', ''); 
            $email = $this->params()->fromPost('email', ''); 
            $password = $this->params()->fromPost('password', ''); 
            $facebook_id = $this->params()->fromPost('facebook_id', ''); 
            
            
            $Model= new Users($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            if($view->act=='register'){  
                $rs = $Model->checkRegister($email, $facebook_id);
                //print_r($rs); exit;
                if(!empty($rs)){
                   $rs = array('status'=>400,'items'=>($view->lang=='')?'อีเมลของคุณอยู่ในระบบแล้ว':'Your email is already in the system.');
                }else{   
                    $id = $Model->getNextId();
                    $add['id'] = $id;  
                    $add['email'] = trim($email);
                    $add['name_en'] = $name; 
                    $add['name_th'] = $name;
                    $add['password'] = md5(trim($email).$password);
                    //$add['active'] = '1';   
                    $add['facebook_id'] = $facebook_id;  
                    $rs = $Model->add($add);    
                    $detail = $Model->getDetail($id);
                    $this->setSession($detail);
                    if(!empty($rs)){ 
                        
                        $OmiseData['description'] = $name.' : '.$id;
                        $OmiseData['email'] = $email; 
                        $Omise = new Omise();
                        $ors = $Omise->apiService('customers', $OmiseData);   
                        if(!empty($ors->id)){
                            $Omise->apiService('customers/'.$ors->id); 
                            $Model->edit(['omise_customer_id'=>$ors->id], $id);  
                        }  
                    } 
                    $rs = array('status'=>200,'items'=>($view->lang=='')?'คุณได้ลงทะเบียนเรียบร้อยแล้ว':'You have successfully registered.','id'=>$id);  
                }    
                print_r(json_encode($rs)); 
                exit;  
            }
            
            $view->content = 'register';
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }

    ################################################################################
    public function createAction() 
    {
        try
        {
            $view = $this->basic();
            
            //exit;
            $view->potname = $this->params()->fromQuery('potname', '');
            $view->cid = $this->params()->fromQuery('cid', '');
            
            $login = $this->getLogin(); 
            
            $name = $this->params()->fromPost('potname', '');
            $organizer = $this->params()->fromPost('organizer', '');
            $category_id = $this->params()->fromPost('category', '');
            $subcate_id = $this->params()->fromPost('subcategory', '');
            $event_date = $this->params()->fromPost('eventdate', ''); 
            $description = $this->params()->fromPost('description', '');
            $image = $this->params()->fromPost('image', '');
            $thumb = $this->params()->fromPost('thumb', '');
            $albums = $this->params()->fromPost('albums', '');
            
            if($view->act=='subcategory' && !empty($view->cid)){
                //$view->id = $view->cid;   
                $Subcategory= new Subcategory($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
                $subcateList = $Subcategory->getListSubCate($view->cid); 
                print_r(json_encode($subcateList)); 
                exit; 
            }else if($view->act=='create' && !empty($login) && !empty($name) && !empty($organizer) && !empty($category_id) && !empty($event_date)){ 
                
                
                
                
                //$view->id = $view->Users->id;  
                $Model= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
                $id = $Model->getNextId();   
                $data['id'] = $id;     
                $data['user_id'] = $view->Users->id;  
                $data['name'] = $name; 
                $data['organizer'] = $organizer;
                $data['category_id'] = $category_id;
                $data['subcate_id'] = $subcate_id;
                $data['event_date'] = $event_date;  
                $data['description'] = $description;   
                //$data['setting_deadline'] = $event_date; 
               
                $rs = $Model->add($data);  
               
                $filename = '';
                if(!empty($thumb)){
                    $thumb_img = $this->UploadS3Base64($thumb, 'covers');
                    $update['thumb'] = $thumb_img;
                    $Model->edit($update, $data['id']);  
                    //print_r($update);  
                }  
                 
                if(!empty($image)){
                    $filename = $this->UploadS3Base64($image, 'covers');
                    $Model->updateIMG($id, $filename); 
                }  
                 
                //exit;
                /*
                if(!empty($_FILES['cover']['name']) && !empty($rs)){  
                    $filename = $this->UploadS3($_FILES['cover'], 'covers', 1200); 
                    if(!empty($filename)){     
                        $Model->updateIMG($id, $filename);  
                    } 
    			}   
    			*/ 
    			
    			if(!empty($filename)){ 
    			    
    			    $u_name = $view->Users->name;
    			    $to_email = $view->Users->email;
    			    $potname = $name; 
    			    $txt = file_get_contents($view->webURL.'/email/createpot.html');       
					$txt = preg_replace(array('/{name}/', '/{potname}/'), array($u_name, $potname), $txt);
					$subject = 'You have create '.$potname.' successfully'; 
					$send = $this->sendMail($subject, 'LONGKHAN', $this->config['email_config']['email_contact'], ucfirst($u_name), $to_email, $txt); 
					//print_r(json_encode([$this->config['email_config']['email_contact'], $send, $u_name, $to_email, $potname]));  exit;  
					
					
					// Album
					if(!empty($albums)){
    					$Albums = new Albums($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
    					
    					foreach($albums as $key=>$item){  
    					    $album_img = $this->UploadS3Base64($item, 'albums');
                            if(!empty($album_img)){  
                                $insertData['image'] = $album_img;
                                $insertData['pot_id'] = $id; 
                                $Albums->add($insertData);    
                            }   
    					}  
					}
    			    $rs = array('status'=>200,'items'=>($view->lang=='')?'คุณสร้างหม้อเงินสำเร็จแล้ว':'You have create money pot successfully.','id'=>$id, 'slug'=>$this->slugify($data['name']));
    			}else{   
    			    $rs = array('status'=>400,'items'=> $view->error); 
    			}
    			print_r(json_encode($rs));  
    			exit;
            }
              
            $stylesheets = $view->stylesheets;
            $stylesheets['screen'][] = 'assets/vendor/flatpickr/dist/flatpickr.min.css';
            $stylesheets['screen'][] = 'assets/vendor/summernote/dist/summernote-lite.css';
            
            //$stylesheets['screen'][] = 'assets/vendor/cropper/dist/cropper.min.css';
            
            $stylesheets['screen'][] = 'assets/vendor/rcrop/dist/rcrop.css';
            
            $view->stylesheets = $stylesheets;
            
            $scripts = $view->scripts;  
            $scripts['head'][] = 'assets/vendor/summernote/dist/summernote-lite.js';
            $scripts['head'][] = 'assets/vendor/flatpickr/dist/flatpickr.min.js'; 
            
            $scripts['head'][] = 'assets/vendor/rcrop/dist/rcrop.min.js'; 
              
            //$scripts['head'][] = 'assets/vendor/cropper/dist/cropper.min.js';    
            //$scripts['foot'][] = 'assets/js/components/hs.summernote-editor.js';   
            //$scripts['foot'][] = 'assets/js/components/hs.range-datepicker.js';  
            
            $view->scripts = $scripts;  
             
            $Category= new Category($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            $view->cateList = $Category->getListCate();
            
            $view->content = 'create';
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }


    ################################################################################
    public function editAction() 
    {
        try
        {
            $view = $this->basic();
            
            $login = $this->getLogin(); 
            
            if(empty($login)){      
                return $this->redirect()->toUrl('/'.$view->lang.'/create/?rd='.rand(100,1000).'&act=login');   
            }
            
            $Model= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            $detail = $Model->getDetail($view->id);
            $view->detail = $detail;  
            
            if(empty($detail)){         
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            if($view->Users->id != $detail['user_id']){
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));
            } 
            
            $name = $this->params()->fromPost('potname', '');
            $organizer = $this->params()->fromPost('organizer', '');
            $category_id = $this->params()->fromPost('category', '');
            $subcate_id = $this->params()->fromPost('subcategory', '');
            $event_date = $this->params()->fromPost('eventdate', ''); 
            $description = $this->params()->fromPost('description', '');
            
            $image = $this->params()->fromPost('image', '');
            $thumb = $this->params()->fromPost('thumb', '');
            
            $pid = $this->params()->fromPost('pid', '');
            
            $albums = $this->params()->fromPost('albums', '');
            $img_del = $this->params()->fromPost('img_del', '');
            
            
            $Albums = new Albums($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $view->albumList = $Albums->getListByPot($view->id);   
            
            if($view->act=='edit' && !empty($login) && !empty($name) && !empty($organizer) && !empty($category_id) && !empty($event_date)){ 
                    
                $data['name'] = $name; 
                $data['organizer'] = $organizer;
                $data['category_id'] = $category_id;
                $data['subcate_id'] = $subcate_id;
                $data['event_date'] = $event_date;  
                $data['description'] = $description;
                
                $rs = $Model->edit($data);
                 
                $filename = '';
                if(!empty($thumb)){
                    $thumb_img = $this->UploadS3Base64($thumb, 'covers');
                    if(!empty($thumb_img)){
                        $update['thumb'] = $thumb_img;
                        $Model->edit($update, $view->id); 
                        $this->DeleteS3('covers/'.$detail['thumb']);  
                    }  
                }    
                 
                if(!empty($image)){
                    $filename = $this->UploadS3Base64($image, 'covers');
                    if(!empty($filename)){
                        $Model->updateIMG($view->id, $filename);  
                        $this->DeleteS3('covers/'.$detail['img']); 
                    }  
                } 
                 
                /*
                if(!empty($_FILES['cover']['name']) && !empty($rs)){  
                    $filename = $this->UploadS3($_FILES['cover'], 'covers', 1200); 
                    if(!empty($filename)){
                        $Model->updateIMG($view->id, $filename);  
                        $this->DeleteS3('covers/'.$detail['img']); 
                    } 
    			} 
    			*/
    			
    			if(!empty($albums)){
					foreach($albums as $key=>$item){  
					    $album_img = $this->UploadS3Base64($item, 'albums');
                        if(!empty($album_img)){  
                            $insertData['image'] = $album_img;
                            $insertData['pot_id'] = $view->id; 
                            $Albums->add($insertData);     
                        }   
					}  
				} 
				 
				if(!empty($img_del)){
				    foreach($img_del as $key=>$item){   
				        $img_detail = $Albums->getDetail($item);
				        $Albums->del($img_detail['id']);  
                        $this->DeleteS3('albums/'.$img_detail['image']); 
				    } 
				}   
    			 
    			if(!empty($rs)){ 
    			    $rs = array('status'=>200,'items'=>($view->lang=='')?'คุณได้แก้ไขหม้อเงินเรียบร้อยแล้ว':'You have edit money pot successfully.');
    			}else{  
    			    $rs = array('status'=>400,'items'=> $view->error); 
    			}
    			print_r(json_encode($rs));  
    			exit;
            } 
            
            $stylesheets = $view->stylesheets;
            $stylesheets['screen'][] = 'assets/vendor/flatpickr/dist/flatpickr.min.css';
            $stylesheets['screen'][] = 'assets/vendor/summernote/dist/summernote-lite.css';
            
            //$stylesheets['screen'][] = 'assets/vendor/cropper/dist/cropper.min.css';
            
            $stylesheets['screen'][] = 'assets/vendor/rcrop/dist/rcrop.css';
            
            $view->stylesheets = $stylesheets;
            
            $scripts = $view->scripts;  
            $scripts['head'][] = 'assets/vendor/summernote/dist/summernote-lite.js';
            $scripts['head'][] = 'assets/vendor/flatpickr/dist/flatpickr.min.js'; 
            
            $scripts['head'][] = 'assets/vendor/rcrop/dist/rcrop.min.js'; 
              
            //$scripts['head'][] = 'assets/vendor/cropper/dist/cropper.min.js';    
            //$scripts['foot'][] = 'assets/js/components/hs.summernote-editor.js';   
            //$scripts['foot'][] = 'assets/js/components/hs.range-datepicker.js';  
            
            $view->scripts = $scripts;   
            
            
            
            
            
            
            
            $view->SEO = [  
                        'title'=>$detail['name'],   
                        'keywords'=>$detail['name'].','.$detail['organizer'],  
                        'description'=>mb_substr(strip_tags($detail['description']),0,200),
                        'image'=>$detail['image']
                        ];
            
            $Category= new Category($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $Subcategory= new Subcategory($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            $view->cateList = $Category->getListCate();
            $view->subcateList = $Subcategory->getListSubCate($detail['category_id']);
            
            /*
            echo "<pre>";
            print_r($view->subcateList);
            echo "</pre>";
            exit;  
            */
            
            
            
            $view->content = 'edit'; 
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }
    


    ################################################################################
    public function viewAction() 
    {
        try
        {
            $view = $this->basic();
            $login = $this->getLogin(); 
            if(empty($login)){      
                //return $this->redirect()->toUrl('/'.$view->lang.'/create/?rd='.rand(100,1000).'&act=login');   
            }
            $Model= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            if(empty($view->id)){         
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            if($view->act=='messages'){
                
                $Transactionsl= new Transactions($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
                $results = $Transactionsl->getMessageList($view->id);  
                print_r(json_encode(['data'=>$results['data'],'total'=>(int)$results['total'],'length'=>$view->pageshow]));
                exit;    
            }else if($view->act=='participants'){  
                
                $Transactionsl= new Transactions($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
                $results = $Transactionsl->getListByPot($view->id);   
                print_r(json_encode(['data'=>$results['data'],'total'=>(int)$results['total'],'length'=>$view->pageshow]));
                exit;    
            }
            
            $detail = $Model->getDetail($view->id);
            $view->detail = $detail; 
            //print_r($detail); exit; 
            if(empty($detail)){       
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            $view->menu_enable = false;
            if($view->Users->id == $detail['user_id']){
                $view->menu_enable = true;
            }
            $view->SEO = [  
                        'title'=>$detail['name'],   
                        'keywords'=>$detail['name'].','.$detail['organizer'],  
                        'description'=>mb_substr(strip_tags($detail['description']), 0, 300), 
                        'image'=>$detail['image']
                        ];
                        
            $Transactions= new Transactions($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $view->listRandom = $Transactions->getRandom($view->id); 
             
            /* 
            print_r($view->listRandom);
            exit;
            */ 
            $view->total_funds = true;
            if($detail['setting_total_funds']==2){
                $view->total_funds = false;
            }
            
            $view->deadline = true;
            if($detail['setting_deadline']!='0000-00-00'){  
                $current_date = strtotime(date("Y-m-d H:i:s"));
                $current_date = strtotime($detail['setting_deadline']." 23:59:59");
                if($current_date>$current_date){
                    $view->deadline = false; 
                }
                //$view->deadline = false; 
            }
            
            
            $stylesheets = $view->stylesheets;
            $stylesheets['screen'][] = 'assets/vendor/pagination/dist/pagination.min.css';
            $stylesheets['screen'][] = 'assets/vendor/jssocials/jssocials.css';
            $stylesheets['screen'][] = 'assets/vendor/jssocials/jssocials-theme-flat.css';
            $stylesheets['screen'][] = 'assets/vendor/OwlCarousel2/dist/assets/owl.carousel.min.css';
            $stylesheets['screen'][] = 'assets/vendor/OwlCarousel2/dist/assets/owl.theme.default.min.css';
            $stylesheets['screen'][] = 'assets/css/font-awesome.css';
            $view->stylesheets = $stylesheets;  
            
            $scripts = $view->scripts;     
            $scripts['head'][] = 'assets/vendor/pagination/dist/pagination.min.js';
            $scripts['head'][] = 'assets/vendor/jssocials/jssocials.js'; 
            $scripts['head'][] = 'assets/vendor/clipboard/dist/clipboard.min.js';
            $scripts['foot'][] = 'assets/vendor/OwlCarousel2/dist/owl.carousel.min.js';  
            $scripts['foot'][] = 'assets/js/view.js'; 
            $view->scripts = $scripts;      
             
            $Albums = new Albums($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $view->albumList = $Albums->getListByPot($view->id); 
            //print_r($view->albumList); exit;
            $view->content = 'view';  
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }



    ################################################################################
    public function settingAction() 
    {
        try
        {
            $view = $this->basic();
            $login = $this->getLogin(); 
            
            if(empty($login)){      
                return $this->redirect()->toUrl('/'.$view->lang.'/view/'.$view->view.'/?rd='.rand(100,1000));   
            } 
            
            $Model= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            if(empty($view->id)){         
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            $detail = $Model->getDetail($view->id);
            $view->detail = $detail;  
            
            if(empty($detail)){         
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            } 
            
            if($view->Users->id != $detail['user_id']){
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));
            } 
            
            $type_participation = $this->params()->fromPost('type_participation', 1);
            $total_fund = $this->params()->fromPost('total_fund', 1);
            $deadline = $this->params()->fromPost('deadline', '0000-00-00');
            $goal = $this->params()->fromPost('goal', 0);  
            $opne_invite = $this->params()->fromPost('opne_invite', 0);
            $hide_participarts_list = $this->params()->fromPost('hide_participarts_list', 0);
            
            if($view->act=='setting' && !empty($login) && !empty($type_participation) && !empty($total_fund)){ 
                  
                $data['setting_type_participation'] = $type_participation; 
                $data['setting_total_funds'] = $total_fund; 
                $data['setting_deadline'] = $deadline;  
                $data['setting_goal'] = $goal;  
                $data['setting_open_invite'] = !empty($opne_invite)?'1':'0';   
                $data['setting_hide_participarts_list'] = !empty($hide_participarts_list)?'1':'0';  
                //print_r($data); exit; 
                $rs = $Model->edit($data);  
                
    			if(!empty($rs)){ 
    			    $rs = array('status'=>200,'items'=>($view->lang=='')?'คุณได้ตั้งหม้อเงินสำเร็จแล้ว':'You have setting money pot successfully.');
    			}else{  
    			    $rs = array('status'=>400,'items'=> $view->error); 
    			}
    			print_r(json_encode($rs));  
    			exit;
            }
            $stylesheets = $view->stylesheets;
            $stylesheets['screen'][] = 'assets/vendor/flatpickr/dist/flatpickr.min.css';
            $view->stylesheets = $stylesheets;
            
            $scripts = $view->scripts;  
            $scripts['head'][] = 'assets/vendor/flatpickr/dist/flatpickr.min.js';
            $view->scripts = $scripts;
            
            $view->content = 'setting';
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }
 

    ################################################################################
    public function inviteAction() 
    {
        try
        {
            $view = $this->basic();
            $login = $this->getLogin(); 
            $subject = $this->params()->fromPost('subject', ''); 
            $email = $this->params()->fromPost('email', ''); 
            $remind = $this->params()->fromPost('remind', 0);
            
            if(empty($login)){      
                return $this->redirect()->toUrl('/'.$view->lang.'/create/?rd='.rand(100,1000).'&act=login');   
            }
            
            $Model= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            if(empty($view->id)){         
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            $detail = $Model->getDetail($view->id);
            $view->detail = $detail;  
            
            if(empty($detail)){         
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            if($view->Users->id != $detail['user_id']){
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));
            }
             
            if($view->act=='invite' && !empty($subject) && !empty($email)){ 
                
                $Invite = new Invite($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
                $email_arr = explode(",", $email);   
                $txt = file_get_contents($view->webURL.'/email/invite.html');  
                $id_name = !empty($detail['slugify'])?$detail['slugify']:$id;
                $link = $view->webURL.'/'.$view->lang.'/view/'.$id_name;  
                $link = '<a href="'.$link.'" target="_blank">Click here</a>';   
                $txt = preg_replace(array('/{name}/', '/{link}/'), array('Guest', $link), $txt);
                
                //$send = $this->sendMail($subject, 'LONGKHAN', $this->config['email_config']['email_contact'], ucfirst('Guest'), $email_arr, $txt);
                
                if(!empty($email_arr)){  
                    foreach($email_arr as $k=>$val){
                        $email = trim($val); 
                        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                            $iv['remind'] = $remind;
                            $iv['email'] = $email; 
                            $iv['subject'] = trim($subject);
                            $iv['pot_id'] = $view->id; 
                            $iv['user_id'] = $view->Users->id; 
                            $Invite->add($iv);  
                            $this->sendMail($subject, 'LONGKHAN', $this->config['email_config']['email_contact'], ucfirst('Guest'), $email, $txt);
                        }
                    }        
                    $return = ['status'=>200]; 
                }else{  
                    $return = ['status'=>404];
                } 
                
                print_r(json_encode($return));  
                exit;
            }
            
            $view->content = 'invite'; 
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }

    ################################################################################
    public function invitelistAction() 
    {
        try 
        { 
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            
           
             
            if(empty($login)){      
                return $this->redirect()->toUrl('/'.$view->lang.'/create/?rd='.rand(100,1000).'&act=login');   
            }
            
            $Model= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $Invite = new Invite($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            if(empty($view->id)){         
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            $detail = $Model->getDetail($view->id);
            $view->detail = $detail;  
            
            $invite_id = $this->params()->fromPost('invite', '');
            $task = $this->params()->fromPost('task', '');
            
            if(!empty($invite_id) && $task=='del'){
                $invite_id = implode(",",$invite_id);
                $Invite->delIn($invite_id);
                return $this->redirect()->toUrl('/'.$view->lang.'/invitelist/'.$view->id.'/?rd='.rand(100,1000));
            }else  if(!empty($invite_id) && $task=='invite'){ 
                
                $invite_id = implode(",",$invite_id);
                $data = $Invite->getInvite($invite_id); 
                if(!empty($data)){
                    
                    $txt = file_get_contents($view->webURL.'/email/invite.html');  
                    $id_name = !empty($detail['slugify'])?$detail['slugify']:$id;
                    $link = $view->webURL.'/'.$view->lang.'/view/'.$id_name;  
                    $link = '<a href="'.$link.'" target="_blank">Click here</a>';   
                    $txt = preg_replace(array('/{name}/', '/{link}/'), array('Guest', $link), $txt);
                    
                    foreach($data as $k=>$val){ 
                        $email = trim($val['email']); 
                        if (filter_var($val['email'], FILTER_VALIDATE_EMAIL)) {
                            $update['invite_date'] = date("Y-m-d H:i:s");  
                            $Invite->edit($update, $val['id']);    
                            $this->sendMail($val['subject'], 'LONGKHAN', $this->config['email_config']['email_contact'], ucfirst('Guest'), $email, $txt);
                        } 
                    }  
                }
                   
                return $this->redirect()->toUrl('/'.$view->lang.'/invitelist/'.$view->id.'/?rd='.rand(100,1000));
            }
            
            
            
            if(empty($detail)){         
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
             
            if($view->Users->id != $detail['user_id']){
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));
            } 
            
            $eid = $this->params()->fromQuery('eid', 0);
            //echo $eid;
            if($view->act=='del' && !empty($eid)){
                $Invite->del($eid);
            } 
              
            $view->emailList = $Invite->getListByPot($view->id);
            /*
            echo "<pre>";
            print_r($view->emailList);
            echo "</pre>";
            exit;
            */  
            if(!empty($view->emailList['total'])){      
                $pagination = new Pagination();   
                $pagination->setRPP($view->pageshow); 
                $pagination->setCurrent($view->page);  
                $pagination->setTotal($view->emailList['total']); 
                //$pagination->setTotal(200);   //test page   
                $view->pagination = $pagination->parse();  
            }   
            
            $view->content = 'inviteList';  
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }    
    
    
    ################################################################################
    public function forgotpassAction() 
    {
        try
        {
            $view = $this->basic();
            $login = $this->getLogin(); 
            if(!empty($login)){      
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            $email = $this->params()->fromPost('email', ''); 
            $password = $this->params()->fromPost('password', ''); 
            $token_post = $this->params()->fromPost('token', '');  
            
            $token = $this->params()->fromQuery('token', '');  
            $view->token = $token;   
            
            $Model= new Users($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            if($view->act=='forgotpass' && !empty($email)){     
                
                $id = $Model->checkLogin($email);   
                  
                if(empty($id)){
                    $rs = array('status'=>400,'items'=>($view->lang=='')?'ไม่พบอีเมลนี้ในระบบ':'This email cannot be found in the system.');
                }else{ 
                    $detail = $Model->getDetail($id); 
                    $name = $detail['name'];
                    $email = $detail['email']; 
                    $date = date("Y-m-d h:i:s", strtotime("+1 day"));
					$token = base64_encode($date.'&'.$email);
					$token = str_replace ( '=', 'lungkhan', $token); 
					$link = $view->webURL.'/'.$view->lang.'/forgotpass/?token='.$token.'&rd='.date("His");
					$link = '<a href="'.$link.'" target="_blank">Click here</a>';   
					$txt = file_get_contents($view->webURL.'/email/forgotPassword.html');     
					$txt = preg_replace(array('/{name}/', '/{link}/'), array($name, $link), $txt);
					$subject = 'You requested a password reset for the lungkhan.'; 
					
					$updateToken['token'] = $token;
					$Model->edit($updateToken, $id); 
					 
					$send = $this->sendMail($subject, 'LONGKHAN', $this->config['email_config']['email_contact'], $name, $email, $txt);
					  
					if($send==200){     
                        $rs = array('status'=>200,'items'=>($view->lang=='')?'รหัสผ่านใหม่ของคุณถูกส่งไปยังอีเมลของคุณแล้ว':'Your new password has been sent link to your email.');  
					}else{   
					    $rs = array('status'=>404,'items'=>$send);   
					} 
                }     
                print_r(json_encode($rs)); 
                exit;   
            }else  if($view->act=='newpass' && !empty($password) && !empty($email)  && !empty($token_post)){   
                $newpass = md5(trim($email).trim($password));   
                $updateData['password'] = $newpass;        
                $update = $Model->newPassword($updateData, trim($email), trim($token_post));    
                $rs = ['status'=>404,'items'=>$view->error];
                if(!empty($update)){
                    $rs = ['status'=>200,'items'=>($view->lang=='')?'บันทึกรหัสผ่านใหม่เรียบร้อยแล้ว':'The new password has been saved successfully.'];
                } 
                print_r(json_encode($rs));    
                exit;  
            }
            
            $time_now = strtotime(date("Y-m-d H:i:s"));   
             
            //if(empty($token)) return $this->redirect()->toRoute('home');  
            if(!empty($token)){   
                $token = str_replace('lungkhan','=', $token);   
                $token = base64_decode($token);     
                $token = explode('&',$token);     
                  
                $token_time = strtotime($token[0]);     
                $token_email = trim($token[1]); 
                $time_expire = false; 
                if($time_now < $token_time) $time_expire = true; 
                $view->token_email = $token_email;    
                $view->time_expire = $time_expire;  
            } 
            
            
            $view->content = 'forgotpass'; 
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }


    ################################################################################
    public function particpatesAction() 
    {
        try
        {
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            
            if(empty($login)){      
                return $this->redirect()->toUrl('/'.$view->lang.'/create/?rd='.rand(100,1000).'&act=login');   
            }
            
            $Model= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            if(empty($view->id)){         
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            } 
            
            $detail = $Model->getDetail($view->id);
            $view->detail = $detail;  
            
            if(empty($detail)){         
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            if($view->Users->id != $detail['user_id']){
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));
            } 
            $Transactions= new Transactions($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            if($view->act=='exportcsv'){
                $listData = $Transactions->getListByPot($view->id); 
                $data = array(array("Name", "Email", "Amount","Date"));
                foreach($listData['data'] as $key=>$val){
                    $data[] = array($val['name'], $val['email'], number_format($val['amount'],2), date("d M Y H:i", strtotime($val['createdate'])));
                }
                $this->outputCSV($data);  
                exit;
            }
            
            $view->listData = $Transactions->getListByPot($view->id); 
            if(!empty($view->listData['total'])){       
                $pagination = new Pagination();  
                $pagination->setRPP($view->pageshow); 
                $pagination->setCurrent($view->page);   
                $pagination->setTotal($view->listData['total']);  
                //$pagination->setTotal(200);   //test page  
                $view->pagination = $pagination->parse();  
            }    
            /*
            $stylesheets = $view->stylesheets;
            $stylesheets['screen'][] = 'assets/vendor/flatpickr/dist/flatpickr.min.css';
            $view->stylesheets = $stylesheets;
            
            $scripts = $view->scripts;  
            $scripts['head'][] = 'assets/vendor/datatables/media/js/jquery.dataTables.min.js';
            $scripts['foot'][] = 'assets/js/components/hs.datatables.js';
            */  
            
            
            $view->content = 'particpates'; 
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }
    
    
    
    

    ################################################################################
    public function contactinfoAction() 
    {
        try
        {
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            
            if(empty($view->id)){      
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            $Model= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            
            $detail = $Model->getDetail($view->id);
            $view->detail = $detail;  
            
            if(empty($detail)){         
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            /*
            if($view->Users->id != $detail['user_id']){
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));
            }  
            */ 
            
            $view->content = 'contactinfo'; 
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    } 


    ################################################################################
    public function transactionAction() 
    {
        try
        {
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            
            $view->fname = $this->params()->fromPost('fname', ''); 
            $view->lname = $this->params()->fromPost('lname', '');
            $view->email = $this->params()->fromPost('email', '');
            $view->amount = $this->params()->fromPost('amount', '');
            $view->type = $this->params()->fromPost('type', '');
            $token = $this->params()->fromPost('token', '');
            $return = $this->params()->fromQuery('rt', '');
            $tid = $this->params()->fromQuery('tid', '');  
            
            $view->banktype = $this->params()->fromPost('bank', '');
            
            $Model= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $Transactions= new Transactions($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $Omise = new Omise(); 
            $container = new Container('Contact');  
             
            if(!empty($return) && !empty($tid)){
                $detail = $Transactions->getDetail($tid);
                if(!empty($detail['charge_id'])){  
                    sleep(5);  
                    $charges = $Omise->chargesDetail($detail['charge_id']);
                    
                    $edit['transaction'] = $charges->transaction;
                    $edit['status'] = $charges->status;
                    $Transactions->edit($edit, $tid);
                    
                    unset($container->fullname);  
                    unset($container->email); 
                    unset($container->amount); 
                    unset($container->type); 
                    
                    if($charges->status=='successful'){
                        
                        $transaction = $Omise->getTransactions($charges->transaction);
                        $edit['fee'] = (($charges->amount-$transaction->amount)/100);
                        $edit['amount_total'] = ($transaction->amount/100); 
                        $Transactions->edit($edit, $tid); 
                        
                        $Tdetail = $Transactions->getDetail($tid); 
                        $detail = $Model->getDetail($Tdetail['pot_id']);
                        
                        $name = $Tdetail['name'];
                        $to_email = $Tdetail['email']; 
                        $bath = $Tdetail['amount']; 
                        
                        $potname = $detail['name']; 
                        $pot_email = $Tdetail['email']; 
                        
                         
                        $editM['total'] = $detail['total']+$Tdetail['amount']; 
                        $Model->edit($editM, $view->id);      
                         
    					$txt = file_get_contents($view->webURL.'/email/thankyou.html');      
    					$txt = preg_replace(array('/{name}/', '/{bath}/', '/{potname}/'), array($name, $bath, $potname), $txt);
    					$subject = 'Thank you for your contribution.'; 
    					$this->sendMail($subject, 'LONGKHAN', $this->config['email_config']['email_contact'], $name, $to_email, $txt);
                        
                        return $this->redirect()->toUrl('/'.$view->lang.'/thankyou/'.$view->id.'/?tid='.$tid.'&rd='.rand(100,1000));  
                        
                    }else{
                        
                        return $this->redirect()->toUrl('/'.$view->lang.'/contactinfo/'.$view->id.'/?error='.$charges->message.'&rd='.rand(100,1000));
                        
                    }
                    exit;
                } 
            }
            
            if(empty($token)){ 
                
                $detail = $Model->getDetail($view->id);
                $view->detail = $detail;  
                 
                if(empty($detail)){         
                    return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
                }  
            }
            
            
            
            if(!empty($view->fname) && !empty($view->email)  && !empty($view->amount)  && !empty($view->type)){
                 
                unset($container->fullname);  
                unset($container->email); 
                unset($container->amount); 
                unset($container->type);  
                 
                $container->fullname = $view->fname.' '.$view->lname;
                $container->email = $view->email; 
                $container->amount = $view->amount; 
                $container->type = $view->type;  
                 
                $view->fullname = $container->fullname;
                $view->email = $container->email; 
                $view->amount = $container->amount; 
                $view->type = $container->type;  
                
            }else{    
                
                $view->fullname = $container->fullname;
                $view->email = $container->email; 
                $view->amount = $container->amount; 
                $view->type = $container->type;  
                
            } 
             
            if(empty($view->fullname) || empty($view->email)  || empty($view->amount)  || empty($view->type)){      
                return $this->redirect()->toUrl('/'.$view->lang.'/contactinfo/'.$view->id.'/?rd='.rand(100,1000));   
            }
            
            
            $view->omise_config = $this->config['omise_config'];
            
            if(!empty($token) && !empty($view->amount) && $view->type==1){ 
                
                $tid = $Transactions->getNextId();
                $return_uri = $view->webURL.'/'.$view->lang.'/transaction/'.$view->id.'/?tid='.$tid.'&rt=complete&rd='.rand(100,1000); 
                $amount = $view->amount*100;    
                $desc = 'Charge ID: '.$tid.' for pot ID :'.$view->id;  
                $charges = $Omise->create($token, $amount, $desc, true, 'thb', $return_uri);  
               
                if(!empty($charges->id) && !empty($charges->authorize_uri)){
                    
                    $insertData['id'] = $tid;
                    $insertData['name'] = $view->fullname;
                    $insertData['email'] = $view->email;
                    $insertData['amount'] = $view->amount;
                    $insertData['type'] = $view->type; 
                    $insertData['charge_id'] = $charges->id;  
                    $insertData['status'] = $charges->status; 
                    $insertData['fee'] = 0;  
                    $insertData['amount_total'] = 0; 
                    $insertData['pot_id'] = $view->id; 
                    $insertData['reference'] = $charges->reference; 
                    $insertData['user_id'] = !empty($view->Users->id)?$view->Users->id:0;
                    $Transactions->add($insertData);   
                    return $this->redirect()->toUrl($charges->authorize_uri);
                }else{    
                    return $this->redirect()->toUrl('/'.$view->lang.'/contactinfo/'.$view->id.'/?error='.$charges->message);  
                }
                
            }else if($view->type==2 && !empty($view->banktype)){
                
                
                $tid = $Transactions->getNextId();
                $return_uri = $view->webURL.'/'.$view->lang.'/transaction/'.$view->id.'/?tid='.$tid.'&rt=complete&rd='.rand(100,1000); 
                $amount = $view->amount*100;    
                $desc = 'Charge ID: '.$tid.' for pot ID :'.$view->id;  
                $sources = $Omise->sources($amount, $view->banktype); 
                
                if(!empty($sources->id)){ 
                    
                    $source = $sources->id; 
                    $charges = $Omise->create_internet_banking($source, $amount, $view->banktype, $desc,  $currency = 'thb', $return_uri);  
                    
                    if(!empty($charges->id) && !empty($charges->authorize_uri)){
                        $insertData['id'] = $tid;
                        $insertData['name'] = $view->fullname;
                        $insertData['email'] = $view->email;
                        $insertData['amount'] = $view->amount;
                        $insertData['type'] = $view->type; 
                        $insertData['charge_id'] = $charges->id;  
                        $insertData['status'] = $charges->status; 
                        $insertData['fee'] = 0; 
                        $insertData['amount_total'] = 0; 
                        $insertData['pot_id'] = $view->id; 
                        $insertData['reference'] = $charges->reference; 
                        $insertData['user_id'] = !empty($view->Users->id)?$view->Users->id:0;
                        $Transactions->add($insertData);      
                        return $this->redirect()->toUrl($charges->authorize_uri);
                    }else{      
                        return $this->redirect()->toUrl('/'.$view->lang.'/contactinfo/'.$view->id.'/?error='.$charges->message);  
                    }
                }
            }
            
            //print_r($_POST);exit; 
            
            $view->content = 'transaction'; 
            return $view;           
        }    
        catch( Exception $e )
        {
            print_r($e);
        }
    }
    
    
    
################################################################################
    public function thankyouAction() 
    {
        try
        {
            $view = $this->basic(); 
            $tid = $this->params()->fromQuery('tid', '');
            $msg = $this->params()->fromPost('message', '');  
            
            $view->tid = $tid; 
              
            $Model= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $Transactions= new Transactions($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            if($view->act=='send_organizer' && !empty($msg)){ 
                
                $Tdetail = $Transactions->getDetail($tid);  
                $detail = $Model->getDetail($Tdetail['pot_id']);
                 
                $name = $Tdetail['name']; 
                $to_email = $Tdetail['email']; 
                $bath = $Tdetail['amount']; 
                
                $potname = $detail['name']; 
                $pot_email = $Tdetail['email'];
                
                $Transactions->edit(['message'=>$msg], $tid); 
                /* 
				$txt = file_get_contents($view->webURL.'/email/m-to-o.html');       
				$txt = preg_replace(array('/{name}/', '/{email}/', '/{msg}/'), array($name, $to_email, $msg), $txt); 
				$subject = $name.' send contact a message.';   
				
				$send = $this->sendMail($subject, 'LONGKHAN', $this->config['email_config']['email_contact'], $name, $pot_email, $txt);
				*/ 
				print_r(json_encode(['status'=>200]));  
                exit;  
            }  
            
            if(!empty($view->id)){
                $detail = $Model->getDetail($view->id);
                $view->detail = $detail; 
                if(empty($detail)){         
                    return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
                }  
            }
            
            if(empty($tid)){         
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }  
            
            $view->tdetail = $Transactions->getDetail($tid);
            //print_r($view->tdetail); exit;
            $view->content = 'thankyou';  
            return $view;           
        }    
        catch( Exception $e )
        {
            print_r($e);
        }
    }
     
    
    ################################################################################   
    public function getLogin()  
    {   
        $container = new Container('Users');
        $login = false; 
        $uid = $container->id; 
        if(!empty($uid)) 
        {
           $login = true; 
        }
        return $login;    
    }
    
    ################################################################################   
    public function setSession($session=array())  
    {  
        $container = new Container('Users'); 
        foreach( $session as $key=>$value )
        {
            $container->$key = $value;
            //$container->$key = preg_match('/^[a-z0-9]{32}$/', $container->$key);
        }  
    } 
    
   
################################################################################   
    public function logoutAction() 
    {  
        $view = $this->basic();
        $container = new Container('Users'); 
        foreach($container as $key=>$val){
            unset($container->$key); 
            print_r($key)."<br>";
        }
        /*
         print_r($container); 
        exit;
        unset($container->id); 
        unset($container->name);  
        $container->id = '';
        $container->name = '';  */ 
        //session_destroy();    
        return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));
    } 
    
    public function mypotAction() 
    {  
        try
        {
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            
            if(empty($login)){      
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            //echo $view->page; exit;
            
            $Moneypot= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $User = new Users($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $view->detail = $User->getDetail($view->Users->id);
            $view->potList = $Moneypot->getListByUser($view->Users->id);
            //$view->pagination = '';
            if(!empty($view->potList['total'])){     
                $pagination = new Pagination();   
                $pagination->setRPP($view->pageshow); 
                $pagination->setCurrent($view->page);  
                $pagination->setTotal($view->potList['total']); 
                //$pagination->setTotal(200);   //test page  
                $view->pagination = $pagination->parse(); 
            } 
             
            $view->content = 'mypot'; 
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        } 
    } 
    
    public function profileAction() 
    {  
        try
        {
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            $name = $this->params()->fromPost('name', '');
            $name_th = $this->params()->fromPost('name_th', '');
            $email = $this->params()->fromPost('email', '');
            
            if(empty($login)){      
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            $Moneypot= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $User = new Users($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            //print_r($_FILES['cover']); exit;
            if($view->act=='editprofile' && !empty($name)){ 
                $edit['name_en'] = $name;  
                $edit['name_th'] = $name_th; 
                //$edit['email'] = $email; 
                $User->edit($edit, $view->Users->id);  
                $detail = $User->getDetail($view->Users->id); 
                $this->setSession($detail);  
                echo json_encode(['status'=>200]);
                exit;
            }else if($view->act=='upload_file' && !empty($_FILES['image'])){
                //print_r($_FILES['image']); exit;    
                $filename = $this->UploadS3($_FILES['image'], 'users', 200); 
                if(!empty($filename)){         
                    $User->updateIMG($view->Users->id, $filename);  
                    $detail = $User->getDetail($view->Users->id); 
                    $this->setSession($detail);  
                    echo json_encode(['status'=>200]);
                }else{
                    echo json_encode(['status'=>404]);
                }   
                
                exit;
            }
            $view->detail = $User->getDetail($view->Users->id);
            $view->potList = $Moneypot->getListByUser($view->Users->id);
            
             
            $view->content = 'profile';  
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        } 
    } 
    
    
    public function transferfundAction() 
    {
        try
        {
            $view = $this->basic();
            $login = $this->getLogin(); 
            
            $cid = $this->params()->fromQuery('cid', ''); 
             
            if(empty($login)){      
                return $this->redirect()->toUrl('/'.$view->lang.'/create/?rd='.rand(100,1000).'&act=login');   
            }
            
            $Model= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            $Transferfund= new Transferfund($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            $detail = $Model->getDetail($view->id);
            $view->detail = $detail;  
            
            if(empty($detail)){         
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            if($view->Users->id != $detail['user_id']){
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));
            }
            if($detail['total']<=0){ 
                //return $this->redirect()->toUrl('/'.$view->lang.'/view/'.$view->id.'/?rd='.rand(100,1000)); 
            } 
             
            $name = $this->params()->fromPost('name', '');
            $type = $this->params()->fromPost('type', '');
            $country = $this->params()->fromPost('country', '');
            $acc_name = $this->params()->fromPost('acc_name', '');
            $acc_number = $this->params()->fromPost('acc_number', '');
            $bank_name = $this->params()->fromPost('bank_name', ''); 
             
            if($view->act=='transferfund' && !empty($name) && !empty($type)  && !empty($country)  && !empty($acc_name)  && !empty($acc_number) && !empty($acc_number)){
                 
                $Omise = new Omise(); 
                $post = array( 
                    'name'=>$name,
                    'description'=>$view->Users->name.'(User ID:'.$view->Users->id.')',
                    'type'=>$type, 
                    'bank_account'=>['brand'=>$bank_name, 'number'=>$acc_number, 'name'=>$acc_name]
                );
                
                $return = $Omise->apiService('recipients', $post);
                
                if(!empty($return->id)){
                    
                    $recipient_id = $return->id;
                    
                    $amount_net = (($detail['total']-$detail['service_charge'])+$view->transfer_fee);    
                     
                    $post2 = array(   
                        //'amount'=>($detail['amount_net']*100),
                        'amount'=>($amount_net*100), 
                        'recipient'=>$recipient_id
                    );  
                    
                    $return2 = $Omise->apiService('transfers', $post2);
                    
                    if(!empty($return2->id)){ 
                        
                        $insertDataT = array(
                            'name'=>$name, 
                            'bank_acc_country'=>$country, 
                            'bank_acc_name'=>$acc_name, 
                            'bank_acc_brand'=>$bank_name, 
                            'bank_acc_number'=>$acc_number,
                            'amount'=>$detail['total'],
                            'omise_recipient_id'=>$recipient_id,
                            'omise_transfer_id'=>$return2->id,
                            'pot_id'=>$view->id,  
                            'user_id'=>$view->Users->id,  
                            'transfer_amount'=>($return2->amount/100),
                            'transfer_fee'=>($return2->fee/100), 
                            'service_charge'=>$detail['service_charge']
                        );  
                        
                        $Transferfund->add($insertDataT);       
                        //$upd['total'] = ($detail['total']-(($return2->amount/100)+$detail['service_charge']));   
                        $upd['total'] = 0;//($detail['total']-($detail['amount_net']+$detail['service_charge']));   
                        $Model->edit($upd, $view->id);       
                            
                        $Users = new Users($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
                        $userDetail = $Users->getDetail($view->Users->id); 
                        $txt = file_get_contents($view->webURL.'/email/transferfund.html');  
                        $subject = 'You have transfer fund';  
                        $txt = preg_replace(array('/{name}/', '/{msg}/'), array($userDetail['name'], 'You have transfer fund status : In progress.'), $txt);
                        $send = $this->sendMail($subject, 'LONGKHAN', $this->config['email_config']['email_contact'], ucfirst($userDetail['name']), $userDetail['email'], $txt);
                            
                        $return = array('status'=>200);  
                    }else{  
                        $return = array('status'=>400, 'items'=>$return2->message);
                    } 
                    
                }else{ 
                    $return = array('status'=>400, 'items'=>$return->message);
                }  
                echo json_encode($return);  
                exit; 
            }else if($view->act=='banks'){ 
                $cid = !empty($cid)?$cid:1; 
                echo json_decode($this->config['banks'][$cid]);
                exit;
            } 
            
            $view->transfer_list = $Transferfund->getListByPot($view->id);
            $view->pagination = '';
            if(!empty($view->transfer_list['total'])){     
                $pagination = new Pagination();  
                $pagination->setRPP($view->pageshow); 
                $pagination->setCurrent($view->page);  
                $pagination->setTotal($view->transfer_list['total']); 
                //$pagination->setTotal(200);   //test page  
                $view->pagination = $pagination->parse(); 
            } 
            /*
            echo "<pre>"; 
            print_r($detail);
            echo "</pre>";
            exit;
             */
            $service_charge = $this->config['service_charge'];
            
            $view->banks = $this->config['banks']; 
            $view->content = 'transferfund';  
            return $view;
        }
        catch( Exception $e )
        { 
            print_r($e);
        }
    }
    
    
    public function managepotAction() 
    {
        try
        {
            $view = $this->basic();
            $login = $this->getLogin(); 
            
            $pid = $this->params()->fromPost('pid', '');
            
            if(empty($login)){      
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000).'&act=login');   
            }
            
            $Moneypot = new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $Transactions = new Transactions($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
             
            $detail = $Moneypot->getDetail($view->id);
            $view->detail = $detail; 
            
            if(empty($detail)){         
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));   
            }
            
            if($view->Users->id != $detail['user_id']){
                return $this->redirect()->toUrl('/'.$view->lang.'/?rd='.rand(100,1000));
            } 
            
            if($view->act=='delete'){ 
                $Transactions->delBypot($view->id);   
                $Moneypot->del($view->id);         
                $this->DeleteS3('covers/'.$detail['img']);   
                $this->DeleteS3('covers/'.$detail['thumb']);
                echo json_encode(['status'=>200]); 
            }else if($view->act=='close'){
                $edit['status'] = 2;     
                $Moneypot->edit($edit, $view->id);  
                echo json_encode(['status'=>200]);
            }
            exit;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }
    
    
    
    public function cronjobAction() 
    {
        try
        {
            $view = $this->basic();
            /*
            $Moneypot = new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $Transactions = new Transactions($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            */
            $Moneypot = new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $Transferfund = new Transferfund($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $Users = new Users($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $Omise = new Omise();
            
            if($view->act=='transferfund')
            { 
                $tff = $Transferfund->getTransfersStatus();
                if(!empty($tff)){ 
                    foreach($tff as $key=>$val){
                        
                        if($val['bank_verified']=='Pending'){
                            
                            $recipient = $Omise->apiService('recipients/'.$val['omise_recipient_id']); 
                            
                            if(!empty($recipient->verified) && !empty($recipient->active)){ 
                                $Transferfund->edit(['bank_verified'=>'Verified'], $val['id']); // Verified
                            } 
                            
                        }
                        
                        if($val['status'] != 3){ 
                            
                            $detail = $Moneypot->getDetail($val['pot_id']); 
                            $transfers = $Omise->apiService('transfers/'.$val['omise_transfer_id']); 
                            $userDetail = $Users->getDetail($val['user_id']); 
                            $txt = file_get_contents($view->webURL.'/email/transferfund.html');  
                            $subject = 'You have transfer fund';
                            
                            if(!empty($transfers->fail_fast)){ 
                                
                                $Transferfund->edit(['status'=>'0'], $val['id']); // Fail
                                $amount = (($transfers->amount + $transfers->fee) / 100);
                                 
                                $Moneypot->edit(['total'=>($detail['total']+$amount)], $val['pot_id']);
                                
                                $txt = preg_replace(array('/{name}/', '/{msg}/'), array($userDetail['name'], 'You have transfer fund status : Fail ('.$transfers->failure_code.') '.$transfers->failure_message.'.'), $txt); 
                                $send = $this->sendMail($subject, 'LONGKHAN', $this->config['email_config']['email_contact'], ucfirst($userDetail['name']), $userDetail['email'], $txt); 
                                
                            }else{ 
                                
                                if(!empty($transfers->sent)){  
                                    
                                    $Transferfund->edit(['status'=>'2'], $val['id']); // Send
                					$txt = preg_replace(array('/{name}/', '/{msg}/'), array($userDetail['name'], 'You have transfer fund status : Send.'), $txt);
                					
                                }  
                                
                                if(!empty($transfers->paid)){  
                                    
                                    $Transferfund->edit(['status'=>'3'], $val['id']); // Paid
                					$txt = preg_replace(array('/{name}/', '/{msg}/'), array($userDetail['name'], 'You have transfer fund status : Paid.'), $txt);
                					$send = $this->sendMail($subject, 'LONGKHAN', $this->config['email_config']['email_contact'], ucfirst($userDetail['name']), $userDetail['email'], $txt); 
                                }
                                
                            }  
                            
                            
                           
                            //print_r(json_encode($send));   
                        }  
                        
                         
                    } 
                    print_r(json_encode($tff)); 
                }
                
            } 
            exit;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }
    
    
    ################################################################################
    public function projectsAction() 
    {
        try
        {
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            
            $Moneypot= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            
            $view->listData = $Moneypot->getList(); 
            if(!empty($view->listData['total'])){     
                $pagination = new Pagination();   
                $pagination->setRPP($view->pageshow); 
                $pagination->setCurrent($view->page);  
                $pagination->setTotal($view->listData['total']);  
                $view->pagination = $pagination->parse(); 
            } 
            
            $view->content = 'projects'; 
            return $view; 
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }
    
    
    ################################################################################
    public function testAction() 
    {
        try  
        {
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            
            //echo "Test"; 
            $rs = $this->sendEmail('Subject Test', 'บอย เสรี', 'boyseree32@gmail.com', 'เสรี','boyatomic32@gmail.com', 'Boydy Test Send Email');
            print_r($rs);
            exit; 
            $view->content = 'howtowork'; 
            return $view; 
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }
    
    
    
	
    
    ################################################################################
    public function howtoworkAction() 
    {
        try  
        {
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            
            $view->content = 'howtowork'; 
            return $view; 
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }
    
    ################################################################################
    public function termsAction() 
    {
        try
        {
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            
            $view->content = 'terms'; 
            return $view; 
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }
    
    ################################################################################
    public function privacyAction() 
    {
        try
        {
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            
            $view->content = 'privacy'; 
            return $view; 
        }
        catch( Exception $e )
        {
            print_r($e);
            
        }
    }
    
    ################################################################################
    public function aboutAction() 
    {
        try
        {
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            
            $view->content = 'about'; 
            return $view; 
        } 
        catch( Exception $e )
        { 
            print_r($e);
        }
    }
    
    ################################################################################
    public function contactAction() 
    {
        try
        {
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            
            $name = $this->params()->fromPost('name', '');
            $email = $this->params()->fromPost('email', '');
            $subject = $this->params()->fromPost('subject', '');
            $message = $this->params()->fromPost('message', '');
            if($view->act=='contact' && !empty($name) && !empty($email)){
                
                $txt = "<h3>Contact Us</h3>";
                $txt .= "<p>From Name : ".$name."</p>";
                $txt .= "<p>Email : ".$email."</p>";
                $txt .= "<p>Subject : ".$subject."</p>";
                $txt .= "<p>Message : ".$message."</p>";
                $send = $this->sendMail($subject, $name,  $email, 'LONGKHAN', $this->config['email_config']['email_contact'], $txt); 
                if($send==200){   
                    $rs = ['status'=>200, 'items'=>($view->lang=='')?'ส่งอีเมลติดต่อเราสำเร็จ':'Send email contact us successfully.']; 
                }else{  
                    $rs = ['status'=>404, 'items'=>$send]; 
                }  
                print(json_encode($rs));    
                exit; 
            }
            $view->content = 'contact'; 
            return $view; 
        } 
        catch( Exception $e )
        { 
            print_r($e);
        }
    }
    
    
    
    ################################################################################
    public function categoryAction() 
    {
        try 
        {  
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            
            
            $stylesheets = $view->stylesheets;
            $stylesheets['screen'][] = 'assets/vendor/cubeportfolio/css/cubeportfolio.min.css';
            $view->stylesheets = $stylesheets; 
            
            $scripts = $view->scripts;   
            $scripts['head'][] = 'assets/vendor/cubeportfolio/js/jquery.cubeportfolio.min.js'; 
            $scripts['foot'][] = 'assets/js/components/hs.cubeportfolio.js'; 
            $view->scripts = $scripts;     
            
            
            
            $Category= new Category($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
             
            $view->CateList = $Category->getListCatePage(6);  
           
               
            $view->content = 'category'; 
            return $view; 
        } 
        catch( Exception $e )
        { 
            print_r($e);
        }
    }


    ################################################################################
    public function categorylistAction() 
    {
        try
        { 
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            if(empty($view->id)){      
                return $this->redirect()->toUrl('/'.$view->lang.'/category/?rd='.rand(100,1000).'&act=login');   
            }
            
            $Moneypot= new Moneypot($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $Subcategory= new Subcategory($this->adapter, $view->lang, $view->action, $view->id, $view->pagestart, $view->pageshow);
            $view->detail = $Subcategory->getDetail($view->id);  
            $view->listData = $Moneypot->getListByCate(null, $view->id);  
            if(!empty($view->listData['total'])){     
                $pagination = new Pagination();   
                $pagination->setRPP($view->pageshow); 
                $pagination->setCurrent($view->page);  
                $pagination->setTotal($view->listData['total']);  
                $view->pagination = $pagination->parse(); 
            }  
            
            $view->content = 'category-list'; 
            return $view; 
        }
        catch( Exception $e )
        {
            print_r($e);
        }
    }



    ################################################################################
    public function uploadsAction() 
    {
        try
        { 
            $view = $this->basic(); 
            $login = $this->getLogin(); 
            
            $image = $this->params()->fromPost('image', ''); 
            $pagename = $this->params()->fromPost('actionpage', 'general');
            $Model= new Images($this->adapter, $view->lang, $view->action, $view->id, 0, 50); 
             
            if($view->act=='new' && !empty($_FILES['image']['name'])){
                $filename = $this->UploadS3($_FILES['image'], 'general', 600); 
        		$id = $Model->getNextId();    
                $view->id = $id;     
                $data['id'] = $id;
                $data['user_id'] = !empty($view->Users->id)?$view->Users->id:0;  
                $data['page'] = $pagename;    
                $data['filename'] = $filename;     
                $Model->add($data);    
        		$pathImg = $view->urlFile.'/general/'.$filename;   
        		print_r($pathImg);  
        		exit;   
            }else if($view->act=='del' && !empty($image)){  
                $image2 = pathinfo($image);
        	    $detail = $Model->getDetailByImage($image2['basename']); 
        	    $del = 'general/'.$image2['basename'];
        	    if(!empty($detail['id'])){  
        	        $Model->del($detail['id']);
        	        $del = 'general/'.$image2['basename'];  
        	    } 
            	$this->DeleteS3($del);   
            	print_r(json_encode(['status'=>200,'file'=>$del])); 
            } 
            exit;  
        }
        catch( Exception $e )
        {
            print_r($e);
            exit; 
        }
    }































































################################################################################
    public function userAction() 
    {
        try 
        {
            $view = $this->basic();
            $act = $this->params()->fromQuery('act', '');
            $models = new Users($this->adapter, $view->id, $view->page);
            if($act == 'detail')
            {
                $view->data = $models->getList();
                $view->detail = $models->getDetail($view->id);
            }
            else if($act == 'add')
            {
                $name = $this->params()->fromPost('name');
                if($name) $models->add($name);
                $this->redirect()->toRoute('index', ['action'=>'user']);
            }
            else if($act == 'edit')
            {
                $name = $this->params()->fromPost('name');
                if($name) $models->edit($name);
                $this->redirect()->toRoute('index', ['action'=>'user']);
            }
            else if($act == 'del')
            {
                $models->del();
                $this->redirect()->toRoute('index', ['action'=>'user']);
            }
            else
            {
                $view->data = $models->getList();
            }
            return $view;
        }
        catch( Exception $e )
        {
            print_r($e);
        }
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
            $message->addFrom(trim($fromEmail), $fromName);
            $message->setSubject($subject);
             
            if(is_array($toEmail)){  
                foreach($toEmail as $k=>$val){ 
                    //echo trim($val)."<br>"; 
                    $message->addTo(trim($val), ucfirst(trim($toName))); 
                } 
                //exit;
            }else{    
                $message->addTo(trim($toEmail), "'".ucfirst(trim($toName))."'");
              
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
            /*
            $rs = $this->sendEmail($subject, $fromName, $fromEmail, $toName, $toEmail, $body); 
            if($rs['status']==200){
                return 200; 
            } else if($rs['status']==400){
                return $rs['message']; 
            }else{
                return htmlentities($e->getMessage());   
            }  */      
        }
    }
    
    
    function sendEmail($subject, $fromName, $fromEmail, $toName, $toEmail, $body, $bccName='', $bccEmail='')
	{ 
	    
	    $url = 'https://api.sendgrid.com/';
        $user = $this->config['email_config']['smtp_config']['connection_config']['username'];
        $pass = $this->config['email_config']['smtp_config']['connection_config']['password']; 
        if(is_array($toEmail)){  
            foreach($toEmail as $k=>$val){ 
                $to_email[] = ucfirst(trim($toName)).' <'.trim($val).'>';
            } 
        }else{
           $to_email[] = ucfirst(trim($toName)).' <'.$toEmail.'>'; 
        } 
        
        $json_string = array(  
          'to' =>$to_email,
        );
        
        if(!empty($this->config['email_config']['smtp_config']['cc_email'])){
            $json_string['cc'] = $this->config['email_config']['smtp_config']['cc_email'];
        }
        if(!empty($this->config['email_config']['smtp_config']['bcc_email'])){
            $json_string['bcc'] = $this->config['email_config']['smtp_config']['bcc_email'];
        }
        
        $params = array( 
            'api_user'  => $user,
            'api_key'   => $pass,
            'x-smtpapi' => json_encode($json_string), 
            'to'        => ucfirst(trim($toName)).' <'.$toEmail.'>',
            'subject'   => $subject,  
            'html'      => $body,
            'from'      => ucfirst(trim($fromName)).' <'.$fromEmail.'>',
          ); 
        
        //print_r($params); exit;
        $request =  $url.'api/mail.send.json';
        
        // Generate curl request
        $session = curl_init($request);
        // Tell curl to use HTTP POST
        curl_setopt ($session, CURLOPT_POST, true);
        // Tell curl that this is the body of the POST
        curl_setopt ($session, CURLOPT_POSTFIELDS, $params);
        // Tell curl not to return headers, but do return the response
        curl_setopt($session, CURLOPT_HEADER, false);
        // Tell PHP not to use SSLv3 (instead opting for TLS)
        curl_setopt($session, CURLOPT_SSLVERSION, CURL_SSLVERSION_TLSv1_2);
        curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
        
        // obtain response
        $response = curl_exec($session);
        curl_close($session);
         
        $response = json_decode($response, true);
        if($response['message']=='success'){
            $response = ['status'=>200,'message'=>$response['message']];
        }else{ 
            $response = ['status'=>400,'message'=>$response['errors'][0]];
        }    
        return $response;
	}
	
	
################################################################################
     /*
    public function UploadS3($FILES,  $folder='general', $resize=1200){ 
        $Config = $this->config['amazon_s3'];  
        $filename = ''; 
        $s3 = new S3Client($Config['config']);    
        // Upload an object to Amazon S3 
        $bucket = $Config['bucket'];//'starter-kit-rockstar';   
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
            
        } catch (S3Exception $e) {    
            // Catch an S3 specific exception.
            echo "<pre>";
            echo $e->getMessage();
            echo "</pre>";
            exit;    
        } 
    }
    */ 
    
    function UploadS3Base64($data_img,  $folder='general')
    {
        try   
        {
            ini_set('display_errors', 0); 
            if (!preg_match('/data:([^;]*);base64,(.*)/', $data_img, $matches)) {
                die("error");
            } 
             
            $content = str_replace('data:image/', '', $matches[0]);
            $content = str_replace('data:application/', '', $content);
            $content = explode(";", $content);
            $content = $content[0];
            $pname = md5(time().uniqid());
            $img_name = $pname.'.'.$content;
            $filenameext = $content;
            if($img_name) 
            {  
                $s3 = new S3Client($this->config['amazon_s3']['config']);    
                $bucket = $this->config['amazon_s3']['bucket'];
                
                $result = $s3->putObject(array( 
                    'Bucket' => $bucket,  
                    'Key' => $folder.'/'.$img_name,  
                    'ACL' => 'public-read',     
                    'SourceFile' => $data_img,    
                    'Expires'=> (string)(1000+(int)date("Y")),                       
                    'ContentType'=>'image/'.$filenameext,      
                )); 
            } 
            return($img_name);
            
        } catch (S3Exception $e) {    
            // Catch an S3 specific exception.
            echo "<pre>";
            echo $e->getMessage();
            echo "</pre>";
            exit;    
        } 
    }
    
    public function UploadS3($FILES,  $folder='general', $resize=1200){ 
        ini_set('display_errors', 0); 
        $Config = $this->config['amazon_s3']; 
        $filename = '';  
        $s3 = new S3Client($Config['config']);     
        // Upload an object to Amazon S3 
        $bucket = $Config['bucket'];//'starter-kit-rockstar';    
        try   
        {    
            $filename = explode(".", $FILES["name"]);  
            $filenameext = strtolower($filename[count($filename)-1]);
            $filename = md5(time().uniqid());
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
    
     
    public function DeleteS3($keyname=''){ 
        
        ini_set('display_errors', 0); 
        @unlink('public/img/'.$keyname);     
        
        if(!empty($keyname)){
            $s3 = new S3Client($this->config['amazon_s3']['config']);   
            // Upload an object to Amazon S3 
            $bucket = $this->config['amazon_s3']['bucket'];//'starter-kit-rockstar';
            
            $result = $s3->deleteObject(array( 
                'Bucket' => $bucket,
                'Key'    => $keyname
            ));
        } 
    }
    
    function slugify($name){ 
        //return preg_replace('/[^a-zA-Z0-9&-]/', '-', $name);
        $slug = preg_replace('@[\s!:;_\?=\\\+\*/%&#]+@', '-', $name);  
          //this will replace all non alphanumeric char with '-'
    	$slug = mb_strtolower($slug, 'UTF-8');
    	      //convert string to lowercase
    	$slug = trim($slug, '-');
    	return $slug;    
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
    
    
    
    
    
}