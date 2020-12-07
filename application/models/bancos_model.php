<?php

class Bancos_model extends CI_Model {

    var $connection;

    public function __construct() {
        parent::__construct();
    }

    public function initialize($connection) {
        $this->connection = $connection;
    }

    public function get_bancos(){

        $this->connection->select("*");
        $this->connection->from("bancos");
        $this->connection->order_by("nombre_cuenta","ASC");
        $result = $this->connection->get();
        if($result->num_rows() > 0){
            return $result->result();
        }else{
            return NULL;
        }
    }

    public function get_tipo_movimientos(){
        
        $this->connection->select("*");
        $this->connection->from("tipo_movimiento_banco");
        $this->connection->order_by("nombre","ASC");
        $result = $this->connection->get();
        if($result->num_rows() > 0){
            return $result->result();
        }else{
            return NULL;
        }

    }


    public function get_ajax_data($start,$limit,$search=null, $orderby=null) {

        if($search != null)
                $search = "and nombre_cuenta like '%$search%' or numero_cuenta like '%$search%' or saldo_inicial like '%$search%' or fecha_creacion like '%$search%'";
            else
                $search = '';


        $data = array();

        $sql = "select SQL_CALC_FOUND_ROWS b.id as id_banco,b.fecha_creacion,b.nombre_cuenta,b.numero_cuenta,b.descripcion,b.saldo_inicial,b.fecha_actualizacion from bancos AS b inner join almacen AS a on a.id = b.id_almacen where a.id > 0 $search  $orderby LIMIT $limit  ";
        
        $data = array();
        $rResult = $this->connection->query($sql);            
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() as cantidad";
        $rResultFilterTotal = $this->connection->query($sQuery);
        // $aResultFilterTotal = $rResultFilterTotal->result_array();
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        $sQuery = " SELECT COUNT(id) as cantidad FROM  bancos";
        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;
        $output = array(
            "sEcho" => intval($_GET['sEcho']) ,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        if($rResult->num_rows() > 0){
            $bancos =  $rResult->result();
            foreach ($bancos as $banco) {
                $sql_mov = "SELECT valor, id_banco, id_tipo, mb.id as id_mb, mb.nombre as nombre_mb, mb.tipo as tipo_movimiento FROM movimientos_bancos inner join tipo_movimiento_banco as mb on mb.id = id_tipo where id_banco = " . $banco->id_banco;

                $result_mov = $this->connection->query($sql_mov);
                $movimientos = $result_mov->result();
                $saldo_actual = 0;
                foreach ($movimientos as $key => $movimiento) {

                    if($movimiento->tipo_movimiento == 1){
                        $saldo_actual = $saldo_actual + (int) $movimiento->valor;
                    }
                    if($movimiento->tipo_movimiento == 2) {
                        $saldo_actual = $saldo_actual - (int) $movimiento->valor;
                    }
                }
                $data[] = array(
                    $banco->fecha_creacion,
                    $banco->nombre_cuenta,
                    $banco->numero_cuenta,
                    $banco->descripcion,
                    $this->opciones_model->formatoMonedaMostrar($banco->saldo_inicial),
                    $this->opciones_model->formatoMonedaMostrar((int)$banco->saldo_inicial + (int)$saldo_actual),
                    $banco->fecha_actualizacion,
                    $banco->id_banco,
                );
            }
        }
        $output['aaData'] = $data;
        return $output;
        /*
        return array(
            'aaData' => $data
        );*/
    }

    public function crear_banco(){
        /*$this->connection->select('almacen_id');
        $this->connection->from('usuario_almacen');
        $this->connection->where('usuario_id',$this->session->userdata('user_id'));
        $this->connection->limit(1);
        $result = $this->connection->get();

        $almacen = $result->result()[0]->almacen_id;
        */
        $data = array(
            'nombre_cuenta' => $this->input->post('nombre_cuenta'),
            'numero_cuenta' => $this->input->post('numero_cuenta'),
            'descripcion' => $this->input->post('descripcion'),
            'saldo_inicial' => $this->input->post('saldo_inicial'),
            'fecha_creacion' => date('Y-m-d'),
            'fecha_actualizacion' => date('Y-m-d'),
            'id_almacen' => $this->input->post('almacen'),
            'id_usuario' => $this->session->userdata('user_id')
        );

        $this->connection->insert("bancos",$data);
    }

    public function editar_banco(){
        $data = array(
            'nombre_cuenta' => $this->input->post('nombre_cuenta'),
            'numero_cuenta' => $this->input->post('numero_cuenta'),
            'descripcion' => $this->input->post('descripcion'),
            'id_almacen' => $this->input->post('almacen'),
            'fecha_actualizacion' => date('Y-m-d'),
            'id_usuario' => $this->session->userdata('user_id')
        );

        $this->connection->where("id",$this->input->post('id'));
        $this->connection->update("bancos",$data);
    }

    public function eliminar_banco(){

        $this->connection->select("*");
        $this->connection->from("bancos b ");
        $this->connection->join("movimientos_bancos mb","b.id = mb.id_banco");
        $this->connection->where("b.id",$this->input->post('id'));
        $result = $this->connection->get();

        if($result->num_rows() > 0){
            return "movimientos_asociados";
        }else{
            $this->connection->where("id",$this->input->post('id'));
            $this->connection->delete("bancos");
            if($this->connection->affected_rows() > 0){
                return "success";
            }else{
                return "error";
            }
        }
      
    }

    public function get_ajax_data_movimientos($start,$limit,$search=null, $orderby=null) {
        if(!$limit){
            $limit = 99999999999;
        }
        if($search != null){
                $search_copy = $search;
                $search = "and mb.fecha_creacion like '%$search%' or mb.observacion like '%$search%' or mb.valor like '%$search%' or b.nombre_cuenta like '%$search%' or mb.estado like '%$search%' or tmb.nombre like '%$search%'";
                $search_user = " and u.username like '%$search_copy%'";
        }else{
            $search = '';
            $search_user = '';
        }

        $sql = "select SQL_CALC_FOUND_ROWS mb.id,mb.fecha_creacion,mb.referencia, mb.observacion, mb.valor, mb.estado, b.nombre_cuenta as nombre_banco, tmb.nombre as nombre_movimiento, tmb.tipo as tipo_movimiento, mb.id_usuario_creacion from movimientos_bancos AS mb inner join bancos AS b on mb.id_banco = b.id left join tipo_movimiento_banco tmb on tmb.id = mb.id_tipo where mb.id > 0 $search $orderby LIMIT $limit";

        $data = array();
        $rResult = $this->connection->query($sql);            
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() as cantidad";
        $rResultFilterTotal = $this->connection->query($sQuery);
        // $aResultFilterTotal = $rResultFilterTotal->result_array();
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        $sQuery = " SELECT COUNT(id) as cantidad FROM movimientos_bancos";
        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;

        if(!isset($_GET['sEcho'])){
            $_GET['sEcho'] = 0;
        }

        $output = array(
            "sEcho" => intval($_GET['sEcho']) ,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );
        
        if($rResult->num_rows() > 0){
            $movimientos =  $rResult->result();
            foreach ($movimientos as $movimiento) {
                $user = "";
                $sql_user = "select u.username from users as u where u.id = '".$movimiento->id_usuario_creacion."' $search_user LIMIT 1";
               
                $result_user = $this->db->query($sql_user);
                if($result_user->num_rows() > 0) $user = $result_user->result()[0]->username;

                $data[] = array(
                    $movimiento->fecha_creacion,
                    ($movimiento->referencia) ? $movimiento->referencia : '0',
                    $movimiento->nombre_movimiento,
                    ($movimiento->tipo_movimiento == 1) ? 'Entrada' : 'Salida',
                    $movimiento->valor,
                    ($movimiento->observacion) ? $movimiento->observacion : '0',
                    $movimiento->nombre_banco,
                    ($movimiento->estado == 'conciliado') ? 'Conciliado' : 'Sin conciliar',
                    $user,
                    $movimiento->id,
                );
            }
        }

        $output['aaData'] = $data;
        return $output;

       /* return array(
            'aaData' => $data
        );*/
    }

    public function validateNombreyCodigo($id,$campo){
        $this->connection->select();
        $this->connection->from("bancos");
        $this->connection->where($campo,$id);
        $result = $this->connection->get();
        if($result->num_rows() > 0):
            return 1;
        else:
            return 0;
        endif;
    }

    public function crear_movimiento(){
        $data = array(
            'observacion' => $this->input->post('observacion'),
            'nota_impresion' => $this->input->post('nota_impresion'),
            'valor' => $this->input->post('valor'),
            'fecha_creacion' => date('Y-m-d'),
            'id_banco' => $this->input->post('banco'),
            'id_tipo' => $this->input->post('tipo_movimiento'),
            'id_usuario_creacion' => $this->session->userdata('user_id'),
            'referencia' => $this->input->post('referencia')
        );

        $this->connection->insert("movimientos_bancos",$data);
        return true;
    }

    public function editar_movimiento(){
        $data = array(
            'observacion' => $this->input->post('observacion'),
            'nota_impresion' => $this->input->post('nota_impresion'),
            'valor' => $this->input->post('valor'),
            'id_banco' => $this->input->post('banco'),
            'id_tipo' => $this->input->post('tipo_movimiento'),
            'referencia' => $this->input->post('referencia'),
        );

        $id = $this->input->post("id_movimiento");
        $this->connection->where("movimientos_bancos.id",$id);
        $this->connection->update("movimientos_bancos",$data);
        return true;
    }

    public function crear_tipo_movimiento(){
        $this->connection->select('*');
        $this->connection->from('tipo_movimiento_banco');
        $this->connection->where('nombre',$this->input->post('nombre_movimiento'));
        $this->connection->where('tipo',$this->input->post('tipo_movimiento'));
        $result = $this->connection->get();
        if($result->num_rows() > 0){
            return false;
        }else{
            $data = array(
                'nombre' => $this->input->post('nombre_movimiento'),
                'tipo' => $this->input->post('tipo_movimiento')
            );
            $this->connection->insert("tipo_movimiento_banco",$data);

            return true;
        }
    }

    public function get_banco($id){
        
        $this->connection->select("b.*,a.nombre");
        $this->connection->from("bancos b");
        $this->connection->join("almacen a","b.id_almacen = a.id");
        $this->connection->where("b.id",$id);
        $this->connection->limit(1);
        $result = $this->connection->get();
        if($result->num_rows() > 0){
            return $result->result()[0];
        }else{
            return NULL;
        }
    }

    public function get_movimientos_por_banco($id){
        $this->connection->select("m.*, t.nombre,t.tipo");
        $this->connection->from("bancos b");
        $this->connection->join("movimientos_bancos m","b.id = m.id_banco");
        $this->connection->join("tipo_movimiento_banco t","t.id = m.id_tipo");
        $this->connection->where("b.id",$id);
        $result = $this->connection->get();
        if($result->num_rows() > 0){
            return $result->result();
        }else{
            return NULL;
        }
    }

    public function conciliar_movimientos(){

        $movimientos = json_decode($this->input->post('movimientos'));
        
        $data = array(
            'transaccion' => 1,
            'gastos_bancarios' => $this->input->post('gastos_bancarios'),
            'impuestos_bancarios' => $this->input->post('impuestos_bancarios'),
            'entradas_bancarias' => $this->input->post('entradas_bancarias'),
            'saldo_final' => $this->input->post('saldo_final'),
            'fecha_corte' => $this->input->post('fecha_corte'),
            'fecha_creacion' => date('Y-m-d'),
            'id_banco' => $this->input->post('banco')
        );
        $this->connection->insert('conciliaciones',$data);
        $id = $this->connection->insert_id();
        
        $movimientos = json_decode($this->input->post('movimientos'));
        $movimientos_conciliados = 0;

        foreach($movimientos as $movimiento):
            $data_movimientos = array(
                'estado' => 'conciliado',
                'id_conciliacion' => $id
            );
    
            $this->connection->where('id',$movimiento);
            $this->connection->update('movimientos_bancos',$data_movimientos);
            if($this->connection->affected_rows() > 0) $movimientos_conciliados++;
        endforeach;
        
        if($movimientos_conciliados == count($movimientos)){
            $this->connection->where('id_banco', $this->input->post('banco'));
            $this->connection->delete('conciliaciones_pendientes');
        }
        
        return true;
    }

    public function get_movimiento($id){
        $this->connection->select("mb.*");
        $this->connection->from("movimientos_bancos mb");
        $this->connection->where("mb.id",$id);
        $this->connection->limit(1);
        $result = $this->connection->get();

        if($result->num_rows() > 0){
            $movimiento = $result->result()[0];
            if($movimiento->estado == 'conciliado' && $movimiento->id_conciliacion != NULL){
                return NULL;
            }else{
                return $movimiento;
            }
        }else{
            return NULL;
        }
    }

    public function get_movimiento_detalle($id){
        $this->connection->select("mb.*,tmb.nombre,tmb.tipo");
        $this->connection->from("movimientos_bancos mb");
        $this->connection->join("tipo_movimiento_banco tmb","mb.id_tipo = tmb.id");
        $this->connection->where("mb.id",$id);
        $this->connection->limit(1);
        $result = $this->connection->get();

        if($result->num_rows() > 0){
            $movimiento = $result->result()[0];
            return $movimiento;
        }else{
            return NULL;
        }
    }

    public function eliminar_movimiento(){

        $this->connection->select("*");
        $this->connection->from("movimientos_bancos mb");
        $this->connection->where("mb.id",$this->input->post('id'));
        $this->connection->where("mb.estado","conciliado");
        $result = $this->connection->get();

        //conciliado
        if($result->num_rows() > 0){
            return "movimiento_conciliado";
        }else{
            $estado_pendiente = false; 
            //pendiente por conciliar
            $this->connection->select("*");
            $this->connection->from("conciliaciones_pendientes cp");
            $result = $this->connection->get();

            if($result->num_rows() > 0){
                foreach($result->result() as $conciliacion_pendiente){
                    $movimientos_pendientes = json_decode($conciliacion_pendiente->movimientos);
                    for($i=0;$i<count($movimientos_pendientes);$i++){
                        if($movimientos_pendientes[$i] == $this->input->post('id') ){
                            $estado_pendiente = true;
                        }
                    }
                } 
            }

            if($estado_pendiente){
                return 'movimiento_pendiente';
            }else{  
                $this->connection->where("id",$this->input->post('id'));
                $this->connection->delete("movimientos_bancos");
                if($this->connection->affected_rows() > 0){
                    return "success";
                }else{
                    return "error";
                }
            }
        }
    }

  


    /****************************************************** */
    /****************** Conciliaciones ******************** */
    /****************************************************** */

    public function get_conciliaciones(){
        $this->connection->select("c.*,b.nombre_cuenta");
        $this->connection->from("conciliaciones c");
        $this->connection->join("bancos b","c.id_banco = b.id");
        $result = $this->connection->get();

        if($result->num_rows() > 0){
            return $result->result();
        }else{
          return NULL;
        }
    }

    public function get_movimientos_conciliacion($id_conciliacion){
        $this->connection->select("mb.*, t.nombre as nombre_movimiento, t.tipo");
        $this->connection->from("movimientos_bancos mb");
        $this->connection->join("tipo_movimiento_banco t","t.id = mb.id_tipo");
        $this->connection->where("mb.id_conciliacion",$id_conciliacion);
        $result = $this->connection->get();

        if($result->num_rows() > 0){
            return $result->result();
        }else{
            return 'null';
        }
    }

    public function get_data_banco_conciliacion($id_banco){
        
        $data = array();

        $this->connection->select("*");
        $this->connection->from("bancos b");
        //$this->connection->join("movimientos_bancos mb","b.id = mb.id_banco");
        //$this->connection->join("tipo_movimiento_banco tmb","tmb.id = mb.id_tipo");
        $this->connection->where("b.id",$id_banco);
        $this->connection->limit(1);
        $result = $this->connection->get();

        if($result->num_rows() > 0){
            $id = $result->result()[0]->id;

            $this->connection->select("mb.*, tmb.nombre, tmb.tipo");
            $this->connection->from("movimientos_bancos mb");
            $this->connection->join("tipo_movimiento_banco tmb","tmb.id = mb.id_tipo");
            $this->connection->where("mb.id_banco",$id);
            $movimientos = $this->connection->get();

            $data["banco"] = $result->result()[0];
            $data["movimientos"] = $movimientos->result(); 

            return $data;
        }else{
            return NULL;
        }
    }

    /* Conciliacion pendiente */
    function guardar_conciliacion(){
        $response = "error";
        $conciliacion_pendiente = $this->validar_conciliacion_pendiente($this->input->post('banco'));
        $data = array(
            'gastos_bancarios' => $this->input->post('gastos_bancarios'),
            'impuestos_bancarios' => $this->input->post('impuestos_bancarios'),
            'entradas_bancarias' => $this->input->post('entradas_bancarias'),
            'saldo_final' => $this->input->post('saldo_final'),
            'fecha_corte' => $this->input->post('fecha_corte'),
            'fecha_creacion' => date('Y-m-d'),
            'movimientos' => $this->input->post('movimientos'),
            'id_banco' => $this->input->post('banco'),
            
        );

        if($conciliacion_pendiente){
            $this->connection->insert('conciliaciones_pendientes',$data);
            if( $this->connection->affected_rows() > 0) $response = "insert_success";
        }else{
            $this->connection->where('id_banco',$data["id_banco"]);
            $this->connection->update('conciliaciones_pendientes',$data);
            if( $this->connection->affected_rows() > 0) $response = "update_success";
        }

        return $response;
    }

    function validar_conciliacion_pendiente($id_banco){
        $this->connection->select("*");
        $this->connection->from("conciliaciones_pendientes");
        $this->connection->where("id_banco",$id_banco);
        $result = $this->connection->get();
        if($result->num_rows() > 0){
            return false;
        }else{
            return true;
        }
    }

    public function get_conciliacion_pendiente($id_banco){
        $data = array();        
        $this->connection->select("*");
        $this->connection->from("conciliaciones_pendientes");
        $this->connection->where("id_banco",$id_banco);
        $result = $this->connection->get();
        if($result->num_rows() > 0){
            $conciliacion_pendiente = $result->result()[0];
            $movimientos_pendientes = json_decode($conciliacion_pendiente->movimientos);
            
            foreach($movimientos_pendientes as $movimiento):    
                $data["movimientos"][] = $this->get_movimiento_detalle($movimiento);
            endforeach;
            $data["conciliacion"] = $conciliacion_pendiente;
        }

        return $data;
    }

    public function check_tables(){
        
        $sql = "CREATE TABLE IF NOT EXISTS `bancos` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nombre_cuenta` varchar(45) DEFAULT NULL,
            `numero_cuenta` varchar(45) DEFAULT NULL,
            `descripcion` text,
            `saldo_inicial` double DEFAULT NULL,
            `fecha_creacion` date DEFAULT NULL,
            `fecha_actualizacion` date DEFAULT NULL,
            `id_almacen` int(11) DEFAULT NULL,
            `id_usuario` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB;";
          $this->connection->query($sql);


        $sql = "CREATE TABLE IF NOT EXISTS `categorias_gastos` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nombre` varchar(150) DEFAULT NULL,
            KEY `id` (`id`)
          ) ENGINE=InnoDB;";
        $this->connection->query($sql);


        $sql = "CREATE TABLE IF NOT EXISTS `conciliaciones` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `transaccion` varchar(25) NOT NULL,
            `gastos_bancarios` double DEFAULT NULL,
            `impuestos_bancarios` double DEFAULT NULL,
            `entradas_bancarias` double DEFAULT NULL,
            `saldo_final` double DEFAULT NULL,
            `fecha_corte` date DEFAULT NULL,
            `fecha_creacion` date DEFAULT NULL,
            `id_banco` int(11) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `fk_conciliaciones_bancos1` (`id_banco`),
            CONSTRAINT `fk_conciliaciones_bancos1` FOREIGN KEY (`id_banco`) REFERENCES `bancos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
          ) ENGINE=InnoDB;";
        $this->connection->query($sql);


        $sql = "CREATE TABLE IF NOT EXISTS `conciliaciones_pendientes` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `gastos_bancarios` double DEFAULT NULL,
            `impuestos_bancarios` double DEFAULT NULL,
            `entradas_bancarias` double DEFAULT NULL,
            `saldo_final` double DEFAULT NULL,
            `fecha_corte` date DEFAULT NULL,
            `fecha_creacion` date DEFAULT NULL,
            `movimientos` varchar(100) DEFAULT NULL,
            `id_banco` int(11) NOT NULL,
            PRIMARY KEY (`id`),
            KEY `fk_conciliaciones_bancos1` (`id_banco`),
            CONSTRAINT `fk_conciliaciones_guardadas_bancos1` FOREIGN KEY (`id_banco`) REFERENCES `bancos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
          ) ENGINE=InnoDB;";
        $this->connection->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS `tipo_movimiento_banco` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `nombre` varchar(45) DEFAULT NULL,
            `tipo` int(2) NOT NULL DEFAULT '1' COMMENT '1 - entrada, 2 - salida',
            PRIMARY KEY (`id`)
            ) ENGINE=InnoDB;";
          $this->connection->query($sql);


        $sql = "CREATE TABLE IF NOT EXISTS `movimientos_bancos` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `observacion` text,
            `nota_impresion` text,
            `valor` double DEFAULT NULL,
            `fecha_creacion` date DEFAULT NULL,
            `estado` varchar(20) DEFAULT NULL,
            `id_banco` int(11) NOT NULL,
            `id_tipo` int(11) NOT NULL,
            `id_conciliacion` int(11) DEFAULT NULL,
            `id_usuario_creacion` int(11) DEFAULT NULL,
            `referencia` varchar(30) DEFAULT NULL,
            PRIMARY KEY (`id`),
            KEY `fk_movimientos_bancos_bancos` (`id_banco`),
            KEY `fk_movimientos_bancos_tipo_movimiento_banco2` (`id_tipo`),
            CONSTRAINT `fk_movimientos_bancos_bancos` FOREIGN KEY (`id_banco`) REFERENCES `bancos` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
            CONSTRAINT `fk_movimientos_bancos_tipo_movimiento_banco2` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_movimiento_banco` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
          ) ENGINE=InnoDB;";
        $this->connection->query($sql);


        $sql = "CREATE TABLE IF NOT EXISTS `subcategorias_gastos` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `nombre` varchar(150) DEFAULT NULL,
          `id_categoria` int(11) DEFAULT NULL,
          KEY `id` (`id`)
        ) ENGINE=InnoDB;";
        $this->connection->query($sql);

        
        $sql = "SHOW COLUMNS FROM proformas LIKE 'banco_asociado'";
        $existeCampo = $this->connection->query($sql)->result();
        if(count($existeCampo) == 0)
        {
            $sql = "ALTER TABLE `proformas`   
                ADD COLUMN `banco_asociado` int(11) DEFAULT NULL COMMENT 'Campo para verificar si el gasto fue asociado a un banco',
                ADD COLUMN `subcategoria_asociada` int(11) DEFAULT NULL COMMENT 'Campo para verficar la categoria asociada al banco',
                ADD COLUMN `movimiento_asociado` int(11) DEFAULT NULL COMMENT 'Campo para verificar si tiene movimiento activo';
            ";

            $this->connection->query($sql);
        }

        $sql = "SELECT * FROM tipo_movimiento_banco WHERE id=1 AND nombre='salida' AND tipo = '2'";
        $result = $this->connection->query($sql);
        if($result->num_rows() == 0){
            $sql = "INSERT INTO tipo_movimiento_banco VALUES(1,'salida',2)";
            $this->connection->query($sql);
        }

        /* Validamos categoria de gastos*/ 
        $sql = "SELECT * FROM categorias_gastos WHERE nombre='gastos de personal' OR nombre='gastos generales'";
        $result = $this->connection->query($sql);
        if($result->num_rows() == 0){
            $sql = "INSERT INTO categorias_gastos VALUES ('2','gastos de personal'),
            ('3','gastos generales'),
            ('4','gastos financieros'),
            ('5','gastos por impuestos')";
            $this->connection->query($sql);
        }

         /* Validamos SUB-categorias de gastos*/ 
         $sql = "SELECT * FROM subcategorias_gastos WHERE nombre='sueldos' OR nombre='arrendamientos'";
         $result = $this->connection->query($sql);
         if($result->num_rows() == 0){
             $sql = "INSERT INTO subcategorias_gastos VALUES ('2','sueldos','1'),
             ('3','comisiones,honorarios y servicios','2'),
             ('4','arrendamientos','2'),
             ('5','servicios públicos','2'),
             ('6','papelería','2'),
             ('7','servicios de aseo','2'),
             ('8','restaurante y lavanderia','2'),
             ('9','publicidad','2'),
             ('10','vigilancia','2'),
             ('11','seguros generales','2'),
             ('12','otros gastos generales','2'),
             ('13','ajuste por diferencia en cambio','3'),
             ('14','ajuste por aproximaciones en cálculos','3'),
             ('15','impuestos de renta','4'),
             ('16','otros impuestos','4')";
             $this->connection->query($sql);
         }
    }
}

?>