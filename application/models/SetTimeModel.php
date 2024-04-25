<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SetTimeModel extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        date_default_timezone_set('Asia/Colombo');
    }


public function save_times($current_time, $previous_time, $update_condition = array())
  {
    $data = array(
      'currentdaytime' => $current_time,
      'previousdaytime' => $previous_time,
      
      'last_updated_by' => $this->session->userdata('user_name')
    );
   

    if (!empty($update_condition)) {
      $this->db->where($update_condition);
    }

    $this->db->update('save_time', $data);
    return $this->db->affected_rows() > 0;
  }

    public function get_last_entered()
    {
        $this->db->select('currentdaytime, previousdaytime, entered_at, last_updated_by');
        $this->db->from('save_time');
        $this->db->order_by('entered_at', 'DESC');
        $this->db->limit(1);

        $query = $this->db->get();
        return $query->row();

    }
}
