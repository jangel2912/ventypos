<?php

class Pais_model extends CI_Model{
    
    public function __construct(){

        parent::__construct();      
    }
    
    public function getAll()
    {
        $data = $this->db->get('pais');
        $paises = [];
        foreach($data->result_array() as $pais)
        {
            $paises[$pais['id_pais']] = $pais['nombre_pais'];
        }
        return $paises;
    }
}