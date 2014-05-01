<?php

$APPPATH=dirname(__FILE__).'/';
$psize=10;
include_once($APPPATH.'../function.php');
include_once($APPPATH.'config.php');


/*============ Get Cate article =================*/
$res='excres.txt';

getsubcatelist($subcate);
$i=0;
$num=12;
//var_dump($subcate);exit;
foreach($subcate as $_cate){
$i++;
//4,8,12,43,47,51,55,59,63,67,71,75,79,83 isok
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
