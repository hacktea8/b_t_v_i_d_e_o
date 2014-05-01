<?php

$APPPATH=dirname(__FILE__).'/';
$psize=10;
include_once($APPPATH.'../function.php');
include_once($APPPATH.'config.php');


/*============ Get Cate article =================*/
$res='excres.txt';

getsubcatelist($subcate);
$i=0;
$num=15;
foreach($subcate as $_cate){
$i++;
//3,7,11,15,16,19,22,25, isok
if($i>$num){
  break;
}
if($i!=$num){
continue;
}
   getSubCatearticle($_cate);
file_put_contents($res,"num $num 已抓取完毕!\r\n",FILE_APPEND);
sleep(10);
}



?>
