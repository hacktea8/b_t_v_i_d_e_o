<?php
require_once 'basemodel.php';
class emuleModel extends baseModel{
  protected $_dataStruct = 'a.`id`, a.`cid`, a.`uid`, a.`name`, a.`collectcount`, a.`ptime`, a.`utime`, a.`cover`, a.`hits`';
  protected $_datatopicStruct = 'a.`id`,a.`ourl`, a.`cid`, a.`uid`, a.`name`, a.`collectcount`, ac.`keyword`, ac.`download`, ac.`vipdwurl`, a.`ptime`, a.`utime`, ac.`intro`, a.`cover`, a.`hits`';

  public function __construct(){
    parent::__construct();
  }
  public function autoSetVideoOnline($limit = 5){
    $cate = $this->getCateByCid(0);
    foreach($cate as $v){
      $k = $v['id'];
      $sql = sprintf('UPDATE %s SET `onlinedate`=%d,`flag`=1,`utime`=%d,`ptime`=%d WHERE `onlinedate`=0 AND `cid`=%d AND `flag`=3 LIMIT %d',$this->db->dbprefix('emule_article'),date('Ymd'),time(),time(),$k,$limit);
      $this->db->query($sql);
    }
    return 1;
  }
  public function setCateVideoTotal(){
    $cate = $this->getCateByCid(0);
    foreach($cate as $v){
      $k = $v['id'];
      $sql = sprintf('UPDATE %s SET`atotal`=(SELECT COUNT(*) FROM %s WHERE `flag`=1 AND `onlinedate`<=%d AND `cid`=%d) WHERE `id`=%d LIMIT 1',$this->db->dbprefix('emule_cate'),$this->db->dbprefix('emule_article'),date('Ymd'),$k,$k);
      $this->db->query($sql);
    }
    return 1;
  }
  public function get_link($mod,$site_url='',$p1='',$p2=0,$p3=1,$p4=''){
    $url = '';
    if('cate' == $mod){
      $url = sprintf('%s/maindex/lists/%d/%d/%d.shtml',$site_url,$p1,$p2,$p3);
    }elseif('topic' == $mod){
      $url = sprintf('%s/maindex/topic/%d.shtml',$site_url,$p1);
    }
    return $url;
  }
  public function getEmuleIndexData(){
    $emuleIndex = $tmp = array();
    $limit = 15;
    $tmp = $this->getArticleListByCid(0,$order=0,$page=1,$limit);
    $emuleIndex['new'] = $tmp['emulelist'];
    $tmp = $this->getArticleListByCid(0,$order=1,$page=1,$limit);
    $emuleIndex['rand'] = $tmp['emulelist'];
    $tmp = $this->getArticleListByCid(0,$order=2,$page=1,$limit);
    $emuleIndex['hot'] = $tmp['emulelist'];
    $cate = $this->getCateByCid(0);
    foreach($cate as $v){
      $k = $v['id'];
      $tmp = $this->getArticleListByCid($k,$order=2,$page=1,$limit);
      $emuleIndex['catehot'][] = array('list'=>$tmp['emulelist'],'name'=>$v['name'],'url'=>$this->get_link('cate','',$k));
    }
    $limits = 25;
    foreach($cate as $v){
      $k = $v['id'];
      $list = array();
      $subcate = array(array('name'=>'最新','url'=>$this->get_link('cate','',$k,0)),array('name'=>'随机','url'=>$this->get_link('cate','',$k,1)));
      $tmp = $this->getArticleListByCid($k,$order=0,$page=1,$limits);
      $list[0] = $tmp['emulelist'];
      $tmp = $this->getArticleListByCid($k,$order=1,$page=1,$limits);
      $list[1] = $tmp['emulelist'];
      $tmp = $this->getArticleListByCid($k,$order=1,$page=2,$limits+10);
      $rand = $tmp['emulelist'];
      $emuleIndex['topiclist'][] = array('rand'=>$rand,'subcate'=>array('cate'=>$subcate,'list'=>$list));
    }
    return $emuleIndex;
  }
  public function getUserCollectList($uid,$order=0,$page=1,$limit=25){
    if( !$uid){
      return false;
    }
    $ordermap = array('new'=>'ct.`atime` DESC ');
    $order = isset($ordermap[$order]) ? $ordermap[$order] : array_shift($ordermap);
    $order = ' ORDER BY '.$order;
    $page = intval($page) - 1;
    $page = $page ? $page : 0;
    $page *= $limit;
    $limit = sprintf(' LIMIT %d,%d ',$page,$limit);
    $sql = sprintf("SELECT ae.`id`, ae.`cid`, ae.`uid`, ae.`name`, ae.`utime`, ae.`cover`,ct.`atime` FROM %s as ae INNER JOIN %s as ct ON(ae.id=ct.aid) WHERE ct.uid=%d AND ct.`flag`=1 %s %s",$this->db->dbprefix('emule_article'),$this->db->dbprefix('collect'),$uid,$order,$limit);
    $list = $this->db->query($sql)->result_array();
    foreach($list as &$v){
      $v['utime'] = date('Y-m-d H:i:s', $v['utime']);
      $v['atime'] = date('Y-m-d H:i:s', $v['atime']);
      $v['url'] = $this->get_link('topic','',$v['id']);
    }
    return $list;
  }

  public function renewUserShip($data){
    //collect
    if('collect' === $data['type']){
       $sql = sprintf("UPDATE %s SET `collectcount`=`collectcount` %s WHERE `id`=%d LIMIT 1",$this->db->dbprefix('emule_article'),$data['collect'],$data['aid']);
       return $this->db->query($sql);
    }
  }

  public function addUserCollect($uid,$aid){
    if( !$uid || !$aid){
      return false;
    }
    $table = $this->db->dbprefix('collect');
    $sql = sprintf("SELECT `flag` FROM %s WHERE `aid`=%d AND `uid`=%d LIMIT 1",$table,$aid,$uid);
    $row = $this->db->query($sql)->row_array();
    if(isset($row['flag'])){
      $flag = $row['flag'];
      $flag = $flag ? 0:1;
      $sql = $this->db->update_string($table,array('flag'=>$flag),array('aid'=>$aid,'uid'=>$uid));
      $this->db->query($sql);
      return $flag;
    }
    $data = array('aid'=>$aid,'uid'=>$uid,'flag'=>1,'atime'=>time());
    $sql = $this->db->insert_string($table,$data);
    $this->db->query($sql);
    return 1;
  }

  public function getUserIscollect($uid,$aid){
    if( !$uid || !$aid){
      return false;
    }
    $sql = sprintf("SELECT `flag` FROM %s WHERE `aid`=%d AND `uid`=%d LIMIT 1",$this->db->dbprefix('collect'),$aid,$uid);
    $row = $this->db->query($sql)->row_array();
    return isset($row['flag']) ? $row['flag']:0;
  }

  public function getUserCollectTotal($uid){
    if( !$uid){
      return false;
    }
    $sql = sprintf("SELECT count(*) as total FROM %s WHERE `uid`=%d",$this->db->dbprefix('collect'),$uid);
    $row = $this->db->query($sql)->row_array();
    return isset($row['total']) ? $row['total']: 0;
  }

  public function getArticleListByCid($cid='',$order=0,$page=1,$limit=25){
     switch($order){
       case 1:
       $order=' ORDER BY a.hits ASC '; break;
       case 2:
       $order=' ORDER BY a.hits DESC '; break;
       default:
       $order=' ORDER BY a.ptime DESC ';
     }
     $page = intval($page) - 1;
     $page = $page ? $page : 0;
     $page *= $limit;
     if($cid){
       $cids = $this->getAllCateidsByCid($cid);
       $cids = implode(',',$cids);
       $where = ' a.`cid` in ('.$cids.') AND ';
     }
     $sql = sprintf('SELECT %s,c.`name` as cname FROM %s as a LEFT JOIN %s as c ON (a.cid=c.id) WHERE %s a.`flag`=1 AND c.flag=1 %s LIMIT %d,%d',$this->_dataStruct,$this->db->dbprefix('emule_article'),$this->db->dbprefix('emule_cate'),$where,$order,$page,$limit);
//echo $sql;exit;
     $data = array();
     $data['emulelist'] = $this->db->query($sql)->result_array();
     foreach($data['emulelist'] as &$val){
       $val['ptime'] = date('Y-m-d', $val['ptime']);
       $val['utime'] = date('Y/m/d', $val['utime']);
       $val['url'] = $this->get_link('topic','',$val['id']);
     }
     $data['postion'] = $this->getsubparentCate($cid);
     $data['subcatelist'] = $this->getAllSubcateByCid($cid);
     //$data['atotal']   = $this->getCateAtotal($cid);
     return $data;
  }

  public function getAllSubcateByCid($cid,$limit = 0){
    $sql = sprintf('SELECT `id`, `pid`, `name`, `atotal` FROM %s WHERE `id`=%d AND `flag`=1 LIMIT 1',$this->db->dbprefix('emule_cate'),$cid);
    $subinfo = $this->db->query($sql)->row_array();
    if( $subinfo['pid']){
      $cid = $subinfo['pid'];
    }
    $limit = intval($limit);
    $limit = $limit ? ' ORDER BY atotal DESC LIMIT '.$limit : '';
    $sql = sprintf('SELECT `id`, `pid`, `name`, `atotal` FROM %s WHERE `pid`=%d AND `flag`=1 %s',$this->db->dbprefix('emule_cate'),$cid,$limit);
    return $this->db->query($sql)->result_array();
  }

  public function getCateListByPid($pid = 0, $limit = 0){
    if( !$pid){
      return false;
    }
    $limit = intval($limit);
    $limit = $limit ? ' ORDER BY atotal DESC LIMIT '.$limit : '';
    $sql = sprintf('SELECT `id`, `pid`, `name`, `atotal` FROM %s WHERE `pid`=%d AND `flag`=1 %s',$this->db->dbprefix('emule_cate'),$pid,$limit);
    return $this->db->query($sql)->result_array();
  }

  public function getsubparentCate($cid = 0){
     if( !$cid){
        return false;
     }
     $sql = sprintf('SELECT `id`, `pid`, `name` FROM %s WHERE `id`=%d AND `flag`=1 LIMIT 1',$this->db->dbprefix('emule_cate'),$cid);
     $subinfo = $this->db->query($sql)->row_array();
     if($subinfo['pid']){
       $parinfo = $this->getsubparentCate($subinfo['pid']);
     }else{
       return array($subinfo);
     }
     return $res = array(array('id'=>$parinfo[0]['id'],'name'=>$parinfo[0]['name']),array('id'=>$subinfo['id'],'name'=>$subinfo['name']));
  }

  public function getEmuleTopicByAid($aid,$uid=0,$isadmin=false){
     $where = '';
     if($uid && !$isadmin)
       $where = sprintf(' AND `uid`=%d LIMIT 1',$uid);

     $table = sprintf("emule_article_content%d",$aid%10);
     $sql = sprintf('SELECT %s FROM %s as a LEFT JOIN %s as ac ON (a.id=ac.id) WHERE a.id =%d  %s',$this->_datatopicStruct,$this->db->dbprefix('emule_article'),$this->db->dbprefix($table),$aid,$where);
     $data = array();
     $data['info'] = $this->db->query($sql)->row_array();
     $data['info']['url'] = $this->get_link('topic','',$data['info']['id']);
     $data['postion'] = $this->getsubparentCate($data['info']['cid']);
     return $data;
  }

  public function setEmuleTopicByAid($uid=0,$data,$isadmin=false){
     //过滤字段
     $header = array();
     $header['id'] = $data['header']['id'];
     $header['cid'] = $data['header']['cid'];
     $header['name'] = $data['header']['name'];
     $header['cover'] = $data['header']['cover'];
     $header['utime'] = time();
     $body = array();
     $body['keyword'] = $data['header']['tags'];
     $body['downurl'] = $data['body']['downurl'];
     $body['vipdwurl'] = $data['body']['vipdwurl'];
     $body['intro'] = $data['body']['intro'];
     if(isset($header['id']) && $header['id']){
        $this->_datatopicStruct = ' a.`id` ';
        $check = $this->getEmuleTopicByAid($header['id'],$uid,$isadmin);
        if( !isset($check['info']['id'])){
           return false;
        }
        $where = array('id'=>$header['id']);
        unset($header['id']);
        $sql = $this->db->update_string($this->db->dbprefix('emule_article'),$header,$where);
        $this->db->query($sql);
        $table = sprintf("emule_article_content%d",$where['id']%10);
        $sql = $this->db->update_string($this->db->dbprefix($table),$body,$where);
        $this->db->query($sql);
        return $data['id'];
     }
     $header['uid'] = $uid;
     unset($header['id']);
     $sql = $this->db->insert_string($this->db->dbprefix('emule_article'),$header);
     $this->db->query($sql);
     $id = $this->db->insert_id();
     $body['id'] = $id;
     $table = sprintf("emule_article_content%d",$id%10);
     $sql = $this->db->insert_string($this->db->dbprefix($table),$info);
     $this->db->query($sql);
     return $id;
  }

  public function delEmuleTopicByAid($aid = 0,$uid=0,$isadmin=false){
     if( !$aid){
        return false;
     }
     $this->_datatopicStruct = ' `id` ';
     $check = $this->getEmuleTopicByAid($aid,$uid,$isadmin);
     if( !isset($check['id'])){
        return false;
     }
     $where = array('id'=>$aid);
     $sql = $this->db->delete($this->db->dbprefix('emule_article'),$where);
     $this->db->query($sql);
     $table = sprintf("emule_article_content%d",$aid%10);
     $sql = $this->db->delete($this->db->dbprefix($table),$where);
     $this->db->query($sql);
     return $aid;
  }

  public function getCateAtotal($cid){
     if( !$cid){
        return false;
     }
     $sql = sprintf('SELECT `pid`, `atotal` FROM %s WHERE `id`=%d AND `flag`=1 LIMIT 1',$this->db->dbprefix('emule_cate'),$cid);
     $subinfo = $this->db->query($sql)->row_array();

     if( !$subinfo['pid']){
        $sql = sprintf('SELECT sum(`atotal`) as atotal FROM %s WHERE `pid`=%d AND `flag`=1',$this->db->dbprefix('emule_cate'),$cid);
        $subinfo = $this->db->query($sql)->row_array();
     }
     return $subinfo['atotal'];

  }

  public function getAllCateidsByCid($cid = 0){
     if( !$cid){
        return false;
     }
     $sql = sprintf('SELECT `id` FROM %s WHERE `pid`=%d AND `flag`=1',$this->db->dbprefix('emule_cate'),$cid);
     $cate = $this->db->query($sql)->result_array();
     $res = array();
     foreach($cate as $val){
       $res[] = $val['id'];
     }
     $res = count($res) ? $res : array($cid);
     return $res;
  }

  public function getHotTopic($order = 'hits',$limit=15){
     $order = $order ? ' `ptime` DESC ': ' `hits` DESC ';
     $sql   = sprintf('SELECT `id`, `name`,`cover` FROM %s WHERE `flag`=1 ORDER BY %s LIMIT %d', $this->db->dbprefix('emule_article'), $order, $limit); 
     return $this->db->query($sql)->result_array();
  }

  public function getCateByCid($sub=0){
     if($sub){
       $sql = sprintf('SELECT `id`, `pid`, `name`, `atotal` FROM %s WHERE `flag` = 1',$this->db->dbprefix('emule_cate'));
       $list= $this->db->query($sql)->result_array();
       $res = array();
       foreach($list as $val){
         if($val['pid'] == 0){
           $res[$val['id']]['id'] = $val['id'];
           $res[$val['id']]['name'] = $val['name'];
           $res[$val['id']]['atotal'] = $val['atotal'];
         }else{
           $res[$val['pid']]['subcate'][] = $val;
         }
       }
       return $res;
     }
     $return = array();
     $sql = sprintf('SELECT `id`, `pid`, `name`, `atotal` FROM %s WHERE `pid` = 0 AND `flag` = 1',$this->db->dbprefix('emule_cate'));
     $list = $this->db->query($sql)->result_array();
     foreach($list as &$v){
       $return[$v['id']] = $v;
     }
     return $return;
  }
}
?>
