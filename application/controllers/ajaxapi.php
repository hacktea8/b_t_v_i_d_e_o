<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
ini_set('display_errors',1);
error_reporting(E_ALL);
require_once 'webbase.php';
class Ajaxapi extends Webbase {


 public function __construct(){
  parent::__construct();
  $this->load->model('emulemodel');
 }
  
 public function getcate($cid = 0, $pid = 0){
  $return = $this->_getCateListById($id, $pid);
  die(json_encode($return));
 }
 public function article_pv($aid){
  $ip = $this->input->ip_address();
  $key = sprintf('user_hits_check:%s:%d',$ip,$aid);
//var_dump($this->redis->exists($key));exit;
  if( $this->redis->exists($key)){
   return 0;
  }
  $this->redis->set($key, 1,$this->expirettl['1d']);
  $key = sprintf('user_topic_hits:%d',$aid);
  $this->redis->incr($key);
  
 }
}
