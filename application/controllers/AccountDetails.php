<?php
include "application/controllers/header/Header_page.php";
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . 'third_party/PHPExcel.php';

class AccountDetails extends Header_page {

    function __construct() {
        parent::__construct();
        $this->load->model('AccountDetailsModel');
        $this->load->library('form_validation');
    }

    public function index() {
        if ($this->headerMenu(11)) {
            $this->load->view('AccountDetailsView');
            $this->footerMenu();
        } else {
            header('Location: ' . base_url());
        }
    }

    public function recordlist() {
     
            	$searchValue = $this->input->post("searchText", true);
				$fromDate = $this->input->post("startDate", TRUE);
                $toDate = $this->input->post("endDate", TRUE);
            	$list = $this->AccountDetailsModel->get_datatables($fromDate,$toDate,$searchValue);
            	$data = array();
            	$no = $_POST['start'];
            	foreach ($list as $item) {
               	 	$no++;
               		 $row = array();
			
					$row[] = $no;
					
					$row[] = $item->Account_Number;
					$row[] = $item->RRN;
					$row[] = number_format($item->Amount,2);
					$row[] = $item->Time;
					

					if($item->Response_Code==1){
						$row[] ="Success";
					}else if($item->Response_Code==2){
						$row[] ="Time out";
					}else if($item->Response_Code==3){
						$row[] ="Reversal";
					}else{
						$row[] ="";
					}
		
            		$row[] = $item->Second_Part;
	
					$data[] = $row;
				}
			
				$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => 0,
					"recordsFiltered" => 0,
					"data" => $data,
				);
				echo json_encode($output);
		
}

public function download_excel()
{
    $from_date = $this->input->post('txtFromDate');
    $to_date = $this->input->post('txtToDate');
    $search_value = $this->input->post('txtSearchValue');

	if (empty($from_date) || empty($to_date) || empty($search_value)) {
		$this->session->set_flashdata('error_message', 'Please enter Account Number, From Date, and To Date to download the Excel file.');
		redirect('account_details');
		return;
	  }

    
    
    
    $this->load->model('AccountDetailsModel');
    $data = $this->AccountDetailsModel->get_datatables($from_date, $to_date, $search_value);




	$objPHPExcel = new PHPExcel();
  
	
	$objPHPExcel->getProperties()
		->setCreator("Your Name/Application")
		->setLastModifiedBy("Your Name/Application")
		->setTitle("Account Details Report")
		->setSubject("Account Details Export")
		->setDescription("Account Details exported on " . date('Y-m-d H:i:s'));
  
	
	$objPHPExcel->setActiveSheetIndex(0)
		->setCellValue('A1', 'No')
		
		->setCellValue('B1', 'Account Number')
		->setCellValue('C1', 'RRN')
		->setCellValue('D1', 'Amount')
		->setCellValue('E1', 'Time')
		->setCellValue('F1', 'Response')
		->setCellValue('G1', 'Other Details');
  
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(5);
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(20);
	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(30);
  
	
	$row_index = 2;
	$no = 1;
	foreach ($data as $item) {
	  $objPHPExcel->getActiveSheet()
		  ->setCellValue('A' . $row_index, $no++)
		  
		  ->setCellValue('B' . $row_index, $item->Account_Number)
		  ->setCellValue('C' . $row_index, $item->RRN)
		  ->setCellValue('D' . $row_index, number_format($item->Amount, 2))
		  ->setCellValue('E' . $row_index, $item->Time)
		  ->setCellValue('F' . $row_index, $item->Response_Code == 1 ? "Success" : ($item->Response_Code == 2 ? "Time out" : ($item->Response_Code == 3 ? "Reversal" : "")))
		  ->setCellValue('G' . $row_index, $item->Second_Part);
	  $row_index++;
	}
  
	
	$objPHPExcel->getActiveSheet()->freezePane('A2');
  
	$objPHPExcel->setActiveSheetIndex(0);
  
	
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
	header('Content-Disposition: attachment;filename="AccountDetails-' . date('Ymd') . '.xlsx"');
	header('Cache-Control: max-age=0');
  
	
	$writer = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
	$writer->save('php://output');
	exit;
  }

}
?>