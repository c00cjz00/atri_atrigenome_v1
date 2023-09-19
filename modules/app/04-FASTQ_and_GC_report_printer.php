<?php
# example: php 04-FASTQ_and_GC_report_printer.php /disk/files/c00cjz00/659/SRR12362016.record
$expNum=$argv[1];
$record=$argv[2];
$srrNum=basename($record,".record");
$base=dirname($record)."/".$srrNum;

if (!is_file($record)) {echo "error\n"; exit();}
$tmpArr=file($record);
$arr=file($record);
foreach($arr as $k => $v){
 $smpArr=explode(":",trim($v));
 if (count($smpArr)==2) $reports[$smpArr[0]]=$smpArr[1];
}

# Image for unzip fastqc
$icon01=$base."_R1_fastqc/Icons/tick.png"; 
$icon02=$base."_R1_fastqc/Icons/warning.png"; 
$icon03=$base."_R1_fastqc/Icons/error.png";
$png11=$base."_R1_fastqc/Images/per_base_quality.png";
$png12=$base."_R1_fastqc/Images/per_sequence_quality.png";
$png13=$base."_R1_fastqc/Images/per_base_sequence_content.png";
$png14=$base."_R1_fastqc/Images/per_sequence_gc_content.png";
$png15=$base."_R1_fastqc/Images/kmer_profiles.png";
$png16=$base."_R1_fastqc/Images/duplication_levels.png";
$png21=$base."_R2_fastqc/Images/per_base_quality.png";
$png22=$base."_R2_fastqc/Images/per_sequence_quality.png";
$png23=$base."_R2_fastqc/Images/per_base_sequence_content.png";
$png24=$base."_R2_fastqc/Images/per_sequence_gc_content.png";
$png25=$base."_R2_fastqc/Images/kmer_profiles.png";
$png26=$base."_R2_fastqc/Images/duplication_levels.png";


# from 644/SRR12362017.record, Per tile sequence quality1:PASS
$summaryArr=array("Per base sequence quality","Per sequence quality scores","Per base sequence content","Per sequence GC content","Kmer Content","Sequence Duplication Levels");
//$summaryArr=array("Per base sequence quality","Per sequence quality scores","Per base sequence content","Per sequence GC content","Sequence Duplication Levels");
for($i=0;$i<count($summaryArr);$i++){
 $myKey=1;  $tmp=$summaryArr[$i].$myKey; 
 if (isset($reports[$tmp])){
  $smp=$reports[$tmp]; $iconTmp="icon".$myKey.($i+1);
  if ($smp=="PASS") { $$iconTmp=$icon01; }elseif ($smp=="WARN") { $$iconTmp=$icon02; }elseif ($smp=="FAIL") { $$iconTmp=$icon03; }
 }
 $myKey=2;  $tmp=$summaryArr[$i].$myKey; 
 if (isset($reports[$tmp])){
  $smp=$reports[$tmp]; $iconTmp="icon".$myKey.($i+1);
  if ($smp=="PASS") { $$iconTmp=$icon01; }elseif ($smp=="WARN") { $$iconTmp=$icon02; }elseif ($smp=="FAIL") { $$iconTmp=$icon03; }
 }
}
 
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <title><?=$srrNum;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!--link rel="stylesheet" href="print.component.scss">
    <script src="print.component.js"></script-->
  
<style type="text/css" media="print">@page {
	size: A4 portrait;  margin: 10mm;  }
	body 
	{
		background-color:#FFFFFF; 
		border: solid 0px black ;
		margin: 0px;  
	}   
</style>
<style>
.custom-container-width {
    max-width: 810px;
}
.table-borderless > tbody > tr > td,
.table-borderless > tbody > tr > th,
.table-borderless > tfoot > tr > td,
.table-borderless > tfoot > tr > th,
.table-borderless > thead > tr > td,
.table-borderless > thead > tr > th {
    border: none;
}
.mytable>tbody>tr>td, .mytable>tbody>tr>th, .mytable>tfoot>tr>td, .mytable>tfoot>tr>th, .mytable>thead>tr>td, .mytable>thead>tr>th {
    padding: 4px;
}

</style>   


 
</head>

<body>
<div class="container custom-container-width">
<table class="table mytable table-borderless">
  <tr>
    <td>
	<table class="table table-borderless">
	  <tr>
	   <td class="col-md-9">
		<div><center><h5><strong>WHOLE GENOME SEQUENCING DATA QUALITY REPORT</strong></h5></center></div>   
	   </td>
	   <td class="col-md-3">
		<div><small>CODE: <?=$expNum;?>_DOC01</small></div>   
		<!--div><small>Effection Date : </small></div-->
	   </td>
	  </tr>
	</table>
	</td>
  </tr>
  <tr>
    <td>
	<table class="table mytable table-borderless">
			  <tr><td class="col-md-1">&nbsp;&nbsp;&nbsp;</td><td class="col-md-5">Sequence ID:</td><td class="col-md-6"><?=$srrNum;?></td></tr>
			  <tr><td class="col-md-1">&nbsp;</td><td class="col-md-5">Converage:</td><td  class="col-md-6"><?=$reports["converage"];?></td></tr>
			  <tr><td class="col-md-1">&nbsp;</td><td class="col-md-5">Depth:</td><td  class="col-md-6"><?=$reports["depth"];?></td></tr>
			  <tr><td class="col-md-1">&nbsp;</td><td class="col-md-5"><strong>FastQC Result R1 </strong></td><td class="col-md-6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
			  <tr><td class="col-md-1">&nbsp;</td><td class="col-md-5"><div>Total sequences:</div></td><td class="col-md-6"><?=$reports["totalSeq01"];?></td></tr>
			  <tr><td class="col-md-1">&nbsp;</td><td class="col-md-5"><div>Sequence  length:</div></td><td class="col-md-6"><?=$reports["seqLen01"];?></td></tr>
			  <tr><td class="col-md-1">&nbsp;</td><td class="col-md-5"><div>%GC:</div></td><td class="col-md-6"><?=$reports["GC01"];?></td></tr>
	</table>
	</td>
  </tr>
  <tr>
    <td>
	<table class="table mytable table-border">
	 <tr>
	  <td  class="col-md-6" align="center">
	  <?=base64_image($png11,230);?>
	  <br>
	  <?=base64_image($icon11,17);?>&nbsp;&nbsp;&nbsp;
	  Per base sequence quality
	  </td>
	  <td  class="col-md-6" align="center">
	  <?=base64_image($png12,230);?>
	  <br>
	  <?=base64_image($icon12,17);?>&nbsp;&nbsp;&nbsp;	  
	  Per sequence quality scores
	  </td>
	 </tr>
	 <tr>
	  <td  class="col-md-6" align="center">
	  <?=base64_image($png13,230);?>
	  <br>
	  <?=base64_image($icon13,17);?>&nbsp;&nbsp;&nbsp;
	  Per base sequence content
	  </td>
	  <td  class="col-md-6" align="center">
	  <?=base64_image($png14,230);?>
	  <br>
	  <?=base64_image($icon14,17);?>&nbsp;&nbsp;&nbsp;
	  Per sequcenc GC content
	  </td>
	 </tr>
	 <tr>
	  <td  class="col-md-6" align="center">
	   <?php 
	   if (is_file($png15)){
        echo base64_image($png15,230)."<br>";
        echo base64_image($icon15,17)."&nbsp;&nbsp;&nbsp;kmer Content";
	   }else{
	   echo "&nbsp;";
	   }
	   ?>
	  </td>
	  <td  class="col-md-6" align="center">
	  <?=base64_image($png16,230);?>
	  <br>. 
	  <?=base64_image($icon16,17);?>&nbsp;&nbsp;&nbsp;
	  Sequence Duplication Levels
	  </td>
	 </tr>	  
	 <tr>
	  <td  class="col-md-6" align="center">
	  </td>
	  <td  class="col-md-6" align="center">
		<?=base64_image($icon01,14);?>&nbsp;Pass&nbsp;&nbsp;  
		<?=base64_image($icon02,13);?>&nbsp;Warn&nbsp;&nbsp;
		<?=base64_image($icon03,13);?>&nbsp;Error 
	  </td>
	 </tr>	 	 	 
	</table>
	</td>
  </tr>  
</table>
<table class="table mytable table-borderless">
  <tr><td class="col-md-1"></td><td class="col-md-10" align="center">Agricultural Genomics Research Laboratory, Agricultural Technology Research Institue</td><td class="col-md-1" align="left">1/2</td></tr>
</table>
</div> 
<p style="page-break-after:always">
<div class="container custom-container-width">
<br>
<table class="table mytable table-borderless">
  <tr>
    <td>
	<table class="table mytable table-borderless">
			  <tr><td class="col-md-1">&nbsp;&nbsp;&nbsp;</td><td class="col-md-5"><strong>FastQC Result R2 </strong></td><td class="col-md-6">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td></tr>
			  <tr><td class="col-md-1">&nbsp;</td><td class="col-md-5"><div>Total sequences:</div></td><td class="col-md-6"><?=$reports["totalSeq02"];?></td></tr>
			  <tr><td class="col-md-1">&nbsp;</td><td class="col-md-5"><div>Sequence length:</div></td><td class="col-md-6"><?=$reports["seqLen02"];?></td></tr>
			  <tr><td class="col-md-1">&nbsp;</td><td class="col-md-5"><div>%GC:</div></td><td class="col-md-6"><?=$reports["GC02"];?></td></tr>
	</table>
	</td>
  </tr>
  <tr>
    <td>
	<table class="table mytable table-border">
	 <tr>
	  <td  class="col-md-6" align="center">
	  <?=base64_image($png21,230);?>
	  <br>
	  <?=base64_image($icon21,17);?>&nbsp;&nbsp;&nbsp;
	  Per base sequence quality
	  </td>
	  <td  class="col-md-6" align="center">
	  <?=base64_image($png22,230);?>
	  <br>
	  <?=base64_image($icon22,17);?>&nbsp;&nbsp;&nbsp;	  
	  Per sequence quality scores
	  </td>
	 </tr>
	 <tr>
	  <td  class="col-md-6" align="center">
	  <?=base64_image($png23,230);?>
	  <br>
	  <?=base64_image($icon23,17);?>&nbsp;&nbsp;&nbsp;
	  Per base sequence content
	  </td>
	  <td  class="col-md-6" align="center">
	  <?=base64_image($png24,230);?>
	  <br>
	  <?=base64_image($icon24,17);?>&nbsp;&nbsp;&nbsp;
	  Per sequcenc GC content
	  </td>
	 </tr>
	 <tr>
	  <td  class="col-md-6" align="center">
	   <?php 
	   if (is_file($png25)){
        echo base64_image($png25,230)."<br>";
        echo base64_image($icon25,17)."&nbsp;&nbsp;&nbsp;kmer Content";
	   }else{
	   echo "&nbsp;";
	   }
	   ?>
	  </td>
	  <td  class="col-md-6" align="center">
	  <?=base64_image($png26,230);?>
	  <br>. 
	  <?=base64_image($icon26,17);?>&nbsp;&nbsp;&nbsp;
	  Sequence Duplication Levels
	  </td>
	 </tr>	  
	 <tr>
	  <td  class="col-md-6" align="center">
	  </td>
	  <td  class="col-md-6" align="center">
		<?=base64_image($icon01,14);?>&nbsp;Pass&nbsp;&nbsp;  
		<?=base64_image($icon02,13);?>&nbsp;Warn&nbsp;&nbsp;
		<?=base64_image($icon03,13);?>&nbsp;Error 
	  </td>
	 </tr>	 	 	 
	</table>
	</td>
  </tr>  
</table>
<table class="table mytable table-borderless">
  <tr><td class="col-md-12" align="right"><h4>Production / Data : ________________________</h4></td></tr>
  <tr><td class="col-md-12" align="right"><h4>Confirm / Data : ________________________</h4></td></tr>

</table>
<br><br>
<table class="table mytable table-borderless">
  <tr><td class="col-md-1"></td><td class="col-md-10" align="center">Agricultural Genomics Research Laboratory, Agricultural Technology Research Institue</td><td class="col-md-1" align="left">2/2</td></tr>
</table>
</div>
</body>
</html>
<?php

function parsefastqc($fastQCFile){
 $cmd="cat ".$fastQCFile." |grep '^Total Sequences'"; $result=explode("\t",shell_exec($cmd));  $ts=trim($result[1]);
 $cmd="cat ".$fastQCFile." |grep '^Sequence length'"; $result=explode("\t",shell_exec($cmd));  $sl=trim($result[1]);
 $cmd="cat ".$fastQCFile." |grep '^%GC'"; $result=explode("\t",shell_exec($cmd));   $gcp=trim($result[1]);

 $cmd="cat ".$fastQCFile." |grep '^>>Per base sequence quality'"; $result=explode("\t",shell_exec($cmd)); $tmp=trim($result[1]);
 if($tmp=="pass"){ $pbsq="tick"; }elseif($tmp=="warn"){ $pbsq="warning"; }elseif($tmp=="fail"){ $pbsq="error"; }

 $cmd="cat ".$fastQCFile." |grep '^>>Per sequence quality scores'"; $result=explode("\t",shell_exec($cmd));  $tmp=trim($result[1]);
 if($tmp=="pass"){ $psqs="tick"; }elseif($tmp=="warn"){ $psqs="warning"; }elseif($tmp=="fail"){ $psqs="error"; }

 $cmd="cat ".$fastQCFile." |grep '^>>Per base sequence content'"; $result=explode("\t",shell_exec($cmd));  $tmp=trim($result[1]);
 if($tmp=="pass"){ $pbsc="tick"; }elseif($tmp=="warn"){ $pbsc="warning"; }elseif($tmp=="fail"){ $pbsc="error"; }

 $cmd="cat ".$fastQCFile." |grep '^>>Per sequence GC content'"; $result=explode("\t",shell_exec($cmd));  $tmp=trim($result[1]);
 if($tmp=="pass"){ $psgc="tick"; }elseif($tmp=="warn"){ $psgc="warning"; }elseif($tmp=="fail"){ $psgc="error"; }

 $cmd="cat ".$fastQCFile." |grep '^>>Kmer Content'"; $r=shell_exec($cmd);
 if ($r!=""){ 
  $result=explode("\t",shell_exec($cmd));   $tmp=trim($result[1]);
  if($tmp=="pass"){ $kmc="tick"; }elseif($tmp=="warn"){ $kmc="warning"; }elseif($tmp=="fail"){ $kmc="error"; }
 }else{
  //echo $cmd."\n";
  $kmc="";
 }
 
 $cmd="cat ".$fastQCFile." |grep '^>>Sequence Duplication Levels'"; $result=explode("\t",shell_exec($cmd));  $tmp=trim($result[1]);
 if($tmp=="pass"){ $sdl="tick"; }elseif($tmp=="warn"){ $sdl="warning"; }elseif($tmp=="fail"){ $sdl="error"; }

 return array($ts, $sl, $gcp, $pbsq, $psqs, $pbsc, $psgc, $kmc, $sdl);	
}

function base64_image($file,$width){
 $fileEncode = base64_encode(file_get_contents($file));
 return "<img width=\"${width}\" src=\"data:image/png;base64,${fileEncode}\">";
}

?>


