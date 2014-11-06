</div>
<a class="show_site_tags"><?php echo $seo_keywords;?></a>

<div class="mainDiv">
<?php if(in_array($_a,array('index','lists','topic'))){?>
<script type="text/javascript">BAIDU_CLB_fillSlot("902635");</script>
<?php }?>
<div class="line_space"></div>
</div>
<div class="clear"></div>
<div class="mainDiv">
<div id="bottom_div">
<br>
&copy;2013 - <?php echo date('Y');?>
如果侵犯了你的权益，请通知我们，我们会及时删除侵权内容，谢谢合作！ 联系信箱：<?php echo $admin_email;?><br />
<?php echo $domain;?> Inc. All rights reserved Powered <?php echo $web_title;?>
</div>
</div>
</div>
</div>
</div>
</div>
<div style="display:none;">
<script type="text/javascript" src="<?php echo $js_url,'footer.js?v=',$version;?>"></script>
<?php if(in_array($_a,array('emuleTopicAdd'))){ ?>
<script  src="<?php echo $js_url,$_c,'_',$_a,'.js?v=',$version;?>" ></script>
<?php } ?>
<script  src="http://qzonestyle.gtimg.cn/qzone/app/qzlike/qzopensl.js#jsdate=20110603&style=3&showcount=1&width=130&height=30" charset="utf-8" defer="defer" ></script>
<?php if(in_array($_a,array('lists','topic'))){ ?>
<script  src="<?php echo $js_url,'moneysad.js?v=',$version;?>" ></script>
<?php } ?>
</div>
</body>
</html>
