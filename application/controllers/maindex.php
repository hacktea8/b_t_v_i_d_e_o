<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'usrbase.php';
class Maindex extends Usrbase {

  public function __construct(){
    parent::__construct();
//var_dump($this->viewData);exit;
  }
  public function index()
  {
    $view = BASEPATH.'../';
    if(!is_writeable($view)){
       die($view.' is not write!');
    }
    $view .= 'index.html';
    $lock = $view . '.lock';
    if( !file_exists($view) || (time() - filemtime($view)) > 1*3600 ){
      if(!file_exists($lock)){
        $emuleIndex = $this->emulemodel->getEmuleIndexData();
        $this->assign(array('_a'=>'index','emuleIndex'=>$emuleIndex));
        $this->view('index_index');
        $output = $this->output->get_output();
        file_put_contents($lock, '');
        file_put_contents($view, $output);
        @unlink($lock);
        @chmod($view, 0777);
        echo $output;
        return true;
      }
    }
    exit();
  }
  public function fav($page = 1){
    if( !isset($this->userInfo['uid']) || !$this->userInfo['uid']){
      redirect('/');
    }
    $page = intval($page);
    $limit = 30;
    $total = $this->emulemodel->getUserCollectTotal($this->userInfo['uid']);
    $endP = ceil($total/$limit);
    if($total && $endP >= $page){
      $lists = $this->emulemodel->getUserCollectList($this->userInfo['uid'],$order = 'new',$page,$limit);
    }
    $this->load->library('pagination');
    $config['base_url'] = sprintf('/index/collect/');
    $config['total_rows'] = $total;
    $config['per_page'] = 25;
    $config['first_link'] = '第一页';
    $config['next_link'] = '下一页';
    $config['prev_link'] = '上一页';
    $config['last_link'] = '最后一页';
    $config['cur_tag_open'] = '<span class="current">';
    $config['cur_tag_close'] = '</span>';
    $config['suffix'] = '.html';
    $config['use_page_numbers'] = TRUE;
    $config['num_links'] = 5;
    $config['cur_page'] = $page;

    $this->pagination->initialize($config);
    $page_string = $this->pagination->create_links();
    $this->assign(array(
    'page_string'=>$page_string,'infolist'=>$lists));
    $this->view('index_collect');
  }
  public function addCollect($aid){
    $data = array('status'=>0);
    if($this->userInfo['uid']){
      $f = $this->emulemodel->addUserCollect($this->userInfo['uid'],$aid);
      $data['status'] = $f;
    }
    die(json_encode($data));
  }
  public function lists($cid,$order = 0,$page = 1){
    $page = intval($page);
    $cid = intval($cid);
    if($cid <1){
      header('HTTP/1.1 301 Moved Permanently');
      header('Location: /');
      exit;
    }
    $order = intval($order);
    $page = $page > 0 ? $page: 1;
    if($page < 11){
       $data = array();
       $data['emulelist'] = $this->mem->get('emulelist'.$cid.'-'.$page.$order);
//echo '<pre>';var_dump($data);exit;
       if( empty($data['emulelist'])){
//die($this->expirettl['12h'].'empty');
         $data = $this->emulemodel->getArticleListByCid($cid,$order,$page);
         $this->mem->set('emulelist'.$cid.'-'.$page.$order,$data['emulelist'],$this->expirettl['1h']);
       }
    }else{
       $data = $this->emulemodel->getArticleListByCid($cid,$order,$page);
    }
    $data['atotal'] = $this->viewData['rootCate'][$cid]['atotal'];
    $this->_rewrite_article_url($data['emulelist']);
    $data['emulelist'] = is_array($data['emulelist']) ? $data['emulelist']: array();
    $this->load->library('pagination');
    $config['base_url'] = sprintf('/maindex/lists/%d/%d/',$cid,$order);
    $config['total_rows'] = $data['atotal'];
    $config['per_page'] = 25; 
    $config['first_link'] = '第一页'; 
    $config['next_link'] = '下一页';
    $config['prev_link'] = '上一页';
    $config['last_link'] = '最后一页';
    $config['cur_tag_open'] = '<span class="current">';
    $config['cur_tag_close'] = '</span>';
    $config['suffix'] = '.html';
    $config['use_page_numbers'] = TRUE;
    $config['num_links'] = 4;
    $config['cur_page'] = $page;
    
    $this->pagination->initialize($config); 
    $page_string = $this->pagination->create_links();
// seo setting
    $title = $kw = '';
    $kw = $this->viewData['rootCate'][$cid]['name'];
    $title = $kw;
    $keywords = $kw.$this->seo_keywords;
    $postion = array($this->viewData['rootCate'][$cid]);
    $this->assign(array('seo_title'=>$title,'seo_keywords'=>$keywords,'infolist'=>$data['emulelist']
    ,'postion'=>$postion,'page_string'=>$page_string,'subcatelist'=>$data['subcatelist'],'cid'=>$cid));
    $this->view('index_lists');
  }
  public function topic($aid){
    $aid = intval($aid);
    if($aid <1){
      header('HTTP/1.1 301 Moved Permanently');
      header('Location: /');
      exit;
    }
    $data = $this->emulemodel->getEmuleTopicByAid($aid,$this->userInfo['uid'], $this->userInfo['isadmin'],0);
    $data['info']['ptime']=date('Y:m:d', $data['info']['ptime']);
    $data['info']['utime'] = date('Y/m/d', $data['info']['utime']);
    $this->_rewrite_article_url($data['info']);
    $data['info'] = $data['info'][0];
    $data['info']['fav'] = 0;
    $cid = $data['info']['cid'] ? $data['info']['cid'] : 0;
    $cpid = isset($data['postion'][0]['id'])?$data['postion'][0]['id']:0;
    $topic_relate_key = 'topic_relate_'.$cid;
    $data['info']['relatdata'] = $this->mem->get($topic_relate_key);
    if(empty($data['info']['relatdata'])){
      $data['info']['relatdata'] = $this->emulemodel->getArticleListByCid($data['info']['cid'],1,2,16);
      $data['info']['relatdata'] = $data['info']['relatdata']["emulelist"];
      $this->mem->set($topic_relate_key,$data['info']['relatdata'],$this->expirettl['1h']);
    }
// seo setting
    $kw = '';
    $data['postion'] = array($this->viewData['rootCate'][$cid],array('url'=>'javascript:void(0);','name'=>$data['info']['name']));
    foreach($data['postion'] as $row){
       $kw .= $row['name'].',';
    }
    $title = $data['info']['name'];
    $default_seo = sprintf('%s下载,%s在线,%s点播,%s种子,%s快播,%s西瓜,%s吉吉,%s网盘下载,%s共享,%s分享,%s百度影音',$title,$title,$title,$title,$title,$title,$title,$title,$title,$title,$title);
    $keywords = $data['info']['name'].','.$kw.$default_seo;
    $title .= '-'.$this->viewData['rootCate'][$cid]['name'];
    $data['info']['intro'] = str_replace(array('<img </td>','IMG_API_URL='),array('<img ',$this->showimgapi),$data['info']['intro']);
    $seo_description = strip_tags($data['info']['intro']);
    $seo_description = preg_replace('#\s+#Uis','',$seo_description);
    $seo_description = mb_substr($seo_description,0,250);
    // not VIP Admin check verify
    $verifycode = '';
/*
    $emu_aid = isset($_COOKIE['hk8_verify_topic_dw'])?strcode($_COOKIE['hk8_verify_topic_dw'],false):'';
    $emu_aid = explode("\t",$emu_aid);
    $emu_aid = $emu_aid[0];
    if( 0 && !($emu_aid == $data['info']['id'] || $this->userInfo['isvip'] || $this->userInfo['isadmin'])){
       $data['info']['downurl'] = '';
       $data['info']['vipdwurl'] = '';
       $this->load->library('verify');
       $verifycode = $this->verify->show();
    }
*/
    $isCollect = $this->emulemodel->getUserIscollect($this->userInfo['uid'],$data['info']['id']);
    $this->assign(array('isCollect'=>$isCollect,'verifycode'=>$verifycode
    ,'seo_title'=>$title,'seo_keywords'=>$keywords,'cid'=>$cid,'cpid'=>$cpid
    ,'info'=>$data['info'],'postion'=>$data['postion'],'aid'=>$aid
    ,'seo_description'=>$seo_description
    )); 
//echo '<pre>';var_dump($data['info']);exit;
/*
    $ip = $this->input->ip_address();
    $key = sprintf('hitslog:%s:%d',$ip,$aid);
//var_dump($this->redis->exists($key));exit;
    if(!$this->redis->exists($key)){
       $this->redis->set($key, 1, $this->expirettl['6h']);
    }
*/
    $this->view('index_topic');
    if(  $this->static_html){
      $cache_file = CACHEDIR.($aid%10).'/'.$aid.'.html';
      $cache_dir = dirname($cache_file);
      makedir($cache_dir,0777);
      $output = $this->output->get_output();
      file_put_contents($cache_file,$output);
      @chmod($cache_file,0777);

    }
  }
  public function tpl(){
    $this->load->view('index_tpl',$this->viewData);
  }
  public function search($q='',$order = 0,$page = 1){
    $q = $q ? $q:$this->input->get('q');
    $q = urldecode($q);
    $q = htmlentities($q);
    $page = intval($page);
    $page = $page - 1;
    $page = $page < 0 ? 0: $page;
    $list = array();
    $pageSize = 12;
    if($q){
      $this->load->library('yunsearchapi');
      $opt = array('query'=>$q,'start'=>$page*$pageSize,'hits'=>$pageSize);
      $this->yunsearchapi->search($list,$opt);
      $hotKeywords = $this->yunsearchapi->getTopQuery($num=8,$days=30);
      //var_dump($hotKeywords);exit;
      if('OK' == $hotKeywords['status']){
         $hotKeywords = $hotKeywords['result']['items']['emu_hacktea8'];
      }
    }
/*
echo '<pre>';
var_dump($q);
var_dump($hotKeywords);
var_dump($list);exit;
/**/
    $page++;
    $hot_search = array();
    $recommen_topic = array();
    $recommen_topic[1] = array();
    $recommen_topic[2] = array();
    $hot_topic = array();
    $hot_topic['hit'] = array();
    $hot_topic['focus'] = array();
    $this->load->library('pagination');
    $config['base_url'] = sprintf('/maindex/search/%s/%d/',urlencode($q),$order);
    $config['total_rows'] = $list['result']['viewtotal'];
    $config['per_page'] = $pageSize;
    $config['first_link'] = '第一页';
    $config['next_link'] = '下一页';
    $config['prev_link'] = '上一页';
    $config['last_link'] = '最后一页';
    $config['cur_tag_open'] = '<span class="current">';
    $config['cur_tag_close'] = '</span>';
    $config['suffix'] = '.shtml';
    $config['use_page_numbers'] = TRUE;
    $config['num_links'] = 4;
    $config['cur_page'] = $page;
    $this->pagination->initialize($config);
    $page_string = $this->pagination->create_links();
    $seo_title = sprintf('正在搜索%s第%d页',$q,$page);
    $this->assign(array('searchlist'=>$list['result'],'kw'=>$q,'q'=>$q
    ,'page_string'=>$page_string,'hot_search'=>$hot_search
    ,'recommen_topic'=>$recommen_topic,'hot_topic'=>$hot_topic
    ,'seo_title'=>$seo_title
    )); 
    $this->load->view('index_search',$this->viewData);
  }
  public function show404($goto = ''){
    $goto = '/';
    $this->assign(array('goto'=>$goto,'seo_title' =>'找不到您需要的页面..现在为您返回首页..'));
    $this->view('index_show404');
  }
  public function login(){
//var_dump($_SERVER);exit;
    $url = $this->viewData['login_url'].urlencode($_SERVER['HTTP_REFERER']);
//echo $url;exit;
    header('Location: '.$url);
    exit;
  }
  public function loginout(){
    $this->session->unset_userdata('user_logindata');
    setcookie('hk8_auth','',time()-3600,'/');
    $url = $_SERVER['HTTP_REFERER'];
//echo $url;exit;
    header('Location: '.$url);
    exit;
  }
  public function crontab(){
    $lock = BASEPATH.'/../crontab_loc.txt';
    if(file_exists($lock) && time()-filemtime($lock)<6*3600){
       return false;
    }
    $this->emulemodel->autoSetVideoOnline(3);
    $this->emulemodel->setCateVideoTotal();
    file_put_contents($lock,'');
    chmod($lock,0777);
    echo 1;exit;
  }
  public function isUserInfo(){
    $data = array('status'=>0);
    if( isset($this->userInfo['uid']) && $this->userInfo['uid']){
       $data['status'] = 1;
    }
    die(json_encode($data));
  }
}
