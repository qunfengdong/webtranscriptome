<html>
<head>
<style>
/* The Cytoscape Web container must have its dimensions set. */
html, body { height: 100%; width: 100%; padding: 0; margin: 0}
/* The Cytoscape Web container must have its dimensions set. */
#cytoscapeweb { width: 100%; height: 100%; }
#note { width: 100%; height: 50%; background-color: #f0f0f0; overflow: auto;  }
p { padding: 0 0.5em; margin: 0; }
p:first-child { padding-top: 0.5em; }
</style>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script type="text/javascript" src="/cytoscape/js/min/json2.min.js"></script>
<!-- Flash embedding utility (needed to embed Cytoscape Web) -->
<script type="text/javascript" src="/cytoscape/js/min/AC_OETags.min.js"></script>
<!-- Cytoscape Web JS API (needed to reference org.cytoscapeweb.Visualization) -->
<script type="text/javascript" src="/cytoscape/js/min/cytoscapeweb.min.js"></script>
<script type="text/javascript" src="/js/maize/cytoscape.js"></script>
</head>
<body>
<form action="/R/brew/network_count.html" method="get">
<select id="exp">
<option value="ear_tip">ear_tip</option>
<option value="ear_mid">ear_mid</option>
<option value="ear_base">ear_base</option>
<option value="tassel_stg1">tassel_stg1</option>
<option value="tassel_stg2">tassel_stg2</option>
<option value="tassel_stg3">tassel_stg3</option>
<option value="ear_wt_1mm">ear_wt_1mm</option>
<option value="ear_ra1_1mm">ear_ra1_1mm</option>
<option value="ear_ra2_1mm">ear_ra2_1mm</option>
<option value="ear_ra3_1mm">ear_ra3_1mm</option>
<option value="ear_wt_2mm">ear_wt_2mm</option>
<option value="ear_ra1_2mm">ear_ra1_2mm</option>
<option value="ear_ra2_2mm">ear_ra2_2mm</option>
<option value="ear_ra3_2mm">ear_ra3_2mm</option>
</select>
<select id="cond">
<option value=">">>=</option>
<option value="<"><=</option>
</select>
<input type="text" id="val" value="1000">
</form>
<button onclick="submitForm()">Count</button>
<button onclick="getNetwork()">Network</button>
<!-- <button onclick="getDesc()">Description</button> -->
<button onclick="drawSelected()">Select list</button>
<hr>
Number of ids: <span id="idscount">0</span>
<hr>
<div id="cytoscapeweb"></div>
<div id="note"></div>
</body>


</html>

