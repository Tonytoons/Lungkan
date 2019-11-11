<?php
namespace Application\Models;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;
use Zend\Cache\StorageFactory;
use Zend\Cache\Storage\Adapter\Memcached;
use Zend\Cache\Storage\StorageInterface;

use Zend\Db\Sql\Sql;
    
class Users 
{ 
    protected $admins;  
    private $table = 'users';
    private $up_forder = 'users';   
    
################################################################################ 
    function __construct($adapter, $inLang, $inAction, $inID=0, $pageStart=0, $perpage=21) 
    {
        $this->cacheTime = 360;
        $this->lang = $inLang; 
        $this->action = $inAction;
        $this->id = $inID; 
        $this->adapter = $adapter;
        //$this->page = $inPage;
        $this->perpage = $perpage;  
        $this->pageStart = $pageStart;//($this->perpage*($this->page-1));
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
        $this->host  = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'];
        $this->config = include __DIR__ . '../../../../config/module.config.php';
        $this->urlFile = $this->config['amazon_s3']['urlFile'].'/'.$this->up_forder;
    } 

################################################################################ 
    function getList($search='')
    {
        $sql = "SELECT 
                    id
                    ,view
                    ,img
                    ,active
                    ,name_".$this->lang." AS name
                    ,last_update
                FROM `blog` 
                WHERE 1 AND name LIKE '%".$search."%' OR detail LIKE '%".$search."%'
                ORDER BY id DESC 
                LIMIT ".$this->pageStart.", ".$this->perpage;
        $query = $this->adapter->query($sql);
        $results = $query->execute(); 
        $resultSet = new ResultSet;
        $data = $resultSet->initialize($results); 
        $data = $data->toArray(); 
       
        $sql2 = "SELECT COUNT(id) AS C FROM `blog` WHERE 1 AND name LIKE '%".$search."%' OR detail LIKE '%".$search."%'"; 
        $statement = $this->adapter->query($sql2);     
        $results = $statement->execute();
        $row = $results->current();    
        $output = array('data'=>$data,'total'=>$row['C']);
        return $output; 
    } 
 
    function add($dataInsert)   
    { 
        try{ 
            $dataInsert['createdate'] = $this->now;
            $dataInsert['lastupdate'] = $this->now; 
            $adapter = $this->adapter;  
            $sql = new Sql($adapter); 
            $insert = $sql->insert($this->table);    
            $insert->values($dataInsert);         
            $statement = $sql->prepareStatementForSqlObject($insert); 
            $result = $statement->execute();  
        }catch (\Exception $e) { 
            $result = $e->getMessage(); 
        }  
        return $result;  
    } 

    function edit($dataUpdate, $id='') 
    { 
        try{ 
            
            if(!empty($id)) $this->id = $id;
            $adapter = $this->adapter; 
            $sql = new Sql($adapter);  
            $update = $sql->update($this->table);   
            $update->set($dataUpdate);  
            $update->where(array('id' => $this->id));  
            $statement = $sql->prepareStatementForSqlObject($update); 
            $result = $statement->execute(); 
        }catch (\Exception $e) { 
            $result = $e->getMessage(); 
        } 
        return($result); 
    }
     
   
    function del()
    { 
       /*  
       $detail = $this->getDetail($this->id);
       if(!empty($detail['img'])){ 
         $pathDelete = 'public/img/blog/'.$detail['img'];      
         @unlink($pathDelete);  
       }  */
       $sql    = "DELETE FROM `".$this->table."` WHERE id=".$this->id." LIMIT 1";
   	   $statement = $this->adapter->query($sql);
   	   //$this->delImgBlogAll();      
       return $statement->execute();    
    }
    
    
    public function getNextId() 
    { 
		$sql    = "SELECT MAX(id) + 1 AS id FROM `".$this->table."` LIMIT 1";
   		$statement = $this->adapter->query($sql);     
        $results = $statement->execute();
        $row = $results->current();  
		$id     = $row['id'];    
		if($id == NULL) $id = '1';
		return ( $id ); 
	}
	
	
	
	function updateIMG($id, $imgName) 
    { 
        $sql = "UPDATE `".$this->table."`
                SET image='".$imgName."'  
                WHERE id=".$id;    
        $sql = $this->adapter->query($sql); 
        return($sql->execute());
    }
    
    

    function getDetail($id=0) 
    {  
        $sql = "SELECT *, name_".$this->lang." AS name FROM `".$this->table."` WHERE id=".$id." LIMIT 1";  
        $statement = $this->adapter->query($sql);        
        $results = $statement->execute(); 
        $row = $results->current(); 
        unset($row['password']);
        if(!empty($row['image'])){     
           $row['image'] = $this->urlFile.'/'.$row['image'];
        }else if(!empty($row['line_userId'])){   
           $row['image'] = $row['line_pictureUrl']; 
        }else if(!empty($row['facebook_id'])){   
           $row['image'] = 'http://graph.facebook.com/'.$row['facebook_id'].'/picture?type=normal';
        }else{ 
            $row['image'] = $this->host.'/assets/img/avatar-100.jpg';  
        }  
        return $row; 
    }
    
    function checkRegister($email='', $facebook_id='')  
    {  
        try{  
           $where = "AND email='".$email."'";
           
           if(!empty($facebook_id)) $where .= " AND facebook_id='".$facebook_id."'";
           
           $sql = "SELECT COUNT(id) AS C FROM `".$this->table."` WHERE 1 ".$where;
           $statement = $this->adapter->query($sql);     
           $results = $statement->execute();
           $row = $results->current();  
           
           return $row['C'];  
            
        }catch (\Exception $e) {  
            return $e->getMessage();
        } 
    }
    
    function checkLogin($email='', $password='', $facebook_id="")  
    {  
        try{ 
           $where = "AND email='".$email."'";
           if(!empty($password)) $where .= " AND password='".md5($email.$password)."'"; 
           if(!empty($facebook_id)) $where .= " AND facebook_id='".$facebook_id."'"; 
           
           $sql = "SELECT id FROM `".$this->table."` WHERE 1 ".$where;
           $statement = $this->adapter->query($sql);     
           $results = $statement->execute();
           $row = $results->current(); 
           return !empty($row['id'])?$row['id']:0;     
        }catch (\Exception $e) {   
            return $e->getMessage();
        } 
    }
    
    
    function newPassword($dataUpdate, $email='', $token='') 
    { 
        try{  
            
            if(!empty($this->id))$where['id'] = $this->id;
            
            if(!empty($email))$where['email'] = $email; 
            
            if(!empty($token))$where['token'] = $token; 
            
            if(!empty($where)){ 
                
                $dataUpdate['lastupdate'] = $this->now; 
                $adapter = $this->adapter;  
                $sql = new Sql($adapter);  
                $update = $sql->update($this->table);   
                $update->set($dataUpdate);  
                $update->where($where);   
                $statement = $sql->prepareStatementForSqlObject($update); 
                $result = $statement->execute(); 
                
            }else{
                $result = [];
            }
        }catch (\Exception $e) { 
            $result = $e->getMessage(); 
        } 
        return($result); 
    }
    
    function checkLineLogin($line_id='')  
    {   
        try{
            
           if(empty($line_id)) return 0; 
           
           $where = " AND line_userId='".$line_id."'"; 
           $sql = "SELECT id FROM `".$this->table."` WHERE 1 ".$where;
           //echo $sql; exit;
           $statement = $this->adapter->query($sql);     
           $results = $statement->execute();
           $row = $results->current();  
           return !empty($row['id'])?$row['id']:0;  
        }catch (\Exception $e) {   
            return $e->getMessage();
        } 
    }
    
################################################################################ 
}
    