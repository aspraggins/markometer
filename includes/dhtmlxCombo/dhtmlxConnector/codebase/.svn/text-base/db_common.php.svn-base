<?php

class DBQuery{
	public $select,$table,$order,$rules,$from,$count,$group_by;
	private $old_rules,$event;
	public function __construct($str=""){
		$this->select="";
		$this->order="";
		$this->table="";
		$this->from=0;
		$this->count=0;
		$this->rules=array();
		if ($str)
			$this->parse($str);
		$this->init();
	}
	protected function init(){
		$this->prefix="";
	}
	public function __toString(){
		return "select:{$this->select}; table:{$this->table};";
	}
	
	public function copy(){
		$temp = new DBQuery();
		$temp->select=$this->select;
		$temp->order=$this->order;
		$temp->table=$this->table;
		$temp->from=$this->from;
		$temp->group_by=$this->group_by;
		$temp->count=$this->count;
		$temp->rules=$this->rules;
		$temp->old_rules=$this->old_rules;
		
		return $temp;
	}
	public function parse($str){
		$data = preg_split("/from/i",$str);
		
		$this->select = preg_replace("/select/i","",$data[0]);
		$data=$data[1]; //after select part

	  	$data = preg_split("/where/i",$data);
      	if (sizeof($data)>1){ //where construction exists
			$this->table = $data[0];
			$data[0]=$data[1];
		}

		$data = preg_split("/order[ ]+by/i",$data[0]);
		if (!$this->table)
			$this->table=$data[0];
		else $this->rules[] = $data[0];
		$this->order = $data[1];		
	}
	public function sql(){ 
		$str="SELECT ".$this->select." FROM ".$this->table;
		if (sizeof($this->rules))
			$str.=" WHERE ".implode(" AND ",$this->rules);
		if ($this->group_by)
			$str.=" GROUP BY ".$this->group_by;
		if ($this->order)
			$str.=" ORDER BY ".$this->order;
		if ($this->count)
			$str.=" LIMIT ".$this->from.",".$this->count;
		return $str;
	}
	
	public function fill($config,$event=false){
		if ($config["id"][0]!="dhx_auto_generated_id") 
			$actual_id=$config["id"];
		else
			$actual_id="UUID() as dhx_auto_generated_id";
			
   		if (!$this->select)
   			$this->set_select($actual_id,$config["field"]);
   		if (!$this->table)
   			$this->set_table($config["table"]);
   			
   		if ($config["sort"])
   			$this->set_order($config["sort"],$config["direction"],$event);
   		if ($config["rules"])
   			$this->set_rules($config["rules"],$event);
   		if ($config["count"])
   			$this->set_count($config["count"],$config["from"]);
	}
		
	public function set_select(){
		$numargs = func_num_args();
		$temp=array();
		for ($i=0; $i < $numargs; $i++) {
			$arg=func_get_arg($i);
			if (is_array($arg) && is_array($arg[0]))
				$temp=array_merge($temp,$arg);
			else
				$temp[]=$arg;
		}
		for ($i=0; $i < sizeof($temp); $i++) {  
			if (is_array($temp[$i]))
				if ($temp[$i][0]==$temp[$i][1])
					$temp[$i]=$temp[$i][0];
				else
					$temp[$i]=$temp[$i][0]." as ".$temp[$i][1];
		}
		$this->select = implode(" , ",$temp);
	}
	public function set_table($data){
		if (is_array($data))
			$this->table=$data[0];
		else
			$this->table=$data;
	}
	public function set_order($data,$direction='ASC',$event=false){
		if (!$direction) $direction="ASC";
		if (is_array($data)) $data=$data[1];
		if ($event){
			$check = $event->trigger("beforeSort",$data,$direction);
			if ($check !== true) 
				return $this->order=$check;
		}
		$this->order=$data." ".$direction;
	}
	
	public function save_rules(){
		$this->old_rules=$this->rules;
	}
	public function restore_rules(){
		$this->rules = $this->old_rules;
	}
	public function set_rules($rule,$event=false){
		if (!is_array($rule)) $rule=array($rule);
		foreach ($rule as $k => $v){
			if ($event){
				$check = $event->trigger("beforeFilter",$v[0],$v[1]);
				if ($check!==true){
					$this->rules[]=$check;
					continue;
				}
			}
			if ($v[2])
				$this->rules[]=$v[0].$v[2]."'".$v[1]."'";
			else
				$this->rules[]=$v[0]." LIKE '%".$v[1]."%'";
		}	
	}
	public function set_count($count,$from=0){
		if (!$from) $from=0;
		$this->count=$count;
		$this->from=$from;
	}
}


class DBWrapper{
	protected $db,$logger;
	public function __construct($db,$logger){
		$this->db=$db;
		$this->logger=$logger;
	}
	function query($sql,$result=false){ 
		$this->virtual_error(__METHOD__); }
	function escape($str){ 
		$this->virtual_error(__METHOD__); }
	function get_data_array($res){ 
		$this->virtual_error(__METHOD__); }
	function get_data_named($res){ 
		$this->virtual_error(__METHOD__); }
	function get_id(){ 
		$this->virtual_error(__METHOD__); }
	function count($res){
		$this->virtual_error(__METHOD__); }
	 
	
	function virtual_error($method){
		trigger_error("Method {$method} nod defined for DB class","E_USER_ERROR");
	 }
}


?>