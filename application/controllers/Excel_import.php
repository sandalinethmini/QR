<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include "application/controllers/header/Header_page.php";

require_once APPPATH . "third_party\PHPExcel.php";

class Excel_import extends Header_page {
	public function __construct()
	{		
		parent::__construct();
		$this->load->model('Excel_import_model');
		
		$this->load->library('upload');
	}
	 
	public function index()
	{
		if($this->headerMenu(10))
		{

       		$this->load->view('Excel_import_View');
			$this->footerMenu();
		}
		else
			header( 'Location: '.base_url() ) ;		
	}


	public function file_check($str)
	{
        $allowed_mime_type_arr = array('application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','text/csv','text/plain','application/vnd.ms-excel');
		
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
			$this->form_validation->set_message('file_check',  '*Invalid File Type');
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
					$flag = true;
					$i=1;
		foreach ($allDataInSheet as $value)
		{
			if($flag)
			{
			$flag =false;
			continue;
			}
			
			if($value['A']=="")
			{
			
			continue;
			}
			$insertData[$i]['PAN_ID'] 		= trim($value['A']);
			$insertData[$i]['Response_Code'] 	= trim($value['B']);
			$insertData[$i]['Credit_Debit_ID'] 	= trim($value['C']);
			$insertData[$i]['Amount'] 			= trim($value['E']/100);
			$insertData[$i]['RRN'] 	= trim($value['K']);
			
			$insertData[$i]['Last'] 	= trim($value['AD']);
			
			
			$dateString = trim($value['F']);
			$dateString = substr($dateString,0,14);
            $date = DateTime::createFromFormat('YmdHis', $dateString);
			
			$insertData[$i]['Time'] = $date->format('Y-m-d H:i:s');
			
			
			

				
			
			$string = ($value['AD']);
			
			$parts = preg_split('/(?=[a-zA-Z])/', $string, 2);

			if (count($parts) === 2) {
			$firstPart = $parts[0]; 
			$secondPart = $parts[1]; 
			$insertData[$i]['First_Part']=  $firstPart;
			$insertData[$i]['Second_Part'] = $secondPart;
			
			} else {
				echo "Invalid format";
			}
			$insertData[$i]['Account_Number'] = substr($string,6,12);
			$insertData[$i]['Branch_Code'] = substr($string,6,4);


            $insertData[$i]['Bank_Code'] = trim($value['W']);

	++$i;
}


	$this->Excel_import_model->uploadData($insertData);
	
}
else
{
	if($this->headerMenu(10))
	{
		$this->load->view('Excel_import_View');
	}


else{
	
		$this->session->set_flashdata('fail', 'Session Expired or Access Denined.');
		header( 'Location: '.base_url() ) ;
	

}

}






}
}

			
					


	
		
	
	
	
	
		