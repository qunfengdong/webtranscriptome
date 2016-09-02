<?php require ('../template/header.php'); ?>

<section class="content-header">
  <h1>ChIP-seq peak analysis</h1>
</section>

<section class="content">
	<div class="box box-success">
		<div class="box-body">

Select the ChIP-seq experiments.
<br>
<?php
require ("../mysql/database_connect.php");
$query = "SELECT exp from chipseq group by exp";

try {
	$stmt = $db->prepare($query);
	$result = $stmt->execute();
}
catch(PDOException $ex) {
	die("Failed to run cluster query: " . $ex->getMessage());
}

$result = $stmt->fetchAll();

foreach($result as $row){
  echo '<label><input type="checkbox" value="'.$row['exp'].'" checked="checked">'.$row['exp'].'</label><br />';
}
?>

<br><br>

Retrieve upstream:
<select id="pr_up">
	<option value="500">500 bp</option>
	<option value="1000">1k bp</option>
	<option value="2000">2k bp</option>
	<option value="5000">5k bp</option>
	<option value="10000">10k bp</option>
</select>
<br>
Gene ids list:<br>
<textarea id="pr_text" rows="5" ></textarea>
<span style="color: blue; cursor: pointer" onclick="loadTextArea('GRMZM2G388140\nGRMZM2G131391\nGRMZM2G117633\nGRMZM2G444222\nGRMZM2G068590\nGRMZM2G100255\nGRMZM2G000448')">sample text</span>

<br><br>
<button class="btn btn-success" onclick="getPromoters()">Get ChIP-seq Peaks</button>

		</div><!-- box-body -->
	</div><!-- box -->

	<div class="box box-success">
		<div class="box-body">
			<div id='output'></div>
		</div><!-- box-body -->
	</div><!-- box -->
</section>

<script src="/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="/js/shared.js" type="text/javascript"></script>
<script src="/js/chipseqpeak.js" type="text/javascript"></script>


<?php require ('../template/footer.php'); ?>