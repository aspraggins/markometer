<?php
require_once("base_connector.php");
require_once("tree_dataprocessor.php");

class TreeDataItem extends DataItem{
	private $parent_id, $im0,  $im1, $im2, $check;
	function __construct($id,$data,$name,$index,$parent_id){
		parent::__construct($id,$data,$name,$index);
		$this->parent_id=$parent_id;
		
		$this->im0=false;
		$this->im1=false;
		$this->im2=false;
		$this->check=false;
	}
	function get_parent_id(){
		return $this->parent_id;
	}
	function get_check_state(){
		return $this->check;
	}
	function set_check_state($value){
		$this->check=$value;
	}
	function set_image($img_folder_closed,$img_folder_open=false,$img_leaf=false){
		$this->im0=$img_folder_closed;
		$this->im1=$img_folder_open?$img_folder_open:$img_folder_closed;
		$this->im2=$img_leaf?$img_leaf:$img_folder_closed;
	}
	
	function to_xml_start(){
		if ($this->skip) return "";
		
		$str1="<item id='".$this->id."' text='".$this->data[$this->name[0][1]]."' ";
		if ($this->im0) $str1.="im0='".$this->im0."' ";
		if ($this->im1) $str1.="im1='".$this->im0."' ";
		if ($this->im2) $str1.="im2='".$this->im0."' ";
		if ($this->check) $str1.="checked='".$this->check."' ";
		$str1.=">";
		return $str1;
	}
	function to_xml(){
		return $this->to_xml_start."</item>";
	}

}

class TreeConnector extends Connector{
	private $pid;
	function __construct($res,$type="MySQL"){
		parent::__construct($res,$type);
	}

	//parse GET scoope, all operations with incoming request must be done here
	function parse_request(){
		if (isset($_GET["id"]))
			$this->pid=$_GET["id"];
		else
			$this->pid="0";

		$this->editing = isset($_GET["editing"]);			
	}
	
	function render_table($table,$id,$field,$pid){
		if (!$id) $id="dhx_auto_generated_id";
		
		$this->set_config("field",$field,true);
		$this->set_config("id",$id);
		$this->set_config("pid",$pid);
		$this->set_config("table",$table);
		
		$this->event->trigger("beforeParse","");
		$this->parse_request();
		
		if ($this->editing){
			$this->sql->config($this->config);
			$dp = new TreeDataProcessor($this->db,$this->_log,$this->event,$this->sql,$this->access);
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
	function render(){
		$this->render_set($this->db->query($this->query));
	}

   
	function render_set($res){
		if (!$res)
			return $this->output_error();
			
		$id = $this->config["id"];
		$field = $this->config["field"];
		$child=false;
		$index=0;
		
		while ($data = $this->db->get_data_named($res)){
			$item_id = $data[$id[1]];
			if ($this->dload){
				$sub = $this->query->copy();
				$sub->restore_rules();
				
				$sub->count = 0;
				$sub->select = " COUNT(*) ";
				$sub->set_rules(array(array($this->config["pid"][0],$item_id,"=")));
				
				$child=$this->db->query($sub,0);
				$this->_log->log($check);
			}
			
			if ($this->event->exist("beforeRender")){
				//we have a custom data generation logic
				$data = new TreeDataItem($data[$id[1]],$data,$field,$index++);
				$this->event->trigger("beforeRender",$data);
				if (!($item_xml=$data->to_xml_start())) continue;
				$this->output.=$item_xml;
			} else {
				//need to measure performance, if difference not big - this branch of code may be droped
				$this->output.="<item id='".$item_id."' text='".$data[$field[0][0]]."' ";
				if ($child)
					$this->output.=" child='".$child."'>";
				else
					$this->output.=" >";
			}			
			
			if (!$this->dload){
				$this->query->restore_rules();
				$this->query->set_rules(array(array($this->config["pid"][0],$item_id,"=")));
				$this->render();
			}
			$this->output.="</item>";
		}
	}
   
	function output_xml(){
		$this->output_header();
		echo "<tree id='".$this->pid."'>";
		echo $this->output;
		echo "</tree>";
		$this->end_run();
	}
}
?>