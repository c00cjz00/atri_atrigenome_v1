<?php
/* program start */
if (!defined('MODULE_FILE')) {
 die ("You can't access this file directly...");
}

/* configure */
$dirBin=dirname(dirname(dirname(__FILE__)));
require "$dirBin/login/dbconf.php";
$elfinderFolder_USER=$elfinderFolder_HOME."/".$_SESSION["username"];
$steps="1a";
$inputArr_Global=array("expNum");
$inputArr_Local=array("srrNum");

/* date */
date_default_timezone_set("Asia/Taipei"); 
$currentTime=date("ymdHis");

/* require start */
if ($_SERVER["REQUEST_METHOD"] == "POST"){
 # Check input value
 for($i=0;$i<count($inputArr_Global);$i++){
  $input=$inputArr_Global[$i];
  if (!isset($_POST[$input]) || ($_POST[$input]=="")){
   echo "<h3>*欄位資料遺漏填寫</h3><br><br>"; backhome("請回上頁01"); exit();
  }else{
   if (check_other($_POST[$input])==1) {
    echo "<h3>*欄位資料遺漏填寫</h3><br><br>"; backhome("請回上頁01a"); exit();	   
   }
   $$input=trim($_POST[$input]);
   $json[$input]=trim($_POST[$input]);      
  }
 }
 for($i=0;$i<count($inputArr_Local);$i++){
  $input=$inputArr_Local[$i];
  if (!isset($_POST[$input]) || ($_POST[$input]=="")){
   echo "<h3>*欄位資料遺漏填寫</h3><br><br>"; backhome("請回上頁02"); exit();
  }else{  
   $$input=trim($_POST[$input]);
  }
 } 
 
 ## fix srrNum
 $srrNum=str_replace(" ","\n",$srrNum); $srrNum=str_replace("\t","\n",$srrNum); 
 $srrNumArr=explode("\n",$srrNum);
 $sraIDArr=array();
 for($j=0;$j<count($srrNumArr);$j++){
  $tmp=trim($srrNumArr[$j]);
  if ($tmp!=""){
   if (check_other($tmp)==1) {
    echo "<h3>*欄位資料遺漏填寫</h3><br><br>"; backhome("請回上頁02a"); exit();	   
   }
   array_push($sraIDArr,$tmp);
  } 
 }
 ## check
 if (count($sraIDArr)==0){
  echo "<h3>*欄位資料遺漏填寫</h3><br><br>"; backhome("請回上頁03"); exit();
 } 

 /* 送出工作 ssh */
 ## OBJECT
 $db = new PHPLogin\DbConn;	
 for($i=0;$i<count($sraIDArr);$i++){
  ## 輸入資料庫
  $sraID=$sraIDArr[$i];	 
  $json[$inputArr_Local[0]]=$sraID;      
  $json_encode=json_encode($json);
  
  ## status: 0 run, 1 success, 2 error 
  $status=0;
 
  ## INSERT
  $expNum_tmp=$expNum."_".$currentTime;
  $sql="INSERT INTO `pipelineJob` (`id`, `timeID`, `account`, `expNum`, `pipeline`, `status`, `information`) VALUES (NULL, '$currentTime', '$authorName', '$expNum_tmp', '$steps', '$status', '$json_encode')";

  $stmt = $db->conn->prepare($sql);	 
  $stmt->execute();
  $last_id = $db->conn->lastInsertId();
  ## 輸出位置
	// ```
	// folder=/disk/files
	// admin=$(whoami)
	// chown -R ${admin}.${admin} ${folder}
	// chmod -R 700 ${folder}
	// setfacl -R -m u:${admin}:rwx ${folder}
	// setfacl -R -d -m u:${admin}:rwx ${folder}
	// guest=www-data
	// setfacl -R -m u:${guest}:rwx ${folder}
	// setfacl -R -d -m u:${guest}:rwx ${folder}
	// guest=atri
	// setfacl -R -m u:${guest}:rwx ${folder}
	// setfacl -R -d -m u:${guest}:rwx ${folder}
	// ``` 
  $outputFolder=$elfinderFolder_USER."/".$last_id;
  if (!is_dir($outputFolder)){
   mkdir($outputFolder, 0770, true); chmod($outputFolder, 0770);
  }
  $json_file=$outputFolder."/".$sraID.".json";
  $fp = fopen($json_file, "w"); fwrite($fp, $json_encode); fclose($fp);

  $app="$dirBin/modules/app/01-SRA_download.sh $sraID $outputFolder $last_id $ssh_account $dirBin";
  $log="$outputFolder/job.log";
  $cmd[]="$app > $log 2>&1";  
 }
 if (isset($cmd)){
  $cmd=implode(" && ",$cmd);   
  $connection = ssh2_connect("localhost",22);  
  if (ssh2_auth_password($connection,$ssh_account,$ssh_password)) {   
   $stream = ssh2_exec($connection, $cmd);  
   stream_set_blocking($stream,0);  
   echo stream_get_contents($stream);   
  }
 }

 ## 工作送出完成
 sleep(1);   
 echo "<h1>工作流程已送出</h1><Br>";
 echo '<a href="/modules.php?name=table&d_op=pipeline_table" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">派送結果</a>';
}else{
 myTable(); 
}



function myTable(){
?>
<form method="post">
 <div class="panel-group">
  <div class="panel panel-primary">
   <div class="panel-heading"><h4>流程Pipeline ► 基因序列 ► 01a. SRA data download</h4></div>
   <div class="panel-body">
   <div class="row">
    <div class="col-md-12">
     <i class="fa  fa-user blue"></i> 流程說明<input type="hidden" name="encodeKey" value="<?=$encodeKey;?>">
     <textarea  class="form-control" id="none" name="none" rows="4" disabled>
► 自NCBI下載SRR/SRP/SRX檔案
► 解壓縮成定序資料 fastq.gz (fastq-dump)
	 </textarea>
    </div>
   </div>
   <br> 
   <div class="row">
    <div class="col-md-6">
     <i class="fa fa-check-square-o blue"></i> 實驗編號
     <input type="text" class="form-control" id="expNum" name="expNum">
    </div>
   </div>
   <br>   
   <div class="row">
    <div class="col-md-6">
     <i class="fa fa-check-square-o blue"></i> NCBI SRA
     <textarea  class="form-control" id="srrNum" name="srrNum" rows="4"></textarea>
     <!--input type="text" class="form-control" id="srrNum" name="srrNum"-->
     <small id="Help" class="form-text text-muted"><font color="#bbbbbb">SRR12362016 SRR12362017</font></small>
    </div>
   </div>
   <br>    
   <hr /><input class="btn btn-primary" name="submit" type="submit" value="submit" />   
   </div>
  </div>
 </div>
</form>
<br><br><br>
<?php }?>