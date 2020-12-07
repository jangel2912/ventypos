<?php

class Tienda_model extends CI_Model {

    //var $connection;
    // Constructor



    public function __construct() {

        parent::__construct();
    }

    public function add($data) {

        $this->db->insert("tienda", $data);
    }

    public function update_field($field, $value, $user) {

        $this->db->where('id_user', $user);

        $this->db->update('tienda', array($field => $value));
    }

    public function update($data) {



        $this->db->where('id_user', $data['id_user']);

        $this->db->update('tienda', array('shopname' => $data['shopname']));



        $this->db->where('id_user', $data['id_user']);

        $this->db->update('tienda', array('id_almacen' => $data['id_almacen']));



        $this->db->where('id_user', $data['id_user']);

        $this->db->update('tienda', array('activo' => $data['activo']));
        
        $this->db->where('id_user', $data['id_user']);

        $this->db->update('tienda', array('stock_almacen' => $data['stock_almacen']));


        $this->db->where('id_user', $data['id_user']);

        $this->db->update('tienda', array('correo' => $data['correo']));


        $this->db->where('id_user', $data['id_user']);

        $this->db->update('tienda', array('description' => $data['description']));

        $this->db->where('id_user', $data['id_user']);

        $this->db->update('tienda', array('telefono' => $data['telefono']));

    }

    public function update_layout($data) {

        $this->db->where('id_user', $data['id_user']);
        $this->db->update('tienda', array('layout' => $data['layout']));
    }

    public function update_formas_pago($data) {
        $this->db->where('id_user', $data['id_user']);
        $this->db->update('tienda', array(
            'merchantId' => $data['merchantId'],
            'accountId' => $data['accountId'],
            'ApiKey' => $data['ApiKey'],
            'apikeyEPayco' => $data['apikeyEPayco'],
            'idClienteEPayco' => $data['idClienteEPayco'],
            'publickeyEPayco' => $data['publickeyEPayco'],
            'cuentabancaria' => $data['cuentabancaria'],
            'nombrebanco' => $data['nombrebanco'],
            'nombretitular' => $data['nombretitular'],
            'tipocuenta' => $data['tipocuenta'],
            'correo' => $data['correo'],
        ));
    }

    public function get_by_id_user($id_user = 0) {
        $query = $this->db->query("SELECT * FROM  tienda WHERE id_user = '" . $id_user . "'");

        return $query->row_array();
    }

    public function get_by_db_config($db_config_id = 0) {
        $query = $this->db->query("SELECT * FROM  tienda WHERE id_db = '" . $db_config_id . "'");

        return $query->row_array();
    }

    public function buscar($tienda) {
        $query = $this->db->query("SELECT * FROM  tienda WHERE shopname = '" . $tienda . "'");
        $query = $query->row_array();

        if (isset($query['id'])) return true;

        return false;
    }

    public function buscarIgual($tienda, $id) {
        $query = $this->db->query("SELECT * FROM  tienda WHERE shopname = '" . $tienda . "' AND id_user <> '" . $id . "'");
        $query = $query->row_array();

        if (isset($query['id'])) return true;

        return false;
    }
    
    public function dbTienda($tiendaNombre=false)
    {
        if ($tiendaNombre) {
            $tienda = $this->db->get_where("tienda",array('shopname'=>$tiendaNombre))->row();

            if (count($tienda) != 0) {
                $usuario = $this->db->get_where('users',array('id'=>$tienda->id_user))->row();

                if (count($usuario) != 0) {
                    return $usuario->db_config_id;
                }
            }
        }

        return false;
    }

    public function load_data_user($user_id){
        $this->db->select('email,password');
        $this->db->from('users');
        $this->db->where('id',$user_id);
        $result = $this->db->get();
        if ($result->num_rows() > 0){
            $user = $result->result()[0];
            return $user;
        }
    }
  
}
