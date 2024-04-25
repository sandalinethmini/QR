<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
 
class AccountDetailsModel extends CI_Model {

	
	
	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Colombo');	
	} 
	
	

	public function get_datatables($fromDate, $toDate,$searchValue)
	{
		$this->db->select('*');
        $this->db->from('Reconfile');
        $this->db->where('Account_Number', $searchValue);
		if ($fromDate == $toDate) {
        $this->db->where("DATE(reconfile.Time)", $fromDate);
    } else {
        $this->db->where("reconfile.Time BETWEEN '" . $fromDate . "' and '" . $toDate . "'");
    }
		$this->db->order_by('Time' , 'DESC');
		$query = $this->db->get();
		return $query->result();
	}
		
 
}
 
?>