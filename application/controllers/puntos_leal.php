<?php

/**
 * Class puntos_leal
 */
class puntos_leal extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->layout->template('memberquickservice')->show('ventas/puntos_leal/index');
    }

    public function iframe()
    {
        $this->load->view('ventas/puntos_leal/index');
    }
}