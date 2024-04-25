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
		$branch_id			=	$this->session->userdata('branch_id');	
		$user_id			=	$this->session->userdata('branch_code');
		$user_name			=	$this->session->userdata('branch_name');
		
		$previous_session_id	=	"";
		$previous_session_ip	=	"";
		$data					=	"";
		
		$home = true;
		
		$open_ticket		=	false;
		$master_data		=	false;
		$ho_ticket_review   =   false;
		$password_reset 	= false;
		$users		=	false;
		$sms_branch_user 	= false;
		
		
		
		
		$this->db->select('last_session_id,last_ip');
		$query		=	$this->db->get_where(' last_session',array('last_user_id'=>$branch_id)); 
		
			$row = $query->row();					
			$previous_session_id	=	$row->last_session_id;
			$previous_session_ip	=	$row->last_ip;	
		
		if($session_id==$previous_session_id)			
		{
			$this->db->trans_start();
			$this->db->select(' page_names.page_name');
			$this->db->from(' access_permission');
			$where_array	=	array(' page_names.page_status' => 1, ' access_permission.access_status' => 1, ' access_permission.access_user_id' => $branch_id);
			$this->db->where($where_array);
			$this->db->join(' page_names',' access_permission.access_page_id =  page_names.page_id','inner');
			$query		=	$this->db->get();  
				
			foreach($query->result_array() as $row)
				{	
					if ($row["page_name"] == "users")
						{
							$master_data	=	true;
							$users	=	true;				
						}
					
					////////////////////////// Application
					
					if ($row["page_name"] == "open_ticket")
						{
							$open_ticket	=	true;			
						}
						
					if ($row["page_name"] == "ho_ticket_review")
						{
							$ho_ticket_review	=	true;			
						}
					
					if ($row["page_name"] == "password_reset") 
						{
							$password_reset	=	true;			
						}
						if ($row["page_name"] == "sms_branch_user")  // 100
						{
							$sms_branch_user	=	true;
							
						}
					
										
			}
									
			$data	=	array(	
				'master_data'=>$master_data, 
				'open_ticket'=>$open_ticket, 
				'users'=>$users,
				'password_reset'=>$password_reset,
				'ho_ticket_review' =>$ho_ticket_review,
				'sms_branch_user' => $sms_branch_user
				
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
			$branch_id	=	$this->session->userdata('branch_id');	
		
			$this->db->trans_start();
			$this->db->select('access_id');
			$this->db->from(' access_permission');
			$this->db->where('access_page_id',$pageId);
			$this->db->where('access_user_id',$branch_id);
			
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