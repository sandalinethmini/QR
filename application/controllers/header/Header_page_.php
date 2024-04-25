<?php 
class Header_page extends CI_Controller 
{
	function __construct()
    {
		parent::__construct();
    }
	public function headerMenu($pageId)
	{
		$this->load->model('header/Header_model');
		$response_data	=	$this->Header_model->menuLoad();
		$pageLoadStatus	=	$this->Header_model->pageLoad($pageId);
		
		if($response_data!="" && $pageLoadStatus)
		{
			$this->load->view('header/HeaderView',$response_data);
			
			return true;
		}
		else
		{
			header( 'Location: '.base_url() ) ;
			return true;
		}

		
	}
	
	
	public function footerMenu()
	{
		$this->load->view('footer/Footer_view');
	}
}
?>