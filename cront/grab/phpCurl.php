<?php
class phpCurl{
  public $config = array();
  public $postVal = array();
  protected $ch = '';
  public $cookie_file = '';
  public $http_header = array('Expect:');

  public function __construct(){
    $this->config = array(
    'cookie'=>'cookie',
    'userAgent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/28.0.1500.95 Safari/537.36',
    'header' => 0
    );

  }
  public function getHtml(){
    $this->ch = curl_init();
    $url = $this->config['url'];
    unset($this->config['url']);
    curl_setopt($this->ch, CURLOPT_URL, $url);
    curl_setopt($this->ch,CURLOPT_HEADER, intval($this->config['header']));
    //lighttpd server
    curl_setopt($this->ch, CURLOPT_HTTPHEADER, $this->http_header);
    curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($this->ch, CURLOPT_MAXREDIRS, 1);
    if(isset($this->config['referer'])){
       curl_setopt ($this->ch,CURLOPT_REFERER, $this->config['referer']);
    }
   //如果你想把一个头包含在输出中，设置这个选项为一个非零值。
    curl_setopt($this->ch,CURLOPT_RETURNTRANSFER, 1); ///设置不输出在浏览器上
    if(count($this->postVal) > 0){
       curl_setopt($this->ch,CURLOPT_POST,count($this->postVal));
    /////如果你想PHP去做一个正规的HTTP POST，设置这个选  项为一个非零值。这个POST是普通的 application/x-www-from-urlencoded 类型，多数被HTML表单使用。
       curl_setopt($this->ch,CURLOPT_POSTFIELDS, $this->postVal);
    }
    ////传递一个作为HTTP "POST"操作的所有数据的字符串。
//    $url_path = parse_url($url);
// cookie 文件要绝对地址;
    $this->cookie_file = ROOTPATH.'cookie/'.$this->config['cookie'];
    if(!file_exists($this->cookie_file)){
      touch($this->cookie_file);
      @chmod($this->cookie_file,0777);
    }
    if(isset($this->config['proxy'])){
       curl_setopt($this->ch, CURLOPT_HTTPPROXYTUNNEL, 1);
       curl_setopt($this->ch, CURLOPT_PROXY ,$this->config['proxy']);
       curl_setopt($this->ch, CURLOPT_PROXYTYPE, 7);
    }
    if(isset($this->config['userAgent'])){
       curl_setopt($this->ch, CURLOPT_USERAGENT, $this->config['userAgent']);
    }
    //curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:8.8.8.8', 'CLIENT-IP:8.8.8.8'));  //构造IP
    curl_setopt($this->ch, CURLOPT_COOKIEFILE, $this->cookie_file);
    curl_setopt($this->ch, CURLOPT_COOKIEJAR, $this->cookie_file);
    /////把返回来的cookie信息保存在$cookie_jar文件中
    $this->html = curl_exec($this->ch);///执行
    $this->postVal = array();
    $this->config['url'] = $url;
    $this->config['referer'] = $url;
    if(!$this->html){
       echo curl_error($this->ch),"\n";
    }
    curl_close($this->ch);
    return $this->html;
  }
  public function translate($q='',$sl='ja',$tl='zh-TW'){
    $url = sprintf("http://translate.google.cn/translate_a/t?client=t&sl=%s&tl=%s&hl=zh-CN&sc=2&ie=UTF-8&oe=UTF-8&prev=enter&srcrom=1&ssel=4&tsel=4&q=%s",$sl,$tl,urlencode($q));
    $html = file_get_contents($url);
    $html = explode(',',$html);
    $html = $html[0];
    $len = strlen('[[["');
    $html = substr($html,$len,-1);
    return $html;
  }
  public function __destruct(){
  }

}

