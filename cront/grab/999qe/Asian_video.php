<?php

define('ROOTPATH',dirname(__FILE__).'/');
require_once ROOTPATH.'config.php';
require_once ROOTPATH.'function.php';
require_once ROOTPATH.'../function.php';
require_once ROOTPATH.'../db.class.php';
require_once ROOTPATH.'../avmodel.php';

$m = new avmodel();
$post_data = array('cate'=>'亚洲视频','playmode'=>2,'flag'=>3,'thum'=>4,'intro'=>'');

for($page = 3;;$page ++){
 $next = $page == 1 ? '':sprintf('index_%d.html',$page);
 $url = sprintf('%ssplist1/%s',$_domain,$next);
//echo $url;exit;
 $html = getHtml($url);
 $html = iconv('GBK','UTF-8//IGNORE',$html);
 $html = striptags($html);
 preg_match_all('#<A href="(/splist1/\d+\.html)" title="[^"]+" target="_blank">([^<]+)</A>#Uis',$html,$match);
 $urlPool = $match[1];
//debug($urlPool);
 if(empty($urlPool)){
  echo "\n== Get $post_data[cate] Url List Empty! ==\n";break;
 }
 $titlePool = $match[2];
 foreach($titlePool as $key => $title){
  $title = trim($title);
  $check = $m->checkVideoByTitle($title);
  if($check){
   echo "\n Title: $title already exist!\n";continue;
  }
  $url = sprintf('%s%s',$_domain,$urlPool[$key]);
//echo $url;exit;
  $html = getHtml($url);
  $html = iconv('GBK','UTF-8//IGNORE',$html);
  $info = getAsianVideoInfo($html);
//  var_dump($info);exit;
  if(empty($info['playurl'])){
   write_log($url);
  // sleep(600);exit;
   $post_data['ourl'] = $url;
  }
  $post_data['title'] = $title;
  $post_data['atime'] = date('Ymd');
  $post_data['vlist'] = array(array('title'=>'第1集','playurl'=>$info['playurl'],'playnum'=>1,'mosaic'=>$info['mosaic']));
//var_dump($post_data);exit;
  $vid = $m->addVideoByData($post_data);
  echo "\n++ Add video $title Vid:$vid OK! \n";sleep(3);
//exit;
 }
}
?>
