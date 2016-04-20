<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class blog_model extends CI_Model
{
public function create($name,$image,$video,$description)
{
$data=array("name" => $name,"image" => $image,"video" => $video,"description" => $description);
$query=$this->db->insert( "tingblog_blog", $data );
$id=$this->db->insert_id();
if(!$query)
return  0;
else
return  $id;
}
public function beforeedit($id)
{
$this->db->where("id",$id);
$query=$this->db->get("tingblog_blog")->row();
return $query;
}
function getsingleblog($id){
$this->db->where("id",$id);
$query=$this->db->get("tingblog_blog")->row();
return $query;
}
public function edit($id,$name,$image,$video,$description)
{
if($image=="")
{
$image=$this->blog_model->getimagebyid($id);
$image=$image->image;
}
$data=array("name" => $name,"image" => $image,"video" => $video,"description" => $description);
$this->db->where( "id", $id );
$query=$this->db->update( "tingblog_blog", $data );
return 1;
}
public function delete($id)
{
$query=$this->db->query("DELETE FROM `tingblog_blog` WHERE `id`='$id'");
return $query;
}
public function getimagebyid($id)
{
$query=$this->db->query("SELECT `image` FROM `tingblog_blog` WHERE `id`='$id'")->row();
return $query;
}
public function getdropdown()
{
$query=$this->db->query("SELECT * FROM `tingblog_blog` ORDER BY `id`
                    ASC")->result();
$return=array(
"" => "Select Option"
);
foreach($query as $row)
{
$return[$row->id]=$row->name;
}
return $return;
}
}
?>
