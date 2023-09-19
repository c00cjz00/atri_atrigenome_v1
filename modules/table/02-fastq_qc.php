<?php
/* program start */
if (!defined('MODULE_FILE')) {
 die ("You can't access this file directly...");
}

/* configure */
$dirBin=dirname(dirname(dirname(__FILE__)));
require "$dirBin/login/dbconf.php";
$elfinderFolder_USER=$elfinderFolder_HOME."/".$_SESSION["username"];
$elfinderFolder_S3DISK_USER=$elfinderFolder_S3DISK."/".$_SESSION["username"];
$Scripts="$dirBin/modules/Scripts";
#$elfinderFolder_PUBLIC="/disk/public";
$steps="1b";
$inputArr_Global=array("expNum","SeqDate","RunID","Organism","LibrarySource","Platform","LibraryLayout","InsertSize");
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
  $fastqFile=trim($srrNumArr[$j]);
  if ($fastqFile!=""){
   ## fastq file check
   $smpArr=explode(":",$fastqFile);
   if (count($smpArr)==2){
	$smp=$smpArr[0]; $smp=str_replace('HOME',$elfinderFolder_USER,$smp); $smp=str_replace('PUBLIC',$elfinderFolder_PUBLIC,$smp); $smp=str_replace('S3DISK',$elfinderFolder_S3DISK_USER,$smp);
	$fastqFile1=$smp;
	$smp=$smpArr[1]; $smp=str_replace('HOME',$elfinderFolder_USER,$smp); $smp=str_replace('PUBLIC',$elfinderFolder_PUBLIC,$smp); $smp=str_replace('S3DISK',$elfinderFolder_S3DISK_USER,$smp);
	$fastqFile2=$smp;
    if (is_file($fastqFile1) && is_file($fastqFile2)){
     $fmpArr=explode("_",basename($fastqFile1)); 
     $sraIDTmp=$fmpArr[0]; 
	 array_push($sraIDArr,$sraIDTmp);
	 $fastqFileArr[$sraIDTmp][0]=$fastqFile;
	 $fastqFileArr[$sraIDTmp][1]=$fastqFile1;
	 $fastqFileArr[$sraIDTmp][2]=$fastqFile2;

    }
   }	  
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
  $fastqFile=$fastqFileArr[$sraID][0];
  $fastqFile1=$fastqFileArr[$sraID][1];
  $fastqFile2=$fastqFileArr[$sraID][2];
  $json[$inputArr_Local[0]]=$fastqFile;      
  $json_encode=json_encode($json);

  ## status: 0 run, 1 success, 2 error 
  $status=0;

  ## INSERT
  $expNum_tmp=$expNum."_".$currentTime;  
  $sql="INSERT INTO `pipelineJob` (`id`, `timeID`, `account`, `expNum`, `pipeline`, `status`, `information`) VALUES (NULL, '$currentTime', '$authorName', '$expNum_tmp', '$steps', '$status', '$json_encode')";
  $stmt = $db->conn->prepare($sql);	 
  $stmt->execute();
  $last_id = $db->conn->lastInsertId();
  $outputFolder=$elfinderFolder_USER."/".$last_id;
  if (!is_dir($outputFolder)){
   mkdir($outputFolder, 0770, true); chmod($outputFolder, 0770);
  }
  $json_file=$outputFolder."/".$sraID.".json";
  $fp = fopen($json_file, "w"); fwrite($fp, $json_encode); fclose($fp);

  $app="$dirBin/modules/app/03-FASTQ_and_GC_report.sh $fastqFile1 $fastqFile2 $outputFolder $last_id $expNum $Scripts $ssh_account $dirBin";
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
 /* 列出表單  from dataFunction.php*/	
 $submitStepTitle="流程Pipeline ► 基因序列 ► 01b. 定序品質分析 (fastq)";
 //$myArr=array(3,4); $descriptionArr=myAnnotation($myArr); $description=implode("\n",$descriptionArr);
 myTable(); 
}

function myTable(){
?>
<form action="modules.php?name=table&d_op=fastq_qc" method="post">
 <div class="panel-group">
  <div class="panel panel-primary">
   <div class="panel-heading"><h4>流程Pipeline ► 基因序列 ► 01a. SRA data download</h4></div>
   <div class="panel-body">
   <div class="row">
    <div class="col-md-12">
     <i class="fa  fa-user blue"></i> 流程說明<input type="hidden" name="encodeKey" value="<?=$encodeKey;?>">
     <textarea  class="form-control" id="none" name="none" rows="4" disabled>
► 定序品質分析 (FsatQC)
► 定序品質報表 (QC Report)
	 </textarea>
    </div>
   </div>
   <br>
   <div class="row">
    <div class="col-md-6">
     <i class="fa fa-check-square-o blue"></i> 實驗編號
     <input type="text" class="form-control" id="expNum" name="expNum">
     <small id="Help" class="form-text text-muted"><font color="#bbbbbb">實驗編號作為檔案命名依據</font></small>	 
    </div>
   </div>
   <br>

   <div class="row">
    <div class="col-md-12">
     <i class="fa fa-check-square-o blue"></i> pair fastq.gz (原始定序資料)
     <textarea  class="form-control" id="srrNum" name="srrNum" rows="4"></textarea>
     <small id="Help" class="form-text text-muted">
	 <font color="#bbbbbb">
	 PUBLIC/SRR12362016_R1.fastq.gz:PUBLIC/SRR12362016_R2.fastq.gz PUBLIC/SRR12362017_R1.fastq.gz:PUBLIC/SRR12362017_R2.fastq.gz
	 </font></small>
    </div>
   </div>
   <br>  
   <div class="row">
    <div class="col-md-6">
     <p class="bg-primary h3">定序樣本資訊</p>
    </div>
   </div><br>
   <div class="row">
    <div class="col-md-6">
     <i class="fa fa-check-square-o blue"></i> 定序日期/SeqDate
     <input type="date" class="form-control" id="SeqDate" name="SeqDate">   
     <small id="Help" class="form-text text-muted"><font color="#bbbbbb">2017/12/12</font></small>            
    </div>
    <div class="col-md-6">
     <i class="fa fa-check-square-o blue"></i> 上機代號 /RunID
     <input type="text" class="form-control" id="RunID" name="RunID">   
     <small id="Help" class="form-text text-muted"><font color="#bbbbbb">A08</font></small>         
    </div>
   </div><br>
   <div class="row">
     <div class="col-md-6">
     <i class="fa fa-check-square-o blue"></i> 微生物/Organism
     <input type="text" class="form-control" id="Organism" name="Organism">   
     <small id="Help" class="form-text text-muted"><font color="#bbbbbb">E. coli</font></small>            
    </div>
    <div class="col-md-6">
     <i class="fa fa-check-square-o blue"></i> 樣本庫種類/LibrarySource
     <input type="text" class="form-control" id="LibrarySource" name="LibrarySource">   
     <small id="Help" class="form-text text-muted"><font color="#bbbbbb">whole genome</font></small>         
    </div>
   </div><br> 
   <div class="row">
    <div class="col-md-6">
     <i class="fa fa-check-square-o blue"></i> 定序平台/Platform
     <input type="text" class="form-control" id="Platform" name="Platform">   
     <small id="Help" class="form-text text-muted"><font color="#bbbbbb">illumina MiSeq</font></small>            
    </div>
    <div class="col-md-6">
     <i class="fa fa-check-square-o blue"></i> 定序方式/LibraryLayout
     <input type="text" class="form-control" id="LibraryLayout" name="LibraryLayout">   
     <small id="Help" class="form-text text-muted"><font color="#bbbbbb">Pair-End</font></small>         
    </div>
   </div><br> 
   <div class="row">
    <div class="col-md-6">
     <i class="fa fa-check-square-o blue"></i> 定序長度/InsertSize
     <input type="text" class="form-control" id="InsertSize" name="InsertSize">   
     <small id="Help" class="form-text text-muted"><font color="#bbbbbb">2x150</font></small>            
    </div>
   </div><br> 
   <hr /><input class="btn btn-primary" name="submit" type="submit" value="submit" />   
  </div>	
 </div>
</div> 
</form>
<br><br><br>
<?php }?>