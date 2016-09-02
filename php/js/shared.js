/**
 * Submit a Ajax request and get the output
 * @param file: ajax php file
 * @param data: JSON object of parameters
 *
 * Returns the results
 */
function ajax_request(file, data){
	$.ajax({
		url: file,
		data: data,
		dataType: 'json',
		method: 'GET',
		success: function(result){
			//console.log(result);
			addTab(file, result);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}

/**
 * Display the html in the div
 *
 * @param html
 */
function addTab(file, html){
	console.log(file);
	//$('#placedivs').after(html);
	if (file == 'genetable.php') {
		$('#tab1').html(html);
	} else if (file == 'rnaseqtable.php') {
		$('#tab2').html(html);
	} else if (file == 'pathway.php') {
		$('#tab4').html(html);
	} else if (file == 'ortholog.php') {
		$('#tab5').html(html);
	} else if (file == 'ajax_display.php') {
		$('#geneExpDivTable').html(html);
		getTableRowValues();
	} else if (file == 'chipseqtable.php'){
		console.log($('#tab3').html());
		$('#tab3').html(html);
	} else if (file =='ajax_promoter.php') {
		$("#promoter_output").html(html);
	}
}
