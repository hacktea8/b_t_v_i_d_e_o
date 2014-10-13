<?php
define('ROOTPATH',$APPPATH.'../');
require_once ROOTPATH.'phpCurl.php';

$apicurl = new phpCurl();
$apicurl->config['cookie'] = 'cookie_api';

function checkArticleByOname($oname){
  global $apicurl,$POST_API;
  $url = $POST_API.'checkArticleByOname';
  $apicurl->config['url'] = $url;
  $apicurl->postVal = array(
  'oname' => $oname
  );
  $html = $apicurl->getHtml();
  $return = json_decode($html,1);
//var_dump($return);exit;
  return $return;
}
function addArticle($data){
  global $apicurl,$POST_API;
  $url = $POST_API.'addArticleInfo';
  $apicurl->config['url'] = $url;
  $apicurl->postVal = array(
  'article_data' => json_encode($data)
  );
  $error = json_last_error();
  if($error){
    var_dump($data);exit;
  }
  $html = $apicurl->getHtml();
//var_dump($html);exit;
  return json_decode($html,1);
}

