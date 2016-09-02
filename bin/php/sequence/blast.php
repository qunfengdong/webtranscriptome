<?php require ('../template/header.php'); ?>

<section class="content-header">
  <h1>BLAST</h1>
</section>

<section class="content">
	<div class="box box-success">
		<div class="box-body">

<?php
require ("blastprogram.php");

if(count($_POST) < 1){
	#echo " POST NOT present<br>";
} else {
	$seq = $_POST['seq'];
	$evalue = $_POST['evalue'];
}

`find /var/www/html/maize/tmp/blast/. -mtime +1 -type f -delete`;

?>

<p>
Please paste or upload your sequence below and search the current collection of Plant sequences.
</p>

<form id="blastform" action="blast.php" method="post" enctype="multipart/form-data">
<span style="width: 200px; display:inline-block">Program</span>
<select name="program">
	<option value="blastn" selected>blastn</option>
	<option value="tblastn">tblastn</option>
</select>
<br>
<span style="width: 200px; display:inline-block">Database</span>
<?php
$ok = str_replace('data/', "", $blastdb);
echo $ok;
?>
&nbsp;<a href="http://ensembl.gramene.org/info/about/species.html" target="_blank" style="cursor: pointer;">Info</a>
<br>
<!--
<span style="width: 200px; display:inline-block">Sequences</span>
<select name="type">
	<option value="dna" selected>dna</option>
	<option value="protein">protein</option>
</select>
<br>
-->
<span style="width: 200px; display:inline-block">E-value</span>
<select name="evalue">
	<option value="1e-50">1e-50</option>
	<option value="1e-20" selected>1e-20</option>
	<option value="0.01">0.01</option>
	<option value="1">1</option>
</select>
<br><br>
Paste query sequence (raw or FASTA format):
<br>
<textarea style="width:500px;" rows=5 name="seq" id="seq">
</textarea>
<br>
<span style="color: blue; cursor: pointer" onclick="loadTextArea('>Test\nATGGCGAAGAAAGTGGCCACTCTGCTGGCCCTCAACCTCCTCTTCTTCGCCTTCGCCGACGCATGCGGCTGCAGGTGCGGCGGATCCTGCCCTAGTCCAGGCGGCGGCAGCGGTGGTGGAGGCGGAGGAGGATCTGGTGGTGGCGGCGGCGGCGGCGGTGGGACGAGCGGTCGTTGCCCCGTGGACGCGCTGAAGCTGGGCGTGTGCGCCAACTTGCTGAACGGGCTGATCAACGCGACCCTGGGGACGCCGCCCAGGACGCCGTGCTGCACGCTGATCCAGGGGCTGGCGGACCTGGAGGCGGCGGTGTGCCTCTGCACCGTCCTCAGGGCCAACGTCCTGGGCATCAACCTCAACCTGCCCATCAACCTCAGCCTCCTCGTCAACTACTGCGGCAGGCGCGTCCCATCGGGCTTCCAGTGCTTCTGA')">sample nucleotide sequence</span>
<br>
<script src="/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
function loadTextArea(text){
	$("#seq").empty();
	$("#seq").val(text);
}
</script>
<br>
Or upload query sequence <input type="file" name="file" id="file">
<br>
<input type="submit" name="submit" value="Submit" class="btn btn-success">
<br><span id="spinner" style="display: none"><img src="/img/ajax-loader1.gif"></span>
</form>

		</div><!-- box-body -->
	</div><!-- box -->

<?php
if(count($_POST) < 1){
	#echo " POST NOT present<br>";
} else {
	$dir = getcwd();
	$par = dirname($dir);
	$path = $par."/tmp/blast/";
	$file = $path .  generateRandomString();

	try{
		if(empty($seq) && empty($_FILES["file"]["tmp_name"])){
			throw new Exception("No Sequence provided and No File uploaded");
		}
		if(! empty($_FILES["file"]["tmp_name"])){
			save_uploaded_file($file);
		} elseif (! empty($seq)){
			file_put_contents("$file", $seq);
		}

		verify_fasta($file);
		verify_program($file, $program, $type);

		#verify_save_file();
		run_blast($file, $database, $program, $type, $evalue);
		show_contents($file);
	}
	catch (Exception $e){
		echo "<span style='color:red; font-weight: bold'>ERROR: ". $e->getMessage() ."</span><br>";
	}
}

function show_contents($file){
	#echo $file;
	echo "<div class='box box-success'>
		<div class='box-body'>
		<h4>BLAST output</h4>
		<pre>";
	echo `cat $file.blout`;
	echo "</pre></div</div>";

}

function startsWith($haystack, $needle)
{
	return $needle === "" || strpos($haystack, $needle) === 0;
}

function verify_fasta($file){
	$line = `head -n 1 $file`;
	error_log($line);
	if(startsWith($line, ">")){
		error_log("Fasta");
	} else {
		#error_log("Not Fasta");
		throw new Exception("Input data is not in FASTA format");
	}
}

function verify_program($file, $program, $type){
	$line = ` head -n 2 $file | tail -n 1`;
	$line = preg_replace("/\r|\n/", "", $line);
	$pattern = "/[^ATGCN]/i";
	error_log(preg_match($pattern, $line));
	if(preg_match($pattern, $line)){
		error_log($line);
		error_log("bad seq");
		if($type == "dna"){
			throw new Exception("Input data is not compatible with BLASTn, BLASTx and tBLASTx");
		}
	} else {
		#if(($program == "blastn") || ($program == "blastx")){
		error_log("good seq");
		if($type == "protein"){
			throw new Exception("Input data is not compatible with BLASTp, tBLASTn");
		}
	}
}

function verify_save_file(){
	if(empty($_FILES["file"]["tmp_name"])){
	} else {
		save_uploaded_file($file);
	}
}

function run_blast($file, $database, $program, $type, $evalue){
	require ("blastprogram.php");
	$dir = getcwd();
	$par = dirname($dir);
	#$p = dirname($par);
	$path = $par."/".$blastdb;
	$output = "$blastprogram/blastn -query $file -db $path -out $file.blout -evalue $evalue";
	echo `$output`;
	return $output;
}

function save_uploaded_file($file){
	#echo "($file)<br>";
	move_uploaded_file($_FILES["file"]["tmp_name"], $file);
}

function generateRandomString($length = 10) {
	$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$randomString = '';
	for ($i = 0; $i < $length; $i++) {
		$randomString .= $characters[rand(0, strlen($characters) - 1)];
	}
	return $randomString;
}

?>

</section>
<?php require ('../template/footer.php'); ?>
