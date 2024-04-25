<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//include "application/models/common/CommonDataSelection.php";
class UserModel extends  CI_Model {

	var $table = 'system_users';
	var $column_order = array(null, 'system_user_name','system_user_full_name'/*,'area_description'*/); //set column field database for datatable orderable
	var $column_search = array('system_user_name','system_user_full_name'/*,'area_description'*/); //set column field database for datatable searchable 
	var $order = array('system_user_name' => 'asc'); // default order 

	public function __construct()
	{
		parent::__construct();
		date_default_timezone_set('Asia/Colombo');		
	}
	
	public function getPages()
	{
		$this->db->select('page_id,page_viewname,page_type');
		$this->db->from('page_names');
		$this->db->where('page_status ', 1);
		//$this->db->order_by('page_type',"ASC");
		$query=$this->db->get();
		
		return $query->result();
	}
	
	public function getPagerDetails($recordId)
	{
		$this->db->select('access_page_id');
		$this->db->from('access_permission');
		$this->db->where('access_user_id', $recordId);
		$query=$this->db->get();
		
		return $query->result();
	}
	
	
	
	public function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}
	
	private function _get_datatables_query()
	{
		$this->db->select('system_user_id,
							system_user_name,
							system_user_full_name,
							system_user_status,');
		$this->db->from('system_users');
		//$this->db->join('area','area_id = system_user_area_id','inner');
		$this->db->where('system_user_status<>', 5);
		
		$i = 0;
		
		$srchval	=	$_POST['search']['value'];
		$srchval = strtoupper($srchval);
		
		foreach ($this->column_search as $item) // loop column 
		{
			if($srchval) // if datatable send POST for search
			{			
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $srchval);
				}
				else
				{
					$this->db->or_like($item, $srchval);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
						$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	
	public function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}
	
	
	
		
    public function updateRecord($userId,$fullName,$pagesList/*,$cmbLocationCode*/)
	{
		$this->db->trans_begin();
			$data = array('system_user_full_name' => $fullName/*'system_user_area_id'=>$cmbLocationCode*/);
			$this->db->where('system_user_id', $userId);
			$this->db->update('system_users', $data);
			
			$this->db->where('access_user_id', $userId);
			$this->db->delete('access_permission');
			
			$i = 0;
			
				while($pagesList[$i]!="")
				{
					$data = array('access_user_id' => $userId,'access_status' => 1,'access_page_id' => $pagesList[$i]);
					$this->db->insert('access_permission', $data);
					$i++;
					if(!isset($pagesList[$i])){ break;}
				}
			$data = array('access_user_id' => $userId,'access_status' => 1,'access_page_id' => 1);
			$this->db->insert('access_permission', $data);
			
			if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$this->session->set_flashdata('fail', 'Data Not Saved. Try Again');
				header( 'Location: '.base_url("index.php/masterData/UserDetails") ) ;
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('success', 'Data Successfully Updated');
			header( 'Location: '.base_url("index.php/masterData/UserDetails") ) ;
		}
			
			//$str = $this->db->last_query();
			//$auditData = array('auditUserId' => $this->session->userdata('sys_user_id') ,'auditTime' => date("Y-m-d G:i:s",strtotime('-5:30')),'auditPage' => 'Contact Details','auditAction' => "Update",	'auditQuery' => $str, 'primaryKey'=>$id);
			//$this->db->insert('audit_trail', $auditData);
			 	
			//header( 'Location: '.base_url("index.php/masterData/UserDetails") ) ;
	}
	
	public function saveRecord($userName,$fullName,$pagesList)
	{
		$this->db->trans_begin();
		
		$data = array('system_user_name' => $userName,'system_user_password' => md5($userName),'system_user_full_name' => $fullName,'system_user_entered_by' => $this->session->userdata('user_id'),'system_user_date_time' => date("Y-m-d G:i:s"),'system_user_status' => 1);
		$this->db->insert('system_users', $data);
		$insert_id = $this->db->insert_id();
		
		$data_last	=	array('last_user_id'=>$insert_id,'last_session_id'=>"",'last_ip'=>"");
		$this->db->insert('last_session',$data_last);
		
		$i = 0;
		while($pagesList[$i]!="")
		{
			$data = array('access_user_id' => $insert_id,'access_status' => 1,'access_page_id' => $pagesList[$i]);
			$this->db->insert('access_permission', $data);
			$i++;
			if(!isset($pagesList[$i])){ break;}
		}
		$data = array('access_user_id' => $insert_id,'access_status' => 1,'access_page_id' => 1);
		$this->db->insert('access_permission', $data);
			
		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
				$this->session->set_flashdata('fail', 'Data Not Saved. Try Again');
				header( 'Location: '.base_url("index.php/masterData/UserDetails") ) ;
		}
		else
		{
			$this->db->trans_commit();
			$this->session->set_flashdata('success', 'Data Successfully Entered');
			header( 'Location: '.base_url("index.php/masterData/UserDetails") ) ;
		}
	}
	
	
	public function changeStatus($userId,$userStatus)
	{
		$this->db->trans_begin();

		$data = array('system_user_status' => $userStatus);
		$this->db->where('system_user_id', $userId);
		$this->db->update('system_users', $data); 
		
		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
		}
		else
		{
				
				$this->db->trans_commit();
				if($userStatus==1)echo "User Activated";
				if($userStatus==0)echo "User De-Activated";
$this->db->close();	
		}	
	}
	
	public function changePassword($userId,$userName)
	{
		$this->db->trans_begin();

		$data = array('system_user_password' => md5($userName));
		$this->db->where('system_user_id', $userId);
		$this->db->update('system_users', $data); 
		
		if ($this->db->trans_status() === FALSE)
		{
				$this->db->trans_rollback();
		}
		else
		{
				$this->db->trans_commit();
				echo "Password Reset Successfully";
				//$this->session->set_flashdata('success', 'Data Successfully Entered');
				//header( 'Location: '.base_url("index.php/masterData/UserDetails") ) ;
		}	
	}
	
	public function editRecord($userId)
	{		
		$this->db->select('system_user_id, system_user_name, system_user_full_name');
		$this->db->from('system_users');
		//$this->db->join('area','area_id = system_user_area_id','inner');
		$this->db->where('system_user_id', $userId);
		$result = $this->db->get()->row();
		return $result;
	}
	
	/*public function loadArea()
	{
		$this->db->select('distinct(area_code) As area_code ,area_description');
		$this->db->from('area');
		$this->db->where('area_status', 1);
		$query=$this->db->get();
	
		$location = array();
		$location = array('' => '-- Select Area --');
		foreach ($query->result_array() as $row) 
		{
			$location[$row['area_code']] = $row['area_description'];
		}
		return $location;
	}
	
	public function loadSubArea($areaCode)
	{
		
		$this->db->select('area_id,area_sub_description');
		$this->db->from('area');
		$this->db->where('area_status', 1);
		$this->db->where('area_code', $areaCode);
		$query=$this->db->get();
		
		$subLocation = array();
		$subLocation = array('' => '-- Select Office --');
		foreach ($query->result_array() as $row) 
		{
			$subLocation[$row['area_id']] = $row['area_sub_description'];
		}
		
		return $subLocation;
	}
	
	public function getSubArea($areaCode)
	{
		$this->db->select('area_id,area_sub_description');
		$this->db->from('area');
		
		$this->db->where('area_status', 1);
		//$this->db->where('area_code', $areaCode);
		$query=$this->db->get();
	
		$area_data= $query->result();
		
		$area_arr[] = array( 'area_id'	 => "",
		   						'area_sub_description' => "-- Select Office --");
		
        foreach ($area_data as $results)
		{
			 $area_arr[] = array('area_id' 	=> $results->area_id,
		   							'area_sub_description' => $results->area_sub_description);	
        }
		return 	$area_arr;
	}*/
}


