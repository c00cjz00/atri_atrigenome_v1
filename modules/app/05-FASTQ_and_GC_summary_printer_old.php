<?php
# example: php 05-FASTQ_and_GC_summary_printer.php aaa /disk/files/c00cjz00/4/SRR12362017.json /disk/files/c00cjz00/4/SRR12362017.record

//$expNum=$argv[1];
//$json_file=$argv[2];
//$record=$argv[3];
$json_file="/disk/files/c00cjz00/4/SRR12362017.json";
$record_file="/disk/files/c00cjz00/4/SRR12362017.record";
$json = file_get_contents($json_file);
$json_data = json_decode($json,true);

$srrNum=basename($record_file,".record");
$base=dirname($record_file)."/".$srrNum;

if (!is_file($record_file)) {echo "error\n"; exit();}
$tmpArr=file($record_file);
$arr=file($record_file);
foreach($arr as $k => $v){
 $smpArr=explode(":",trim($v));
 if (count($smpArr)==2) $reports[$smpArr[0]]=$smpArr[1];
}
print_r($reports);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title><?=$expNum;?></title>
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
		<div><!--small>CODE : <?=$expNum;?></small--></div>   
		<div><!--small>Effection Date : </small--></div>
	   </td>
	  </tr>
	</table>
	</td>
  </tr>
  <tr>
    <td>
	  <table class="table mytable table-borderless">
  	    <tr>
			<td colspan="6"><strong>I.　Sequencing Data</strong></td>
		</tr>		
		<tr>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">Sequencing date:</td>
			<td class="col-md-3"><?=$json_data["SeqDate"];?></td>		  
			<td class="col-md-2">Library Source:</td>
			<td class="col-md-3"><?=$json_data["LibrarySource"];?></td>	
			<td class="col-md-1">&nbsp;</td>
		</tr>
		<tr>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">Run ID:</td>
			<td class="col-md-3"><?=$json_data["RunID"];?></td>		  
			<td class="col-md-2">Library Layout:</td>
			<td class="col-md-3"><?=$json_data["LibraryLayout"];?></td>	
			<td class="col-md-1">&nbsp;</td>
		</tr>
		<tr>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">Organism:</td>
			<td class="col-md-3"><em><?=$json_data["Organism"];?></em></td>		  
			<td class="col-md-2">Platform:</td>
			<td class="col-md-3"><?=$json_data["Platform"];?></td>	
			<td class="col-md-1">&nbsp;</td>
		</tr>
		<tr>
			<td class="col-md-1">&nbsp;</td>
			<td class="col-md-2">Read length:</td>
			<td class="col-md-3"><?=$json_data["InsertSize"];?></td>		  
			<td class="col-md-2">&nbsp;</td>
			<td class="col-md-3">&nbsp;</td>	
			<td class="col-md-1">&nbsp;</td>
		</tr>
	  </table>
	</td>
  </tr>
  <tr>
    <td>
	  <table class="table table-border">	
		<thead>
			<tr>
				<th colspan="8"><strong>II.　Summary of quality</strong><br></th>
			</tr>		
			<tr>
				<th scope="col" class="text-center">#</th>
				<th scope="col" class="text-center" >Sequence ID</th>
				<th scope="col" class="text-center">Reads</th>
				<th scope="col" class="text-center">Total Sequence</th>
				<th scope="col"  class="text-center">%GC</th>
				<th scope="col" class="text-center">Sequence quality</th>
				<th scope="col" class="text-center">Coverage</th>
				<th scope="col" class="text-center">Depth</th>
			</tr>
		</thead>	  
		<tbody>	  
		<?=$record;?>			
		</tbody>	  
	  </table>
	</td>
  </tr>
  <tr>
    <td><br><br><br>
		<table class="table mytable table-borderless">
		  <tr><td class="col-md-12" align="right"><h4>Production / Data : ________________________</h4></td></tr>
		  <tr><td class="col-md-12" align="right"><h4>Confirm / Data : ________________________</h4></td></tr>
		</table>	
	</td>
  </tr>  
  <!--tr>
    <td>
<table class="table mytable table-borderless">
  <tr><td class="col-md-1"></td><td class="col-md-10" align="center">Agricultural Genomics Research Laboratory, Agricultural Technology Research Institue</td><td class="col-md-1" align="left">1/1</td></tr>
</table>	
	</td>
  </tr-->  
</table>
<?php
$img01="/disk/files/".$authorName."/".$expNum."/".$RunID."_check.png";
$img02="/disk/files/".$authorName."/".$expNum."/".$RunID.".png";
$img01Link="http://data.biobank.org.tw/files/".$authorName."/".$expNum."/".$RunID."_check.png";
$img02Link="http://data.biobank.org.tw/files/".$authorName."/".$expNum."/".$RunID.".png";
if (is_file($img01) && is_file($img02)){
?>

<p style="page-break-after:always">
<div class="container custom-container-width">
<table class="table mytable table-borderless">
 <tr>
 <td></td>
  <td align="center">
	<br><br><img src="<?=$img01Link;?>" width="550"><br><br><br>
	<img src="<?=$img02Link;?>" width="550"><br><br>
 </td>
 <td></td> 
 </tr> 
  <tr><td class="col-md-1"></td><td class="col-md-10" align="center">Agricultural Genomics Research Laboratory, Agricultural Technology Research Institue</td><td class="col-md-1" align="left"></td></tr> 
</table>
</div> 
<?php }?>
</body>
</html>