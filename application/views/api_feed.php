<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">
  <channel>
    <title><?php echo $rootCate[$cid]['name'],' - ',$web_title;?></title>
    <description><![CDATA[<?php echo $rootCate[$cid]['name'],' - ',$web_title;?>]]></description>
    <language>zh-cn</language>
    <pubDate><?php echo date('r');?></pubDate>
    <lastBuildDate><?php echo date('r');?></lastBuildDate>
    <generator>EmuBt.com</generator>
    <managingEditor>1187247901@qq.com.com (webmaster)</managingEditor>
    <webMaster>1187247901@qq.com (webmaster)</webMaster>
    <ttl>4</ttl>
<?php foreach($data as $v){?>
    <item>
      <title><![CDATA[<?php echo '[',$rootCate[$cid]['name'],']',$v['name'];?>]]></title>
      <link><?php echo $baseurl,'maindex/topic/',$v['id'];?></link>
      <description><![CDATA[<?php echo $v['summary'];?>]]></description>
      <pubDate><?php echo date('r',$v['ptime']);?></pubDate>
      <dc:creator>www</dc:creator>
    </item>
<?php }?>
  </channel>
</rss>
