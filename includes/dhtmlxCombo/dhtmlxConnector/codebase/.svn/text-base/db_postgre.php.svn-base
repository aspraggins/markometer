<?php
require_once("db_common.php");

class PostgreDBWrapper extends DBWrapper{
	function query($sql,$result=false){
		if (is_object($sql))
			$sql=$sql->sql();
			
		$this->logger->log("Exec SQL: ",$sql);
		$res=pg_query($this->db,$sql);
		if (!$res){
			$this->logger->log("PostgreSQL error: ",pg_last_error());
			return false;
		}
		if ($result===false)
			return $res;
		else return pg_fetch_result($res,$result);
	}	
	function escape($str){
		return pg_escape_string($this->db,$str);
	}	
	function get_data_array($res){
		return pg_fetch_array($res,MYSQL_NUM);
	}
	function get_data_named($res){
		return pg_fetch_assoc($res);
	}
	function get_id(){
		$res = pg_query( $this->db, "SELECT LASTVAL() AS seq");
		$data = pg_fetch_assoc($res);
			pg_free_result($res);
		return $data['seq'];
	}
	function count($res){
		return pg_num_rows($res);	
	}
}
	
class DBQueryPostgre extends DBQuery{
	private $prefix;
	protected function init(){
		$this->prefix="";
	}
	public function sql(){ 
		$str="SELECT ".$this->select." FROM ".$this->table;
		if (sizeof($this->rules))
			$str.=" WHERE ".implode(" AND ",$this->rules);
		if ($this->order)
			$str.=" ORDER BY ".$this->order;
		if ($this->count)
			$str.=" OFFSET ".$this->from." LIMIT ".$this->count;
		return $this->prefix.$str;
	}
	public function fill($config,$event=false){
		if ($config["id"][0]!="dhx_auto_generated_id") 
			$actual_id=$config["id"];
		else{
			$this->prefix="CREATE TEMP sequence dhx_sequence;";
			$actual_id=" to_hex(nextval('dhx_sequence'))||'_'||current_timestamp as dhx_auto_generated_id"; //!!FIXME
		}
			
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
}

?>