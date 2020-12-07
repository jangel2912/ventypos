<?php

// Proyecto: Sistema Facturacion
// Version: 1.0 Enero 2017
// Programador: Leonardo Molina
// Framework: Codeigniter
// Clase: crm_oportunidades

class Crm_oportunidades_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('crm_model'));
    }

    public function get_data($id_crm = null, $id_oportunidad = null) {
        if (!isset($id_oportunidad)) {
            $query = $this->db->select()
                    ->from('crm_oportunidades')
                    ->where('id_crm', $id_crm)
                    ->get()
                    ->result_array();
            $data = array();
            foreach ($query as $rowQuery) {
                $plan_descripcion = $this->get_plan_id($rowQuery['id_plan']);
                $rowQuery['plan_descripcion'] = isset($plan_descripcion['descripcion']) ? $plan_descripcion['descripcion'] : '';
                $usuario_nombre = $this->get_usuario_id($rowQuery['id_usuario']);
                $rowQuery['usuario_nombre'] = isset($usuario_nombre['nombre']) ? $usuario_nombre['nombre'] : '';
                $data[] = $rowQuery;
            }
        } else {
            $query = $this->db->select()
                    ->from('crm_oportunidades')
                    ->where('id', $id_oportunidad)
                    ->get()
                    ->row_array();
            $plan_descripcion = $this->get_plan_id($query['id_plan']);
            $query['plan_descripcion'] = $plan_descripcion['descripcion'];
            $usuario_nombre = $this->get_usuario_id($query['id_usuario']);
            $query['usuario_nombre'] = isset($usuario_nombre['nombre']) ? $usuario_nombre['nombre'] : '';
            $data = $query;
        }
        return $data;
    }
    
    public function get_dashboard_data($user_email) {

        $query = $this->db->select()
                ->from('crm_estados')
                ->get()
                ->result_array();
        
        $user_log = $this->crm_model->get_usuario_email($user_email);
        
        $data = array();
        foreach ($query as $rowQuery) {
            $dashboard = $this->get_total_data(null, $rowQuery['id'],$user_log);
            $rowQuery['dashboard'] = $dashboard['data'];
            $rowQuery['monto'] = $dashboard['monto'];
            $data[] = $rowQuery;
        }

        return $data;
    }
    
    public function get_total_data($id_crm = null, $estado = null,$user_log = null) {
        $monto = 0;
        if (!isset($id_crm)) {
            $this->db->select()
                    ->from('crm_oportunidades');
            if (isset($estado)) {// Si se filtran todos los registros por estado
                $this->db->where('id_estado', $estado);
            }
            if($user_log['rol_id'] == 0){
                $this->db->where('id_usuario', $user_log['id']);
            }
            $this->db->order_by('fecha_creacion', 'desc');
            $query = $this->db->get()->result_array();
            $data = array();
            foreach ($query as $rowQuery) {
                $estado_descripcion = $this->get_estados_id($rowQuery['id_estado']);
                $rowQuery['estado_descripcion'] = isset($estado_descripcion['descripcion']) ? $estado_descripcion['descripcion'] : '';
                $dias_estado = $this->get_fecha_estado($rowQuery['id'], $rowQuery['id_estado']);
                $day = $dias_estado - $estado_descripcion['dias_alerta'];
                
                $rowQuery['crm'] = $this->crm_model->get_crm_id($rowQuery['id_crm']);
                
                if ($day < 0) {
                    $estado_array = array(
                        'day' => $day,
                        'style' => 'green',
                    );
                } else if ($day == 0) {
                    $estado_array = array(
                        'day' => $day,
                        'style' => 'yellow',
                    );
                } else if ($day > 0) {
                    $estado_array = array(
                        'day' => $day,
                        'style' => 'red',
                    );
                }
                $rowQuery['info_estado'] = $estado_array;
                $monto += $rowQuery['monto'];
                $data[] = $rowQuery;
            }
        } else {
            $query = $this->db->select()
                    ->from('crm')
                    ->where('id', $id_crm)
                    ->get()
                    ->row_array();
            $estado_descripcion = $this->get_estados_id($query['estado']);
            $query['estado_descripcion'] = isset($estado_descripcion['descripcion']) ? $estado_descripcion['descripcion'] : '';
            $data = $query;
        }
        return array('data'=>$data,'monto'=>$monto,);
    }

    public function get_estados_id($estado_id) {
        $query = $this->db->select('*')
                ->from('crm_estados')
                ->where('id', $estado_id)
                ->get()
                ->row_array();
        return $query;
    }

    public function get_plan_id($plan_id) {
        $query = $this->db->select('*')
                ->from('crm_planes')
                ->where('id', $plan_id)
                ->get()
                ->row_array();
        return $query;
    }

    public function get_usuario_id($usuario_id) {
        $query = $this->db->select('*')
                ->from('crm_usuarios')
                ->where('id', $usuario_id)
                ->get()
                ->row_array();
        return $query;
    }
    
    public function select_plan($all = FALSE) {
        $query = $this->db->select('*')
                ->from('crm_planes')
                ->get()
                ->result();

        $data = array();
        if (!empty($all)) {
            $data[0] = "Todos";
        } else {
            $data[''] = "Seleccione";
        }
        foreach ($query as $dato) {
            $data[$dato->id] = $dato->descripcion;
        }
        return $data;
    }

    public function get_fecha_estado($id_crm, $id_estado) {
        $query = $this->db->select('*')
                ->from('crm_fecha_estados')
                ->where('id_estado', $id_estado)
                ->where('id_oportunidad', $id_crm)
                ->order_by('id', 'desc')
                ->get()
                ->row_array();

        if (count($query) > 0) {
            $fecha_estado = new DateTime($query['fecha']);
            $date_now = new DateTime(date('Y-m-d H:i:s'));
            $interval = $fecha_estado->diff($date_now);
            $day = $interval->format('%R%a');
        } else {
            $fecha_creacion = $this->db->select('fecha_creacion')->from('crm_oportunidades')->where('id', $id_crm)->get()->row_array();
            $fecha_estado = new DateTime($fecha_creacion['fecha_creacion']);
            $date_now = new DateTime(date('Y-m-d H:i:s'));
            $interval = $fecha_estado->diff($date_now);
            $day = $interval->format('%R%a');
        }
        return $day;
    }

    public function select_crm_usuarios($all = FALSE) {
        $query = $this->db->select('*')
                ->from('crm_usuarios')
                ->get()
                ->result();

        $data = array();
        if (!empty($all)) {
            $data[0] = "Todos";
        } else {
            $data[''] = "Seleccione";
        }
        foreach ($query as $dato) {
            $data[$dato->id] = $dato->nombre;
        }
        return $data;
    }

}

?>