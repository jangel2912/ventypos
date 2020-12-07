<?php
class Marcas_model extends CI_Model {
    var $connection;

    // Constructor
    public function __construct() {
        parent::__construct();
    }

    // inicializacion
    public function initialize($connection) {
        $this->connection = $connection;
    }

    public function get_data($id=false) {
        $where = array();

        if ($id) {
            $where = array('id' => $id);
        }

        return $this->connection->get_where('marcas', $where)->result();
    }
}
?>