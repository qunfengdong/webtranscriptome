<?php
require ('../template/header.php');
require ('../settings/errordiv.php');
?>

<section class="content-header">
  <h1>Cluster search</h1>
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
 
<div class="form-group">
	<p>
	Search using a single gene ID: <input type="text" id="cluster_geneid" value="GRMZM2G700291" class="mac">&nbsp;&nbsp;&nbsp;
	<button id="updateBtn" onclick="cluster_table()" class="btn btn-success">Search</button><br><br>
	<?php if($_SESSION['user']['id']){?>
		<span style="font-weight: bold">OR</span> Search using an uploaded gene list: <br>
		<?php echo getSelect();
	}?>
	&nbsp;&nbsp;<br>
	<span id="spinner" style="display: none"><img src="/img/ajax-loader1.gif"></span>
</div>

		</div><!-- box-body -->
	</div><!-- box -->
	<div id ="clusterDiv"></div>
</section>

<!-- My Scripts -->

<script src="/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="/js/network.js" type="text/javascript"></script>

<?php require ('../template/footer.php'); ?>

<?php 

function getSelect(){
	$html = '';
	
	$dir = getcwd();
	$par = dirname($dir);
	$path = $par . "/tmp/genelist/";
	
	require ("../mysql/database_connect.php");
	$query = "SELECT listname FROM mygenelist where userid = ". $_SESSION['user']['id'];

	#echo $query . "<br>";

	try {
		$stmt = $db->prepare($query);
		$result = $stmt->execute();
	}
	catch(PDOException $ex) {
		echo '<div class="callout callout-danger"><h4>ERROR</h4><p>'.$ex->getMessage().'</p></div>';
		die;
	}
	$result = $stmt->fetchAll();
	
	$total = sizeof($result);
	if($total == 0){
		#$html = "<span style='color:teal'>INFO: No uploaded gene list found. Use the sample gene list file below and upload it at 'MyGene' tab.</span><br>
		$html = "You may upload a list of gene IDs via the <a href='/mydata/'>MyGene</a> tab, here is an example gene list <a href='/data/sample_gene_cluster.txt'>sample_gene_cluster.txt</a><br>";
		$html = '<div class="callout callout-info"><p>'.$html.'</p></div>';
		return $html;
	}
	
	$html .= '<select id="select_file">';

	foreach($result as $row){
		$html .= "<option value=". $row['listname'] .">". preg_replace("/^[0-9]+\./", "", $row['listname']) ."</option>";
	}
	
	$html .= '</select>&nbsp;&nbsp;<button onclick="getClusters()" class="btn btn-success">Classify into Clusters</button>';
	
	return $html;
}
?>

