function run_hclust(){
	$('#spinner').toggle();
	console.log($('#div_hclust').html());
	$('#div_hclust').empty();
	gene = $('#hc_genelist').val();
	email = $("input[name*='run_hclust_path']").val();
	method = $( "select#hc_method option:selected").val();
	dist = $( "select#hc_dist option:selected").val();
	var selected = new Array();
	$('input[type=checkbox][id=list]').each(function() {
		//console.log($(this).is(":checked"));
		if($(this).is(":checked")){
		//if($(this).checked){
			selected.push($(this).val());
		}
	});
	if(selected.length < 2){
		$('#div_hclust').html("<span style='color:red'>ERROR: Minimum 2 experiments required for clustering.</span>");
		$('#spinner').toggle();
		return;
	}
	gene_arr = gene.split("\n");
	if(gene_arr[gene_arr.length - 1] == ""){
		gene_arr.pop();
	}
	if(gene_arr.length < 3){
		$('#div_hclust').html("<span style='color:red'>ERROR: Minimum 3 Gene IDs required for clustering.</span>");
		$('#spinner').toggle();
		return;
	}
	
	exp = selected.join("XXX");
	//console.log(exp);
	submitHclust("/brew/cluster.html", gene, exp, email, method, dist);
}

function submitHclust(file, gene, exp, email, method, dist){
	stringname = randomString();
	$.ajax({
		url: file,
		data: {
			gene: gene,
			exp: exp,
			email: email,
			method: method,
			dist: dist,
			fname: stringname
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			f = data;
			//f = f.replace("/media/storage/www/maize/newmaize", '');
			//f = f.replace("/var/www/html/maize", '');
			console.log(f);
			img = $('<img>');
			img.attr('src', f);
			//img.appendTo('#div_hclust');
			divstart = '<div class="box box-success"><div class="box-body">';
			divend = '</div></div>';
			html = divstart + '<img src="' + f + '">' + divend;
			console.log(html);
			$('#div_hclust').html(html);
			$('#spinner').toggle();
		},
		//* If error. show the error in console.log
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			$('#spinner').toggle();
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function randomString() {
	var chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz";
	var string_length = 8;
	var randomstring = '';
	for (var i=0; i<string_length; i++) {
		var rnum = Math.floor(Math.random() * chars.length);
		randomstring += chars.substring(rnum,rnum+1);
	}
	return randomstring;
}
