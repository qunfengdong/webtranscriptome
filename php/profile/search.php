<?php require ('../template/header.php'); ?>

<section class="content-header">
  <h1>Profile search</h1>
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
				</div>
				<div class="col-md-3">
					<h4>Gene name:</h4>
					<input id="s_genename" type="text" value="GRMZM2G328795" class="form-control"/>
					<br>
					<h4>Correlation Cutoff:</h4>
					<select id="s_method" class="form-control">
						<option value="pearson">pearson</option>
						<option value="spearman" selected="selected">spearman</option>
						<option value="kendall">kendall</option>
					</select>
					<select id="s_op"  class="form-control">
						<option value=">=">>=</option>
						<option value="<="><=</option>
					</select>
					<input id="s_cutoff" type="text" value="0.9"  class="form-control"/>
				</div>
			</div>

			<script src="../js/jquery-1.10.2.min.js" type="text/javascript"></script>
			<script src="../js/jscharts.js" type="text/javascript"></script>

			<?php require ("../mysql/database_connect.php"); ?>
			<button class="btn btn-success" style="width:100px" onclick="run_spearman('<?php echo $_SESSION["user"]["email"] ?>')">Search</button>
			<button class="btn btn-success" style="width:100px" onclick="run_spearman_log('<?php echo $_SESSION["user"]["email"] ?>')">Search (log)</button>
			<br><span id="spinner" style="display: none"><img src="/img/ajax-loader1.gif"></span>
		</div>
	</div>
	
	<div id ="geneExpDiv"></div>
	<div id ="canvas"></div>
</section>

<script src="../js/shared.js" type="text/javascript"></script>
<script src="../js/search.js" type="text/javascript"></script>
<?php require ('../template/footer.php'); ?>