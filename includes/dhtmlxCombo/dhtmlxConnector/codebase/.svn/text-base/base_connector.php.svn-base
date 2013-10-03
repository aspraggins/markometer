<?php
require_once("db_mysql.php");
require_once("tools.php");

//enable buffering to catch and ignore any custom output before XML generation
//because of this command, it strongly recommended to include connector's file before any other libs
//in such case it will be handle any extra output from not well formed code in them
ini_set("output_buffering","On");
ob_start();

class DataItem{
	protected $id, $name, $data, $index, $skip;
	function __construct($id,$data,$name,$index){
		$this->id=$id;
		$this->name=$name;
		$this->data=$data;
		$this->index=$index;
		$this->skip=false;
	}
	
	public function get_value($name){
		return $this->data[$name];
	}
	public function set_value($name,$value){
		return $this->data[$name]=$value;
	}
	public function get_id(){
		return $this->id;
	}
	public function set_id($value){
		$this->id=$value;
	}
	public function get_index($value){
		return $this->index;
	}
	public function skip(){
		$this->skip=true;
	}
}

class Connector {
	public $db,$config,$sql,$event,$access; //???
	protected $_log;
	private $exec_time;
	protected $dload,$output,$form,$client_log,$dbtype,$editing,$encoding;
	
	public function __construct($db,$type="MySQL"){
		$this->config = array();

		$this->_log = new LogMaster();
		$this->event = new EventMaster();
		$this->access = new AccessMaster();
		
		$this->dbtype=$type;
		$type.="DBWrapper";
		$this->db=new $type($db,$this->_log);
		$this->sql = new SQLMaster($this->db);
		

		
		$this->dload = false;
		
		$this->output = "";
		$this->form = false;
		$this->client_log=true;
		$this->encoding="UTF-8";
		
		$this->exec_time=microtime(true);
	}
	
	public function attach_form($form, $form_name){
		$this->form=array("obj"=>$form,"name"=>$form_name);
	}
	
	public function set_encoding($encoding){
		$this->encoding=$encoding;
	}
	
	protected function end_run(){
		$time=microtime(true)-$this->exec_time;
		$this->_log->log("Done in {$time}ms");
		flush();
		die();
	}
	
	protected function set_alias($data){ 
			$data=preg_split("/\\(|\\)/i",$data);
			if (sizeof($data)==1){
				$temp=explode(".",$data[0]);
				$data[1]=$temp[sizeof($temp)-1];
			}
			return $data;
	}
	protected function set_config($name,$str,$array_mode=false){
		 if ($array_mode){
		 	$data=explode(",",$str);
		 	foreach($data as $k=>$v)
		 		$data[$k]=$this->set_alias($v);
	 	} else
			$data = $this->set_alias($str);
			
		$this->config[$name]=$data;		
	}
	
	protected function get_query($sql=""){
		if (!$this->query){
			$query_class="DBQuery".$this->dbtype;
			$this->query = new $query_class($sql);
		}
		return $this->query;
	}
	public function fill_query(){
		if (!$this->access->check("read")){
			$this->_log->log("Access control: read operation blocked");
			return $this->output_error();
		}
			
		$this->log("Ready for SQL generation",$this->config);
   		$this->get_query()->fill($this->config,$this->event);
	}

	public function dynamic_loading($count){
		$this->dload=$count;
	}
	
	protected function output_error(){	
		$this->log("Critical error in Connector, processing stoped.");
		header("Content-type:text/html");
		if ($this->client_log)
			echo "<pre><xmp>\n".$this->_log->get_session_log()."\n</xmp></pre>";
		else
			echo "Error in Connector\nCheck server side logs for more details.";
		
		die();
	}
	
	protected function output_header(){
		ob_clean();
		header("Content-type:text/xml");
		echo "<?xml version='1.0' encoding='".$this->encoding."' ?>";
	}	
	
	//map methods of x-masters
	public function enable_log($path=true,$client_log=false){
		$this->_log->enable_log($path);
		$this->client_log=$client_log;
	}
	
	public function is_select_mode(){
		$this->parse_request();
		return !$this->editing;
	}
	
	//map methods of x-masters
	protected function log($str,$data=""){
		$this->_log->log($str,$data);
	}	
}
?>