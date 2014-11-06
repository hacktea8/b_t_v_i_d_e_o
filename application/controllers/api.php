<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'usrbase.php';
class Api extends Usrbase {
   
 /**
  * Index Page for this controller.
  *
  */
 public function __construct(){
  parent::__construct();
  $this->load->model('emulemodel');
 }
 public function index(){
  // $this->load->model('testmodel');
  $data=$this->testModel->getdata();
  $this->load->view('welcome_message',array('data'=>$data));
 }
 public function feed($cid=0){
  $data = $this->emulemodel->getArticleListByCid($cid,$order=0,$page=1,$limit=25);
  header('Content-Type:application/xml'); 
  $this->assign(array('cid'=>$cid,'data'=>$data['emulelist']));
  $this->load->view('api_feed', $this->viewData);
 }
 public function opensearch(){
  //$data = $this->emulemodel->getArticleListByCid($cid='',$order=0,$page=1,$limit=25);
  header('Content-Type:application/xml'); 
  $this->load->view('api_opensearch',array('data'=>$data));
 }
}
