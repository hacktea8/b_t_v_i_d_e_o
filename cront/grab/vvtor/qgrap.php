<?php

$APPPATH=dirname(__FILE__).'/';
$psize=10;
include_once($APPPATH.'../function.php');
include_once($APPPATH.'config.php');

/*=========== Update Cate Article Total =========*/
//getAllcate();exit;
/*============ Get Cate article =================*/

$res='excres.txt';

getsubcatelist($subcate);
$i=0;
$num=1;
foreach($subcate as $_cate){
$i++;
//1,5,9,13,15,18,21,,24,27 isok
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
