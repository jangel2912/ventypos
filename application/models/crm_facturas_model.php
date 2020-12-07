<?php
//modelo es para las facturas de licencias de usuario
//no tiene nada que ver con las facturas del POS
class Crm_facturas_model extends CI_Model {
 
    var $connection;
    public function __construct() {

        parent::__construct();
    }

    public function initialize($connection) {

        $this->connection = $connection;
    }

    public function get_numero_factura(){
        $db_host_prod = "produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com";
        $db_username_prod = "vendtyMaster";
        $db_password_prod = "ro_ar_8027*_na";
        $db_prod = mysqli_connect($db_host_prod,$db_username_prod,$db_password_prod, 'vendty2');
        $db_prod->set_charset("utf8");
        $sql = "SELECT * from crm_numeracion_facturacion where id = 1";

        $result = $db_prod->query($sql);
        $data = $result->fetch_assoc();

        $consecutivo = (int)$data['consecutivo'];

        $sql = "UPDATE crm_numeracion_facturacion set consecutivo = '".($consecutivo + 1)."' where id = 1";
        $result = $db_prod->query($sql);

        $db_prod->close();

        return $data['consecutivo'];       
    }

    public function get_numero_factura_electronica(){
        $db_host_prod = "produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com";
        $db_username_prod = "vendtyMaster";
        $db_password_prod = "ro_ar_8027*_na";
        $db_prod = mysqli_connect($db_host_prod,$db_username_prod,$db_password_prod, 'vendty2');
        $db_prod->set_charset("utf8");
        $sql = "SELECT * from crm_numeracion_facturacion where id = 2";

        $result = $db_prod->query($sql);
        $data = $result->fetch_assoc();

        $consecutivo = (int)$data['consecutivo'];

        $sql = "UPDATE crm_numeracion_facturacion set consecutivo = '".($consecutivo + 1)."' where id = 2";
        $result = $db_prod->query($sql);

        $db_prod->close();

        return $data['consecutivo'];        
    }

    public function get_prefijo_factura(){
        $db_host_prod = "produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com";
        $db_username_prod = "vendtyMaster";
        $db_password_prod = "ro_ar_8027*_na";
        $db_prod = mysqli_connect($db_host_prod,$db_username_prod,$db_password_prod, 'vendty2');
        $db_prod->set_charset("utf8");
        $sql = "SELECT * from crm_info_factura_vendty where id = 2";

        $result = $db_prod->query($sql);
        $data = $result->fetch_assoc();
        $result->close();
        return $data['prefijo'];        
    }

    public function update_numero_factura($consecutivo){  
        $this->db->set('consecutivo', $consecutivo);   
        $this->db->update('crm_numeracion_facturacion');                       
    }

    public function get_facturas($where=0){
        if(!empty($where)){
            $this->db->where($where);
        }          
        $this->db->select('*');
        $this->db->from('crm_factura_licencia');
        $this->db->join('crm_empresas_clientes','crm_factura_licencia.idempresas_clientes=crm_empresas_clientes.idempresas_clientes');
        $query = $this->db->get();        
        return $query->result();
    }

    public function get_detalle_factura($where){
        $this->db->where($where);
        $this->db->select('*');
        $this->db->from('crm_detalle_factura_licencia');
        $this->db->join('crm_factura_licencia','crm_factura_licencia.id_factura_licencia=crm_detalle_factura_licencia.id_factura_licencia');
        $query = $this->db->get();
        return $query->result();
    }

    public function agregar_factura($data){
        $this->db->insert('crm_factura_licencia',$data);
        return $this->db->insert_id();
    }

    public function agregar_detalle_factura($data){

        $this->db->insert('crm_detalle_factura_licencia',$data);
        return $this->db->insert_id();
    }

    public function anular_factura($where,$data){
        if(!empty($where)){
            $this->db->where($where);
        }   
        $this->db->set($data);   
        $this->db->update('crm_factura_licencia');
        return 1;
    }


}
?>