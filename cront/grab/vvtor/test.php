<?php
/**
*/

$uinfo = parse_url('http://i2.tietuku.com/cc0875dc8b2133f1s.jpg');
var_dump($uinfo);exit;

$APPPATH = dirname(__FILE__).'/';
include_once($APPPATH.'../db.class.php');
include_once($APPPATH.'config.php');

$pattern = '/vvtor/grab.php';
require_once $APPPATH.'singleProcess.php';


$con_data = array('url' => 'http://img.hacktea8.com/ttkapi/uploadurl?seq='
, 'imgurl'=>'','filename'=>'','site'=>'btv','album'=>'');

$mimg = array('http://6.blog.xuite.net/6/f/1/9/235832018/blog_3093938/txt/64483615/1.jpg');

foreach($mimg as $img){
 if('IMG_API_URL=' == substr($img,0,12)){
   continue;
 }
  $img_url = $img;
 if('http://' != substr($img,0,7)){
  $img_url = $_root.$img;
 }
 //echo "== $val[thum] ==\n";
 $con_data['imgurl'] = $img_url;
 $con_data['filename'] = basename($img_url);
//var_dump($con_data);exit;
 $covers = getHtml($con_data);
var_dump($covers);exit;
 //去除字符串前3个字节
 $covers = substr($covers,3);
 if(false == stripos($covers,'.')){
  die("\nid: $val[id] down contents images error!\n");
 }
 //echo $covers,"\n";
 $img_str = 'IMG_API_URL='.$covers;
 $info['intro'] = str_replace($img,$img_str,$info['intro']);
}


function getnocoverlist($limit = 20){
    global $db;
    $sql=sprintf('SELECT `id`,`sitetype`,`thum`,`ourl` FROM %s WHERE `iscover`=0 LIMIT %d',$db->getTable('emule_article'),$limit);
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
function seterrcoverByid($cover = 4,$id = 0){
    if(!$id){
       return false;
    }
    global $db;
    $sql = sprintf('UPDATE %s SET `iscover`=%d WHERE `id`=%d LIMIT 1',$db->getTable('emule_article'),$cover,$id);
    $db->query($sql);
}
function setcoverByid($cover = '',$id = 0){
    $pos = stripos($cover,'.');
    if(!$id || !$pos){
       return false;
    }
    global $db;
    $sql = sprintf('UPDATE %s SET `cover`=\'%s\',`iscover`=1,flag=1 WHERE `id`=%d LIMIT 1',$db->getTable('emule_article'),mysql_real_escape_string($cover),$id);
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
  curl_setopt($curl, CURLOPT_TIMEOUT,300);
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
