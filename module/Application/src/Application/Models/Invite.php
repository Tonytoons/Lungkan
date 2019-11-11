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
    
class Invite extends Common
{ 
    protected $admins;  
    private $table = 'email_invite';
    private $up_forder = 'invite';    
    private $config;
    
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
        $this->config = include __DIR__ . '../../../../config/module.config.php';
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
                    ,name
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
            $dataInsert['invite_date'] = $this->now;  
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
            
            $dataUpdate['lastupdate'] = $this->now; 
            
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
     
   
    function del($id='')
    { 
       /*  
       $detail = $this->getDetail($this->id);
       if(!empty($detail['img'])){ 
         $pathDelete = 'public/img/blog/'.$detail['img'];      
         @unlink($pathDelete);  
       }  */
       //echo $id; exit;
       if(!empty($id)) $this->id = $id; 
       $sql  = "DELETE FROM `".$this->table."` WHERE id=".$this->id." LIMIT 1";
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
        $sql = "SELECT * FROM `".$this->table."` WHERE id=".$id." LIMIT 1";  
        $statement = $this->adapter->query($sql);       
        $results = $statement->execute();
        $row = $results->current(); 
        unset($row['password']);
        if(!empty($row['image'])){    
           $row['image'] = $this->urlFile.'/'.$row['image'];
        }else{ 
            $row['image'] = $this->host.'/assets/img/avatar-100.jpg';  
        }  
        return $row; 
    }
    
    
    
    function getListByPot($id)
    {
        try{ 
            $sql = "SELECT *
                    FROM ".$this->table." 
                    WHERE 1 AND pot_id = ".$id."
                    ORDER BY id DESC 
                    LIMIT ".$this->pageStart.", ".$this->perpage;
            $query = $this->adapter->query($sql);  
            $results = $query->execute(); 
            $resultSet = new ResultSet;
            $data = $resultSet->initialize($results); 
            $data = $data->toArray(); 
            $items = array(); 
            
            foreach($data as $kay=>$value){ 
                $value['invited'] = $this->invited($value['email'], $id); 
                $items[] = $value; 
            } 
            
            $sql2 = "SELECT COUNT(id) AS C FROM ".$this->table." WHERE 1  AND pot_id = ".$id; 
            $statement = $this->adapter->query($sql2);     
            $results = $statement->execute();
            $row = $results->current();     
            $output = array('data'=>$items,'total'=>$row['C']);
            return $output; 
        }catch (\Exception $e) { 
            return $e->getMessage(); 
        } 
    }
    
    
    function invited($email, $pid){
        $where = "AND email='".$email."' AND pot_id=".$pid;
        $sql = "SELECT COUNT(id) AS C FROM `transactions` WHERE 1 ".$where;
        $statement = $this->adapter->query($sql);     
        $results = $statement->execute();
        $row = $results->current(); 
       return $row['C']; 
    }
    
    
    function delIn($id='')
    { 
       $sql  = "DELETE FROM `".$this->table."` WHERE id IN (".$id.")";
       //echo $sql; exit;
   	   $statement = $this->adapter->query($sql); 
       return $statement->execute();    
    }
    
    
    function getInvite($id='')
    {  
       $sql  = "SELECT id, email, subject FROM `".$this->table."` WHERE id IN (".$id.")";
   	   $query = $this->adapter->query($sql);  
       $results = $query->execute(); 
       $resultSet = new ResultSet;
       $data = $resultSet->initialize($results); 
       return $data->toArray();    
    }
    
    
################################################################################ 
}
    