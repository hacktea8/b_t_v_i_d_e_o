<?php

$APPPATH = dirname(__FILE__).'/';
include_once($APPPATH.'../config.php');
include_once($APPPATH.'/function.php');
include_once($APPPATH.'../post_fun.php');
include_once($APPPATH.'config.php');


/*============ Get Cate article =================*/

$lastgrab = basename(__FILE__);
$path = $APPPATH.'config/';

$abort = 0;
$lastK = 0;
foreach($cate_config as $k => $_cate){
  echo "\n==== Current Index $k Cid $_cate[cid] ======\n";
  //0 isok
  if($k > $lastK){
    break;
  }
  if($k != $lastK){
    continue;
  }
  $cid = $_cate['cid'];
  $lastgrab = $path.$cid.'_'.$lastgrab;
  getinfolist($_cate);
  sleep(10);
}



?>
