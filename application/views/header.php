<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="zh-CN" xml:lang="zh-CN" xmlns="http://www.w3.org/1999/xhtml" xmlns:wb="http://open.weibo.com/wb">
<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
<?php if('topic' == $_a){?>
<meta property="og:image" content="<?php echo $showimgapi,$info['cover'];?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php echo $base_url,'maindex/topic/',$info['id'];?>" />
<meta property="og:site_name" content="<?php echo $web_title;?>" />
<meta property="og:title" content="<?php echo $info['name'];?>" />
<meta property="article:section" content="<?php echo $rootCate[$info['cid']]['name'];?>" />
<meta property="og:description" content="<?php echo $seo_description;?>" />
<meta name=thumbnail" content="<?php echo $showimgapi,$info['cover'];?>" />
<meta itemprop="image" content="<?php echo $showimgapi,$info['cover'];?>"/>
<?php }?>
<meta content="<?php echo $seo_keywords,',',$web_title;if($_a != 'topic'){echo ",龙BT发布,BT之家";}?>" name="keywords">
<meta content="<?php echo $seo_title,'-',$web_title,$seo_description;?>" name="description">
<?php if(isset($cid)){?>
<link href="/api/feed/<?php echo $cid;?>" title="<?php echo $seo_title;?> - <?php echo $web_title;?>" type="application/rss+xml" rel="alternate">
<?php }?>
<title><?php echo $seo_title;?> - <?php echo $web_title,'种子资源分享';if(in_array($_a,array('index','lists'))){ echo '_BT种子下载|龙BT发布|BT之家|BTV.EmuBt.Com';}?></title>
<link title="电驴资源" href="/api/opensearch" type="application/opensearchdescription+xml" rel="search">
<link rel="stylesheet" href="<?php echo $css_url;?>global.css?v=<?php echo $version;?>" type="text/css">
<link rel="stylesheet" href="<?php echo $css_url,$_c,'_',$_a;?>.css?v=<?php echo $version;?>" type="text/css">
<script type="text/javascript" src="http://libs.baidu.com/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo $js_url;?>global.js?v=<?php echo $version;?>"></script>
<?php if(in_array($_a,array('index','lists','topic','fav','search'))){ ?>
<script type="text/javascript" src="<?php echo $js_url;?>jquery.lazyload.min.js?v=<?php echo $version;?>"></script>
<?php } ?>
<?php if(in_array($_a,array('topic'))){ ?>
<script type="text/javascript" src="<?php echo $js_url;?>ZeroClipboard.js?v=<?php echo $version;?>"></script>
<script type="text/javascript" src="<?php echo $js_url;?>item.js?v=<?php echo $version;?>"></script>
<?php } ?>
<script type="text/javascript">
var _action = '<?php echo $_a;?>';
var _errpic = '<?php echo $errorimg;?>';
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-43439571-2', 'emubt.com');
  ga('send', 'pageview');
</script>
<?php if(in_array($_a,array('index','lists','topic'))){ ?>
<script type="text/javascript" src="http://cbjs.baidu.com/js/m.js"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo $js_url;?>lanage.js?v=<?php echo $version;?>"></script>
</head>
<body>
<?php if($click_ad_link){?>
<a href="<?php echo $click_ad_link;?>" target="_blank" style="width: 100%;height: auto;z-index: 999999;position: absolute;margin-left: -699px;" id="adlink_button">  </a>
<?php }?>
<a style="display: none;" id="gotop" href="#" title="返回顶部"
	onfocus="this.blur()"> <span><em class="tr">♦</em><em
	class="tube">▐</em></span></a>
<div style="height: 31px;">
<div id="header_div"><iframe scrolling="no" frameborder="no"
	src="about:blank" class="topbar_iframe"></iframe>
<div class="mainDiv" id="notice_wrapper">
<ul class="header_link clearfix">
  <li class="link_item"><a href="/"
onclick="" class="hover_red"><strong>首页</strong></a></li>
 <li class="link_item">
<a style="display:block;margin-top:5px;" target="_blank" href="http://shang.qq.com/wpa/qunwpa?idkey=b7e5c10f9f8ff387d4b4b7d0cbe6644f8b3468948f539199f465ef7ecc45282d"><img border="0" src="http://pub.idqqimg.com/wpa/images/group.png" alt="静思乐BT影视" title="静思乐BT影视"></a>
  </li>
  <li class="link_item">
<a version="1.0" class="qzOpenerDiv" href="http://goo.gl/LwcZXa" target="_blank" alt="赞一个" title="赞一个">赞一个</a>
  </li>
<li class="link_item">
<a style="color: blue;font-size: medium;" href="javascript:transformLan();" id="a-lang" title="點擊以繁體中文方式浏覽" name="a-lang">繁體中文切換</a>
</li>
</ul>
<div id="header_login">
<span class="link_box">
<ul>
<?php if(0){ ?>
	<li type="history" name="dropmenu" class="watching" status="hide"
		original_class="watching"><a
		onclick=""
		class="watching_item" href="javascript: void(0);">我正在看<span
		class="top_arrow"></span></a></li>
<?php } 
?>
  <li>
   <div id="user_login">
   <span class="user">Œ</span>
   <div class="iconList" style="display: none;">
   <ul>
<?php if(0){ ?>
    <li><a href="/history/" title="我看過的"><em class="watch">图片</em>我看過的</a></li>
    <li><a href="/bookmark/" title="我的書簽"><em class="iconfont">ŷ</em><cite>我的書簽</cite></a></li>
<?php } ?>
    <li><a href="/maindex/fav/" title="我的收藏"><em class="iconfont">ũ</em><cite>我的收藏</cite></a></li>
    <li><a href="/maindex/loginout" title="登出"><em class="iconfont">ơ</em><cite>登出</cite></a></li>
   </ul>
   </div>
   <div class="dropMenu" style="display: none;">
   <ul>
<?php if(0){ ?>
    <li><a href="http://ck101.com/member.php?mod=register" title="注冊會員">注冊會員</a></li>
<?php } ?>
    <li><a class="btn" title="登入" href="/maindex/login" target="_blank">登入</a></li>
   </ul> 
   </div>
  </div>
   <?php echo '<a href="'.$loginurl.'" title="'.$logintitle.'">'.$logintitle.'</a>';?>
  </li>
</ul>
</span></div>
</div>
</div>
</div>
<div id="banner_div">
<div class="mainDiv block">
<div id="logo_div"><a href="/" title="<?php echo $web_title;?> - BT影视种子"
	target="_top" id="index_logo"><img alt="<?php echo $web_title;?> - BT影视种子"
	src="<?php echo $img_url;?>emulogo.jpg?v=<?php echo $version;?>"
	class="png_image"></a></div>
<div id="new_search_bar_div">
<div class="clearfix">
<?php if(0){ ?>
<div id="top_add" class="top_add"><a
	onclick="showTopAddOptions(this);return false;"
	class="top_add_link" href="#">分享</a>
<div style="display: none;" id="top_add_options"
	class="top_add_options png"><a class="top_add_article"
	onclick="" href="/articles/add/">
文章 </a> <a class="top_add_entry"
	onclick="" href="/base/add"> 资料
</a> <a class="top_add_topic"
	onclick=""
	href="/topics/post"> 资源 </a></div>
<?php } ?>
</div>
<div id="top-search">
<form action="/maindex/search/" onsubmit=""
	class="block"><span id="search-module-toggle"> <img
	onload="this.onload=''; if(this.style.filter) { this.src='<?php echo $img_url;?>search0.gif'; this.width=18; this.height=18; }"
	style=""
	src="<?php echo $img_url;?>entries.png?v=<?php echo $version;?>"
	alt="" id="current-search-module-img"> </span> <input type="text"
	tabindex="1" 
	x-webkit-grammar="builtin:translate" x-webkit-speech=""
	onblur="if(this.value=='')this.value='搜索资料标题、内容...';this.style.color='#999';"
	onfocus="if(this.value=='搜索资料标题、内容...'){this.value=''};this.style.color='#000';"
	autocomplete="off" class="top-search-input" value="" name="q"
	id="search_keyword"> <input type="hidden" value="<?php echo isset($cid)?$cid:0;?>"
	id="search_type">
<button class="top-search-button" id="top-search-button" type="submit">搜索</button>
<?php if(0){ ?>
<button
	onclick="location.href='/search#advanced';return false;"
	class="top-search-button" id="top-search-advance" type="button">高级搜索</button>
<?php } ?>
</form>
</div>

<div class="link_line hot_search_keywords">热门搜索：&nbsp;
<?php if(0){ ?>
<a target="_blank" href="/search/entries/%E7%88%B1%E6%83%85%E5%85%AC%E5%AF%934" style="text-decoration: none;">爱情公寓4</a>&nbsp;&nbsp;
<?php } ?>
</div>
</div>
</div>
</div>

<div class="mainDiv">
<div id="nav_div">
<ul id="header_ul_big" class="ul big">
	<li><a onclick="" href="/" class="<?php echo $_a == 'index' ? 'hover':'';?>">首页</a></li>
<?php foreach($rootCate as $row){
?>
                <li><a href="<?php echo $row['url'];?>" onclick="" class="<?php echo in_array($row['id'],array($cid,$cpid)) ? 'hover':'';?>"><?php echo $row['name'];?></a></li>
<?php
} ?>
</ul>

</div>
</div>
<div class="mainDiv">
<div style="float: right; padding-right: 12px;" id="addfavorite_div">
<a onclick="addfavorite('<?php echo $base_url;?>','<?php echo $web_title;?>');return false;"
	style="color: #555" rel="sidebar" href="<?php echo $base_url;?>">+ 将本站加入收藏夹</a></div>
<div id="location_div">您的位置：<a href="/"><?php echo $web_title;?></a>
<?php foreach($postion as $row){?>
 &gt; <a
	href="<?php echo $row['url'];?>"><?php echo $row['name'];?></a> 
<?php } ?>
<?php if(isset($cidd)){ ?>
<a href="http://www.verycd.com/base/tv/feed"
	title="使用RSS订阅本栏更新"><img align="absmiddle"
	src="<?php echo $img_url;?>feeds.gif?v=<?php echo $version;?>" alt="feed" style="vertical-align: top;"></a>
<?php } ?>
</div>
</div>
<div class="mainDiv">
