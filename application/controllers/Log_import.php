<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include "application/controllers/header/Header_page.php";

require_once APPPATH . "third_party\PHPExcel.php";
//use Carbon\carbon;

class Log_import extends Header_page {
	public function __construct()
	{		
		parent::__construct();
		$this->load->model('Log_import_model');
		
		$this->load->library('upload');
	}
	 
	public function index()
	{
		if($this->headerMenu(10))
		{

       		$this->load->view('Log_import_View');
			$this->footerMenu();
		}
		else
			header( 'Location: '.base_url() ) ;		
	}


	public function file_check($str)
	{
		$allowed_mime_type_arr = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','text/csv','text/plain','application/vnd.ms-excel','application/zip','application/octet-stream');
		if($_FILES['file']['name']!="")
		{
			$mime = mime_content_type($_FILES["file"]["tmp_name"]);
		}
       			
		if($_FILES["file"]["size"] > 112000000)
		{
			$this->form_validation->set_message('file_check', '*Please lesser size.');
			return false;
		}
		else if($_FILES['file']['name']=="")
		{
			$this->form_validation->set_message('file_check', '*Please choose a file to upload.');
			return false;
		}
		
		else if(in_array($mime, $allowed_mime_type_arr))
		{
			return true;
		}
			
		else
		{
			$this->form_validation->set_message('file_check',  $mime);
			return false;
		}
    }


	public function upload()
    {
		$this->form_validation->set_rules('file', '', 'callback_file_check');
		
			  
		$this->form_validation->set_error_delimiters('<span class="spanError">', '</span>');

			  if($this->form_validation->run() == true)
			  {
					$config['upload_path']   = './uploads/';
					$config['allowed_types'] = 'xls|xlsx';
					$config['max_size']      = 10240;
					$this->load->library('upload', $config);
					$this->upload->initialize($config);


					$inputFileName = $_FILES["file"]["tmp_name"];

					$inputFileType = PHPExcel_IOFactory::identify($inputFileName);
					$objReader = PHPExcel_IOFactory::createReader($inputFileType);
					$objReader->setLoadSheetsOnly( array("Sheet1") );
					$objPHPExcel = $objReader->load($inputFileName);
					
					
					$allDataInSheet = $objPHPExcel->getSheet(0)->toArray(null, true, true, true);
					$startRow = 23; 
                    $i = $startRow - 1; 

              foreach ($allDataInSheet as $key => $value) 
			  {
				if ($key < $startRow)
				 {
					continue; 
				 }

				
			
					$insertData[$i]['No'] 		= trim($value['A']);
					$insertData[$i]['Transaction_type'] 	= trim($value['E']);
					$insertData[$i]['Transaction_code'] 	= trim($value['F']);
					$insertData[$i]['Amount'] 			= trim($value['H']);
					$insertData[$i]['RRN'] = ltrim($value['M'], '0');
					$insertData[$i]['Send_Acc_No'] 	= trim($value['P']);
					$insertData[$i]['Beneficiary_Acc_No'] 	= trim($value['N']);
					$insertData[$i]['Response_Code'] 	= trim($value['T']);

					
		
						$dateString = trim($value['K']);
						
						try{
						$date = date_create_from_format('d/m/Y H:i:s', $dateString);
						if($date === false){
							throw new Exception('Invalid date format');
						}

 
                        $insertData[$i]['Time'] = $date->format('Y-m-d H:i:s');
					}
					catch(Exception $e){
						echo 'Error: ' . $e->getMessage();
					}
				
		++$i;
			}

			$this->Log_import_model->uploadData($insertData);
	
		}
		else
		{
			if($this->headerMenu(10))
			{
				
				$this->load->view('Log_import_View');
			}
		
		
		else{
			
				$this->session->set_flashdata('fail', 'Session Expired or Access Denined.');
				header( 'Location: '.base_url() ) ;
			
		
		}
		
		}
		
	}
}


			
					


	
		
	
	
	
	
		