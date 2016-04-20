<?php
if ( !defined( "BASEPATH" ) )
exit( "No direct script access allowed" );
class blogtag_model extends CI_Model
{
public function create($relatedtag,$blog)
{
$data=array("blog" => $blog,"tag" => $relatedtag);
$query=$this->db->insert( "tagsblog", $data );
$id=$this->db->insert_id();
if(!$query)
return  0;
else
return  $id;
}
public function beforeedit($id)
{
$this->db->where("id",$id);
$query=$this->db->get("tagsblog")->row();
return $query;
}
function getsinglerealtedblog($id){
$this->db->where("id",$id);
$query=$this->db->get("selftables_realtedblog")->row();
return $query;
}
public function edit($id,$relatedtag,$blog)
{

$data=array("tag" => $relatedtag);
$this->db->where( "id", $id );
$query=$this->db->update( "tagsblog", $data );

return 1;
}
public function delete($id)
{
$query=$this->db->query("DELETE FROM `tingblog_tags` WHERE `id`='$id'");
return $query;
}
// public function getimagebyid($id)
// {
// $query=$this->db->query("SELECT `image` FROM `selftables_realtedblog` WHERE `id`='$id'")->row();
// return $query;
// }
// public function getdropdown()
// {
// $query=$this->db->query("SELECT * FROM `selftables_realtedblog` ORDER BY `id`
//                     ASC")->row();
// $return=array(
// "" => "Select Option"
// );
// foreach($query as $row)
// {
// $return[$row->id]=$row->name;
// }
// return $return;
// }

public function getblogdropdown()
{
$query=$this->db->query("SELECT * FROM `tingblog_blog`  ORDER BY `id` ASC")->result();
$return=array(
"" => "Choose an option"
);
foreach($query as $row)
{
$return[$row->id]=$row->name;
}

return $return;
}
}
?>
