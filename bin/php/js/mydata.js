function goterms(list){
	$.ajax({
		url: '/php/ajax/query_go.php',
		data: {
			list: list
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			$('#result').empty();
			//console.log(data);
			$('#result').html(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function plantgsea(list){
	$("#spinner").css({'display': 'block'});
	$.ajax({
		url: '/php/ajax/query_gsea_table.php',
		data: {
			list: list
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			$('#result').empty();
			//console.log(data);
			$('#result').html(data);
			$("#spinner").css({'display': 'none'});
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function pathways(list){
	$("#spinner").css({'display': 'block'});
	$.ajax({
		url: '/php/ajax/query_pathway_table.php',
		data: {
			list: list
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			$('#result').empty();
			//console.log(data);
			$('#result').html(data);
			$("#spinner").css({'display': 'none'});
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function gene_description(list){
	get_genes(list);
}

function get_genes(list){
	$.ajax({
		url: '/php/ajax/query_description.php',
		data: {
			list: list
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			$('#result').empty();
			//console.log(data);
			$('#result').html(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function profile_display(list){
	gettable(list);
}

function box_collapse(ok){
	var box = $(ok).parent().parent().parent();
	//console.log(box);
	var boolean = false;
	$(box).children('div').each(function(){
		//console.log(this);
		if(boolean){ $(this).toggle() };
		boolean = true;
	});
	//console.log("-------");
}

function box_close(ok){
	var box = $(ok).parent().parent().parent();
	//console.log(box);
	$(box).remove();
}

function rnaseq_show(list){
	getrnaseq(list);
}

function chipseq_show(list){
	console.log("kashi");
	getchipseq(list);
}

function snp_show(list){
	console.log("kashi");
	getsnp(list);
}

function ortholog_show(list){
	console.log("kashi");
	getortholog(list);
}

function getortholog(list){
	//* Use ajax to get JSON object
	$.ajax({
	//* file
	//url: 'gettable.php',
	url: '/php/ajax/query_ortholog_table.php',
	//* data required
	data: {
		list: list
	},
	//* datatype returned
	dataType: 'json',
	//* request method
	method: 'GET',
	//* If success
	success: function(data){
		$('#result').empty();
		//console.log(data);
		$('#result').html(data);
		//display_getTableRowValues();
	},
	//* If error. show the error in console.log
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus+" - "+errorThrown);
		console.log(XMLHttpRequest.responseText);
	}
	});
};

function getsnp(list){
	//* Use ajax to get JSON object
	$.ajax({
	//* file
	//url: 'gettable.php',
	url: '/php/ajax/query_snp_table.php',
	//* data required
	data: {
		list: list
	},
	//* datatype returned
	dataType: 'json',
	//* request method
	method: 'GET',
	//* If success
	success: function(data){
		$('#result').empty();
		//console.log(data);
		$('#result').html(data);
		//display_getTableRowValues();
	},
	//* If error. show the error in console.log
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus+" - "+errorThrown);
		console.log(XMLHttpRequest.responseText);
	}
	});
};


function getchipseq(list){
	//* Use ajax to get JSON object
	$.ajax({
	//* file
	//url: 'gettable.php',
	url: '/php/ajax/query_chipseq_table.php',
	//* data required
	data: {
		list: list
	},
	//* datatype returned
	dataType: 'json',
	//* request method
	method: 'GET',
	//* If success
	success: function(data){
		$('#result').empty();
		//console.log(data);
		$('#result').html(data);
		//display_getTableRowValues();
	},
	//* If error. show the error in console.log
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus+" - "+errorThrown);
		console.log(XMLHttpRequest.responseText);
	}
	});
};


function getrnaseq(list){
	//* Use ajax to get JSON object
	$.ajax({
	//* file
	//url: 'gettable.php',
	url: '/php/ajax/query_rnaseq_table.php',
	//* data required
	data: {
		list: list
	},
	//* datatype returned
	dataType: 'json',
	//* request method
	method: 'GET',
	//* If success
	success: function(data){
		$('#result').empty();
		//console.log(data);
		$('#result').html(data);
		//display_getTableRowValues();
	},
	//* If error. show the error in console.log
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus+" - "+errorThrown);
		console.log(XMLHttpRequest.responseText);
	}
	});
};

function gettable(id){
	//* Use ajax to get JSON object
	$.ajax({
	//* file
	//url: 'gettable.php',
	url: '/php/ajax/profile_display.php',
	//* data required
	data: {
		id: id
	},
	//* datatype returned
	dataType: 'json',
	//* request method
	method: 'GET',
	//* If success
	success: function(data){
		//console.log(data);
		$('#result').html(data + '<button onclick="getGeneList()">Combine graph</button><br><div id="display_canvas"></div>');
		getTableRowValues();
	},
	//* If error. show the error in console.log
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus+" - "+errorThrown);
		console.log(XMLHttpRequest.responseText);
	}
	});
};


function show(file){
	show_file(file);
}

function edit(file){
	//console.log(file);
	$('#edit_div').toggle();
	get_edit_form(file);
}

function get_edit_form(file){
	$.ajax({
		url: '/mydata/ajax_edit.php',
		data: {
			file: file
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			//console.log(data);
			$("#edit_info").html(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function save_file(){
	oldname = $('#old_filename').val();
	newname = $('#new_filename').val();
	list = $('#list').val();
	console.log(oldname);
	console.log(newname);
	console.log(list);
	updatefile(oldname, newname, list);
}

function upload_box(){
	$('#saveas_div').toggle();
}

function saveas_close(){
	$('#saveas_div').toggle();
}
function edit_close(){
	$('#edit_div').toggle();
}

function updatefile(oldname, newname, list){
	$.ajax({
		url: '/mydata/ajax_update.php',
		data: {
			newname: newname,
			oldname: oldname,
			list: list
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			console.log(data);
			//$("#next").after(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function deletefile(button, file){
	console.log("delete");
	var html = "&nbsp;&nbsp;Are you sure? <button class=\"btn btn-danger\" onclick='deletenow(\"" + file + "\")'>Yes</button>/<button class=\"btn btn-danger\" onclick='canceldelete()'>No</button>";
	console.log(html);
	var span = $('<span/>').attr({'id': 'sure'}).html(html);
	$(button).after(span);
	console.log(span);
}

function canceldelete(){
	$('#sure').remove();
}

function deletenow(file){
	console.log(file);
	delete_file(file);
}

function show_file(file){
	$.ajax({
		url: '/mydata/ajax_show.php',
		data: {
			file: file
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			//console.log(data);
			$("#next").after(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
};

function delete_file(file){
	$.ajax({
		url: '/mydata/ajax_delete.php',
		data: {
			file: file
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			//console.log(data);
			//$("#next").after(data);
			//window.location.replace("http://maizeinflorescence.org/mydata.php");
			location.reload();
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
};

function getTableRowValues(){
	var exp = [];
	$('#display_resulttable > tbody > tr').each(function(){
		$(this).children('th').each(function(key, val){
			exp.push($(val).html());
		});
	});
	exp.shift();
	exp.shift();
	exp.shift();
	$('#display_resulttable > tbody > tr').each(function(){
		//console.log(this);
		var arr = [];
		$(this).children('td').each(function(key, val){
			//console.log(key, exp[key], val);
			if(exp[key].toLowerCase().indexOf("<input id=\"geneselect\" type=\"checkbox\">") >= 0){
				return;
			}
			arr.push([exp[key], parseInt($(val).html())]);
		});
		//id = $(this).children('th').eq(1).html();
		id = $($(this).children('th').eq(1).html()).html();
		//console.log($($(this).children('th').eq(1).html()).html());
		$(this).click(function(){
			//createGraph(arr);
			//id = $(this).children('th').eq(1).html();
			id = $($(this).children('th').eq(1).html()).html();
			createGraph(arr, id);
			//console.log(arr);
		});
		$(this).mouseover(function(){
			//$(this).children().css({"background-color": "2px solid red"});
		});
		$(this).mouseout(function(){
			//$(this).children().css({"border": "1px solid gainsboro"});
		});
	});
}

function createGraph(arr, exp){
	console.log(exp);
	$('#display_canvas').empty();
	var myChart = new JSChart('display_canvas', 'line');
	myChart.setDataArray(arr);
	myChart.setTitle('Chart for ' + exp);
	myChart.setAxisNameX('Experiments');
	myChart.setAxisNameY('RPKM');
	myChart.setAxisValuesAngle(45);
	//myChart.setLegendShow(true);
	myChart.setAxisPaddingBottom(70);
	myChart.draw();
}

function getGeneList(){
	var arrSelect = [];
	$('#display_resulttable #geneselect').each(function(){
		if(this.checked){
			tr = $(this).parent().parent();
			//console.log($(this).parent().parent());
			var exp = [];
			$('#display_resulttable > tbody > tr').each(function(){
				$(this).children('th').each(function(key, val){
					exp.push($(val).html());
				});
			});
			exp.shift();
			exp.shift();
			exp.shift();
			$(tr).each(function(){
				//console.log(this);
				var arr = [];
				id = $($(this).children('th').eq(1).html()).html();
				arr.push(id);
				$(this).children('td').each(function(key, val){
					//console.log(key, exp[key], val);
					if(isNaN(parseInt($(val).html()))){
						return;
					}
					arr.push([exp[key], parseInt($(val).html())]);
				});
				//var gene = $(this).children('th').next().html();
				//console.log(arr);
				//arrSelect[gene] = arr;
				arrSelect.push(arr);
			});
		}
	});
	if(arrSelect.length < 2){
		alert("Use checkbox to select more then 2 Gene ids to combine.");
	} else {
		createCombineGraph(arrSelect);
	}
	//console.log(arrSelect);
}

function createCombineGraph(arrSelect){
	//console.log(arrSelect);
	$('#display_canvas').empty();
	var myChart = new JSChart('display_canvas', 'line');
	$.each(arrSelect, function(key, val){
		var gene = val.shift();
		myChart.setDataArray(val, gene);
		var color = Math.floor(Math.random()*16777215).toString(16);
		if(color.length < 6){
			color = '800000'
		}
		myChart.setLineColor('#'+ color, gene);
	});
	myChart.setTitle('Chart');
	myChart.setAxisNameX('Experiments');
	myChart.setAxisNameY('RPKM');
	myChart.setAxisValuesAngle(45);
	myChart.setLegendShow(true);
	myChart.setAxisPaddingBottom(70);
	myChart.draw();
}

function get_ortholog_table_only(){
	var list = new Array();
	var genus = new Array();
	$.each($("input[name='orthologlist']:checked"), function() {
		genus.push($(this).val());
		//console.log("-------------------------------", $(this).val());
	});
	$.each($("input[name='genelist[]']:checked"), function() {
		list.push($(this).val());
		//console.log($(this).val());
	});
	list = $('#list').val();
	//console.log(list);
	get_ortholog_data_only(list,genus);
	//$('#rnaseq_output').css({'display': 'block'});
}
function get_ortholog_data_only(list, genus){
	$.ajax({
		url: 'php/ajax/query_ortholog_table_only.php',
		data: {
			list: list,
			genus: genus
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			//console.log(data);
			$('#ortholog_table').empty();
			$('#ortholog_table').html(data);
			//addTab(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function filter_that(ddivv){
	var series = new Array();
	//first = $("select#firstchoice option:selected").val();
	//second = $("select#secondchoice option:selected").val();
	series = $("select#series option:selected").val();
	string = $("input[name='string']").val();
	string = string.replace("'", "", "g");
	list = string.split(",");
	//console.log(first, second, list);
	console.log(series, list);
	//get_rnaseq_data_filter(list, first, second)
	get_rnaseq_data_filter(list, series)
}

//function get_rnaseq_data_filter(string, first, second){
function get_rnaseq_data_filter(string, series){
	$.ajax({
		url: 'php/ajax/query_rnaseq_table.php',
		data: {
			list: string,
			//first: first,
			//second: second
			series: series
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			$('#result').empty();
			//console.log(data);
			$('#result').html(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function filter_chipseq(){
	var offset = $("input[name='chipseqoffset']").val();
	var string = $("input[name='chipseqstring']").val();
	console.log(offset, string);
	console.log("kashi");
	get_chipseq_offset(string, offset);
}
function get_chipseq_offset(list, offset){
	$.ajax({
		url: '/php/ajax/query_chipseq_table.php',
		data: {
			list: list,
			offset: offset
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			$('#result').empty();
			//console.log(data);
			$('#result').html(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
};
