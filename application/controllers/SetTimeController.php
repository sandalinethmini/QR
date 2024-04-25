<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
include "application/controllers/header/header_page.php";

class SetTimeController extends Header_page
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('SetTimeModel');
        date_default_timezone_set('Asia/Colombo');
    }

    public function index()
    {
        
      

        $data['daytime'] = $this->SetTimeModel->get_last_entered();
        
        $data['daytime'] = $this->SetTimeModel->get_last_entered(array('currentdaytime', 'previousdaytime', 'entered_at', 'last_updated_by'));
       
	    

        if ($this->headerMenu(12)) {
            $this->load->view('SetTimeView', $data,);
            $data['daytime'] = $this->SetTimeModel->get_last_entered(array('currentdaytime', 'previousdaytime', 'entered_at', 'last_updated_by'));
        } else {
            header('Location: ' . base_url());
        }
    }

    public function set_time()
    {
        $this->load->helper('form');

        $data = array(
            'title' => 'Save Time',
        );

        $validation_rules = array(
            array(
                'field' => 'current_time',
                'label' => 'Current Time',
                'rules' => 'required|callback_validate_time'
            ),
            array(
                'field' => 'previous_time',
                'label' => 'Previous Day Time',
                'rules' => 'required|callback_validate_time'
            )
        );

        $this->form_validation->set_rules($validation_rules);

        if ($this->form_validation->run() == TRUE) {
            $current_time = $this->input->post('current_time');
            $previous_time = $this->input->post('previous_time');

            if ($this->SetTimeModel->save_times($current_time, $previous_time)) {
                $data['success'] = 'Times saved successfully!';
            } else {
                $data['error'] = 'Error saving times!';
            }
        }

        if ($this->headerMenu(12)) {
            $data['daytime'] = $this->SetTimeModel->get_last_entered();
            $this->load->view('SetTimeView', $data);
        } else {
            header('Location: ' . base_url());
        }
    }

    public function validate_time($time)
    {
        if (!preg_match('/^([0-1][0-9]|2[0-3]):([0-5][0-9]):([0-5][0-9])$/', $time)) {
            $this->form_validation->set_message('validate_time', 'The {field} format is invalid. Please use HH:MM:SS.');
            return FALSE;
        }

        return TRUE;
    }
}
