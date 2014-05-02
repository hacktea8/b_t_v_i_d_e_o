<?php

$root = dirname(__FILE__);
define('BASEPATH',1);
require_once $root.'/model.php';
require_once $root.'/config.php';
require_once $root.'/../../../application/libraries/Yunsearchapi.php';
require_once $root.'/../../../application/helpers/rewrite_helper.php';

$search = new Yunsearchapi();
$model = new Model();
$count = 200;

$cate = $model->getCate();

while($count){
   $lists = $model->getNoneSearchLimit(5);
   if(empty($lists)){
     break;
   }
//var_dump($lists);exit;
   $_itemsArr = array();
   $idarr = array();
   foreach($lists as $val){
      $itemArr['id'] = 'btv_article_'.$val['id'];
      $itemArr['cat'] = $cate[$val['cid']]['name'];
      $itemArr['title'] = $val['name'];
      $itemArr['group_id'] = intval($val['cid']);
      $itemArr['tag'] = str_replace('，',',',$val['keyword']);
      $itemArr['focus_count'] = intval($val['collectcount']);
      $itemArr['create_timestamp'] = $val['ptime'];
      $itemArr['update_timestamp'] = $val['utime'];
      $itemArr['body'] = trim(preg_replace('#\s+#is',' ',strip_tags($val['intro'])));
      $itemArr['body'] = mb_substr($itemArr['body'], 0, 256, 'utf-8');
//var_dump($val['intro']);
//var_dump($itemArr['body']);exit;
      $itemArr['thumbnail'] = $val['cover'];
      $itemArr['hit_num'] = intval($val['hits']);
      $itemArr['url'] = article_url($val['id']);
      //$_itemsArr[] = $itemArr;
      $idarr[] = $val['id'];
      $post_data = array('fields'=>$itemArr,'cmd'=>'ADD');
//      $post_data = $itemArr;
      $_itemsArr[] = $post_data;
   }
   //var_dump($_itemsArr);exit;
   $result = $search->addDoc($_itemsArr);
//print_r($result);echo "\n",exit;
   $model->setIsSearch($idarr);
   $count --;
}
  echo "执行完毕!\n";exit; 

