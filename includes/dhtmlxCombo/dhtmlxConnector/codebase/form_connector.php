<?php
require_once("base_connector.php");
require_once("form_dataprocessor.php");

class FormDataRow{
	public $data,$id,$name;
	function __construct($id,$data,$name){
		$this->id=$id;
		$this->name=$name;
		$this->data=$data;
	}
	function to_xml(){
		$str="<form id='".$this->id."'>";
		for ($i=0; $i < sizeof($this->name); $i++)
			$str.="<data id='".$this->name[$i][1]."'><![CDATA[".$this->data[$this->name[$i][1]]."]]></data>";
		$str.="</form>";
		return $str;
	}
}

class FormConnector extends Connector{
	private $id,$editing;
	function __construct($res,$type="MySQL"){
		parent::__construct($res,$type);
	}

	//parse GET scoope, all operations with incoming request must be done here
	function parse_request(){
		if (isset($_GET["form_id"])){
			$this->id=$_GET["form_id"];
		}
		$this->editing = isset($_GET["editing"])&&(!isset($_POST["ids"]));
	}

	function customize_config(){
		$this->config["rules"]=array();
		$this->config["rules"][]=array($this->config["id"][0],$this->db->escape($this->id),"=");
	}
	
	function render_table($table,$id,$field){
		$this->set_config("field",$field,true);
		$this->set_config("id",$id);
		$this->set_config("table",$table);
		
		$this->event->trigger("beforeParse","");
		$this->parse_request();
		
		if (!$this->id) return;
		if ($this->editing)
			return $this->update();

		$this->customize_config();
		
		$result=$this->event->trigger("beforeFetch",$this->config);
		if ($result===true){
			$this->fill_query();
			$this->render();
		} else
			$this->output=$result;
			
		$this->output_xml();
	}
	
	function update(){
		$this->sql->config($this->config);
		$dp = new FormDataProcessor($this->db,$this->_log,$this->event,$this->sql,$this->access);
		return $dp->process($this->config["field"]);		
	}
	
	function update_external($action){
		if (!$this->dp){
			$this->sql->config($this->config);
			$this->dp = new FormDataProcessor($this->db,$this->_log,$this->event,$this->sql,$this->access);
		}
			
		return $this->dp->process($this->config["field"],$action);
	}
	
	function render(){
		if (!$this->id) return;

		$this->render_set($this->db->query($this->query));
	}
	
	function render_sql($sql,$id,$field){
		$this->query = new DBQuery($sql);
		$this->render_table("",$id,$field);
	}
		
	function render_set($res){
		if (!$this->id) return;
		if (!$res) return $this->output_error();
		
		$id = $data[$this->config["id"][1]];
		$field = $this->config["field"];	
		$data = $this->db->get_data_named($res);
		
		/*
			It is possible that in case of form linked to any other component 
			record in linked table not exists. In such case we still sending empty response back
			DataProcessor will automatically use INSERT action for such form instead of update. 
		*/
		$data = new FormDataRow($this->id,$data,$field);	//using original ID, it is important for client side code 
		$this->event->trigger("beforeRender",$data);
		$this->output.=$data->to_xml();
	}
	function output_error(){
		header("Content-type:text/html");
		echo "Error in FormConnector\nCheck server side logs for more details.";
		$this->log("Critical error in FormConnector , processing stoped.");
		die();
	}
	function output_xml(){
		$this->output_header();		
		echo $this->output;
		$this->end_run();
		die();
	}
}
?>