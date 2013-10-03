<?php

class DataProcessor{
	protected $event,$sql,$access,$db,$logger;
	
	function __construct($db,$logger,$event,$sql,$access){
		$this->db = $db;
		$this->logger=$logger;
		$this->event=$event;
		$this->sql=$sql;
		$this->access=$access;
	}
	

	function status_to_mode($status){
		switch($status){
			case "updated":
				return "update";
				break;
			case "inserted":
				return "insert";
				break;
			case "deleted":
				return "delete";
				break;
			default:
				return $status;
				break;
		}
	}
	
	function inner_process($status,$id,$data,$master=false){
		$action = new DataAction($status,$id,$data,$master);
		$action->assign_logger($this->logger);
		$results[]=$action;
		$mode = $this->status_to_mode($status);
		
		if (!$this->access->check($mode)){
			$this->logger->log("Access control: {$operation} operation blocked");
			$action->error();
		} else {
			$check = $this->event->trigger("beforeProcessing",$action);
			if (!$action->is_ready()){
				$this->check_exts($action,$mode);
				$action->sync_config($this->sql);
				if (!$action->is_ready())
				 	switch($status){
						case "inserted":
							$this->data_insert($action);
							break;
						case "deleted":
							$this->data_delete($action);
							break;
						case "updated":
							$this->data_update($action);
							break;
						default:
							$this->logger->log("Unknown action ",$status);
							break;
				}
			}
			$check = $this->event->trigger("afterProcessing",$action);
		}
		return $action;
	}
	
	//check if some event or sql code intercepts processing
	function check_exts($action,$mode){
		$check = $this->event->trigger("before".$mode,$action);	
		if ($action->is_ready()){
			$this->logger->log("Event code for ".$mode." processed");
		} else {
			$sql=$this->sql->get($mode,$action->get_id(),$action->get_data());
			if ($sql!==true){
				//exec sql
				$this->logger->log("SQL code for ".$mode." mastered from template");
				$this->db->query($sql);
				if ($mode=="inserted")
					$action->success($this->db->get_id());
				else
					$action->success();	
			}
		}
	}
	

	function data_update($action){
		
		$confirm = $this->sql->confirm_sql($action->get_id());
		if ($confirm){
			if (!$this->db->count($this->db->query($confirm))){
				if ($this->access->check("insert")){
					$action->set_status("inserted");
					$this->check_exts($action,"insert");
					if (!$action->is_ready())
						$this->data_insert($action);
					return;
				} else
					$action->error();
			}
		}
		
		$res=$this->db->query($this->sql->update_sql($action->get_id(),$action->get_data()));
		if (!$res) 
			return $action->error();
		$action->success();
	}
	function data_delete($action){
		$res=$this->db->query($this->sql->delete_sql($action->get_id(),$action->get_data()));
		if (!$res) 
			return $action->error();
		else
			return $action->success();
	}
	function data_insert($action){
		$res=$this->db->query($this->sql->insert_sql($action->get_id(),$action->get_data(),$action->master_id()));
		if (!$res)
			return $action->error();
		else
			return $action->success($this->db->get_id());
	}		
	
	function output_edit($results){
		$this->logger->log("Edit operation finished",$results);
		ob_clean();
		header("Content-type:text/xml");
		echo "<?xml version='1.0' ?>";
		
		echo "<data>";
		for ($i=0; $i < sizeof($results); $i++)
			echo $results[$i]->to_xml();
		echo "</data>";
	}		
	
}

class DataAction{
	private $status,$id,$data,$userdata,$nid,$output,$attrs,$ready,$master,$addf,$delf;
	private $logger;
	
	function __construct($status,$id,$data,$master=false){
		$this->status=$status;
		$this->id=$id;
		$this->data=$data["data"];
		$this->userdata=$data["original"];
		$this->nid=$id;
		$this->master=$master;
		
		$this->output="";
		$this->attrs=array();
		$this->ready=false;
		
		$this->addf=array();
		$this->delf=array();
		$this->logger=false;
	}
	
	function assign_logger($logger){
		$this->logger=$logger;
	}
	function log($a,$b=""){
		if ($this->logger)
			$this->logger->log($a,$b);
	}
	
	
	function add_field($name,$value){
		$this->log("adding field: ".$name.", with value: ".$value);
		$this->data[$name]=$value;
		$this->addf[]=$name;
	}
	function remove_field($name){
		$this->log("removing field: ".$name);
		$this->delf[]=$name;
	}
	function sync_config($slave){
		foreach ($this->addf as $k => $v)
			$slave->add_field($v);
		foreach ($this->delf as $k => $v)
			$slave->remove_field($v);
			
			//TODO - check , if all fields removed then cancel action
	}
	
	function get_value($name){
		return $this->data[$name];
	}
	function set_value($name,$value){
		$this->log("change value of: ".$name." as: ".$value);
		$this->data[$name]=$value;
	}
	function get_data(){
		return $this->data;
	}
	function get_userdata_value($name){
		return $this->userdata[$name];
	}
	function set_userdata_value($name,$value){
		$this->log("change userdata of: ".$name." as: ".$value);
		$this->userdata[$name]=$value;
	}
	function get_status(){
		return $this->status;
	}
	function set_status($status){
		$this->status=$status;
	}
	function get_id(){
		return $this->id;
	}
	function set_response_text($text){
		$this->set_response_xml("<![CDATA[".$text."]]>");
	}
	function set_response_xml($text){
		$this->output=$text;
	}
	function set_response_attribute($name,$value){
		$this->attrs[$name]=$value;
	}
	function is_ready(){
		return $this->ready;
	}
	function get_new_id(){
		return $this->nid;
	}
	function master_id(){
		if ($this->master) return $this->master->get_new_id();
	}
	
	
	function error(){
		$this->status="error";
		$this->ready=true;
		if ($this->master)
			$this->master->error();
	}
	function invalid(){
		$this->status="invalid";
		$this->ready=true;
		if ($this->master)
			$this->master->invalid();
	}
	function success($id=false){
		if ($id!==false)
			$this->nid = $id;
		$this->ready=true;
		if ($this->master)
			$this->master->success($id);
	}
	
	function to_xml(){
		$str="<action type='{$this->status}' sid='{$this->id}' tid='{$this->nid}' ";
		foreach ($this->attrs as $k => $v) {
			$str.=$k."='".$v."' ";
		}
		$str.=">{$this->output}</action>";	
		return $str;
	}
	
	function __toString(){
		return "action:{$this->status}; sid:{$this->id}; tid:{$this->nid};";
	}
}


?>