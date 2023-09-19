<?php
function backhome($message){
 if (!isset($message) || $message=="") $message="系統錯誤, 請回上一頁!";
 die('<input type="button"  style="width:300px;height:40px;font-size:20px;" value="'.$message.'" onclick="history.back()">'); 
}
function format_input($data){
     $data = trim($data);
     $data = stripslashes($data);
     $data = htmlspecialchars($data);
     return $data;
}
function check_other($data){
 $error=0;
 if (empty($data)){
  $error=1;
 }else{ 
  $data=format_input($data);
  if (!preg_match("/^[a-zA-Z0-9 \/.:_,-=]*$/",$data)){
   $error=1;
  }
 }
 return $error;
}
