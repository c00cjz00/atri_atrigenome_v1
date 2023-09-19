<?php
$authorName=$_SESSION['username'];
/* program start */
if (!defined('MODULE_FILE')) {
 die ("You can't access this file directly...");
}
## OBJECT
$db = new PHPLogin\DbConn;
## SELECT
$myTable="atri_card";
$sql = "show full columns from $myTable";
$stmt = $db->conn->prepare($sql);
$stmt->execute();
$columns_info = $stmt->fetchAll(PDO::FETCH_ASSOC);
$thead="<tr>";
foreach($columns_info as $row){
 $thead.="<th>".$row["Field"]."</th>";
}
$thead.="</tr>";
$sql="SELECT * FROM $myTable";
$stmt = $db->conn->prepare($sql);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
$tbody="";
foreach($result as $row){
 $tbody.="<tr>";
 foreach($row as $key => $value){
  $tbody.="<td>".$value."</td>";
 }
 $tbody.="</tr>";
}
?>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css">	
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>	
<script type="text/javascript" language="javascript" src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>	
<script type="text/javascript" class="init">
	$(document).ready(function() {
		var table = $('#myTable').DataTable( {
		 //"order": [[ 0, "desc" ],[ 0, "desc" ]],
		 "scrollX": true
		} );
	} );
</script>
<table id="myTable" class="table table-striped table-bordered nowrap" style="width:100%">
<thead><?=$thead;?></thead>
<tbody><?=$tbody;?></tbody>
</table>
