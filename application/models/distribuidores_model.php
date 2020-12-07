<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Distribuidores_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get_suscripciones($fecha_inicio, $fecha_fin, $estado, $tipo_licencia,$vendedor) {
        $filtro_fecha = "";
        $filtro_fecha_suscripciones = "";
        $is_admin = $this->session->userdata('is_admin');
        $username = $this->session->userdata('username');

        if ($fecha_inicio != "" && $fecha_fin != "") {
            $filtro_fecha = " where v.fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
            $filtro_fecha_suscripciones = " where s.fecha BETWEEN '$fecha_inicio' and '$fecha_fin'";
            if ($almacen != "") {
                $filtro_fecha .= " and v.almacen_id = $almacen";
                $filtro_fecha_suscripciones .= " and s.almacen_id = $almacen";
            }
        } elseif ($fecha_inicio != "") {
            $filtro_fecha = " where v.fecha > '$fecha_inicio'";
            $filtro_fecha_suscripciones = " where S.fecha > '$fecha_inicio'";
            if ($almacen != "") {
                $filtro_fecha .= " and v.almacen_id = $almacen";
                $filtro_fecha_suscripciones .= " and S.almacen_id = $almacen";
            }
        } elseif ($fecha_fin != "") {
            $filtro_fecha = " where v.fecha < '$fecha_fin'";
            $filtro_fecha_suscripciones = " where S.fecha < '$fecha_fin'";
            if ($almacen != "") {
                $filtro_fecha .= " and v.almacen_id = $almacen";
                $filtro_fecha_suscripciones .= " and s.almacen_id = $almacen";
            }
        }
    }
}
