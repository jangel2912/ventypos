<?php

class Licencias_model  extends CI_Model{

    public $connection;
	// Constructor

	public function __construct(){
		parent::__construct();
	}

    public function initialize($connection) {
        $this->connection = $connection;
    }   

    public function get_ajax_dsssata(){
        $id_db_config = $this->session->userdata('db_config_id');
        $sql_crm_licencias = "SELECT l.id_almacen,l.fecha_inicio_licencia,l.fecha_vencimiento,es.descripcion FROM crm_licencias_empresa l,crm_estados es WHERE id_db_config = $id_db_config and l.estado_licencia = es.id ";
        $data = array();

        foreach ($this->db->query($sql_crm_licencias)->result() as $value) {
            $sql = "SELECT id,nombre,direccion,telefono FROM almacen WHERE id= $value->id_almacen"; 
            $almacen = $this->connection->query($sql)->result();
           
            $data[] = array(
                $almacen[0]->nombre,
                $almacen[0]->direccion,
                $almacen[0]->telefono,
                $value->id_almacen,
                $value->fecha_inicio_licencia,
                $value->fecha_vencimiento,
                $value->descripcion
            );
        }
        return array(
            'aaData' => $data
        );
    }

    public function get_ajax_datos(){
        $id_db_config = $this->session->userdata('db_config_id');
        $sql_crm_licencias = "SELECT id_almacen,fecha_inicio_licencia,fecha_vencimiento,estado_licencia FROM crm_licencias_empresa WHERE id_db_config = $id_db_config";
        $data = array();

        foreach ($this->db->query($sql_crm_licencias)->result() as $value) {
            $sql = "SELECT id,nombre,direccion,telefono FROM almacen WHERE id= $value->id_almacen"; 
            $almacen = $this->connection->query($sql)->result();
           
            $data[] = array(
                $almacen[0]->nombre,
                $almacen[0]->direccion,
                $almacen[0]->telefono,
                $value->id_almacen,
                $value->fecha_inicio_licencia,
                $value->fecha_vencimiento,
                $value->estado_licencia
            );
        }
        return array(
            'aaData' => $data
        );
    }
    
    public function by_id_config_almacen($id,$almacen){
        $sql_crm_licencias = "select * from v_crm_licencias where id_db_config=$id and id_almacen=$almacen";  
        return $this->db->query($sql_crm_licencias)->row_array();        
    }    
    /*
    public function by_id_config_estado_licen($id,$limit){
        $sql_crm_licencias = "select * from v_crm_licencias where id_db_config=$id and estado_licencia=15";
        if($limit){             
            return $this->db->query($sql_crm_licencias)->row_array();   
        }else{            
            return $this->db->query($sql_crm_licencias)->result_array();  
        }    
    }*/

    public function by_id_config_estado_licencias($where,$limit){
        $sql_crm_licencias = "select * from v_crm_licencias ".$where;     
       // echo"<br>sql=".$sql_crm_licencias;  die();
        if($limit){          
            $sql_crm_licencias .=" limit 1";             
            return $this->db->query($sql_crm_licencias)->result_array();   
        }else{            
            return $this->db->query($sql_crm_licencias)->result_array();  
        }    
    }

    public function licenciaPrueba($from, $where){
        $sql_crm_licencias = "SELECT * FROM $from WHERE $where";
        return $this->db->query($sql_crm_licencias)->result_array(); 
    }

    /*public function by_id_config_estado_licen($where,$limit){
        //echo $where;
        //$where = array('id_db_config' => 2409);
        $this->db->where($where); 
        $this->db->from('v_crm_licencias');
        $query = $this->db->get();  
        //$sql_crm_licencias = "select * from v_crm_licencias where id_db_config=$id and estado_licencia=15";
        if($limit){
            return $query->row_array();             
            //return $this->db->query($sql_crm_licencias)->row_array();   
        }else{            
            //return $this->db->query($sql_crm_licencias)->result_array();  
            return $query->result_array();
        }    
    }*/
 }
