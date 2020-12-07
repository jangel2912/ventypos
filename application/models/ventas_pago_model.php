<?php

class Ventas_pago_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function initialize($connection)
    {
        $this->connection = $connection;
    }

    public function consultarFormaPago($nombre)
    {
        $query = $this->connection->get_where('ventas_pago', array('forma_pago' => "$nombre"));

        if (count($query->result()) == 0) {
            return true;
        } else {
            return false;
        }
    }
}
