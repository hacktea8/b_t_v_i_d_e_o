<?php

$APPPATH=dirname(__FILE__).'/';
include_once($APPPATH.'../db.class.php');
include_once($APPPATH.'config.php');

$pattern = '/vvtor/grab.php';
require_once $APPPATH.'singleProcess.php';

$db=new DB_MYSQL();

$data = array('url' => 'http://img.hacktea8.com/fileapi/uploadurl?seq=', 'imgurl'=>'');
$file_data = array('url' => 'http://img.hacktea8.com/fileapi/uploadurl?seq=', 'imgurl'=>'','filename'=>'');
$task = 600;
while($task){
$list = getnocoverlist();
if(empty($list)){
sleep(600);
break;
}
foreach($list as $val){
if('http://' != substr($val['thum'],0,7)){
  $val['thum'] = $_root.$val['thum'];
}
echo "== $val[thum] ==\n";
$data['imgurl'] = $val['thum'];
$cover = getHtml($data);
//去除字符串前3个字节
$cover = substr($cover,3);
echo $cover,"\n";
//exit;
//echo strlen($cover);exit;
if( in_array($cover,array('44','404'))){
  die('Token 失效!');
}
if(0 == $cover){
  echo "$val[id] cover is down!\n";
  setcoverByid(4,$val['id']);
  continue;
}
$info = getvideobyid($val['id']);
$header = get_headers($_root.$info['downurl'],1);
$filename = substr($header['Content-Disposition'],strlen("attachment; filename*=UTF-8''"));
//var_dump($header);exit;
//$filename = str_replace('.html','.zip',$val['ourl']);
$file_data['filename'] = trim($filename);
$file_data['imgurl'] = $_root.$info['downurl'];
//var_dump($file_data);exit;
$downurl = getHtml($file_data);
//去除字符串前3个字节
$downurl = substr($downurl,3);
if(strlen($downurl)<10){
sleep(600);exit;
}
//echo $downurl,"\n";

preg_match_all('#<img .*src="([^"]+)"#Uis',$info['intro'],$match);
foreach($match[1] as $img){
 if('IMG_API_URL=' == substr($img,0,12)){
   continue;
 }
  $img_url = $img;
 if('http://' != substr($img,0,7)){
  $img_url = $_root.$img;
 }
//echo "== $val[thum] ==\n";
$data['imgurl'] = $img_url;
$covers = getHtml($data);
//去除字符串前3个字节
$covers = substr($covers,3);
//echo $covers,"\n";
$img_str = 'IMG_API_URL='.$covers;
$info['intro'] = str_replace($img,$img_str,$info['intro']);
}
$info['intro'] = droptags($info['intro']);
$set_data = array('download'=>$downurl,'intro'=>$info['intro']);

//var_dump($set_data);exit;
setcontentdata($set_data,$val['id']);
//
setcoverByid($cover,$val['id']);
//echo $val['id'],"\n",exit;
sleep(15);
}
//var_dump($list);exit;
$task --;
//2min
sleep(18);
}
file_put_contents('imgres.txt',$val['id']);


function getnocoverlist($limit = 20){
    global $db;
    $sql=sprintf('SELECT `id`,`sitetype`,`thum`,`ourl` FROM %s WHERE `cover`=\'0\' LIMIT %d',$db->getTable('emule_article'),$limit);
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
    $sql = sprintf('UPDATE %s SET `cover`=\'%s\' WHERE `id`=%d LIMIT 1',$db->getTable('emule_article'),mysql_real_escape_string($cover),$id);
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
