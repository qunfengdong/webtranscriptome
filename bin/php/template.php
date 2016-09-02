<!DOCTYPE html>
<html>
<head>
<title>Website</title>
<link href="/asset/css/template.css" rel="stylesheet" type="text/css" />
<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
<![endif]-->
</head>
<body>

<!-- header logo: style can be found in header.less -->
<header class="header">
  <div class="row">
    <div class="col-md-12 headerdiv">
      <a href="index.php" class="logo">RNAseqChIPseqWeb</a>
      <div class="navbar-right">
        Login
      </div>
    </div>
  </div>
</header>


<div>
	<aside class="left-side sidebar-offcanvas">
    <section class="sidebar">
    	<ul class="sidebar-menu">
  	  	<li>
		    	<a href="#">
			    	<span>Profile</span>
			    </a>
			    <ul class="sidebar">
				    <li><a href="profile_display.php"><i class="fa fa-angle-double-right"></i> Profile Display</a></li>
				    <li><a href="profile_search.php"><i class="fa fa-angle-double-right"></i> Profile Search </a></li>
				    <li><a href="cluster.php"><i class="fa fa-angle-double-right"></i> Hierarchical Cluster</a></li>
				    <li><a href="heatmap.php"><i class="fa fa-angle-double-right"></i> Heatmap</a></li>
				    <li><a href="qtl.php"><i class="fa fa-angle-double-right"></i> QTL regions </a></li>
			    </ul>
  		  </li>
	  	  <li>
			    <a href="#">
				    <i class="fa fa-tasks"></i>
				    <span>Sequence Analysis</span>
			    </a>
			    <ul class="sidebar">
				    <li><a href="promoter.php"><i class="fa fa-angle-double-right"></i> Promoter Analysis </a></li>
				    <li><a href="mast.php"><i class="fa fa-angle-double-right"></i> ChIP-seq Analysis </a></li>
				    <li><a href="goterm.php"><i class="fa fa-angle-double-right"></i> GO Term </a></li>
				    <li><a href="blast.php"><i class="fa fa-angle-double-right"></i> BLAST search </a></li>
				    <li><a href="snp.php"><i class="fa fa-angle-double-right"></i> SNP Annotation </a></li>
				    <li><a href="pathway.php"><i class="fa fa-angle-double-right"></i> Pathway analysis </a></li>
			    </ul>
		    </li>
		    <li>
			    <a href="#">
				    <i class="fa fa-table"></i>
				    <span>Database</span>
			    </a>
			    <ul class="sidebar">
			      <li><a href="network.php"><i class="fa fa-angle-double-right"></i> Gene Network</a></li>
			      <li><a href="genelist.php"><i class="fa fa-angle-double-right"></i> Gene list Query</a></li>
				    <li><a href="query.php"><i class="fa fa-angle-double-right"></i> Interval Query</a></li>
				    <li><a href="keyword.php"><i class="fa fa-angle-double-right"></i> Keyword Search </a></li>
			    </ul>
		    </li>
		    <li>
				  <a href="http://maizeinflorescence.org/JBrowse-1.11.6/index.php?data=maize&loc=chr5%3A14064161..14082560&tracks=GFF%2Cqtl%2Ctophat_ear_base_bigwig%2Ctophat_ear_mid_bigwig%2Ctophat_ear_tip_bigwig&highlight=" target="_blank">
  			    <i class="fa fa-laptop"></i> Genome Browser
  			  </a>
	  	  </li>
		    <li>
			    <a href="mydata.php">
				    <i class="fa fa-angle-double-right"></i> <span>MyGene</span>
				  </a>
		    </li>
		    <li>
			    <a href="sop.php">
				    <i class="fa fa-file-text"></i> <span>SOP</span>
			    </a>
		    </li>
		    <li>
			    <a href="data.php">
				    <i class="fa fa-folder"></i> <span>Download</span>
			    </a>
		    </li>
		    <li>
			    <a href="outreach.php">
				    <i class="fa fa-square"></i> <span>Outreach</span>
			    </a>
		    </li>
		    <li>
  			  <a href="data/MaizeInflorescenceWebSiteTutorial_20151102.pdf">
				    <i class="fa fa-question"></i> <span>Tutorial</span>
			    </a>
		    </li>
		    <li>
			    <a href="mailto: qdong@luc.edu">
				    <i class="fa fa-envelope"></i> <span>Contact</span>
			    </a>
		    </li>
	    </ul>
    </section>
  </aside>

	<!-- Right side column. Contains the navbar and content of the page -->
	<aside class="right-side">
	  <section class="content">
	    <div class="row">
	      <div class="col-md-12 content">
       		<h3 class="box-title">Gene description search using keyword.</h3>
     			<textarea id="keyword_text" rows="8" >ramosa</textarea>
     		  <br>
     		  <button id="updateBtn" onclick="search_keywordtable()" class="btn btn-success">Search</button>
     		  <br><br>
     		  <div id="keywordsearchoutput" style="display:hide"></div>
	      </div>
	    </div>
	  </section>
	</aside>
</div>
</body>
</html>