<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include "application/controllers/header/header_page.php";
class PdfReport extends Header_page {
	 
	public function __construct()
	{
		parent::__construct();
		$this->load->model('PdfReportModel', 'RenwalModel');
		date_default_timezone_set('Asia/Colombo');
	}
	 
	public function index()
	{
		if($this->headerMenu(12))
		{
			$data = array('form_valid' => false,'print_type' => "");
       		$this->load->view('PdfReportView',$data);
		}
		else
			header( 'Location: '.base_url() ) ;		
	}
	
	
	public function printReport()
	{
		$selectedDate = $this->input->post("txtSelectedDate", TRUE);
		$print_type = $this->input->post('print_type', TRUE);
	
		if ($print_type == "print_pdf") {
			$this->RenwalModel->printPDF($selectedDate);
		} else if ($print_type == "print_excel") {
			$this->RenwalModel->printExcel($selectedDate);
		}
	}
	

	public function getValidation()
	{
		$this->form_validation->set_rules('txtSelectedDate', 'Selected Date', 'required', array('required' => '*Date Required'));
		$action = $this->input->post('action',TRUE);
	   
	   $print_type = "";
        if($action == 'view_PDF') {
            $print_type = "print_pdf";
        }
        if($action == 'view_Excel') {
            $print_type = "print_excel";
        }

		$this->form_validation->set_error_delimiters('<span class="spanError">', '</span>');
		
		if ($this->form_validation->run() == FALSE){
			if($this->headerMenu(12))
		{
				$data = array('form_valid' => false,'print_type' => "");
				$this->load->view('PdfReportView',$data);
			}
			else{
				$this->session->set_flashdata('fail', 'Session Expired or Access Denined.');
                header( 'Location: '.base_url() ) ;
			}
		}
		else{

			if($this->headerMenu(12))
		{
				$data = array('form_valid' => true,'print_type' => $print_type);
				$this->load->view('PdfReportView',$data);
			}else{
				$this->session->set_flashdata('fail', 'Session Expired or Access Denined.');
                header( 'Location: '.base_url() ) ;
			}
		}		
	
	}
	

}