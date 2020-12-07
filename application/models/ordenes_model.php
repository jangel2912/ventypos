<?php 
class Ordenes_model extends CI_Model
{
    
    var $connection;
        // Constructor
    
    public function __construct() {

        parent::__construct();
    }
    
    public function initialize($connection) {
    
        $this->connection = $connection;
    }

    public function verificarMesa($id){
        
        $query = $this->connection->query("SELECT * FROM orden_producto_restaurant where mesa_id = $id and estado = 2");
        return $query->num_rows();
    }

    public function addProductoOrden($data){
        $data['created_at']=date('Y-m-d H:i:s');
        $this->connection->select('*');
        $this->connection->from('orden_producto_restaurant');
        $this->connection->where('order_producto',$data["order_producto"]);
        $this->connection->where('zona',$data["zona"]);
        $this->connection->where('mesa_id',$data["mesa_id"]);
        $this->connection->where('almacen',$data["almacen"]);
        $this->connection->where('estado',1);
        
        if((isset($data["tablecantidad"])) && ($data["tablecantidad"]=="tablecantidad")){
            $this->connection->where('id',$data["id"]);
            unset($data["tablecantidad"]);
            $this->connection->update("orden_producto_restaurant", $data);
        }else{
            $result = $this->connection->get()->result_array();      
            $band=false;
            if(count($result) > 0){
                foreach ($result as $key => $value) {               
                    if((empty($value['order_modificacion']))&&(empty($value['order_adiciones']))){
                        $band=true;
                        $data_update = array(
                            'cantidad' => $data["cantidad"]
                        );
                        $this->connection->where('order_producto',$data["order_producto"]);
                        $this->connection->where('zona',$data["zona"]);
                        $this->connection->where('mesa_id',$data["mesa_id"]);
                        $this->connection->where('almacen',$data["almacen"]);
                        $this->connection->where('id',$value['id']);
                        $this->connection->update("orden_producto_restaurant", $data_update);
                    }           
                }
                if(!$band){                
                    $data["cantidad"]=1;
                    $this->connection->insert("orden_producto_restaurant", $data);                   
                }  
            }else{
                $data["cantidad"]=1;
                $this->connection->insert("orden_producto_restaurant", $data);  
            }
        }
        
    }

    public function getProductoOrden($zona,$mesa,$almacenA=0){
        $sql_precioxalmacen='SELECT valor_opcion FROM opciones WHERE nombre_opcion="precio_almacen"';
        $precioxalmacen = $this->connection->query($sql_precioxalmacen)->row();
        $orden=" order by id desc";
        if($precioxalmacen->valor_opcion=="1"){
            $str_query = "SELECT opr.*, sa.precio_venta, p.nombre, opr.estado/*, imp.**/
                        FROM orden_producto_restaurant opr
                        INNER JOIN stock_actual sa ON opr.order_producto=sa.producto_id
                        INNER JOIN producto p ON opr.order_producto=p.id 
                        /*INNER JOIN impuesto imp ON sa.impuesto=imp.id_impuesto*/
                        WHERE sa.almacen_id=$almacenA
                        AND opr.zona = $zona
                        AND opr.mesa_id = $mesa
                        AND opr.almacen= $almacenA";    
        }
        else{
            //$str_query = "select a.*, p.precio_venta,p.nombre, a.estado  from orden_producto_restaurant a, producto p where a.order_producto = p.id and a.zona = $zona and a.mesa_id = $mesa";
            $str_query = "SELECT a.*, p.precio_venta,p.nombre, a.estado/*, imp.*  */
            FROM orden_producto_restaurant a, producto p/*, impuesto imp*/ 
            WHERE a.order_producto = p.id 
            /*AND p.impuesto=imp.id_impuesto*/
            AND a.zona = $zona AND a.mesa_id = $mesa";
        }
        $query = $this->connection->query($str_query);       
        $query=$query->result_array();
       
        $datos=array();
        
        if(!empty($query)){
            foreach ($query as $key => $value) {
                if($precioxalmacen->valor_opcion=="1"){
                     $sql="SELECT i.* FROM stock_actual sa INNER JOIN impuesto i WHERE sa.producto_id=".$value['order_producto']." AND sa.impuesto=i.id_impuesto"; 
                }else{
                    $sql="SELECT i.* FROM producto p INNER JOIN impuesto i WHERE p.id=".$value['order_producto']." AND p.impuesto=i.id_impuesto"; 
                }
                $sql=$this->connection->query($sql)->result_array();

                if(empty($sql)){                   
                    $sql[0]['id_impuesto']=0;
                    $sql[0]['nombre_impuesto']="Sin Impuesto";
                    $sql[0]['porciento']=0;
                }

                $value['id_impuesto']=$sql[0]['id_impuesto'];
                $value['nombre_impuesto']=$sql[0]['nombre_impuesto'];
                $value['porciento']=$sql[0]['porciento'];                
                $datos[]=$value;
            }            
        }
       
        return $datos;
    }

    public function getOrdenById($id){
        $str_query = "select * from orden_producto_restaurant a where a.id = $id";    
        
        
        $query = $this->connection->query($str_query);
        return $query->result_array();
    }

    public function getOrdenByMesa($id,$zona,$mesa,$adicional = null, $modificacion = null, $id_orden = 0){
        if($id_orden !=0){
           $where=" AND id=$id_orden";
        }
       // echo "SELECT * FROM orden_producto_restaurant where order_producto = $id and zona = '".$zona."' and mesa_id = '".$mesa."' and estado in (1) $where";
        //$query = $this->connection->query("SELECT * FROM orden_producto_restaurant where order_producto = $id and zona = '".$zona."' and mesa_id = '".$mesa."' and estado in (1,2)");
        $query = $this->connection->query("SELECT * FROM orden_producto_restaurant where order_producto = $id and zona = '".$zona."' and mesa_id = '".$mesa."' and estado in (1) $where");       
        $num_rows =  $query->num_rows();
        if($num_rows > 0){
            foreach($query->result() as $orden){
                if($adicional != null){
                    $adiciones = json_decode($orden->order_adiciones);
                    if(count($adiciones)){
                        if(!in_array($adicional,$adiciones)){
                            return false;
                        }
                    }else{
                        return false;
                    }

                }
                if($modificacion != null){
                    
                    $modificaciones = json_decode($orden->order_modificacion);
                    if(count($modificaciones)){
                        if(!in_array($modificacion,$modificaciones)){
                            return false;
                        }
                    }else{
                        return false;
                    }

                }
            }
        }
        return true;
        
    }

    public function saveModificacion($id,$data){
        $this->connection->where('id', $id);
        $array = array();
        foreach($data as $value)
        {
            array_push($array,$value);
        }
        if(empty($array)){
            $array="";
        }
        else{
            $array=json_encode($array);
        }
        $this->connection->update("orden_producto_restaurant", array('order_modificacion'=> $array));
        return true;

    }

    public function saveAdicional($id,$data){
        $this->connection->where('id', $id);
        $array = array();
        foreach($data as $value)
        {
            array_push($array,$value);
        }
         if(empty($array)){
            $array="";
        }
        else{
            $array=json_encode($array);
        }
        $this->connection->update("orden_producto_restaurant", array('order_adiciones'=> $array));
        return true;

    }

    public function deleteProductoOrden($id)
    {
        $this->connection->where('id', $id);
        $this->connection->delete("orden_producto_restaurant");
        return true;
    }

    public function eliminarOrden($zona,$mesa,$estado=0){
        if($estado!=0){
            //$this->connection->where('estado', $estado);
            $this->connection->where_in('estado', $estado);
        }
        $this->connection->where('zona', $zona);
        $this->connection->where('mesa_id', $mesa);
        $this->connection->delete("orden_producto_restaurant");
        return true;
    }

    public function guardarOrden($zona,$mesa,$id_venta,$estado=0){
        $this->connection->select("*");
        $this->connection->from("orden_producto_restaurant");
        $this->connection->where("zona",$zona);
        $this->connection->where("mesa_id",$mesa);
        if($estado!=0){            
            $this->connection->where_in('estado', $estado);
        }
        $result = $this->connection->get();

        if($result->num_rows() > 0){
            $ordenessql = $result->result_array();
            foreach($ordenessql as $orden):
                $ordenes=array();
                $ordenes["id_venta"] = $id_venta;
                $ordenes["order_producto"] = $orden['order_producto'];
                $ordenes["order_modificacion"] = $orden['order_modificacion'];
                $ordenes["order_adiciones"] = $orden['order_adiciones'];
                $ordenes["zona"] = $orden['zona'];
                $ordenes["mesa_id"] = $orden['mesa_id'];
                $ordenes["estado"] = $orden['estado'];
                $ordenes["created_at"] = $orden['created_at'];
                $ordenes["update_at"] = $orden['update_at'];
                $ordenes["cantidad"] = $orden['cantidad'];
                $ordenes["almacen"] = $orden['almacen'];
                //print_r($ordenes); //die();
                $this->connection->insert("historico_orden_producto_restaurant",$ordenes);
            endforeach;
        }
    }

    public function confirmarOrden($zona,$mesa,$notacomanda){
        $this->connection->where('zona', $zona);
        $this->connection->where('mesa_id', $mesa);
        $this->connection->where('estado', 1);
        $this->connection->update("orden_producto_restaurant", array('estado'=> 2,'nota'=> $notacomanda,'update_at'=>date("Y-m-d H:i:s")));
        return true;
    }
    public function CambiarDivisionCuentaOrdenZonaMesa($zona,$mesa){
        $this->connection->where('zona', $zona);
        $this->connection->where('mesa_id', $mesa);
        $this->connection->where('estado', 4);
        $this->connection->update("orden_producto_restaurant", array('estado'=> 3));
        return true;
    }
    public function CambiarDivisionCuentaOrden($orden,$estado=0){
        $this->connection->where('id', $orden);
        $this->connection->update("orden_producto_restaurant", array('estado'=> $estado));
        return true;
    }

    public function DuplicarRowDivisionCuentaOrden($orden,$cantp=0){
       
        $sql="INSERT INTO orden_producto_restaurant (order_producto,order_modificacion,order_adiciones,zona,mesa_id,estado,created_at,update_at,cantidad,almacen)
                SELECT order_producto,order_modificacion,order_adiciones,zona,mesa_id,4,created_at,update_at,SUM(cantidad-$cantp) AS cantidad,almacen
                FROM orden_producto_restaurant 
                WHERE id = $orden";
        $this->connection->query($sql);
        $this->connection->where('id', $orden);
        $this->connection->update("orden_producto_restaurant", array('cantidad'=> $cantp));
        return true;
    }

    public function verificaEstado($zona,$mesa)
    {
        $query = $this->connection->query("SELECT * FROM orden_producto_restaurant where zona = $zona and mesa_id = $mesa and estado in (1,2,3)");
        return $query->num_rows();
    }

    public function verifyOrdersInCommand($zona,$mesa)
    {
        $query = $this->connection->query("SELECT * FROM orden_producto_restaurant where zona = $zona and mesa_id = $mesa and estado in (2)");
        return $query->num_rows();
    }

    public function getFechaOrdenMesa($zona, $mesa)
    {
        $array_datos = array(
            "mesa_id" => $mesa,
            "zona" => $zona
        );
        $this->connection->where($array_datos);
        $this->connection->order_by('created_at', 'asc');
        $this->connection->limit('1');

        $query = $this->connection->get('orden_producto_restaurant');
        return $query->row_array();
    }

    public function getDataOrdenByMesa($zona,$mesa,$almacenA=0, $all = false){
        $where_state = '';
        if($zona == -1 || $all){
            $where_state = " AND o.estado IN(1,2,3)";
        }else{
            $where_state = " AND o.estado IN(2,3)";
        }

        $sql_precioxalmacen='SELECT valor_opcion FROM opciones WHERE nombre_opcion="precio_almacen"';
        $precioxalmacen = $this->connection->query($sql_precioxalmacen)->row();
       
        if($precioxalmacen->valor_opcion=="1"){
            
            $str_query = "SELECT 0 AS id_cliente,o.mesa_id AS nombre_comercial, o.*,p.id AS fk_id_producto,p.nombre, sa.precio_venta AS precio,p.codigo,p.nombre AS descripcion_d, IFNULL(i.porciento,0) AS impuesto, (sa.precio_venta * IFNULL(i.porciento,0)) / 100 AS monto_iva, sa.precio_venta AS monto, ms.comensales
            FROM orden_producto_restaurant o
            INNER JOIN stock_actual sa ON o.order_producto=sa.producto_id
            INNER JOIN producto p ON o.order_producto=p.id
            LEFT JOIN impuesto i ON sa.impuesto=i.id_impuesto
            LEFT JOIN mesas_secciones ms ON ms.id=o.mesa_id
            WHERE o.almacen=sa.almacen_id
            AND o.almacen=$almacenA
            AND o.zona = $zona AND o.mesa_id = $mesa 
            $where_state";    
        }
        else{
            //$str_query = "select 0 as id_cliente,o.mesa_id as nombre_comercial, o.*,p.id as fk_id_producto,p.nombre, p.precio_venta as precio,p.codigo,p.nombre as descripcion_d, i.porciento as impuesto, (p.precio_venta * i.porciento) / 100 as monto_iva, precio_venta as monto from orden_producto_restaurant o, producto p, impuesto i where p.impuesto = i.id_impuesto and o.zona = $zona and o.mesa_id = $mesa and estado in(2,3) and o.order_producto = p.id";
            $str_query = "SELECT 0 AS id_cliente,o.mesa_id AS nombre_comercial, 
                        o.*,p.id AS fk_id_producto,p.nombre, p.precio_venta AS precio,
                        p.codigo,p.nombre AS descripcion_d,  
                        IFNULL(i.porciento,0) AS impuesto, 
                        (p.precio_venta * IFNULL(i.porciento,0)) / 100 AS monto_iva,
                        precio_venta AS monto 
                        FROM orden_producto_restaurant o
                        INNER JOIN producto p ON p.id = o.order_producto 
                        LEFT JOIN impuesto i ON p.impuesto=i.id_impuesto
                        WHERE o.zona = $zona AND o.mesa_id = $mesa
                        $where_state";
        }        
       
        $query = $this->connection->query($str_query);
        return $query->result();
    }

    public function getDataOrdenByZona($zona){
        $str_query = "select 0 as id_cliente,o.mesa_id as nombre_comercial, o.*,p.id as fk_id_producto,p.nombre, p.precio_venta as precio,p.codigo,p.nombre as descripcion_d, i.porciento as impuesto, (p.precio_venta * i.porciento) / 100 as monto_iva, precio_venta as monto from orden_producto_restaurant o, producto p, impuesto i where p.impuesto = i.id_impuesto and o.zona = $zona and estado in(2,3) and o.order_producto = p.id GROUP BY mesa_id";
        $query = $this->connection->query($str_query);
        return $query->result_array();
    }

    public function getAllOrdenes($almacen=0){
         $and="";
        if($almacen!=0){
            $and="AND o.almacen=".$almacen;
        }
        $str_query = "select 0 as id_cliente,o.mesa_id as nombre_comercial,o.created_at,z.nombre_seccion AS zona_mesa,o.mesa_id, SUM(o.cantidad) as cantidad,o.zona, p.id as fk_id_producto,p.nombre, p.precio_venta as precio,p.codigo,p.nombre as descripcion_d, m.nombre_mesa, i.porciento as impuesto, (p.precio_venta * i.porciento) / 100 as monto_iva, SUM(precio_venta*o.cantidad) as monto, o.almacen from orden_producto_restaurant o, producto p, impuesto i,mesas_secciones m, secciones_almacen z  where p.impuesto = i.id_impuesto and estado in(2,3,4) and o.order_producto = p.id AND m.id = o.mesa_id AND z.id = o.zona $and  GROUP BY zona,mesa_id";               
        $query = $this->connection->query($str_query);
        $ordenes = array();
        $precioAdiciones=0;
        foreach($query->result_array() as $value){
            $orden = $this->ordenes->getDataOrdenByMesa($value["zona"],$value["mesa_id"],$value["almacen"]);  
                  
            foreach ($orden as $key1 => $value1) {
                if(!empty($value1->order_adiciones)){
                     $adiciones = json_decode($value1->order_adiciones);
                     foreach($adiciones as $adicion){
                        $data_adicion = $this->productos->getAdicionByid($value1->order_producto,$adicion);
                        if(!empty($data_adicion)) { 
                            $precioAdiciones += (($data_adicion[0]['precio']*$data_adicion[0]['cantidad'])*$value1->cantidad);
                        }
                     }                    
                }               
            }           
            $value["monto"] = number_format($value["monto"] + $precioAdiciones);
            $ordenes[] = $value;
        }
        return $ordenes;
    }

    public function getAllOrdenes2($almacen=0){
         $and="";
        if($almacen!=0){
            $and="AND o.almacen=".$almacen;
        }
        $str_query = "SELECT 0 AS id_cliente,o.mesa_id AS nombre_comercial,o.created_at,
                        z.nombre_seccion AS zona_mesa,
                        o.mesa_id, 
                        (o.cantidad) AS cantidad,
                        o.zona, 
                        p.id AS fk_id_producto,
                        p.nombre, 
                        p.precio_venta AS precio,
                        p.codigo,
                        p.nombre AS descripcion_d, 
                        m.nombre_mesa, 
                        (precio_venta*o.cantidad) AS monto, 
                        o.almacen 
                        FROM orden_producto_restaurant o, 
                        producto p, 
                        mesas_secciones m, 
                        secciones_almacen z  
                        WHERE
                        estado IN(2,3,4) 
                        AND o.order_producto = p.id 
                        AND m.id = o.mesa_id 
                        AND z.id = o.zona 
                        AND z.id != -1
                        $and  GROUP BY zona,mesa_id";               
        $query = $this->connection->query($str_query)->result_array();
      /*  $ordenes = array();
        $precioAdiciones=0;
        
        foreach($query->result_array() as $value){

            $orden = $this->ordenes->getDataOrdenByMesa($value["zona"],$value["mesa_id"],$value["almacen"]);  
            echo"<br>".$this->connection->last_query()."<br><br>";
            foreach ($orden as $key1 => $value1) {
                if(!empty($value1->order_adiciones)){
                     $adiciones = json_decode($value1->order_adiciones);
                     foreach($adiciones as $adicion){
                        $data_adicion = $this->productos->getAdicionByid($value1->order_producto,$adicion);
                        $precioAdiciones += (($data_adicion[0]['precio']*$data_adicion[0]['cantidad'])*$value1->cantidad);
                     }                    
                }               
            }           
            $value["monto"] = number_format($value["monto"] + $precioAdiciones);
            $ordenes[] = $value;
        }
        print_r($ordenes); die();*/
        return $query;
    }

    public function getLatestOrdenByBarra(){
        $query = "SELECT zona+1 as ultima_barra FROM orden_producto_restaurant WHERE mesa_id = -1 ORDER BY zona DESC LIMIT 1";
        $result = $this->connection->query($query);
        if($result->num_rows() > 0){
            return $result->result()[0]->ultima_barra;
        }else{
            return NULL;
        }
        
    }

    public function creartable_orden_producto_restaurant(){
        $sql=" CREATE TABLE IF NOT EXISTS `orden_producto_restaurant` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `order_producto` varchar(250) DEFAULT NULL,
            `order_modificacion` varchar(250) DEFAULT NULL,
            `order_adiciones` varchar(250) DEFAULT NULL,
            `zona` int(11) DEFAULT NULL,
            `mesa_id` int(11) DEFAULT NULL,
            `estado` int(11) DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `update_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
            `cantidad` int(11) DEFAULT NULL,
            `almacen` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=latin1;";

        $this->connection->query($sql);

        $sql = "SHOW COLUMNS FROM orden_producto_restaurant LIKE 'almacen'";
        $existeCampo = $this->connection->query($sql)->result();
        if(count($existeCampo) == 0)
        {
            $sql = "ALTER TABLE `orden_producto_restaurant`   
                ADD COLUMN `almacen` INT(11) UNSIGNED NULL AFTER `cantidad`;
            ";
            $this->connection->query($sql);
        }
    }

    function changeOrdenByMesa($data){
        $data_order = array(
            "zona" => $data["zona_seleccionada"],
            "mesa_id" => $data["mesa_seleccionada"],
        );
        
        $this->connection->where("zona",$data["zona_anterior"]);
        $this->connection->where("mesa_id",$data["mesa_anterior"]);
        $this->connection->update("orden_producto_restaurant",$data_order);
        if($this->connection->affected_rows() > 0){
            return  true;
        }else{
            return false;
        }
    }

    function verify_print_command($zone,$table){
        $this->connection->select("*");
        $this->connection->from("orden_producto_restaurant");
        $this->connection->where("zona",$zone);
        $this->connection->where("mesa_id",$table);
        $this->connection->where("estado",3);
        $result = $this->connection->get();
        if($result->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }


    public function create_table_historico_ordenes(){
        $sql=" CREATE TABLE IF NOT EXISTS `historico_orden_producto_restaurant` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `order_producto` varchar(250) DEFAULT NULL,
            `order_modificacion` varchar(250) DEFAULT NULL,
            `order_adiciones` varchar(250) DEFAULT NULL,
            `zona` int(11) DEFAULT NULL,
            `mesa_id` int(11) DEFAULT NULL,
            `estado` int(11) DEFAULT NULL,
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            `update_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
            `cantidad` int(11) DEFAULT NULL,
            `almacen` int(11) DEFAULT NULL,
            `id_venta` int(11) NOT NULL,
            PRIMARY KEY (`id`)
          ) ENGINE=InnoDB AUTO_INCREMENT=187 DEFAULT CHARSET=latin1;";

        $this->connection->query($sql);
    }

    public function create_table_log_quick_service(){
        $sql=" CREATE TABLE IF NOT EXISTS `log_quick_service` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `user_id` int(6) DEFAULT NULL,
            `username` varchar(50) DEFAULT NULL,
            `email` varchar(50) DEFAULT NULL,
            `database` varchar(100) DEFAULT NULL,
            `request` text,
            `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY `id` (`id`)
          ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";

        $this->connection->query($sql);
    }

    public function save_log_quick_service($data){
        $this->connection->insert("log_quick_service",$data);
    }
}
?>