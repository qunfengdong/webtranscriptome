function loadTextArea(){
	$("#gotermslist").val('GRMZM2G005155\nGRMZM2G040600\nGRMZM2G109821\nGRMZM2G119782\nGRMZM2G151873\nGRMZM2G112176\nGRMZM2G133885\nGRMZM2G177828\nGRMZM2G012391\nGRMZM2G126239\nGRMZM2G440614\nGRMZM2G090300\nGRMZM2G037064\nGRMZM2G046676\nGRMZM2G014154\nGRMZM2G029912\nGRMZM2G416019\nGRMZM2G159950\nGRMZM2G101179\nGRMZM2G323936\nGRMZM5G889776\nGRMZM2G101689\nGRMZM2G086906\nGRMZM2G077131');
}

function goterm(){
	$('#spinner').css({'display': 'block'});
	var sel = $('#select_file option:selected').val();
	var pop = $('#select_pop option:selected').val();
	var list = $('#gotermslist').val();
	//console.log(sel);
	if(list == ''){
		get_goterms(sel, pop);
	} else {
		gotermlist()
	}
	//get_goterms(sel);
}

function gotermlist(){
	$('#spinner').css({'display': 'block'});
	var sel = $('#gotermslist').val();
	var pop = $('#select_pop option:selected').val();
	console.log(sel);
	//console.log(pop);
	$.ajax({
		url: 'ajax_gosave.php',
		data: {
			list: sel
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			//console.log(data);
			//$('#div_goterms').html(data);
			get_goterms(data, pop);
			//addTab(data);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
	//get_goterms(sel);
}


function get_goterms(name, pop){
	$.ajax({
		url: 'ajax_goterms.php',
		data: {
			name: name,
			pop: pop
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			//console.log(data);
			//console.log($('#div_goterms').html());
			$('#div_goterms').html(data);
			//addTab(data);
			$('#spinner').css({'display': 'none'});
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
};