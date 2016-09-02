function run_heatmap(){
	$('#spinner').toggle();
	$('#div_heatmap').empty();
	gene = $('#hm_genelist').val();
	email = $("input[name*='run_heatmap_path']").val();
	dist_method = $( "select#heatmap_dist_method option:selected").val();
	hclust_method = $( "select#heatmap_hclust_method option:selected").val();
	var selected = new Array();
	$('input[type=checkbox][id=list]').each(function() {
		//console.log($(this).is(":checked"));
		if($(this).is(":checked")){
		//if($(this).checked){
			selected.push($(this).val());
		}
	});
	//console.log(selected);
	if(selected.length < 2){
		$('#div_heatmap').html("<span style='color:red'>ERROR: Minimum 2 experiments required for clustering.</span>");
		$('#spinner').toggle();
		return;
	}
	gene_arr = gene.split("\n");
	if(gene_arr[gene_arr.length - 1] == ""){
		gene_arr.pop();
	}
	if(gene_arr.length < 3){
		$('#div_heatmap').html("<span style='color:red'>ERROR: Minimum 3 Gene IDs required for clustering.</span>");
		$('#spinner').toggle();
		return;
	}
	
	exp = selected.join("XXX");
	//console.log(exp);
	submitHeatmap("/brew/heatmap.html", gene, exp, email, dist_method, hclust_method);
}

function submitHeatmap(file, gene, exp, email, dist_method, hclust_method){
	stringname = randomString();
	$.ajax({
		url: file,
		data: {
			gene: gene,
			exp: exp,
			email: email,
			hclust_method: hclust_method,
			dist_method: dist_method,
			fname: stringname
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			f = data;
			console.log(f);
			$('#div_heatmap').empty();
			//f = f.replace("/media/storage/www/maize/newmaize", '');
			//f = f.replace("/var/www/html/maize", '');
			img = $('<img>');
			img.attr('src', f);
			//img.appendTo('#div_heatmap');
			divstart = '<div class="box box-success"><div class="box-body">';
			divend = '</div></div>';
			html = divstart + '<img src="' + f + '">' + divend;
			$('#div_heatmap').html(html);
			$('#spinner').toggle();	
		},
		//* If error. show the error in console.log
		error: function(XMLHttpRequest, textStatus, errorThrown) {
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
