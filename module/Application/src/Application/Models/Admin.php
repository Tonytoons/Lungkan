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
 
class Admin
{ 
    protected $admins;  
    
    private $table = 'admin';  
    private $up_forder = 'admin';   
    
################################################################################ 
	function __construct($adapter, $inLang, $inAction,  $inID=0, $pageStart=0, $perpage=21) 
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
                    ,level
                    ,image
                    ,status
                    ,name
                    ,email
                    ,createdate
                FROM admin 
                WHERE 1 AND name LIKE '%".$search."%' OR email LIKE '%".$search."%'
                ORDER BY id DESC 
                LIMIT ".$this->pageStart.", ".$this->perpage;
        $query = $this->adapter->query($sql);
        $results = $query->execute(); 
        $resultSet = new ResultSet;
        $data = $resultSet->initialize($results); 
        $data = $data->toArray(); 
        $items = array();
        foreach($data as $kay=>$value){
            
            if(!empty($value['image'])){
               $value['image'] = $this->urlFile.'/'.$value['image'];
            }else{ 
                $value['image'] = $this->host.'/images/photo_not_available.jpg'; 
            }
            $items[] = $value;  
        }
       
        $sql2 = "SELECT COUNT(id) AS C FROM admin WHERE 1 AND name LIKE '%".$search."%' OR email LIKE '%".$search."%'"; 
        $statement = $this->adapter->query($sql2);     
        $results = $statement->execute();
        $row = $results->current();   
        $output = array('data'=>$items,'total'=>$row['C']);
        return $output; 
    }
 
    function add($insertData)  
    {  
        try{
            $insertData['createdate'] = $this->now;
            $adapter = $this->adapter;  
            $sql = new Sql($adapter); 
            $insert = $sql->insert($this->table);    
            $insert->values($insertData);            
            $statement = $sql->prepareStatementForSqlObject($insert); 
            $result = $statement->execute();  
        }catch (\Exception $e) { 
            $result = $e->getMessage(); 
        }  
        return $result; 
    } 

    function edit($dataUpdate) 
    { 
         try{ 
            $dataUpdate['lastupdate'] = $this->now;
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
     
    function updatePassword($password) 
    { 
        $detail = $this->getDetail($this->id);  
        $sql = "UPDATE `admin` 
                SET password='".md5($detail['email'].$password)."' 
                WHERE id=".$this->id;  
        $sql = $this->adapter->query($sql); 
        return($sql->execute());
    }
    
    function del()
    { 
        /*
       $detail = $this->getDetail($this->id);
       if(!empty($detail['image'])){ 
         $pathDelete = 'public/img/admin/'.$detail['image'];    
         @unlink($pathDelete);  
       } */ 
       $sql    = "DELETE FROM `admin` WHERE id=".$this->id." LIMIT 1";
   	   $statement = $this->adapter->query($sql);      
       return $statement->execute();    
    }
    
    
    public function getNextId()
    { 
		$sql    = "SELECT MAX(id) + 1 AS id FROM `admin` LIMIT 1";
   		$statement = $this->adapter->query($sql);     
        $results = $statement->execute();
        $row = $results->current();  
		$id     = $row['id'];   
		if($id == NULL) $id = '1';
		return ( $id ); 
	}
    
    function updateIMG($id, $imgName) 
    { 
        $sql = "UPDATE `admin` 
                SET image='".$imgName."'  
                WHERE id=".$id;    
        $sql = $this->adapter->query($sql); 
        return($sql->execute());
    }

    function getDetail($id=0)
    { 
        $sql = "SELECT * FROM admin WHERE id=".$id." LIMIT 1";  
        $statement = $this->adapter->query($sql);       
        $results = $statement->execute();
        $row = $results->current();
        $row['img'] = $row['image'];
        if(!empty($row['image'])){  
           $row['image'] = $this->urlFile.'/'.$row['image'];
        }else{ 
            $row['image'] = $this->host.'/images/photo_not_available.jpg'; 
        }
        return $row;
    } 
    
    function checkEmail($email='')    
    {    
       $return = "false";  
       if(!empty($email)){  
            $sql = "SELECT COUNT(id) AS C FROM `admin` WHERE 1 AND email ='".$email."'";  
            $statement = $this->adapter->query($sql);     
            $results = $statement->execute();
            $row = $results->current(); 
            if($row['C']==0)$return = "true"; 
       }  
       return $return; 
    }














################################################################################ 
}
    