<?php
//modelo es para las facturas de licencias de usuario
//no tiene nada que ver con las facturas del POS
class Crm_pagos_licencias_model extends CI_Model {

	var $connection;
    public function __construct() {

        parent::__construct();
    }

    public function get_pagos($where,$or_where=false){
    	$this->db->select('crm_pagos_licencias.*,crm_formas_pago.nombre_forma');
    	$this->db->from('crm_pagos_licencias');
    	$this->db->join('crm_formas_pago','crm_formas_pago.idformas_pago=crm_pagos_licencias.idformas_pago');
    	$this->db->join('crm_factura_licencia','crm_factura_licencia.id_factura_licencia=crm_pagos_licencias.id_factura_licencia','left');
    	$this->db->join('crm_orden_licencia','crm_orden_licencia.id_orden_licencia=crm_pagos_licencias.id_orden_licencia','left');
    	$this->db->where($where);
    	if($or_where){
    		$this->db->or_where($or_where);
    	}
    	$query = $this->db->get();
    	return $query->result();

	}
	
	public function get_by_id($where) {		
		$this->db->select('*');
    	$this->db->from('crm_pagos_licencias');
    	$this->db->where($where);
		$query = $this->db->get();
		$data = $query->result();
    	return $data;
	}
	
	public function update_pago_factura($where,$data) {	
    	$this->db->where($where);
        $this->db->update('crm_pagos_licencias',$data);
        if($this->db->affected_rows() > 0){
            return true;
        }else{
            return false;
        }
	}

	public function delete_pago($where) {	
    	$this->db->where($where);
        $this->db->delete('crm_pagos_licencias');
        if($this->db->affected_rows() > 0){
            return 1;
        }else{
            return 0;
        }
	}
	
	public function ver_pagos($where=0){
        if(!empty($where)){
             $this->db->where($where);
        }
		$this->db->select('crm_pagos_licencias.*,crm_formas_pago.nombre_forma, crm_licencias_empresa.idempresas_clientes,crm_empresas_clientes.nombre_empresa,crm_licencias_empresa.id_almacen');
		$this->db->from('crm_pagos_licencias');
    	$this->db->join('crm_formas_pago','crm_pagos_licencias.idformas_pago=crm_formas_pago.idformas_pago');
    	$this->db->join('crm_licencias_empresa','crm_pagos_licencias.id_licencia=crm_licencias_empresa.idlicencias_empresa');
    	$this->db->join('crm_empresas_clientes','crm_licencias_empresa.idempresas_clientes=crm_empresas_clientes.idempresas_clientes');
        
        return $this->db->get()->result_array();
    }

}