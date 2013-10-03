var timer=500;
		var data;
		var table;
		var hide_timer;
		var show_timer;
		var last_zone;
		var last_shown_zone;
		var lastnode=null;
		var fm={
			space:"<span class='spacer'>&nbsp;</span>",
			gt:"{{span class='bracket'}}&gt;{{/span}}",
			lt:"{{span class='bracket'}}&lt;$1{{/span}}{{span class='tag'}}$2{{/span}}$3",
			amp:"&amp;",
			attr:"{{span class='attr'}}$1{{/span}}={{span class='value'}}$2{{/span}}"
		}
		function z(name){
			var v=document.createElement(name);
			for (var i=1; i < arguments.length; i++) 
				if (typeof arguments[i] == "object")
					v.appendChild(arguments[i]);
				else
					v.innerHTML=arguments[i];
			return v;
		}
		function getTag (el) {
			el=el.parentNode.childNodes;
			for (var i=0; i < el.length; i++) {
				if(el[i].className=="tag")
					return el[i].firstChild.data;
			};
		}
		function showInfo (tag,attr,e) {
			last_zone=document.getElementById(tag+"_"+attr)
			if (last_zone){
				last_zone.style.left=e.clientX+10+"px";
				last_zone.style.top=e.clientY+document.body.scrollTop+10+"px";
			}
			if (hide_timer)  window.clearTimeout(hide_timer)
			show_timer = window.setTimeout(reallyShowInfo,timer) 
		}
		function reallyShowInfo(){
			reallyHideInfo();				
			if (last_zone && last_zone!=last_shown_zone){
				last_zone.className="details";
				last_zone.style.display="block";
			}
			last_shown_zone=last_zone;
		}
		function reallyHideInfo(){
			if (last_shown_zone)
				last_shown_zone.style.display="none"
			last_shown_zone=null;
		}
		function hideInfo(){
 			if (show_timer)  window.clearTimeout(show_timer)
			hide_timer = window.setTimeout(reallyHideInfo,timer) 
		}
		function init_xml(name){ 
			//lets fun begin
			data=document.getElementById(name).value.split("\n");
			table=document.getElementById('xml_1');
			table.onmouseover=function(ev){
				el=ev?ev.target:event.srcElement;
				if (el.className=="tag")
					showInfo(el.firstChild.data,"",(ev||event))
				else if (el.className=="attr")
					showInfo(getTag(el),el.innerHTML,(ev||event))
				else 
					hideInfo();
			}
			document.getElementById('ev_catcher').onmousemove=function(){
				window.clearTimeout(hide_timer)
			}
			for (var i=0; i < data.length; i++) {
				data[i]=data[i].split("#");
				data[i][0]=data[i][0].replace(/([a-zA-Z0-9_]+)=("[^"]*")/g,fm.attr).replace(/&/g,fm.amp).replace(/<([\/]*)([^ >]*)( |>)/g,fm.lt).replace(/>/g,fm.gt).replace(/\t/g,fm.space).replace(/\{\{/g,"<").replace(/\}\}/g,">");
				var row=z("TR",z("TD",data[i][0]),z("TD",(data[i][1]||"")));
				row.className=(i%2?"un":"")+"even_row";
				table.rows[0].parentNode.appendChild(row);
			};
		}
	function clear_table () {
		table=document.getElementById('xml_1');	
		var len=table.rows.length;
		for (var i=1; i < len; i++) {
			table.rows[0].parentNode.removeChild(table.rows[1]);
		};
	}
	function init_first_scheme(){
		if (parent && parent.show_loader)
			parent.show_loader(false);
		document.getElementById("start_me").onclick();
	}
	function init_plain(){
		var data={}
		table=document.getElementById('xml_1');
		var x=document.getElementsByTagName("DIV")
		for (var i=0; i < x.length; i++) {
			if (x[i].className=="hidden" || x[i].className=="details"){
				var info=x[i].id.split("_");
				if (!data[info[0]])
					data[info[0]]={ attrs:[], name:info[0] }
				if (info[1]=="")
					data[info[0]].data=x[i].innerHTML;
				else
					data[info[0]].attrs.push({ data:x[i].innerHTML, name:info[1]});
			}
		};
		
		var text="<table>";
		var temp=data; data=[];
		for (var name in temp){
			data.push(temp[name])
		}
		data.sort(function(a,b){
			return a.name>b.name?1:-1;
		})
		for (var i=0; i < data.length; i++) {
			var t=data[i];
			var name=data[i].name;
			text+="<tr><td class='f_col'>"+name+"</td><td>";
			text+=t.data.replace(/<h3>.*<\/h3>/i,"").replace(/<h4>(.|\n)*$/i,"");
			if (t.attrs.length){
				t.attrs.sort(function(a,b){
					return a.name>b.name?1:-1;
				});
				text+="<h4>Attributes</h4><table>"
				for (var j=0; j < t.attrs.length; j++) {
					text+="<tr><td class='f_col'>"+t.attrs[j].name+"</td><td>";
					text+=t.attrs[j].data;
					text+="</td></tr>"
				};
				text+="</table><hr/>"
			}
			text+="</td></tr>"
			
		}
		text+="</table>";
		var row=z("TR",z("TD",text));
		row.firstChild.colSpan=2;
		table.rows[0].parentNode.appendChild(row);
	}
	function init_scheme(name,node){
		node.className="active";
		if (lastnode) lastnode.className="";
		lastnode=node;
		clear_table();
		if (name=="plain")
			init_plain();
		else
			init_xml("xml_"+name);
	}