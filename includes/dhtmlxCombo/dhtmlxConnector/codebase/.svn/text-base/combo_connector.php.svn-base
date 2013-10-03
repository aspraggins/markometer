<?php
require_once("base_connector.php");
class ComboDataItem extends DataItem{
	private $selected;
	function __construct($id,$data,$name,$index=0){
		parent::__construct($id,$data,$name,$index);
		
		$this->selected=false;
	}
	function select(){
		$this->selected=true;
	}
	
	function to_xml(){
		if ($this->skip) return "";
		
		$str="<option ".($this->selected?"selected='true'":"")."value='".$this->id."'><![CDATA[".$this->data[$this->name[0][1]]."]]></option>";
		return $str;
	}

}
class ComboConnector extends Connector{
	private $filter,$position;
	//parse GET scoope, all operations with incoming request must be done here
	function parse_request(){
		if (isset($_GET["pos"]))
			$this->position=$_GET["pos"];
		else
			$this->position=false;
			
		if (isset($_GET["mask"]))
			$this->filter=$_GET["mask"];
		else
			$this->filter=false;
	}
	
	
	function render_table($table,$id,$field){
		if (!$id) $id="dhx_auto_generated_id";
		
		$this->set_config("field",$field,true);
		$this->set_config("id",$id);
		$this->set_config("table",$table);
		
		$this->event->trigger("beforeParse","");
		
		$this->parse_request();
		
		if ($this->filter){
			$this->config["rules"]=array();
			$this->config["rules"][]= array($this->config["field"][0][0],$this->db->escape($this->filter));
		}
		if ($this->dload){
			$this->config["from"]=$this->position;
			$this->config["count"]=$this->dload;
		}
		$result=$this->event->trigger("beforeFetch",$this->config);
		if ($result===true){
			$this->fill_query();
			$this->render();
		} else
			$this->output=$result;
		
		$this->output_xml();
	}

	function render(){
		$this->render_set($this->db->query($this->query));
	}

   	function render_sql($sql,$id,$field){
		$this->query = $this->get_query($sql);
		$this->render_table($this->query->table,$id,$field);
	}
	
	function render_set($res){
		if (!$res)
			return $this->output_error();
			
		$id = $this->config["id"];
		$field = $this->config["field"];
		$index=0;
		
		while ($data = $this->db->get_data_named($res)){
			$data = new ComboDataItem($data[$id[1]],$data,$field,$index++);
			$this->event->trigger("beforeRender",$data);
			$this->output.=$data->to_xml();
		}
	}
	function output_xml(){
		$this->output_header();
		if ($this->position && $this->position["start"]!=0)
			echo "<complete add='true'>";
		else
			echo "<complete>";
			
		echo $this->output;
		echo "</complete>";
	}
}
?>