<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Search
 *
 * @author Efrain
 */
class Search  extends CI_Controller {
    
    function __construct() {
       parent::__construct(); 
    }
    
    function find(){
        $data = array();
        if($this->form_validation->run('search') == true){
            $criteria = $this->input->post('criteria');
            $this->load->library('zend');
            $data = $this->zend->search_data($criteria);   
        }
        $this->layout->template('member')->show('search/find', array('data' => $data));
    }
    
}

?>