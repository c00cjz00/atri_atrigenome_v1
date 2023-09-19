<?php
if (!defined('MODULE_FILE')) {
	die ("You can't access this file directly...");
}
$d_op=$_GET["d_op"];
$d_op = addslashes(trim($d_op));	
switch($d_op) {
    case "pipeline_table":
        include_once("modules/$module_name/00-pipeline_table.php");
    break;	
    case "sra_download":
        include_once("modules/$module_name/01-sra_download.php");
    break;
    case "fastq_qc":
        include_once("modules/$module_name/02-fastq_qc.php");
    break;
    case "pipeline_report_single":
        include_once("modules/$module_name/10-pipeline_report_single.php");
    break;		
    case "pipeline_report_multiple":
        include_once("modules/$module_name/11-pipeline_report_multiple.php");
    break;
    case "card_table":
        include_once("modules/$module_name/20-card_table.php");
    break;
}
?>

