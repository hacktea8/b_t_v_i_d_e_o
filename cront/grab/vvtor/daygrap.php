<?php

$APPPATH=dirname(__FILE__).'/';
$psize=10;
include_once($APPPATH.'../function.php');
include_once($APPPATH.'config.php');


/*============ Get Cate article =================*/

$res='excres.txt';

getsubcatelist($subcate);
//var_dump($subcate);exit;
foreach($subcate as $_cate){
   if(!$_cate['oname']){
      continue;
   }
   getSubCatearticle($_cate);
file_put_contents($res,"cate $_cate[id] 已抓取完毕!\r\n",FILE_APPEND);
sleep(5);
}

?>
