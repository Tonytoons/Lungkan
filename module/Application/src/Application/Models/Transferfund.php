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
 
class Transferfund  extends Common 
{ 
    protected $admins; 
    
    private $table = 'transferfund';
    private $up_forder = 'transferfund';
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
        $this->urlFile = $this->config['amazon_s3']['urlFile'].'/'.$this->up_forder; 
        $this->host  = "http" . (($_SERVER['SERVER_PORT'] == 443) ? "s://" : "://") . $_SERVER['HTTP_HOST'];
    } 

################################################################################ 
    function getList($search='')
    {
        $sql = "SELECT *
                FROM ".$this->table." 
                WHERE 1 AND name LIKE '%".$search."%'
                ORDER BY sort_order, id DESC 
                LIMIT ".$this->pageStart.", ".$this->perpage;
        $query = $this->adapter->query($sql);  
        $results = $query->execute(); 
        $resultSet = new ResultSet;
        $data = $resultSet->initialize($results); 
        $items = $data->toArray(); 
        /*
        $items = array(); 
        
        foreach($data as $kay=>$value){
            
            if(!empty($value['image'])){
               $value['image'] = $this->urlFile.'/'.$value['image'];
            }else{ 
                $value['image'] = $this->host.'/images/photo_not_available.jpg'; 
            }
            $items[] = $value; 
        }
        */
        $sql2 = "SELECT COUNT(id) AS C FROM ".$this->table." WHERE 1 AND name LIKE '%".$search."%'"; 
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
            $insertData['lastupdate'] = $this->now;   
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

    function edit($dataUpdate, $id='') 
    { 
         try{ 
            if(!empty($id)) $this->id = $id;
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
    
    
    function del($id='')
    {  
       if(!empty($id)) $this->id = $id;
       $sql    = "DELETE FROM `".$this->table."` WHERE id=".$this->id." LIMIT 1";
   	   $statement = $this->adapter->query($sql);      
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
        $sql = "SELECT * FROM ".$this->table." WHERE id=".$id." LIMIT 1";  
        $statement = $this->adapter->query($sql);       
        $results = $statement->execute();
        $row = $results->current();
        return $row;
    }
    
    
    function delBypot($id='')
    {  
       if(!empty(!$id)) $this->id = $id;
       $sql    = "DELETE FROM `".$this->table."` WHERE pot_id = ".$id." LIMIT 1";
   	   $statement = $this->adapter->query($sql);      
       return $statement->execute();    
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
                $value['bank_name'] = $this->config['banks'][$value['bank_acc_country']][$value['bank_acc_brand']];
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
    
    
    function getTransfersStatus()
    {  
        try{  
            $sql = "SELECT id, name, user_id, omise_recipient_id, omise_transfer_id, status, bank_verified, pot_id 
                    FROM ".$this->table." 
                    WHERE 1 AND status != '3' AND status != '0'
                    ORDER BY id DESC";  
            $query = $this->adapter->query($sql);  
            $results = $query->execute(); 
            $resultSet = new ResultSet;
            $data = $resultSet->initialize($results); 
            $output = $data->toArray(); 
            return $output; 
        }catch (\Exception $e) {  
            return $e->getMessage(); 
        } 
    } 
    

################################################################################ 
}
    