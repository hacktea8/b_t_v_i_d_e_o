<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'webbase.php';
class Usrbase extends Webbase {
   
  public $seo_title = '首页'; 
  public $seo_keywords = '种子,快播种子,BT种子,BT下载,最新电影,高清电影,快播资源,百度影音种子,百度影音资源,torrent,电影资源';
  public $seo_description = '提供最新高清电影下载，拥有最全最高清的电影种子，快播(百度影音)种子资源你懂的，提供国内外最新BT种子下载服务，btv.hacktea8.com，专注于分享各类最新720(1080)P电影下载分享服务。';
  public $imguploadapiurl = 'http://img.hacktea8.com/imgapi/upload/?seq=';

  public function __construct(){
    parent::__construct();
    
    $this->load->helper('rewrite');
    $this->load->model('emulemodel');
    $hotTopic = $this->mem->get('hotTopic');
//var_dump($hotTopic);exit;
    if( empty($hotTopic)){
      $hotTopic = $this->emulemodel->getHotTopic();
      $this->_rewrite_article_url($hotTopic);
      $this->mem->set('hotTopic',$hotTopic,$this->expirettl['1h']);
    }
    $rootCate = $this->mem->get('rootCate');
    if( empty($rootCate)){
      $rootCate = $this->emulemodel->getCateByCid(0);
      $this->_rewrite_list_url($rootCate);
      $this->mem->set('rootCate',$rootCate,$this->expirettl['30m']);
    } 
    $this->assign(array(
    'seo_keywords'=>$this->seo_keywords,'seo_description'=>$this->seo_description,'seo_title'=>$this->seo_title
    ,'showimgapi'=>$this->showimgapi,'error_img'=>$this->showimgapi.'3958009_0000671092.jpg','hotTopic'=>$hotTopic,'rootCate'=>$rootCate,
    'cpid'=>0,'cid'=>0
    ,'editeUrl' => '/edite/index/emuleTopicAdd'
    ));
    $this->_get_postion();
//var_dump($this->viewData);exit;
  }
  protected function _get_postion($postion = array()){
    $this->assign(array('postion'=>$postion));
  }
}
