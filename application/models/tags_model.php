<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class tags_model extends CI_Model
{
public function create($name)
{
$data=array("name" => $name);
$query=$this->db->insert( "tingblog_tags", $data );
$id=$this->db->insert_id();
if(!$query)
return  0;
else
return  $id;
}
public function beforeedit($id)
{
$this->db->where("id",$id);
$query=$this->db->get("tingblog_tags")->row();
return $query;
}
function getsingletags($id){
$this->db->where("id",$id);
$query=$this->db->get("tingblog_tags")->row();
return $query;
}
public function edit($id,$name)
{
$data=array("name" => $name);
$this->db->where( "id", $id );
$query=$this->db->update( "tingblog_tags", $data );
return 1;
}
public function delete($id)
{
$query=$this->db->query("DELETE FROM `tingblog_tags` WHERE `id`='$id'");
return $query;
}
// public function getimagebyid($id)
// {
// $query=$this->db->query("SELECT `image` FROM `tingblog_tags` WHERE `id`='$id'")->row();
// return $query;
// }
public function gettagdropdown()
{
$query=$this->db->query("SELECT * FROM `tingblog_tags` ORDER BY `id`
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
