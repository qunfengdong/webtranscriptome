<?php require ('../template/header.php'); ?>

<section class="content-header">
  <h1>Heatmap</h1>
</section>

<section class="content">
	<div class="box box-success">
		<div class="box-body">
			<div class="row">
				<div class="col-md-8">
    				<h4>Filter by Experiments:</h4>
    				<?php include ("experiments.php") ?>
    			</div><!-- col-md-8 -->
    			<div class="col-md-4">
    				<h4>Gene list:</h4>
    				<textarea id="hm_genelist" rows="10" type="text" class="form-control">GRMZM5G859187
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
GRMZM2G488458
GRMZM2G117633
GRMZM2G119782</textarea>
					<h4>Distance measurement:</h4>
					<select id="heatmap_dist_method" class="form-control">
						<option value="binary">binary</option>
						<option value="canberra">canberra</option>
						<option value="euclidean" selected>euclidean</option>
						<option value="manhattan">manhattan</option>
						<option value="maximum">maximum</option>
						<option value="minkowski">minkowski</option>
					</select>
					<h4>Hierarchical cluster analysis:</h4>
					<select id="heatmap_hclust_method"  class="form-control">
						<option value="average" selected>average</option>
						<option value="centroid">centroid</option>
						<option value="complete">complete</option>
						<option value="median">median</option>
						<option value="mcquitty">mcquitty</option>
						<option value="single">single</option>
						<option value="ward">ward</option>
					</select>
				</div><!-- col-md-4 -->
			</div><!-- row -->

			<?php require ("../mysql/database_connect.php"); ?>
			<button class="btn btn-success" style="width:100px" onclick="run_heatmap()">Run</button><br>
			<input type="hidden" name="run_heatmap_path" value="<?php if ($_SESSION['user']['email']) { echo $_SESSION['user']['email'];} else { echo "guest";} ?>">
			<br><span id="spinner" style="display: none"><img src="/img/ajax-loader1.gif"></span>
			<script src="/js/jquery-1.10.2.min.js" type="text/javascript"></script>
			<script src="/js/jscharts.js" type="text/javascript"></script>
			<script src="/js/heatmap.js" type="text/javascript"></script>
		</div><!-- box-body -->
	</div><!-- box -->

	<div id="div_heatmap"></div>

</section>
<?php require ('../template/footer.php'); ?>