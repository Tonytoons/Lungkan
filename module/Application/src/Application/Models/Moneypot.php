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


//use Application\Models\Common;
   
class Moneypot extends Common
{  
    protected $admins;
    
    private $table = 'moneypot'; 
    private $up_forder = 'covers';   
    
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
    function getList()
    { 
        $sql = "SELECT *
                FROM ".$this->table." 
                WHERE 1  
                ORDER BY id DESC, name ASC  
                LIMIT ".$this->pageStart.", ".$this->perpage;
        
        $query = $this->adapter->query($sql);  
        $results = $query->execute(); 
        $resultSet = new ResultSet; 
        $data = $resultSet->initialize($results); 
        $data = $data->toArray(); 
        $items = array(); 
        
        foreach($data as $kay=>$value){
             
            $value['amount_total'] = $this->getSumMoneypot($value['id']);
            $value['slugify'] = $this->slugify($value['id'].'-'.$value['name']); 
            
            if(!empty($value['image'])){
               $value['image'] = $this->urlFile.'/'.$value['image'];
            }else{ 
                $value['image'] = $this->host.'/images/photo_not_available.jpg'; 
            }
            
            if(!empty($value['thumb'])){  
               $value['thumbnail'] = $this->urlFile.'/'.$value['thumb'];
            }else{  
                $value['thumbnail'] = $this->host.'/images/photo_not_available.jpg'; 
            }
            $value['participants'] = $this->getCountParticipants($value['id']); 
            $value['date_fomat'] = $this->dateFormat($value['createdate']);
            $items[] = $value;  
        }  
       
        $sql2 = "SELECT COUNT(id) AS C FROM ".$this->table." WHERE 1  LIMIT 1"; 
        $statement = $this->adapter->query($sql2);      
        $results = $statement->execute(); 
        $row = $results->current();     
        $output = array('items'=>$items,'total'=>$row['C']);
        return $output;  
    }
    
    
    function getListCate($limit=0)
    { 
        $LIMIT = '';
        if(!empty($limit)) $LIMIT = ' LIMIT '.$limit;
        
        $sql = "SELECT id, name_".$this->lang." AS name, image
                FROM ".$this->table." WHERE 1 AND status = '1' ORDER BY sort_order ASC".$LIMIT;    
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
            $value['participants'] = $this->getCountParticipants($value['id']); 
            $value['date_fomat'] = $this->dateFormat($value['createdate']);
            $items[] = $value; 
        }
        return $items;   
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
            $results = $statement->execute();  
            $result = $results->getGeneratedValue(); 
        }catch (\Exception $e) {   
            $result = '';//$e->getMessage(); 
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
       if(!empty(!$id)) $this->id = $id;
       
       $sql = "DELETE FROM `".$this->table."` WHERE id=".$this->id." LIMIT 1";
   	   $statement = $this->adapter->query($sql);  
   	   $statement->execute(); 
   	   
   	   $sql2 = "DELETE FROM `email_invite` WHERE pot_id=".$this->id." LIMIT 1";
   	   $statement2 = $this->adapter->query($sql2);  
   	   $statement2->execute();
   	   
   	   $sql3 = "DELETE FROM `transactions` WHERE pot_id=".$this->id." LIMIT 1";
   	   $statement3 = $this->adapter->query($sql3);  
   	   $statement3->execute();
   	   
   	   $sql4 = "DELETE FROM `transferfund` WHERE pot_id=".$this->id." LIMIT 1";
   	   $statement4 = $this->adapter->query($sql4);  
   	   $statement4->execute();  
   	   
       return true;     
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
        if(!empty($row)){
            
            $row['img'] = $row['image'];
            $row['amount_total'] = $this->getSumMoneypot($row['id']);
            $row['slugify'] = $this->slugify($row['id'].'-'.$row['name']); 
            
            if(!empty($row['image'])){   
               $row['image'] = $this->urlFile.'/'.$row['image'];
            }else{ 
                $row['image'] = $this->host.'/images/photo_not_available.jpg'; 
            }  
            $row['service_charge'] = $this->getServiceCharge($row['total']);  
            $row['amount_net'] = ($row['total'] - $row['service_charge']); 
            
            $row['participants'] = $this->getCountParticipants($row['id']); 
            if($row['setting_deadline']!='0000-00-00'){ 
                $row['day_diff'] = $this->day_diff($row['setting_deadline'], $this->now);
            }else{
                $row['day_diff'] = $this->day_diff($row['event_date'], $this->now);
            }
            $row['date_fomat'] = $this->dateFormat($row['createdate']);
        } 
        return $row;
    } 
    
    
    public function getSumMoneypot($id)
    { 
		$sql    = "SELECT SUM(amount) AS sum FROM `transactions` WHERE 1 AND pot_id = ".$id." AND status='successful' LIMIT 1";
   		$statement = $this->adapter->query($sql);     
        $results = $statement->execute();
        $row = $results->current();   
		$sum = !empty($row['sum'])?$row['sum']:0; 
		return $sum;    
	}
	
    
    function getListByUser($uid)
    {   
        $sql = "SELECT *
                FROM ".$this->table." 
                WHERE 1 AND user_id = ".$uid." 
                ORDER BY id DESC, name ASC  
                LIMIT ".$this->pageStart.", ".$this->perpage;
        
        $query = $this->adapter->query($sql);  
        $results = $query->execute(); 
        $resultSet = new ResultSet; 
        $data = $resultSet->initialize($results); 
        $data = $data->toArray(); 
        $items = array(); 
        
        foreach($data as $kay=>$value){
            
            $value['amount_total'] = $this->getSumMoneypot($value['id']);
            $value['slugify'] = $this->slugify($value['id'].'-'.$value['name']); 
            
            if(!empty($value['image'])){
               $value['image'] = $this->urlFile.'/'.$value['image'];
            }else{   
                $value['image'] = $this->host.'/images/photo_not_available.jpg'; 
            }
            
            if(!empty($value['thumb'])){  
               $value['thumbnail'] = $this->urlFile.'/'.$value['thumb'];
            }else{  
                $value['thumbnail'] = $this->host.'/images/photo_not_available.jpg'; 
            }
            $value['participants'] = $this->getCountParticipants($value['id']);
            $value['date_fomat'] = $this->dateFormat($value['createdate']); 
            $items[] = $value; 
        }  
       
        $sql2 = "SELECT COUNT(id) AS C FROM ".$this->table." WHERE 1 AND user_id = ".$uid; 
        $statement = $this->adapter->query($sql2);     
        $results = $statement->execute(); 
        $row = $results->current();     
        $output = array('items'=>$items,'total'=>$row['C']);
        return $output; 
    }
    
    
    function getServiceCharge($total_fund=0){
        
        $precen = $this->config['service_charge'];  
        $service_charge = $total_fund*($precen/100);
        return $service_charge; 
    }
     
    function getCountParticipants($id=0){ 
        $sql2 = "SELECT COUNT(DISTINCT(email)) AS C FROM `transactions` WHERE 1 AND status='successful' AND pot_id = ".$id."  LIMIT 1"; 
        $statement = $this->adapter->query($sql2);     
        $results = $statement->execute();  
        $row = $results->current();      
        return $row['C'];     
    }
    
    
    
    ################################################################################ 
    function getListByCate($cid='', $sid='')
    { 
        $Where = '';
        if(!empty($cid))
        {
            $Where .= " AND category_id=".$cid;
        }
        
        if(!empty($sid))
        {
            $Where .= " AND subcate_id=".$sid;
        } 
        
        
        $sql = "SELECT *
                FROM ".$this->table." 
                WHERE 1  ".$Where."
                ORDER BY id DESC, name ASC  
                LIMIT ".$this->pageStart.", ".$this->perpage;
        //echo $sql; exit;
         
        $query = $this->adapter->query($sql);  
        $results = $query->execute(); 
        $resultSet = new ResultSet; 
        $data = $resultSet->initialize($results); 
        $data = $data->toArray(); 
        $items = array(); 
        
        foreach($data as $kay=>$value){
             
            $value['amount_total'] = $this->getSumMoneypot($value['id']);
            $value['slugify'] = $this->slugify($value['id'].'-'.$value['name']); 
            
            if(!empty($value['image'])){
               $value['image'] = $this->urlFile.'/'.$value['image'];
            }else{ 
                $value['image'] = $this->host.'/images/photo_not_available.jpg'; 
            }
            
            if(!empty($value['thumb'])){  
               $value['thumbnail'] = $this->urlFile.'/'.$value['thumb'];
            }else{  
                $value['thumbnail'] = $this->host.'/images/photo_not_available.jpg'; 
            }
            $value['participants'] = $this->getCountParticipants($value['id']); 
            $value['date_fomat'] = $this->dateFormat($value['createdate']);
            $items[] = $value;  
        }  
       
        $sql2 = "SELECT COUNT(id) AS C FROM ".$this->table." WHERE 1 ".$Where." LIMIT 1"; 
        $statement = $this->adapter->query($sql2);      
        $results = $statement->execute(); 
        $row = $results->current();     
        $output = array('items'=>$items,'total'=>$row['C']);
        return $output;  
    }
    
    
    function getBoy()
    {
        return $this->getTest();
    }
    
}
    