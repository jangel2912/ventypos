<?php
class Crm_orden extends CI_Model {
 
    var $connection;
    public function __construct() {

        parent::__construct();
    }

    public function check_orden_activa($id_empresa){

        
        $query = $this->db->query("SELECT count(*) as cantidad FROM crm_orden_licencia where idempresas_clientes = '$id_empresa' and estado_orden = 1");
        

        return $query->row()->cantidad;		
    }

    public function get_orden_activa(){

    }

    public function nuevo($id_empresa){
        
        //obtenemos el correlativo de la orden de compra 
        
        $last_orden = $this->get_last_orden($id_empresa);
        $last_orden = (isset($last_orden['numero_orden']) ? $last_orden['numero_orden'] + 1 : 1);
        
        $fecha_orden = $this->input->post('fecha_orden');
        $total_impuesto = $this->input->post('total_impuesto');
        $total_orden = $this->input->post('total_orden');
        $fecha_vencimiento = $this->input->post('fecha_vencimiento');
        $descripcion_orden = $this->input->post('descripcion_orden');
        $notas_orden = $this->input->post('notas_orden');
        $estado_orden = $this->input->post('estado_orden');

        $array_datos = array(
            "fecha_creacion" => date('%d-%m-%Y'),
            "numero_orden" => $last_orden,
            "fecha_orden" => (isset($fecha_orden) ? $fecha_orden : date()),
            "total_impuesto" => (isset($total_impuesto) ? $total_impuesto : 0),
            "total_orden" =>  isset($total_orden) ? $total_orden : 0,
            "fecha_vencimiento" => isset($fecha_vencimiento) ? $fecha_vencimiento : '',
            "descripcion_orden" => isset($descripcion_orden) ? $descripcion_orden : 'Sin descripcion generada',
            "notas_orden" => isset($notas_orden) ? $notas_orden : '',
            "idempresas_clientes" => $id_empresa,
            "estado_orden" => 1
        );



        $this->db->insert('crm_orden_licencia',$array_datos);


    }

    public function get_last_orden($id_empresa){
        $query = $this->db->query("SELECT numero_orden FROM crm_orden_licencia where idempresas_clientes = $id_empresa and estado_orden = 1");
        return $query->result();
    }

    public function add_licencia_orden(){
        "llego";
    }
}
