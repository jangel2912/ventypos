<?php

// Proyecto: Sistema Facturacion
// Version: 1.0
// Programador: Leonardo Molina
// Framework: Codeigniter
// Clase: crm

class Primeros_pasos_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function link_primeros_pasos($where,$in=null) {
        if(!empty($in)){
            $this->db->where_in('negocio', $in);
        }
        
        $this->db->where($where); 
        $this->db->order_by("orden"); 
        $this->db->select('*');                
        $data=$this->db->get('link_primeros_pasos')->result();
        return $data;
    }
    
    public function tareas_realizadas_tablero($where) {                
        $this->db->where($where); 
        $this->db->order_by("id_paso"); 
        $this->db->select('*');                
        $data=$this->db->get('primeros_pasos_usuarios')->result();
        return $data;
    }

    public function insertar_tareas_realizadas($data) {

        $this->db->insert("primeros_pasos_usuarios", $data);
        $id = $this->db->insert_id();
        return $id;
    }

    public function verificar_tareas_realizadas($where) {                
        $this->db->where($where); 
        $this->db->order_by("id_paso"); 
        $this->db->select('*');                
        $data=$this->db->get('primeros_pasos_usuarios')->result();
        
        if(!empty($data)){
            return $data;
        }else{
            return 0;
        }        
        
    }

}

?>