function loadTextArea(text){
	$("#pr_text").val(text);
}
function checkbox_values(){
	var vals = [];
	$("#exp :checked").each(function(){
		vals.push($(this).val());
	});
	return vals;
}

function getPromoters(){
	up = $("#pr_up").val();
	id = $("#pr_text").val();
	cb = checkbox_values();
	console.log(up, id, cb);
	ajaxPromoters(up, id, cb);
}

function check_uncheck(){
	var cb = $("input[name=selected_ids]");
	if($("#checkbox_checkall").is(":checked")){
		cb.prop("checked", true);
	}else{
		cb.prop("checked", false);
	}
}

function ajaxPromoters(up, id, cb){
	$("#output").empty();
	$.ajax({
	url: 'ajax_mast.php',
	data: {
		up: up,
		id: id,
		cb: cb
	},
	dataType: 'json',
	method: 'GET',
	success: function(data){
		//console.log(data);
		$("#output").html(data);
	},
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus+" - "+errorThrown);
		console.log(XMLHttpRequest.responseText);
	}
	});
};
function checkbox_checkall_uncheck(){
	$("#checkbox_checkall").prop("checked", false);
}
function getMast(){
	$('#spinner').toggle();
	id = $("#pr_text").val();
	//console.log(id);
	arr_ids = []
	$("input[name=selected_ids]:checked").each(function(){
		console.log($(this).val());
		arr_ids.push($(this).val());
	});
	if(arr_ids.length == 0){
		check_uncheck();
		id = $("#pr_text").val();
	} else {
		id = arr_ids.join("\n");
	}
	up = $("#pr_up").val();
	ajaxMast(up, id);
}

function ajaxMast(up, ids){
	$("#motif_result").empty();
	$("#spinner").css({'display': 'block'});
	$.ajax({
	url: 'php/ajax/mast.php',
	data: {
		up: up,
		ids: ids 
	},
	dataType: 'json',
	method: 'GET',
	success: function(data){
		$('#spinner').toggle();
		console.log(data);
		$("#motif_result").html(data);
	},
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		$("#spinner").css({'display': 'none'});
		console.log(textStatus+" - "+errorThrown);
		console.log(XMLHttpRequest.responseText);
	}
	});
}

function getMeme(){
	$('#spinner').toggle();
	id = $("#pr_text").val();
	//console.log(id);
	arr_ids = []
	$("input[name=selected_ids]:checked").each(function(){
		console.log($(this).val());
		arr_ids.push($(this).val());
	});
	console.log(arr_ids.length);
	if(arr_ids.length == 0){
		check_uncheck();
		id = $("#pr_text").val();
	} else {
		id = arr_ids.join("\n");
	}
	up = $("#pr_up").val();
	ajaxMeme(up, id);
}

function ajaxMeme(up, ids){
	$("#motif_result").empty();
	$("#spinner").css({'display': 'block'});
	$.ajax({
	url: 'php/ajax/meme.peaks.php',
	data: {
		up: up,
		ids: ids 
	},
	dataType: 'json',
	method: 'GET',
	success: function(data){
		$('#spinner').toggle();
		console.log(data);
		$("#motif_result").html(data);
	},
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		$("#spinner").css({'display': 'none'});
		console.log(textStatus+" - "+errorThrown);
		console.log(XMLHttpRequest.responseText);
	}
	});
}
