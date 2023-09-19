<?php
if (($_SERVER["REQUEST_METHOD"] == "POST") && isset($_POST["expNum"])){
$expNum=$_POST["expNum"];	
session_start();
if (!isset($_SESSION["username"])) exit();
$account=$_SESSION["username"];
$dirBin=dirname(__FILE__);
require "$dirBin/vendor/autoload.php";
$db = new PHPLogin\DbConn;

## SELECT
try {
 $err = null;
 $sql = "SELECT * FROM  pipelineJob  WHERE expNum = '$expNum' and account='$account'";
 $stmt = $db->conn->prepare($sql);
 $stmt->execute();
 $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
 unset($stmt);
} catch (PDOException $e) {
 $err = "Error: " . $e->getMessage();
}
#Determines returned value ('true' or error code)
$resp = ($err == null) ? true : $err;
if ($resp==1) {
 $record="";
 foreach($result as $row){
  $id=$row["id"];
  $json=$row["information"];
  $json_data = json_decode($json,true);
  $RunID=$json_data["RunID"];
  if (is_file("/disk/files/".$account."/".$id."/".$RunID."_check.png") && is_file("/disk/files/".$account."/".$id."/".$RunID.".png")){
   $identify_file_01="/disk/files/".$account."/".$id."/".$RunID."_check.png";
   $identify_file_02="/disk/files/".$account."/".$id."/".$RunID.".png";  
  }
  $srrNum=basename($json_data["srrNum"],"_R2.fastq.gz");
  $record_file="/disk/files/".$account."/".$id."/".$srrNum.".record";
  $arr=file($record_file);
  foreach($arr as $k => $v){
   $smpArr=explode(":",trim($v));
   if (count($smpArr)==2) $reports[$srrNum][$smpArr[0]]=$smpArr[1];
  }
  $converage=$reports[$srrNum]["converage"];  
  if ($converage<80) $converage="<font color=\"#ff0000\">".$converage."</font>"; 
  $icon01="/Icons/tick.png"; $icon02="/Icons/warning.png"; $icon03="/Icons/error.png";
  $smp=$reports[$srrNum]["Per base sequence quality1"]; if ($smp=="PASS") { $icon11=$icon01; }elseif ($smp=="WARN") { $icon11=$icon02; }elseif ($smp=="FAIL") { $icon11=$icon03; }
  $smp=$reports[$srrNum]["Per base sequence quality2"]; if ($smp=="PASS") { $icon21=$icon01; }elseif ($smp=="WARN") { $icon21=$icon02; }elseif ($smp=="FAIL") { $icon21=$icon03; }
  $record.='
<tr class="text-center"> 
	<td scope="col">&nbsp;</td>
	<td scope="col">'.$srrNum.'</td>
	<td scope="col">R1</td>
	<td scope="col">'.$reports[$srrNum]["totalSeq01"].'</td>
	<td scope="col">'.$reports[$srrNum]["GC01"].'</td>
	<td scope="col"><img src="'.$icon11.'" width="20"></td>
	<td scope="col">'.$converage.'</td>
	<td scope="col">'.$reports[$srrNum]["depth"].'</td>
</tr>
<tr class="text-center"> 
	<td scope="col">&nbsp;</td>
	<td scope="col">&nbsp;</td>
	<td scope="col">R2</td>
	<td scope="col">'.$reports[$srrNum]["totalSeq02"].'</td>
	<td scope="col">'.$reports[$srrNum]["GC02"].'</td>
	<td scope="col"><img src="'.$icon21.'" width="20"></td>
	<td scope="col">&nbsp;</td>
	<td scope="col">&nbsp;</td>
</tr>
';  
 }
}
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
		<div><small>CODE : <?=$expNum;?></small></div>   
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
if (isset($identify_file_01) && isset($identify_file_02) && is_file($identify_file_01) && is_file($identify_file_02)){
?>

<p style="page-break-after:always">
<div class="container custom-container-width">
<table class="table mytable table-borderless">
 <tr>
 <td></td>
  <td align="center">
	<br><br>
	<?=base64_image($identify_file_01,550);?>
	<br><br>
	<?=base64_image($identify_file_02,550);?>
	<br><br>
 </td>
 <td></td> 
 </tr> 
  <tr><td class="col-md-1"></td><td class="col-md-10" align="center">Agricultural Genomics Research Laboratory, Agricultural Technology Research Institue</td><td class="col-md-1" align="left"></td></tr> 
</table>
</div> 
<?php }?>
</body>
</html>
<?php



}
function base64_image($file,$width){
 $fileEncode = base64_encode(file_get_contents($file));
 return "<img width=\"${width}\" src=\"data:image/png;base64,${fileEncode}\">";
}
