<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Grabapi extends CI_Controller {

        /**
         * Index Page for this controller.
         *
         */
  public function __construct(){
    parent::__construct();
    $this->load->model('grabapimodel');
  }
  public function checkArticleByOname(){
    $oname = $_POST['oname'];
    if(!$oname){
      echo '404';
      return 0;
    }
    $data = $this->grabapimodel->checkArticleByOname($oname);
    $data = json_encode($data);
    die($data);
  }
  public function addArticleInfo(){
    $post = $_POST['article_data'];
    $post = json_decode($post,1);
    if(!$post){
      echo '404';
      return 0;
    }
    $data = $this->grabapimodel->addArticle($post);
    $data = json_encode($data);
    die($data);
  }
}
