function run_spearman(email){
	console.log(email);
	$('#spinner').toggle();
	gene = $('#s_genename').val();
	cutoff = $('#s_cutoff').val();
	method = $('#s_method').val();
	op = $( "select#s_op option:selected").val();
	var selected = new Array();
	// Get list of cob experiments
	$('#list:checked').each(function() {
		console.log($(this).val());
		selected.push($(this).val());
	});
	//console.log(selected);
	exp = selected.join("XXX");
	//console.log(gene, cutoff, op, exp, method);
	//$("#geneExpDiv").html("Email will be sent to you at "+ email);
	//$("#geneExpDiv").html("<br><div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check\"></i><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><b>Information:</b> Email will be sent to you at "+ email +"</div>");

	submitSpearman("/brew/search.html", gene, cutoff, op, exp, method, email, 0)
	//submitSpearman("/R/brew/test_ajax_spearman.html", gene, cutoff, op, exp, method, email)
	//submitEmailSpearman(gene, cutoff, op, exp, method, email)
	//submitemail(email);
	//$('#spinner').toggle();
}

function run_spearman_log(email){
	console.log(email);
	$('#spinner').toggle();
	gene = $('#s_genename').val();
	cutoff = $('#s_cutoff').val();
	method = $('#s_method').val();
	op = $( "select#s_op option:selected").val();
	var selected = new Array();
	$("input[type=checkbox][name=list]").each(function() {
		//console.log($(this).is(":checked"));
		if($(this).is(":checked")){
		//if($(this).checked){
			selected.push($(this).val());
		}
	});
	//console.log(selected);
	exp = selected.join("XXX");
	//console.log(gene, cutoff, op, exp, method);
	//$("#geneExpDiv").html("Email will be sent to you at "+ email);
	//$("#geneExpDiv").html("<br><div class=\"alert alert-success alert-dismissable\"><i class=\"fa fa-check\"></i><button type=\"button\" class=\"close\" data-dismiss=\"alert\" aria-hidden=\"true\">&times;</button><b>Information:</b> Email will be sent to you at "+ email +"</div>");

	submitSpearman("/R/brew/ajax_spearman_desc.html", gene, cutoff, op, exp, method, email, 1)
	//submitSpearman("/R/brew/test_ajax_spearman.html", gene, cutoff, op, exp, method, email)
	//submitEmailSpearman(gene, cutoff, op, exp, method, email)
	//submitemail(email);
	//$('#spinner').toggle();
}

function submitemail(email){
	$.ajax({
		url: "/email.php",
		data: {
			email: email
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			$("#geneExpDiv").html("Email has been sent.");
		},
		//* If error. show the error in console.log
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function submitEmailSpearman(gene, cutoff, op, exp, method, email){
	$.ajax({
		url: "/php/pages/profile_search.php",
		data: {
			gene: gene,
			cutoff: cutoff,
			op: op,
			exp: exp,
			method: method,
			email: email
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			console.log(data);
		},
		//* If error. show the error in console.log
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}


function submitSpearman(file, gene, cutoff, op, exp, method, email, log){
	$.ajax({
		url: file,
		data: {
			gene: gene,
			cutoff: cutoff,
			op: op,
			exp: exp,
			method: method,
			log: log
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			out = data[0].replace('\\', '');
			$('#geneExpDiv').empty(0)
			$('#geneExpDiv').html(data);
			//console.log($('#geneExpDiv').html());
			$('#combine_graph').css({'display': 'block'});
			getTableRowValues();
			$('#spinner').toggle();
		},
		//* If error. show the error in console.log
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

function return_thead(){
	var exp = [];
	//$('#resulttable > tbody > tr').each(function(){
	$('#resulttable > thead > tr').each(function(){
		//console.log(this);
		$(this).children('th').each(function(key, val){
			exp.push($(val).html());
			//console.log(exp);
		});
	});
	return exp;
}

function getTableRowValues(){
	var exp = return_thead();
	//console.log(exp);
	$('#resulttable > tbody > tr').each(function(){
		//console.log(this);
		var arr = [];
		$(this).children('td').each(function(key, val){
			//console.log(key, exp[key], val, parseInt($(val).html()));
			if(isNaN(parseInt($(val).html()))){
				return;
			}
			//if(exp[key].toLowerCase().indexOf("<input id=\"geneselect\" type=\"checkbox\">") >= 0){
			//	return;
			//}
			if($(val).html() == "NA"){
				arr.push([exp[key], 0]);
			} else {
				arr.push([exp[key], parseInt($(val).html())]);
			}
			//console.log(arr);
		});
		//id = $(this).children('td').eq(1).html();
		id = $($(this).children('td').eq(1).html()).html();
		//console.log(id);
		$(this).click(function(){
			//createGraph(arr);
			//id = $(this).children('td').eq(1).html();
			id = $($(this).children('td').eq(1).html()).html();
			createGraph(arr, id);
			//console.log(id);
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
	//console.log(arr);
	$('#canvas').empty();
	var myChart = new JSChart('canvas', 'line');
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
	$('#resulttable #geneselect').each(function(){
		if(this.checked){
			tr = $(this).parent().parent();
			//console.log($(this).parent().parent());
			var exp = return_thead();
			$(tr).each(function(){
				var arr = [];
				id = $($(this).children('td').eq(1).html()).html();
				arr.push(id);
				$(this).children('td').each(function(key, val){
					//console.log(key, exp[key], val);
					if(isNaN(parseInt($(val).html()))){
						return;
					}
					if($(val).html() == "NA"){
						arr.push([exp[key], 0]);
					} else {
						arr.push([exp[key], parseInt($(val).html())]);
					}
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
	$('#canvas').empty();
	var myChart = new JSChart('canvas', 'line');
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
	myChart.setAxisNameY('RPKM');
	myChart.setAxisValuesAngle(45);
	myChart.setLegendShow(true);
	myChart.setAxisPaddingBottom(70);
	myChart.draw();
}

var genecount = 0;

function getAllGeneList(){
	var arrSelect = [];
	$('#resulttable #geneselect').each(function(){
		genecount = genecount + 1;
		//console.log(genecount);
		tr = $(this).parent().parent();
		//console.log($(this).parent().parent());
		var exp = return_thead();
		//console.log(exp);
		$(tr).each(function(){
		var arr = [];
		id = $($(this).children('td').eq(1).html()).html();
		//id = $($(this).next().html()).html();
		//console.log($(this));
		arr.push(id);
		//$(this).parent().children('td').each(function(key, val){
		$(this).children('td').each(function(key, val){
			//console.log(key, exp[key], val);
			a = 3
			if(key < a){
				return;
			}
			if(isNaN(parseInt($(val).html()))){
				//return;
			}
			//console.log(key, exp[key], val);
			if($(val).html() == "NA"){
				arr.push([exp[key], 0]);
			} else {
				arr.push([exp[key], parseInt($(val).html())]);
			}
		});
		//var gene = $(this).children('th').next().html();
		//console.log(arr);
		//arrSelect[gene] = arr;
		arrSelect.push(arr);
		});
	});
	createCombineGraph(arrSelect);
}

function createCombineGraph(arrSelect){
	  //console.log(arrSelect);
	  $('#canvas').empty();
	  var myChart = new JSChart('canvas', 'line');
	  $.each(arrSelect, function(key, val){
	    var gene = val.shift();
	    //console.log(gene);
	    myChart.setDataArray(val, gene);
	    var color = Math.floor(Math.random()*16777215).toString(16);
	    if(color.length < 6){
	      color = '800000'
	    }
	    myChart.setLineColor('#'+ color, gene);
	  });
	  myChart.setSize(500 + (20*genecount), 300 + (10*genecount));
	  myChart.setTitle('Chart');
	  myChart.setAxisNameX('Experiments');
	  myChart.setAxisNameY('RPKM');
	  myChart.setAxisValuesAngle(45);
	  myChart.setLegendShow(true);
	  myChart.setAxisPaddingBottom(70);
	  myChart.draw();
	  //console.log(genecount);
	  genecount = 0;
	}
