<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Site extends CI_Controller
{
	public function __construct( )
	{
		parent::__construct();

		$this->is_logged_in();
	}
	function is_logged_in( )
	{
		$is_logged_in = $this->session->userdata( 'logged_in' );
		if ( $is_logged_in !== 'true' || !isset( $is_logged_in ) ) {
			redirect( base_url() . 'index.php/login', 'refresh' );
		} //$is_logged_in !== 'true' || !isset( $is_logged_in )
	}
	function checkaccess($access)
	{
		$accesslevel=$this->session->userdata('accesslevel');
		if(!in_array($accesslevel,$access))
			redirect( base_url() . 'index.php/site?alerterror=You do not have access to this page. ', 'refresh' );
	}
    public function getOrderingDone()
    {
        $orderby=$this->input->get("orderby");
        $ids=$this->input->get("ids");
        $ids=explode(",",$ids);
        $tablename=$this->input->get("tablename");
        $where=$this->input->get("where");
        if($where == "" || $where=="undefined")
        {
            $where=1;
        }
        $access = array(
            '1',
        );
        $this->checkAccess($access);
        $i=1;
        foreach($ids as $id)
        {
            //echo "UPDATE `$tablename` SET `$orderby` = '$i' WHERE `id` = `$id` AND $where";
            $this->db->query("UPDATE `$tablename` SET `$orderby` = '$i' WHERE `id` = '$id' AND $where");
            $i++;
            //echo "/n";
        }
        $data["message"]=true;
        $this->load->view("json",$data);

    }
	public function index()
	{
		$access = array("1","2");
		$this->checkaccess($access);
		$data[ 'page' ] = 'dashboard';
		$data[ 'title' ] = 'Welcome';
		$this->load->view( 'template', $data );
	}
	public function createuser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['accesslevel']=$this->user_model->getaccesslevels();
		$data[ 'status' ] =$this->user_model->getstatusdropdown();
		$data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
        $data['gender']=$this->user_model->getgenderdropdown();
//        $data['category']=$this->category_model->getcategorydropdown();
		$data[ 'page' ] = 'createuser';
		$data[ 'title' ] = 'Create User';
		$this->load->view( 'template', $data );
	}
	function createusersubmit()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email|is_unique[user.email]');
		$this->form_validation->set_rules('password','Password','trim|required|min_length[6]|max_length[30]');
		$this->form_validation->set_rules('confirmpassword','Confirm Password','trim|required|matches[password]');
		$this->form_validation->set_rules('accessslevel','Accessslevel','trim');
		$this->form_validation->set_rules('status','status','trim|');
		$this->form_validation->set_rules('socialid','Socialid','trim');
		$this->form_validation->set_rules('logintype','logintype','trim');
		$this->form_validation->set_rules('json','json','trim');
		if($this->form_validation->run() == FALSE)
		{
			$data['alerterror'] = validation_errors();
            $data['gender']=$this->user_model->getgenderdropdown();
			$data['accesslevel']=$this->user_model->getaccesslevels();
            $data[ 'status' ] =$this->user_model->getstatusdropdown();
            $data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
            $data[ 'page' ] = 'createuser';
            $data[ 'title' ] = 'Create User';
            $this->load->view( 'template', $data );
		}
		else
		{
            $name=$this->input->post('name');
            $email=$this->input->post('email');
            $password=$this->input->post('password');
            $accesslevel=$this->input->post('accesslevel');
            $status=$this->input->post('status');
            $socialid=$this->input->post('socialid');
            $logintype=$this->input->post('logintype');
            $json=$this->input->post('json');
            $firstname=$this->input->post('firstname');
            $lastname=$this->input->post('lastname');
            $phone=$this->input->post('phone');
            $billingaddress=$this->input->post('billingaddress');
            $billingcity=$this->input->post('billingcity');
            $billingstate=$this->input->post('billingstate');
            $billingcountry=$this->input->post('billingcountry');
            $billingpincode=$this->input->post('billingpincode');
            $billingcontact=$this->input->post('billingcontact');

            $shippingaddress=$this->input->post('shippingaddress');
            $shippingcity=$this->input->post('shippingcity');
            $shippingstate=$this->input->post('shippingstate');
            $shippingcountry=$this->input->post('shippingcountry');
            $shippingpincode=$this->input->post('shippingpincode');
            $shippingcontact=$this->input->post('shippingcontact');
            $shippingname=$this->input->post('shippingname');
            $currency=$this->input->post('currency');
            $credit=$this->input->post('credit');
            $companyname=$this->input->post('companyname');
            $registrationno=$this->input->post('registrationno');
            $vatnumber=$this->input->post('vatnumber');
            $country=$this->input->post('country');
            $fax=$this->input->post('fax');
            $gender=$this->input->post('gender');

            $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="image";
			$image="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image=$uploaddata['file_name'];

                $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r);
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $image=$this->image_lib->dest_image;
                    //return false;
                }

			}

			if($this->user_model->create($name,$email,$password,$accesslevel,$status,$socialid,$logintype,$image,$json,$firstname,$lastname,$phone,$billingaddress,$billingcity,$billingstate,$billingcountry,$billingpincode,$billingcontact,$shippingaddress,$shippingcity,$shippingstate,$shippingcountry,$shippingpincode,$shippingcontact,$shippingname,$currency,$credit,$companyname,$registrationno,$vatnumber,$country,$fax,$gender)==0)
			$data['alerterror']="New user could not be created.";
			else
			$data['alertsuccess']="User created Successfully.";
			$data['redirect']="site/viewusers";
			$this->load->view("redirect",$data);
		}
	}
    function viewusers()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data['page']='viewusers';
        $data['base_url'] = site_url("site/viewusersjson");

		$data['title']='View Users';
		$this->load->view('template',$data);
	}
    function viewusersjson()
	{
		$access = array("1");
		$this->checkaccess($access);


        $elements=array();
        $elements[0]=new stdClass();
        $elements[0]->field="`user`.`id`";
        $elements[0]->sort="1";
        $elements[0]->header="ID";
        $elements[0]->alias="id";


        $elements[1]=new stdClass();
        $elements[1]->field="`user`.`name`";
        $elements[1]->sort="1";
        $elements[1]->header="Name";
        $elements[1]->alias="name";

        $elements[2]=new stdClass();
        $elements[2]->field="`user`.`email`";
        $elements[2]->sort="1";
        $elements[2]->header="Email";
        $elements[2]->alias="email";

        $elements[3]=new stdClass();
        $elements[3]->field="`user`.`socialid`";
        $elements[3]->sort="1";
        $elements[3]->header="SocialId";
        $elements[3]->alias="socialid";

        $elements[4]=new stdClass();
        $elements[4]->field="`user`.`logintype`";
        $elements[4]->sort="1";
        $elements[4]->header="Logintype";
        $elements[4]->alias="logintype";

        $elements[5]=new stdClass();
        $elements[5]->field="`user`.`json`";
        $elements[5]->sort="1";
        $elements[5]->header="Json";
        $elements[5]->alias="json";

        $elements[6]=new stdClass();
        $elements[6]->field="`accesslevel`.`name`";
        $elements[6]->sort="1";
        $elements[6]->header="Accesslevel";
        $elements[6]->alias="accesslevelname";

        $elements[7]=new stdClass();
        $elements[7]->field="`statuses`.`name`";
        $elements[7]->sort="1";
        $elements[7]->header="Status";
        $elements[7]->alias="status";


        $search=$this->input->get_post("search");
        $pageno=$this->input->get_post("pageno");
        $orderby=$this->input->get_post("orderby");
        $orderorder=$this->input->get_post("orderorder");
        $maxrow=$this->input->get_post("maxrow");
        if($maxrow=="")
        {
            $maxrow=20;
        }

        if($orderby=="")
        {
            $orderby="id";
            $orderorder="ASC";
        }

        $data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `user` LEFT OUTER JOIN `logintype` ON `logintype`.`id`=`user`.`logintype` LEFT OUTER JOIN `accesslevel` ON `accesslevel`.`id`=`user`.`accesslevel` LEFT OUTER JOIN `statuses` ON `statuses`.`id`=`user`.`status`");

		$this->load->view("json",$data);
	}


	function edituser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$data[ 'status' ] =$this->user_model->getstatusdropdown();
        $data["before1"]=$this->input->get('id');
        $data["before2"]=$this->input->get('id');
        $data["before3"]=$this->input->get('id');
        $data["before4"]=$this->input->get('id');
        $data["before5"]=$this->input->get('id');
		$data['accesslevel']=$this->user_model->getaccesslevels();
		$data['gender']=$this->user_model->getgenderdropdown();
		$data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
		$data['before']=$this->user_model->beforeedit($this->input->get('id'));
		$data['page']='edituser';
		$data['page2']='block/userblock';
		$data['title']='Edit User';
		$this->load->view('templatewith2',$data);
	}
	function editusersubmit()
	{
		$access = array("1");
		$this->checkaccess($access);

		$this->form_validation->set_rules('name','Name','trim|required|max_length[30]');
		$this->form_validation->set_rules('email','Email','trim|required|valid_email');
		$this->form_validation->set_rules('password','Password','trim|min_length[6]|max_length[30]');
		$this->form_validation->set_rules('confirmpassword','Confirm Password','trim|matches[password]');
		$this->form_validation->set_rules('accessslevel','Accessslevel','trim');
		$this->form_validation->set_rules('status','status','trim|');
		$this->form_validation->set_rules('socialid','Socialid','trim');
		$this->form_validation->set_rules('logintype','logintype','trim');
		$this->form_validation->set_rules('json','json','trim');
		if($this->form_validation->run() == FALSE)
		{
			$data['alerterror'] = validation_errors();
			$data[ 'status' ] =$this->user_model->getstatusdropdown();
            $data['gender']=$this->user_model->getgenderdropdown();
			$data['accesslevel']=$this->user_model->getaccesslevels();
            $data[ 'logintype' ] =$this->user_model->getlogintypedropdown();
			$data['before']=$this->user_model->beforeedit($this->input->post('id'));
			$data['page']='edituser';
//			$data['page2']='block/userblock';
			$data['title']='Edit User';
			$this->load->view('template',$data);
		}
		else
		{

            $id=$this->input->get_post('id');
            $name=$this->input->get_post('name');
            $email=$this->input->get_post('email');
            $password=$this->input->get_post('password');
            $accesslevel=$this->input->get_post('accesslevel');
            $status=$this->input->get_post('status');
            $socialid=$this->input->get_post('socialid');
            $logintype=$this->input->get_post('logintype');
            $json=$this->input->get_post('json');
//            $category=$this->input->get_post('category');
            $firstname=$this->input->post('firstname');
            $lastname=$this->input->post('lastname');
            $phone=$this->input->post('phone');
            $billingaddress=$this->input->post('billingaddress');
            $billingcity=$this->input->post('billingcity');
            $billingstate=$this->input->post('billingstate');
            $billingcountry=$this->input->post('billingcountry');
            $billingpincode=$this->input->post('billingpincode');
            $billingcontact=$this->input->post('billingcontact');

            $shippingaddress=$this->input->post('shippingaddress');
            $shippingcity=$this->input->post('shippingcity');
            $shippingstate=$this->input->post('shippingstate');
            $shippingcountry=$this->input->post('shippingcountry');
            $shippingpincode=$this->input->post('shippingpincode');
            $shippingcontact=$this->input->post('shippingcontact');
            $shippingname=$this->input->post('shippingname');
            $currency=$this->input->post('currency');
            $credit=$this->input->post('credit');
            $companyname=$this->input->post('companyname');
            $registrationno=$this->input->post('registrationno');
            $vatnumber=$this->input->post('vatnumber');
            $country=$this->input->post('country');
            $fax=$this->input->post('fax');
            $gender=$this->input->post('gender');
            $config['upload_path'] = './uploads/';
			$config['allowed_types'] = 'gif|jpg|png|jpeg';
			$this->load->library('upload', $config);
			$filename="image";
			$image="";
			if (  $this->upload->do_upload($filename))
			{
				$uploaddata = $this->upload->data();
				$image=$uploaddata['file_name'];

                $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
                $config_r['maintain_ratio'] = TRUE;
                $config_t['create_thumb'] = FALSE;///add this
                $config_r['width']   = 800;
                $config_r['height'] = 800;
                $config_r['quality']    = 100;
                //end of configs

                $this->load->library('image_lib', $config_r);
                $this->image_lib->initialize($config_r);
                if(!$this->image_lib->resize())
                {
                    echo "Failed." . $this->image_lib->display_errors();
                    //return false;
                }
                else
                {
                    //print_r($this->image_lib->dest_image);
                    //dest_image
                    $image=$this->image_lib->dest_image;
                    //return false;
                }

			}

            if($image=="")
            {
            $image=$this->user_model->getuserimagebyid($id);
               // print_r($image);
                $image=$image->image;
            }

			if($this->user_model->edit($id,$name,$email,$password,$accesslevel,$status,$socialid,$logintype,$image,$json,$firstname,$lastname,$phone,$billingaddress,$billingcity,$billingstate,$billingcountry,$billingpincode,$billingcontact,$shippingaddress,$shippingcity,$shippingstate,$shippingcountry,$shippingpincode,$shippingcontact,$shippingname,$currency,$credit,$companyname,$registrationno,$vatnumber,$country,$fax,$gender)==0)
			$data['alerterror']="User Editing was unsuccesful";
			else
			$data['alertsuccess']="User edited Successfully.";

			$data['redirect']="site/viewusers";
			//$data['other']="template=$template";
			$this->load->view("redirect",$data);

		}
	}

	function deleteuser()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->user_model->deleteuser($this->input->get('id'));
//		$data['table']=$this->user_model->viewusers();
		$data['alertsuccess']="User Deleted Successfully";
		$data['redirect']="site/viewusers";
			//$data['other']="template=$template";
		$this->load->view("redirect",$data);
	}
	function changeuserstatus()
	{
		$access = array("1");
		$this->checkaccess($access);
		$this->user_model->changestatus($this->input->get('id'));
		$data['table']=$this->user_model->viewusers();
		$data['alertsuccess']="Status Changed Successfully";
		$data['redirect']="site/viewusers";
        $data['other']="template=$template";
        $this->load->view("redirect",$data);
	}
    public function viewcart()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewcart";
    $data["before1"]=$this->input->get('id');
        $data["before2"]=$this->input->get('id');
        $data["before3"]=$this->input->get('id');
        $data["before4"]=$this->input->get('id');
        $data["before5"]=$this->input->get('id');
$data['page2']='block/userblock';
$data["base_url"]=site_url("site/viewcartjson?id=").$this->input->get('id');
$data["title"]="View cart";
$this->load->view("templatewith2",$data);
}
function viewcartjson()
{
    $id=$this->input->get('id');
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`fynx_cart`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`fynx_cart`.`user`";
$elements[1]->sort="1";
$elements[1]->header="User";
$elements[1]->alias="user";
$elements[2]=new stdClass();
$elements[2]->field="`fynx_cart`.`quantity`";
$elements[2]->sort="1";
$elements[2]->header="Quantity";
$elements[2]->alias="quantity";
$elements[3]=new stdClass();
$elements[3]->field="`fynx_cart`.`product`";
$elements[3]->sort="1";
$elements[3]->header="Product";
$elements[3]->alias="product";
$elements[4]=new stdClass();
$elements[4]->field="`fynx_cart`.`timestamp`";
$elements[4]->sort="1";
$elements[4]->header="Timestamp";
$elements[4]->alias="timestamp";

$elements[5]=new stdClass();
$elements[5]->field="`fynx_cart`.`size`";
$elements[5]->sort="1";
$elements[5]->header="Size";
$elements[5]->alias="size";

$elements[6]=new stdClass();
$elements[6]->field="`fynx_cart`.`color`";
$elements[6]->sort="1";
$elements[6]->header="Color";
$elements[6]->alias="color";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `fynx_cart`","WHERE `fynx_cart`.`user`='$id'");
$this->load->view("json",$data);
}
    public function viewwishlist()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewwishlist";
    $data["before1"]=$this->input->get('id');
        $data["before2"]=$this->input->get('id');
        $data["before3"]=$this->input->get('id');
        $data["before4"]=$this->input->get('id');
        $data["before5"]=$this->input->get('id');
$data['page2']='block/userblock';
$data["base_url"]=site_url("site/viewwishlistjson?id=".$this->input->get('id'));
$data["title"]="View wishlist";
$this->load->view("templatewith2",$data);
}
function viewwishlistjson()
{
    $user=$this->input->get('id');
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`fynx_wishlist`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`fynx_wishlist`.`user`";
$elements[1]->sort="1";
$elements[1]->header="User";
$elements[1]->alias="user";
$elements[2]=new stdClass();
$elements[2]->field="`fynx_wishlist`.`product`";
$elements[2]->sort="1";
$elements[2]->header="Product";
$elements[2]->alias="product";
$elements[3]=new stdClass();
$elements[3]->field="`fynx_wishlist`.`timestamp`";
$elements[3]->sort="1";
$elements[3]->header="Timestamp";
$elements[3]->alias="timestamp";

$elements[4]=new stdClass();
$elements[4]->field="`fynx_product`.`name`";
$elements[4]->sort="1";
$elements[4]->header="Product Name";
$elements[4]->alias="productname";
$search=$this->input->get_post("search");
$pageno=$this->input->get_post("pageno");
$orderby=$this->input->get_post("orderby");
$orderorder=$this->input->get_post("orderorder");
$maxrow=$this->input->get_post("maxrow");
if($maxrow=="")
{
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `fynx_wishlist` LEFT OUTER JOIN `fynx_product` ON `fynx_product`.`id`=`fynx_wishlist`.`product`","WHERE `fynx_wishlist`.`user`='$user'");
$this->load->view("json",$data);
}



    public function viewblog()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewblog";
$data["base_url"]=site_url("site/viewblogjson");
$data["title"]="View blog";
$this->load->view("template",$data);
}
function viewblogjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`tingblog_blog`.`id`";
$elements[0]->sort="1";
$elements[0]->header="ID";
$elements[0]->alias="id";
$elements[1]=new stdClass();
$elements[1]->field="`tingblog_blog`.`name`";
$elements[1]->sort="1";
$elements[1]->header="name";
$elements[1]->alias="name";
$elements[2]=new stdClass();
$elements[2]->field="`tingblog_blog`.`image`";
$elements[2]->sort="1";
$elements[2]->header="image";
$elements[2]->alias="image";
$elements[3]=new stdClass();
$elements[3]->field="`tingblog_blog`.`video`";
$elements[3]->sort="1";
$elements[3]->header="video";
$elements[3]->alias="video";
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
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `tingblog_blog`");
$this->load->view("json",$data);
}

public function createblog()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createblog";
$data["title"]="Create blog";
$this->load->view("template",$data);
}
public function createblogsubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("name","name","trim");
$this->form_validation->set_rules("image","image","trim");
$this->form_validation->set_rules("video","video","trim");
$this->form_validation->set_rules("description","description","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createblog";
$data["title"]="Create blog";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$name=$this->input->get_post("name");
// $image=$this->input->get_post("image");
$video=$this->input->get_post("video");
$description=$this->input->get_post("description");
$config['upload_path'] = './uploads/';
		 $config['allowed_types'] = 'gif|jpg|png|jpeg';
		 $this->load->library('upload', $config);
		 $filename="image";
		 $image="";
		 if (  $this->upload->do_upload($filename))
		 {
			 $uploaddata = $this->upload->data();
			 $image=$uploaddata['file_name'];

							 $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
							 $config_r['maintain_ratio'] = TRUE;
							 $config_t['create_thumb'] = FALSE;///add this
							 // $config_r['width']   = 800;
							 // $config_r['height'] = 800;
							 $config_r['quality']    = 100;
							 //end of configs
							 $this->load->library('image_lib', $config_r);
							 $this->image_lib->initialize($config_r);
							 if(!$this->image_lib->resize())
							 {
									 echo "Failed." . $this->image_lib->display_errors();
									 //return false;
							 }
							 else
							 {
									 //print_r($this->image_lib->dest_image);
									 //dest_image
									 $image=$this->image_lib->dest_image;
									 //return false;
							 }

		 }
if($this->blog_model->create($name,$image,$video,$description)==0)
$data["alerterror"]="New blog could not be created.";
else
$data["alertsuccess"]="blog created Successfully.";
$data["redirect"]="site/viewblog";
$this->load->view("redirect",$data);
}
}
public function editblog()
{
	$data['page'] = 'editblog';
	$data['page2'] = 'block/blogblock';
	$data['tag'] = $this->tags_model->gettagdropdown();
	$data['before1'] = $this->input->get('id');
	$data['before2'] = $this->input->get('id');
	$data['title'] = 'Edit blog';

	$data['tag'] = $this->tags_model->gettagdropdown();
	$data['before'] = $this->blog_model->beforeedit($this->input->get('id'));
// $this->load->view("template",$data);
$this->load->view('templatewith2', $data);

}
public function editblogsubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","ID","trim");
$this->form_validation->set_rules("name","name","trim");
$this->form_validation->set_rules("image","image","trim");
$this->form_validation->set_rules("video","video","trim");
$this->form_validation->set_rules("description","description","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editblog";
$data["title"]="Edit blog";
$data["before"]=$this->blog_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$name=$this->input->get_post("name");
// $image=$this->input->get_post("image");
$video=$this->input->get_post("video");
$description=$this->input->get_post("description");
$config['upload_path'] = './uploads/';
		 $config['allowed_types'] = 'gif|jpg|png|jpeg';
		 $this->load->library('upload', $config);
		 $filename="image";
		 $image="";
		 if (  $this->upload->do_upload($filename))
		 {
			 $uploaddata = $this->upload->data();
			 $image=$uploaddata['file_name'];

							 $config_r['source_image']   = './uploads/' . $uploaddata['file_name'];
							 $config_r['maintain_ratio'] = TRUE;
							 $config_t['create_thumb'] = FALSE;///add this
							 // $config_r['width']   = 800;
							 // $config_r['height'] = 800;
							 $config_r['quality']    = 100;
							 //end of configs

							 $this->load->library('image_lib', $config_r);
							 $this->image_lib->initialize($config_r);
							 if(!$this->image_lib->resize())
							 {
									 echo "Failed." . $this->image_lib->display_errors();
									 //return false;
							 }
							 else
							 {
									 //print_r($this->image_lib->dest_image);
									 //dest_image
									 $image=$this->image_lib->dest_image;
									 //return false;
							 }

		 }

					 if($image=="")
					 {
					 $image=$this->productimage_model->getImageById($id);
							// print_r($image);
							 $image=$image->image;
					 }

if($this->blog_model->edit($id,$name,$image,$video,$description)==0)
$data["alerterror"]="New blog could not be Updated.";
else
$data["alertsuccess"]="blog Updated Successfully.";
$data["redirect"]="site/viewblog";
$this->load->view("redirect",$data);
}
}
public function deleteblog()
{
$access=array("1");
$this->checkaccess($access);
$this->blog_model->delete($this->input->get("id"));
$data["redirect"]="site/viewblog";
$this->load->view("redirect",$data);
}
public function viewtags()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewtags";
$data["base_url"]=site_url("site/viewtagsjson");
$data["title"]="View tags";
$this->load->view("template",$data);
}
function viewtagsjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`tingblog_tags`.`id`";
$elements[0]->sort="1";
$elements[0]->header="id";
$elements[0]->alias="id";
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
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `tingblog_tags`");
$this->load->view("json",$data);
}

public function createtags()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="createtags";
$data["title"]="Create tags";
$this->load->view("template",$data);
}
public function createtagssubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("name","name","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="createtags";
$data["title"]="Create tags";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$name=$this->input->get_post("name");
if($this->tags_model->create($name)==0)
$data["alerterror"]="New tags could not be created.";
else
$data["alertsuccess"]="tags created Successfully.";
$data["redirect"]="site/viewtags";
$this->load->view("redirect",$data);
}
}
public function edittags()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="edittags";
$data["title"]="Edit tags";
$data["before"]=$this->tags_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function edittagssubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","id","trim");
$this->form_validation->set_rules("name","name","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="edittags";
$data["title"]="Edit tags";
$data["before"]=$this->tags_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$name=$this->input->get_post("name");
if($this->tags_model->edit($id,$name)==0)
$data["alerterror"]="New tags could not be Updated.";
else
$data["alertsuccess"]="tags Updated Successfully.";
$data["redirect"]="site/viewtags";
$this->load->view("redirect",$data);
}
}
public function deletetags()
{
$access=array("1");
$this->checkaccess($access);
$this->tags_model->delete($this->input->get("id"));
$data["redirect"]="site/viewtags";
$this->load->view("redirect",$data);
}
public function viewgif()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="viewgif";
$data["base_url"]=site_url("site/viewgifjson");
$data["title"]="View gif";
$this->load->view("template",$data);
}
function viewgifjson()
{
$elements=array();
$elements[0]=new stdClass();
$elements[0]->field="`tingblog_gif`.`id`";
$elements[0]->sort="1";
$elements[0]->header="id";
$elements[0]->alias="id";
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
$maxrow=20;
}
if($orderby=="")
{
$orderby="id";
$orderorder="ASC";
}
$data["message"]=$this->chintantable->query($pageno,$maxrow,$orderby,$orderorder,$search,$elements,"FROM `tingblog_gif`");
$this->load->view("json",$data);
}

public function creategif()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="creategif";
$data["title"]="Create gif";
$this->load->view("template",$data);
}
public function creategifsubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("name","name","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="creategif";
$data["title"]="Create gif";
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$name=$this->input->get_post("name");
if($this->gif_model->create($name)==0)
$data["alerterror"]="New gif could not be created.";
else
$data["alertsuccess"]="gif created Successfully.";
$data["redirect"]="site/viewgif";
$this->load->view("redirect",$data);
}
}
public function editgif()
{
$access=array("1");
$this->checkaccess($access);
$data["page"]="editgif";
$data["title"]="Edit gif";
$data["before"]=$this->gif_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
public function editgifsubmit()
{
$access=array("1");
$this->checkaccess($access);
$this->form_validation->set_rules("id","id","trim");
$this->form_validation->set_rules("name","name","trim");
if($this->form_validation->run()==FALSE)
{
$data["alerterror"]=validation_errors();
$data["page"]="editgif";
$data["title"]="Edit gif";
$data["before"]=$this->gif_model->beforeedit($this->input->get("id"));
$this->load->view("template",$data);
}
else
{
$id=$this->input->get_post("id");
$name=$this->input->get_post("name");
if($this->gif_model->edit($id,$name)==0)
$data["alerterror"]="New gif could not be Updated.";
else
$data["alertsuccess"]="gif Updated Successfully.";
$data["redirect"]="site/viewgif";
$this->load->view("redirect",$data);
}
}
public function deletegif()
{
$access=array("1");
$this->checkaccess($access);
$this->gif_model->delete($this->input->get("id"));
$data["redirect"]="site/viewgif";
$this->load->view("redirect",$data);
}


public function viewblogtag()
{
		$access = array('1');
		$this->checkaccess($access);
		$data['page'] = 'viewblogtag';
		$data['page2'] = 'block/blogblock';
		$data['before1'] = $this->input->get('id');
		$data['before2'] = $this->input->get('id');
		$data['base_url'] = site_url('site/viewblogtagjson?id=').$this->input->get('id');
		$data['title'] = 'View Blog Tag';
		$this->load->view('templatewith2', $data);
}
public function viewblogtagjson()
{
		$id = $this->input->get('id');

		$elements = array();
		$elements[0] = new stdClass();
		$elements[0]->field = '`tagsblog`.`id`';
		$elements[0]->sort = '1';
		$elements[0]->header = 'id';
		$elements[0]->alias = 'id';
		$elements[1] = new stdClass();
		$elements[1]->field = '`tagsblog`.`blog`';
		$elements[1]->sort = '1';
		$elements[1]->header = 'blog';
		$elements[1]->alias = 'blog';

		$elements[2] = new stdClass();
		$elements[2]->field = '`tagsblog`.`tag`';
		$elements[2]->sort = '1';
		$elements[2]->header = 'tag';
		$elements[2]->alias = 'tag';

		$elements[3] = new stdClass();
		$elements[3]->field = '`tingblog_tags`.`name`';
		$elements[3]->sort = '1';
		$elements[3]->header = 'name';
		$elements[3]->alias = 'name';

		$elements[4] = new stdClass();
		$elements[4]->field = '`tagsblog`.`blog`';
		$elements[4]->sort = '1';
		$elements[4]->header = 'blogid';
		$elements[4]->alias = 'blogid';

		$search = $this->input->get_post('search');
		$pageno = $this->input->get_post('pageno');
		$orderby = $this->input->get_post('orderby');
		$orderorder = $this->input->get_post('orderorder');
		$maxrow = $this->input->get_post('maxrow');
		if ($maxrow == '') {
				$maxrow = 20;
		}
		if ($orderby == '') {
				$orderby = 'id';
				$orderorder = 'ASC';
		}
		$data['message'] = $this->chintantable->query($pageno, $maxrow, $orderby, $orderorder, $search, $elements, 'FROM `tagsblog` LEFT OUTER JOIN `tingblog_tags` on `tagsblog`.`tag` =`tingblog_tags`.`id`', "WHERE `tagsblog`.`blog`='$id'");
		$this->load->view('json', $data);
}

public function createblogtag()
{
		$access = array('1');
		$this->checkaccess($access);
		$data['page'] = 'createblogtag';
		$data['page2'] = 'block/blogblock';
		$data['title'] = 'Create Blogtags';
		$data['before1'] = $this->input->get('id');
		$data['before2'] = $this->input->get('id');
		$data['blog'] = $this->blogtag_model->getblogdropdown();
		$data['relatedtag'] = $this->tags_model->gettagdropdown();
		$this->load->view('templatewith2', $data);
}
public function createblogtagsubmit()
{
		$access = array('1');
		$this->checkaccess($access);
		$this->form_validation->set_rules('blog', 'blog', 'trim');
		if ($this->form_validation->run() == false) {
				$data['alerterror'] = validation_errors();
				$data['page'] = 'createblogtag';
				$data['title'] = 'Create Blog Tags';
				$this->load->view('template', $data);
		} else {
				$relatedtag = $this->input->get_post('relatedtag');
				$blog = $this->input->get_post('blog');
				if ($this->blogtag_model->create($relatedtag, $blog) == 0) {
						$data['alerterror'] = 'New Blog Tag could not be created.';
				} else {
						$data['alertsuccess'] = 'Blog Tag created Successfully.';
				}
				$data['redirect'] = 'site/viewblogtag?id='.$blog;
				$this->load->view('redirect2', $data);
		}
}
public function editblogtag()
{
		$access = array('1');
		$this->checkaccess($access);
		$data['blog'] = $this->blogtag_model->getblogdropdown();
		$data['relatedtag'] = $this->tags_model->gettagdropdown();
		$data['page'] = 'editblogtag';
		$data['page2'] = 'block/blogblock';
		$data['title'] = 'Edit Blog Tag';
		$data['before'] = $this->blogtag_model->beforeedit($this->input->get('id'));
		$data['before1'] = $this->input->get('id');
		$data['before2'] = $this->input->get('id');
		$this->load->view('templatewith2', $data);
}
public function editblogtagsubmit()
{
		$access = array('1');
		$this->checkaccess($access);
		$this->form_validation->set_rules('id', 'id', 'trim');
		$this->form_validation->set_rules('blog', 'blog', 'trim');
		if ($this->form_validation->run() == false) {
				$data['alerterror'] = validation_errors();
				$data['blog'] = $this->realtedblog_model->getblogdropdown();
				$data['relatedblog'] = $this->realtedblog_model->getblogdropdown();
				$data['page'] = 'editblogtag';
				$data['title'] = 'Edit realtedblog';
				$data['before'] = $this->realtedblog_model->beforeedit($this->input->get('id'));
				$this->load->view('templatewith2', $data);
		} else {
				$id = $this->input->get_post('id');
				$relatedtag = $this->input->get_post('relatedtag');
				$blog = $this->input->get_post('blog');
				if ($this->blogtag_model->edit($id, $relatedtag, $blog) == 0) {
						$data['alerterror'] = 'New Blogtag could not be Updated.';
				} else {
						$data['alertsuccess'] = 'Blogtag Updated Successfully.';
				}
				$data['redirect'] = 'site/viewblogtag?id='.$blog;
				$this->load->view('redirect2', $data);
		}
}
public function deleteblogtag()
{
		$access = array('1');
		$this->checkaccess($access);
		$this->blogtag_model->delete($this->input->get('id'));
		$data['redirect'] = 'site/viewblogtag?id='.$this->input->get('blogid');
		$this->load->view('redirect', $data);
	}
}
?>
