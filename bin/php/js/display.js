function updatetable(start){
	$('#spinner').css({'display': 'block'});
    // Get list of cob experiments
	var list = new Array();
	$('#list:checked').each(function() {
		list.push($(this).val());
	});
    id = $('#id_text').val();

	data = {list: list, id: id, start: start};

	//gettable();
	ajax_request('ajax_display.php', data);
	//$('#div_button').css({'display': 'block'});
	//displayfilter();
	$('#spinner').css({'display': 'none'});
}

function updatetablelog(start){
	$('#spinner').css({'display': 'block'});
	// Get list of cob experiments
	var list = new Array();
	$('#list:checked').each(function() {
		list.push($(this).val());
	});
	id = $('#id_text').val();

	//gettablelog(exp, op, val, list, chipseqlist, id, start);
	//$('#div_button').css({'display': 'block'});
	//displayfilter();
	data = {list: list, id: id, start: start, log: 1};
	ajax_request('ajax_display.php', data);
	$('#spinner').css({'display': 'none'});
}


var genecount = 0;
function hasgenedescriptions(){
	var hasdesc = $("#hasdesc").val();
	if(hasdesc == 1){
		return true;
	}
	else{
		return false;
	}
}

function getTableRowValues(){
	var exp = [];
	$('#display_resulttable > thead > tr').each(function(){
		$(this).children('th').each(function(key, val){
			exp.push($(val).html());
		});
	});
	exp.shift();
	exp.shift();
	if(hasgenedescriptions()) {
		exp.shift();
	}
	$('#display_resulttable > tbody > tr').each(function(){
		//console.log(this);
		var arr = [];
		$(this).children('td').each(function(key, val){
			//console.log(key, exp[key], val, patt.test($(val).html()));
			if(exp[key].toLowerCase().indexOf("<input id=\"geneselect\" type=\"checkbox\">") >= 0){
				return;
			}
			if($(val).html() == "NA"){
				arr.push([exp[key], 0]);
			} else {
				arr.push([exp[key], parseInt($(val).html())]);
			}
		});
		//console.log(exp);
		//id = $(this).children('th').eq(1).html();
		id = $(this).children('th').eq(1).html();
		//console.log($($(this).children('th').eq(1).html()).html());
		$(this).click(function(){
			//createGraph(arr);
			//id = $(this).children('th').eq(1).html();
			id = $($(this).children('th').eq(1)).html();
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
	console.log(arr);
	$('#display_canvas').empty();
	var myChart = new JSChart('display_canvas', 'line');
	myChart.setSize(500 + (20*genecount), 300 + (10*genecount));
	myChart.setDataArray(arr);
	myChart.setTitle('Chart for ' + exp);
	myChart.setAxisNameX('Experiments');
	myChart.setAxisNameY('RPKM');
	myChart.setAxisValuesAngle(45);
	//myChart.setLegendShow(true);
	myChart.setAxisPaddingBottom(70);
	myChart.draw();
	genecount = 0
}

function getGeneList(){
	var arrSelect = [];
	$('#display_resulttable #geneselect').each(function(){
		if(this.checked){
			genecount = genecount + 1;
			tr = $(this).parent().parent();
			//console.log($(this).parent().parent());
			var exp = [];
			$('#display_resulttable > thead > tr').each(function(){
				$(this).children('th').each(function(key, val){
					exp.push($(val).html());
				});
			});
			console.log(exp);
			exp.shift();
			exp.shift();
			if(hasgenedescriptions()) {
				exp.shift();
			}
			$(tr).each(function(){
				//console.log(this);
				var arr = [];
				id = $($(this).children('th').eq(1)).html();
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
	myChart.setSize(500 + (20*genecount), 300 + (10*genecount));
	myChart.setTitle('Chart');
	myChart.setAxisNameX('Experiments');
	myChart.setAxisNameY('RPKM');
	myChart.setAxisValuesAngle(45);
	myChart.setLegendShow(true);
	myChart.setAxisPaddingBottom(70);
	myChart.draw();
	genecount = 0
}

function getAllGeneList(){
	var arrSelect = [];
	genecount = 0;
	$('#display_resulttable #geneselect').each(function(){
		//if(this.checked){
			genecount = genecount + 1;
			tr = $(this).parent().parent();
			//console.log($(this).parent().parent());
			var exp = [];
			$('#display_resulttable > thead > tr').each(function(){
				$(this).children('th').each(function(key, val){
					exp.push($(val).html());
				});
			});
			exp.shift();
			exp.shift();
			if(hasgenedescriptions()) {
				exp.shift();
			}
			$(tr).each(function(){
				//console.log(this);
				var arr = [];
				id = $($(this).children('th').eq(1)).html();
				arr.push(id);
				console.log(id);
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
				//arrSelect[gene] = arr;
				arrSelect.push(arr);
			});
		//}
	});
	console.log(arrSelect);
	createCombineGraph(arrSelect);
}
