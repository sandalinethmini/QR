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
			//$query	=	$this->db->get_where('system_users', array('system_user_name' => $userName,'system_user_password'=> md5($password),'system_user_status'=>1));
			
			$this->db->select('system_user_id,system_user_name,system_user_full_name,system_user_status');
			$this->db->from('system_users');
			//$this->db->join('area','area_id = system_user_area_id','inner');
			$this->db->where('system_user_name',$userName);
			$this->db->where('system_user_password',md5($password));
			//$this->db->where('system_user_status',1);
			$query = $this->db->get();
			
			if ($query->num_rows() > 0)
			{		
				$row 			= 	$query->row();					
				$user_id		=	$row->system_user_id;
				$user_name		=	$row->system_user_name;
				//$user_area		=	$row->area_sub_description;
				$user_full_name	=	$row->system_user_full_name;
				
				$status	=	$row->system_user_status;
				
				if($status==1 || $status==5)
				{
					$session_data		=	array('user_id'=>$user_id,'user_name'=>$user_name,'user_full_name'=>$user_full_name);				
					$this->session->set_userdata($session_data);
					
					$data_log			=	array('log_session_id'=>session_id(),'log_ip'=>$_SERVER['REMOTE_ADDR'],'log_user_id'=>$user_id,'log_login_time'=>date("Y-m-d G:i:s"));
					
					$data_last			=	array('last_session_id'=>session_id(),'last_ip'=>$_SERVER['REMOTE_ADDR']);
					
					$this->db->trans_begin();
															
					$this->db->insert('log_catalog',$data_log);
					$this->db->update('last_session', $data_last, "last_user_id = $user_id");								
					
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