<?php

class Mesas_secciones_model extends CI_Model{

    var $connection;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function initialize($connection)
    {
        $this->connection = $connection;
    } 

    public function add_campo_comensales()
    {
        $sql = "SHOW COLUMNS FROM mesas_secciones LIKE 'comensales'";
        $field = $this->connection->query($sql)->result();

        if (count($field) <= 0) {
            $sql = "ALTER TABLE `mesas_secciones` ADD `comensales` INT UNSIGNED NULL DEFAULT 1 AFTER `consecutivo_orden_restaurante`;";
            $this->connection->query($sql);
        }
    }

    public function agregar_mesa($data){

      if($this->validar_mesa($data["codigo_mesa"],$data["id_seccion"])){
        return  $this->connection->insert('mesas_secciones',$data);
      }  else{
        return null;
      }
      
    }

    public function validar_mesa($codigo_mesa,$id_seccion){
        
        $this->connection->select("*");
        $this->connection->from("mesas_secciones");
        $this->connection->where("codigo_mesa",$codigo_mesa);
        $this->connection->where("id_seccion",$id_seccion);
        $result = $this->connection->get();

        if($result->num_rows() > 0){
            return false;
        }else{
            return true;
        }
    }

    public function  get_mesa_secciones($where,$estacion=0){
        if(!empty($estacion)){
             $this->connection->where($where);
             $this->connection->where($estacion);
        }else{
             $this->connection->where($where);
        }
        
        $this->connection->select('*');
        $this->connection->from('mesas_secciones');       
        $this->connection->order_by('codigo_mesa');
        $query = $this->connection->get();
    
        $mesas = array();
        if($query->num_rows() > 0){
            $mesas = $query->result();
            $sortArray = array(); 
            foreach($mesas as $mesa){ 
                foreach($mesa as $key=>$value){ 
                    if(!isset($sortArray[$key])){ 
                        $sortArray[$key] = array(); 
                    } 
                    $sortArray[$key][] = $value; 
                } 
            } 

            $orderby = "codigo_mesa";
            array_multisort($sortArray[$orderby],SORT_NUMERIC,$mesas,SORT_DESC); 
        }

        return $mesas;
        
    }

    public function get_una_mesa_by($where){
        $this->connection->where($where);
        $this->connection->select('*');
        $query = $this->connection->get('mesas_secciones');        
        return $query->row();
    }

    public function actualizar_mesa($data,$where){
        $this->connection->where($where);
        return $this->connection->update('mesas_secciones',$data);
    }


    public function delete_mesa($where){
        $this->connection->where($where);
       $exito = $this->connection->delete("mesas_secciones"); 
       if($exito){
            return "Se ha eliminado correctamente";
       }else{
            return "No se ha eliminado la mesa, intente de nuevo mas tarde";
       }
    }


    public function get_ajax_data() {

        $aColumns = array('nombre','nombre_seccion', 'nombre_mesa', 'codigo_mesa','s.activo', 's.id');

        $sIndexColumn = "s.id";

        $sTable = "mesas_secciones";

        $sLimit = "";

        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {

            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " .
                    intval($_GET['iDisplayLength']);
        }

        $sOrder = "";

        if (isset($_GET['iSortCol_0'])) {

            $sOrder = "ORDER BY  ";

            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {

                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {

                    $sOrder .=  $aColumns[intval($_GET['iSortCol_' . $i])] ." ".
                            ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }



            $sOrder = substr_replace($sOrder, "", -2);

            if ($sOrder == "ORDER BY") {

                $sOrder = "";
            }
        }

        $sWhere = "";

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $sWhere = "WHERE (";

            for ($i = 0; $i < count($aColumns); $i++) {

                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {

                    $sWhere .=  $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $sWhere = substr_replace($sWhere, "", -3);

            $sWhere .= ')';
        }

        /* Individual column filtering */

        for ($i = 0; $i < count($aColumns); $i++) {

            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {

                if ($sWhere == "") {

                    $sWhere = "WHERE ";
                } else {

                    $sWhere .= " AND ";
                }

                $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }

        if($sWhere == ""){
            $sWhere .= 'WHERE b.id != -1';
        }else{
            $sWhere .= ' AND b.id != -1';
        }
       

        $sQuery = "

        SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(",", $aColumns)) . "

        FROM   $sTable s 
        INNER JOIN secciones_almacen as b on (s.id_seccion=b.id)
        INNER JOIN almacen as c on (b.id_almacen=c.id)
        $sWhere  
        
        $sOrder

        $sLimit

            ";

       // echo $sQuery;    

        $rResult = $this->connection->query($sQuery);

        /* Data set length after filtering */

        $sQuery = "SELECT FOUND_ROWS() as cantidad ";

        $rResultFilterTotal = $this->connection->query($sQuery);

        //$aResultFilterTotal = $rResultFilterTotal->result_array();

        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;

        $sQuery = "SELECT COUNT(" . $sIndexColumn . ") as cantidad FROM   $sTable as s";     

        $rResultTotal = $this->connection->query($sQuery);

        $iTotal = $rResultTotal->row()->cantidad;

        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult->result() as $row) {

            $data = array(
                        $row->nombre,
                        $row->nombre_seccion,
                        $row->codigo_mesa,
                        $row->nombre_mesa,
                        $row->activo,
                        $row->id
                    );

            $output['aaData'][] = $data;
        }

        return $output;
    }

    public function get_secciones_mesas_by_id_mesa($id_mesa){
        $this->connection->select("m.nombre_mesa, s.nombre_seccion,m.consecutivo_orden_restaurante,m.comensales");
        $this->connection->from("mesas_secciones m");
        $this->connection->join("secciones_almacen s","m.id_seccion = s.id");
        $this->connection->where("m.id",$id_mesa);
        $this->connection->limit(1);
        $result = $this->connection->get();
        if($result->num_rows() > 0){
            return $result->row();
        }else{
            return NULL;
        }
    }

    public function agregarnota($nota=null,$where=null)
    {
        $sql = "SHOW COLUMNS FROM mesas_secciones LIKE 'nota_comanda'";
        $existeCampo = $this->connection->query($sql)->result();
        if(count($existeCampo) == 0)
        {
            $sql = "ALTER TABLE `mesas_secciones`   
                ADD COLUMN `nota_comanda` TEXT NULL COMMENT 'nota comanda mientras este ocupada la mesa',
                ADD COLUMN `vendedor_estacion` INT(11) COMMENT 'para saber que vendedor la tiene ocupada mientras esta en la estacion pedido',
                ADD COLUMN consecutivo_orden_restaurante int NOT NULL DEFAULT 0 COMMENT 'Campo para saber el numero de orden de pedidos para las mesas';                 
            ";
            $this->connection->query($sql);
        }else{
            $sql = "SHOW COLUMNS FROM mesas_secciones LIKE 'vendedor_estacion'";
            $existeCampo = $this->connection->query($sql)->result();
            if(count($existeCampo) == 0)
            {
                $sql = "ALTER TABLE `mesas_secciones`   
                    ADD COLUMN `vendedor_estacion` INT(11) COMMENT 'para saber que vendedor la tiene ocupada mientras esta en la estacion pedido',
                    ADD COLUMN consecutivo_orden_restaurante int NOT NULL DEFAULT 0 COMMENT 'Campo para saber el numero de orden de pedidos para las mesas';                 
                ";
                $this->connection->query($sql);
            }
        }
        
        if(!empty($where)){
            $this->connection->where($where);
            $this->connection->update("mesas_secciones", array('nota_comanda'=> $nota));
            return true;
        }
        return false;
       
    }

    public function get_vendedor_venta_estacion($where){
        $this->connection->where($where);
        $this->connection->select('vendedor.*');
        $this->connection->from('mesas_secciones');
        $this->connection->join('vendedor', 'vendedor.id = mesas_secciones.vendedor_estacion');
        $query = $this->connection->get()->row();
      
        return $query;
    }

    public function get_mesas_abiertas(){

        $user_id = $this->session->userdata('user_id');
        $this->connection->select('almacen_id');
        $this->connection->from('usuario_almacen');
        $this->connection->where('usuario_id',$user_id);

        $result = $this->connection->get();
        if($result->num_rows() > 0){
            
            $almacen = $result->result()[0]->almacen_id;
            $this->connection->select('*');
            $this->connection->from('orden_producto_restaurant o');
            $this->connection->where('o.almacen',$almacen);
            $this->connection->where('o.zona >','0');
            $result = $this->connection->get();
            if($result->num_rows() > 0){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }


    public function verify_seccion_quick_service(){
        $this->connection->select("*");
        $this->connection->from("secciones_almacen sa");
        $this->connection->where("sa.id",-1);
        $result = $this->connection->get();
        if($result->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function verify_mesa_quick_service($mesa){
        $this->connection->select("*");
        $this->connection->from("mesas_secciones ms");
        $this->connection->where("ms.id",$mesa);
        $result = $this->connection->get();
        if($result->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function create_seccion_quick_service($zona,$mesa){
        
        if(!$this->verify_seccion_quick_service()){
            $query_almacen = $this->connection->get_where('usuario_almacen', array('usuario_id' => $this->session->userdata('user_id')));
            $almacen = $query_almacen->result()[0]->almacen_id;
           

            $data = array(
                'id' => -1,
                'fecha_creacion' => date('y-m-d h:i:s'),
                'creado_por' => $this->session->userdata('user_id'),
                'activo' => 1,
                'id_almacen' => $almacen,
                'codigo_seccion' => 'qs',
                'nombre_seccion' => 'quick service',
                'descripcion_seccion' => 'quick service'
            );
            $this->connection->insert("secciones_almacen",$data);
        }
        
        if(!$this->verify_mesa_quick_service($mesa)){
            $data = array(
                'id' => $mesa,
                'fecha_creacion' => date('y-m-d h:i:s'),
                'creado_por' => $this->session->userdata('user_id'),
                'activo' => 1,
                'id_seccion' => -1,
                'codigo_mesa' => -1,
                'nombre_mesa' => 'quick service',
                'comensales' => NULL
            );
            $this->connection->insert("mesas_secciones",$data);
        }
    }

    public function get_order_consecutive($store_id){
        $this->connection->select("consecutivo_orden_restaurante");
        $this->connection->from("almacen a");
        $this->connection->where("a.id",$store_id);
        $this->connection->limit(1);
        $result = $this->connection->get();
        return $result->result()[0]->consecutivo_orden_restaurante;
    }
}