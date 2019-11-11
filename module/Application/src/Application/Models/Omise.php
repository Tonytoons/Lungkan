<?php
namespace Application\Models; 
 
class Omise { 
    
    var $_api_url = 'https://api.omise.co/';
	var $pkey, $skey;
	
	public function __construct()
	{ 
	    $this->config = include __DIR__ . '../../../../config/module.config.php';
	    $this->pkey = $this->config['omise_config']['public_key']; 
		$this->skey = $this->config['omise_config']['secret_key'];
		$this->_api_url = $this->config['omise_config']['api_url'];  
	} 
	 
	public function init($publish_key, $secret_key){
		$this->pkey = $publish_key;
		$this->skey = $secret_key;
	}
	
	public function create($card_token, $amount, $description = null, $capture = true, $currency = 'thb', $return_uri = null){
		$post = array(
					'amount' 	=> $amount,
					'card'		=> $card_token,
					'currency'	=> $currency,
					'capture'	=> $capture,
					//'return_uri' => 'https://lungkan-tonytoons.c9users.io/th/transaction/5/'
				); 
				
		if($description != null){
			$post['description'] = $description;
		}
		if($return_uri != null){
			$post['return_uri'] = $return_uri;
		}
		return $this->postCurl('charges', $post);
	}
	
	public function capture($charge_id){
		$capture_path = 'charges/'.$charge_id."/capture";
		return $this->getCurl($capture_path); 
	}
	
	public function chargesDetail($charge_id){
		$capture_path = 'charges/'.$charge_id;
		return $this->getCurl($capture_path);
	}
	
	function sources($amount, $type = 'internet_banking_scb', $currency='thb')
	{
	    $post = array( 
					'amount' 	=> $amount,
					'currency'	=> $currency,
					'type'	=> $type 
				); 
				
		return $this->postCurl('sources', $post);
	}
	
	
	public function create_internet_banking($source, $amount, $type = 'internet_banking_scb', $description = null,  $currency = 'thb', $return_uri = null){
		 
		$post = array(
		            'source'=>$source,
					'amount' 	=> $amount,
					'currency'	=> $currency
				); 
		 		
		if($description != null){
			$post['description'] = $description;
		}
		if($return_uri != null){
			$post['return_uri'] = $return_uri;
		}
		return $this->postCurl('charges', $post);
	}
	
	
	function getTransactions($transactions_id){
	    $capture_path = 'transactions/'.$transactions_id;
		return $this->getCurl($capture_path);
	}
	
	
	
	function apiService($service='charges', $post_array=[])
	{
		
		if(!empty($post_array))
		{
			$output = $this->postCurl($service, $post_array);	
		}
		else
		{
			$output	= $this->getCurl($service); 
		} 
		return $output;
	}
	
	
	private function getCurl($command='charges')
	{
	    $gurl = $this->_api_url.$command;
	    $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $gurl); 
        curl_setopt($ch, CURLOPT_USERPWD, $this->skey.":");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        try{
            $return = curl_exec($ch);
        }catch (Exception $e){
            echo " error : ".$e->getMessage();
        } 
        curl_close($ch);
        return json_decode($return); 
	}
	
	private function postCurl($command='charges', $post_array = array())
	{
		//prepare url
		$gurl = $this->_api_url.$command;
		
		//prepare post value
		$qst = http_build_query($post_array);
		//header
        $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg'; 
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8'; 

        //init
        $process = curl_init($gurl); 
 
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_ENCODING , 'gzip');
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POSTFIELDS, $qst);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_USERPWD, $this->skey.":");
        try{
            $return = curl_exec($process);
        }catch (Exception $e){
            $return = $e->getMessage();
        } 
        curl_close($process);
        return json_decode($return);
	}
	
	
	/*
	private function _exeCurl($command = 'charges', $post_array = array()){
		//prepare url
		$gurl = $this->_api_url.$command;
		
		//prepare post value
		$qst = http_build_query($post_array);
		//header
        $headers[] = 'Accept: image/gif, image/x-bitmap, image/jpeg, image/pjpeg'; 
        $headers[] = 'Connection: Keep-Alive';
        $headers[] = 'Content-type: application/x-www-form-urlencoded;charset=UTF-8'; 

        //init
        $process = curl_init($gurl); 
 
        curl_setopt($process, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($process, CURLOPT_HEADER, 0);
        curl_setopt($process, CURLOPT_ENCODING , 'gzip');
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POSTFIELDS, $qst);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($process, CURLOPT_POST, 1);
        curl_setopt($process, CURLOPT_USERPWD, $this->skey.":");
        try{
            $return = curl_exec($process);
        }catch (Exception $e){
            $return = $e->getMessage();
        } 
        curl_close($process);
        return json_decode($return);
	}
	*/
	
}
?>
