<?php
  
include_once($APPPATH.'../post_fun.php');

function getinfolist(&$_cate){
  global $_root,$cid;
  for($i=1; $i<=2000; $i++){
//通过 atotal计算i的值
   $suf = $i == 1?'/':'/index_'.$i.'.html';
   $url = $_root.$_cate['ourl'].$suf;
   echo "\n++++ ",$url," ++++\n";
   //exit;
   $html = getHtml($url);
   if('GBK' == CHAR_SET){
    $html = mb_convert_encoding($html,"UTF-8","GBK");
   }
   $matchs = getCateList($html);
//echo '<pre>';var_dump($matchs);exit;
    if(empty($matchs)){
       file_put_contents('match_error_list'.$cid.'.html',$html);
       //preg_match_all('##Uis',$html,$matchs,PREG_SET_ORDER);
    }
    if(empty($matchs)){
      echo ('Cate list Failed '.$url."\r\n");
      return 6;
    }
    foreach($matchs as $list){
      $oid = preg_replace('#[^\d]+#','',$list['ourl']);
      $title = trim($list['title']);
/**/
//在判断是否更新
      $aid = checkArticleByOname($title);
      if($aid){
         echo "{$aid}已存在未更新!\r\n";
         continue;
        return 6;
      }
/**/
      $ourl = $_root.$list['ourl'];
      $ainfo = array('thum'=>'','ourl'=>$ourl,'title'=>$title,'oname'=>$list['oname']
      ,'oid'=>$oid,'cid'=>$cid);
      getinfodetail($ainfo);
sleep(1);
    }
  }
return 0;
}

function getinfodetail(&$data){
  global $model,$_root,$cid,$strreplace,$pregreplace;
echo $data['ourl'],"\n";
  $html = getHtml($data['ourl']);
  if(CHAR_SET == 'GBK'){
   $html = mb_convert_encoding($html,"UTF-8","GBK");
  }
  if(!$html){
    echo "获取html失败";exit;
  }
  //kw
  preg_match('#<meta name=keywords content="([^"]*)">#Uis',$html,$match);
  $data['keyword'] = trim(@$match[1]);
  //
  $data['ptime'] = time();
  $data['utime'] = time();
  preg_match('#<div id="Zoom">(.+)</div>#Uis',$html,$match);
  $match[1] = isset($match[1])?$match[1]:'';
  $match[1] = @iconv("UTF-8","UTF-8//TRANSLIT",$match[1]);
//echo $match[1],"\n";
  $data['intro'] = @$match[1];
  $data['thund'] = get_downurl($data['intro']);
  $data['intro'] = filter_code($data['intro']);
  if(!$data['title'] || empty($data['thund'])){
    echo "抓取失败 $data[ourl] Cid $cid ==\r\n";
    //return false;
  }
  $data['downurl'] = '';
  $data['ourl'] = str_replace($_root,'',$data['ourl']);
  echo '<pre>';var_dump($data);exit;
/*
*/
  $aid = addArticle($data);
//echo '|',$aid,'|';exit;
  if( !$aid){
    var_dump($data);echo "\r\n添加失败! $data[ourl] Cid $cid ==\r\n";
    exit;return false;
  }
  echo "添加成功! $aid \r\n";
exit;
}

function get_downurl($str){
 preg_match('#<td style="[^"]*" bgcolor="[^"]*"><a href="([^"]+)">.*</a></td>#Uis',$str,$match);
 $url = @$match[1];
 return trim($url);
}
function filter_code($str){
 $str = preg_replace('#<[^>]*script[^>]*>.*<[^>]*/[^>]*script[^>]*>#Uis','',$str);
 $str = preg_replace('#<p style="margin: 0px; padding: 0px; color: rgb\(24, 55, 120\); font-family: Verdana, Arial, Helvetica, sans-serif;">.*</p>#Uis','',$str);
 $str = preg_replace('#<a[^>]*>#Uis','',$str);
 $str = preg_replace('#<!--.*-->#Uis','',$str);
 $str = str_replace(array('</a>','</tr>','</td>','电影天堂www.dy2018.com')
 ,array('','','','电驴BT资源分享www.emubt.com'),$str);
 $str = preg_replace('#<table[^>]*>.*</table>#Uis','',$str);
 $str = preg_replace('#<center[^>]*>.*</center>#Uis','',$str);
 $str = preg_replace('# (style|class)="[^"]*"#Uis','',$str);
 $str = preg_replace('# (style|class)=\'[^\']*\'#Uis','',$str);
 $str = preg_replace('#<(td|tr)[^>]*>#Uis','',$str);
 $str = preg_replace('#<hr[^>]*>#Uis','',$str);
 $str = preg_replace("#(\r\n)+#Uis",'<br />',$str);
 return trim($str);
}

function getCateList($html){
  preg_match_all('#<a href="(/i/\d+\.html)" class="ulink" title="[^"]+">([^<]+)</a>#Uis',$html,$matchs,PREG_SET_ORDER);
  $r = array();
  foreach($matchs as $v){
   $oname = $v[2];
   //preg_match('#《([^》]+)》#Uis',$oname,$mt);
   preg_match('#《(.+)》#Uis',$oname,$mt);
   $title = trim(@$mt[1]);
   $ourl = $v[1];
   $r[] = compact('oname','title','ourl');
  }
  return $r;
}
