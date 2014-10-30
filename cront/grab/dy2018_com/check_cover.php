<?php

$APPPATH=dirname(__FILE__).'/';
include_once($APPPATH.'../db.class.php');
include_once($APPPATH.'config.php');

$pattern = '/tutuzx/check_cover.php';
require_once $APPPATH.'singleProcess.php';

$db=new DB_MYSQL();

$fname = '3958009_0000013488.jpg';
$data = array();
$data['url'] = 'http://img.hacktea8.com/picapi/delfile/'.$fname;
$html = getHtml($data);

echo $html,"\n";exit;


function getHtml(&$data){
  $curl = curl_init();
  $url = $data['url'];
  unset($data['url']);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.3 (Windows; U; Windows NT 5.3; zh-TW; rv:1.9.3.25) Gecko/20110419 Firefox/3.7.12');
  // curl_setopt($curl, CURLOPT_PROXY ,"http://189.89.170.182:8080");
  curl_setopt($curl, CURLOPT_POST, count($data));
  curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
  curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true);
  curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
  curl_setopt($curl, CURLOPT_HEADER, 0);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $tmpInfo = curl_exec($curl);
  if(curl_errno($curl)){
    echo 'error',curl_error($curl),"\r\n";
    return false;
  }
  curl_close($curl);
  $data['url'] = $url;
  return $tmpInfo;
}
function droptags($html){
global $_root;
$str_replace = array(
array('from'=>'</a>','to'=>'')
,array('from'=>'<img </td>','to'=>'<img ')
,array('from'=>substr($_root,0,-1),'to'=>'http://btv.hacktea8.com/')
);
$preg_replace = array(
array('from'=>'#<a[^>]+>#Uis','to'=>'')
);
foreach($str_replace as $v){
  $html = str_replace($v['from'],$v['to'],$html);
}
foreach($preg_replace as $v){
  $html = preg_replace($v['from'],$v['to'],$html);
}
return $html;
}
