<?php

class Secciones_almacen_model extends CI_Model{

    var $connection;
    
    public function __construct()
    {
        parent::__construct();
    }

    public function initialize($connection)
    {
        $this->connection = $connection;
    } 


    public function agregar_seccion($data){
    	return	$this->connection->insert('secciones_almacen',$data);
    }

    public function  get_secciones_almacen($where){
        
        $this->connection->where($where);
        $this->connection->select('a.*,b.nombre');
        $this->connection->from('secciones_almacen a');
        $this->connection->join('almacen b','a.id_almacen=b.id');
        $query = $this->connection->get();
        return $query->result();
    }

    public function get_una_seccion_by($where){
        $this->connection->where($where);
        $this->connection->select('*');
        $query = $this->connection->get('secciones_almacen');
        return $query->row();
    }

    public function actualizar_seccion($data,$where){
        $this->connection->where($where);
        return $this->connection->update('secciones_almacen',$data);
    }


    public function delete_seccion($where){
        $this->connection->where($where);
       $exito = $this->connection->delete("secciones_almacen"); 
       if($exito){
            return "Se ha eliminado correctamente";
       }else{
            return "No se ha eliminado la seccion, intente de nuevo mas tarde";
       }
    }

    public function check_existe_tabla_secciones(){
        $instruccion_sql = "CREATE TABLE IF NOT EXISTS `secciones_almacen`(  
              `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'incremental de la tabla y clave primaria',
              `fecha_creacion` DATETIME COMMENT 'fecha en que se crea el registro',
              `creado_por` INT(11) COMMENT 'el usuario que creo que el registro',
              `fecha_modificacion` DATETIME COMMENT 'fecha de ultima actualizacion del registro',
              `modificado_por` INT(11) COMMENT 'el usuario que realizo la ultima modificacion',
              `activo` TINYINT DEFAULT 1 COMMENT 'activo 1 desactivo 0',
              `id_almacen` INT(11) COMMENT 'referencia la tabla almacenes, el almacen al que pertenece esta seccion',
              `codigo_seccion` VARCHAR(10) COMMENT 'codigo identificador para informes',
              `nombre_seccion` VARCHAR(50) COMMENT 'nombre de la seccion o piso',
              `descripcion_seccion` VARCHAR(500) COMMENT 'descripcion de la seccion de mesas',
              PRIMARY KEY (`id`),
              CONSTRAINT `fk_almacen_seccion` FOREIGN KEY (`id_almacen`) REFERENCES `almacen`(`id`) ON UPDATE CASCADE ON DELETE CASCADE
            )
            COMMENT='para las mesas contiene las secciones o pisos donde hay mesa';";

        $this->connection->query($instruccion_sql);    

        $instruccion_sql = "CREATE TABLE IF NOT EXISTS `mesas_secciones`(  
              `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'identificador de la tabla autoincremental',
              `fecha_creacion` DATETIME COMMENT 'fecha en que se crea el registro',
              `creado_por` INT(11) COMMENT 'usuario que creo el registro',
              `fecha_modificacion` DATETIME COMMENT 'ultima fecha en que se modifico el registro',
              `modificado_por` INT(11) COMMENT 'identificador del usuario que realizo la ultima modificacion',
              `activo` TINYINT DEFAULT 1 COMMENT 'activo =1 desactivado =0',
              `id_seccion` INT(11) COMMENT 'referencia la tabla secciones_almacen a que seccion pertenece esta mesa',
              `codigo_mesa` VARCHAR(10) COMMENT 'codigo de la mesa',
              `nombre_mesa` VARCHAR(100) COMMENT 'nombre de la mesa, el que vera el usuario',
              PRIMARY KEY (`id`),
              CONSTRAINT `fk_seccion_mesa` FOREIGN KEY (`id_seccion`) REFERENCES `secciones_almacen`(`id`) ON UPDATE CASCADE ON DELETE CASCADE
            )
            COMMENT='listado de mesas que se tiene en las diferentes secciones';";

        $this->connection->query($instruccion_sql);    

        $instruccion_sql = "SHOW COLUMNS FROM factura_espera LIKE 'id_mesa'";
        $result = $this->connection->query($instruccion_sql);
        if(!($result->num_rows() > 0)){
            $instruccion_sql = "ALTER TABLE `factura_espera`   
                ADD COLUMN `id_mesa` INT(11) NULL  COMMENT 'referencia la tabla mesas_secciones cual es la mesa a la que pertenece esta venta en espera' AFTER `sobrecosto`;";

            $this->connection->query($instruccion_sql);    
        }

        
    }

    public function get_ajax_data() {

        $aColumns = array('codigo_seccion', 'b.nombre', 'nombre_seccion', 'descripcion_seccion','s.activo', 's.id');

        $sIndexColumn = "s.id";

        $sTable = "secciones_almacen";

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
            $sWhere .= 'WHERE s.id != -1';
        }else{
            $sWhere .= ' AND s.id != -1';
        }


        $sQuery = "

        SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(",", $aColumns)) . "

        FROM   $sTable s 
        INNER JOIN almacen as b on (s.id_almacen=b.id)
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
                        $row->codigo_seccion,
                        $row->nombre,
                        $row->nombre_seccion,
                        $row->descripcion_seccion,
                        $row->activo,
                        $row->id
                    );

            $output['aaData'][] = $data;
        }

        return $output;
    }

    function get_mesas_secciones(){
        $this->connection->select("*");
        $this->connection->from("mesas_secciones");
        $this->connection->order_by('codigo_mesa');
        $result = $this->connection->get();
        return $result->result();
    }

    function get_mesas_secciones_dashboard(){
        $this->connection->select("*");
        $this->connection->from("mesas_secciones");
        //Jeisson Todriguez
        //Ignorar todas las mesas que no sean quick services
        $this->connection->where("id_seccion !=", '-1');
        //fin
        $this->connection->order_by('codigo_mesa');
        $result = $this->connection->get();
        return $result->result();
    }

    function get_mesas_disponibles($id_almacen){
        $query = "SELECT DISTINCT m.id, m.nombre_mesa AS nombre, m.id_seccion AS id_zona, s.nombre_seccion AS nombre_zona FROM mesas_secciones AS m
        LEFT OUTER JOIN orden_producto_restaurant AS o ON o.mesa_id = m.id
        INNER JOIN secciones_almacen AS s ON s.id = m.id_seccion  WHERE m.id_seccion <> '-1' AND o.mesa_id IS NULL AND s.id_almacen = '".$id_almacen."' ORDER BY nombre";
        $result = $this->connection->query($query);
        if($result->num_rows() > 0){
            $data = array();
            $zonas = array();
            foreach($result->result() as $mesa){
                $zonas[$mesa->id_zona] = array(
                    "id" => $mesa->id_zona,      
                    "nombre" => $mesa->nombre_zona                   
                );            
            }
            $data["zonas"] = $zonas;
            $data["mesas"] = $result->result();
            return $data;
        }else{
            return NULL;
        }
    }
        
}