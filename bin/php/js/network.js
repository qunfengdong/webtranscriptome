function cluster_table(){
	$('#spinner').toggle();
	val = $('#cluster_geneid').val();
	
	cluster_table_get(val);
}

function cluster_table_get(gene){
	//* Use ajax to get JSON object
	$.ajax({
	//* file
	url: '/query/ajax_network.php',
	//* data required
	data: {
		gene: gene
	},
	//* datatype returned
	dataType: 'json',
	//* request method
	method: 'GET',
	//* If success
	success: function(data){
		//console.log(data);
		$('#clusterDiv').html(data);
		$('#spinner').toggle();
	},
	//* If error. show the error in console.log
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus+" - "+errorThrown);
		console.log(XMLHttpRequest.responseText);
	}
	});
};

function getClusters(){
	$('#spinner').toggle();
	var sel = $('#select_file option:selected').val();
	console.log(sel);
	get_cluster_table(sel);
}

function get_cluster_table(sel){
	//* Use ajax to get JSON object
	$.ajax({
	//* file
	url: 'php/ajax/networkcluster.php',
	//* data required
	data: {
		file: sel
	},
	//* datatype returned
	dataType: 'json',
	//* request method
	method: 'GET',
	//* If success
	success: function(data){
		//console.log(data);
		//$('#clusterDiv').html("Under Development.");
		$('#clusterDiv').html(data);
		$('#spinner').toggle();
	},
	//* If error. show the error in console.log
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus+" - "+errorThrown);
		console.log(XMLHttpRequest.responseText);
	}
	});
}
