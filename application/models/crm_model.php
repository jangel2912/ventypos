<?php

// Proyecto: Sistema Facturacion
// Version: 1.0
// Programador: Leonardo Molina
// Framework: Codeigniter
// Clase: crm

class Crm_model extends CI_Model
{

    var $connection;

    public function __construct()
    {
        parent::__construct();
    }

    public function initialize($connection) {

        $this->connection = $connection;

    }

    public function get_total_data($id_crm = null, $estado = null)
    {
        if (!isset($id_crm)) {
            $this->db->select('crm.id,crm.nombre,crm.fecha_creacion,crm.empresa,crm.mail,crm_oportunidades.id_estado,crm_oportunidades.id_usuario')
                ->from('crm');
            $this->db->join('crm_oportunidades', 'crm_oportunidades.id_crm = crm.id');
            if (isset($estado)) { // Si se filtran todos los registros por estado
                $this->db->where('estado', $estado);
            }
            $query = $this->db->get()->result_array();
            $data = array();
            foreach ($query as $rowQuery) {
                $estado_descripcion = $this->get_estados_id($rowQuery['id_estado']);
                $rowQuery['estado_descripcion'] = isset($estado_descripcion['descripcion']) ? $estado_descripcion['descripcion'] : '';
                $usuario_asignado = $this->get_usuario_id($rowQuery['id_usuario']);
                $rowQuery['usuario_asignado'] = isset($usuario_asignado['nombre']) ? $usuario_asignado['nombre'] : '';
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
        return $data;
    }

    public function get_dashboard_data()
    {

        $query = $this->db->select()
            ->from('crm_estados')
            ->get()
            ->result_array();
        $data = array();
        foreach ($query as $rowQuery) {
            $rowQuery['dashboard'] = $this->get_total_data(null, $rowQuery['id']);
            $data[] = $rowQuery;
        }

        return $data;
    }

    public function get_data_actividad($id_crm = null, $id_actividad = null)
    {
        if (!isset($id_actividad)) {
            $query = $this->db->select()
                ->from('crm_actividades')
                ->where('id_crm', $id_crm)
                ->get()
                ->result_array();
            $data = array();
            foreach ($query as $rowQuery) {
                $actividad_descripcion = $this->get_tipo_actividad_id($rowQuery['tipo_actividad']);
                $rowQuery['actividad_descripcion'] = isset($actividad_descripcion['descripcion']) ? $actividad_descripcion['descripcion'] : '';
                $usuario_nombre = $this->get_usuario_id($rowQuery['usuario']);
                $rowQuery['usuario_nombre'] = isset($usuario_nombre['nombre']) ? $usuario_nombre['nombre'] : '';
                $data[] = $rowQuery;
            }
        } else {
            $query = $this->db->select()
                ->from('crm_actividades')
                ->where('id', $id_actividad)
                ->get()
                ->row_array();
            $actividad_descripcion = $this->get_tipo_actividad_id($query['tipo_actividad']);
            $query['actividad_descripcion'] = $actividad_descripcion['descripcion'];
            $usuario_nombre = $this->get_usuario_id($query['usuario']);
            $query['usuario_nombre'] = isset($usuario_nombre['nombre']) ? $usuario_nombre['nombre'] : '';
            $data = $query;
        }
        return $data;
    }

    public function get_data_alerta($id_crm = null, $id_alerta = null)
    {
        if (!isset($id_alerta)) {
            $query = $this->db->select()
                ->from('crm_alertas')
                ->where('id_crm', $id_crm)
                ->get()
                ->result_array();
            $data = array();
            foreach ($query as $rowQuery) {
                $actividad_descripcion = $this->get_tipo_actividad_id($rowQuery['tipo_actividad']);
                $rowQuery['actividad_descripcion'] = isset($actividad_descripcion['descripcion']) ? $actividad_descripcion['descripcion'] : '';
                $usuario_nombre = $this->get_usuario_id($rowQuery['usuario']);
                $rowQuery['usuario_nombre'] = isset($usuario_nombre['nombre']) ? $usuario_nombre['nombre'] : '';
                $data[] = $rowQuery;
            }
        } else {
            $query = $this->db->select()
                ->from('crm_alertas')
                ->where('id', $id_alerta)
                ->get()
                ->row_array();
            $actividad_descripcion = $this->get_tipo_actividad_id($query['tipo_actividad']);
            $query['actividad_descripcion'] = $actividad_descripcion['descripcion'];
            $usuario_nombre = $this->get_usuario_id($query['usuario']);
            $query['usuario_nombre'] = isset($usuario_nombre['nombre']) ? $usuario_nombre['nombre'] : '';
            $data = $query;
        }
        return $data;
    }

    public function get_alertas($email)
    {

        $user_info = $this->get_usuario_email($email);

        $query = $this->db->select()
            ->from('crm_alertas')
            ->where('usuario', $user_info['id'])
            ->where('fecha_programada <=', date('Y-m-d h:i:s'))
            ->where('activo', 1)

            ->order_by('fecha_programada', 'desc')
            ->order_by('hora', 'desc')
            ->get()
            ->result_array();

        $data = array();
        foreach ($query as $rowQuery) {
            $actividad_descripcion = $this->get_tipo_actividad_id($rowQuery['tipo_actividad']);
            $rowQuery['actividad_descripcion'] = isset($actividad_descripcion['descripcion']) ? $actividad_descripcion['descripcion'] : '';
            $usuario_nombre = $this->get_usuario_id($rowQuery['usuario']);
            $rowQuery['usuario_nombre'] = isset($usuario_nombre['nombre']) ? $usuario_nombre['nombre'] : '';
            $crm_nombre = $this->get_crm_id($rowQuery['id_crm']);
            $rowQuery['crm_nombre'] = isset($crm_nombre['nombre']) ? $crm_nombre['nombre'] : '';
            $data[] = $rowQuery;
        }

        return $data;
    }

    public function get_usuario_email($email)
    {
        $query = $this->db->select('*')
            ->from('crm_usuarios')
            ->where('email', $email)
            ->get()
            ->row_array();
        return $query;
    }

    public function get_estados_id($estado_id)
    {
        $query = $this->db->select('*')
            ->from('crm_estados')
            ->where('id', $estado_id)
            ->get()
            ->row_array();
        return $query;
    }

    public function get_tipo_actividad_id($actividad_id)
    {
        $query = $this->db->select('*')
            ->from('crm_tipoactividad')
            ->where('id', $actividad_id)
            ->get()
            ->row_array();
        return $query;
    }

    public function get_usuario_id($usuario_id)
    {
        $query = $this->db->select('*')
            ->from('crm_usuarios')
            ->where('id', $usuario_id)
            ->get()
            ->row_array();
        return $query;
    }

    public function get_crm_id($crm_id)
    {
        $query = $this->db->select('*')
            ->from('crm')
            ->where('id', $crm_id)
            ->get()
            ->row_array();
        return $query;
    }

    public function select_tipo_negocio($all = false, $where = null)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }

        $this->db->select('*');
        $this->db->from('crm_tiponegocio');
        $this->db->order_by('descripcion', 'asc');
        $query = $this->db->get()->result();

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

    public function select_tipo_actividad($all = false)
    {
        $query = $this->db->select('*')
            ->from('crm_tipoactividad')
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

    public function select_estados($all = false)
    {
        $query = $this->db->select('*')
            ->from('crm_estados')
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

    public function select_justificacion($all = false)
    {
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

    public function select_plan($all = false)
    {
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

    public function get_fecha_estado($id_crm, $id_estado)
    {
        $query = $this->db->select('*')
            ->from('crm_fecha_estados')
            ->where('id_estado', $id_estado)
            ->where('id_crm', $id_crm)
            ->order_by('id', 'desc')
            ->get()
            ->row_array();

        if (count($query) > 0) {
            $fecha_estado = new DateTime($query['fecha']);
            $date_now = new DateTime(date('Y-m-d H:i:s'));
            $interval = $fecha_estado->diff($date_now);
            $day = $interval->format('%R%a');
        } else {
            $fecha_creacion = $this->db->select('fecha_creacion')->from('crm')->where('id', $id_crm)->get()->row_array();
            $fecha_estado = new DateTime($fecha_creacion['fecha_creacion']);
            $date_now = new DateTime(date('Y-m-d H:i:s'));
            $interval = $fecha_estado->diff($date_now);
            $day = $interval->format('%R%a');
        }
        return $day;
    }

    public function select_crm_usuarios($all = false)
    {
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

    public function get_usuario_notificacion()
    {

    }

    public function get_usuario_renovacion($where, $plan = 0)
    {

        $this->db->where($where);
        if (!empty($plan)) {
            $this->db->where_not_in('id_plan', $plan);
        }
        $this->db->order_by("fecha_vencimiento, id_almacen", "asc");
        $query = $this->db->get('v_crm_licencias');

        return $query->result();
    }

    public function get_all_planes()
    {
        $query = $this->db->get('crm_planes');
        return $query->result();
    }

    public function get_all_planes_by_distribuidor()
    {
        $query = $this->db->get_where('crm_planes', array('mostrar' => 1));
        return $query->result();
    }

    public function get_planes($where)
    {
        $this->db->where($where);
        $query = $this->db->get('crm_planes');
        return $query->result();
    }

    public function get_planes_All()
    {
        $query = $this->db->get('crm_planes');
        return $query->result();
    }

    public function get_detalle_plan($where)
    {

        $sql_crm_detalles_planes = "select * from crm_detalles_planes $where order by id_plan, nombre_campo";
        return $this->db->query($sql_crm_detalles_planes)->result();
    }

    public function get_distribuidor($where)
    {
        $this->db->where($where);
        $this->db->select('a.*,b.users_id');
        $this->db->from('crm_distribuidores_licencia a');
        $this->db->join('crm_usuarios_distribuidores b', 'a.id_distribuidores_licencia=b.id_distribuidores_licencia');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_id_distribuidor($where)
    {
        $datos = $this->get_distribuidor($where);
        $id_distribuidores = 1;
        foreach ($datos as $key => $value) {
            $id_distribuidores = $value->id_distribuidores_licencia;
        }
        return $id_distribuidores;
    }

    public function get_empresas($where = 0, $limit = 0)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($limit)) {
            $this->db->limit($limit);
        }
        $this->db->order_by('nombre_empresa');
        $query = $this->db->get('crm_empresas_clientes');
        return $query->result();
    }

    public function get_formas_pago($where = null)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }

        $query = $this->db->get('crm_formas_pago');
        return $query->result();
    }

    public function agrear_pago($data)
    {
        $this->db->insert('crm_pagos_licencias', $data);
        return $this->db->insert_id();
    }

    public function agregar_orden_compra($data)
    {
        $this->db->insert('crm_orden_licencia', $data);
        return $this->db->insert_id();
    }

    public function agregar_detalle_orden_compra($data)
    {
        $this->db->insert('crm_detalle_orden_licencia', $data);
        return $this->db->insert_id();
    }

    public function get_pagos_licencia($where)
    {
        $this->db->where($where);
        $this->db->select('*');
        $this->db->from('crm_pagos_licencias');
        $this->db->join('crm_formas_pago', 'crm_formas_pago.idformas_pago=crm_pagos_licencias.idformas_pago');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_distribuidor()
    {
        $this->db->select('*');
        $this->db->from('crm_distribuidores_licencia');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_user($where = 0)
    {
        $array = array('email !=' => "");
        $this->db->where($array);
        $this->db->order_by("email", "asc");
        $this->db->select('id,email,db_config_id');
        $this->db->from('users');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_distribuidor_by_id()
    {
        $user = $this->session->userdata('user_id');

        $this->db->select('crm_distribuidores_licencia.id_distribuidores_licencia as id');
        $this->db->from('crm_master_distribuidores');
        $this->db->join('crm_distribuidores_licencia', 'crm_distribuidores_licencia.id_distribuidores_licencia=crm_master_distribuidores.id_distribuidor_licencia');
        $this->db->where('crm_master_distribuidores.id_user', $user);
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result()[0]->id;
    }

    public function validate_distribuidor()
    {
        $user = $this->session->userdata('user_id');
        $this->db->select('crm_master_distribuidores.*');
        $this->db->from('crm_master_distribuidores');
        $this->db->join('crm_distribuidores_licencia', 'crm_distribuidores_licencia.id_distribuidores_licencia=crm_master_distribuidores.id_distribuidor_licencia');
        $this->db->where('crm_master_distribuidores.id_user', $user);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result()[0]->id_distribuidor_licencia;
        } else {
            return null;
        }
    }
    public function get_info_distribuidor_by_id()
    {
        $user = $this->session->userdata('user_id');
        //$this->db->select('crm_empresas_clientes.*');
        //$this->db->from('crm_empresas_clientes');
        //$this->db->join('crm_distribuidores_licencia','crm_distribuidores_licencia.id_distribuidores_licencia=crm_empresas_clientes.id_distribuidores_licencia');
        //$this->db->join('crm_usuarios_distribuidores','crm_usuarios_distribuidores.users_id=crm_empresas_clientes.id_user_distribuidor');
        //$this->db->where('crm_usuarios_distribuidores.users_id',$user);
        //$this->db->where('crm_empresas_clientes.users_id',$user);

        $this->db->select("*");
        $this->db->from("users");
        $this->db->join('users_groups', 'users_groups.user_id=users.id');
        $this->db->where("users.id", $user);
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->result()[0];
    }

    public function get_vendedores()
    {
        $id_distribuidor = $this->get_distribuidor_by_id();
        $this->db->select('users.*');
        $this->db->from('users');
        $this->db->join('crm_usuarios_distribuidores', 'crm_usuarios_distribuidores.users_id=users.id');
        $this->db->join('users_groups', 'users_groups.user_id=crm_usuarios_distribuidores.users_id');
        $this->db->where('id_distribuidores_licencia', $id_distribuidor);
        $this->db->where('users_groups.group_id', '4');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_clientes()
    {
        $id_distribuidor = $this->get_distribuidor_by_id();
        $this->db->select('users.id,users.email,users.username,users.phone,crm_licencias_empresa.fecha_inicio_licencia,crm_licencias_empresa.fecha_vencimiento,crm_empresas_clientes.nombre_empresa as empresa');
        $this->db->from('users');
        $this->db->join('users_groups', 'users_groups.user_id=users.id');
        $this->db->join('crm_licencias_empresa', 'crm_licencias_empresa.id_db_config=users.db_config_id');
        $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.id_db_config=users.db_config_id');
        $this->db->where('crm_empresas_clientes.id_distribuidores_licencia', $id_distribuidor);
        //$this->db->where('crm_licencias_empresa.id_db_config',$this->session->userdata('db_config_id'));
        $this->db->where('users_groups.group_id', '2');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_all_clientes()
    {

        $user_distribuidor = $this->validate_distribuidor();
        if ($user_distribuidor != null) {
            $this->db->select('crm_licencias_empresa.planes_id as plan,users.id as usuario_id,users.email,users.username,users.phone,crm_licencias_empresa.fecha_inicio_licencia,crm_licencias_empresa.fecha_vencimiento,crm_empresas_clientes.nombre_empresa as empresa');
            $this->db->from('crm_licencias_empresa');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('users', 'users.db_config_id=crm_empresas_clientes.id_db_config');
            $this->db->where('crm_empresas_clientes.id_distribuidores_licencia', $user_distribuidor);
            $query = $this->db->get();
            echo $this->db->last_query();die();
            return $query->result();
        }
    }

    public function get_all_vencidos($tipo, $fecha_inicio = null, $fecha_fin = null)
    {

        if ($tipo == "mensual") {
            $dias_vigencia = 30;
        } else if ($tipo == "anual") {
            $dias_vigencia = 365;
        } else {
            $dias_vigencia = 90;
        }

        if ($fecha_inicio == null && $fecha_fin == null) {
            $hoy = date('Y-m-d');
            $fecha_fin = strtotime('-1 day', strtotime($hoy));
            $fecha_fin = date('Y-m-d', $fecha_fin);
            $fecha_inicio = date('Y-m-01');
        } else {
            $fecha_fin = strtotime('-1 day', strtotime($fecha_fin));
            $fecha_fin = date('Y-m-d', $fecha_fin);
        }

        // echo"fecha_inicio=".$fecha_inicio;
        //  echo"fecha_fin=".$fecha_fin; die();

        $user_distribuidor = $this->validate_distribuidor();
        if ($user_distribuidor != null) {
            $where = "crm_licencias_empresa.fecha_vencimiento BETWEEN '$fecha_inicio' and '$fecha_fin' AND crm_licencias_empresa.planes_id != '1' AND (`crm_planes`.`id` !=  15) AND (`crm_planes`.`id` !=  16)  AND (`crm_planes`.`id` !=  17)  AND (`crm_planes`.`dias_vigencia` =  " . $dias_vigencia . ") AND  (`crm_licencias_empresa`.`estado_licencia` =  15)";
            $this->db->select('users.db_config_id as id,users.phone,crm_licencias_empresa.fecha_inicio_licencia,crm_licencias_empresa.fecha_vencimiento,crm_empresas_clientes.nombre_empresa,crm_planes.nombre_plan,users.email,crm_planes.valor_plan');
            $this->db->from('crm_licencias_empresa');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('users', 'users.id=crm_empresas_clientes.idusuario_creacion');
            $this->db->join('crm_planes', 'crm_licencias_empresa.planes_id=crm_planes.id');
            $this->db->where('crm_empresas_clientes.id_distribuidores_licencia', $user_distribuidor);
            $this->db->where($where);
            $this->db->group_by('crm_licencias_empresa.idlicencias_empresa');
            $this->db->order_by('crm_licencias_empresa.fecha_inicio_licencia');

            $query = $this->db->get();
            //echo $this->db->last_query(); die();
            return $query->result();
        }
    }

    public function get_all_nuevos($tipo, $fecha_inicio = null, $fecha_fin = null)
    {

        if ($fecha_inicio == null && $fecha_fin == null) {
            $fecha_inicio = date('Y-m-01');
            $fecha_fin = date('Y-m-d');
        }

        if ($tipo == "mensual") {
            $dias_vigencia = 30;
        } else if ($tipo == "anual") {
            $dias_vigencia = 365;
        } else {
            $dias_vigencia = 90;
        }

        $user_distribuidor = $this->validate_distribuidor();
        if ($user_distribuidor != null) {

            $this->db->select('users.db_config_id as id,users.phone,crm_licencias_empresa.fecha_inicio_licencia,crm_licencias_empresa.fecha_vencimiento,crm_empresas_clientes.nombre_empresa,crm_planes.nombre_plan,users.email,(crm_pagos_licencias.monto_pago - crm_pagos_licencias.descuento_pago) as total,crm_planes.valor_plan');
            $this->db->from('crm_licencias_empresa');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('users', 'users.id=crm_empresas_clientes.idusuario_creacion');
            $this->db->join('crm_planes', 'crm_licencias_empresa.planes_id=crm_planes.id');
            $this->db->join('crm_pagos_licencias', 'crm_pagos_licencias.id_licencia=crm_licencias_empresa.idlicencias_empresa');
            $this->db->where('crm_empresas_clientes.id_distribuidores_licencia', $user_distribuidor);
            $this->db->where('crm_pagos_licencias.fecha_pago >=', $fecha_inicio);
            $this->db->where('crm_pagos_licencias.fecha_pago <=', $fecha_fin);
            $this->db->where('crm_pagos_licencias.estado_pago', 1);
            $this->db->where('crm_licencias_empresa.fecha_activacion >=', $fecha_inicio);
            $this->db->where('crm_licencias_empresa.fecha_activacion <=', $fecha_fin);
            $this->db->where('crm_licencias_empresa.planes_id !=', 1);
            $this->db->where('crm_licencias_empresa.planes_id !=', 15);
            $this->db->where('crm_licencias_empresa.planes_id !=', 16);
            $this->db->where('crm_licencias_empresa.planes_id !=', 17);
            $this->db->where('crm_planes.dias_vigencia =', $dias_vigencia);
            $this->db->group_by('crm_pagos_licencias.fecha_pago,crm_pagos_licencias.id_licencia');
            $this->db->order_by('crm_licencias_empresa.fecha_inicio_licencia');
            $query = $this->db->get();
            //echo $this->db->last_query(); die();
            return $query->result();
        }
    }
    public function getMonthDays_mes($mes)
    {
        $inicio = date("$mes-d");
        $array = explode('-', $inicio);
        $Year = $array[0];
        $Month = $array[1];

        if (is_callable("cal_days_in_month")) {
            return cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
        } else {
            return date("d", mktime(0, 0, 0, $Month + 1, 0, $Year));
        }
    }
    public function getMonthDays()
    {
        $inicio = date('Y-m-d');
        $array = explode('-', $inicio);
        $Year = $array[0];
        $Month = $array[1];

        if (is_callable("cal_days_in_month")) {
            return cal_days_in_month(CAL_GREGORIAN, $Month, $Year);
        } else {
            return date("d", mktime(0, 0, 0, $Month + 1, 0, $Year));
        }
    }

    public function planes_mensuales_por_renovar($fecha_inicio = null, $fecha_fin = null)
    {

        $dias = $this->getMonthDays();

        $user_distribuidor = $this->validate_distribuidor();
        if ($user_distribuidor != null) {
            $data = array();
            $i = 0;

            if ($fecha_inicio == null && $fecha_fin == null) {
                $fecha_inicio = date('Y-m-d');
                $fecha_fin = date('Y-m-' . $dias);
            }

            $where = "`crm_empresas_clientes`.`id_distribuidores_licencia` =  '" . $user_distribuidor . "'
            AND (`crm_planes`.`dias_vigencia` =  30) AND (`crm_planes`.`id` !=  1) AND (`crm_planes`.`id` !=  15) AND (`crm_planes`.`id` !=  16)  AND (`crm_planes`.`id` !=  17)
            AND `crm_licencias_empresa`.`estado_licencia` =  1
            AND `crm_licencias_empresa`.`fecha_vencimiento` BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "'";

            $this->db->select('users.db_config_id as id,users.phone,crm_licencias_empresa.fecha_inicio_licencia,crm_licencias_empresa.fecha_vencimiento,crm_empresas_clientes.nombre_empresa,crm_planes.nombre_plan,users.email,crm_planes.valor_plan as valor_plan');
            $this->db->from('crm_licencias_empresa');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('users', 'users.id=crm_empresas_clientes.idusuario_creacion');
            $this->db->join('crm_planes', 'crm_licencias_empresa.planes_id=crm_planes.id');
            //$this->db->join('crm_pagos_licencias','crm_pagos_licencias.id_licencia=crm_licencias_empresa.idlicencias_empresa');
            $this->db->where($where);
            $this->db->group_by('crm_licencias_empresa.idlicencias_empresa,crm_licencias_empresa.id_almacen');
            $this->db->order_by('crm_licencias_empresa.fecha_inicio_licencia');
            //$this->db->group_by('crm_pagos_licencias.fecha_pago,crm_pagos_licencias.id_licencia');
            $query = $this->db->get();
            $result = $query->result();

            // echo $this->db->last_query(); die();
            $cantidad_licencias = $query->num_rows();
            $total_pagos = 0;
            foreach ($result as $value) {
                $total_pagos += $value->valor_plan;
            }

            return array(
                'clientes' => $result,
                'total_pagos' => $total_pagos,
                'cantidad_licencias' => $cantidad_licencias,
            );
        }
    }

    public function planes_anuales_por_renovar($fecha_inicio = null, $fecha_fin = null)
    {
        $dias = $this->getMonthDays();
        $user_distribuidor = $this->validate_distribuidor();
        if ($user_distribuidor != null) {
            $data = array();
            $i = 0;
            if ($fecha_inicio == null && $fecha_fin == null) {
                $fecha_inicio = date('Y-m-d');
                $fecha_fin = date('Y-m-' . $dias);
            }
            $where = "`crm_empresas_clientes`.`id_distribuidores_licencia` =  '" . $user_distribuidor . "'
            AND (`crm_planes`.`dias_vigencia` =  365) AND (`crm_planes`.`id` !=  15) AND (`crm_planes`.`id` !=  16)  AND (`crm_planes`.`id` !=  17)
            AND `crm_licencias_empresa`.`estado_licencia` =  1
            AND `crm_licencias_empresa`.`fecha_vencimiento` BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "'";

            $this->db->select('users.db_config_id as id,users.phone,crm_licencias_empresa.fecha_inicio_licencia,crm_licencias_empresa.fecha_vencimiento,crm_empresas_clientes.nombre_empresa,crm_planes.nombre_plan,users.email,crm_planes.valor_plan as valor_plan');
            $this->db->from('crm_licencias_empresa');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('users', 'users.db_config_id=crm_empresas_clientes.id_db_config');
            $this->db->join('crm_planes', 'crm_licencias_empresa.planes_id=crm_planes.id');
            //$this->db->join('crm_pagos_licencias','crm_pagos_licencias.id_licencia=crm_licencias_empresa.idlicencias_empresa');
            $this->db->where($where);
            $this->db->group_by('crm_licencias_empresa.idlicencias_empresa');
            $this->db->order_by('crm_licencias_empresa.fecha_inicio_licencia');
            //$this->db->group_by('crm_pagos_licencias.fecha_pago,crm_pagos_licencias.id_licencia');
            $query = $this->db->get();
            //echo $this->db->last_query();
            $result = $query->result();

            $cantidad_licencias = $query->num_rows();
            $total_pagos = 0;
            foreach ($result as $value) {
                $total_pagos += $value->valor_plan;
            }

            return array(
                'clientes' => $result,
                'total_pagos' => $total_pagos,
                'cantidad_licencias' => $cantidad_licencias,
            );
        }
    }

    public function planes_mensuales_pagados($fecha_inicio = null, $fecha_fin = null)
    {
        $user_distribuidor = $this->validate_distribuidor();
        if ($user_distribuidor != null) {
            $data = array();
            $i = 0;
            if ($fecha_inicio == null && $fecha_fin == null) {
                $fecha_inicio = date('Y-m-1');
                $fecha_fin = date('Y-m-d');
                $mes_activacion = date('m');
            } else {
                $mes_activacion = date("m", strtotime($fecha_inicio));
            }

            $where = "`crm_empresas_clientes`.`id_distribuidores_licencia` =  '" . $user_distribuidor . "'
            AND (`crm_planes`.`dias_vigencia` =  30) AND (`crm_planes`.`id` !=  15) AND (`crm_planes`.`id` !=  16)  AND (`crm_planes`.`id` !=  17)
            AND `crm_licencias_empresa`.`estado_licencia` =  1
            AND `crm_pagos_licencias`.`estado_pago` =  1
            AND (`crm_licencias_empresa`.`fecha_activacion` is NULL OR MONTH(`crm_licencias_empresa`.`fecha_activacion`) <> '" . $mes_activacion . "')
            AND `crm_pagos_licencias`.`fecha_pago` BETWEEN  '" . $fecha_inicio . "' AND  '" . $fecha_fin . "'";

            $this->db->select('users.db_config_id as id,users.phone,crm_licencias_empresa.fecha_inicio_licencia,crm_licencias_empresa.fecha_vencimiento,crm_empresas_clientes.nombre_empresa,crm_planes.nombre_plan,crm_planes.valor_plan,users.email,crm_pagos_licencias.*');
            $this->db->from('crm_licencias_empresa');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('users', 'users.id=crm_empresas_clientes.idusuario_creacion');
            $this->db->join('crm_planes', 'crm_licencias_empresa.planes_id=crm_planes.id');
            $this->db->join('crm_pagos_licencias', 'crm_pagos_licencias.id_licencia=crm_licencias_empresa.idlicencias_empresa');
            $this->db->where($where);
            $this->db->group_by('crm_pagos_licencias.fecha_pago,crm_pagos_licencias.id_licencia');
            $this->db->order_by('crm_licencias_empresa.fecha_inicio_licencia');
            $query = $this->db->get();
            //echo $this->db->last_query();die();
            $result = $query->result();
            $cantidad_licencias = $query->num_rows();
            $total_pagos = 0;
            foreach ($result as $value) {
                $total_pagos += $value->monto_pago - $value->descuento_pago;
            }

            return array(
                'clientes' => $result,
                'total_pagos' => $total_pagos,
                'cantidad_licencias' => $cantidad_licencias,
            );
        }
    }

    public function planes_mensuales_pagados_total()
    {
        $user_distribuidor = $this->validate_distribuidor();
        if ($user_distribuidor != null) {
            $data = array();
            $i = 0;
            $fecha_inicial = date('Y-m-1');
            $fecha_limite = date('Y-m-d');
            $where = "`crm_empresas_clientes`.`id_distribuidores_licencia` =  '" . $user_distribuidor . "'
            AND (`crm_planes`.`dias_vigencia` =  30) AND (`crm_planes`.`id` !=  15) AND (`crm_planes`.`id` !=  16)  AND (`crm_planes`.`id` !=  17)
            AND `crm_licencias_empresa`.`estado_licencia` =  1
            AND `crm_licencias_empresa`.`fecha_inicio_licencia` >=  '" . $fecha_inicial . "'";

            $this->db->select('users.db_config_id as id,crm_licencias_empresa.fecha_inicio_licencia,crm_licencias_empresa.fecha_vencimiento,crm_empresas_clientes.nombre_empresa,crm_planes.nombre_plan,users.email');
            $this->db->from('crm_licencias_empresa');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('users', 'users.db_config_id=crm_empresas_clientes.id_db_config');
            $this->db->join('crm_planes', 'crm_licencias_empresa.planes_id=crm_planes.id');
            $this->db->where($where);
            $query = $this->db->get();
            //echo $this->db->last_query();
            $result = $query->result();
            $cantidad_licencias = $query->num_rows();
            $total_pagos = 0;
            foreach ($result as $value) {
                $total_pagos += $value->monto_pago - $value->descuento_pago;
            }

            return array(
                'clientes' => $result,
                'total_pagos' => $total_pagos,
                'cantidad_licencias' => $cantidad_licencias,
            );
        }
    }

    public function planes_anuales_pagados($fecha_inicio = null, $fecha_fin = null)
    {
        $user_distribuidor = $this->validate_distribuidor();
        if ($user_distribuidor != null) {
            $data = array();
            $i = 0;
            if ($fecha_inicio == null && $fecha_fin == null) {
                $fecha_inicio = date('Y-m-1');
                $fecha_fin = date('Y-m-d');
                $mes_activacion = date('m');
            } else {
                $mes_activacion = date('m', strtotime($fecha_inicio));
            }

            $where = "`crm_empresas_clientes`.`id_distribuidores_licencia` =  '" . $user_distribuidor . "'
            AND (`crm_planes`.`dias_vigencia` =  365) AND (`crm_planes`.`id` !=  15) AND (`crm_planes`.`id` !=  16)  AND (`crm_planes`.`id` !=  17)
            AND `crm_licencias_empresa`.`estado_licencia` =  1
            AND `crm_pagos_licencias`.`estado_pago` =  1
            AND (`crm_licencias_empresa`.`fecha_activacion` is NULL OR MONTH(`crm_licencias_empresa`.`fecha_activacion`) <> '" . $mes_activacion . "')
            AND `crm_pagos_licencias`.`fecha_pago` BETWEEN  '" . $fecha_inicio . "' AND  '" . $fecha_fin . "'";

            $this->db->select('users.db_config_id as id,users.phone,crm_licencias_empresa.fecha_inicio_licencia,crm_licencias_empresa.fecha_vencimiento,crm_empresas_clientes.nombre_empresa,crm_planes.nombre_plan,crm_planes.valor_plan,users.email,crm_pagos_licencias.*');
            $this->db->from('crm_licencias_empresa');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('users', 'users.id=crm_empresas_clientes.idusuario_creacion');
            $this->db->join('crm_planes', 'crm_licencias_empresa.planes_id=crm_planes.id');
            $this->db->join('crm_pagos_licencias', 'crm_pagos_licencias.id_licencia=crm_licencias_empresa.idlicencias_empresa');
            $this->db->where($where);
            $this->db->group_by('crm_pagos_licencias.fecha_pago,crm_pagos_licencias.id_licencia');
            $this->db->order_by('crm_licencias_empresa.fecha_inicio_licencia');
            $query = $this->db->get();
            //echo $this->db->last_query();
            $result = $query->result();
            $cantidad_licencias = $query->num_rows();
            $total_pagos = 0;
            foreach ($result as $value) {
                $total_pagos += $value->monto_pago - $value->descuento_pago;
            }

            return array(
                'clientes' => $result,
                'total_pagos' => $total_pagos,
                'cantidad_licencias' => $cantidad_licencias,
            );
        }
    }

    public function get_all_pagos_by_ano()
    {
        $dias = $this->getMonthDays();

        $fecha_final = date('Y-m-01');

        //$hoy = date('Y-m-j');
        $fechaAnterior = strtotime('-2 month', strtotime($fecha_final));
        $fechaAnterior = date('Y-m-j', $fechaAnterior);

        $fecha_limite = date('Y-m-' . $dias);

        $user_distribuidor = $this->validate_distribuidor();
        if ($user_distribuidor != null) {
            $where = "`crm_empresas_clientes`.`id_distribuidores_licencia` =  '" . $user_distribuidor . "'
            AND (`crm_planes`.`dias_vigencia` =  30 || `crm_planes`.`dias_vigencia` = 365 ) AND (`crm_planes`.`id` !=  15) AND (`crm_planes`.`id` !=  16)  AND (`crm_planes`.`id` !=  17)
            AND `crm_licencias_empresa`.`estado_licencia` =  1
            AND `crm_pagos_licencias`.`estado_pago` =  1
            AND `crm_pagos_licencias`.`fecha_pago` BETWEEN  '" . $fechaAnterior . "' AND  '" . $fecha_limite . "'";

            //$where = "crm_pagos_licencias.fecha_pago BETWEEN '$fechaAnterior' and '$fecha_limite' AND `crm_pagos_licencias`.`estado_pago` =  1 AND (`crm_licencias_empresa`.`planes_id` !=  1)";
            $this->db->select(' MONTH(crm_pagos_licencias.fecha_pago) AS mes,(crm_pagos_licencias.monto_pago - crm_pagos_licencias.descuento_pago ) as total,crm_planes.valor_plan');
            $this->db->from('crm_pagos_licencias');
            $this->db->join('crm_licencias_empresa', 'crm_licencias_empresa.idlicencias_empresa=crm_pagos_licencias.id_licencia');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('users', 'users.id=crm_empresas_clientes.idusuario_creacion');
            $this->db->join('crm_planes', 'crm_planes.id=crm_licencias_empresa.planes_id');
            $this->db->where('crm_empresas_clientes.id_distribuidores_licencia', $user_distribuidor);
            $this->db->where($where);
            $this->db->group_by('Month(crm_pagos_licencias.fecha_pago),crm_pagos_licencias.id_licencia');
            $query = $this->db->get();

            $total_pagos = array();
            /*for($i=1; $i<=12; $i++){
            $total_pagos[$i] = array("mes" => $i,"total" => 0);
            }*/

            foreach ($query->result_array() as $value) {
                $total_pagos[$value["mes"]] = array("mes" => $value["mes"], "total" => 0, "valor_plan" => 0);
            }

            foreach ($query->result_array() as $value) {
                //$total_pagos[$value["mes"]] = array("mes" => $value["mes"], "total" => 0);
                $total_pagos[$value["mes"]]["mes"] = $value["mes"];
                $total_pagos[$value["mes"]]["total"] += $value["total"];
                $total_pagos[$value["mes"]]["valor_plan"] += $value["valor_plan"];
            }

            return $total_pagos;
        }
    }

    public function get_all_pagos_by_last_ano()
    {
        $hoy = date('Y-m-j');
        $fechaAnterior = strtotime('-1 year', strtotime($hoy));
        $fechaAnterior = date('Y-m-j', $fechaAnterior);

        $user_distribuidor = $this->validate_distribuidor();
        if ($user_distribuidor != null) {
            $where = "crm_pagos_licencias.fecha_pago BETWEEN '$fechaAnterior' and '$hoy' AND `crm_pagos_licencias`.`estado_pago` =  1 AND (`crm_licencias_empresa`.`planes_id` !=  1)";
            $this->db->select('COUNT(crm_licencias_empresa.idlicencias_empresa) as total_licencias,SUM(crm_pagos_licencias.monto_pago - crm_pagos_licencias.descuento_pago) as total_pagos');
            $this->db->from('crm_pagos_licencias');
            $this->db->join('crm_licencias_empresa', 'crm_licencias_empresa.idlicencias_empresa=crm_pagos_licencias.id_licencia');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('crm_planes', 'crm_planes.id=crm_licencias_empresa.planes_id');
            $this->db->where('crm_empresas_clientes.id_distribuidores_licencia', $user_distribuidor);
            $this->db->where($where);
            $this->db->group_by(' crm_pagos_licencias.fecha_pago,crm_pagos_licencias.id_licencia');
            $query = $this->db->get();
            return $query->result();
        }
    }

    /*Funcion para trear los pagos pendientes hasta el 31 de diciembre del año actual*/
    public function get_pagos_pendientes_by_ano()
    {
        $hoy = date('Y-m-d');
        $fecha_fin = date('Y-12-31');
        $user_distribuidor = $this->validate_distribuidor();
        if ($user_distribuidor != null) {
            $data = array();
            $i = 0;

            $where = "`crm_empresas_clientes`.`id_distribuidores_licencia` = '" . $user_distribuidor . "'
            AND (`crm_licencias_empresa`.`planes_id` !=  1)
            AND `crm_licencias_empresa`.`estado_licencia` =  1
            AND crm_licencias_empresa.fecha_vencimiento BETWEEN '$hoy' and '$fecha_fin'";

            $this->db->select('crm_planes.valor_plan as valor_plan');
            $this->db->from('crm_licencias_empresa');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('users', 'users.db_config_id=crm_empresas_clientes.id_db_config');
            $this->db->join('crm_planes', 'crm_licencias_empresa.planes_id=crm_planes.id');
            $this->db->join('crm_pagos_licencias', 'crm_pagos_licencias.id_licencia=crm_licencias_empresa.idlicencias_empresa');
            $this->db->where($where);
            $this->db->group_by('crm_pagos_licencias.fecha_pago,crm_pagos_licencias.id_licencia');
            $query = $this->db->get();
            $result = $query->result();

            $cantidad_licencias = $query->num_rows();
            $total_pagos = 0;
            foreach ($result as $value) {
                $total_pagos += $value->valor_plan;
            }

            return array(
                'total_pagos' => $total_pagos,
                'cantidad_licencias' => $cantidad_licencias,
            );
        }

        $user_distribuidor = $this->validate_distribuidor();
        if ($user_distribuidor != null) {
            // $where = "crm_licencias_empresa.idlicencias_empresa NOT IN(SELECT id_licencia FROM crm_pagos_licencias) AND crm_licencias_empresa.fecha_vencimiento BETWEEN '$hoy' and '$fecha_fin'";
            $where = "crm_licencias_empresa.fecha_vencimiento BETWEEN '$hoy' and '$fecha_fin'";
            $this->db->select('count(crm_empresas_clientes.id_distribuidores_licencia) as pagos,SUM(crm_planes.valor_plan) as total');
            $this->db->from('crm_licencias_empresa');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('crm_planes', 'crm_planes.id=crm_licencias_empresa.planes_id');
            $this->db->where('crm_empresas_clientes.id_distribuidores_licencia', $user_distribuidor);
            $this->db->where($where);
            $query = $this->db->get();
            return $query->result()[0];
        }
    }

    public function get_clientes_distribuidor()
    {
        $id_distribuidor = $this->get_distribuidor_by_id();
        $this->db->select('users.id,users.email,users.username,users.phone,crm_licencias_empresa.fecha_inicio_licencia,crm_licencias_empresa.fecha_vencimiento,crm_empresas_clientes.nombre_empresa as empresa');
        $this->db->from('users');
        $this->db->join('crm_usuarios_distribuidores', 'crm_usuarios_distribuidores.users_id=users.id');
        $this->db->join('users_groups', 'users_groups.user_id=crm_usuarios_distribuidores.users_id');
        $this->db->join('crm_licencias_empresa', 'crm_licencias_empresa.id_db_config=users.db_config_id');
        $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.id_db_config=users.db_config_id');
        $this->db->where('crm_empresas_clientes.id_distribuidores_licencia', $id_distribuidor);
        //$this->db->where('crm_licencias_empresa.id_db_config',$this->session->userdata('db_config_id'));
        $this->db->where('users_groups.group_id', '2');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_ajax_data_clientes_distribuidor($fecha_inicio = null, $fecha_fin = null, $estado = null, $tipo_plan = null, $vendedor = null)
    {
        $user_distribuidor = $this->validate_distribuidor();
        if ($user_distribuidor != null) {
            $where = "crm_empresas_clientes.id_distribuidores_licencia = $user_distribuidor ";
            $where .= "AND users_groups.group_id = 2 ";

            if ($fecha_inicio != null && $fecha_fin != null && $fecha_inicio != 'null' && $fecha_fin != 'null') {
                $where .= "AND crm_licencias_empresa.fecha_inicio_licencia >= '$fecha_inicio' AND crm_licencias_empresa.fecha_vencimiento <= '$fecha_fin' ";
            }

            if ($tipo_plan != null && $tipo_plan != 'null') {
                $where .= "AND crm_licencias_empresa.planes_id = '$tipo_plan' ";
            }

            if ($vendedor != null && $vendedor != 'null') {
                $where .= "AND crm_empresas_clientes.idusuario_creacion = $vendedor ";
            }

            $this->db->select('users.id as user_id,users.db_config_id as db_config,crm_licencias_empresa.planes_id as plan,users.email as email,users.username,users.phone,crm_licencias_empresa.fecha_inicio_licencia,crm_licencias_empresa.fecha_vencimiento,crm_empresas_clientes.nombre_empresa as empresa,crm_planes.nombre_plan,crm_licencias_empresa.fecha_vencimiento as estado,crm_empresas_clientes.pais,crm_empresas_clientes.ciudad_empresa,crm_empresas_clientes.tipo_negocio');
            $this->db->from('crm_licencias_empresa');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('crm_planes', 'crm_planes.id=crm_licencias_empresa.planes_id');
            $this->db->join('users', 'users.db_config_id=crm_empresas_clientes.id_db_config');
            $this->db->join('users_groups', 'users_groups.user_id=users.id');
            $this->db->where($where);
            $this->db->group_by('users.db_config_id');
            $query = $this->db->get();

            //echo $this->db->last_query();
            $data = array();
            foreach ($query->result() as $cliente) {
                $estado = '';
                if ($cliente->plan == 2) {
                    $estado = 'Pruebas';
                } else if ($cliente->fecha_vencimiento > date('Y m d')) {
                    $estado = 'Activo';
                } else {
                    $estado = 'Suspendido';
                }

                $url_licencia = site_url('administracion_vendty/distribuidores/usuario_licencias/' . $cliente->db_config . '/' . $cliente->user_id);

                $data[] = array(
                    '<a href="' . $url_licencia . '">' . $cliente->username . '</a>',
                    $cliente->email,
                    $cliente->empresa,
                    $cliente->pais,
                    $cliente->ciudad_empresa,
                    $cliente->tipo_negocio,
                    $cliente->phone,
                    //$cliente->nombre_plan,
                    //$estado

                );
            }

            return array(
                'aaData' => $data,
            );
        }

    }

    public function get_ajax_data_licencias($fecha_inicio = null, $fecha_fin = null, $estado = null, $tipo_plan = null, $vendedor = null)
    {

        $id_distribuidor = $this->get_distribuidor_by_id();

        $where = "crm_empresas_clientes.id_distribuidores_licencia = $id_distribuidor ";
        $where .= "AND users_groups.group_id = 2 ";
        $where .= "AND crm_licencias_empresa.estado_licencia = 1 ";

        if ($fecha_inicio != null && $fecha_fin != null && $fecha_inicio != 'null' && $fecha_fin != 'null') {
            $where .= "AND crm_licencias_empresa.fecha_inicio_licencia >= '$fecha_inicio' AND crm_licencias_empresa.fecha_vencimiento <= '$fecha_fin' ";
        }

        if ($tipo_plan != null && $tipo_plan != 'null') {
            $where .= "AND crm_licencias_empresa.planes_id = '$tipo_plan' ";
        }

        if ($vendedor != null && $vendedor != 'null') {
            $where .= "AND crm_empresas_clientes.idusuario_creacion = $vendedor ";
        }

        $this->db->select('crm_licencias_empresa.planes_id as plan,users.email,users.username,users.phone,crm_licencias_empresa.fecha_inicio_licencia,crm_licencias_empresa.fecha_vencimiento,crm_empresas_clientes.nombre_empresa as empresa,crm_planes.nombre_plan');
        $this->db->from('crm_licencias_empresa');
        $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
        $this->db->join('crm_planes', 'crm_planes.id=crm_licencias_empresa.planes_id');
        //$this->db->join('crm_usuarios_distribuidores','crm_usuarios_distribuidores.users_id=crm_empresas_clientes.idusuario_creacion');
        $this->db->join('users', 'crm_licencias_empresa.id_db_config=users.db_config_id');
        $this->db->join('users_groups', 'users_groups.user_id=users.id');
        $this->db->where($where);
        $this->db->where('crm_licencias_empresa.planes_id !=', 15);
        $this->db->where('crm_licencias_empresa.planes_id !=', 16);
        $this->db->where('crm_licencias_empresa.planes_id !=', 17);
        $this->db->group_by('crm_licencias_empresa.idlicencias_empresa');

        $query = $this->db->get();

        //echo $this->db->last_query();
        $data = array();
        foreach ($query->result() as $cliente) {
            $estado = '';

            if ($cliente->plan == 2) {
                $estado = 'Pruebas';
            } else if ($cliente->fecha_vencimiento > date('Y m d')) {
                $estado = 'Activo';
            } else {
                $estado = 'Suspendido';
            }

            $data[] = array(
                $cliente->nombre_plan,
                $cliente->username,
                $cliente->email,
                $cliente->empresa,
                $cliente->fecha_inicio_licencia,
                $cliente->fecha_vencimiento,
                $cliente->phone,
                $estado,

            );
        }

        return array(
            'aaData' => $data,
        );
    }

    public function get_ajax_data_pagos($fecha_inicio = null, $fecha_fin = null, $estado = null, $tipo_plan = null, $cliente = null)
    {

        $hoy = date('Y-m-j');
        $fechaAnterior = strtotime('-1 year', strtotime($hoy));
        $fechaAnterior = date('Y-m-j', $fechaAnterior);

        $user_distribuidor = $this->validate_distribuidor();
        if ($user_distribuidor != null) {

            $where = "crm_empresas_clientes.id_distribuidores_licencia = $user_distribuidor ";
            $where .= "AND users_groups.group_id = 2 ";

            if ($fecha_inicio != null && $fecha_fin != null) {
                $where .= "AND crm_licencias_empresa.fecha_inicio_licencia >= '$fecha_inicio' AND crm_licencias_empresa.fecha_vencimiento <= '$fecha_fin' ";
            }

            if ($tipo_plan != null) {
                $where .= "AND crm_licencias_empresa.planes_id = '$tipo_plan' ";
            }

            if ($cliente != null) {
                $where .= "AND users.id = $cliente ";
            }

            $where .= "AND crm_pagos_licencias.fecha_pago BETWEEN '$fechaAnterior' and '$hoy'";
            $this->db->select('crm_pagos_licencias.fecha_pago, crm_pagos_licencias.monto_pago, crm_pagos_licencias.observacion_pago, crm_pagos_licencias.estado_pago, users.email,users.username,users.phone,crm_licencias_empresa.fecha_inicio_licencia,crm_licencias_empresa.fecha_vencimiento,crm_empresas_clientes.nombre_empresa as empresa,crm_planes.nombre_plan');
            $this->db->from('crm_pagos_licencias');
            $this->db->join('crm_licencias_empresa', 'crm_licencias_empresa.idlicencias_empresa=crm_pagos_licencias.id_licencia');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('crm_planes', 'crm_planes.id=crm_licencias_empresa.planes_id');
            $this->db->join('users', 'crm_licencias_empresa.id_db_config=users.db_config_id');
            $this->db->join('users_groups', 'users_groups.user_id=users.id');
            $this->db->where($where);
            $this->db->where('crm_licencias_empresa.planes_id !=', 15);
            $this->db->where('crm_licencias_empresa.planes_id !=', 16);
            $this->db->where('crm_licencias_empresa.planes_id !=', 17);
            $query = $this->db->get();
            //echo $this->db->last_query();
            $data = array();
            foreach ($query->result() as $value) {

                $data[] = array(
                    $value->fecha_pago,
                    $value->monto_pago,
                    $value->observacion_pago,
                    $value->nombre_plan,
                    $value->username,
                    $value->fecha_inicio_licencia,
                    $value->fecha_vencimiento,
                    $value->estado_pago,
                );
            }

            return array(
                'aaData' => $data,
            );
        }

    }
    // total de licencias
    public function total_licencias($tipo = null, $where2 = null)
    {

        if (!empty($where2)) {
            $licencias = "l.idlicencias_empresa not in (" . $where2 . ")";
        }

        //busco distribuidor
        $user = $this->session->userdata('user_id');
        $distribuidor = $this->get_distribuidor2(array('users_id' => $user));
        $iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];
        $hoy = date('Y-m-d');
        $dias_vigencias = "";

        if ($tipo == "mensual") {
            $dias_vigencias = "(pl.dias_vigencia=30 or pl.dias_vigencia=90 or pl.dias_vigencia=180)";
        } else {
            $dias_vigencias = "(pl.dias_vigencia=365)";
        }

        $this->db->select('l.`id_db_config`, l.`idlicencias_empresa`, l.`fecha_activacion`, l.`fecha_inicio_licencia`,l.`fecha_vencimiento`,pl.`nombre_plan` , pl.`valor_plan`, pl.`dias_vigencia`,e.`nombre_empresa`, u2.email, u2.phone ,d.`nombre_distribuidor`, u.`username` AS vendedor');
        $this->db->from('crm_licencias_empresa l');
        $this->db->join('crm_planes pl', 'l.planes_id = pl.id', 'inner');
        $this->db->join('crm_empresas_clientes e', 'l.idempresas_clientes=e.idempresas_clientes', 'inner');
        $this->db->join('crm_distribuidores_licencia d', 'e.id_distribuidores_licencia=d.id_distribuidores_licencia', 'inner');
        $this->db->join('users u', 'e.id_user_distribuidor=u.id', 'inner');
        $this->db->join('users u2', 'e.idusuario_creacion=u2.id', 'inner');
        $this->db->where('l.fecha_vencimiento >=', "$hoy");
        $this->db->where('l.planes_id !=', 1);
        $this->db->where('l.planes_id !=', 15);
        $this->db->where('l.planes_id !=', 16);
        $this->db->where('l.planes_id !=', 17);
        $this->db->where('l.planes_id !=', 25);
        $this->db->where($dias_vigencias);
        if (!empty($where2)) {
            $this->db->where($licencias);
        }
        if ($iddistribuidor != 1) {
            $this->db->where("e.id_distribuidores_licencia =" . $iddistribuidor);
        }
        $this->db->group_by('l.idlicencias_empresa , l.id_almacen, pl.dias_vigencia');
        $this->db->order_by('l.fecha_vencimiento');
        $query = $this->db->get();
        //echo $this->db->last_query(); die();
        return $query->result();
    }

    public function planes_pagados($tipo = null, $fecha_inicio = null, $fecha_fin = null)
    {

        //busco distribuidor
        $user = $this->session->userdata('user_id');
        $distribuidor = $this->get_distribuidor2(array('users_id' => $user));
        $iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];
        $dias_vigencia = "";
        $data = array();
        $i = 0;
        if ($fecha_inicio == null && $fecha_fin == null) {
            $fecha_inicio = date('Y-m-1');
            $fecha_fin = date('Y-m-d');
            $mes_activacion = date('m');
        } else {
            $mes_activacion = date("m", strtotime($fecha_inicio));
        }

        if ($tipo == "mensual") {
            $dias_vigencia = " AND (pl.dias_vigencia=30 or pl.dias_vigencia=90 or pl.dias_vigencia=180)";
        } else if ($tipo == "anual") {
            $dias_vigencia = " AND pl.dias_vigencia=365 ";
        }

        $where = "p.fecha_pago BETWEEN '$fecha_inicio' AND '$fecha_fin'
                AND (l.fecha_activacion IS NULL OR (l.fecha_activacion < '$fecha_inicio' OR l.fecha_activacion > '$fecha_fin' ))
                AND p.estado_pago = 1
                AND p.estado = 0
                AND l.planes_id != 1
                AND l.planes_id != 25
                $dias_vigencia";

        $this->db->select('u.db_config_id AS id,u.phone,l.idlicencias_empresa,l.fecha_activacion,l.fecha_inicio_licencia,l.fecha_vencimiento,e.nombre_empresa,pl.nombre_plan, pl.valor_plan, (p.monto_pago - p.descuento_pago + p.retencion_pago) AS total, u.email, e.id_distribuidores_licencia, d.nombre_distribuidor, e.id_user_distribuidor, u2.`username` AS vendedor, pl.dias_vigencia, p.*');
        $this->db->from('crm_pagos_licencias p');
        $this->db->join('crm_licencias_empresa l', 'p.id_licencia = l.idlicencias_empresa', 'inner');
        $this->db->join('crm_factura_licencia f', 'p.idpagos_licencias = f.id_pago', 'left');
        $this->db->join('crm_empresas_clientes e', 'l.idempresas_clientes = e.idempresas_clientes', 'inner');
        $this->db->join('crm_planes pl', 'l.planes_id = pl.id', 'inner');
        $this->db->join('users u', 'e.idusuario_creacion = u.id', 'inner');
        $this->db->join('users u2', 'e.id_user_distribuidor=u2.id', 'inner');
        $this->db->join('crm_distribuidores_licencia d', 'e.id_distribuidores_licencia=d.id_distribuidores_licencia', 'inner');
        $this->db->where($where);
        if ($iddistribuidor != 1) {
            $this->db->where("e.id_distribuidores_licencia =" . $iddistribuidor);
        }
        $this->db->group_by('p.id_licencia, p.fecha_pago, p.ref_payco, p.transaction_id');
        $this->db->order_by('p.id_licencia, p.fecha_pago');
        $query = $this->db->get();
        //echo $this->db->last_query(); die();
        $result = $query->result();
        $cantidad_licencias = $query->num_rows();
        $total_pagos = 0;
        foreach ($result as $value) {
            $total_pagos += $value->monto_pago - $value->descuento_pago + $value->retencion_pago;
        }

        return array(
            'clientes' => $result,
            'total_pagos' => $total_pagos,
            'cantidad_licencias' => $cantidad_licencias,
        );
    }

    public function activas_pagadas_antes($tipo = null, $fecha_inicio = null, $fecha_fin = null)
    {
        //  $tipo='mensual';
        // $fecha_inicio="2018-12-01";
        // $fecha_fin="2018-12-31";
        $id_nuevos = "";
        $id_pagadas = "";
        $id_vencidos = "";
        $id_porrenovar = "";
        $id_todos = "";

        //busco las nuevas
        $nuevos = $this->get_all_nuevos2($tipo, $fecha_inicio, $fecha_fin);
        foreach ($nuevos as $key => $value) {
            $id_nuevos .= "," . $value->idlicencias_empresa;
        }
        //busco las pagadas
        $pagadas = $this->planes_pagados($tipo, $fecha_inicio, $fecha_fin);
        foreach ($pagadas['clientes'] as $key => $value) {
            $id_pagadas .= "," . $value->idlicencias_empresa;
        }
        //busco las Suspendidas
        $vencidos = $this->get_all_vencidos2($tipo, $fecha_inicio, $fecha_fin);
        foreach ($vencidos as $key => $value) {
            $id_vencidos .= "," . $value->idlicencias_empresa;
        }
        //busco Por renovar
        $porrenovar = $this->planes_por_renovar($tipo, $fecha_inicio, $fecha_fin);
        foreach ($porrenovar['clientes'] as $key => $value) {
            $id_porrenovar .= "," . $value->idlicencias_empresa;
        }

        $id_nuevos = trim($id_nuevos, ",");
        $id_pagadas = trim($id_pagadas, ",");
        $id_vencidos = trim($id_vencidos, ",");
        $id_porrenovar = trim($id_porrenovar, ",");

        if (!empty($id_nuevos)) {
            $id_todos .= "," . $id_nuevos;
        }
        if (!empty($id_pagadas)) {
            $id_todos .= "," . $id_pagadas;
        }
        if (!empty($id_vencidos)) {
            $id_todos .= "," . $id_vencidos;
        }
        if (!empty($id_porrenovar)) {
            $id_todos .= "," . $id_porrenovar;
        }
        $id_todos = trim($id_todos, ",");

        //busco todas las que faltan
        if (!empty($id_todos)) {
            $todas = $this->total_licencias($tipo, $id_todos);
        }
        return $todas;

    }

    public function planes_por_renovar($tipo = null, $fecha_inicio = null, $fecha_fin = null)
    {

        //busco distribuidor
        $user = $this->session->userdata('user_id');
        $distribuidor = $this->get_distribuidor2(array('users_id' => $user));
        $iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];
        $dias_vigencias = "";
        if ($iddistribuidor != 1) {
            $this->db->where("e.id_distribuidores_licencia =" . $iddistribuidor);
        }

        $dias = $this->getMonthDays();
        $data = array();
        $i = 0;
        $fecha_inicio = date('Y-m-d');

        if ($fecha_fin == null) {
            $fecha_fin = date('Y-m-' . $dias);
        }

        if ($tipo == "mensual") {
            $dias_vigencias = "(pl.dias_vigencia=30 or pl.dias_vigencia=90 or pl.dias_vigencia=180)";
        } else {
            $dias_vigencias = "(pl.dias_vigencia=365)";
        }

        $this->db->select('u.db_config_id AS id,u.phone,l.idlicencias_empresa ,l.fecha_activacion,l.fecha_inicio_licencia,l.fecha_vencimiento,e.nombre_empresa,pl.nombre_plan,u.email,pl.valor_plan, pl.valor_plan as total, e.id_distribuidores_licencia, d.nombre_distribuidor, e.id_user_distribuidor, u2.`username` AS vendedor, pl.dias_vigencia');
        $this->db->from('crm_licencias_empresa l');
        $this->db->join('crm_empresas_clientes e', 'e.idempresas_clientes=l.idempresas_clientes', 'inner');
        $this->db->join('crm_planes pl', 'l.planes_id=pl.id', 'inner');
        $this->db->join('users u', 'u.id=e.idusuario_creacion', 'inner');
        $this->db->join('users u2', 'e.id_user_distribuidor=u2.id', 'inner');
        $this->db->join('crm_distribuidores_licencia d', 'e.id_distribuidores_licencia=d.id_distribuidores_licencia', 'inner');
        $this->db->where("l.fecha_vencimiento BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "'");
        $this->db->where('l.planes_id !=', 1);
        $this->db->where('l.planes_id !=', 15);
        $this->db->where('l.planes_id !=', 16);
        $this->db->where('l.planes_id !=', 17);
        $this->db->where('l.planes_id !=', 25);
        $this->db->where($dias_vigencias);
        $this->db->group_by('l.idlicencias_empresa,l.id_almacen');
        $this->db->order_by('l.fecha_vencimiento');
        $query = $this->db->get();
        //echo $this->db->last_query(); die();
        $result = $query->result();

        $cantidad_licencias = $query->num_rows();
        $total_pagos = 0;
        foreach ($result as $value) {
            $total_pagos += $value->valor_plan;
        }

        return array(
            'clientes' => $result,
            'total_pagos' => $total_pagos,
            'cantidad_licencias' => $cantidad_licencias,
        );

    }

    public function nuevosid($tipo = null, $fecha_inicio = null, $fecha_fin = null)
    {
        //busco distribuidor
        $user = $this->session->userdata('user_id');
        $distribuidor = $this->get_distribuidor2(array('users_id' => $user));
        $iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];
        $dias_vigencias = "";

        if ($tipo == "mensual") {
            $dias_vigencias = " and (p.dias_vigencia=30 or p.dias_vigencia=90 or p.dias_vigencia=180)";
        } else {
            $dias_vigencias = " and (p.dias_vigencia=365)";
        }

        $this->db->select('*');
        $this->db->from('crm_licencias_empresa l');
        $this->db->join('crm_empresas_clientes e', 'e.idempresas_clientes=l.idempresas_clientes', 'inner');
        $this->db->join('crm_planes p', 'l.planes_id=p.id', 'inner');
        $this->db->where("l.fecha_activacion BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "' " . $dias_vigencias);
        $this->db->where('l.planes_id !=', 1);
        $this->db->where('l.planes_id !=', 15);
        $this->db->where('l.planes_id !=', 16);
        $this->db->where('l.planes_id !=', 17);
        $this->db->where('l.planes_id !=', 25);
        if ($iddistribuidor != 1) {
            $this->db->where("e.id_distribuidores_licencia =" . $iddistribuidor);
        }
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    public function get_ajax_data_clientes_by_graphics2($fecha_inicio = null, $fecha_fin = null)
    {

        $fecha_fin_r = $fecha_fin;
        $hoy = $fecha_fin_r;
        if ($fecha_inicio == null && $fecha_fin == null) {
            $dias = $this->getMonthDays();
            $fecha_inicio = date('Y-m-1');
            $fecha_fin = date('Y-m-' . $dias);
            $fecha_fin_r = null;
            $hoy = date('Y-m-d');
        }

        $inicio = new DateTime($fecha_inicio);
        $fin = new DateTime($fecha_fin);

        $diff = $inicio->diff($fin);

        if ($diff->days <= 31) {
            $dias = explode('-', $fecha_inicio);
            $filter_inicial = $dias[2];
            $filter_final = $filter_inicial + $diff->days;

            $categories = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30');
        } else {
            $meses = explode('-', $fecha_inicio);
            $filter_inicial = $meses[1];
            $filter_final = $filter_inicial + $diff->m;
            $categories = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
        }

        $mes = array();

        $fechaAnterior = strtotime('-1 year', strtotime($hoy));
        $fechaAnterior = date('Y-m-j', $fechaAnterior);

        //busco mi distribuidor
        $user = $this->session->userdata('user_id');
        $distribuidor = $this->get_distribuidor2(array('users_id' => $user));
        $iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];

        if (count($categories) == 30) {
            $q = "DAY";
        } else {
            $q = "MONTH";
        }

        //SUSCRIPCIONES
        $this->db->select('' . $q . '(l.fecha_inicio_licencia) as dia,l.fecha_inicio_licencia,COUNT(*) AS total');
        $this->db->from('crm_licencias_empresa l');
        $this->db->join('crm_empresas_clientes e', 'e.idempresas_clientes=l.idempresas_clientes', 'inner');
        $this->db->where("l.fecha_inicio_licencia BETWEEN '$fecha_inicio' AND '$fecha_fin'");
        $this->db->where('l.planes_id', 1);
        if ($iddistribuidor != 1) {
            $this->db->where("e.id_distribuidores_licencia =" . $iddistribuidor);
        }
        $this->db->group_by('' . $q . '(l.fecha_inicio_licencia)');
        $suscripciones = $this->db->get();
        //echo "<br>suscripciones=".$this->db->last_query(); die();

        //nuevos
        $this->db->select('' . $q . '(l.fecha_activacion) as dia,l.fecha_activacion,COUNT(*) AS total');
        $this->db->from('crm_licencias_empresa l');
        $this->db->join('crm_empresas_clientes e', 'e.idempresas_clientes=l.idempresas_clientes', 'inner');
        $this->db->join('crm_planes p', 'l.planes_id=p.id', 'inner');
        $this->db->where("l.fecha_activacion BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "' AND (p.dias_vigencia = 30 || p.dias_vigencia = 90 || p.dias_vigencia = 365)");
        $this->db->where('l.planes_id !=', 1);
        $this->db->where('l.planes_id !=', 15);
        $this->db->where('l.planes_id !=', 16);
        $this->db->where('l.planes_id !=', 17);
        $this->db->where('l.planes_id !=', 25);
        if ($iddistribuidor != 1) {
            $this->db->where("e.id_distribuidores_licencia =" . $iddistribuidor);
        }
        $this->db->group_by('' . $q . '(l.fecha_activacion)');
        $nuevos = $this->db->get();
        //echo $this->db->last_query(); die();

        //Suspendidas
        $this->db->select('' . $q . '(l.fecha_vencimiento) as dia, l.fecha_vencimiento, COUNT(*) AS total');
        $this->db->from('crm_licencias_empresa l');
        $this->db->join('crm_empresas_clientes e', 'e.idempresas_clientes=l.idempresas_clientes', 'inner');
        $this->db->join('crm_planes p', 'l.planes_id=p.id', 'inner');
        $this->db->where("l.fecha_vencimiento BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "'");
        $this->db->where('l.planes_id !=', 1);
        $this->db->where('l.planes_id !=', 15);
        $this->db->where('l.planes_id !=', 16);
        $this->db->where('l.planes_id !=', 17);
        $this->db->where('l.planes_id !=', 25);
        $this->db->where('l.estado_licencia =', 15);
        if ($iddistribuidor != 1) {
            $this->db->where("e.id_distribuidores_licencia =" . $iddistribuidor);
        }
        $this->db->group_by('' . $q . '(l.fecha_vencimiento)');
        $suspendidos = $this->db->get();
        //echo $this->db->last_query(); die();

        $data = array();
        $total_pruebas = 0;
        $total_nuevos = 0;
        $total_suspendidos = 0;
        $array_pruebas = array();
        $array_nuevos = array();
        $array_suspendidos = array();
        $fn_array_pruebas = array();
        $fn_array_nuevos = array();
        $fn_array_suspendidos = array();

        for ($i = $filter_inicial; $i <= $filter_final; $i++) {
            $array_pruebas[$i] = 0;
            $array_nuevos[$i] = 0;
            $array_suspendidos[$i] = 0;
        }

        foreach ($suscripciones->result() as $value) {
            $array_pruebas[$value->dia] = intval($value->total);
            $total_pruebas = $total_pruebas + $value->total;
        }

        foreach ($nuevos->result() as $value) {
            $array_nuevos[$value->dia] = intval($value->total);
            $total_nuevos = $total_nuevos + $value->total;
        }

        foreach ($suspendidos->result() as $value) {
            $array_suspendidos[$value->dia] = intval($value->total);
            $total_suspendidos = $total_suspendidos + $value->total;
        }

        foreach ($array_nuevos as $nuevo) {
            $fn_array_nuevos[] = $nuevo;
        }
        foreach ($array_pruebas as $prueba) {
            $fn_array_pruebas[] = $prueba;
        }
        foreach ($array_suspendidos as $suspendido) {
            $fn_array_suspendidos[] = $suspendido;
        }

        //busco los id de los nuevos MENSAULES y anuales
        $nuevos_m = $this->nuevosid('mensual', $fecha_inicio, $fecha_fin);

        $id_nuevos_m = "";
        foreach ($nuevos_m as $key => $value) {
            $id_nuevos_m .= "," . $value->idlicencias_empresa;
        }

        $id_nuevos_m = trim($id_nuevos_m, ",");

        $nuevos_anual = $this->nuevosid('anual', $fecha_inicio, $fecha_fin);

        $id_nuevos_anual = "";
        foreach ($nuevos_anual as $key => $value) {
            $id_nuevos_anual .= "," . $value->idlicencias_empresa;
        }

        $id_nuevos_anual = trim($id_nuevos_anual, ",");

        //update estadisiticas
        $est_nuevos_mensuales_all = $this->get_all_nuevos2('mensual', $fecha_inicio, $fecha_fin);
        $est_nuevos_pagos_mensuales = 0;
        $est_nuevos_mensuales = [];
        $id_nuevos_anual = "";
        $anterior = 0;
        foreach ($est_nuevos_mensuales_all as $key => $nuevo) {
            if ($anterior == $nuevo->idlicencias_empresa) {
                continue;
            }
            array_push($est_nuevos_mensuales, $est_nuevos_mensuales_all[$key]);
            $anterior = $nuevo->idlicencias_empresa;
            $est_nuevos_pagos_mensuales += $nuevo->total;
        }

        $est_nuevos_anuales = $this->get_all_nuevos2('anual', $fecha_inicio, $fecha_fin);
        $est_nuevos_pagos_anuales = 0;
        foreach ($est_nuevos_anuales as $nuevo) {
            $est_nuevos_pagos_anuales += $nuevo->total;
        }

        $est_vencidos_mensual = $this->get_all_vencidos2('mensual', $fecha_inicio, $fecha_fin);
        $est_vencidos_pagos_mensual = 0;
        $id_vencidos_m = "";
        $id_vencidos_anual = "";
        foreach ($est_vencidos_mensual as $vencido) {
            $est_vencidos_pagos_mensual += $vencido->valor_plan;
            $id_vencidos_m .= "," . $vencido->idlicencias_empresa;
        }
        $id_vencidos_m = trim($id_vencidos_m, ",");

        $est_vencidos_anual = $this->get_all_vencidos2('anual', $fecha_inicio, $fecha_fin);
        $est_vencidos_pagos_anual = 0;
        foreach ($est_vencidos_anual as $vencido) {
            $est_vencidos_pagos_anual += $vencido->valor_plan;
            $id_vencidos_anual .= "," . $vencido->idlicencias_empresa;
        }
        $id_vencidos_anual = trim($id_vencidos_anual, ",");

        $est_planes_mensuales_pagados = $this->planes_pagados('mensual', $fecha_inicio, $fecha_fin);
        $id_pagadas_m = "";
        $id_pagadas_anual = "";
        //busco los id
        foreach ($est_planes_mensuales_pagados['clientes'] as $key => $value) {
            $id_pagadas_m .= "," . $value->idlicencias_empresa;
        }
        $id_pagadas_m = trim($id_pagadas_m, ",");

        $est_planes_anuales_pagados = $this->planes_pagados('anual', $fecha_inicio, $fecha_fin);
        //busco los id
        foreach ($est_planes_anuales_pagados['clientes'] as $key => $value) {
            $id_pagadas_anual .= "," . $value->idlicencias_empresa;
        }
        $id_pagadas_anual = trim($id_pagadas_anual, ",");

        $est_por_renovar_mensual = $this->planes_por_renovar('mensual', $fecha_inicio, $fecha_fin_r);
        $id_porrenovar_m = "";
        $id_porrenovar_anual = "";

        foreach ($est_por_renovar_mensual['clientes'] as $key => $value) {
            $id_porrenovar_m .= "," . $value->idlicencias_empresa;
        }
        $id_porrenovar_m = trim($id_porrenovar_m, ",");

        $est_por_renovar_anual = $this->planes_por_renovar('anual', $fecha_inicio, $fecha_fin_r);
        foreach ($est_por_renovar_anual['clientes'] as $key => $value) {
            $id_porrenovar_anual .= "," . $value->idlicencias_empresa;
        }
        $id_porrenovar_anual = trim($id_porrenovar_anual, ",");

        //busco las licencias que faltan pagadas antes mensuales
        $todas_m = "";
        $id_todos_m = "";
        $todas_pagos_m = 0;

        if (!empty($id_nuevos_m)) {
            $id_todos_m .= "," . $id_nuevos_m;
        }
        if (!empty($id_pagadas_m)) {
            $id_todos_m .= "," . $id_pagadas_m;
        }
        if (!empty($id_vencidos_m)) {
            $id_todos_m .= "," . $id_vencidos_m;
        }
        if (!empty($id_porrenovar_m)) {
            $id_todos_m .= "," . $id_porrenovar_m;
        }
        $id_todos_m = trim($id_todos_m, ",");

        //busco todas las que faltan
        if (!empty($id_todos_m)) {
            $todas_m = $this->total_licencias('mensual', $id_todos_m);
            foreach ($todas_m as $key => $value) {
                $todas_pagos_m += $value->valor_plan;
            }
        }
        //busco las licencias que faltan pagadas antes anuales
        $todas_anual = "";
        $id_todos_anual = "";
        $todas_pagos_anual = 0;
        if (!empty($id_nuevos_anual)) {
            $id_todos_anual .= "," . $id_nuevos_anual;
        }
        if (!empty($id_pagadas_anual)) {
            $id_todos_anual .= "," . $id_pagadas_anual;
        }
        if (!empty($id_vencidos_anual)) {
            $id_todos_anual .= "," . $id_vencidos_anual;
        }
        if (!empty($id_porrenovar_anual)) {
            $id_todos_anual .= "," . $id_porrenovar_anual;
        }
        $id_todos_anual = trim($id_todos_anual, ",");

        //busco todas las que faltan
        if (!empty($id_todos_anual)) {
            $todas_anual = $this->total_licencias('anual', $id_todos_anual);
            foreach ($todas_anual as $key => $value) {
                $todas_pagos_anual += $value->valor_plan;
            }
        }

        //busco distribuidor
        if ($iddistribuidor != 1) {
            $todas_m = array();
            $$todas_pagos_m = 0;
            $todas_anual = array();
            $todas_pagos_anual = 0;
        }

        $est_total_licencias_mensual = count($todas_m) + $est_por_renovar_mensual["cantidad_licencias"] + $est_planes_mensuales_pagados["cantidad_licencias"] + count($est_vencidos_mensual);
        $est_total_licencias_mensual_pagos = '$' . number_format($todas_pagos_m + $est_por_renovar_mensual["total_pagos"] + $est_planes_mensuales_pagados["total_pagos"] + $est_vencidos_pagos_mensual);

        $est_total_licencias_anual = count($todas_anual) + $est_por_renovar_anual["cantidad_licencias"] + $est_planes_anuales_pagados["cantidad_licencias"] + count($est_vencidos_anual);
        $est_total_licencias_anual_pagos = '$' . number_format($todas_pagos_anual + $est_por_renovar_anual["total_pagos"] + $est_planes_anuales_pagados["total_pagos"] + $est_vencidos_pagos_anual);

        /* Formateamos los valores con pagos*/
        $est_planes_mensuales_pagados["total_pagos"] = '$' . number_format($est_planes_mensuales_pagados["total_pagos"]);
        $est_planes_anuales_pagados["total_pagos"] = '$' . number_format($est_planes_anuales_pagados["total_pagos"]);
        $est_por_renovar_mensual["total_pagos"] = '$' . number_format($est_por_renovar_mensual["total_pagos"]);
        $est_por_renovar_anual["total_pagos"] = '$' . number_format($est_por_renovar_anual["total_pagos"]);

        $data = array(
            'suscripciones' => $total_pruebas,
            'activos' => $total_nuevos,
            'suspendidos' => $total_suspendidos,
            'array_suscripciones' => $fn_array_pruebas,
            'array_activos' => $fn_array_nuevos,
            'array_suspendidos' => $fn_array_suspendidos,
            'categories' => $categories,
            'nuevos_mensuales' => $est_nuevos_mensuales,
            'nuevos_mensuales_pagos' => '$' . number_format($est_nuevos_pagos_mensuales),
            'nuevos_anuales' => $est_nuevos_anuales,
            'nuevos_anuales_pagos' => '$' . number_format($est_nuevos_pagos_anuales),
            'mensuales_vencidos' => $est_vencidos_mensual,
            'mensuales_vencidos_pagos' => '$' . number_format($est_vencidos_pagos_mensual),
            'anuales_vencidos' => $est_vencidos_anual,
            'anuales_vencidos_pagos' => '$' . number_format($est_vencidos_pagos_anual),
            'pagados_antes_m' => count($todas_m),
            'pagados_antes_m_pagos' => '$' . number_format($todas_pagos_m),
            'pagados_antes_anual' => count($todas_anual),
            'pagados_antes_anual_pagos' => '$' . number_format($todas_pagos_anual),
            'mensuales_pagados' => $est_planes_mensuales_pagados,
            'anuales_pagados' => $est_planes_anuales_pagados,
            'mensuales_por_renovar' => $est_por_renovar_mensual,
            'anuales_por_renovar' => $est_por_renovar_anual,
            'total_mensuales' => $est_total_licencias_mensual,
            'total_mensuales_pagos' => $est_total_licencias_mensual_pagos,
            'total_anuales' => $est_total_licencias_anual,
            'total_anuales_pagos' => $est_total_licencias_anual_pagos,
        );

        return $data;

    }

    //obtener los nuevos
    public function get_all_nuevos2($tipo = null, $fecha_inicio = null, $fecha_fin = null)
    {

        //busco distribuidor
        $user = $this->session->userdata('user_id');
        $distribuidor = $this->get_distribuidor2(array('users_id' => $user));
        $iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];
        $dias_vigencias = "";

        if ($fecha_inicio == null && $fecha_fin == null) {
            $fecha_inicio = date('Y-m-01');
            $fecha_fin = date('Y-m-d');
        }

        if ($tipo == "mensual") {
            $dias_vigencias = " AND (pl.dias_vigencia=30 or pl.dias_vigencia=90 or pl.dias_vigencia=180)";
        } else {
            $dias_vigencias = " AND (pl.dias_vigencia=365)";
        }

        /*
        $this->db->select('u.db_config_id AS id,u.phone,p.fecha_pago,l.idlicencias_empresa, l.fecha_activacion,l.fecha_inicio_licencia,l.fecha_vencimiento,e.nombre_empresa,pl.nombre_plan,u.email,(p.monto_pago - p.descuento_pago + p.retencion_pago) AS total,pl.valor_plan, e.id_distribuidores_licencia, d.nombre_distribuidor, e.id_user_distribuidor, u2.`username` AS vendedor');
        $this->db->from('crm_licencias_empresa l');
        $this->db->join('crm_empresas_clientes e','e.idempresas_clientes=l.idempresas_clientes','inner');
        $this->db->join('crm_planes pl','l.planes_id=pl.id','inner');
        $this->db->join('users u','e.idusuario_creacion=u.id','inner');
        $this->db->join('users u2','e.id_user_distribuidor=u2.id','inner');
        $this->db->join('crm_pagos_licencias p','l.idlicencias_empresa=p.id_licencia','inner');
        $this->db->join('crm_distribuidores_licencia d','e.id_distribuidores_licencia=d.id_distribuidores_licencia','inner');
        $this->db->where("l.fecha_activacion BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'");
        $this->db->where("p.fecha_pago BETWEEN '".$fecha_inicio."' AND '".$fecha_fin."'");
        $this->db->where('l.planes_id !=',1);
        $this->db->where('l.planes_id !=',15);
        $this->db->where('l.planes_id !=',16);
        $this->db->where('l.planes_id !=',17);
        $this->db->where('l.planes_id !=',25);
        $this->db->where($dias_vigencias);
        if($iddistribuidor!=1){
        $this->db->where("e.id_distribuidores_licencia =".$iddistribuidor);
        }
        $this->db->group_by('p.id_licencia,p.fecha_pago,p.ref_payco,p.transaction_id');
        $this->db->order_by('l.fecha_activacion, p.fecha_pago');   */

        ##nuevo con datos que tienen fecha activación y las que no tienen pago asociados
        $sql = "
            (SELECT `u`.`db_config_id` AS id, `u`.`phone`, `p`.`fecha_pago`, `l`.`idlicencias_empresa`, `l`.`fecha_activacion`,
            `l`.`fecha_inicio_licencia`, `l`.`fecha_vencimiento`, `e`.`nombre_empresa`, `pl`.`nombre_plan`, `u`.`email`,
            (p.monto_pago - p.descuento_pago + p.retencion_pago) AS total, `pl`.`valor_plan`, `e`.`id_distribuidores_licencia`,
            `d`.`nombre_distribuidor`, `e`.`id_user_distribuidor`, `u2`.`username` AS vendedor, pl.dias_vigencia
            FROM (`crm_licencias_empresa` l)
            INNER JOIN `crm_empresas_clientes` e ON `e`.`idempresas_clientes`=`l`.`idempresas_clientes`
            INNER JOIN `crm_planes` pl ON `l`.`planes_id`=`pl`.`id`
            INNER JOIN `users` u ON `e`.`idusuario_creacion`=`u`.`id`
            INNER JOIN `users` u2 ON `e`.`id_user_distribuidor`=`u2`.`id`
            LEFT JOIN `crm_pagos_licencias` p ON `l`.`idlicencias_empresa`=`p`.`id_licencia`
            INNER JOIN `crm_distribuidores_licencia` d ON `e`.`id_distribuidores_licencia`=`d`.`id_distribuidores_licencia`
            WHERE `l`.`fecha_activacion` BETWEEN '$fecha_inicio' AND '$fecha_fin'
            AND `p`.`fecha_pago` BETWEEN '$fecha_inicio' AND '$fecha_fin'
            AND p.estado_pago = 1
            AND p.estado = 0
            AND `l`.`planes_id` != 1
            AND `l`.`planes_id` != 15
            AND `l`.`planes_id` != 16
            AND `l`.`planes_id` != 17
            AND `l`.`planes_id` != 25" . $dias_vigencias;
        if ($iddistribuidor != 1) {
            $sql .= " AND e.id_distribuidores_licencia=$iddistribuidor";
        }
        $sql .= "
            GROUP BY `p`.`id_licencia`, `p`.`fecha_pago`, `p`.`ref_payco`, `p`.`transaction_id`
            ORDER BY `l`.`fecha_activacion`, `p`.`fecha_pago`)
            UNION
            (SELECT `u`.`db_config_id` AS id, `u`.`phone`, null, `l`.`idlicencias_empresa`, `l`.`fecha_activacion`,
            `l`.`fecha_inicio_licencia`, `l`.`fecha_vencimiento`, `e`.`nombre_empresa`, `pl`.`nombre_plan`, `u`.`email`,
            (0) AS total, `pl`.`valor_plan`, `e`.`id_distribuidores_licencia`,
            `d`.`nombre_distribuidor`, `e`.`id_user_distribuidor`, `u2`.`username` AS vendedor,  pl.dias_vigencia
            FROM (`crm_licencias_empresa` l)
            INNER JOIN `crm_empresas_clientes` e ON `e`.`idempresas_clientes`=`l`.`idempresas_clientes`
            INNER JOIN `crm_planes` pl ON `l`.`planes_id`=`pl`.`id`
            INNER JOIN `users` u ON `e`.`idusuario_creacion`=`u`.`id`
            INNER JOIN `users` u2 ON `e`.`id_user_distribuidor`=`u2`.`id`
            LEFT JOIN `crm_pagos_licencias` p ON `l`.`idlicencias_empresa`=`p`.`id_licencia`
            INNER JOIN `crm_distribuidores_licencia` d ON `e`.`id_distribuidores_licencia`=`d`.`id_distribuidores_licencia`
            WHERE `l`.`fecha_activacion` BETWEEN '$fecha_inicio' AND '$fecha_fin'
            AND `p`.`fecha_pago` IS NULL
            AND `l`.`planes_id` != 1
            AND `l`.`planes_id` != 15
            AND `l`.`planes_id` != 16
            AND `l`.`planes_id` != 17
            AND `l`.`planes_id` != 25
            $dias_vigencias
            GROUP BY `p`.`id_licencia`, `p`.`fecha_pago`, `p`.`ref_payco`, `p`.`transaction_id`
            ORDER BY `l`.`fecha_activacion`, `p`.`fecha_pago`)
            ORDER BY idlicencias_empresa,fecha_pago,fecha_activacion";
        //echo $sql; die();
        $query = $this->db->query($sql)->result();
        return $query;
    }
    //obtener los vencidos
    public function get_all_vencidos2($tipo = null, $fecha_inicio = null, $fecha_fin = null, $adicional = null)
    {

        //busco distribuidor
        $user = $this->session->userdata('user_id');
        $distribuidor = $this->get_distribuidor2(array('users_id' => $user));
        $iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];
        $dias_vigencia = "";

        if (!empty($adicional)) {
            $this->db->where("l.id_db_config NOT IN (2128)");
        }

        if ($tipo == "mensual") {
            $dias_vigencia = "(pl.dias_vigencia=30 or pl.dias_vigencia=90 or pl.dias_vigencia=180)";
        } else if ($tipo == "anual") {
            $dias_vigencia = "(pl.dias_vigencia=365)";
        } else {
            if ($tipo == "todos") {
                $dias_vigencia = "(pl.dias_vigencia=30 or pl.dias_vigencia=90 or pl.dias_vigencia=180 or pl.dias_vigencia=365)";
            }

        }
        $hoy = date('Y-m-d');
        if ($fecha_inicio == null && $fecha_fin == null) {
            $fecha_fin = strtotime('-1 day', strtotime($hoy));
            $fecha_fin = date('Y-m-d', $fecha_fin);
            $fecha_inicio = date('Y-m-01');
        } else {
            if ($fecha_fin == null) {

                $fecha_fin = strtotime('-1 day', strtotime($hoy));
                $fecha_fin = date('Y-m-d', $fecha_fin);

            }
        }

        if ($fecha_fin > $hoy) {
            $fecha_fin = $hoy;
        }

        $this->db->select('u.db_config_id AS id,u.phone,l.idlicencias_empresa, l.fecha_activacion, l.fecha_inicio_licencia,l.fecha_vencimiento,e.nombre_empresa,pl.nombre_plan,u.email,pl.valor_plan, pl.valor_plan as total, e.id_distribuidores_licencia, d.nombre_distribuidor, e.id_user_distribuidor, u2.`username` AS vendedor, pl.dias_vigencia');
        $this->db->from('crm_licencias_empresa l');
        $this->db->join('crm_empresas_clientes e', 'e.idempresas_clientes=l.idempresas_clientes', 'inner');
        $this->db->join('crm_planes pl', 'l.planes_id=pl.id', 'inner');
        $this->db->join('users u', 'u.id=e.idusuario_creacion', 'inner');
        $this->db->join('users u2', 'e.id_user_distribuidor=u2.id', 'inner');
        $this->db->join('crm_distribuidores_licencia d', 'e.id_distribuidores_licencia=d.id_distribuidores_licencia', 'inner');
        $this->db->where("l.fecha_vencimiento BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "'");
        $this->db->where('l.planes_id !=', 1);
        $this->db->where('l.planes_id !=', 15);
        $this->db->where('l.planes_id !=', 16);
        $this->db->where('l.planes_id !=', 17);
        $this->db->where('l.planes_id !=', 25);
        $this->db->where('l.estado_licencia =', 15);
        $this->db->where($dias_vigencia);
        if ($iddistribuidor != 1) {
            $this->db->where("e.id_distribuidores_licencia =" . $iddistribuidor);
        }
        $this->db->group_by('l.idlicencias_empresa');
        $this->db->order_by('l.fecha_vencimiento');
        $query = $this->db->get();

        //echo $this->db->last_query(); die();
        return $query->result();
    }

    //obtener los pagados en el mes
    public function pagados_mes($mes = null, $fecha_inicio = null, $fecha_fin = null)
    {
        // echo"".$mes; die();
        //busco distribuidor
        $user = $this->session->userdata('user_id');
        $distribuidor = $this->get_distribuidor2(array('users_id' => $user));
        $iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];

        $data = array();
        if ($fecha_inicio == null && $fecha_fin == null) {
            $dias = $this->getMonthDays_mes($mes);
            $fecha_inicio = date("$mes-1");
            $fecha_fin = date("$mes-$dias");
        }

        $this->db->select('l.`idlicencias_empresa`, `l`.`fecha_activacion`, `l`.`fecha_inicio_licencia`, `l`.`fecha_vencimiento`,`d`.`nombre_distribuidor`, `e`.`id_user_distribuidor`, `u2`.`username` AS vendedor, `pl`.`nombre_plan`, `pl`.`valor_plan`, (p.monto_pago - p.descuento_pago + p.retencion_pago) AS total, p.`fecha_pago`, p.`monto_pago` ,p.`descuento_pago`,p.`retencion_pago`, fp.`nombre_forma`,f.`numero_factura`, fd.`nombre_empresa`,fd.`tipo_identificacion`,fd.`numero_identificacion`,pl.dias_vigencia,p.idpagos_licencias');
        $this->db->from('crm_pagos_licencias p');
        $this->db->join('crm_licencias_empresa l', 'p.id_licencia = l.idlicencias_empresa', 'inner');
        $this->db->join('crm_factura_licencia f', 'p.idpagos_licencias = f.id_pago', 'left');
        $this->db->join('crm_detalle_factura_licencia fd', 'f.id_factura_licencia = fd.id_factura_licencia', 'left');
        $this->db->join('crm_empresas_clientes e', 'l.idempresas_clientes = e.idempresas_clientes', 'inner');
        $this->db->join('crm_planes pl', 'l.planes_id = pl.id', 'inner');
        $this->db->join('crm_formas_pago fp', 'p.idformas_pago = fp.idformas_pago', 'inner');
        $this->db->join('users u', 'e.idusuario_creacion = u.id', 'inner');
        $this->db->join('users u2', 'e.id_user_distribuidor=u2.id', 'inner');
        $this->db->join('crm_distribuidores_licencia d', 'e.id_distribuidores_licencia=d.id_distribuidores_licencia', 'inner');
        $this->db->where("p.fecha_pago BETWEEN '$fecha_inicio' AND '$fecha_fin'");
        $this->db->where('p.estado_pago', 1);
        $this->db->where('p.estado', 0);
        $this->db->where('l.planes_id !=', 1);
        /* $this->db->where('l.planes_id !=',15);
        $this->db->where('l.planes_id !=',16);
        $this->db->where('l.planes_id !=',17);*/
        $this->db->where('l.planes_id !=', 25);
        if ($iddistribuidor != 1) {
            $this->db->where("e.id_distribuidores_licencia =" . $iddistribuidor);
        }
        $this->db->group_by('p.id_licencia, p.fecha_pago, p.ref_payco, p.transaction_id');
        $this->db->order_by('f.numero_factura');
        $query = $this->db->get();
        //echo $this->db->last_query(); die();
        $result = $query->result();
        $cantidad_licencias = $query->num_rows();
        $total_pagos = 0;
        foreach ($result as $value) {
            $total_pagos += $value->monto_pago - $value->descuento_pago + $value->retencion_pago;
        }

        return array(
            'clientes' => $result,
            'total_pagos' => $total_pagos,
            'cantidad_licencias' => $cantidad_licencias,
        );
    }

    public function get_ajax_data_clientes_by_graphics($fecha_inicio = null, $fecha_fin = null)
    {

        $inicio = new DateTime($fecha_inicio);
        $fin = new DateTime($fecha_fin);

        $diff = $inicio->diff($fin);
        //print_r($diff);
        //die();
        if ($diff->days <= 31) {
            $dias = explode('-', $fecha_inicio);
            $filter_inicial = $dias[2];
            $filter_final = $filter_inicial + $diff->days;
            //$filter = $diff->days;
            $categories = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30');
        } else {
            $meses = explode('-', $fecha_inicio);
            $filter_inicial = $meses[1];
            $filter_final = $filter_inicial + $diff->m;
            $categories = array('1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12');
            //$categories = array('Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
        }

        $mes = array();

        //$categories = array('Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');

        $hoy = date('Y-m-j');
        $fechaAnterior = strtotime('-1 year', strtotime($hoy));
        $fechaAnterior = date('Y-m-j', $fechaAnterior);

        $user_distribuidor = $this->validate_distribuidor();

        if ($user_distribuidor != null) {
            if (count($categories) == 30) {
                $q = "DAY";
            } else {
                $q = "MONTH";
            }

            //SUSCRIPCIONES
            $this->db->select('' . $q . '(fecha_inicio_licencia) as dia,fecha_inicio_licencia,COUNT(*) AS total');
            $this->db->from('crm_licencias_empresa');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            //$this->db->join('users','users.db_config_id=crm_empresas_clientes.id_db_config');
            $this->db->where("crm_empresas_clientes.id_distribuidores_licencia = " . $user_distribuidor . " AND crm_licencias_empresa.planes_id = 1 AND crm_licencias_empresa.fecha_inicio_licencia BETWEEN '$fecha_inicio' AND '$fecha_fin' ");
            $this->db->group_by('' . $q . '(fecha_inicio_licencia)');
            $suscripciones = $this->db->get();

            //NUEVOS
            $this->db->select('' . $q . '(fecha_activacion) as dia,fecha_activacion,COUNT(*) AS total');
            $this->db->from('crm_licencias_empresa');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            //$this->db->join('users','users.db_config_id=crm_empresas_clientes.id_db_config');
            $this->db->join('crm_planes', 'crm_licencias_empresa.planes_id=crm_planes.id');
            $this->db->where("crm_empresas_clientes.id_distribuidores_licencia = " . $user_distribuidor . " AND crm_licencias_empresa.fecha_activacion BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "' AND (crm_planes.dias_vigencia = 30 || crm_planes.dias_vigencia = 365)");
            $this->db->where('crm_licencias_empresa.planes_id !=', 1);
            $this->db->where('crm_licencias_empresa.planes_id !=', 15);
            $this->db->where('crm_licencias_empresa.planes_id !=', 16);
            $this->db->where('crm_licencias_empresa.planes_id !=', 17);
            $this->db->group_by('' . $q . '(fecha_activacion)');
            $nuevos = $this->db->get();
            //echo $this->db->last_query();
            //SUSPENDIDAS
            $this->db->select('' . $q . '(fecha_inicio_licencia) as dia,fecha_activacion,COUNT(*) AS total');
            $this->db->from('crm_licencias_empresa');
            $this->db->join('crm_empresas_clientes', 'crm_empresas_clientes.idempresas_clientes=crm_licencias_empresa.idempresas_clientes');
            $this->db->join('crm_planes', 'crm_licencias_empresa.planes_id=crm_planes.id');
            $this->db->where("crm_empresas_clientes.id_distribuidores_licencia = " . $user_distribuidor . " AND crm_licencias_empresa.estado_licencia = 15 AND fecha_vencimiento BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "'");
            $this->db->where('crm_licencias_empresa.planes_id !=', 1);
            $this->db->where('crm_licencias_empresa.planes_id !=', 15);
            $this->db->where('crm_licencias_empresa.planes_id !=', 16);
            $this->db->where('crm_licencias_empresa.planes_id !=', 17);
            $this->db->group_by('' . $q . '(fecha_inicio_licencia)');
            $suspendidos = $this->db->get();

            $data = array();
            $total_pruebas = 0;
            $total_nuevos = 0;
            $total_suspendidos = 0;
            $array_pruebas = array();
            $array_nuevos = array();
            $array_suspendidos = array();
            $fn_array_pruebas = array();
            $fn_array_nuevos = array();
            $fn_array_suspendidos = array();

            for ($i = $filter_inicial; $i <= $filter_final; $i++) {
                $array_pruebas[$i] = 0;
                $array_nuevos[$i] = 0;
                $array_suspendidos[$i] = 0;
            }

            foreach ($suscripciones->result() as $value) {
                $array_pruebas[$value->dia] = intval($value->total);
                $total_pruebas = $total_pruebas + $value->total;
            }

            foreach ($nuevos->result() as $value) {
                $array_nuevos[$value->dia] = intval($value->total);
                $total_nuevos = $total_nuevos + $value->total;
            }

            foreach ($suspendidos->result() as $value) {
                $array_suspendidos[$value->dia] = intval($value->total);
                $total_suspendidos = $total_suspendidos + $value->total;
            }

            foreach ($array_nuevos as $nuevo) {
                $fn_array_nuevos[] = $nuevo;
            }
            foreach ($array_pruebas as $prueba) {
                $fn_array_pruebas[] = $prueba;
            }
            foreach ($array_suspendidos as $suspendido) {
                $fn_array_suspendidos[] = $suspendido;
            }

            //update estadisiticas
            $est_nuevos_mensuales_all = $this->get_all_nuevos('mensual', $fecha_inicio, $fecha_fin);
            $est_nuevos_pagos_mensuales = 0;
            $est_nuevos_mensuales = [];
            $anterior = 0;
            foreach ($est_nuevos_mensuales_all as $key => $nuevo) {
                if ($anterior == $nuevo->idlicencias_empresa) {
                    continue;
                }
                array_push($est_nuevos_mensuales, $est_nuevos_mensuales_all[$key]);
                $anterior = $nuevo->idlicencias_empresa;
                $est_nuevos_pagos_mensuales += $nuevo->total;
            }

            $est_nuevos_anuales = $this->get_all_nuevos('anual', $fecha_inicio, $fecha_fin);
            $est_nuevos_pagos_anuales = 0;
            foreach ($est_nuevos_anuales as $nuevo) {
                $est_nuevos_pagos_anuales += $nuevo->total;
            }

            $est_vencidos_mensual = $this->get_all_vencidos('mensual', $fecha_inicio, $fecha_fin);
            $est_vencidos_pagos_mensual = 0;
            foreach ($est_vencidos_mensual as $vencido) {
                $est_vencidos_pagos_mensual += $vencido->valor_plan;
            }

            $est_vencidos_anual = $this->get_all_vencidos('anual', $fecha_inicio, $fecha_fin);
            $est_vencidos_pagos_anual = 0;
            foreach ($est_vencidos_anual as $vencido) {
                $est_vencidos_pagos_anual += $vencido->valor_plan;
            }

            $est_planes_mensuales_pagados = $this->planes_mensuales_pagados($fecha_inicio, $fecha_fin);
            $est_planes_anuales_pagados = $this->planes_anuales_pagados($fecha_inicio, $fecha_fin);

            $est_por_renovar_mensual = $this->planes_mensuales_por_renovar($fecha_inicio, $fecha_fin);
            $est_por_renovar_anual = $this->planes_anuales_por_renovar($fecha_inicio, $fecha_fin);

            $est_total_licencias_mensual = $est_por_renovar_mensual["cantidad_licencias"] + $est_planes_mensuales_pagados["cantidad_licencias"] + count($est_vencidos_mensual);
            $est_total_licencias_mensual_pagos = '$' . number_format($est_por_renovar_mensual["total_pagos"] + $est_planes_mensuales_pagados["total_pagos"] + $est_vencidos_pagos_mensual);

            $est_total_licencias_anual = $est_por_renovar_anual["cantidad_licencias"] + $est_planes_anuales_pagados["cantidad_licencias"] + count($est_vencidos_anual);
            $est_total_licencias_anual_pagos = '$' . number_format($est_por_renovar_anual["total_pagos"] + $est_planes_anuales_pagados["total_pagos"] + $est_vencidos_pagos_anual);

            /* Formateamos los valores con pagos*/
            $est_planes_mensuales_pagados["total_pagos"] = '$' . number_format($est_planes_mensuales_pagados["total_pagos"]);
            $est_planes_anuales_pagados["total_pagos"] = '$' . number_format($est_planes_anuales_pagados["total_pagos"]);
            $est_por_renovar_mensual["total_pagos"] = '$' . number_format($est_por_renovar_mensual["total_pagos"]);
            $est_por_renovar_anual["total_pagos"] = '$' . number_format($est_por_renovar_anual["total_pagos"]);

            $data = array(
                'suscripciones' => $total_pruebas,
                'activos' => $total_nuevos,
                'suspendidos' => $total_suspendidos,
                'array_suscripciones' => $fn_array_pruebas,
                'array_activos' => $fn_array_nuevos,
                'array_suspendidos' => $fn_array_suspendidos,
                'categories' => $categories,
                'nuevos_mensuales' => $est_nuevos_mensuales,
                'nuevos_mensuales_pagos' => '$' . number_format($est_nuevos_pagos_mensuales),
                'nuevos_anuales' => $est_nuevos_anuales,
                'nuevos_anuales_pagos' => '$' . number_format($est_nuevos_pagos_anuales),
                'mensuales_vencidos' => $est_vencidos_mensual,
                'mensuales_vencidos_pagos' => '$' . number_format($est_vencidos_pagos_mensual),
                'anuales_vencidos' => $est_vencidos_anual,
                'anuales_vencidos_pagos' => '$' . number_format($est_vencidos_pagos_anual),
                'mensuales_pagados' => $est_planes_mensuales_pagados,
                'anuales_pagados' => $est_planes_anuales_pagados,
                'mensuales_por_renovar' => $est_por_renovar_mensual,
                'anuales_por_renovar' => $est_por_renovar_anual,
                'total_mensuales' => $est_total_licencias_mensual,
                'total_mensuales_pagos' => $est_total_licencias_mensual_pagos,
                'total_anuales' => $est_total_licencias_anual,
                'total_anuales_pagos' => $est_total_licencias_anual_pagos,
            );

            return $data;
        }

    }

    public function nuevo_usuario_distribuidor($distribuidor, $nombre, $email, $mobile)
    {
        if ($distribuidor != "" && $nombre != "" && $email != "" && $mobile != "") {
            $data_user_groups = array(
                "user_id" => $distribuidor,
                "group_id" => 4,
            );
            $this->db->insert("users_groups", $data_user_groups);
        }
    }

    public function update_configuracion($data)
    {
        $user = $this->session->userdata('user_id');
        $this->db->where('id', $user);
        $this->db->update('users', $data);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function get_licencias_por_usuario($db_config)
    {
        $this->db->select('*');
        $this->db->from('crm_licencias_empresa');
        $this->db->join('crm_planes', 'crm_planes.id = crm_licencias_empresa.planes_id');
        $this->db->where('id_db_config', $db_config);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return null;
        }
    }

    public function get_info_cliente($id_user)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('id', $id_user);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            return $query->result()[0];
        } else {
            return null;
        }
    }

    public function get_info_vendty()
    {
        $this->db->select('*');
        $this->db->from('crm_info_factura_vendty');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result()[0];
        } else {
            return null;
        }
    }

    public function get_info_vendty_factura_electronica()
    {
        $this->db->select('*');
        $this->db->from('crm_info_factura_vendty');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result()[1];
        } else {
            return null;
        }
    }

    public function get_info_empresa($where)
    {
        $this->db->select('*');
        $this->db->where($where);
        $this->db->from('crm_info_factura_clientes');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result()[0];
        } else {
            return null;
        }
    }
    public function ver_pagos_facturas($where = 0)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->select('*');
        $this->db->from('v_crm_pagos_facturas');
        return $this->db->get()->result_array();
    }

    public function ver_pagos($where = 0, $groupby = 0)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($groupby)) {
            $this->db->group_by($groupby);
        }
        $this->db->select('*');
        /*$this->db->select('crm_pagos_licencias.idpagos_licencias   AS id_pago,
        crm_pagos_licencias.id_licencia          AS id_licencia,
        crm_pagos_licencias.fecha_pago          AS fecha_pago,
        crm_pagos_licencias.monto_pago          AS monto_pago,
        crm_pagos_licencias.descuento_pago      AS descuento_pago,
        (crm_pagos_licencias.monto_pago - crm_pagos_licencias.descuento_pago) AS total,
        crm_pagos_licencias.idformas_pago       AS id_forma,
        crm_formas_pago.nombre_forma       AS nombre_forma,
        crm_pagos_licencias.estado_pago         AS estado_pago,
        crm_pagos_licencias.observacion_pago    AS observacion_pago,
        crm_pagos_licencias.info_adicional_pago AS info_adicional_pago,
        crm_pagos_licencias.ref_payco AS ref_payco,
        crm_pagos_licencias.transaction_id AS transaction_id,
        crm_pagos_licencias.id_factura_licencia      AS numero_factura,
        crm_pagos_licencias.id_factura_licencia AS id_factura,
        crm_empresas_clientes.nombre_empresa     AS nombre_empresa,
        crm_empresas_clientes.idempresas_clientes     AS id_empresa,
        crm_licencias_empresa.idlicencias_empresa     AS id_almacen');       */
        //$this->db->from('crm_pagos_licencias');
        $this->db->from('v_crm_pagos');
        /* $this->db->join('crm_formas_pago', 'crm_pagos_licencias.idformas_pago=crm_formas_pago.idformas_pago');
        $this->db->join('crm_licencias_empresa', 'crm_pagos_licencias.id_licencia=crm_licencias_empresa.idlicencias_empresa');
        $this->db->join('crm_empresas_clientes', 'crm_licencias_empresa.idempresas_clientes=crm_empresas_clientes.idempresas_clientes');*/
        //$this->db->where('crm_pagos_licencias.id_factura_licencia',NULL);
        // $this->db->group_by('crm_pagos_licencias.fecha_pago, crm_pagos_licencias.id_licencia,crm_pagos_licencias.estado_pago,crm_pagos_licencias.idformas_pago');

        //$this->db->get()->result_array();
        //echo $this->db->last_query();die();
        return $this->db->get()->result_array();

    }

    public function existe_pago($where = 0)
    {

        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->select('*');
        $this->db->from('crm_pagos_licencias');
        $query = $this->db->get()->result_array();
        //echo $this->db->last_query(); die();
        if (!empty($query)) {
            return 1;
        } else {
            return 0;
        }
    }

    public function update_cant_almacen($where, $data)
    {
        $this->db->where($where);
        $this->db->set($data);
        $this->db->update('db_config');
        return 1;
    }
    //******** */
    public function get_distribuidor2($where)
    {
        $this->db->where($where);
        $this->db->select('id_distribuidores_licencia');
        $this->db->from('crm_usuarios_distribuidores');
        $query = $this->db->get()->result_array();
        return $query;
    }

    public function get_all_user2($where = 0)
    {
        $array = array('email !=' => "");
        $this->db->where($array);

        if (!empty($where)) {
            $this->db->where_in('db_config_id', $where);
        }

        $this->db->order_by("email", "asc");
        $this->db->select('id,email,db_config_id');
        $this->db->from('users');
        $query = $this->db->get()->result();
        return $query;
    }

    public function get_all_clientes2($user_distribuidor = 0)
    {

        if (!empty($user_distribuidor)) {
            $this->db->where('e.id_distribuidores_licencia', $user_distribuidor);
        }

        $this->db->select('l.idlicencias_empresa as id_licencia, l.planes_id as plan,u.id as usuario_id,u.email,u.username,u.phone,l.idlicencias_empresa,l.fecha_inicio_licencia,l.fecha_vencimiento,l.fecha_activacion,e.nombre_empresa as empresa');
        $this->db->from('crm_licencias_empresa l');
        $this->db->join('crm_empresas_clientes e', 'e.idempresas_clientes=l.idempresas_clientes');
        $this->db->join('users u', 'u.db_config_id=e.id_db_config');
        $this->db->where('u.is_admin', 't');
        $this->db->group_by('l.idlicencias_empresa');
        $this->db->order_by('l.idlicencias_empresa');
        $query = $this->db->get();
        //echo"<br>".$this->db->last_query();     die();
        return $query->result();

    }

    public function pagos_ultimos_12meses()
    {
        //busco mi distribuidor
        $user = $this->session->userdata('user_id');
        $distribuidor = $this->get_distribuidor2(array('users_id' => $user));
        $iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];

        ///ultimos 3 meses
        $fechafin = date('Y-m-d');
        $fechainicial = date('Y-m-d');
        $fechainicial = strtotime('-11 month', strtotime($fechainicial));
        $fechainicial = date('Y-m-01', $fechainicial);

        $this->db->select("YEAR(p.fecha_pago) AS ano , MONTH(p.fecha_pago) AS mes ,(p.monto_pago - p.descuento_pago + p.retencion_pago) AS total, p.monto_pago AS valor_plan, p.retencion_pago");
        $this->db->from('crm_pagos_licencias p');
        $this->db->join('crm_licencias_empresa l', 'p.id_licencia = l.idlicencias_empresa', 'inner');
        $this->db->where("p.fecha_pago BETWEEN '$fechainicial' AND '$fechafin'");
        $this->db->where('p.estado_pago', 1);
        $this->db->where('p.estado', 0);
        $this->db->where('l.planes_id !=', 1);
        $this->db->where('l.planes_id !=', 25);
        if ($iddistribuidor != 1) {
            $this->db->where("e.id_distribuidores_licencia =" . $iddistribuidor);
        }
        $this->db->group_by("p.id_licencia,p.fecha_pago,p.ref_payco,p.transaction_id");
        $this->db->order_by('ano, mes');
        $query = $this->db->get();

        foreach ($query->result_array() as $value) {
            $total_pagos[$value["ano"] . "-" . $value["mes"]] = array("mes" => $value["mes"], "total" => 0, "valor_plan" => 0);
        }

        foreach ($query->result_array() as $value) {
            $total_pagos[$value["ano"] . "-" . $value["mes"]]["mes"] = $value["mes"];
            $total_pagos[$value["ano"] . "-" . $value["mes"]]["total"] += $value["total"];
            $total_pagos[$value["ano"] . "-" . $value["mes"]]["valor_plan"] += $value["valor_plan"];
        }

        return $total_pagos;
    }

    public function pagos_ultimos_18meses()
    {
        //busco mi distribuidor
        $user = $this->session->userdata('user_id');
        $distribuidor = $this->get_distribuidor2(array('users_id' => $user));
        $iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];

        ///ultimos 3 meses
        $fechafin = date('Y-m-d');
        $fechainicial = date('Y-m-d');
        $fechainicial = strtotime('-17 month', strtotime($fechainicial));
        $fechainicial = date('Y-m-01', $fechainicial);

        $this->db->select(" YEAR(p.fecha_pago) AS ano , MONTH(p.fecha_pago) AS mes ,(p.monto_pago - p.descuento_pago + p.retencion_pago) AS total, p.monto_pago AS valor_plan, p.retencion_pago");
        $this->db->from('crm_pagos_licencias p');
        $this->db->join('crm_licencias_empresa l', 'p.id_licencia = l.idlicencias_empresa', 'inner');
        $this->db->where("p.fecha_pago BETWEEN '$fechainicial' AND '$fechafin'");
        $this->db->where('p.estado_pago', 1);
        $this->db->where('p.estado', 0);
        $this->db->where('l.planes_id !=', 1);
        $this->db->where('l.planes_id !=', 25);
        if ($iddistribuidor != 1) {
            $this->db->where("e.id_distribuidores_licencia =" . $iddistribuidor);
        }
        $this->db->group_by("p.id_licencia,p.fecha_pago,p.ref_payco,p.transaction_id");
        $this->db->order_by('ano, mes');
        $query = $this->db->get();
        $total_pagos = [];

        foreach ($query->result_array() as $value) {
            $total_pagos[$value["ano"] . "-" . $value["mes"]] = array("mes" => $value["mes"], "total" => 0, "valor_plan" => 0);
        }

        foreach ($query->result_array() as $value) {
            $total_pagos[$value["ano"] . "-" . $value["mes"]]["mes"] = $value["mes"];
            $total_pagos[$value["ano"] . "-" . $value["mes"]]["total"] += $value["total"];
            $total_pagos[$value["ano"] . "-" . $value["mes"]]["valor_plan"] += $value["valor_plan"];
        }

        return $total_pagos;
    }

    public function update_data_empresa_config($data)
    {
        //Actualizacion realizada para la configuracion inicial
        //verifico si existe el registro
        $this->db->select("*");
        $this->db->where('id_db', $this->session->userdata('db_config_id'));
        $this->db->from('crm_db_activas');
        $existe = $this->db->get()->result_array();

        if (empty($existe)) {
            //busco las licencias de ese usuario
            $planes = array('1', '15', '16', '17');
            $this->db->select("*");
            $this->db->where('l.id_db_config', $this->session->userdata('db_config_id'));
            $this->db->where_not_in('planes_id', $planes);
            $this->db->where('db.estado', 1);
            $this->db->from('crm_licencias_empresa l');
            $this->db->join('db_config db', 'l.id_db_config = db.id', 'inner');
            $existelicencias = $this->db->get()->result_array();

            if (!empty($existelicencias)) {
                //busco la empresa asociada
                $this->db->select("*");
                $this->db->where('id_db_config', $this->session->userdata('db_config_id'));
                $this->db->from('crm_empresas_clientes');
                $existeempresa = $this->db->get()->result_array();

                if (!empty($existeempresa)) {
                    $data['id_empresa'] = $existeempresa[0]['idempresas_clientes'];
                }
                //creo el registro
                $data['id_db'] = $this->session->userdata('db_config_id');

                //insertar el registro
                $this->db->insert('crm_db_activas', $data);

                //ingreso los almacenes
                foreach ($existelicencias as $key => $value) {

                    $sqlpais = 'SHOW COLUMNS FROM ' . $value["base_dato"] . '.almacen LIKE "pais"';
                    $sqlrazon = 'SHOW COLUMNS FROM ' . $value["base_dato"] . '.almacen LIKE "razon_social"';
                    $existeCampo = $this->db->query($sqlpais)->result();
                    $existeCamporazon = $this->db->query($sqlrazon)->result();

                    $sqlinsert = 'insert into crm_db_activa_almacenes (id_licencia,id_db_config,id_almacen,nombre_almacen,direccion_almacen,telefono_almacen,razon_social_almacen,pais_almacen,ciudad_almacen,numero_documento_almacen)
                            values(' . $value["idlicencias_empresa"] . '	, ' . $value["id"] . ',	' . $value["id_almacen"] . ',(SELECT nombre FROM ' . $value["base_dato"] . '.almacen WHERE id=	' . $value["id_almacen"] . '),(SELECT direccion FROM ' . $value["base_dato"] . '.almacen WHERE id=	' . $value["id_almacen"] . '),(SELECT telefono FROM	' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '),';

                    if (count($existeCamporazon) == 0) {
                        $sqlinsert .= '"",';
                    } else {
                        $sqlinsert .= '(SELECT razon_social FROM ' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '),';
                    }

                    if (count($existeCampo) == 0) {
                        //no existe
                        $sqlinsert .= '"",';
                    } else {
                        //existe
                        $sqlinsert .= '(SELECT pais FROM ' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '),';
                    }

                    $sqlinsert .= '(SELECT ciudad FROM ' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '),(SELECT nit FROM ' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '))';

                    //insertar
                    $this->db->query($sqlinsert);
                }
            }
        } else {
            //busco nombre del pais asaociado
            $this->db->select("nombre_pais");
            $this->db->where('id_pais', $data['nombre_pais_config']);
            $this->db->from('pais');
            $querypais = $this->db->get()->row_array();
            $data['nombre_pais_config'] = $querypais['nombre_pais'];
            $this->db->where('id_db', $this->session->userdata('db_config_id'));
            $this->db->set($data);
            $this->db->update('crm_db_activas');
            return 1;
        }
    }

    public function update_almacenes_info($data)
    {
        //verifico si existe el registro en crm_db_activas
        $this->db->select("*");
        $this->db->where('id_db', $this->session->userdata('db_config_id'));
        $this->db->from('crm_db_activas');
        $existe = $this->db->get()->result_array();

        if (empty($existe)) {
            $datadb = array();
            //busco la empresa asociada
            $this->db->select("*");
            $this->db->where('id_db_config', $this->session->userdata('db_config_id'));
            $this->db->from('crm_empresas_clientes');
            $existeempresa = $this->db->get()->result_array();

            if (!empty($existeempresa)) {
                $empresa = $existeempresa[0]['idempresas_clientes'];
            }
            //busco los datos del empresa configuracion
            $this->db->select("*");
            $this->db->where('id', $this->session->userdata('db_config_id'));
            $this->db->from('db_config');
            $existeempresa = $this->db->get()->result_array();


            $queryDbOpcionesQuery = "SELECT valor_opcion FROM opciones WHERE nombre_opcion='tipo_negocio'" ;
            $tipo_negocio = $this->connection->query($queryDbOpcionesQuery)->row();
            $queryDbOpcionesQuery = "SELECT valor_opcion FROM opciones WHERE nombre_opcion='nombre_empresa'";
            $nombre_empresa = $this->connection->query($queryDbOpcionesQuery)->row();
            $queryDbOpcionesQuery = "SELECT valor_opcion FROM opciones WHERE nombre_opcion='documento'";
            $documento = $this->connection->query($queryDbOpcionesQuery)->row();
            $queryDbOpcionesQuery = "SELECT valor_opcion FROM opciones WHERE nombre_opcion='nit'";
            $nit = $this->connection->query($queryDbOpcionesQuery)->row();
            $queryDbOpcionesQuery = "SELECT valor_opcion FROM opciones WHERE nombre_opcion='direccion_empresa'";
            $direccion_empresa = $this->connection->query($queryDbOpcionesQuery)->row();
            $queryDbOpcionesQuery = "SELECT valor_opcion FROM opciones WHERE nombre_opcion='email_empresa'";
            $email_empresa = $this->connection->query($queryDbOpcionesQuery)->row();
            $queryDbOpcionesQuery = "SELECT valor_opcion FROM opciones WHERE nombre_opcion='contacto_empresa'";
            $contacto_empresa = $this->connection->query($queryDbOpcionesQuery)->row();
            $queryDbOpcionesQuery = "SELECT valor_opcion FROM opciones WHERE nombre_opcion='telefono_empresa'";
            $telefono_empresa = $this->connection->query($queryDbOpcionesQuery)->row();
            $queryDbOpcionesQuery = "SELECT valor_opcion AS pais_confi FROM	opciones WHERE nombre_opcion='pais'";
            $pais = $this->connection->query($queryDbOpcionesQuery)->row();

            //creo el registro para insertar en crm_db_activa
            $sqlinsertdb = "insert into vendty2.crm_db_activas(id_db, id_empresa, tipo_negocio, nombre_empresa_config, tipo_documento_config, numero_documento_config, direccion_empresa_config, email_empresa_config, contacto_empresa_config, telefono_empresa_config, nombre_pais_config ) values(" . $this->session->userdata('db_config_id') . ",$empresa,	'" . $tipo_negocio->valor_opcion . "', '" . $nombre_empresa->valor_opcion . "', '" . $documento->valor_opcion . "', '" . $nit->valor_opcion . "', '" . $direccion_empresa->valor_opcion . "', '" . $email_empresa->valor_opcion . "', '" . $contacto_empresa->valor_opcion . "', '". $telefono_empresa->valor_opcion . "',	(SELECT nombre_pais AS pais FROM vendty2.pais WHERE id_pais IN('" . $pais->valor_opcion. "')))";

            $this->db->query($sqlinsertdb);

            //busco las licencias de ese usuario
            $planes = array('1', '15', '16', '17');
            $this->db->select("*");
            $this->db->where('l.id_db_config', $this->session->userdata('db_config_id'));
            $this->db->where_not_in('planes_id', $planes);
            $this->db->where('db.estado', 1);
            $this->db->from('crm_licencias_empresa l');
            $this->db->join('db_config db', 'l.id_db_config = db.id', 'inner');
            $existelicencias = $this->db->get()->result_array();

            if (!empty($existelicencias)) {

                //ingreso los almacenes  a crm_db_activa_almacenes
                foreach ($existelicencias as $key => $value) {
                    //$sqlpais = 'SHOW COLUMNS FROM ' . $value["base_dato"] . '.almacen LIKE "pais"';
                    //$sqlrazon = 'SHOW COLUMNS FROM ' . $value["base_dato"] . '.almacen LIKE "razon_social"';
                    $sqlpais = 'SHOW COLUMNS FROM almacen LIKE "pais"';
                    $sqlrazon = 'SHOW COLUMNS FROM almacen LIKE "razon_social"';
                    //$existeCampo = $this->db->query($sqlpais)->result();
                    //$existeCamporazon = $this->db->query($sqlrazon)->result();
                    $existeCampo = $this->connection->query($sqlpais)->result();
                    $existeCamporazon = $this->connection->query($sqlrazon)->result();


                    $miAlmacenQuery = "SELECT nombre, direccion, telefono, ciudad, nit FROM almacen WHERE id=" . $value["id_almacen"];
                    $miAlmacen = $this->connection->query($miAlmacenQuery)->row();

                    $sqlinsert = 'insert into crm_db_activa_almacenes (id_licencia,id_db_config,id_almacen,nombre_almacen,direccion_almacen,telefono_almacen,razon_social_almacen,pais_almacen,ciudad_almacen,numero_documento_almacen)
                            values(' . $value["idlicencias_empresa"] . '	, ' . $value["id"] . ',	' . $value["id_almacen"] . ', \'' . $miAlmacen->nombre . '\', \'' . $miAlmacen->direccion . '\', \'' . $miAlmacen->telefono . '\',';

                    if (count($existeCamporazon) == 0) {
                        $sqlinsert .= '"",';
                    } else {
                        $miAlmacenQueryRazon = "SELECT razon_social FROM almacen WHERE id=" . $value["id_almacen"];
                        $miAlmacenRazon = $this->connection->query($miAlmacenQueryRazon)->row();

                        $sqlinsert .= "'". $miAlmacenRazon->razon_social . "',";
                    }

                    if (count($existeCampo) == 0) {
                        //no existe
                        $sqlinsert .= '"",';
                    } else {
                        //existe
                        $miAlmacenQueryPais = "SELECT pais FROM almacen WHERE id=" . $value["id_almacen"];
                        $miAlmacenPais = $this->connection->query($miAlmacenQueryPais)->row();
                        $sqlinsert .= "'" . $miAlmacenPais->pais . "',";
                    }

                    $sqlinsert .= "'".$miAlmacen->ciudad."', '" . $miAlmacen->nit ."')";

                    //insertar
                    $this->db->query($sqlinsert);
                }
            }
        }

        $this->db->where('id_db_config', $this->session->userdata('db_config_id'));
        $this->db->where('id_almacen', $data['id_almacen']);
        $this->db->set($data);
        $this->db->update('crm_db_activa_almacenes');
        return 1;
    }

    public function add_almacenes_info($data)
    {

        $this->db->insert('crm_db_activa_almacenes', $data);
        return 1;
    }

    public function insert_db_activa_info($data)
    {
        //verifico si existe el registro en crm_db_activas
        $this->db->select("*");
        $this->db->where('id_db', $data['id_db']);
        $this->db->from('crm_db_activas');
        $existe = $this->db->get()->result_array();

        if (empty($existe)) {
            $this->db->insert('crm_db_activas', $data);
        }
        //ingreso en crm_db_activa_almacenes los almacenes
        //busco las licencias de ese usuario
        $planes = array('1', '15', '16', '17');
        $this->db->select("*");
        $this->db->where('l.id_db_config', $data['id_db']);
        $this->db->where_not_in('planes_id', $planes);
        $this->db->where('db.estado', 1);
        $this->db->from('crm_licencias_empresa l');
        $this->db->join('db_config db', 'l.id_db_config = db.id', 'inner');
        $existelicencias = $this->db->get()->result_array();

        if (!empty($existelicencias)) {

            //ingreso los almacenes  a crm_db_activa_almacenes
            foreach ($existelicencias as $key => $value) {
                $sqlpais = 'SHOW COLUMNS FROM ' . $value["base_dato"] . '.almacen LIKE "pais"';
                $sqlrazon = 'SHOW COLUMNS FROM ' . $value["base_dato"] . '.almacen LIKE "razon_social"';
                $existeCampo = $this->db->query($sqlpais)->result();
                $existeCamporazon = $this->db->query($sqlrazon)->result();

                $sqlinsert = 'insert into crm_db_activa_almacenes (id_licencia,id_db_config,id_almacen,nombre_almacen,direccion_almacen,telefono_almacen,razon_social_almacen,pais_almacen,ciudad_almacen,numero_documento_almacen)
                        values(' . $value["idlicencias_empresa"] . '	, ' . $value["id"] . ',	' . $value["id_almacen"] . ',(SELECT nombre FROM ' . $value["base_dato"] . '.almacen WHERE id=	' . $value["id_almacen"] . '),(SELECT direccion FROM ' . $value["base_dato"] . '.almacen WHERE id=	' . $value["id_almacen"] . '),(SELECT telefono FROM	' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '),';

                if (count($existeCamporazon) == 0) {
                    $sqlinsert .= '"",';
                } else {
                    $sqlinsert .= '(SELECT razon_social FROM ' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '),';
                }

                if (count($existeCampo) == 0) {
                    //no existe
                    $sqlinsert .= '"",';
                } else {
                    //existe
                    $sqlinsert .= '(SELECT pais FROM ' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '),';
                }

                $sqlinsert .= '(SELECT ciudad FROM ' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '),(SELECT nit FROM ' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '))';

                //insertar
                $this->db->query($sqlinsert);
            }
        }
    }
    public function info_fiscal($where = null)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }

        $this->db->select("*");
        $this->db->from('v_crm_info_licencias_clientes');
        return $this->db->get()->result();
    }

    public function info_fiscal_cliente($where = null)
    {
        if (!empty($where)) {
            $this->db->where($where);
        }

        $this->db->select("*");
        $this->db->from('crm_info_totales_bd_activas');
        return $this->db->get()->result();
    }

    public function load_state_actualizar_info($where)
    {
        $this->db->select("*");
        $this->db->from("crm_info_negocio");
        $this->db->where($where);
        $result = $this->db->get()->result_array();

        if (!empty($result)) {
            return $result;
        } else {
            return 0;
        }
    }
    public function crm_opciones($where)
    {

        if (!empty($where)) {
            $this->db->where($where);
        }

        $this->db->select("nombre_opcion,valor_opcion,mostrar_opcion");
        $this->db->from("opciones");
        $result = $this->db->get()->result_array();
        return $result;
    }

    public function insert_actualizar_info_negocio($data)
    {
        $this->db->select("*");
        $this->db->from("crm_info_negocio");
        $this->db->where('id_db_config', $data['id_db_config']);
        $result = $this->db->get()->result_array();

        if (empty($result)) {
            //inserto la data
            $this->db->insert('crm_info_negocio', $data);
            return 1;
        } else {
            return 0;
        }
    }
    public function get_server($where = "")
    {
        //buscamos las conexiones en vendty2
        $this->db->select("*");
        $this->db->from("crm_server_bd");
        if (!empty($where)) {
            $this->db->where($where);
        }

        $result = $this->db->get()->result_array();
        return $result;
    }

    public function get_user_activo($where = "")
    {
        //buscamos si existe el correo
        $this->db->select("u.email, db.id, db.base_dato");
        $this->db->from("users u");
        $this->db->join('db_config db', 'u.db_config_id = db.id', 'inner');

        if (!empty($where)) {
            $this->db->where($where);
        }

        $result = $this->db->get()->result_array();
        return $result;
    }

    public function existenBD($desde, $hasta, $bd)
    {
        //buscar las conexiones
        $conexiondesde = $this->get_server(array('id' => $desde));
        $conexionhasta = $this->get_server(array('id' => $hasta));
        $result = "";
        //creo la variable de conexion desde
        $usuariod = $conexiondesde[0]['usuario'];
        $claved = $conexiondesde[0]['clave'];
        $servidord = $conexiondesde[0]['servidor'];
        $base_datosd = 'vendty2';
        $dns = "mysql://$usuariod:$claved@$servidord/$base_datosd";
        $this->db1 = $this->load->database($dns, true);

        if ($desde === "1") {
            $this->db1->select('*');
            $this->db1->from('db_config');
            $this->db1->where('base_dato', $bd);
            $query = $this->db1->get();

            if ($query->num_rows() > 0) {
                $usuariod = $query->result()[0]->usuario;
                $claved = $query->result()[0]->clave;
                $servidord = $query->result()[0]->servidor;
                $base_datosd = 'vendty2';
                $dns = "mysql://$usuariod:$claved@$servidord/$base_datosd";
                $this->db1 = $this->load->database($dns, true);
            }
        }

        $existeDBd = $this->db1->query("SHOW DATABASES WHERE `database` = '" . $bd . "'");

        if ($existeDBd->num_rows() == 0) {
            $result .= '0_';
        } else {
            $result .= '1_';
        }
        //creo la variable de conexion hasta
        $usuarioh = $conexionhasta[0]['usuario'];
        $claveh = $conexionhasta[0]['clave'];
        $servidorh = $conexionhasta[0]['servidor'];
        $base_datosh = 'vendty2';
        $dns = "mysql://$usuarioh:$claveh@$servidorh/$base_datosh";
        $this->db1 = $this->load->database($dns, true);
        $existeDBdh = $this->db1->query("SHOW DATABASES WHERE `database` = '" . $bd . "'");

        if ($existeDBdh->num_rows() == 0) {
            $result .= '0';
        } else {
            $result .= '1';
        }

        return $result;
    }

    public function migrar_bd($desde, $hasta, $bd, $id_bd, $opcion)
    {

        //buscar las conexiones
        //$conexiondesde=$this->get_server(array('id'=>$desde));
        //$conexiondesde=$this->get_server($desde);die();
        $conexionhasta = $this->get_server(array('id' => $hasta));

        //creo la variable de conexion desde
        /* $usuariod = $conexiondesde[0]['usuario'];
        $claved = $conexiondesde[0]['clave'];
        $servidord = $conexiondesde[0]['servidor'];
        $base_datosd = 'vendty2';*/

        //creo la variable de conexion hasta
        $usuarioh = $conexionhasta[0]['usuario'];
        $claveh = $conexionhasta[0]['clave'];
        $servidorh = $conexionhasta[0]['servidor'];
        $base_datosh = 'vendty2';
        $dns = "mysql://$usuarioh:$claveh@$servidorh/$base_datosh";
        $this->dbConnection = $this->load->database($dns, true);

        $existeDB2 = $this->dbConnection->query("SHOW DATABASES WHERE `database` = '" . $bd . "'");

        if ($existeDB2->num_rows() > 0) {
            /*if($existeDB2->num_rows() == 0){
            $sql = "CREATE DATABASE $bd";
            $this->dbConnection->query($sql);
            }

            $baseDatos = $bd;
            $nombreBackupSQL = "/mnt/up/sql/{$baseDatos}.sql";
            //$nombreBackupSQL = "/home/ubuntu/uploads/sql/{$baseDatos}.sql";
            //$nombreBackupSQL = "http://pos.vendty.com/uploads/sql/{$baseDatos}.sql";
            //$nombreBackupSQL = "C:/xampp/htdocs/vendty/pos/uploads/sql/{$baseDatos}.sql";

            shell_exec("mysqldump --opt -h $servidord -u $usuariod -p$claved $baseDatos > $nombreBackupSQL"); //Genero el .sql a restaurar
            shell_exec("mysql -u $usuarioh -p$claveh -h $servidorh $baseDatos < $nombreBackupSQL"); //restauroel .sql
             */
            //Si se migrar a beta
            if ($hasta == 3) {
                //eliminar o actualizar los registros que haya de esa bd en vendty2
                //db_config
                $sqldelete = "DELETE FROM db_config WHERE id=$id_bd";
                $this->dbConnection->query($sqldelete);
                //users
                $sqldelete = "DELETE FROM users WHERE db_config_id=$id_bd";
                $this->dbConnection->query($sqldelete);
                //pagos licencias
                $sqldelete = "TRUNCATE TABLE crm_pagos_licencias";
                $this->dbConnection->query($sqldelete);
                //licencias
                $sqldelete = "DELETE FROM crm_licencias_empresa WHERE id_db_config=$id_bd";
                $this->dbConnection->query($sqldelete);

                //migrar db_config
                //busco registro
                $resultconfig = $this->consulta_bd(array('id' => $id_bd), 'db_config');
                //insertar db_config
                if (!empty($resultconfig)) {
                    $data = array(
                        'id' => $resultconfig[0]['id'],
                        'servidor' => $resultconfig[0]['servidor'],
                        'base_dato' => $resultconfig[0]['base_dato'],
                        'usuario' => $resultconfig[0]['usuario'],
                        'clave' => $resultconfig[0]['clave'],
                        'fecha' => $resultconfig[0]['fecha'],
                        'almacen' => $resultconfig[0]['almacen'],
                        'estado' => $resultconfig[0]['estado'],
                        'api_key' => $resultconfig[0]['api_key'],
                    );
                    $this->dbConnection->insert('db_config', $data);
                    //update
                    $data = array(
                        'servidor' => $servidorh,
                        'usuario' => $usuarioh,
                        'clave' => $claveh,
                    );
                    $this->dbConnection->where('id', $id_bd);
                    $this->dbConnection->update('db_config', $data);
                }

                //migrar users
                //busco registro
                $resultconfig = $this->consulta_bd(array('db_config_id' => $id_bd), 'users');
                //insertar users
                if (!empty($resultconfig)) {
                    foreach ($resultconfig as $value) {
                        $data = array(
                            'id' => $value['id'],
                            'ip_address' => $value['ip_address'],
                            'username' => $value['username'],
                            'password' => $value['password'],
                            'salt' => $value['salt'],
                            'email' => $value['email'],
                            'activation_code' => $value['activation_code'],
                            'forgotten_password_code' => $value['forgotten_password_code'],
                            'forgotten_password_time' => $value['forgotten_password_time'],
                            'remember_code' => $value['remember_code'],
                            'created_on' => $value['created_on'],
                            'last_login' => $value['last_login'],
                            'active' => $value['active'],
                            'first_name' => $value['first_name'],
                            'last_name' => $value['last_name'],
                            'company' => $value['company'],
                            'phone' => $value['phone'],
                            'db_config_id' => $value['db_config_id'],
                            'idioma' => $value['idioma'],
                            'pais' => $value['pais'],
                            'rol_id' => $value['rol_id'],
                            'is_admin' => $value['is_admin'],
                            'sucursales' => $value['sucursales'],
                            'realname' => $value['realname'],
                            'realmname_id' => $value['realmname_id'],
                            'term_acept' => $value['term_acept'],
                            'term_fecha' => $value['term_fecha'],
                            'es_estacion_pedido' => $value['es_estacion_pedido'],
                            'remember_token' => $value['remember_token'],
                        );
                        $this->dbConnection->insert('users', $data);
                    }
                    //update
                    $data = array(
                        'password' => '0ccf0cede9785bc7257102c3d9415532e590fff9',
                    );
                    $this->dbConnection->where('db_config_id', $id_bd);
                    $this->dbConnection->update('users', $data);
                }

                //migrar empresa
                //busco registro
                $resultconfig = $this->consulta_bd(array('id_db_config' => $id_bd), 'crm_empresas_clientes');
                //insertar/modificar empresa
                if (!empty($resultconfig)) {
                    $data = array(
                        'idempresas_clientes' => $resultconfig[0]['idempresas_clientes'],
                        'nombre_empresa' => $resultconfig[0]['nombre_empresa'],
                        'direccion_empresa' => $resultconfig[0]['direccion_empresa'],
                        'telefono_contacto' => $resultconfig[0]['telefono_contacto'],
                        'idusuario_creacion' => $resultconfig[0]['idusuario_creacion'],
                        'id_db_config' => $resultconfig[0]['id_db_config'],
                        'id_distribuidores_licencia' => $resultconfig[0]['id_distribuidores_licencia'],
                        'id_user_distribuidor' => $resultconfig[0]['id_user_distribuidor'],
                        'identificacion_empresa' => $resultconfig[0]['identificacion_empresa'],
                        'tipo_identificacion' => $resultconfig[0]['tipo_identificacion'],
                        'razon_social_empresa' => $resultconfig[0]['razon_social_empresa'],
                        'ciudad_empresa' => $resultconfig[0]['ciudad_empresa'],
                        'departamento_empresa' => $resultconfig[0]['departamento_empresa'],
                        'pais' => $resultconfig[0]['pais'],
                        'valor_renovacion' => $resultconfig[0]['valor_renovacion'],
                        'tipo_negocio' => $resultconfig[0]['tipo_negocio'],
                    );
                    //consultar si esta en beta
                    $sqlem = "select * from crm_empresas_clientes where id_db_config=$id_bd";
                    $empre = $this->dbConnection->query($sqlem);
                    if ($empre->num_rows() == 0) {
                        $this->dbConnection->where('id_db_config', $id_bd);
                        $this->dbConnection->insert('crm_empresas_clientes', $data);
                    } else {
                        $this->dbConnection->where('id_db_config', $id_bd);
                        $this->dbConnection->update('crm_empresas_clientes', $data);
                    }
                }

                //migrar licencias
                //busco registro
                $resultconfig = $this->consulta_bd(array('id_db_config' => $id_bd), 'crm_licencias_empresa');
                //insertar db_config

                if (!empty($resultconfig)) {
                    foreach ($resultconfig as $value) {
                        $data = array(
                            'idlicencias_empresa' => $value['idlicencias_empresa'],
                            'idempresas_clientes' => $value['idempresas_clientes'],
                            'planes_id' => $value['planes_id'],
                            'fecha_creacion' => $value['fecha_creacion'],
                            'creado_por' => $value['creado_por'],
                            'fecha_modificacion' => $value['fecha_modificacion'],
                            'fecha_inicio_licencia' => $value['fecha_inicio_licencia'],
                            'fecha_vencimiento' => $value['fecha_vencimiento'],
                            'id_db_config' => $value['id_db_config'],
                            'id_almacen' => $value['id_almacen'],
                            'estado_licencia' => $value['estado_licencia'],
                            'observacion_adicional_licencia' => $value['observacion_adicional_licencia'],
                            'fecha_activacion' => $value['fecha_activacion'],
                            'desactivada' => $value['desactivada'],
                            'fecha_desactivada' => $value['fecha_desactivada'],
                        );
                        $this->dbConnection->insert('crm_licencias_empresa', $data);
                    }
                }
            }

            if (($desde == 2) && ($hasta == 1) && $opcion == 2) { //Mintic
                //update db_config
                //busco registro
                $resultconfig = $this->consulta_bd(array('id' => $id_bd), 'db_config');
                //insertar db_config
                if (!empty($resultconfig)) {
                    //update
                    $data = array(
                        'servidor' => $servidorh,
                        'estado' => 1,
                    );
                    $this->dbConnection->where('id', $id_bd);
                    $this->dbConnection->update('db_config', $data);
                }
                //agregar licencia de 8 meses a partir del dia de activación
                $resultconfig = $this->consulta_bd(array('id_db_config' => $id_bd), 'crm_licencias_empresa');

                if (!empty($resultconfig)) {
                    foreach ($resultconfig as $value) {
                        $fecha_actual = date("Y-m-d");
                        $fecha_fin = date("Y-m-d", strtotime($fecha_actual . "+ 8 month"));

                        $data = array(
                            'planes_id' => 9,
                            'fecha_inicio_licencia' => $fecha_actual,
                            'fecha_vencimiento' => $fecha_fin,
                        );

                        $this->dbConnection->where('id_db_config', $id_bd);
                        $this->dbConnection->update('crm_licencias_empresa', $data);
                    }
                }

            } else {
                if (($desde == 2) && ($hasta == 1) && $opcion == 1) { //probar modulo de producción
                    //update db_config
                    //busco registro
                    $resultconfig = $this->consulta_bd(array('id' => $id_bd), 'db_config');
                    //insertar db_config
                    if (!empty($resultconfig)) {
                        //update
                        $data = array(
                            'servidor' => $servidorh,
                        );
                        $this->dbConnection->where('id', $id_bd);
                        $this->dbConnection->update('db_config', $data);
                    }
                }

            }
            return 1;
        } else {
            return 0;
        }
    }

    public function consulta_bd($where = "", $tabla = "")
    {
        //buscamos las conexiones en vendty2
        $this->db->select("*");
        $this->db->from($tabla);
        if (!empty($where)) {
            $this->db->where($where);
        }

        $result = $this->db->get()->result_array();
        return $result;
    }

    public function activar_modulo($where, $data)
    {

        $this->db->select("*");
        $this->db->from("modulos_clientes");
        $this->db->where($where);
        $result = $this->db->get();

        if ($result->num_rows() == 0) {
            $this->db->insert("modulos_clientes", $data);
        } else {
            $this->db->where($where);
            $this->db->update("modulos_clientes", $data);
        }
    }

    public function get_ajax_data_empresas()
    {

        $aColumns = array(
            'nombre_empresa',
            'nombre_distribuidor',
            'u2.username',
            'telefono_contacto',
            'u.email',
            'e.pais',
            'idempresas_clientes',
            'e.id_db_config',
            'u.id',
        );

        $sIndexColumn = "idempresas_clientes";
        $sTable = "crm_empresas_clientes e";
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
        }

        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . ' ' . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        $sWhere = "";
        $groupby = " GROUP BY $sIndexColumn ";

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere .= " WHERE (";
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
                    $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        $sQuery = "
            SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(", ", $aColumns)) . "
            FROM   $sTable
            INNER JOIN users u ON e.idusuario_creacion = u.id
            LEFT JOIN crm_distribuidores_licencia d ON e.`id_distribuidores_licencia`=d.id_distribuidores_licencia
            INNER JOIN crm_usuarios_distribuidores ud ON d.id_distribuidores_licencia=ud.id_distribuidores_licencia
            INNER JOIN users u2 ON ud.users_id = u2.id
            $sWhere
            $groupby
            $sOrder
            $sLimit";

        $rResult = $this->db->query($sQuery);
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() as cantidad";
        $rResultFilterTotal = $this->db->query($sQuery);
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;

        $sQuery = "SELECT COUNT(`" . $sIndexColumn . "`) as cantidad FROM $sTable";
        $rResultTotal = $this->db->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array(),
        );

        foreach ($rResult->result_array() as $row) {
            $data = array();
            for ($i = 0; $i < count($aColumns); $i++) {
                switch ($i) {
                    case '2':
                        $data[] = $row['username'];
                        break;

                    case '4':
                        $data[] = $row['email'];
                        break;

                    case '5':
                        $data[] = $row['pais'];
                        break;

                    case '7':
                        $data[] = $row['id_db_config'];
                        break;

                    case '8':
                        $data[] = $row['id'];
                        break;

                    default:
                        $data[] = $row[$aColumns[$i]];
                        break;
                }
            }
            $output['aaData'][] = $data;
        }

        return $output;
    }

    public function get_suscripciones_nuevas($tipo = null, $fecha_inicio = null, $fecha_fin = null)
    {

        $planes = array(1, 15, 16, 17, 25);
        if ((!empty($fecha_inicio)) && (!empty($fecha_fin))) {
            $this->db->where("fecha_activacion BETWEEN '$fecha_inicio' AND '$fecha_fin'");
        }

        if ((!empty($tipo)) && ($tipo == 'mensual')) {
            $this->db->where('(pl.dias_vigencia=30 OR pl.dias_vigencia=90 OR pl.dias_vigencia=180)');
        }

        $this->db->select('l.`idlicencias_empresa`,l.`idempresas_clientes`,l.`id_db_config`,l.`fecha_activacion`,l.`fecha_inicio_licencia`,l.`fecha_vencimiento`,pl.`nombre_plan`,pl.`valor_final`,pl.`dias_vigencia`,u.`email`,e.`nombre_empresa`');
        $this->db->from('crm_licencias_empresa l');
        $this->db->join('crm_planes pl', 'l.planes_id = pl.id', 'inner');
        $this->db->join('crm_empresas_clientes e', 'l.idempresas_clientes = e.idempresas_clientes', 'inner');
        $this->db->join('users u', 'e.idusuario_creacion = u.id', 'inner');
        $this->db->where_not_in('planes_id', $planes);
        $query = $this->db->get();
        //echo "<br>".$this->db->last_query();
        return $query->result();
    }

    //obtener los vencidos_suscriptos_nuevos
    public function get_all_vencidos_suscriptos_nuevos($tipo = null, $fecha_inicio = null, $fecha_fin = null, $adicional = null)
    {

        //busco distribuidor
        $user = $this->session->userdata('user_id');
        $distribuidor = $this->get_distribuidor2(array('users_id' => $user));
        $iddistribuidor = $distribuidor[0]['id_distribuidores_licencia'];
        $dias_vigencia = "";
        $fecha_inicio_mes_a = $fecha_inicio;
        $fecha_inicio_mes_a = strtotime('-1 month', strtotime($fecha_inicio_mes_a));
        $fecha_inicio_mes_a = date('Y-m-01', $fecha_inicio_mes_a);

        $fecha_fin_mes_a = $fecha_fin;
        $fecha_fin_mes_a = strtotime('-1 month', strtotime($fecha_fin_mes_a));
        $fecha_fin_mes_a = date('Y-m-d', $fecha_fin_mes_a);
        $fecha_fin_mes_a = strtotime('-1 day', strtotime($fecha_fin_mes_a));
        $fecha_fin_mes_a = date('Y-m-d', $fecha_fin_mes_a);

        if (!empty($adicional)) {
            $this->db->where("l.id_db_config NOT IN (2128)");
        }

        if ($tipo == "mensual") {
            $dias_vigencia = "(pl.dias_vigencia=30 or pl.dias_vigencia=90 or pl.dias_vigencia=180)";
        } else if ($tipo == "anual") {
            $dias_vigencia = "(pl.dias_vigencia=365)";
        } else {
            if ($tipo == "todos") {
                $dias_vigencia = "(pl.dias_vigencia=30 or pl.dias_vigencia=90 or pl.dias_vigencia=180 or pl.dias_vigencia=365)";
            }

        }
        $hoy = date('Y-m-d');
        if ($fecha_inicio == null && $fecha_fin == null) {
            $fecha_fin = strtotime('-1 day', strtotime($hoy));
            $fecha_fin = date('Y-m-d', $fecha_fin);
            $fecha_inicio = date('Y-m-01');
        } else {
            if ($fecha_fin == null) {

                $fecha_fin = strtotime('-1 day', strtotime($hoy));
                $fecha_fin = date('Y-m-d', $fecha_fin);

            }
        }

        if ($fecha_fin > $hoy) {
            $fecha_fin = $hoy;
        }

        $this->db->select('u.db_config_id AS id,u.phone,l.idlicencias_empresa, l.fecha_activacion, l.fecha_inicio_licencia,l.fecha_vencimiento,e.nombre_empresa,pl.nombre_plan,pl.valor_final,u.email,pl.valor_plan, pl.valor_plan as total, e.id_distribuidores_licencia, d.nombre_distribuidor, e.id_user_distribuidor, u2.`username` AS vendedor, pl.dias_vigencia');
        $this->db->from('crm_licencias_empresa l');
        $this->db->join('crm_empresas_clientes e', 'e.idempresas_clientes=l.idempresas_clientes', 'inner');
        $this->db->join('crm_planes pl', 'l.planes_id=pl.id', 'inner');
        $this->db->join('users u', 'u.id=e.idusuario_creacion', 'inner');
        $this->db->join('users u2', 'e.id_user_distribuidor=u2.id', 'inner');
        $this->db->join('crm_distribuidores_licencia d', 'e.id_distribuidores_licencia=d.id_distribuidores_licencia', 'inner');
        $this->db->where("l.fecha_vencimiento BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "'");
        $this->db->where("l.fecha_activacion BETWEEN '" . $fecha_inicio_mes_a . "' AND '" . $fecha_fin_mes_a . "'");
        $this->db->where('l.planes_id !=', 1);
        $this->db->where('l.planes_id !=', 15);
        $this->db->where('l.planes_id !=', 16);
        $this->db->where('l.planes_id !=', 17);
        $this->db->where('l.planes_id !=', 25);
        $this->db->where('l.estado_licencia =', 15);
        $this->db->where($dias_vigencia);
        if ($iddistribuidor != 1) {
            $this->db->where("e.id_distribuidores_licencia =" . $iddistribuidor);
        }
        $this->db->group_by('l.idlicencias_empresa');
        $this->db->order_by('l.fecha_vencimiento');
        $query = $this->db->get();

        //echo $this->db->last_query(); die();
        return $query->result();
    }

    public function update_clave_user_admin($where, $data)
    {
        $this->db->where($where);
        $this->db->update('users', $data);

        if ($this->db->affected_rows() > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    public function getdiascuentaprueba()
    {
        $this->db->select('dias');
        $this->db->from('crm_cuentas_nuevas_vendty');
        return $this->db->get()->result_array();
    }

    /*Se crea métodos para poder consultar informe de cuentas en prueba*/
    public function informe_prueba($fecha_inicio, $fecha_fin)
    {

        if (!isset($_GET['iSortCol_0'])) {

        }
        $aColumns = array(
            'l.idlicencias_empresa',
            'e.nombre_empresa',
            'e.telefono_contacto',
            'u.username',
            'u.first_name',
            'u.email',
            '0',
            '0',
            '0',
            '0',
            'db.fecha',
            'l.fecha_inicio_licencia',
            'l.fecha_vencimiento',
            'u.last_login',
        );

        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
        }

        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {
                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . ' ' . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }

        $sWhere = "";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $sWhere .= "AND (";
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
                    $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere .= ')';
        }

        $sql = "SELECT SQL_CALC_FOUND_ROWS l.idlicencias_empresa, l.idempresas_clientes, l.fecha_inicio_licencia, l.fecha_vencimiento, l.id_db_config,
        e.nombre_empresa, e.tipo_identificacion,  e.identificacion_empresa,e.tipo_negocio, e.telefono_contacto,
        e.direccion_empresa, e.ciudad_empresa, e.pais,
        u.id,u.username, u.first_name, u.email, FROM_UNIXTIME(u.last_login , '%Y-%m-%d') as last_login, db.fecha, db.base_dato, db.usuario, db.clave
        FROM crm_licencias_empresa l
        INNER JOIN crm_empresas_clientes e ON e.idempresas_clientes=l.idempresas_clientes
        INNER JOIN users u ON l.id_db_config=u.db_config_id
        INNER JOIN db_config db ON l.id_db_config=db.id
        WHERE l.fecha_inicio_licencia BETWEEN '$fecha_inicio' AND '$fecha_fin'
        AND l.planes_id = 1
        $sWhere
        $sOrder
        $sLimit";
        // echo $sql; die();
        $rResult = $this->db->query($sql);
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() as cantidad";
        $rResultFilterTotal = $this->db->query($sQuery);
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        $sQuery = "SELECT COUNT(idlicencias_empresa) as cantidad FROM crm_licencias_empresa WHERE fecha_inicio_licencia BETWEEN '$fecha_inicio' AND '$fecha_fin' AND planes_id = 1";
        $rResultTotal = $this->db->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;
        $output = array(
            "sEcho" => (isset($_GET['iSortCol_0'])) ? (intval($_GET['sEcho'])) : 0,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array(),
        );
        $data = array();

        foreach ($rResult->result() as $value) {
            //creo conexion
            /*$value->id=1129;
            $bd='vendty2_db_restaurante_vendty';
            $usuarioh = 'root';
            $claveh = '';
            $servidorh = 'localhost';*/
            $cantproductos = 0;
            $cantfacturas = 0;
            $wizard = 0;
            $fechafacturas = "";
            $bd = $value->base_dato;
            $usuarioh = $value->usuario;
            $claveh = $value->clave;
            $servidorh = 'ec2-35-163-242-38.us-west-2.compute.amazonaws.com';
            $base_datosh = 'vendty2';
            $dns = "mysql://$usuarioh:$claveh@$servidorh/$base_datosh";
            $this->dbConnection2 = $this->load->database($dns, true);
            //verificar que tenga tablas
            //producto
            /* $existetabla = "SHOW TABLES WHERE Tables_in_$bd = 'producto'";
            $existetabla = $this->dbConnection2->query($existetabla)->result();
            if(count($existetabla) > 0){
            $cantproductos = $this->dbConnection2->query("SELECT COUNT(*) AS cantidad FROM $bd.producto");
            $cantproductos= $cantproductos->row()->cantidad;
            }
            //venta
            $existetabla = "SHOW TABLES WHERE Tables_in_$bd = 'venta'";
            $existetabla = $this->dbConnection2->query($existetabla)->result();
            if(count($existetabla) > 0){
            $cantfacturas = $this->dbConnection2->query("SELECT COUNT(*) AS cantidad FROM $bd.venta");
            $cantfacturas = $cantfacturas->row()->cantidad;
            $fechafacturas = $this->dbConnection2->query("SELECT fecha FROM $bd.venta ORDER BY fecha DESC LIMIT 1");
            $fechafacturas = $fechafacturas->row()->fecha;
            }
            //wizard
            $existetabla = "SHOW TABLES WHERE Tables_in_$bd = 'usuario_almacen'";
            $existetabla = $this->dbConnection2->query($existetabla)->result();
            if(count($existetabla) > 0){
            $wizard = $this->dbConnection2->query("SELECT wizard_tiponegocio FROM $bd.usuario_almacen WHERE usuario_id=$value->id");
            $wizard = $wizard->row()->wizard_tiponegocio;
            }   */

            $data[] = array(
                $value->idlicencias_empresa,
                $value->nombre_empresa,
                $value->telefono_contacto,
                $value->username,
                $value->first_name,
                $value->email,
                $wizard,
                $cantproductos,
                $cantfacturas,
                $fechafacturas,
                $value->fecha,
                $value->fecha_inicio_licencia,
                $value->fecha_vencimiento,
                $value->last_login,
            );
        }
        $output['aaData'] = $data;
        return $output;
    }
}
