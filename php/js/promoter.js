function loadTextArea(text){
	$("#pr_text").val(text);
}
function getPromoters(){
	up = $("#pr_up").val();
	id = $("#pr_text").val();
	console.log(up, id);
	//ajaxPromoters(up, id);
    data = {up: up, id: id }
    ajax_request('ajax_promoter.php', data);
}
function check_uncheck(){
	var cb = $("input[name=selected_ids]");
	if($("#checkbox_checkall").is(":checked")){
		cb.prop("checked", true);
	}else{
		cb.prop("checked", false);
	}
}

function checkbox_checkall_uncheck(){
	$("#checkbox_checkall").prop("checked", false);
}
function getMotifs(){
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
	console.log(id);
	ajaxMeme(up, id);
}
function ajaxMeme(up, ids){
	$("#motif_result").empty();
	$("#spinner").css({'display': 'block'});
	$.ajax({
	url: 'meme.php',
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