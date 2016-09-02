<?php require ('../template/header.php'); ?>

<section class="content-header">
  <h1>Information on file formats</h1>
</section>
<section class="content">
	<div class="box">
		<div class="box-body">
		  <h4>Reference genome</h4>
		  <pre>
>1
GGATTTTTGGAGGATTCGTCGATCCGACTACGACGAGCAAGCCTGAGGCGCCAATGCAAT
CGCTGAACCAACTCCCTGTGGTTACCGACCTTGCTGATGCGAGATCGGCCTGATCACGAA
>2
GGATTTTTGGAGGATTCGTCGATCCGACTACGACGAGCAAGCCTGAGGCGCCAATGCAAT
CGCTGAACCAACTCCCTGTGGTTACCGACCTTGCTGATGCGAGATCGGCCTGATCACGAA
		  </pre>
  	</div><!-- box-body -->
  </div><!-- box -->
  <div class="box">
		<div class="box-body">
		  <h4>Reference GFF</h4>
		  <pre>
1	gramene	gene	4854	9652	.	-	.	gene_id "GRMZM2G059865"; gene_version "1"; gene_source "gramene"; gene_biotype "protein_coding";
1	gramene	transcript	4854	9652	.	-	.	gene_id "GRMZM2G059865"; gene_version "1"; transcript_id "GRMZM2G059865_T01"; transcript_version "1"; gene_source "gramene"; gene_biotype "protein_coding"; transcript_source "gramene"; transcript_biotype "protein_coding";
1	gramene	exon	9193	9652	.	-	.	gene_id "GRMZM2G059865"; gene_version "1"; transcript_id "GRMZM2G059865_T01"; transcript_version "1"; exon_number "1"; gene_source "gramene"; gene_biotype "protein_coding"; transcript_source "gramene"; transcript_biotype "protein_coding"; exon_id "GRMZM2G059865_T01.exon1"; exon_version "1";
1	gramene	CDS	9193	9519	.	-	0	gene_id "GRMZM2G059865"; gene_version "1"; transcript_id "GRMZM2G059865_T01"; transcript_version "1"; exon_number "1"; gene_source "gramene"; gene_biotype "protein_coding"; transcript_source "gramene"; transcript_biotype "protein_coding"; protein_id "GRMZM2G059865_P01"; protein_version "1";
1	gramene	start_codon	9517	9519	.	-	0	gene_id "GRMZM2G059865"; gene_version "1"; transcript_id "GRMZM2G059865_T01"; transcript_version "1"; exon_number "1"; gene_source "gramene"; gene_biotype "protein_coding"; transcript_source "gramene"; transcript_biotype "protein_coding";
		  </pre>
  	</div><!-- box-body -->
  </div><!-- box -->
  <div class="box">
		<div class="box-body">
		  <h4>Gene descriptions</h4>
		  <pre>
#gene_id  description
AC148152.3_FG005	1-aminocyclopropane-1-carboxylate oxidase
AC148152.3_FG008	Uncharacterized protein
AC148167.6_FG001	Uncharacterized protein
AC149475.2_FG002	Uncharacterized protein
AC149475.2_FG003	Proteasome subunit alpha type
AC149475.2_FG005	Uncharacterized protein
		  </pre>
  	</div><!-- box-body -->
  </div><!-- box -->
  <div class="box">
		<div class="box-body">
		  <h4>GO file</h4>
		  <pre>
#gene_id  go_term go_description
GRMZM2G151616	GO:0046872	metal ion binding
GRMZM2G151616	GO:0008270	zinc ion binding
GRMZM2G151616	GO:0005515	protein binding
GRMZM2G111238	GO:0005622	intracellular
GRMZM2G111238	GO:0003676	nucleic acid binding
		  </pre>
  	</div><!-- box-body -->
  </div><!-- box -->
  <div class="box">
		<div class="box-body">
		  <h4>Ortholog file</h4>
		  <pre>
#gene_id	species1	species1_desc	species2	species2_desc	species3	species3_desc	species4	species4_desc	species5	species5_desc	species6	species6_desc
GRMZM2G439951			Si039503m.g	Uncharacterized protein  [Source:UniProtKB/TrEMBL;Acc:K4AKS7]		BGIOSGA032813	Putative uncharacterized protein  [Source:UniProtKB/TrEMBL;Acc:A2Z6M6]	OS10G0376400	Phosphate-induced protein 1 conserved region containing protein; Putative uncharacterized protein OSJNBa0095C06.7  [Source:UniProtKB/TrEMBL;Acc:Q8S7K1]	Sb0012s005000	Putative uncharacterized protein Sb0012s005000 [source:UniProtKB/TrEMBL;Acc:C6JRS8_SORBI]
GRMZM2G094632			Si038090m.g	Uncharacterized protein  [Source:UniProtKB/TrEMBL;Acc:K4AGT3]		BGIOSGA032533	Putative uncharacterized protein  [Source:UniProtKB/TrEMBL;Acc:B8BFR2]	OS10G0148000	Protease inhibitor/seed storage/LTP family protein, expressed  [Source:UniProtKB/TrEMBL;Acc:Q10A49]	Sb01g026220	Putative uncharacterized protein Sb01g026220 [source:UniProtKB/TrEMBL;Acc:C5WP24_SORBI]
GRMZM2G095094	AT2G01740	Tetratricopeptide repeat (TPR)-like superfamily protein [Source:TAIR_LOCUS;Acc:AT2G01740]	Si025982m.g	Uncharacterized protein  [Source:UniProtKB/TrEMBL;Acc:K3ZHD0]			BGIOSGA032530	Putative uncharacterized protein  [Source:UniProtKB/TrEMBL;Acc:B8BFR0]	OS10G0147250	Putative salt-inducible protein  [Source:UniProtKB/TrEMBL;Acc:Q94HS0]	Sb01g026260	Putative uncharacterized protein Sb01g026260 [source:UniProtKB/TrEMBL;Acc:C5WP30_SORBI]
		  </pre>
  	</div><!-- box-body -->
  </div><!-- box -->
</section>


<?php require ('../template/footer.php'); ?>

