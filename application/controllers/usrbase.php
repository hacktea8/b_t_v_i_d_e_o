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
    ,'showimgapi'=>$this->showimgapi,'error_img'=>$this->showimgapi.'3958009_0000671092.jpg'
    ,'hotTopic'=>$hotTopic,'rootCate'=>$rootCate,'click_ad_link'=>''
    ,'cpid'=>0,'cid'=>0
    ,'editeUrl' => '/edite/index/emuleTopicAdd'
    ));
    $this->_get_postion();
    $this->_get_ads_link();
//var_dump($this->viewData);exit;
  }
  protected function _get_postion($postion = array()){
    $this->assign(array('postion'=>$postion));
  }
  protected function _get_ads_link(){
   $click_ad_link = '';
   if( 0&& !isset($_COOKIE['ahref_click']) && in_array($this->_a,array('lists','topic'))){
    $host = $_SERVER['HTTP_HOST'];
    $url = sprintf("http://c.3808010.com/code1/cpc_0_1_1.asp?w=960&h=130&s_h=1&s_l=6&c1=CCCCCC&c2=c90000&c3=ffffff&pid=264232&u=204756&top=%s&err=&ref=%s/",$this->viewData['current_url'],$host);
    $referer = 'http://'.$this->viewData['current_url'];
    $default_opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.66 Safari/537.36\r\n".
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n".
    "Accept-Language: zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3\r\n".
    "Cache-Control: max-age=0\r\n".
    $referer

  )
);
    $default = stream_context_get_default($default_opts);
    $context = stream_context_create($default_opts);
    $html =  file_get_contents($url, false, $context);
    preg_match_all('#<a .*href="([^"]+)"#Uis',$html,$match);
    $links = $match[1];
    $k = array_rand($links);
    $click_ad_link = $links[$k];
   }
    $this->assign(array('click_ad_link'=>$click_ad_link));
    //echo $links[$k];exit;
  }

}
