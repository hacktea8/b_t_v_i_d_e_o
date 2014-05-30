<?php

$APPPATH=dirname(__FILE__).'/';
include_once($APPPATH.'../db.class.php');
include_once($APPPATH.'config.php');

$pattern = '/vvtor/init.php';
require_once $APPPATH.'singleProcess.php';

$db=new DB_MYSQL();

for($p=0;;$p++){
$list = getnocoverlist($p);
if(empty($list)){
echo "grab list empty!\n";
break;
}
foreach($list as $val){
echo "\nid:$val[id] Cover: $val[cover]\n";
  if(false != stripos($val['cover'],'.')){
     setcoverByid(1,$val['id']);
  }
//exit;
}
sleep(5);
}

function getnocoverlist($page = 0,$limit = 100){
    global $db;
    $p = $page*$limit;
    $sql=sprintf('SELECT `id`,`cover` FROM %s  LIMIT %d,%d',$db->getTable('emule_article'),$p,$limit);
    $res=$db->result_array($sql);
    return $res;
}
function getcontenttable($id){
  return sprintf("emule_article_content%d",$id%10);
}
function getvideobyid($id){
  global $db;
  $table = getcontenttable($id);
  $sql=sprintf('SELECT  `downurl`, `intro` FROM %s WHERE `id`=%d LIMIT 1',$db->getTable($table),$id);
  $row = $db->row_array($sql);
  return $row;
}
function setcontentdata($data,$id){
  global $db;
  $table = getcontenttable($id);
  $sql = $db->update_string($db->getTable($table),$data,array('id'=>$id));
  $db->query($sql);
  return true;
}
function setcoverByid($cover = '',$id = 0){
    if(!$id){
       return false;
    }
    global $db;
    $sql = sprintf('UPDATE %s SET `iscover`=%d,flag=1 WHERE `id`=%d LIMIT 1',$db->getTable('emule_article'),$cover,$id);
    $db->query($sql);
}
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
