<?php
//modelo es para las facturas de licencias de usuario
//no tiene nada que ver con las facturas del POS
class Impresoras_Restaurante_model extends CI_Model {
 
    var $connection;
    public function __construct() {

        parent::__construct();
    }

    public function initialize($connection) {

        $this->connection = $connection;
    }

    public function get_impresoras(){
        $this->connection->select('*');
        $this->connection->from('impresoras_restaurante');
        $query = $this->connection->get();
        return $query->result_array();
    }

    public function add($data){
        $this->connection->insert('impresoras_restaurante',$data);
        return $this->connection->insert_id();
    }

    public function update($data){       
        $this->connection->where('id', $data['id']);
        return $this->connection->update('impresoras_restaurante', $data); 
    }

    public function delete($id){
        $this->connection->where('id', $id);
        $band=$this->connection->delete('impresoras_restaurante');
        if($band){
            return "Se ha eliminado correctamente";
        }else{
            return "La impresora no se puede eliminar";
        }
        
    }

    public function impresora_get_by_id($id){
        $this->connection->select('*');
        $this->connection->from('impresoras_restaurante');
        $this->connection->where('id', $id);
        $query = $this->connection->get();
        return $query->row_array();
    }

    public function impresora_get_almacen($id){
        $this->connection->select('id_almacen');
        $this->connection->from('impresoras_restaurante');
        $this->connection->where('id', $id);
        $query = $this->connection->get();
        return $query->row_array();
    }



    /*****************************/

    public function get_numero_factura(){
        $numero_factura = 1;
        $this->db->select_max('numero_factura');
        $query = $this->db->get('crm_factura_licencia');
        foreach ($query->result() as $key => $value) {
            $numero_factura = $value->numero_factura;
        }
        
        return ++$numero_factura;
    }

    public function get_facturas($where){
        $this->db->where($where);
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

    public function createtableimpresora_restaurante(){
        $sql="CREATE TABLE IF NOT EXISTS impresoras_restaurante (
            id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            nombre VARCHAR(20) DEFAULT NULL,
            codigo VARCHAR(15) DEFAULT NULL,
            PRIMARY KEY (id)
            ) ENGINE=INNODB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";
        $this->connection->query($sql);
    }

    public function getApiKey(){
          $this->db->select("api_key");
          $this->db->from("db_config");
          $this->db->where("base_dato",$this->connection->database);
          $this->db->limit(1);
          $result  = $this->db->get();

          if($result->num_rows() > 0){
              return $result->result()[0]->api_key; 
          }else{
            return NULL;
          }
      }

      public function generateApiKey(){
        $db_config = $this->session->userdata('db_config_id');
        $user_id = $this->session->userdata('user_id'); 
        $api_key = md5(uniqid($user_id,true));

        
        $data = array(
            "api_key" => $api_key
        );

        $this->db->where('id',$db_config);
        $this->db->update("db_config",$data);

        //echo $this->db->last_query(); die();
        return $api_key;
    }


}
?>