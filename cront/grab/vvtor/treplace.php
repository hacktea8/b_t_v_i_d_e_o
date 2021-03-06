<?php

$root = dirname(__FILE__).'/';

require_once $root.'../model.php';
require_once $root.'../db.class.php';

$model = new Model();

$strreplace = array(
array('from'=>'www.ed2kers.com','to'=>'www.emubt.com')
,array('from'=>'\"','to'=>'"')
,array('from'=>'\r\n','to'=>'')
,array('from'=>'\n','to'=>'')
);

$pregreplace=array(
array('from'=>'#<br>引用.+</td>#Us','to'=>'</td>')
,array('from'=>'#<script [^>]+>.*</script>#','to'=>'')
);

$info = array();

for($page = 400; $page<500; $page++){
  $list = $model->getArticleList($page, $limit = 500);
  if(empty($list)){
    break;
  }

  foreach($list as $val){
    $data = $model->getArticleByid($val['id']);
    foreach($pregreplace as $replace){
      $data['downurl'] = preg_replace($replace['from'],$replace['to'],$data['downurl']);
      $data['intro'] = preg_replace($replace['from'],$replace['to'],$data['intro']);
    }
    $info['downurl'] = $data['downurl'];
    $info['intro'] = $data['intro'];
     $info['id'] = $val['id'];
//var_dump($info);
    $model->update_article_contents($info );
//    exit;
  }

  sleep(1);
}

echo "\n== 执行完毕! ===\n";
