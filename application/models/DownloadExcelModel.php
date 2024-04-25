<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class DownloadExcelModel extends CI_Model {

	

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
		
	}

    
    public function getRecordsResponseCodeALL($fromDate, $toDate, $responseCode) {

        $objPHPExcel = new PHPExcel();
    
        $fileName ='QR And Just Pay Transactions';
    
        $this->db->select('reconfile.Account_Number, FORMAT(reconfile.Amount,2) AS "Amount", reconfile.RRN, reconfile.Response_Code, reconfile.Time, reconfile.Second_Part', false);
        $this->db->from('reconfile');
        $this->db->where("reconfile.Time BETWEEN '" . $fromDate . "' and '" . $toDate . "'");
        
    
        $query = $this->db->get();
    
        $rowCount = 1;
        $indexNumber = 1;
        $ceditAmount = 0;
    
        $objPHPExcel->getActiveSheet()->getStyle('A8:Q8')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'FFFF00')));
    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $rowCount, '' . $fileName);
    
        ++$rowCount;
        ++$rowCount;
    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $rowCount, 'STATUS - ALL');
            ++$rowCount;
            ++$rowCount;
    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $rowCount, 'From: ' . $fromDate)
            ->setCellValue('A' . ++$rowCount, 'To: ' . $toDate);
    
        ++$rowCount;
        ++$rowCount;
    
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A' . $rowCount, 'Account No')
            ->setCellValue('B' . $rowCount, 'Amount')
            ->setCellValue('C' . $rowCount, 'Response Code')
            ->setCellValue('D' . $rowCount, 'Action Time')
            ->setCellValue('E' . $rowCount, 'Description');
    
        foreach ($query->result_array() as $list) {
            $rowCount++;
    
            $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $list["Account_Number"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $list["Amount"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $list["Response_Code"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $list["Time"]);
            $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $list["Second_Part"]);
    
            $indexNumber++;
            $ceditAmount += 250;
        }
    
        $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '_All_Response_Codes.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }
    
	
public function getRecordsResponseCode1($fromDate, $toDate, $responseCode) {
    $objPHPExcel = new PHPExcel();

    
    $fileName = 'QR And Just Pay Transactions';

    $this->db->select('reconfile.Second_Part, reconfile.Account_Number, reconfile.Response_Code, FORMAT(reconfile.Amount,2) AS "Amount", reconfile.Time, reconfile.RRN, COUNT(reconfile.RRN) as rrn_count');
    $this->db->from('reconfile');
    $this->db->where("reconfile.Time BETWEEN '" . $fromDate . "' and '" . $toDate . "'");
    $this->db->where_in('reconfile.Response_Code', array(1, 3));
    $this->db->group_by('reconfile.RRN');
    $this->db->having('rrn_count < 2');
    $this->db->order_by('reconfile.Time', 'ASC');

    $query = $this->db->get();

    $rowCount = 1; // 
    $indexNumber = 1;
    $ceditAmount = 0;
	$objPHPExcel->getActiveSheet()->getStyle('A8:Q8')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'FFFF00')));
	
    
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $rowCount, '' . $fileName);

    ++$rowCount;
	++$rowCount;

	$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $rowCount, 'STATUS - SUCCESS');
		++$rowCount;
		++$rowCount;
    
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $rowCount, 'From: ' . $fromDate)
        ->setCellValue('A' . ++$rowCount, 'To: ' . $toDate);

    ++$rowCount;
	++$rowCount;

    
    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $rowCount, 'Account No')
        ->setCellValue('B' . $rowCount, 'Amount')
        ->setCellValue('C' . $rowCount, 'Response Code')
        ->setCellValue('D' . $rowCount, 'Action Time')
        ->setCellValue('E' . $rowCount, 'Description');
		
    foreach ($query->result_array() as $list) {
        $rowCount++;

        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $list["Account_Number"]);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $list["Amount"]);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $list["Response_Code"]);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $list["Time"]);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $list["Second_Part"]);

        $indexNumber++;
        $ceditAmount += 250;
    }

    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
}



public function getRecordsResponseCode2($fromDate, $toDate, $responseCode) {

    $objPHPExcel = new PHPExcel();

    $fileName ='QR And Just Pay Transactions';

    $this->db->select('reconfile.Account_Number, FORMAT(reconfile.Amount,2) AS "Amount", reconfile.RRN, reconfile.Response_Code, reconfile.Time, reconfile.Second_Part', false);
    $this->db->from('reconfile');
    $this->db->where("reconfile.Time BETWEEN '" . $fromDate . "' and '" . $toDate . "'");
    $this->db->where('reconfile.Response_Code', 2);

    $query = $this->db->get();

    $rowCount = 1;
    $indexNumber = 1;
    $ceditAmount = 0;

	$objPHPExcel->getActiveSheet()->getStyle('A8:Q8')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'FFFF00')));

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $rowCount, '' . $fileName);

    ++$rowCount;
	++$rowCount;

	$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $rowCount, 'STATUS - Time Out');
		++$rowCount;
		++$rowCount;

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $rowCount, 'From: ' . $fromDate)
        ->setCellValue('A' . ++$rowCount, 'To: ' . $toDate);

    ++$rowCount;
	++$rowCount;

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $rowCount, 'Account No')
        ->setCellValue('B' . $rowCount, 'Amount')
        ->setCellValue('C' . $rowCount, 'Response Code')
        ->setCellValue('D' . $rowCount, 'Action Time')
        ->setCellValue('E' . $rowCount, 'Description');

    foreach ($query->result_array() as $list) {
        $rowCount++;

        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $list["Account_Number"]);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $list["Amount"]);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $list["Response_Code"]);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $list["Time"]);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $list["Second_Part"]);

        $indexNumber++;
        $ceditAmount += 250;
    }

    $objPHPExcel->getActiveSheet()->setTitle('Sheet1');
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $fileName . '_Response_Code_2.xlsx"');
    header('Cache-Control: max-age=0');
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
}


	public function getRecordsResponseCode3($fromDate,$toDate,$responseCode){

		$objPHPExcel = new PHPExcel();

		$fileName ='QR And Just Pay Transactions';
        


		$this->db->select('t.Account_Number "Account No",
		FORMAT(t.Amount,2) AS "Amount",
		t.RRN AS "RRN",
		t.Response_Code AS "Response Code",
		SUCCESS.Time AS "Action Time",
		t.Time AS "Dispute Time",
		t.Second_Part AS "Description"' , false);
		$this->db->from('Reconfile t');
		$this->db->join('(SELECT                              
	    r.Response_Code,
		r.Amount,
		r.Time,
		r.RRN,
		r.Account_Number
		FROM
		Reconfile r
		WHERE
		r.Response_Code = 1) SUCCESS','SUCCESS.RRN = t.RRN' , 'left');
		$this->db->where("SUCCESS.Time BETWEEN '". $fromDate. "' and '". $toDate."'"); 
		$this->db->where('t.Response_Code' , 3);
		
		
		
		$query = $this->db->get();


		$rowCount = 1;
		$indexNumber = 1;
		$ceditAmount = 0;

        $objPHPExcel->getActiveSheet()->getStyle('A8:Q8')->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => 'FFFF00')));

		$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $rowCount, '' . $fileName);

    ++$rowCount;
	++$rowCount;

	$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $rowCount, 'STATUS - Reversal');
		++$rowCount;
		++$rowCount;

    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $rowCount, 'From: ' . $fromDate)
        ->setCellValue('A' . ++$rowCount, 'To: ' . $toDate);

    ++$rowCount;
	++$rowCount;


	$objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A' . $rowCount, 'Account No')
        ->setCellValue('B' . $rowCount, 'Amount')
        ->setCellValue('C' . $rowCount, 'Response Code')
        ->setCellValue('D' . $rowCount, 'Action Time')
		->setCellValue('E' . $rowCount, 'Dispute Time')
        ->setCellValue('F' . $rowCount, 'Description');
		
		
		 foreach ($query->result_array() as $list) {
			
			$rowCount++;
				$objPHPExcel->getActiveSheet()->SetCellValue('A' . $rowCount, $list["Account No"]); 
				$objPHPExcel->getActiveSheet()->SetCellValue('B' . $rowCount, $list["Amount"]);
				$objPHPExcel->getActiveSheet()->SetCellValue('C' . $rowCount, $list["Response Code"]);
				$objPHPExcel->getActiveSheet()->SetCellValue('D' . $rowCount, $list["Action Time"]);
				$objPHPExcel->getActiveSheet()->SetCellValue('E' . $rowCount, $list["Dispute Time"]);
                $objPHPExcel->getActiveSheet()->SetCellValue('F' . $rowCount, $list["Description"]);
                
				
				$indexNumber++;
				$ceditAmount += 250; 
		 }
				 
					
        
					$rowCount++;
				 
					
				
		
		

			
			
				$objPHPExcel->getActiveSheet()->setTitle('Sheet1');
				$objPHPExcel->setActiveSheetIndex(0);
				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.date("Y_m_d_His").'Reconfile_Account_Details.xlsx"'); // file name of excel
				//header('Content-Disposition: attachment;filename="SMS_Renewal_Batch_'.date("Y_m_d_His").'.xlsx"'); // file name of excel
				header('Cache-Control: max-age=0');
				$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
				$objWriter->save('php://output');

	}	
     }


	
