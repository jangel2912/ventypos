<?php

// Proyecto: Sistema Facturacion
// Version: 1.0
// Programador: Leonardo Molina
// Framework: Codeigniter
// Clase: crm

class Crm_empresas_clientes_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }


    public function add($data) {
        $this->db->insert("crm_empresas_clientes", $data);  
        return $this->db->insert_id();
    }

    public function update($data){       
        $this->db->where('idempresas_clientes', $data['idempresas_clientes']);
        return $this->db->update('crm_empresas_clientes', $data); 
    }
   
    public function delete($id) {       
            $this->db->where('idempresas_clientes', $id);
            $this->db->delete("crm_empresas_clientes");
            return true;        
    }

    public function get_by_id($id = 0) {

        $this->db->where("idempresas_clientes", $id);
        $query = $this->db->get('crm_empresas_clientes');
        return $query->result();
        
    }

    public function excel_exist($id_proveedor, $id_impuesto, $fecha, $cantidad=null){           

        $this->connection->where("id_proveedor", $id_proveedor);

        $this->connection->where("id_impuesto", $id_impuesto);

        $this->connection->where("fecha", $fecha);

        $this->connection->where("cantidad", $cantidad);

        $this->connection->from("proformas");

        $this->connection->select("*");

        $flag = false; 

        $query = $this->connection->get();

         if($query->num_rows() > 0){

             $flag = true;

         }

         $query->free_result();

         return $flag;

    }
    
    public function get_by_id_cliente_config($where){
        $this->db->where($where);
        $query = $this->db->get('crm_empresas_clientes')->result_array();
        $empresa=$query[0]['idempresas_clientes'];
        if(!empty($empresa)){             
            $this->db->where(array('id_empresa_cliente' => $empresa));
            $query2 = $this->db->get('crm_info_factura_clientes')->result_array();
            if(!empty($query2)){                
                return $query2;
            }          
        }        
        /*
        $query[0]['direccion']=$query[0]['direccion_empresa'];
        $query[0]['numero_identificacion']=$query[0]['identificacion_empresa'];
        $query[0]['ciudad']=$query[0]['ciudad_empresa'];
        $query[0]['telefono']=$query[0]['telefono_contacto'];*/
        
        //die("sdas");
        return $query[0]=0;
    }
    public function update_info_factura_cliente($data,$where){
        $this->db->where($where);
        $query = $this->db->get('crm_empresas_clientes')->result_array();
        $empresa=$query[0]['idempresas_clientes'];
        if(!empty($empresa)){   
            $this->db->where(array('id_empresa_cliente' => $empresa));
            $query2 = $this->db->get('crm_info_factura_clientes')->result_array();
            if(!empty($query2)){      
                //update
                $this->db->where('id_empresa_cliente', $empresa);
                $this->db->update('crm_info_factura_clientes',$data);                    
            } 
            else{
                $data['id_empresa_cliente']=$empresa;
                $this->db->insert('crm_info_factura_clientes',$data); 
            }
        }

    }

    public function get_all() {
        $this->db->select('users.id,users.email')->from('crm_empresas_clientes');
        $this->db->join('users', 'crm_empresas_clientes.idusuario_creacion = users.id');      
        return $this->db->get()->result_array();         
    }

}

?>