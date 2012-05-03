<?php

namespace plugins\riLog;

use plugins\riPlugin\Object;

class Logs extends Object{
	
	private $logs = array();
	
	public function add($log){
		if(!$log instanceof RiLog){	
			$args = array_merge(array(
				'message' => '', 
				'session' => false, 
				'type' => 'error', 
				'scope' => 'global'
				), $log);
				
			$log = $this->container->get('riLog.Log');
			$log->put($args['message'], $args['session'], $args['type'], $args['scope']);			
			
		}
		$this->logs[] = $log; 
	}
	
	
	function count(){
		return count($this->logs);	
	}
	
	public function copyToZen($admin = false){
		global $messageStack;
		
		foreach($this->logs as $log){
			if($log->session){
				if($admin)
					$messageStack->add_session($log->message, $log->type);
				else
					$messageStack->add_session($log->scope, $log->message, $log->type);
			}
			else{ 
				if($admin)
					$messageStack->add($log->message, $log->type);
				else
					$messageStack->add($log->scope, $log->message, $log->type);
			}					
		}	
		
		return $this;
	}
		
	public function getCollectionAsArray(){
	    $logs = array();
	    foreach($this->logs as $log)
	        $logs = $log->getArray();
	    return $logs;
	}
	
	public function clear(){
		$this->logs = array();
	}
}