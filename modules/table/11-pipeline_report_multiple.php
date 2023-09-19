<?php
$authorName=$_SESSION['username'];
/* program start */
if (!defined('MODULE_FILE')) {
 die ("You can't access this file directly...");
}
/* configure */
$dirBin=dirname(dirname(dirname(__FILE__)));
require "$dirBin/login/dbconf.php";
$elfinderFolder_USER=$elfinderFolder_HOME."/".$_SESSION["username"];

## OBJECT
$db = new PHPLogin\DbConn;
## SELECT
$myTable="pipelineJob";

$thead="<tr>";
$thead.="<th>編號</th>";
$thead.="<th>派送狀態</th>";
$thead.="<th>派送時間</th>";
$thead.="<th>派送帳號</th>";
$thead.="<th>實驗編號</th>";
$thead.="<th>上機代號</th>";
$thead.="<th>工作派送代號</th>";
$thead.="<th>輸入檔案</th>";
$thead.="<th>輸入資料</th>";
$thead.="</tr>";

$sql="SELECT * FROM $myTable where account = '$authorName' and pipeline='1b'";
$stmt = $db->conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row){
 $job_id=$row["id"];
 $status=$row["status"]; 
 $expNum=$row["expNum"];
 $arr=json_decode($row["information"],True);
 $fastq=$arr["srrNum"];
 $RunID=$arr["RunID"];
 $srrNum=basename($fastq,"_R2.fastq.gz");
 $data[$expNum]["fastq"][$job_id]=$fastq;
 $data[$expNum]["srrNum"][$job_id]=$srrNum;
 $log_file=$elfinderFolder_USER."/".$job_id."/job.log";
 if (($status!=1) || !is_file($log_file)) $check[]=$expNum;
}
$tbody="";
foreach($result as $row){
 $expNum=$row["expNum"];
 if (!isset($$expNum) && !in_array($expNum,$check)){
  $tbody.="<tr>";
  $$expNum=1; 
  $tbody.="<td>".$row["id"]."</td>";
  $value='<form target="_blank" method="post" action="/modules/app/05-FASTQ_and_GC_summary_printer.php"><input type="hidden" id="expNum" name="expNum" value="'.$row["expNum"].'" /><button type="submit" class="btn btn-primary">HTML</button></form>';
  $tbody.="<td>".$value."</td>";
  //$tbody.="<td>".$row["status"]."</td>";
  
  $tbody.="<td>".$row["timeID"]."</td>";
  $tbody.="<td>".$row["account"]."</td>";
  $tbody.="<td>".$row["expNum"]."</td>";
  $tbody.="<td>".$RunID."</td>";
  $tbody.="<td>".$row["pipeline"]."</td>";
  $fastq_link="";
  foreach($data[$expNum]["fastq"] as $k => $v){
   $url="elfinder.php#elf_l1_".base64_encode($k);	  
   $fastq_link.='<a href="'.$url.'" target="_blank">'.$v.'</a><br>';
  }
  $tbody.="<td>".$fastq_link."</td>";
  $tbody.="<td>".$row["information"]."</td>";
  $tbody.="</tr>";
 }
}
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">	
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>	
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>	
<script type="text/javascript" class="init">
	$(document).ready(function() {
		var table = $('#myTable').DataTable( {
		 "order": [[ 2, "desc" ],[ 0, "desc" ]],
		 "scrollX": true
		} );
	} );
</script>
<table id="myTable" class="table table-striped table-bordered nowrap" style="width:100%">
<thead><?=$thead;?></thead>
<tbody><?=$tbody;?></tbody>
</table>
