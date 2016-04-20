<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed");
class Json extends CI_Controller 
{function getallblog()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`tingblog_blog`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";

$elements=array();
$elements[1]=new stdClass();
$elements[1]->field="`tingblog_blog`.`name`";
$elements[1]->sort="1";
$elements[1]->header="name";
$elements[1]->alias="name";

$elements=array();
$elements[2]=new stdClass();
$elements[2]->field="`tingblog_blog`.`image`";
$elements[2]->sort="1";
$elements[2]->header="image";
$elements[2]->alias="image";

$elements=array();
$elements[3]=new stdClass();
$elements[3]->field="`tingblog_blog`.`video`";
$elements[3]->sort="1";
$elements[3]->header="video";
$elements[3]->alias="video";

$elements=array();
$elements[4]=new stdClass();
$elements[4]->field="`tingblog_blog`.`description`";
$elements[4]->sort="1";
$elements[4]->header="description";
$elements[4]->alias="description";

$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `tingblog_blog`");
$this->load->view("json",$data);
}
public function getsingleblog()
{
$id=$this->input->get_post("id");
$data["message"]=$this->blog_model->getsingleblog($id);
$this->load->view("json",$data);
}
function getalltags()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`tingblog_tags`.`id`";
$elements[0]->sort="1";
$elements[0]->header="id";
$elements[0]->alias="id";

$elements=array();
$elements[1]=new stdClass();
$elements[1]->field="`tingblog_tags`.`name`";
$elements[1]->sort="1";
$elements[1]->header="name";
$elements[1]->alias="name";

$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `tingblog_tags`");
$this->load->view("json",$data);
}
public function getsingletags()
{
$id=$this->input->get_post("id");
$data["message"]=$this->tags_model->getsingletags($id);
$this->load->view("json",$data);
}
function getallgif()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`tingblog_gif`.`id`";
$elements[0]->sort="1";
$elements[0]->header="id";
$elements[0]->alias="id";

$elements=array();
$elements[1]=new stdClass();
$elements[1]->field="`tingblog_gif`.`name`";
$elements[1]->sort="1";
$elements[1]->header="name";
$elements[1]->alias="name";

$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `tingblog_gif`");
$this->load->view("json",$data);
}
public function getsinglegif()
{
$id=$this->input->get_post("id");
$data["message"]=$this->gif_model->getsinglegif($id);
$this->load->view("json",$data);
}
} ?>