<?php

$APPPATH=dirname(__FILE__).'/';
$psize=10;
include_once($APPPATH.'../function.php');
include_once($APPPATH.'config.php');

/*=========== Update Cate Article Total =========*/
//getAllcate();
/*============ Get Cate article =================*/
$res='excres.txt';

getsubcatelist($subcate);
$i=0;
$num=2;
foreach($subcate as $_cate){
$i++;
//2,6,10,14,17,20,23,26 is ok
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
