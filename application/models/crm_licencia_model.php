<?php
class Crm_licencia_model extends CI_Model {
 
    var $connection;
    public function __construct() {

        parent::__construct();
    }

    public function initialize($connection) {

        $this->connection = $connection;
    }

    public function getLicencias(){
        
        
        $user_id = $this->session->userdata('user_id');
        
		$query = $this->db->query("SELECT e.idempresas_clientes, l.idlicencias_empresa, e.nombre_empresa,
                                    p.nombre_plan,
                                    date_format(l.fecha_inicio_licencia,'%d-%m-%Y') as fecha_inicio_licencia,
                                    date_format(l.fecha_vencimiento,'%d-%m-%Y') as fecha_vencimiento ,
                                    l.id_almacen,
                                    db.base_dato
                                FROM
                                    crm_empresas_clientes e,
                                    crm_licencias_empresa l,
                                    crm_planes p,
                                    db_config db
                                WHERE
                                    e.id_user_distribuidor = $user_id
                                        AND e.idempresas_clientes = l.idempresas_clientes
                                        AND l.planes_id = p.id
                                        AND e.id_db_config = db.id
                                ");

		return $query->result();

    }


    public function get_licencias($where){
        $this->db->where($where);
        $this->db->select('*,DATEDIFF(fecha_vencimiento,now()) as dias_pago');
        $this->db->from('crm_licencias_empresa');
        $this->db->join('crm_planes','crm_planes.id=crm_licencias_empresa.planes_id');
        $query = $this->db->get();
        return $query->result();
    }

    public function agregar_licencia($data){
        $this->db->insert('crm_licencias_empresa',$data);
        return $this->db->insert_id();
    }

    public function get_by_id($where) {
        $this->db->where($where);
        $query = $this->db->get('crm_licencias_empresa');
        return $query->result();        
    }
/*
    public function existe_licencia($where=0){
            
        if(!empty($where)){
            $this->db->where($where);
        }        
        $this->db->select('*');
        $this->db->from('crm_licencias_empresa'); 
        $query = $this->db->get()->result_array();
        
        if(!empty($query)){ 
            return 1;
        }else{            
            return 0;
        }
    }*/

    public function incluir_campo_desactivar_licencia(){
        $sql = "SHOW COLUMNS FROM crm_licencias_empresa LIKE 'desactivada'";

        $existeCampo = $this->db->query($sql)->result();
        if(count($existeCampo) == 0)
        {
            $sql = "ALTER TABLE crm_licencias_empresa   
                ADD COLUMN desactivada BOOLEAN NOT NULL DEFAULT FALSE COMMENT 'Campo para saber si el usuario desactivo la licencia',
                ADD COLUMN fecha_desactivada DATE NULL COMMENT 'Fecha de la desactivación de la licencia'";

            $this->db->query($sql);
        }
    }

    public function update_licencia($where,$data){
        $this->db->where($where);
        $this->db->set($data);   
        return $this->db->update('crm_licencias_empresa');
       
    }    

}
