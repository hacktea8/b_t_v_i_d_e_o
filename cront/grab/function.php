<?php

/*
include_once($APPPATH.'../db.class.php');
include_once($APPPATH.'../model.php');

$model=new Model();
*/
include_once($APPPATH.'../post_fun.php');
include_once($APPPATH.'../config.php');

require_once $APPPATH.'../../../application/libraries/Tietuku.php';
require_once $APPPATH.'../../../application/libraries/gickimg.php';

$gickimg = new Gickimg();
$tietuku = new Tietuku();

function upload2Ttk($data = array()){
 global $ttkAlbum,$allowext,$ttkKey,$gickimg,$tietuku;
 $err = array('flag'=>-1,'msg'=>'未知错误');
 $imgurl = &$data['imgurl'];
 $referer = &$data['referer'];
 $curMin = date('i');
 $mk = $curMin%count($ttkKey);
 $curKey = $ttkKey['m'.$mk];
 $curAlbum = $ttkAlbum['m'.$mk];
 $ak = 'w'.date('w');
 $albumid = $curAlbum[$ak];
 $filename = basename($imgurl);
 $imginfo = array('title'=>$filename);
 $imginfo['ext'] = getextname($filename);
/**/
 $dwdata = array('url'=>$imgurl,'referer'=>$referer);
 $html = getHtml($dwdata['url']);
 $imgurl = ROOTPATH.'cache_images/ttk'.$imginfo['title'];
 @file_put_contents($imgurl, $html);
 @chmod($imgurl, 0777);
 if(!file_exists($imgurl)){
  @unlink($imgurl);
  $err['msg'] = 'file Down err '.$imgurl;
  return $err;
 }
 if( filesize($imgurl) <2000){
  @unlink($imgurl);
  $err['msg'] = 'file size too small';
  return $err;
 }
 if(in_array($imginfo['ext'], $allowext)){
  $dst_ext = '';
  if('.jpg' != $imginfo['ext']){
   $dst_ext = '.jpg';
  }
  $imgurl_w = ROOTPATH.'cache_images/ttkw'.$imginfo['title'].$dst_ext;
  $out_imgurl = $imgurl.$dst_ext;

  $cmd = "convert {$imgurl} {$out_imgurl}";
  //echo "$cmd\n";
  @exec($cmd);
  if( !file_exists($out_imgurl)){
   @unlink($imgurl);
   $err['msg'] = 'file Convert err '.$imgurl;
   return $err;
  }
  $water = ROOTPATH.'water/mhwater.png';
  $gickimg->waterMark($out_imgurl,$water,$imgurl_w);
  @chmod($imgurl_w, 0777);
  $upFile = &$imgurl_w;
  if( !file_exists($imgurl_w) || filesize($imgurl_w) <2000){
   $upFile = &$out_imgurl;
   @unlink($imgurl_w);
  }
 }else{
  $upFile = &$imgurl;
//exit;
 }
 $tietuku->init($curKey);
 $json = $tietuku->uploadFile($albumid,$upFile);
 @unlink($imgurl);
 @unlink($out_imgurl);
 @unlink($upFile);
 $iurl = @$json['linkurl'];
 if( !$iurl){
  $err['msg'] = 'save file failed';
//var_dump($json);exit;
  return $err;
 }
 $r = parse_info($iurl);
//var_dump($r);exit;
 if( !$r){
  $err['msg'] = 'parse url failed';
  return $err;
 }
 $r['flag'] = 1;
 return $r;
}
function parse_info($url){
  $uinfo = parse_url($url);
  $r = array();
  $host = @$uinfo['host'];
  $host = explode('.',$host);
  $r['host'] = @$host[0];
  $host = ltrim(@$uinfo['path'],'/');
  $host = explode('.',$host);
  $r['key'] = @$host[0];
  if( !$r['host'] || !$r['key']){
   return 0;
  }
  $r['url'] = $url;
  return $r;
}
function getextname($fname=''){
 if(!$fname){
  return false;
 }
 $extend =explode("." , $fname);
 $ext = strtolower(end($extend));
 return '.'.$ext;
}
/*
获取配对的标签的内容
*/
function getTagpair(&$str,&$string,$head,$end,$same){
  $str='';
  $start=stripos($string, $head);
  if($start===false){
    return false;
  }
//第一个包含head标签位置的剩下字符串
  $string=substr($string,$start);
//第一次结尾的end标签的位置
  $start=stripos($string, $end)+strlen($end);
  if($start===false){
    return false;
  }
  $str=substr($string,0,$start);
  $others=substr($string, $start+1);
//开始标签出现的次数
  $count_head=substr_count($str,$same);
//结束标签出息的次数
  $count_tail=substr_count($str, $end);
//echo $others,exit;
  while($count_head!=$count_tail &&$count_tail){
    //$start=stripos($others, $same);
    $length=stripos($others, $end)+strlen($end);
    $str.=substr($others, 0,$length);
    $others=substr($others, $length);
    $count_head=substr_count($str,$same);
    $count_tail=substr_count($str, $end);	
  }
}
/*
function getTagpair(&$str,&$string,$head,$end,$same){
  $str='';	
  $start=stripos($string, $same);
  $length=stripos($string, $end)+strlen($end)-$start;
  $str=substr($string, $start,$length);
  $others=substr($string, $length+$start);
  $count_head=substr_count($str,$same);
  $count_tail=substr_count($str, $end);
  while($count_head!=$count_tail){
    //$start=stripos($others, $same);
    $length=stripos($others, $end)+strlen($end);
    $str.=substr($others, 0,$length);
    $others=substr($others, $length);
    $count_head=substr_count($str,$same);
    $count_tail=substr_count($str, $end);	
  }
		
}
*/
function updateCateatotal(){
 global $model;
  return $model->updateCateatotal();
}

function getsubcatelist(&$subcate){
  global $model;
  $subcate=$model->getsubcatelist();
}

function getlastgrabinfo($mode=1,$config=array()){
  global $lastgrab,$cateid,$pageno;
  if($mode){
     if(!file_exists($lastgrab)){
        return false;
     }
     include($lastgrab);
     return true;
  }
  $text="<?php\r\n";
  $text.="\$cateid=$config[cateid];\r\n";
  $text.="\$pageno=$config[pageno];\r\n";
  
  file_put_contents($lastgrab,$text);
  return true;
}

function getCatearticle($pid=0){
  if(!$pid){
    return false;
  }
  global $model,$_root,$cid;
  //$flag=getlastgrabinfo();
  
  $cateList=$model->getCateInfoBypid($pid);
  foreach($cateList as $cate){
    if($cate['id']!=$cateid &&$flag){
         continue;
    }
    if($cate['id']==$cateid){
       $flag=false;
    }
    $cateurl=$_root.$cate['url'];
    $cid=$cate['id'];
    $status = getinfolist($cateurl);
    if(6 == $status){
       break;
    }
sleep(30);
  }
}

function getSubCatearticle($cate){
   global $model,$_root,$cid;
   $cateurl=$_root.$cate['oname'];
   $cid=$cate['id'];
   getinfolist($cateurl);
}

function getAllcate(){
  global $model,$_root;
  $html=getHtml($_root);
  preg_match_all('#<li id="menu-item-\d+" class="menu-item menu-item-type-taxonomy menu-item-object-category menu-item-\d+"><a href="http://www.vvtor.com/([^"]+)">([^<]+)</a></li>#Uis',$html,$match,PREG_SET_ORDER);
  $pcate=$match;
//var_dump($pcate);exit;
  foreach($pcate as $pc){
    $pid=$model->addCateByname(trim($pc[2]),0,trim($pc[1]));
    if(!$pid){
      continue;
    }
    echo "Parent Cate id $pid\r\n";
sleep(2);
  }
}
function getinfolist(&$cateurl){
  global $model,$psize,$pageno,$action,$_root,$cid;
  for($i=1;$i<3;$i++){
//通过 atotal计算i的值
    $ps = $i == 1?'':'/page/'.$i;
    $html=getHtml($cateurl.$ps);
    preg_match_all('#<a class="entry-thumb lazyload" href="http://www\.vvtor\.com/([^"]+)" title="([^"]+)" rel="bookmark" target="_blank"><img class="" src="http://www\.vvtor\.com/wordpress/wp-content/themes/NewsPro2/timthumb\.php\?src=([^&]+)&amp;h=130&amp;w=100&amp;zc=1" alt="([^"]+)" /></a>#Uis',$html,$matchs,PREG_SET_ORDER);
//echo '<pre>';var_dump($matchs);exit;
    if(empty($matchs)){
      echo ('Cate list Failed '.$cateurl."/第{$i}页\r\n");
      return 6;
    }
    foreach($matchs as $list){
      $oid=preg_replace('#\.html#','',$list[1]);
      $oname=trim($list[2]);
//先判断是否存在
      //$aid=$model->checkArticleByOname($oname);
      $aid = checkArticleByOname($oname);
      if($aid){
         echo "{$aid}已存在未更新!\r\n";
         continue;
        return 6;
      }
      $purl=$_root.$list[1];
      $thum = trim($list[3]);
      $ainfo=array('ourl'=>$purl,'name'=>$oname,'thum'=>$thum,'cid'=>$cid);
//print_r($ainfo);exit;
      getinfodetail($ainfo);
//exit;
sleep(1);
    }

sleep(1);
  }
return 0;
}

function getinfodetail(&$data){
  global $model,$cid,$strreplace,$pregreplace,$_root;
  $html=getHtml($data['ourl']);
  if(!$html){
    echo "获取html失败";exit;
  }
  //kw
  preg_match('#<meta name="keywords" content="(.+)" />#U',$html,$match);
  $data['keyword'] = '';//trim($match[1]);
  //
  $data['ptime']=time();//strtotime(trim($match[1]));
  $data['utime']=time();//strtotime(trim($match[2]));
//  var_dump($match);exit;
  preg_match('#href="http://www\.vvtor\.com/(\?dl_id=\d+)">#Uis',$html,$match);
  $str = @$match[1];
  $data['downurl']=$str;
  $data['ourl'] = str_replace($_root,'',$data['ourl']);
  $data['thum'] = str_replace($_root,'',$data['thum']);
  //
  preg_match('#</h2>\s+(<pre>.+</p>)\s+<hr />#Uis',$html,$match);
  $str = $match[1];
  $data['intro']=$str;
  //preg_match_all('#<img .*src="([^"]+)"#Uis',$data['intro'],$match);
  //echo '<pre>';var_dump($match);exit;
  foreach($strreplace as $val){
    $data['intro']=str_replace($val['from'],$val['to'],$data['intro']);
  }
  foreach($pregreplace as $val){
    $data['intro']=preg_replace($val['from'],$val['to'],$data['intro']);
  }
  $data['intro']=trim($data['intro']);
  if(!$data['name'] || !$data['downurl']){
     echo "抓取失败 $data[ourl] \r\n";
     return false;
  }
  //echo '<pre>';var_dump($data);exit;
  //$aid=$model->addArticle($data);
  $aid = addArticle($data);
  if(!$aid){
    echo "添加失败! $data[ourl]  Cid $data[cid] \r\n";
    exit;return false;
  }
  echo "添加成功! $aid \r\n";
//exit;
}
?>
