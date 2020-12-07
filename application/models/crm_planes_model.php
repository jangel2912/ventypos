<?php

class Crm_planes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }
    
    public function get_planes_All(){        
        $query = $this->db->get('crm_planes');
        return $query->result();
    }

    public function add($data){  
            
        $this->db->insert('crm_planes',$data);
        return $this->db->insert_id();
    }

    public function update($data,$where){  
        $this->db->where($where);
        $this->db->update('crm_planes',$data);
    }

    public function delete($where){  
        $this->db->where($where);
        $this->db->delete('crm_planes');
    }
    
    public function add_detalle_plan($data){        
        $this->db->insert('crm_detalles_planes',$data);
        return $this->db->insert_id();
    }

    public function update_detalle($data,$where){  
        $this->db->where($where);
        $this->db->update('crm_detalles_planes',$data);
    }

    public function delete_detalle($where){  
        $this->db->where($where);
        $this->db->delete('crm_detalles_planes');
    }

    public function get_plan($where){
        $this->db->where($where);
        $query = $this->db->get('crm_planes');
        return $query->result_array();
    }

    public function get_detalle_plan($where){
        $this->db->where($where);
        $query = $this->db->get('crm_detalles_planes');
        return $query->result_array();
    }
}