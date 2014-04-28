<?php

$oname = '《《Sweet》日本时尚杂志甜美性感系列》(Sweet)更新至2013年11月号';
      if(false != $pos = strpos($oname,'更新至')){
         $title = substr($oname,$pos);
         preg_match('#《《.+》.+》#Uis',$oname,$match);
var_dump($match);
         $oname = isset($match[0]) ? $match[0] : substr($oname,0,$pos);
      }
echo $oname;

