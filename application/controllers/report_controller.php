
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Report_controller extends CI_Controller {

    public function __construct() {
        
        parent::__construct();
		
        $this->load->model('Report_model', 'reportModel');
        
        $this->load->helper('captcha');

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->load->model('backend/db_config/db_config_model', "dbconfig");

        $this->load->model('ion_auth_model');

        $this->lang->load('auth');

        $this->load->helper('language');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

    }

    public function index() {
        
        // llamamos a traves del sistema de templates un view limpio		
        $this->layout->template('ajax')->show('report_view');
        
    }

    public function email() {

        // Get post param mail
        $getEmail = $this->input->post('mail', TRUE);

        // Get query from model
        $queryResult = $this->reportModel->getDbId($getEmail);
        
        // If result query is not FALSE from Model
        if ($queryResult) {

            // Convert object to json and print
            $jsonResponse = json_encode($queryResult);
            $this->output
                    ->set_content_type('application/json')
                    ->set_output($jsonResponse);
        } else {
            $this->output
                    ->set_output("Mail not Found");
        }
    }

    public function pass() {

        $pass = $this->input->post('pass', TRUE);        
        $pass = $this->ion_auth->hash_password( $pass );
        echo $pass;
                

        
    }
    
}

?>