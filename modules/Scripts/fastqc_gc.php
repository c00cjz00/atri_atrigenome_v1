<?php
/***  cmd  ****
php fastqc_gc.php SRR4434213_R1.fastq.gz SRR4434213_R2.fastq.gz
****  end  ***/
$dirBin=dirname(__FILE__);
#include($dirBin."/config.php");
chdir($dirBin);
if (!isset($argv[1])){
 echo "請輸入fastq 1\n"; exit(); 
}else{
 $fastqFile1=trim($argv[1]); 
 $fastqFile2=trim($argv[2]);  
 $ID=basename($fastqFile1,"_R1.fastq.gz");  $outputfolder=dirname($fastqFile1); 
 $fastqFileQC1=$outputfolder."/".$ID."_R1_fastqc/fastqc_data.txt";
 $fastqFileQC2=$outputfolder."/".$ID."_R2_fastqc/fastqc_data.txt";
 $summary1=$outputfolder."/".$ID."_R1_fastqc/summary.txt";
 $summary2=$outputfolder."/".$ID."_R2_fastqc/summary.txt";
 $depthFile=$outputfolder."/".$ID.".depth";
 $recordFile=$outputfolder."/".$ID.".record";
 ## count ##
 if (!is_file($recordFile)){
  $tmpArr=file($depthFile); $smpArr=explode(":",trim($tmpArr[0])); $basecount1=$smpArr[1];
  $tmpArr=file($depthFile); $smpArr=explode(":",trim($tmpArr[1])); $basecount2=$smpArr[1];
  $depth=(($basecount1+$basecount2)/5013480); $depth=round($depth,2);
  list($totalSequences01, $sequenceLength01, $GC01, $pbsq1, $psqs1, $pbsc1, $psgc1, $kmc1, $sdl1)=parsefastqc($fastqFileQC1);
  list($totalSequences02, $sequenceLength02, $GC02, $pbsq2, $psqs2, $pbsc2, $psgc2, $kmc2, $sdl2)=parsefastqc($fastqFileQC2);	
  $converage=($totalSequences01+$totalSequences02)*151/5000000; $converage=round($converage,2);
  $summaryRecord1=trim(summaryRecord($summary1,1));
  $summaryRecord2=trim(summaryRecord($summary2,2));
  
  
  $record="";
  $record="totalSeq01:".$totalSequences01."\nseqLen01:".$sequenceLength01."\nGC01:".$GC01."\n";
  $record.="totalSeq02:".$totalSequences02."\nseqLen02:".$sequenceLength02."\nGC02:".$GC02."\n";
  $record.="converage:".$converage."\ndepth:".$depth."\n".$summaryRecord1."\n".$summaryRecord2."\n";
  $fp = fopen($recordFile, "w"); fwrite($fp, $record); fclose($fp);
  echo $recordFile."\n";
 }
} 
function summaryRecord($summary,$id){  
 $record="";
 $tmpArr=file($summary); 
 for($i=0;$i<count($tmpArr);$i++){
  $tmp=trim($tmpArr[$i]);
  if ($tmp!=""){  
   $smpArr=explode("\t",$tmp); 
   $smp0=trim($smpArr[0]);	
   $smp1=trim($smpArr[1]).$id;
   $record.=$smp1.":".$smp0."\n";  
  }   
 }
 return $record;
}
function parsefastqc($fastQCFile){
 $cmd="cat ".$fastQCFile." |grep '^Total Sequences'"; $result=explode("\t",shell_exec($cmd));  $ts=trim($result[1]);
 $cmd="cat ".$fastQCFile." |grep '^Sequence length'"; $result=explode("\t",shell_exec($cmd));  $sl=trim($result[1]);
 $cmd="cat ".$fastQCFile." |grep '^%GC'"; $result=explode("\t",shell_exec($cmd));   $gcp=trim($result[1]);

 $cmd="cat ".$fastQCFile." |grep '^>>Per base sequence quality'"; $result=explode("\t",shell_exec($cmd)); $tmp=trim($result[1]);
 if($tmp=="pass"){ $pbsq="tick"; }elseif($tmp=="warn"){ $pbsq="warning"; }elseif($tmp=="fail"){ $pbsq="error"; }

 $cmd="cat ".$fastQCFile." |grep '^>>Per sequence quality scores'"; $result=explode("\t",shell_exec($cmd));  $tmp=trim($result[1]);
 if($tmp=="pass"){ $psqs="tick"; }elseif($tmp=="warn"){ $psqs="warning"; }elseif($tmp=="fail"){ $psqs="error"; }

 $cmd="cat ".$fastQCFile." |grep '^>>Per base sequence content'"; $result=explode("\t",shell_exec($cmd));  $tmp=trim($result[1]);
 if($tmp=="pass"){ $pbsc="tick"; }elseif($tmp=="warn"){ $pbsc="warning"; }elseif($tmp=="fail"){ $pbsc="error"; }

 $cmd="cat ".$fastQCFile." |grep '^>>Per sequence GC content'"; $result=explode("\t",shell_exec($cmd));  $tmp=trim($result[1]);
 if($tmp=="pass"){ $psgc="tick"; }elseif($tmp=="warn"){ $psgc="warning"; }elseif($tmp=="fail"){ $psgc="error"; }

 $cmd="cat ".$fastQCFile." |grep '^>>Kmer Content'"; $r=shell_exec($cmd);
 if ($r!=""){ 
  $result=explode("\t",shell_exec($cmd));   $tmp=trim($result[1]);
  if($tmp=="pass"){ $kmc="tick"; }elseif($tmp=="warn"){ $kmc="warning"; }elseif($tmp=="fail"){ $kmc="error"; }
 }else{
  //echo $cmd."\n";
  $kmc="";
 }
 
 $cmd="cat ".$fastQCFile." |grep '^>>Sequence Duplication Levels'"; $result=explode("\t",shell_exec($cmd));  $tmp=trim($result[1]);
 if($tmp=="pass"){ $sdl="tick"; }elseif($tmp=="warn"){ $sdl="warning"; }elseif($tmp=="fail"){ $sdl="error"; }

 return array($ts, $sl, $gcp, $pbsq, $psqs, $pbsc, $psgc, $kmc, $sdl);	
}
 

