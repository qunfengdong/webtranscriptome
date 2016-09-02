<?php require ('../template/header.php'); ?>

<section class="content-header">
  <h1>Keyword search</h1>
</section>

<section class="content">
	<div class="box">
		<div class="box-header"><h3 class="box-title"><i class="fa fa-info-circle"></i> Information</h3></div>
		<div class="box-body">
			<p>Lorem ipsum dolor sit amet, vim rebum sapientem ad, usu cu aperiri mandamus consequat. An sumo facer nam. Ius et sint dissentiunt. Quodsi assentior his id. Eu ius rebum commodo, ne debitis mentitum menandri pro.</p>
		</div>
	</div>
	<div class="box box-info">
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