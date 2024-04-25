<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Excel_import_model extends CI_Model {

	

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
	}


	


	function uploadData($data)
    {
		$this->db->trans_begin();
        $this->db->db_debug = FALSE;	                       
        $this->db->insert_batch('Reconfile', $data);
		$duplicate_error=$this->db->error();
              
		
			if ($this->db->trans_status() === FALSE)
		{
			$this->db->trans_rollback();

			if($duplicate_error['code']== 1062  )
			{				
				$this->session->set_flashdata('info', "Duplicate File");	
			}
			header( 'Location: '.base_url("index.php/Excel_import") ) ;
	}
		else  {
		
		$this->db->trans_commit();
			$this->db->db_debug = TRUE;
			$this->session->set_flashdata('info', ' Successfully Uploaded. ');
		header( 'Location: '.base_url("index.php/Excel_import") ) ;
		}
    }

    function getUploadedData()
    {
        $query = $this->db->get('Reconfile');
        return $query->result_array();
    } 

public function getExistingRRNs($rrnList){
	$this->db->select('RRN');
	$this->db->where_in('RRN' , $rrnList);
	$query = $this->db->get('Reconfile');

	$existingRRNs = [];

	if($query->num_rows() > 0){
		foreach ($query->result() as $row) {
			$existingRRNs[] = $row->RRN;

		}
	}
	return $existingRRNs;
}



    
}



