<?php
$abort = 0;
$lastK = 1;

$APPPATH = dirname(__FILE__).'/';
$psize = 10;
include_once($APPPATH.'../function.php');
include_once($APPPATH.'config.php');


/*============ Get Cate article =================*/

$res='excres.txt';
if(0){
 getsubcatelist($subcate);
 $json = json_encode($subcate);
 file_put_contents($APPPATH.'subcate.json', $json);
 var_dump($subcate);exit;
}else{
 $json = file_get_contents($APPPATH.'subcate.json');
 $subcate = json_decode($json, 1);
}

foreach($subcate as $k => $_cate){
 if(!$_cate['oname']){
  continue;
 }
 echo "\n=== Current Index $k Cid $_cate[id] Url $_cate[oname] =====\n";
 if($abort && $k<$lastK){
  continue;
 }
 getSubCatearticle($_cate);
 file_put_contents($res,"cate $_cate[id] 已抓取完毕!\r\n",FILE_APPEND);
 sleep(5);
}

?>
