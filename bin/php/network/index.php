<?php require ('../template/header.php'); ?>

<!-- add new calendar event modal -->
<section class="content">
	<div class="row">		
		<div class="col-md-12">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">Gene Regulation Network prediction using an R package(BUS)</h3>
				</div>
				<div class="box-body">
							
<div class="panel-group" id="accordion">
	<div class="panel panel-default" id="panel1">
		<div class="panel-heading">
			<h4 class="panel-title">
				<a data-toggle="collapse" data-target="#collapseOne" href="#collapseOne">Query</a>
			</h4>
		</div>
		<div id="collapseOne" class="panel-collapse collapse">
			<div class="panel-body">
				<?php include '../profile/experiments.php'?>
			</div>
		</div>
	</div>
</div>

<div class="row"><div class="col-lg-12">

<style>
#cytoscapeweb { width: 100%; height: 800px; }
#note { width: 100%; height: 50px; background-color: #f0f0f0; overflow: auto;  }
</style>

<script src="../asset/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script type="text/javascript" src="../asset/js/cytoscape/js/min/json2.min.js"></script>
<!-- Flash embedding utility (needed to embed Cytoscape Web) -->
<script type="text/javascript" src="../asset/js/cytoscape/js/min/AC_OETags.min.js"></script>
<!-- Cytoscape Web JS API (needed to reference org.cytoscapeweb.Visualization) -->
<script type="text/javascript" src="../asset/js/cytoscape/js/min/cytoscapeweb.min.js"></script>
<script type="text/javascript" src="../asset/js/cytoscape.js"></script>
<p>
<form action="/brew/network_count.html" method="get">
<select  class="btn btn-default" id="exp">
<?php
include '../profile/experiments.options.php';
?>
</select>
<select  class="btn btn-default" id="cond">
<option value=">">>=</option>
<option value="<"><=</option>
</select>
<input type="text" id="val" value="1000">
</form>
</p>
<p>
<button class="btn btn-primary" onclick="submitForm()">Count</button>
<button class="btn btn-primary" onclick="getNetwork()" id="btn_network">Network</button>
<!-- <button onclick="getDesc()">Description</button> -->
<button class="btn btn-primary" onclick="drawSelected()">Select list</button>
</p>
<p><span id="spinner" style="display: none"><img src="/img/ajax-loader1.gif"></span></p>

<hr>
Number of ids: <span id="idscount">0</span>
<hr>
<div id="cytoscapeweb"></div>
<div id="note"></div>

<?php require ('../template/footer.php'); ?>