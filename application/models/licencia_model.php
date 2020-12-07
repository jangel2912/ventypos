<?php
class Licencia_model extends CI_Model
{
    public $connection;

    // Constructor
    public function __construct()
    {
        parent::__construct();
    }

    public function initialize($connection)
    {

        $this->connection = $connection;

    }

    public function get_by_id($id)
    {

        $where = array();

        if ($id) {
            $where = array('id_db_config' => $id);
        }

        return $this->db->get_where('v_crm_licencias', $where)->result();
    }

    public function get_by_id_licencia($id)
    {

        $where = array();

        if ($id) {
            $where = array('id_licencia' => $id);
        }

        return $this->db->get_where('v_crm_licencias', $where)->result();
    }

    public function addOrdenPago($data)
    {
        $this->db->insert('crm_orden_licencia', $data);
        return true;
    }

    public function insertPagoLicenciaManual($data)
    {

        $this->db->insert("crm_pagos_licencias", $data);
        return $this->db->insert_id();
    }

    public function insertPagoLicencia($id_licencia, $estado, $valor, $observacion, $info_adicional, $sw, $transaction_id, $ref_payco, $forma_pago = "", $valor_dolares = "", $total_pais = 0, $metodopago = "", $pago_por = "", $currency = "")
    {
        $pagos = "";
        if (empty($info_adicional)) {
            $info_adicional = "no se de donde viene";
        }
        if (empty($forma_pago)) {
            $forma_pago = "3";
        }
        if (empty($valor_dolares)) {
            $valor_dolares = array();
        }
        if (empty($total_pais)) {
            $total_pais = 0;
        }
        if (empty($metodopago)) {
            $metodopago = 0;
        }
        if (empty($pago_por)) {
            $pago_por = 0;
        }
        if (empty($currency)) {
            $currency = 'COP';
        }

        $var = "(fecha_creacion, creado_por, idformas_pago, fecha_pago, monto_pago, estado_pago, fecha_conciliacion, observacion_pago, info_adicional_pago, id_licencia, transaction_id, ref_payco, monto_pago_dolares,monto_total_pais,metodopago,pago_por,moneda_pago)";
        if ($sw != 0) {
            for ($x = 0; $x < count($id_licencia); $x++) {
                $id = $id_licencia[$x];
                $total = $valor[$x];
                $total_dolares = $valor_dolares[$x];
                $values = "(NOW(), 12094, $forma_pago, NOW(), $total, $estado, NOW(), '$observacion', '$info_adicional', $id, '$transaction_id', '$ref_payco', $total_dolares,$total_pais,'$metodopago','$pago_por','$currency')";
                $sql = "INSERT INTO crm_pagos_licencias $var VALUES $values";
                //echo "<br>insertar1= ".$sql;
                $this->db->query($sql);
                $pagos .= "," . $this->db->insert_id();

            }
        } else {
            if (count($valor_dolares) == 0) {
                $valor_dolares = 0;
            }

            $values = "(NOW(), 12094, $forma_pago, NOW(), $valor, $estado, NOW(), '$observacion', '$info_adicional', $id_licencia, '$transaction_id', '$ref_payco', $valor_dolares,$total_pais,'$metodopago','$pago_por','$currency')";
            $sql = "INSERT INTO crm_pagos_licencias $var VALUES $values";
            //echo "<br>insertar0= ".$sql;
            $this->db->query($sql);
            $pagos .= "," . $this->db->insert_id();
        }

        $pagos = trim($pagos, ",");
        return $pagos;
    }

    public function updateLicencia($id_licencia, $id_plan)
    {
        $sql = "UPDATE crm_licencias_empresa SET planes_id = $id_plan, fecha_modificacion = NOW() WHERE idlicencias_empresa = $id_licencia";
        $this->db->query($sql);
        return true;
    }

    public function updateLicencianuevo($id_licencia, $id_plan, $tiempo)
    {

        /**ubico el tipo de plan */
        switch ($tiempo) {
            case '30': //mensual
                $meses = 1;
                break;
            case '90': //trimestral
                $meses = 3;
                break;
            case '180': //Semestral
                $meses = 6;
                break;
            case '365': //Anual
                $meses = 12;
                break;
            default:
                $meses = 1;
                break;
        }

        $f_nueva = date('Y-m-d');
        //echo $f_nueva;
        $f_nueva = strtotime('+' . $meses . ' month', strtotime($f_nueva));
        $fecha_vencimiento = date('Y-m-d', $f_nueva);

        $sql = "UPDATE crm_licencias_empresa SET planes_id = $id_plan, fecha_activacion=NOW(), estado_licencia = 1, fecha_vencimiento='$fecha_vencimiento', fecha_inicio_licencia=NOW(), fecha_modificacion = NOW() WHERE idlicencias_empresa = $id_licencia";
        $this->db->query($sql);
        return true;
    }

    public function getPlanActual($id_licencia)
    {
        $sql = "SELECT L.planes_id FROM crm_empresas_clientes E
                JOIN crm_licencias_empresa L ON E.idempresas_clientes=L.idempresas_clientes
                WHERE L.idlicencias_empresa=$id_licencia";
        $res = $this->db->query($sql)->row();
        return $res->planes_id;
    }

    public function updateEstadoBD($id_db)
    {
        $servidor = "produccion-5.cgog1qhbqtxl.us-west-2.rds.amazonaws.com";
        $sql = "UPDATE db_config SET estado=1, servidor='$servidor' WHERE id=$id_db";
        $this->db->query($sql);
        return true;
    }

    public function updateEstadoBD2($id_db)
    {
        $sql = "UPDATE db_config SET estado=1 WHERE id=$id_db";
        $this->db->query($sql);
        return true;
    }

    public function buscarBD($id_licencia)
    {
        $query = "SELECT L.fecha_inicio_licencia, L.fecha_vencimiento, L.id_almacen, P.nombre_plan, P.valor_plan, D.servidor, D.id, D.base_dato, D.usuario, D.clave, U.email, U.username
        FROM crm_licencias_empresa L JOIN crm_planes P ON L.planes_id=P.id
        JOIN db_config D ON D.id=L.id_db_config
        JOIN users U ON U.db_config_id=D.id
        WHERE L.idlicencias_empresa = $id_licencia ORDER BY U.id LIMIT 0,1";
        return $this->db->query($query)->result_array();
    }

    public function get_planes()
    {
        $query = "SELECT * FROM crm_planes WHERE nombre_plan <> 'PLAN GRATIS' AND mostrar IN(1,2,3) ORDER BY orden_mostrar;";
        $res = $this->db->query($query);

        return $res->result_array();
    }

    public function getPlanesCliente($id_db_config)
    {
        $sql = "SELECT L.planes_id FROM crm_empresas_clientes E JOIN crm_licencias_empresa L ON E.idempresas_clientes=L.idempresas_clientes ";
        $sql .= " WHERE E.id_db_config = $id_db_config ";
        $res = $this->db->query($sql);
        return $res->result_array();
    }

    public function get_ajax_data()
    {
        $id_db_config = $this->session->userdata('db_config_id');
        $sql_crm_licencias = "SELECT l.* FROM v_crm_licencias l WHERE id_db_config = $id_db_config";
        $data = array();

        $ip = "";
        $paisip = "Colombia";
        //busco la ip
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $res = file_get_contents('https://www.iplocate.io/api/lookup/' . $ip);
        $res = json_decode($res);
        $paisip = $res->country;

        if (empty($paisip)) {
            $paisip = "Colombia";
        }

        if ($id_db_config == '11152') {
            $paisip = "Colombia1";
        }
        foreach ($this->db->query($sql_crm_licencias)->result() as $value) {
            $sql = "SELECT id,nombre,direccion,telefono FROM almacen WHERE id= $value->id_almacen";
            $almacen = $this->connection->query($sql)->result();

            if (isset($almacen[0])) {
                $hoy = date("Y-m-d");
                $estado = $value->fecha_vencimiento > $hoy ? "Activa" : "Inactiva";

                if ($paisip == "Colombia") {
                    $valor = $value->valor_plan;
                    $valor2 = $value->valor_plan_dolares;
                } else {
                    $valor = $value->valor_plan_dolares;
                    $valor2 = $value->valor_plan;
                }

                $data[] = array(
                    $almacen[0]->nombre,
                    $almacen[0]->telefono,
                    $value->nombre_plan,
                    $value->fecha_inicio_licencia,
                    $value->fecha_vencimiento,
                    $estado,
                    $valor,
                    $value->id_licencia,
                    $valor2,
                );
            }
        }

        return array(
            'aaData' => $data,
        );
    }

    public function produccion($base_dato)
    {

        $existeDB = $this->db->query("SHOW DATABASES WHERE `database` = '" . $base_dato . "'");

        if ($existeDB->num_rows() == 0) {
            $sql = "CREATE DATABASE $base_dato";
            $this->db->query($sql);
            $this->dump($base_dato);
        }
    }

    public function dump($base_dato)
    {
        $usuario = "vendtyMaster";
        $passwd = "ro_ar_8027*_na";
        $baseDatos = $base_dato;
        $dbhost = "ec2-35-163-242-38.us-west-2.compute.amazonaws.com";
        $dbhost2 = "produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com";
        $nombreBackupSQL = "/mnt/up/sql/{$baseDatos}.sql";
        shell_exec("mysqldump --opt -h $dbhost -u $usuario -p$passwd $baseDatos > $nombreBackupSQL"); //Genero el .sql a restaurar
        shell_exec("mysql -u $usuario -p$passwd -h $dbhost2 $baseDatos < $nombreBackupSQL"); //restauroel .sql
    }
}
