<?php
$id=$argv[1];
$status=$argv[2];
if (filter_var($id, FILTER_VALIDATE_INT) && filter_var($status, FILTER_VALIDATE_INT)) {
 $dirBin=dirname(__FILE__);
 require "$dirBin/vendor/autoload.php";
 $db = new PHPLogin\DbConn;
 ## UPDATE
 try {
  $err = null;
  $sql="UPDATE pipelineJob SET status = '".$status."'  WHERE id = '".$id."'";
  $stmt = $db->conn->prepare($sql);
  $stmt->execute();
 } catch (PDOException $e) {
  $err = 'Error: ' . $e->getMessage();
 }
 #Determines returned value ('true' or error code)
 $resp = ($err == null) ? true : $err;

}