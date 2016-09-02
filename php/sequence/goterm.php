<?php
require ("../mysql/database_connect.php");
require ("../user/verify.php");
require ('../settings/errordiv.php');
?>

<?php require ('../template/header.php'); ?>

<section class="content-header">
  <h1>Gene Ontology Term Enrichment</h1>
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

<?php
require ("../mysql/database_connect.php");
require ("../user/verify.php");

$query = "SELECT listname FROM mygenelist where userid = ". $_SESSION['user']['id'];

#echo $query . "<br>";

try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute();
}
catch(PDOException $ex) {
	echo errordiv("Failed to run query: " . $ex->getMessage());
	exit;
}
$result = $stmt->fetchAll();

$total = sizeof($result);
if($total == 0){ ?>
	<div class="callout callout-danger"><h4>No saved genelist files available</h4></div>
<?php
} else {
?>

<select id="select_file">
<?php
foreach($result as $row){
	echo "<option value=". $row['listname'] .">". preg_replace("/^[0-9]+\./", "", $row['listname']) ."</option>";
}
?>
</select>

<button onclick="goterm()" class="btn btn-success">Find GO terms</button>
<?php } ?>

<h4>OR</h4>
<br>Copy and Paste a list of gene ids:<br>
<textarea id="gotermslist" rows="5"></textarea><br><span style="color: blue; cursor: pointer" onclick="loadTextArea()">sample text</span>
<br><br>
Against the background of
<select id="select_pop">
	<option value="default">all Maize genes</option>
<?php
foreach($result as $row){
	echo "<option value=". $row['listname'] .">". preg_replace("/^[0-9]+\./", "", $row['listname']) ."</option>";
}
?>
</select>
<br>
<br>
<button onclick="goterm()" class="btn btn-success">Find GO terms</button>
<br><span id="spinner" style="display: none"><img src="/img/ajax-loader1.gif"></span>

		</div><!-- box-body -->
	</div><!-- box -->

	<div id="div_goterms"></div>
</section>

<!-- My Scripts -->
<script src="/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="/js/goterm.js" type="text/javascript"></script>

<script>
function loadTextArea(stuff){
	$('#gotermslist').val("GRMZM2G005155\nGRMZM2G040600\nGRMZM2G109821\nGRMZM2G119782\nGRMZM2G151873\nGRMZM2G112176\nGRMZM2G133885\nGRMZM2G177828\nGRMZM2G012391\nGRMZM2G126239\nGRMZM2G440614\nGRMZM2G090300\nGRMZM2G037064\nGRMZM2G046676\nGRMZM2G014154\nGRMZM2G029912\nGRMZM2G416019\nGRMZM2G159950\nGRMZM2G101179\nGRMZM2G323936\nGRMZM5G889776\nGRMZM2G101689\nGRMZM2G086906\nGRMZM2G077131");
}
</script>

<?php require ('../template/footer.php'); ?>