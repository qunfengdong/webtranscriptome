<?php require ('../template/header.php'); ?>

<section class="content-header">
  <h1>Keyword search</h1>
</section>

<section class="content">
	<div class="box box-success">
		<div class="box-body">

<textarea id="keyword_text" rows="8" >ramosa</textarea>
<br>
<button id="updateBtn" onclick="search_keywordtable()" class="btn btn-success">Search</button>

   	</div><!-- box-body -->
  </div><!-- box -->

	<div id="keywordsearchoutput" style="display:hide"></div>

</section>

<script src="/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script>
function search_keywordtable(){
	id = $('#keyword_text').val();
	get_keywords(id);
}
function get_keywords(list){
	$.ajax({
	url: 'ajax_keyword.php',
	data: {
		list: list
	},
	dataType: 'json',
	method: 'GET',
	success: function(data){
		//console.log(data);
		$("#keywordsearchoutput").empty();
		$("#keywordsearchoutput").html(data);
		$("#keywordsearchoutput").css({'display': 'block'});
	},
	error: function(XMLHttpRequest, textStatus, errorThrown) {
		console.log(textStatus+" - "+errorThrown);
		console.log(XMLHttpRequest.responseText);
	}
	});
}
</script>

<?php require ('../template/footer.php'); ?>