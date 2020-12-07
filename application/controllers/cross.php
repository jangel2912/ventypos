<?php

class Cross extends CI_Controller {

    function __construct() {
        parent::__construct();
    }
    
    public function loadCross(){
        $this->load->view('cross');
    }
}

