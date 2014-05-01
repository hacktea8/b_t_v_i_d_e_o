<?php

$cateid=2;$pageno=1;
$_root='http://www.vvtor.com/';
//
//
$strreplace=array(
#array('from'=>'www.vvtor.com','to'=>'btv.hacktea8.com')
array('from'=>'\"','to'=>'"')
,array('from'=>'\r\n','to'=>'')
,array('from'=>'\n','to'=>'')
);
//
$pregreplace=array(
array('from'=>'#class="[^"]+"#Us','to'=>'</td>')
,array('from'=>'#id="[^"]+"#','to'=>'')
,array('from'=>'#<script [^>]+>.*</script>#','to'=>'')
);


?>
