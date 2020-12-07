<?php

/**
 * Class quickservice
 */
class quickservice extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->layout->template('memberquickservice')->show('ventas/quickservice');
    }

    public function iframe()
    {
        $this->load->view('ventas/quickservice/index');
    }
}