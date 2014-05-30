#!/usr/local/php/bin/php -q
<?php

$root=dirname(__FILE__).'/';
define('BASEPATH', $root.'../../');
//echo $root;exit;
require_once($root.'../grab/db.class.php');
require_once(BASEPATH.'application/helpers/rewrite_helper.php');

class model{
  public $db;
  public function __construct(){
    $this->db = new DB_MYSQL();
  }
  public function getList($page = 1,$limit = 100){
    $start = $page * $limit;
    $sql = sprintf('SELECT `id`,`utime` FROM '.$this->db->getTable('emule_article').' WHERE `flag`=1 AND `onlinedate`<=%d LIMIT %d,%d',date('Ymd'),$start,$limit);
     return $this->db->result_array($sql);
  }
  public function addIndex($data = array()){
    $sql = sprintf("SELECT * FROM %s WHERE `type`=%d AND `index`=%d limit 1",$this->db->getTable('emule_sitemap'),$data['type'],$data['index']);
    $row = $this->db->row_array($sql);
    if($row){
      return $row['id'];
    }
    $sql = $this->db->insert_string($this->db->getTable('emule_sitemap'),$data);
    $this->db->query($sql); 
    return $this->db->insert_id();
  }
  public function getIndexList($type){
    $sql = sprintf('SELECT   `index` FROM '.$this->db->getTable('emule_sitemap').' WHERE `type`=%d ORDER BY `id` DESC',$type);
    return $this->db->result_array($sql);
  }
  public function getMaxIndex($type){
    $sql = sprintf('SELECT  `aid`, `index` FROM '.$this->db->getTable('emule_sitemap').' WHERE `type`=%d ORDER BY `id` DESC  LIMIT 1',$type);
    $row = $this->db->row_array($sql);
    $index = isset($row['index'])?$row['index']:0;
    if($index && $row['aid']){
      $index++;
    }
    return $index;
  }
  public function setIndex($data,$where){

    $sql = $this->db->update_string($this->db->getTable('emule_sitemap'),$data,$where);
    $this->db->query($sql);
    return 1;
  }
  public function getListNum($aid){
  }
}

$type = 1;
$base_url = 'http://btv.hacktea8.com/';
$count = 1;
$countLimit = 30000;
$model = new model();
$startIndex = $model->getMaxIndex($type);
$sitemap = '<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
$tmp = '';
/**/
for($p = $startIndex;;$p++){
 $list = $model->getList($p,$countLimit);
 $list = $list ? $list: array();
 foreach($list as $val){
   $tmp .= '<url><loc>'.$base_url.article_url($val['id']).'</loc><lastmod>'.date('Y-m-d',$val['utime']).'</lastmod><changefreq>weekly</changefreq><priority>1</priority></url>';
   $count++;
  }
   if($tmp){
      $tmp = $sitemap.$tmp.'</urlset>';
      $index_file = BASEPATH.'google_sitemap'.$p.'.xml';
      file_put_contents($index_file,$tmp);
      $model->addIndex(array('type'=>$type,'index'=>$p,'aid'=>0,'update'=>$val['utime']));
      $tmp = '';
     
sleep(5);
   }
   if(count($list) == $countLimit){
      $model->setIndex(array('aid'=>1),array('`index`'=>$p,'`type`'=>$type));
   }
   if(empty($list)){
     break;
   }
}
/**/

$sitemap_index = '<?xml version="1.0" encoding="UTF-8"?><sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

$indexList = $model->getIndexList($type);
foreach($indexList as $val){
  $sitemap_index .= '<sitemap><loc>'.$base_url.'google_sitemap'.$val['index'].'.xml</loc><lastmod>'.date('Y-m-d').'</lastmod></sitemap>';
}

$sitemap_index .= '</sitemapindex>';
file_put_contents(BASEPATH.'google_sitemap.xml',$sitemap_index);
echo "\n=== work success! ==\n";
?>
