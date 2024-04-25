<?php 
include "application/controllers/header/Header_page.php";
class Home extends Header_page 
{
	public function __construct()
	{
		parent::__construct();
		
	}
	
	public function index()
	{	
		if($this->headerMenu(-100))
		{
			
			$this->load->view('HomeView');
			$this->footerMenu();
		}
	}
	

	
}
?>