<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include "application/controllers/header/Header_page.php";

require_once APPPATH. "third_party\PHPExcel.php";

class DownloadExcel extends Header_page {
	public function __construct()
	{		
		parent::__construct();
        $this->load->model('DownloadExcelModel');
		date_default_timezone_set('Asia/Colombo');
		
	}


    public function index()
    {
        if($this->headerMenu(12))
		{
			$this->load->view('DownloadExcelView');
			$this->footerMenu();
        }
        else
        {
            $this->session->set_flashdata('fail', 'Session Expired or Access Denined.');
            header( 'Location: '.base_url() ) ;
        }
    }

	public function SalvaBatch()
{
    $this->form_validation->set_rules('txtFromDate', 'From Date', 'required', array('required' => '* Please Pick Date'));
    $this->form_validation->set_rules('txtToDate', 'To Date', 'required', array('required' => '* Please Pick Date '));
    $this->form_validation->set_rules('response_code', 'Response Code', 'required', array('required' => '* Please select a Response Code'));

    $this->form_validation->set_error_delimiters('<span class="invalid-feedback" style="display:block">', '</span>');

    if ($this->headerMenu(12)) {
        if ($this->form_validation->run() == FALSE) {
            $this->load->view('DownloadExcelView');
            $this->footerMenu();
        } else {
            $fromDate = $this->input->post("txtFromDate", TRUE);
            $toDate = $this->input->post("txtToDate", TRUE);
            $responseCode = $this->input->post('response_code');

            switch ($responseCode) {

                case 'All':
                    $records = $this->DownloadExcelModel->getRecordsResponseCodeAll($fromDate, $toDate, $responseCode);
                    break;
                case '1':
                    $records = $this->DownloadExcelModel->getRecordsResponseCode1($fromDate, $toDate, $responseCode);
                    break;

                case '2':
                    $records = $this->DownloadExcelModel->getRecordsResponseCode2($fromDate, $toDate, $responseCode);
                    break;

                case '3':
                    $records = $this->DownloadExcelModel->getRecordsResponseCode3($fromDate, $toDate, $responseCode);
                    break;
            }
        }
    } else {
        $this->session->set_flashdata('fail', 'Session Expired or Access Denied.');
        header('Location: ' . base_url());
    }
}
  
}
