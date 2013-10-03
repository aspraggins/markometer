<?php

class EventMaster{
	private $events;
	function __construct(){
		$this->events=array();
	}
	
	public function exist($name){
		$name=strtolower($name);
		return isset($this->events[$name]);
	}
	public function attach($name,$method){
		$name=strtolower($name);
		$this->events[$name]=$method;
	}
	public function detach($name){
		$name=strtolower($name);
		unset($this->events[$name]);
	}
	public function trigger($name,$data){
		$arg_list = func_get_args();
		$name=strtolower(array_shift($arg_list));
		
		if (!isset($this->events[$name]))
			return true;
		return  call_user_func_array($this->events[$name], $arg_list);
	}
}

class AccessMaster{
	private $rules,$local;
	function __construct(){
		$this->rules=array("read" => true, "insert" => true, "update" => true, "delete" => true);
		$this->local=true;
	}
	
	public function allow($name){
		$this->rules[$name]=true;
	}
	public function deny($name){
		$this->rules[$name]=false;
	}
	public function check($name){
		if ($this->local){
			//check referer to be sure that call initiated from the same host
			//can be tricked by custom user agent - so it is not enough
			
		}
		if (!isset($this->rules[$name]) || !$this->rules[$name]){
			return false;
		}
		return true;
	}
}

class SQLMaster{
	public $sqls,$db,$id,$field,$config,$confirm=false;
	function __construct($db){
		$this->sqls=array();
		$this->db=$db;
	}
	
	
	public function add_field($name,$aliase=false){
		if ($aliase===false) $aliase=$name;
		
		$ind = $this->is_field($name);
		if ($ind!=-1) return;
		array_push($this->config["field"],array($name,$aliase));
		
	}
	public function remove_field($name){
		$ind = $this->is_field($name);
		if ($ind==-1) return;
		array_splice($this->config["field"],$ind,1);
	}
	private function is_field($name){
		for ($i=0; $i<sizeof($this->config["field"]); $i++)
			if ($this->config["field"][$i][0]==$name || $this->config["field"][$i][1]==$name )	return $i;
		return -1;
	}
	public function auto_insert($mode=true){
		$this->confirm=$mode;
	}
	public function config($config){
			$this->config=$config;
	}
	public function attach($name,$data){
		$name=strtolower($name);
		$this->sqls[$name]=$data;
	}
	public function get($name,$rid,$data){
		$name=strtolower($name);
		if (!$this->sqls[$name]) return true;
		
		$str=str_replace("{".$this->config["id"][1]."}",$this->db->escape($rid),$this->sqls[$name]);
		for ($i=0; $i < sizeof($this->config["field"]); $i++) {
			$step=$this->config["field"][$i][1];
			$str=str_replace("{".$step."}",$this->db->escape($data[$step]),$str);
		}
		return $str;
	}
	
	public function confirm_sql($rid){
		if (!$this->confirm) return false;
		
		$id=$this->config["id"][0];
		$table=$this->config["table"][0];

		$sql="SELECT ".$id." FROM ".$table." WHERE ".$id."=".$this->db->escape($rid);
		return $sql;
	}
	public function update_sql($rid,$data){
		$sql="UPDATE ".$this->config["table"][0]." SET ";
		$temp=array();
		for ($i=0; $i < sizeof($this->config["field"]); $i++) { 
			$step=$this->config["field"][$i][0];
			$step_name=$this->config["field"][$i][1];
			$temp[$i]= $step."='".$this->db->escape($data[$step_name])."'";
		}
		$sql.=implode(",",$temp)." WHERE ".$this->config["id"][0]."='".$this->db->escape($rid)."'";
		return $sql;
	}
	public function delete_sql($rid,$data){
		$sql="DELETE FROM ".$this->config["table"][0];
		$sql.=" WHERE ".$this->config["id"][0]."='".$this->db->escape($rid)."'";
		return $sql;
	}
	public function insert_sql($rid,$data,$id=false){
		$temp=array(); foreach($this->config["field"] as $k => $v) $temp[$k]=$v[0];
		if ($id) $temp[]=$this->config["id"][0];
		
		$sql="INSERT INTO ".$this->config["table"][0]."(".implode(",",$temp).") VALUES ";
		$temp=array(); 
		for ($i=0; $i < sizeof($this->config["field"]); $i++) { 
			$temp[$i]= "'".$this->db->escape($data[$this->config["field"][$i][1]])."'";
		}
		if ($id) $temp[]="'".$this->db->escape($id)."'";
		
		$sql.="(".implode(",",$temp).")";
		return $sql;
	}
}

class LogMaster{
	private $_log,$session;
	function __construct(){
		$this->mode=false;
		$this->session="";
	}		
	
	private function log_details($data,$pref=""){
		if (is_array($data)){
			$str=array("");
			foreach($data as $k=>$v)
				array_push($str,$pref.$k." => ".$this->log_details($v,$pref."\t"));
			return implode("\n",$str);
   		}
   		return $data;
	}
	
	public function log($str="",$data=""){
		if ($this->_log){
			$message = $str.$this->log_details($data)."\n\n";
			$this->session.=$message;
			error_log($message,3,$this->_log);			
		}
	}
	public function get_session_log(){
		return $this->session;
	}
	public function error_log($errn,$errstr,$file,$line,$context){
		$this->log($errstr." at ".$file." line ".$line);
	}

	public function enable_log($name){
		$this->_log=$name;
		if ($this->_log){
			set_error_handler(array($this,"error_log"),E_ALL ^ E_NOTICE);
			$this->log("\n\n====================================\nLog started, ".date("d/m/Y h:m:s")."\n====================================");
		}
	}
}

?>