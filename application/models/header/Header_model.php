<?php 
class Header_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();	
	}
	function menuLoad()
	{
		$session_id			=	session_id();
		$ip_address			=	$_SERVER['REMOTE_ADDR'];
		//$user_area			=	$this->session->userdata('user_area');
		$user_id			=	$this->session->userdata('user_id');
		$user_name			=	$this->session->userdata('user_name');
		
		$previous_session_id	=	"";
		$previous_session_ip	=	"";
		$data					=	"";
		
		$home = true;
		
		$administration			=	false;
		$user_accounts			=	false;
		$upload_file			=	false;
		$account_search			=	false;
		$download_status_file	=	false;
		$password_reset 		= 	false;
		
		$reports	=	false;
		
		
		$user_details		=	false;
		
		$password_reset 	= false;
		
		
		$this->db->select('last_session_id,last_ip');
		$query		=	$this->db->get_where('last_session',array('last_user_id'=>$user_id)); 
		
			$row = $query->row();					
			$previous_session_id	=	$row->last_session_id;
			$previous_session_ip	=	$row->last_ip;	
		
		if($session_id==$previous_session_id)			
		{
			$this->db->trans_start();
			$this->db->select('page_names.page_name');
			$this->db->from('access_permission');
			$where_array	=	array('page_names.page_status' => 1, 'access_permission.access_status' => 1, 'access_permission.access_user_id' => $user_id);
			$this->db->where($where_array);
			$this->db->join('page_names','access_permission.access_page_id =  page_names.page_id','inner');
			$query		=	$this->db->get();  
				
			foreach($query->result_array() as $row)
				{	
					if ($row["page_name"] == "user_accounts")
						{
							$administration	=	true;
							$user_accounts	=	true;				
						}
					
					////////////////////////// Application
					
					if ($row["page_name"] == "upload_file")
						{
							$upload_file	=	true;			
						}
					
					if ($row["page_name"] == "account_search") 
						{
							$account_search	=	true;			
						}
					
					if ($row["page_name"] == "download_status_file")
						{
							$download_status_file	=	true;	
						}
					if ($row["page_name"] == "password_reset")
						{
							$password_reset	=	true;	
						}
					
					
										
			}
									
			$data	=	array(	
				'administration'=>$administration, 
				'user_accounts'=>$user_accounts, 
				'upload_file'=>$upload_file,
				'account_search'=>$account_search,
				'download_status_file'=>$download_status_file,
				'password_reset'=>$password_reset,
			);		
				
			$this->db->trans_complete();					
			return $data;		
		}
		else
			return "";				
	}
	
	function pageLoad($pageId)
	{
		if($pageId==-100)
		{			
			return true;
		}
		else
		{
			$user_id	=	$this->session->userdata('user_id');
		
			$this->db->trans_start();
			$this->db->select('access_id');
			$this->db->from('access_permission');
			$this->db->where('access_page_id',$pageId);
			$this->db->where('access_user_id',$user_id);
			
			$query		=	$this->db->get();
			$this->db->trans_complete();
			
			if($this->db->trans_status() === TRUE){}
			
			if ($query->num_rows() > 0)	
				return true;
			else
				return false;	
		}
		
		
		
	}
}
?>