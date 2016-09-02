var desc;
var nodes = [];
var edges = [];

window.onload = function(){
	$("#btn_network").prop('disabled', true);
	getDesc();
}

function submitForm(email){
	exp = $('select#exp option:selected').val();
	cond = $('#cond').val();
	value = $('#val').val();
	console.log(exp, cond, value)
	submitCount(exp, cond, value)
}

function submitCount(exp, cond, value){
	$.ajax({
		url: "/brew/network_count.html",
		data: {
			exp: exp,
			cond: cond,
			value: value
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			//console.log(data);
			$("#idscount").html(data)
		},
		//* If error. show the error in console.log
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}
function getDesc(){
	$.ajax({
		url: "/brew/network_desc.html",
		data: {},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			console.log(data);
			desc = data;
			$("#btn_network").prop('disabled', false);
		},
		//* If error. show the error in console.log
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}
function submitSpearman(file, exp, cond, value){
	$.ajax({
		url: "/brew/network_mimatrix.html",
		data: {
			exp: exp,
			cond: cond,
			value: value
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			//console.log(data);
			//$("#idscount").html(data)
			//console.log(desc)
			var idlist = [];
			var val = data[0];
			$.each(val, function(k, v){
				idlist.push(k)
				nodes.push({id: k, label: k, desc: desc[k]})
				//console.log(k, desc[k]);
				//nodes.push({id: k, label: k})
			})

			//console.log(nodes)
			$.each(data, function(key, val){
				console.log("------------------------------------------")
				$.each(val, function(k, v){
					if(v == 0){
						return true;
					}
					if(idlist[key] == k){
						return true;
					}
					//console.log(idlist[key], k, v)
					edges.push({id: idlist[key]+"to"+k, target: idlist[key], source: k, label: v})
				})
			})
			//console.log(edges)
			drawCytoscape(nodes, edges)
		},
		//* If error. show the error in console.log
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function getNetwork(){
	$("#spinner").toggle()
	exp = $('select#exp option:selected').val();
	cond = $('#cond').val();
	value = $('#val').val();
	console.log(exp, cond, value)
	submitForm();
	submitSpearman(exp, cond, value)
}

function drawSelected(){
	selected = ["GRMZM2G180246", "GRMZM2G016232", "GRMZM2G041238", "GRMZM2G072855", "GRMZM2G063896"]
	drawCytoscape(selected);
}

function drawCytoscape(selected){
	// id of Cytoscape Web container div
	var div_id = "cytoscapeweb";
				
	// you could also use other formats (GraphML) or grab the network data via AJAX
	var network_json = {
		dataSchema: {
			nodes: [ 
				{ name: "label", type: "string" }, 
				{ name: "desc", type: "string" }
			],
			edges: [ 
				{ name: "label", type: "double" }
			]
		},
		data: {
			nodes: nodes,
			edges: edges
		}
	};
					
	// initialization options
	var options = {
		// where you have the Cytoscape Web SWF
		swfPath: "/cytoscape/swf/CytoscapeWeb",
		// where you have the Flash installer SWF
		flashInstallerPath: "/cytoscape/swf/playerProductInstall"
	};
					
	// init and draw
	var vis = new org.cytoscapeweb.Visualization(div_id, options);
	
	//Callback when Cytoscape has finished drawing
	vis.ready(function() {
		// add a listener for when nodes and edges are clicked
		vis.addListener("click", "nodes", function(event) {
			handle_click(event);
		})
		.addListener("click", "edges", function(event) {
			handle_click(event);
		})

		function handle_click(event){
			/**
			var target = event.target;
			clear();
			print("event.group = " + event.group);
			for(var i in target.data){
				var variable_name = i;
				var variable_value = target.data[i];
				print("event.target.data."+ variable_name + " = " + variable_value);
			}
			*/
			clear();
			console.log(event.target.data.desc)
			print("gene_id: " + event.target.data.id + "<br>gene_description: " + event.target.data.desc)
		}
		
		function clear(){
			document.getElementById("note").innerHTML = "";
		}
		function print(msg){
			document.getElementById("note").innerHTML += "<p>" + msg + "</p>";
		}
		
		// On right click you will see "Select the first neighbors"
		vis.addContextMenuItem("Select first neighbors", "nodes", 
			function (evt) {
				// Get the right-clicked node:
				var rootNode = evt.target;
				// Get the first neighbors of that node:
				var fNeighbors = vis.firstNeighbors([rootNode]);
				var neighborNodes = fNeighbors.neighbors;
				// Select the root node and its neighbors:
				vis.select([rootNode]).select(neighborNodes);
			}
    );
		
		if(selected.length != 0){
			vis.select(selected);
		}
	});
	
	var visual_style = {
		global: {
			backgroundColor: "#E0FFFF"
		},
		nodes: {
			shape: "ROUNDRECT",
			borderWidth: 1,
			borderColor: "#800000",
			width: 120,
			labelHorizontalAnchor: "center",
			color: "#FFFFFF"
		},
		edges: {
			color: "red",
			width: {
	      defaultValue: 3,
				continuousMapper: { attrName: "label", minValue: 1, maxValue: 10 }
			},
		}
	}
	
	//Draw 
	var draw_options = {
	  network: network_json,
	  //edgeLabelsVisible: true, 
	  //layout: "Circle",
	  visualStyle: visual_style,
	  panZoomControlVisible: true
	}
	vis.draw(draw_options);
	$("#spinner").toggle();
}