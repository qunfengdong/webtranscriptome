<?php require ('../template/header.php'); ?>

<section class="content-header">
  <h1>Hierarchical cluster</h1>
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
    				<h4>Gene list:</h4>
    				<textarea id="hc_genelist" rows="10" type="text" class="form-control">GRMZM5G859187
GRMZM5G872747
AC205376.4_FG003
GRMZM2G024563
GRMZM5G809265
GRMZM2G125201
GRMZM2G005155
GRMZM2G111923
GRMZM2G163956
GRMZM2G332423
GRMZM2G305390
GRMZM2G700981
GRMZM5G828883
GRMZM2G040600
GRMZM2G065652
GRMZM2G382029
GRMZM2G373322
GRMZM2G140016
GRMZM2G161411
GRMZM2G048962
GRMZM2G028366
GRMZM2G004888
AC217311.3_FG001
GRMZM5G889338
GRMZM2G453919
GRMZM2G109821
GRMZM2G519811
GRMZM2G488458
GRMZM2G117633
GRMZM2G119782</textarea>
					<h4>Agglomeration method:</h4>
					<select id="hc_method" class="form-control">
						<option value="average" selected>average</option>
						<option value="centroid">centroid</option>
						<option value="complete">complete</option>
						<option value="median">median</option>
						<option value="mcquitty">mcquitty</option>
						<option value="single">single</option>
						<option value="ward">ward</option>
					</select>
					<h4>Distance calculation:</h4>
					<select id="hc_dist" class="form-control">
						<option value="euc" selected>Euclidean</option>
						<option value="cor">Correlation</option>
					</select>
				</div><!-- col-md-4 -->
			</div><!-- row -->

			<?php require ("../mysql/database_connect.php"); ?>
			<button class="btn btn-success" style="width:100px" onclick="run_hclust()">Run</button>
			<input type="hidden" name="run_hclust_path" value="<?php if ($_SESSION['user']['email']) { echo $_SESSION['user']['email'];} else { echo "guest";} ?>">
			<br><span id="spinner" style="display: none"><img src="/img/ajax-loader1.gif"></span>
		
			<script src="/js/jquery-1.10.2.min.js" type="text/javascript"></script>
			<script src="/js/jscharts.js" type="text/javascript"></script>
			<script src="/js/cluster.js" type="text/javascript"></script>
		</div><!-- box-body -->
	</div><!-- box -->

	<div id="div_hclust"></div>
</section>

<?php require ('../template/footer.php'); ?>