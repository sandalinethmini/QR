<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include "application/controllers/header/Header_page.php";

class UserDetails extends Header_page {
	public function __construct()
	{		
		parent::__construct();
		$this->load->model('masterData/UserModel');
	}
	 
	public function index()
	{
		if($this->headerMenu(2))
		{
			//$data['location'] =	$this->UserModel->loadArea();
			//$data['subLocation'] =	$this->UserModel->loadSubArea("");
			
			$data['pages'] =	$this->UserModel->getPages();
       		$this->load->view('masterData/UserView',$data);
			$this->footerMenu();
		}
		else
			header( 'Location: '.base_url() ) ;		
	}
	
	public function loadPages()
	{
		$recordId		= 	trim($_POST['recordId']);
		$page_data = $this->UserModel->getPagerDetails($recordId);
		
		if($page_data){
		
			foreach ($page_data as $results)
			{
				 $page_arr[] = array('page_id' 	=> $results->access_page_id);	
			}	
		}
		else
		{
			//$page_arr[]= "";
		}
        echo json_encode($page_arr);	
	}
	
	public function recordlist()
	{
		$list = $this->UserModel->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $items) 
		{
			$no++;
			$row = array();
			$row[] = $no;
			$row[] = $items->branch_code.' - '.$items->branch_name;
			$row[] = $items->branch_email;
			
			$row[] = $items->branch_status;
			
			$row[] = $items->branch_id;
			$row[] = $items->branch_code;	
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->UserModel->count_all(),
						"recordsFiltered" => $this->UserModel->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}
	
	public function saveRecord()
	{
		$userId		=	$this->input->post("txtUserId", TRUE);
		//$cmbLocationCode=	$this->input->post("cmbLocationCode", TRUE);
		
		if($userId)
		{
			$this->form_validation->set_rules('txtEmpNo', 'User Name', 'required]');
			//$this->form_validation->set_rules('txtUserCode', 'User Name', 'required]');
		}
		else
		{
			$this->form_validation->set_rules('txtEmpNo', 'User Name', 'required|is_unique[system_users.branch_email]');
			$this->form_validation->set_rules('txtUserCode', 'User Code', 'required|is_unique[system_users.branch_code]');
		}
		
		$this->form_validation->set_rules('txtFullName', ' Full Name', 'required');
		//$this->form_validation->set_rules('cmbLocationCode', ' Working Factory', 'required');
		
		$this->form_validation->set_error_delimiters('<div class="text-danger"><small>', '</small></div>');
		
		if ($this->form_validation->run() == FALSE)
		{
			if($this->headerMenu(2))
			{
				//$data['location'] =	$this->UserModel->loadArea();
				//$data['subLocation'] =	$this->UserModel->loadSubArea($cmbLocationCode);
				$data['pages'] =	$this->UserModel->getPages();
       			$this->load->view('masterData/UserView',$data);
				$this->footerMenu();
			}
			else
				header( 'Location: '.base_url() ) ;	
		}
		else
		{
			if($this->headerMenu(2))
			{
				$userName	=	$this->input->post("txtEmpNo", TRUE);
				$code	=	$this->input->post("txtUserCode", TRUE);
				$fullName	=	$this->input->post("txtFullName", TRUE);
				//$userId		=	$this->input->post("txtUserId", TRUE);
				//*$cmbLocationCode=	$this->input->post("cmbSubLocation", TRUE);
				$pagesList 	=	$this->input->post('page[]',TRUE);
			
				if($userId)
				{
					$this->UserModel->updateRecord($userId,$fullName,$pagesList,$code);
				}
				else
				{
					$this->UserModel->saveRecord($userName,$fullName,$pagesList,$code);
				}			
			}
			else
				header( 'Location: '.base_url() ) ;	
		}		
	}
	
	public function changeStatus()
	{
		$userId			= 	trim($_POST['recordId']);
		$userStatus		= 	trim($_POST['status']);
		$this->UserModel->changeStatus($userId,$userStatus);	
	}
	public function changePassword()
	{
		$userId			= 	trim($_POST['recordId']);
		$userName		= 	trim($_POST['userName']);
		$this->UserModel->changePassword($userId,$userName);	
	}
	
	public function editRecord()
	{
		$userId			= 	trim($_POST['recordId']);
		$data['jsonData']	= $this->UserModel->editRecord($userId);
		echo json_encode($data);
	}
	
	/*public function getSubArea()
	{
		$areaCode = $_POST['areaCode'];	
		$area_data =	$this->UserModel->getSubArea($areaCode);	
        echo json_encode($area_data);	
	}	*/
}
