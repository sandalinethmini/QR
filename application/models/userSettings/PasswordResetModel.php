<?php 
class PasswordResetModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();	
	}
		
	function saveRecord($userId,$currentPassword,$newPassword,$newRePassword)
	{
		try
		{
			$this->db->trans_begin();
			
				$this->db->select('system_user_password');
				$this->db->from('system_users');
				$this->db->where('system_user_id', $userId);
				$query	=	$this->db->get();
				
				$sysPassword = $query->row('system_user_password');
				
				if(md5($currentPassword)==$sysPassword)
				{
					$this->db->where('system_user_id', $userId);
					$this->db->set('system_user_password', md5($newPassword));
					//$this->db->set('ba_password_error_count', 0);
					//$this->db->set('ba_password_update_date', date("Y-m-d G:i:s",strtotime('-1:30')));
					$this->db->update('system_users');
					
				}
				else
				{
					$this->db->trans_rollback();
					$this->session->set_flashdata('fail', 'Invalid Current Password. Try Again !');
					header( 'Location: '.base_url("index.php/userSettings/passwordReset") ) ;
					exit();
				}
			
			//$auditData = array('audit_user_id' => $this->session->userdata('user_id') ,'audit_time' => date("Y-m-d G:i:s",strtotime('-5:30')),'audit_page_id' => 7,'audit_table' => "working_year" ,'audit_action' => " Insert",	'audit_description' => " Insert New Working Year as $Year");
			//$this->db->insert('audit_trail', $auditData);
			 
			$this->db->trans_complete();
			
			if($this->db->trans_status() === TRUE)
			{
				$this->db->trans_commit();
				$this->session->set_flashdata('success', 'Password Successfully Changed !');
				header( 'Location: '.base_url("index.php/userSettings/passwordReset") ) ;
			}
			else
			{
				$this->db->trans_rollback();
				$this->session->set_flashdata('fail', 'Password Not Changed. Try Again !');
				header( 'Location: '.base_url("index.php/userSettings/passwordReset") ) ;
			}
				
		}
		catch(Exception $e)
		{
			//$data = array('excep_user_id' => $this->session->userdata('user_id') ,'excep_date' => date("Y-m-d G:i:s",strtotime('-5:30'))	,'excep_page' => '7' ,'excep_function' => 'saveYears');
			//$this->db->insert('exceptions', $data);
			//echo false;
		}
	}
	
	
}
?>