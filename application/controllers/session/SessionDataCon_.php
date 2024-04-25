<?php 
/* Location: ./application/controllers/session/session_data_page.php */
class SessionDataCon extends CI_Controller
{
	public function index()
	{	
		$this->form_validation->set_rules('txtUserId', 'USER NAME', 'required');
		$this->form_validation->set_rules('txtPassword', 'PASSWORD', 'required');
		$this->form_validation->set_error_delimiters('<div class="error">', '</div>');
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('welcomeView');
		}
		else
		{
			$userName	=	$this->input->post("txtUserId", TRUE);
			$password	=	$this->input->post("txtPassword", TRUE);
			$this->load->model('sessionData/SessionDataModel');
			$this->SessionDataModel->checkUser($userName,$password);
		}
	}
}
?>