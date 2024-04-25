<?php 
class SessionDataModel extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Colombo');
	}
	function checkUser($userName,$password)
	{		
		try
		{			

			$this->db->select(' branch_id, branch_code, branch_name,branch_status,branch_email');
			$this->db->from('system_users');
			$this->db->where('branch_email',$userName);
			$this->db->where('branch_password',md5($password));
			$query = $this->db->get();
			
			if ($query->num_rows() > 0)
			{		
				$row 			= 	$query->row();	
				$branch_id		=	$row->branch_id;				
				$branch_code	=	$row->branch_code;
				$branch_name	=	$row->branch_name;
				$branch_email	=	$row->branch_email;
				
				
				$status	=	$row->branch_status;
				
				if($status==1 || $status==5)
				{
					$session_data		=	array('branch_id'=>$branch_id,'branch_code'=>$branch_code,'branch_name'=>$branch_name);				
					$this->session->set_userdata($session_data);
					
					$data_log			=	array('log_session_id'=>session_id(),'log_ip'=>$_SERVER['REMOTE_ADDR'],'log_user_id'=>$branch_code,'log_login_time'=>date("Y-m-d G:i:s"));
					
					$data_last			=	array('last_session_id'=>session_id(),'last_ip'=>$_SERVER['REMOTE_ADDR']);
					
					$this->db->trans_begin();
															
					$this->db->insert(' log_catalog',$data_log);
					$this->db->update(' last_session', $data_last, "last_user_id = $branch_id");								
					
					if ($this->db->trans_status() === FALSE)
					{
						$this->db->trans_rollback();
						$this->session->set_flashdata('fail', 'Please Try Again!');
						header( 'Location: '.base_url()) ;
					}
					else
					{
						$this->db->trans_commit();
						header( 'Location: '.base_url("index.php/Home") ) ;
					}
				}
				
				else
				{
					$this->session->set_flashdata('fail', 'Inactive User');
					header( 'Location: '.base_url()) ;
				}
				
				
			}
			else
			{
				$this->session->set_flashdata('fail', 'Invalid User Name or Password');
				header( 'Location: '.base_url()) ;
			}
		}
		catch(Exception $e)
		{
			$this->session->set_flashdata('fail', 'Please Try Again!');
			header( 'Location: '.base_url()) ;
		}	
	}
}
?>