<?php require ('../template/header.php'); ?>

<section class="content-header">
  <h1>Profile display</h1>
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
    	<div class="row">
    		<div class="col-md-8">
					<h4>Filter by Experiments:</h4>
					<?php include ("experiments.php") ?>
				</div><!-- col-md-8 -->
				<div class="col-md-4">
					<h4>Filter by Gene ID(s) or description:</h4>
					<textarea id="id_text" rows="8" class="form-control"></textarea>
					<span style="color: blue; cursor: pointer" onclick="loadTextArea('GRMZM2G115357\nGRMZM2G154641\nGRMZM2G089619')">Example set of gene ids.</span>
				</div><!-- col-md-4 -->
			</div><!-- row -->
			<?php
			include ("../settings/descriptions.php");
			if($available_description){
				echo "<input type='hidden' id='hasdesc' name='hasdesc' value=1>";
			}
			?>
			<button id="updateBtn" onclick="updatetable(0)" class="btn btn-success">Search (RPKM values)</button>
			<button id="updateBtn" onclick="updatetablelog(0)" class="btn btn-success">Search (Log<sub>2</sub> of RPKM)</button>
			<span id="spinner" style="display: none"><img src="/img/ajax-loader1.gif"></span>

			<script src="/js/jquery-1.10.2.min.js" type="text/javascript"></script>
			<script src="/js/shared.js" type="text/javascript"></script>
			<script src="/js/jscharts.js" type="text/javascript"></script>
			<script src="/js/display.js" type="text/javascript"></script>
  	</div><!-- box-body -->
  </div><!-- box -->

	<div id="geneExpDivTable"></div>
</section>


<?php require ('../template/footer.php'); ?>

<script>
function loadTextArea(stuff){
	$('#id_text').val(stuff);
	//console.log("kashi");
}
</script>


<?php
$aa = $_POST["genelist"];
$nn = count($aa);
for($i= 0; $i < $nn; $i++){
	echo $aa[$i]. "\n";
}
$aa = $_POST[gene_id_list];
$nn = count($aa);
for($i= 0; $i < $nn; $i++){
	echo $aa[$i]. "\n";
}

if(empty($aa)){
	$aa = $_GET["genelist"];
	$arr = explode(",", $aa);
	foreach($arr as $n){
		echo $n, "\n";
	}
}
?>
