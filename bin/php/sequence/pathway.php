<?php require ('../template/header.php'); ?>
<?php require ('../user/verify.php'); ?>

<section class="content-header">
  <h1>Pathway analysis</h1>
</section>

<section class="content">
	<div class="box box-success">
  	<div class="box-body">
  		<div class="row">
  			<div class="col-md-8">

<?php

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
	echo(json_encode("Failed to run query: " . $ex->getMessage()));
	die;
}
$result = $stmt->fetchAll();

$total = sizeof($result);
if($total == 0){
	?>
	<span style='color:blue'>INFO: No saved gene list found.</span><br>
	<?php 
}
else {
?>

<select id="select_file">
<?php 
foreach($result as $row){
	echo "<option value=". $row['listname'] .">". preg_replace("/^[0-9]+\./", "", $row['listname']) ."</option>";
}
?>
</select>

<button onclick="pan()" class="btn btn-success">Analyze pathway</button>
<?php } ?>
<br>
<h4>OR</h4>
<br>
<textarea id="geneidlist" rows="5"></textarea><br>
<span style="color: blue; cursor: pointer" onclick="loadTextArea()">sample text</span>
<br><br>
Against the background of 
<select id="select_pop">
<option value="default">all Maize genes</option>

<?php 
foreach($result as $row){
	echo "<option value=". $row['listname'] .">". preg_replace("/^[0-9]+\./", "", $row['listname']) ."</option>";
}
?>
</select><br>
<script src="/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<button onclick="panlist()" class="btn btn-success">Analyze pathway</button>
<br><span id="spinner" style="display: none"><img src="/img/ajax-loader1.gif"></span>

				</div><!-- col-md-8 -->
			</div><!-- row -->
		</div><!- box-body -->
	</div><!-- box -->

	<div id="div_pathway"></div>

</section>

<script>
function loadTextArea(){
	$("#geneidlist").val("GRMZM2G066578\nGRMZM2G040600\nGRMZM2G109821\nGRMZM2G119782\nGRMZM2G151873\nGRMZM2G112176\nGRMZM2G133885\nGRMZM2G177828\nGRMZM2G012391\nGRMZM2G126239\nGRMZM2G440614\nGRMZM2G090300\nGRMZM2G037064\nGRMZM2G046676\nGRMZM2G014154\nGRMZM2G029912\nGRMZM2G416019\nGRMZM2G159950\nGRMZM2G101179\nGRMZM2G323936\nGRMZM5G889776\nGRMZM2G101689\nGRMZM2G086906\nGRMZM2G077131");
}

function pan(){
	$('#spinner').toggle();
	var sel = $('#select_file option:selected').val();
	console.log(sel);
	get_pathway(sel, "");
	//get_goterms(sel);
}

function panlist(){
	$('#spinner').toggle();
	var list = $('#geneidlist').val();
	console.log(list);
	get_pathway('', list);
	//get_goterms(sel);
}


function get_pathway(name, list){
	var pop = $('#select_pop option:selected').val();
	$.ajax({
		url: 'ajax_pathway.php',
		data: {
			name: name,
			list: list,
			pop: pop
		},
		dataType: 'json',
		method: 'GET',
		success: function(data){
			//console.log(data);
			$('#div_pathway').html(data);
			//addTab(data);
			$('#spinner').toggle();
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) {
			console.log(textStatus+" - "+errorThrown);
			console.log(XMLHttpRequest.responseText);
		}
	});
}
</script>

<?php require ('../template/footer.php'); ?>