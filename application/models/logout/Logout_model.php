<?php 
class Logout_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();	
		date_default_timezone_set('Asia/Colombo');
	}
	function logoutUser()
	{
		try
		{
			$this->db->trans_start();	
			$session_id		=	session_id();
			$ip_address		=	$_SERVER['REMOTE_ADDR'];
			$user_id		=	$this->session->userdata('user_id');
			$logout_time	=	date("Y-m-d G:i:s");
				
			$this->db->select('log_id');
			$where_array 	= array('log_session_id' => $session_id, 'log_user_id' => $user_id, 'log_ip' => $ip_address);
			$query			=	$this->db->get_where('log_catalog',$where_array);
			
			if ($query->num_rows() > 0)
			{
				$row 	=	$query->row();					
				$log_id	=	$row->log_id;
			}
			else $log_id	=	"";
			
			$update_data	=	array('log_logout_time' => $logout_time);
			$this->db->where('log_id', $log_id);
			$this->db->update('log_catalog', $update_data);
						
			$this->session->sess_destroy();
			$this->db->trans_complete();
			if($this->db->trans_status() === TRUE){$this->db->close();}	
		}
		catch(Exception $e)
		{
			//$data = array('fail_user_id' => $this->session->userdata('user_id') ,'fail_date' => date("Y-m-d G:i:s")	,'fail_type' => 'logout');
			//$this->db->insert('fail_login_logout', $data);
		}
	}
}
?>