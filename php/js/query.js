function get_comp_select(genome){
	console.log($(genome).val());
	var vlist = $(genome).val();
	var vitems = vlist.split("__"); 
	window.open("http://plants.ensembl.org/Zea_mays/Location/Compara_Alignments/Image?align="+vitems[0]+";r="+vitems[1]+":"+vitems[2]+"-"+vitems[3]);
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

/**
 * Collect the query options:
 * - chromosome
 * - start
 * - end
 * - user
 *
 * Get the list of gene table
 */
function start_search(){
	chr = $("select#cart_chr option:selected").val();
	start = $('#cart_start').val();
	end = $('#cart_end').val();
	user = $('#cart_user').val();
	//get_genetable(cart_chr, cart_start, cart_end, cart_user);
	data = {chr: chr, start: start, end: end, user: user};
	ajax_request('genetable.php', data);
	ajax_request('rnaseqtable.php', data);
	ajax_request('chipseqtable.php', data);
	ajax_request('pathway.php', data);
	ajax_request('ortholog.php', data);
	$('#qtabs').css({'display': 'block'});
}

function start_qtl_search(){
	cart_chr = $("select#cart_qtl option:selected").val();
	arr = cart_chr.split(":");
	get_genetable(arr[0], arr[1], arr[2]);
}

function get_exp_table(){
	var list = get_selected_ids();
	get_experiment_table(list);
}

function get_experiment_table(list){
	$.ajax({
		url: 'php/ajax_exp_table.php',
		data: {
			list: list
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			//console.log(data);
			addTab("Experiments", data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
};

function get_rnaseq_table(){
	var list = get_selected_ids();
	get_rnaseq_data(list);
	//$('#rnaseq_output').css({'display': 'block'});
}

function get_profile_display(){
	var list = get_selected_ids();
	//list = list.join("\\n");
	window.location.replace("http://maizeinflorescence.org/ajax_display.php?genelist=" + list);
	//get_profile_table(list);
	//$('#rnaseq_output').css({'display': 'block'});
}

function get_profile_table(list){
	//* Use ajax to get JSON object
	$.ajax({
	//* file
	//url: 'gettable.php',
	url: '/php/ajax/ajax_display.php',
	//* data required
	data: {
		id: list
	},
	//* datatype returned
	dataType: 'json',
	//* request method
	method: 'GET',
	//* If success
	success: function(data){
		//console.log(data);
		addTab(data);
		//$('#result').html(data + '<button onclick="getGeneList()">Combine graph</button><br><div id="display_canvas"></div>');
		//getTableRowValues();
	},
	//* If error. show the error in console.log
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus+" - "+errorThrown);
		console.log(XMLHttpRequest.responseText);
	}
	});
}


function get_snp_table(){
	var list = get_selected_ids();
	get_snp_data(list);
	//$('#rnaseq_output').css({'display': 'block'});
}

function get_gsea_table(){
	$("#spinner").css({'display': 'block'});
	var list = get_selected_ids();
	console.log(list);
	get_gsea_data(list);
}

function get_pathway_table(){
	$("#spinner").css({'display': 'block'});
	var list = get_selected_ids();
	console.log(list);
	get_pathway_data(list);
}

function get_selected_ids(){
	var list = new Array();
	$.each($("input[name='genelist[]']:checked"), function() {
		list.push($(this).val());
		//console.log($(this).val());
	});
	return list;
}

function get_ortholog_table(){
	var list = new Array();
	var genus = new Array();
	$.each($("input[name='orthologlist']:checked"), function() {
		genus.push($(this).val());
		console.log("-------------------------------", $(this).val());
	});
	$.each($("input[name='genelist[]']:checked"), function() {
		list.push($(this).val());
		//console.log($(this).val());
	});
	//console.log(list);
	get_ortholog_data(list,genus);
	//$('#rnaseq_output').css({'display': 'block'});
}
function get_ortholog_table_only(){
	var list = new Array();
	var genus = new Array();
	$.each($("input[name='orthologlist']:checked"), function() {
		genus.push($(this).val());
		console.log("-------------------------------", $(this).val());
	});
	$.each($("input[name='genelist[]']:checked"), function() {
		list.push($(this).val());
		//console.log($(this).val());
	});
	//console.log(list);
	get_ortholog_data_only(list,genus);
	//$('#rnaseq_output').css({'display': 'block'});
}

function get_chipseq_table(){
	var list = get_selected_ids();
	get_chipseq_data(list);
	$('#chipseq_output').css({'display': 'block'});
}
function get_rnaseq_chipseq(name){
	var list = new Array();
	$.each($("input[name='" + name + "']:checked"), function() {
		check = $.inArray($(this).val(), list);
		if(check < 0){
			list.push($(this).val());
		}
		//console.log($(this).val());
	});
	console.log(list);
	get_chipseq_data(list);
	$('#chipseq_table_output').css({'display': 'block'});
}
function get_chipseq_rnaseq(name){
	var list = new Array();
	$.each($("input[name='" + name + "']:checked"), function() {
		check = $.inArray($(this).val(), list);
		if(check < 0){
			list.push($(this).val());
		}
		//console.log($(this).val());
	});
	console.log(list);
	get_rnaseq_data(list);
	$('#rnaseq_table_output').css({'display': 'block'});
}
function get_rnaseq_data(list){
	$.ajax({
		url: 'php/ajax/query_rnaseq_table.php',
		data: {
			list: list
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			//console.log(data);
			addTab(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}
function get_chipseq_data(list){
	$.ajax({
		url: 'php/ajax/query_chipseq_table.php',
		data: {
			list: list
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			//console.log(data);
			addTab(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
};

function get_snp_data(list){
	$.ajax({
		url: 'php/ajax/query_snp_table.php',
		data: {
			list: list
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			console.log(data);
			addTab(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function get_gsea_data(list){
	$.ajax({
		url: 'php/ajax/query_gsea_table.php',
		data: {
			list: list
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			console.log(data);
			addTab(data);
			$("#spinner").css({'display': 'none'});
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function get_pathway_data(list){
	$.ajax({
		url: 'php/ajax/query_pathway_table.php',
		data: {
			list: list
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			console.log(data);
			addTab(data);
			$("#spinner").css({'display': 'none'});
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function get_ortholog_data(list, genus){
	$.ajax({
		url: 'php/ajax/query_ortholog_table.php',
		data: {
			list: list,
			genus: genus
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			//console.log(data);
			addTab(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
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

function genelist_selectall(someid, eachid){
	//select_name = $(someid).attr("name");
	//var check = $("input[name='genelist_selectall']").is(":checked");
	//var check = $("input[name='" + select_name +"']").is(":checked");
	var check = $(someid).is(":checked");
	$.each($("input[name='" + eachid + "']"), function() {
		//list.push($(this).val());
		console.log("this is " + check);
		if (check) {
			$(this).prop('checked', true);
		} else {
			$(this).prop('checked', false);
		} 
	});
}
function chipseq_selectall(someid, eachid){
	//select_name = $(someid).attr("name");
	//var check = $("input[name='genelist_selectall']").is(":checked");
	//var check = $("input[name='" + select_name +"']").is(":checked");
	var check = $(someid).is(":checked");
	$.each($("input[name='" + eachid + "']"), function() {
		//list.push($(this).val());
		console.log("this is " + check);
		if (check) {
			$(this).prop('checked', true);
		} else {
			$(this).prop('checked', false);
		} 
	});
}

var data = {
		'tassel_dev': ['1-2mm vs. 3-4mm', '3-4mm vs. 5-7mm'],
		'ear_dev': ['mid vs. base', 'tip vs. mid'],
		'mut_series': ['ra1_1mm vs. ra1_2mm', 'ra2_1mm vs. ra2_2mm', 'ra3_1mm vs. ra3_2mm', 'wt vs. ra1', 'wt vs. ra2', 'wt vs. ra3', 'wt vs. tas', 'wt_1mm vs. wt_2mm'],
};

function correctSelect(sel){
	var key = sel.value;
	var vals = [];

	switch(key) {
	case 'tassel_dev':
		vals = data.tassel_dev;
		break;
	case 'ear_dev':
		vals = data.ear_dev;
		break;
	case 'mut_series':
		vals = data.mut_series;
		break;
	default:
		vals = ['Please choose from above'];
	}

	var $secondChoice = $("#secondchoice");
	$secondChoice.empty();
	$.each(vals, function(index, value) {
		$secondChoice.append("<option value='" + value + "'>" + value + "</option>");
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
			addTab(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}
function filter_chipseq(){
	/**
	var offset = $("input[name='chipseqoffset']").val();
	var string = $("input[name='chipseqstring']").val();
	console.log(offset, string);
	console.log("kashi");
	get_chipseq_offset(string, offset);*/
	chr = $("select#cart_chr option:selected").val();
	start = $('#cart_start').val();
	end = $('#cart_end').val();
	user = $('#cart_user').val();
	offset = $("input[name='chipseqoffset']").val();
	//get_genetable(cart_chr, cart_start, cart_end, cart_user);
	data = {chr: chr, start: start, end: end, user: user, offset: offset};
	//ajax_request('genetable.php', data);
	//ajax_request('rnaseqtable.php', data);
	ajax_request('chipseqtable.php', data);
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
			console.log(data);
			addTab(data);
			//addTab("ChIPseq (filter)", data);
			//$("#" + offset).tablesorter({
			//	headers: {0: {sorter: false}}
			//});
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
};

function get_save_list(){
	$('#saveas_div').toggle();
	//console.log('kashi');
}

function saveas_save(){
	var list = get_selected_ids();
	var filename = $('#saveas_text').val();
	ajax_save_list(list, filename);
}

function ajax_save_list(list, name){
	$.ajax({
		url: 'save_list.php',
		data: {
			list: list,
			name: name
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			console.log(data);
			if(data['result'] == "error"){
				$('#saveas_info').html("<span style='color:red'>" + data['msg']);
			} else {
				$('#saveas_info').html("<span style='color:green'>" + data['msg']);
				$('#saveas_div').fadeOut('slow');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
};

function saveas_close(){
	$('#saveas_div').toggle();
}
