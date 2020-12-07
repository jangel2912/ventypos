<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of permisos_model
 *
 * @author danbuchi
 */
class Permisos_model extends CI_Model {
    public function __construct()
	{
		parent::__construct();		
	}
        
     public function get_permisos($sistema){
         
         $this->db->order_by("id_permiso"); 
         $data = $this->db->get_where('permisos', array('sistema' => 'POS'))->result();
         return $data;
     }   
     
     public function get_combo_data($sistema){
         $combo_data = array();
         
      
         foreach ($this->get_permisos($sistema) as $row){
             $combo_data[$row->id_permiso] = $row->nombre_permiso; 
         }
         return $combo_data;
     }
     
     
}

?>
