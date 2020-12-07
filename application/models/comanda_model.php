<?php

class Comanda_model extends CI_Model {

    var $connection;

    // Constructor

    public function __construct() {

        parent::__construct();
    }

    public function initialize($connection) {

        $this->connection = $connection;
    }
    
    public function distribuirComanda(){
        
        $detalle = $this->input->post("detalle");
        
        $detalleResult = [];
        
        if( $detalle != "" ){
            
            foreach( $detalle as $key => $val){
                
                $idFact = $val["factura"];
                $idUser = $val["id"];
                
                $sql = " SELECT estado FROM comanda_notificacion_detalle WHERE id_factura_espera = $idFact AND id_usuario = $idUser ";
                $query = $this->connection->query($sql);
                $estado = $query->num_rows > 0 ? $query->row()->estado : "0";
                
                $row = array(
                    "estado" => $estado,
                    "id_factura_espera" => $idFact,
                    "id_usuario" => $idUser
                );
                
                $detalleResult[] = $row;
                
            }
            
            // Eliminamos todas las relaciones
            $sql = " DELETE FROM comanda_notificacion_detalle ";
            $this->connection->query($sql);
                        
            $this->connection->insert_batch("comanda_notificacion_detalle", $detalleResult);
            
        }else{
            // Eliminamos todas las relaciones
            $sql = " DELETE FROM comanda_notificacion_detalle ";
            $this->connection->query($sql);            
        }
                
        $this->sendPushToUsers();
        
    }
    
    public function sendPushToUsers(){
        
        $now = DateTime::createFromFormat('U.u', microtime(true));
        $ping = $now->format("YdmHisu");
        
        $sql = " UPDATE comanda_notificacion_cliente SET notificacion = '$ping' ";
        $this->connection->query($sql);
        
    }
    
    public function sendPushToServer(){
        
        $now = DateTime::createFromFormat('U.u', microtime(true));
        $ping = $now->format("YdmHisu");
        
        $sql = " UPDATE comanda_notificacion_servidor SET notificacion = '$ping' ";
        $this->connection->query($sql);
        
    }
    
    public function conectarse($id){
                
        $sql = "SELECT usuario FROM comanda_notificacion_cliente WHERE usuario = '$id' ";
        $query = $this->connection->query($sql);
        
        if( $query->num_rows == 0){
                        
            $nombre = $this->session->userdata('username'); 
            
            $row = Array(
                "usuario" => $id,
                "nombre" => $nombre,
                "notificacion" => ""
            );
            $this->connection->insert("comanda_notificacion_cliente", $row);
            
            // Enviamos notificacion para avisar que se ha conectado un nuevo usuario
            $this->sendPushToServer();
            
        }
        
    }
    
    public function setEstado( $tipo, $id ){
        
        // 0 = enviado
        // 1 = recibido
        // 2 = leido
        // 3 = validado
                
        $idUser = $this->session->userdata('user_id');
        
        $sql = "";
        
        if( $tipo == "recibido" ){
            $sql = "
                UPDATE comanda_notificacion_detalle
                SET estado = 1
                WHERE id_usuario = '$idUser'
                AND estado = 0 ;
            ";
        }

        if( $tipo == "visto" ){
            $sql = "
                UPDATE comanda_notificacion_detalle
                SET estado = 2
                WHERE id_factura_espera = '$id'
                AND estado <> 3 ;
                ;
            ";
        }
        
        if( $tipo == "validado" ){
            $sql = "
                UPDATE comanda_notificacion_detalle
                SET estado = 3
                WHERE id_factura_espera = '$id';
            ";
            //Enviamos la notificacion al servidor
            $this->sendPushToServer();
        }
        
        $this->connection->query($sql);
        
    }

    
    public function getData(){
        
        $sql = " SELECT usuario AS id, nombre FROM comanda_notificacion_cliente ";        
        $usuarios = $this->connection->query($sql)->result_array();
        
        $sql = " SELECT cn.id_usuario AS id, cn.id_factura_espera AS factura, fe.factura AS nombre, cn.estado FROM comanda_notificacion_detalle AS cn INNER JOIN factura_espera AS fe ON cn.id_factura_espera = fe.id ";
        $detalles = $this->connection->query($sql)->result_array();
        
        // facturas en espera que no esten asignadas a un usuario
        $sql = " SELECT fe.id, fe.factura AS nombre FROM factura_espera AS fe LEFT JOIN comanda_notificacion_detalle AS cn ON cn.id_factura_espera = fe.id WHERE fe.id > -1 AND cn.id IS NULL ";
        $facturas_espera = $this->connection->query($sql)->result_array();        
        
        $result = array(
            "usuarios" => $usuarios,
            "detalles" => $detalles,
            "espera" => $facturas_espera
        );
        
        return $result;
        
    }
    
    
    public function getComandas($id){
        
        
        // Notificaciones asignadas al usuario        
        $sql = "
            SELECT cn.estado, fe.id, fe.factura, fe.nota, fe.fecha FROM comanda_notificacion_detalle AS cn
            INNER JOIN factura_espera AS fe ON cn.id_factura_espera = fe.id
            WHERE cn.id_usuario = '$id'
            AND fe.activo = '1'
        ";
        
        $comandas = $this->connection->query($sql)->result_array();
        
        // Añadimos los detalles de la comanda
        
        foreach( $comandas as $key => $row ){
            
            $idEspera = $row["id"];
            
            $sqlSub = "
                SELECT 
                df.codigo_producto AS codigo,
                df.nombre_producto AS nombre,
                df.unidades,
                #df.precio_venta AS precio,
                #descuento,
                #ROUND(( df.precio_venta - df.descuento )*(1 + df.impuesto/100)) AS total,
                p.descripcion
                FROM detalle_factura_espera AS df
                LEFT JOIN producto AS p ON df.id_producto = p.id
                WHERE df.venta_id = $idEspera
            ";

            $resultSub = $this->connection->query($sqlSub)->result_array();            
            $comandas[$key]["detalle"] = $resultSub;
                        
            
        }
        
        
        return $comandas;
        
    }
    
    public function getNotificacionServer(){
        
        $sql = " SELECT notificacion FROM comanda_notificacion_servidor ";          
        return $this->connection->query($sql)->result_array();                
    }
    
    public function getNotificacion($id){
        
        $sql = " SELECT notificacion FROM comanda_notificacion_cliente WHERE usuario = '$id'; ";        
        return $this->connection->query($sql)->result_array();                
    }
    
    public function crearTablasNotificaciones(){

        if ( !$this->connection->table_exists('comanda_notificacion_servidor') ){

           $sql = "
               CREATE TABLE IF NOT EXISTS comanda_notificacion_servidor (
                    notificacion VARCHAR(30)
               ) ENGINE=INNODB DEFAULT CHARSET=utf8;
               ";
           $this->connection->query( $sql );
           
           $sql = " INSERT INTO comanda_notificacion_servidor (notificacion) VALUES ('') ";
           $this->connection->query( $sql );
           

        }
        
        if ( !$this->connection->table_exists('comanda_notificacion_cliente') ){

           $sql = "
               CREATE TABLE IF NOT EXISTS comanda_notificacion_cliente (
                        id INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
                        usuario INT(12),
                        nombre VARCHAR(150),
                        notificacion VARCHAR(20),
                        PRIMARY KEY (id)
               ) ENGINE=INNODB DEFAULT CHARSET=utf8;
               ";

           $this->connection->query( $sql );

        }
        
        if ( !$this->connection->table_exists('comanda_notificacion_detalle') ){ 

           $sql = "
               CREATE TABLE IF NOT EXISTS comanda_notificacion_detalle (
                        id INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
                        id_usuario INT(12),
                        id_factura_espera INT(10),
                        estado INT(2),             
                        PRIMARY KEY (id)
               ) ENGINE=INNODB DEFAULT CHARSET=utf8;
               ";

           $this->connection->query( $sql );

        }
        
        if(count($this->connection->get_where('opciones',array("nombre_opcion"=>"comanda_push"))->result()) == 0)
        {
            $this->connection->insert('opciones',array("nombre_opcion"=>"comanda_push","valor_opcion"=>1));
        }

        
    }


    public function insertar_comanda($data){
        $this->connection->insert('comanda_notificacion_detalle',$data);
        $this->sendPushToUsers();
    }


    public function get_usuarios_comanda(){
        $sql = " SELECT usuario AS id, nombre FROM comanda_notificacion_cliente ";        
        $usuarios = $this->connection->query($sql)->result();
        return $usuarios;
    }

    public function get_una_comanda($where){
        $this->connection->where($where);
        $query = $this->connection->get('comanda_notificacion_detalle');
        return $query->result();
    }

     
}

?>