<?php

$APPPATH=dirname(__FILE__).'/';
include_once($APPPATH.'../config.php');
include_once($APPPATH.'../function.php');
include_once($APPPATH.'/function.php');
include_once($APPPATH.'config.php');


/*============ Get Cate article =================*/

$lastgrab = basename(__FILE__);
$path = $APPPATH.'config/';

$i = 0;
$num = 19;
foreach($cate_config as $_cate){
  $i++;
  //3,7,11,15,19 isok
  if($i > $num){
    break;
  }
  if($i != $num){
    continue;
  }
  $lastgrab = $path.$_cate['cid'].'_'.$lastgrab;
  getSubCatearticle($_cate);
  sleep(10);
}



?>
