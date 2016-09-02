<?php require ('../template/header.php'); ?>

<section class="content-header">
  <h1>Promoter analysis</h1>
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

Retrieve both upstream and downstream sequences of length
<select id="pr_up">
	<option value="500">500 bp</option>
	<option value="1000">1k bp</option>
	<option value="2000">2k bp</option>
	<option value="5000">5k bp</option>
	<option value="10000">10k bp</option>
</select>
<br>
Gene ids list:<br>
<textarea id="pr_text" rows="8" ></textarea>
<span style="color: blue; cursor: pointer" onclick="loadTextArea('AC155624.2_FG006\nAC166636.1_FG008\nGRMZM2G124292\nGRMZM2G022792\nGRMZM2G545132')">sample text</span>
<br><br>
<button class="btn btn-success" onclick="getPromoters()">Get Promoter Region</button>

		</div><!-- box-body -->
	</div><!-- box -->

	<div id='promoter_output'></div>

</section>
<script src="/js/jquery-1.10.2.min.js" type="text/javascript"></script>
<script src="/js/shared.js" type="text/javascript"></script>
<script src="/js/promoter.js" type="text/javascript"></script>
<?php require ('../template/footer.php'); ?>