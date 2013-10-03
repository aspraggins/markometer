<?php
require_once("grid_connector.php");
require_once("treegrid_dataprocessor.php");

class TreeGridDataItem extends GridDataItem{
	private $parent_id;
	function __construct($id,$data,$name,$index,$parent_id){
		parent::__construct($id,$data,$name,$index);
		
		$this->parent_id=$parent_id;
		$this->im0=false;
	}
	function get_parent_id(){
		return $this->parent_id;
	}
	function set_image($img){
		$this->set_cell_attribute($this->name[0][1],"image",$img);
	}	
}
class TreeGridConnector extends GridConnector{
	private $dyn_load,$pid;
	function __construct($res,$type="MySQL"){
		parent::__construct($res,$type);
	}
	function parse_request_treegrid(){
		if (isset($_GET["id"]))
			$this->pid=$_GET["id"];
		else
			$this->pid="0";
	}
	function render_table($table,$id,$field,$pid){
		//block defautl dyn. mode of grid
		$this->dyn_load=$this->dload; $this->dload=false;
		
		if (!$id) $id="dhx_auto_generated_id";
		
		$this->set_config("field",$field,true);
		$this->set_config("id",$id);
		$this->set_config("pid",$pid);
		$this->set_config("table",$table);
		
		$this->event->trigger("beforeParse","");
		$this->parse_request();
		$this->parse_request_treegrid();
		
		if ($this->editing){
			$this->sql->config($this->config);
			$dp = new TreeGridDataProcessor($this->db,$this->_log,$this->event,$this->sql,$this->access);
			$dp->process($this->form);
			$this->end_run();
		}
		
		$result=$this->event->trigger("beforeFetch",$this->config);
		if ($result===true){
			$this->fill_query();
			$this->query->save_rules();
			$this->query->set_rules(array(array($this->config["pid"][0],$this->db->escape($this->pid),"=")));
			$this->render();
		} else 
			$this->output=$result;
		
		$this->output_xml();
	}
	
	function render_sql($sql,$id,$field,$pid){
		$this->get_query($sql);
		$this->render_table($this->query->table,$id,$field,$pid);
	}
		
	function render_set($res){
		if (!$res)
			return $this->output_error();
			
		$id = $this->config["id"];
		$field = $this->config["field"];
		$child=false;
		$index=0;
		
		if (!$this->position["start"] && $this->event->exist("DataFilterOptions"))
			for ($i=0; $i < sizeof($field); $i++){
				$check=$this->event->trigger("beforeFilterOptions",$field[$i][1]);		
				if ($check!==true) 
					$this->output.=$this->format_options($check,$i);
			}
		
		
		while ($data = $this->db->get_data_named($res)){
			$item_id = $data[$id[1]];
			if ($this->dyn_load){
				$sub = $this->query->copy();
				$sub->restore_rules();
				
				$sub->count = 0;
				$sub->select = " COUNT(*) ";
				$sub->set_rules(array(array($this->config["pid"][0],$item_id,"=")));
				
				$child=$this->db->query($sub,0);
			}
			
			if ($this->event->exist("beforeRender")){
				//we have a custom data generation logic
				$data = new TreeGridDataItem($data[$id[1]],$data,$field,$index++);
				$this->event->trigger("beforeRender",$data);
				if (!($item_xml=$data->to_xml_start())) continue;
				$this->output.=$item_xml;
			} else {
				//need to measure performance, if difference not big - this branch of code may be droped
				$this->output.="<row id='".$data[$id[1]]."' ".($child?"xmlkids='1'":"").">";
				for ($i=0; $i < sizeof($field); $i++)
					$this->output.="<cell><![CDATA[".$data[$field[$i][1]]."]]></cell>";
			}			
			
			if (!$this->dyn_load){
				$this->query->restore_rules();
				$this->query->set_rules(array(array($this->config["pid"][0],$item_id,"=")));
				$this->render();
			}
			$this->output.="</row>";
		}
	}
	
	function output_xml(){
		$this->output_header();
		echo "<rows parent='".$this->pid."' >";
		if ($this->form)
			echo "<userdata name='!linked_form'>".$this->form["name"]."</userdata>";
			
		echo $this->output;
		echo "</rows>";
		$this->end_run();
	}
}
?>