<?php
class grabapiModel extends CI_Model{
  public $db;
  public $serverMod = array('qvod'=>1,'百度影音'=>2,'xfplay'=>3,'bdhd'=>2,'xigua'=>5,'jjhd'=>6);
  public function __construct(){
     parent::__construct();
     $this->db  = $this->load->database('default', TRUE);

  }
  public function get_content_table($id){
    return sprintf('emule_article_content%d',$id%10);
  }
  public function checkArticleByOname($name){
     if(!$name){
       return array();
     }
     $sql = sprintf("SELECT `id` FROM `%s` WHERE  `name`='%s' LIMIT 1",$this->db->dbprefix('emule_article'),mysql_real_escape_string($name));
    $row = $this->db->query($sql)->row_array();
    return $row['id']?$row['id']:0;
  }
   public function addArticle($data){
    if(empty($data['name'])){
      return 0;
    }
    $head = $this->copy_array($data,array('name','cid','thum','ourl','ptime','utime'));
    $contents = $this->copy_array($data,array('intro','downurl'));
    $sql = $this->db->insert_string($this->db->dbprefix('emule_article'),$head);
    $this->db->query($sql);
    $id = $this->db->insert_id();
    if(!$id){
       return false;
    }
    $contents['id'] = $id;
    $table = $this->get_content_table($id);
    $sql = $this->db->insert_string($this->db->dbprefix($table),$contents);
    $this->db->query($sql);
    return $id;
  }
   public function copy_array($data,$key){
    $return = array();
    foreach($key as $k){
      if(isset($data[$k])){
        $return[$k] = $data[$k];
      }
    }
    return $return;
  }

}
