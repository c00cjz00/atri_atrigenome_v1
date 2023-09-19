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

$sql="SELECT * FROM $myTable where account = '$authorName' and pipeline='1b' and status='1'";
$stmt = $db->conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$tbody="";
foreach($result as $row){
 $log_file=$elfinderFolder_USER."/".$row["id"]."/job.log";
 if (is_file($log_file)){	
  $tbody.="<tr>";
  $tbody.="<td>".$row["id"]."</td>";

  $arr=json_decode($row["information"],True);
  $fastq=$arr["srrNum"];
  $RunID=$arr["RunID"];

  $srrNum=basename($fastq,"_R2.fastq.gz"); 
  $url_folder="/elfinder.php#elf_l1_".base64_encode($row["id"]);  
  $url_html="/php/connector.minimal.php?cmd=file&target=l1_".base64_encode($row["id"]."/".$srrNum.".record.html");
  $url_pdf="/php/connector.minimal.php?cmd=file&target=l1_".base64_encode($row["id"]."/".$srrNum.".record.pdf");
  $value='<a href="'.$url_html.'" class="btn btn-success" role="button">HTML</a> <a href="'.$url_pdf.'" class="btn btn-warning" role="button" download>PDF</a>';
  $tbody.="<td>".$value."</td>";
  
  $tbody.="<td>".$row["timeID"]."</td>";
  $tbody.="<td>".$row["account"]."</td>";
  $tbody.="<td>".$row["expNum"]."</td>";
  $tbody.="<td>".$RunID."</td>";
  $tbody.="<td>".$row["pipeline"]."</td>";
  $tbody.="<td><a href=\"".$url_folder."\" target=\"_blank\">".$fastq."</a></td>";  
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
