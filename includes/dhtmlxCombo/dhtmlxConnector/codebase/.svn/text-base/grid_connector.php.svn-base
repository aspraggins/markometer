<?php
require_once("base_connector.php");
require_once("grid_dataprocessor.php");

class GridDataItem extends DataItem{
	protected $row_attrs,$cell_attrs;
	function __construct($id,$data,$name,$index=0){
		parent::__construct($id,$data,$name,$index);
		
		$this->row_attrs=array();
		$this->cell_attrs=array();
	}
	function set_row_color($color){
		$this->row_attrs["bgColor"]=$color;
	}
	function set_row_style($color){
		$this->row_attrs["style"]=$color;
	}
	function set_cell_style($name,$value){
		$this->set_cell_attribute($name,"style",$value);
	}
	function set_cell_class($name,$value){
		$this->set_cell_attribute($name,"class",$value);
	}
	function set_cell_attribute($name,$attr,$value){
		if (!$this->cell_attrs[$name]) $this->cell_attrs[$name]=array();
		$this->cell_attrs[$name][$attr]=$value;
	}
	
	
	function to_xml_start(){
		if ($this->skip) return "";
		
		$str="<row id='".$this->id."'";
		foreach ($this->row_attrs as $k=>$v)
			$str.=" ".$k."='".$v."'";
		$str.=">";
		for ($i=0; $i < sizeof($this->name); $i++){ 
			$str.="<cell";
			$cattrs=$this->cell_attrs[$this->name[$i][1]];
			if ($cattrs)
				foreach ($cattrs as $k => $v)
					$str.=" ".$k."='".$v."'";
			$str.="><![CDATA[".$this->data[$this->name[$i][1]]."]]></cell>";
		}
		
		return $str;
	}
	function to_xml(){
		return $this->to_xml_start()."</row>";
	}
}
class GridConnector extends Connector{
	protected $filter,$sorting,$position,$total_size;
	function __construct($res,$type="MySQL"){
		parent::__construct($res,$type);
	}

	//parse GET scoope, all operations with incoming request must be done here
	function parse_request(){
		if (isset($_GET["posStart"]))
			$this->position=array("start" => intval($_GET['posStart']), "count" => intval($_GET['count']));
		else
			$this->position=false;
		if (isset($_GET["filter"])){
			$this->filter=array();
			$max = intval($_GET["filter"]);
			for ($i =0; $i < $max; $i++) { 
				if (isset($_GET['col'.$i]) && $_GET['col'.$i]!="")
					$this->filter[$i]=$_GET['col'.$i];
			}
		} else
			$this->filter=false;
			
		if (isset($_GET["sort_ind"]))
			$this->sorting=array("column" => intval($_GET['sort_ind']), "direction" => ($_GET['sort_dir']=="asc"?"ASC":"DESC"));
		else
			$this->sorting=false;
			
		$this->editing = isset($_GET["editing"]);
	}
	function check_total(){
		if (!$this->dload || $this->position) return;
		$subquery = $this->query->copy();
		$subquery->count=0;
		$subquery->order="";
		$subquery->select=" COUNT(*) ";
		$this->total=$this->db->query($subquery,0);
	}
	function customize_config(){
		if ($this->filter){
			$this->config["rules"]=array();
			foreach ($this->filter as $column=>$rule)
				$this->config["rules"][]=array($this->config["field"][$column][0],$this->db->escape($rule));
		}
		if ($this->sorting){
			$this->config["sort"]=$this->config["field"][$this->sorting["column"]][1];
			$this->config["direction"]=$this->sorting["direction"];
		} else {
			//default sorting - descedent by primary id
			//disabled, because it is a killer for big datasets
			/*$this->config["sort"]=$this->config["id"][1];
			$this->config["direction"]="DESC";*/
		}
		
		if ($this->position){
			$this->config["count"]=$this->position["count"];
			$this->config["from"]=$this->position["start"];
		} else if ($this->dload)
			$this->config["count"]=$this->dload;
	}
	
	function render_table($table,$id,$field){
		if (!$id) $id="dhx_auto_generated_id";
		
		$this->set_config("field",$field,true);
		$this->set_config("id",$id);
		$this->set_config("table",$table);
		
		$this->event->trigger("beforeParse","");
		$this->parse_request();
		
		if ($this->editing){
			$this->sql->config($this->config);
			$dp = new GridDataProcessor($this->db,$this->_log,$this->event,$this->sql,$this->access);
			$dp->process($this->form);
			$this->end_run();
		}		
		
		$this->customize_config();
						
		$result=$this->event->trigger("beforeFetch",$this->config);
		if ($result===true){
			$this->fill_query();
			$this->check_total();
			$this->render();
		} else 
			$this->output=$result;
		
		$this->output_xml();
	}
		
	
	function render(){
		$this->render_set($this->db->query($this->query));
	}
	
	function render_sql($sql,$id,$field){
		$this->get_query($sql);
		$this->render_table($this->query->table,$id,$field);
	}
		
	function render_set($res){
		if (!$res)
			return $this->output_error();
			
		$id = $this->config["id"];
		$field = $this->config["field"];		
		if (!$this->position["start"] && $this->event->exist("beforeFilterOptions"))
			for ($i=0; $i < sizeof($field); $i++){
				$check=$this->event->trigger("beforeFilterOptions",$field[$i][1]);		
				if (is_array($check)) 
					$this->output.=$this->format_options($check,$i);
			}
		$index=$this->position["start"];
		while ($data = $this->db->get_data_named($res)){
			if ($this->event->exist("beforeRender")){
				//we have a custom data generation logic
				$data = new GridDataItem($data[$id[1]],$data,$field,$index++);
				$this->event->trigger("beforeRender",$data);
				$this->output.=$data->to_xml();
			} else {
				//need to measure performance, if difference not big - this branch of code may be droped
				$this->output.="<row id='".$data[$id[1]]."'>";
				for ($i=0; $i < sizeof($field); $i++)
					$this->output.="<cell><![CDATA[".$data[$field[$i][1]]."]]></cell>";
				$this->output.="</row>";
			}
		}
	}
	
	function format_options($options,$ind){
		$str="<options for='".$ind."'>";
		for ($i=0; $i < sizeof($options); $i++) { 
			$str.="<option><![CDATA[".$options[$i]."]]>'</option>";
		}
		return $str."</options>";
	}
	function output_xml(){
		$this->output_header();
		echo "<rows ";
		if ($this->dload){
			if (!$this->position["start"])
				echo "total_count='".$this->total."' pos='0' ";
			else
				echo "pos='".$this->position["start"]."' ";
		}
		echo ">";
		
		if ($this->form)
			echo "<userdata name='!linked_form'>".$this->form["name"]."</userdata>";
			
		echo $this->output;
		echo "</rows>";
		
		$this->end_run();
	}
}
?>