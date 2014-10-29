<?php

$root=dirname(__FILE__).'/';
define('BASEPATH',$root.'../../system/');
require_once($root.'../grab/db.class.php');
require_once($root.'../../application/libraries/rediscache.php');


$model = new model();

$redis = new Rediscache();
$keys = $redis->keys('user_topic_hits:*');

//var_dump($keys);exit;

foreach($keys as $k){
  $id = explode(':',$k);
  $id = array_pop($id);
  $hit = $redis->get($k);
//var_dump($hit);exit;
  $model->setTopicHitsLog($id, $hit);
  $redis->delete($k);
//  usleep(1000);
}

echo "\n===".count($keys)."=== Update Emule Topic Hit Log OK! ========\n";

class model{
  protected $db;

  function __construct(){
    $this->db = new DB_MYSQL();
  }
  function setTopicHitsLog($id, $hit = 1){
    if(!$id){
       return false;
    }
    $sql = sprintf('UPDATE %s SET `hits`=`hits`+%d WHERE `id`=%d LIMIT 1',$this->db->getTable('emule_article'), $hit, $id);
    $this->db->query($sql);
    return true;
  }
}
