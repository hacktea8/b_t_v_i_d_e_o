<?php
require_once $root.'/../db.class.php';
class Model{
  protected $db = '';

  public function __construct(){
     $this->db = new DB_MYSQL(); 
  }
  public function getCate(){
    $sql = sprintf('SELECT * FROM %s',$this->db->getTable('emule_cate'));
    $list = $this->db->result_array($sql);
    $return = array();
    foreach($list as &$v){
      $return[$v['id']] = $v;
    }
    return $return;
  }
  public function getNoneSearchLimit($limit = 30){
     $sql = sprintf('SELECT * FROM %s WHERE `onlinedate`<=%d AND `flag`=1 AND nonesearch = 0 LIMIT %d',$this->db->getTable('emule_article'),date('Ymd'), $limit);
     $list = $this->db->result_array($sql);
     if(empty($list)){
       return array();
     }
     foreach($list as $k => $val){
       $table = sprintf('emule_article_content%d',$val['id']%10);
       $sql = sprintf('SELECT keyword,intro FROM %s WHERE id = %d LIMIT 1',$this->db->getTable($table),$val['id']);
       $row = $this->db->row_array($sql);
       $list[$k]['intro'] = $row['intro'];
       $list[$k]['keyword'] = $row['keyword'];
     }
     return $list;
  }
  public function setIsSearch($ids = ''){
     if(!$ids){
        return false;
     }
     $limit = count($ids);
     $ids = implode(',',$ids);
     $sql = sprintf('UPDATE %s SET `nonesearch` = 1  WHERE `id` IN (%s) LIMIT %d',$this->db->getTable('emule_article'),$ids,$limit);
     $this->db->query($sql);
  }
}
