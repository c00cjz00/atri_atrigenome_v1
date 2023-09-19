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
$sql = "show full columns from $myTable";
$stmt = $db->conn->prepare($sql);
$stmt->execute();
$columns_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
$thead="<tr>";
foreach($columns_info as $row){
 $thead.="<th>".$row["Comment"]."</th>";
}
$thead.="</tr>";



$thead="<tr>";
$thead.="<th>編號</th>";
$thead.="<th>派送狀態</th>";
$thead.="<th>派送時間</th>";
$thead.="<th>派送帳號</th>";
$thead.="<th>實驗編號</th>";
$thead.="<th>工作派送代號</th>";
$thead.="<th>輸入資料</th>";
$thead.="</tr>";


$sql="SELECT * FROM $myTable where account = '$authorName'";
$stmt = $db->conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$tbody="";
foreach($result as $row){
 $log_file=$elfinderFolder_USER."/".$row["id"]."/job.log";
 if (is_file($log_file)){
  $tbody.="<tr>";
  $tbody.="<td>".$row["id"]."</td>";
  $value=$row["status"];
  $url="elfinder.php#elf_l1_".base64_encode($row["id"]);
  if ($value==0){
   $value='<a href="'.$url.'" class="btn btn-warning" role="button">Running</a>';
  }elseif ($value==1){
   $value='<a href="'.$url.'" class="btn btn-success" role="button">Finish</a>';
  }elseif ($value==2){
   $value='<a href="'.$url.'" class="btn btn-danger" role="button">Error</a>';
  }
  $tbody.="<td>".$value."</td>";
  $tbody.="<td>".$row["timeID"]."</td>";
  $tbody.="<td>".$row["account"]."</td>";
  $tmp=explode("_",$row["expNum"]);
  $tbody.="<td>".$tmp[0]."</td>";
  $tbody.="<td>".$row["pipeline"]."</td>";
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
