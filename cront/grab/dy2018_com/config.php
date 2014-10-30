<?php

$_root='http://www.dy2018.com/';
define('CHAR_SET', 'GBK');
$_devStatus = 'OK';
#$http_proxy = '211.138.121.37:82';
$http_proxy = '';
//
$strreplace=array(
array('from'=>'www.ed2kers.com','to'=>'emu.hacktea8.com')
,array('from'=>'\"','to'=>'"')
,array('from'=>'\r\n','to'=>'')
,array('from'=>'\n','to'=>'')
);
//
$pregreplace=array(
array('from'=>'#<br>引用.+</td>#Us','to'=>'</td>')
,array('from'=>'#<script [^>]+>.*</script>#','to'=>'')
);

$cate_config = array(
array('cid'=>3,'ourl'=>'2','name'=>'动作片')
,array('cid'=>7,'ourl'=>'1','name'=>'喜剧片')
,array('cid'=>12,'ourl'=>'4','name'=>'科幻片')
,array('cid'=>10,'ourl'=>'8','name'=>'恐怖片')
,array('cid'=>10,'ourl'=>'7','name'=>'惊悚片')
,array('cid'=>6,'ourl'=>'14','name'=>'战争片')
,array('cid'=>9,'ourl'=>'15','name'=>'犯罪片')
,array('cid'=>8,'ourl'=>'3','name'=>'爱情片')
,array('cid'=>4,'ourl'=>'0','name'=>'剧情片')
,array('cid'=>14,'ourl'=>'5','name'=>'动漫片')
,array('cid'=>14,'ourl'=>'/html/dongman','name'=>'动漫片')

);


?>
