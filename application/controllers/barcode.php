<?php class barcode extends CI_Controller {

    public function index()
    {
        //I'm just using rand() function for data example
        $temp = 'sebastian navarrete';
        $this->set_barcode($temp);
    }

    private function set_barcode($code)
    {
        //load library
        $this->load->library('zend');
        //load in folder Zend
        $this->zend->load('Zend/Barcode');
        //generate barcode
        Zend_Barcode::render('code128', 'image', array('text'=>$code), array());
    }

}