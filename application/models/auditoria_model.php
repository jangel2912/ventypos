<?php

class Auditoria_model extends CI_Model {

    var $connection;

    public function __construct() {

        parent::__construct();
        $this->load->model("stock_actual_model","stock");
        $this->stock->initialize($this->dbConnection);
    }

    public function initialize($connection) {
        $this->connection = $connection;
    }

    public function get_auditorias($where){
    	$this->connection->where($where);
    	$this->connection->select('auditoria_inventario.*,almacen.nombre');
        $this->connection->from('auditoria_inventario');
        $this->connection->join('almacen','almacen.id=auditoria_inventario.id_almacen');
        $query = $this->connection->get();
        return $query->result();
    }

    public function update_auditoria($where,$data){
    	$this->connection->where($where);
    	$this->connection->update('auditoria_inventario',$data);
    }

    public function delete_detalle_auditoria($where){
    	$this->connection->where($where);
    	$this->connection->delete('detalle_auditoria');
    }

    public function update_detalle_auditoria($where,$data){
    	$this->connection->where($where);
    	$this->connection->update('detalle_auditoria',$data);	
    }

    public function get_ajax_datatable($id_almacen,$estado=0) {

        $aColumns = array('auditoria_inventario.id','fecha_creacion', 'nombre_auditoria', 'descripcion_auditoria','estado_auditoria', 'almacen.nombre','archivo_fisico','fecha_anulacion','motivo','u.username');

        $sIndexColumn = "auditoria_inventario.id";

        $sTable = "auditoria_inventario";

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

        $Where = "";

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $Where = "WHERE (";

            for ($i = 0; $i < count($aColumns); $i++) {

                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {

                    $Where .=  $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $Where = substr_replace($Where, "", -3);

            $Where .= ')';
        }

        $sWhere="";
        $sWhereT="";
        if($Where == ""){
        	$sWhere =" WHERE "; 
        }else{
        	$sWhere.=" AND ";
        }
        if($sWhereT == ""){
        	$sWhereT =" WHERE "; 
        }else{
        	$sWhereT.=" AND ";
        }
        $inneruser="";
        if($estado!=-1){   
            $inneruser=" LEFT JOIN vendty2.users u ON auditoria_inventario.creado_por = u.id ";
            if($id_almacen == 0){                
                $sWhere .=" auditoria_inventario.estado_auditoria != 'Eliminada'";
                $sWhereT .=" auditoria_inventario.estado_auditoria != 'Eliminada'";
            }else{
                $sWhere .=" auditoria_inventario.estado_auditoria != 'Eliminada' AND auditoria_inventario.id_almacen =$id_almacen ";
                $sWhereT .=" auditoria_inventario.estado_auditoria != 'Eliminada' AND auditoria_inventario.id_almacen =$id_almacen ";
                
            }
        }else{   
            $inneruser=" LEFT JOIN vendty2.users u ON auditoria_inventario.id_user_anulacion = u.id ";                    
            if($id_almacen == 0){
                $sWhere .=" auditoria_inventario.estado_auditoria = 'Eliminada'";
                $sWhereT .=" auditoria_inventario.estado_auditoria = 'Eliminada'";
            }else{
                $sWhere .=" auditoria_inventario.estado_auditoria = 'Eliminada' AND auditoria_inventario.id_almacen =$id_almacen ";
                $sWhereT .=" auditoria_inventario.estado_auditoria = 'Eliminada' AND auditoria_inventario.id_almacen =$id_almacen ";
            }
        }
        
        //se modificó la consulta para que trajera los valores correctos
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS " . str_replace(" , ", " ", implode(",", $aColumns)) . "
        FROM   $sTable  
        INNER JOIN almacen  on (auditoria_inventario.id_almacen=almacen.id)
        $inneruser
        $Where    
        $sWhere    
        $sOrder
        $sLimit";
        //echo $sQuery; die();           
        $rResult = $this->connection->query($sQuery);        
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() as cantidad ";
        $rResultFilterTotal = $this->connection->query($sQuery);
        //$aResultFilterTotal = $rResultFilterTotal->result_array();
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        $sQuery = "SELECT COUNT(" . $sIndexColumn . ") as cantidad FROM $sTable $sWhereT";   
        //echo $sQuery; die();
        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;
        $output = array(
            "sEcho" => intval($_GET['sEcho']),
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        foreach ($rResult->result() as $row) {
          //  print_r($rResult); die();
            if($estado!=-1){            
                $data = array(
                    $row->id,
                    $row->fecha_creacion,
                    $row->nombre,
                    $row->nombre_auditoria,
                    $row->descripcion_auditoria,
                    $row->estado_auditoria,
                    $row->archivo_fisico
                );
            }else{
                $data = array(
                    $row->id,                    
                    $row->fecha_creacion,
                    $row->nombre,
                    $row->nombre_auditoria,
                    $row->descripcion_auditoria,
                    $row->estado_auditoria,
                    $row->archivo_fisico,
                    $row->fecha_anulacion,
                    $row->motivo,
                    $row->username
                );
            }
            $output['aaData'][] = $data;            
        }

        return $output;
    }

    public function insertar_auditoria($data){
    	$this->connection->insert('auditoria_inventario',$data);
    	return $this->connection->insert_id();
    }

    public function insertar_detalle_auditoria($data_detalle){
    	if(!empty($data_detalle)){
    		$this->connection->insert_batch('detalle_auditoria',$data_detalle);
    		return true;	
    	}
    	return false;
    	
    }

    public function get_detalle_auditoria($where){
    	
    	$this->connection->select('*');
    	$this->connection->from('detalle_auditoria');
    	$this->connection->join('producto','producto.id=detalle_auditoria.producto_id');
    	$this->connection->where($where);
    	$query = $this->connection->get();
    	return $query->result();

    }



    public function check_existe_tablas(){

    	$sql ="CREATE TABLE IF NOT EXISTS `auditoria_inventario` (
				  `id` INT NOT NULL AUTO_INCREMENT,
				  `fecha_creacion` DATETIME NULL,
				  `creado_por` INT NULL,
				  `fecha_modificacion` DATETIME NULL,
				  `modificado_por` INT NULL,
				  `nombre_auditoria` VARCHAR(100) NULL COMMENT 'el nombre es como un titulo, para que el usuario sepa',
				  `descripcion_auditoria` VARCHAR(300) NULL COMMENT 'una descripcion adicional',
				  `estado_auditoria` VARCHAR(15) NULL COMMENT 'borrador cuando se crea, cerrado cuando se confirma y ya no permite modificar',
				  `id_almacen` INT(11) NOT NULL COMMENT 'referencia la tabla almacen, el almacen al que pertence esta auditoria',
				  `archivo_fisico` VARCHAR(1000) NULL COMMENT 'si existe un archivo de soporte se puede cargar y la ruta se guarda en este campo',
				  PRIMARY KEY (`id`),
				  INDEX `fk_auditoria_inventario_almacen_idx` (`id_almacen` ASC),
				  CONSTRAINT `fk_auditoria_inventario_almacen`
				    FOREIGN KEY (`id_almacen`)
				    REFERENCES `almacen` (`id`)
				    ON DELETE NO ACTION
				    ON UPDATE NO ACTION)
				ENGINE = InnoDB";
        $this->connection->query($sql);

        $sql= "CREATE TABLE IF NOT EXISTS `detalle_auditoria` (
				  `id` INT NOT NULL AUTO_INCREMENT,
				  `fecha_creacion` DATETIME NULL,
				  `creado_por` INT NULL,
				  `fecha_modificacion` DATETIME NULL,
				  `modificado_por` INT NULL,
				  `id_auditoria` INT NOT NULL,
				  `producto_id` INT(11) NOT NULL,
				  `cantidad_contada` DOUBLE NULL COMMENT 'la cantidad del producto que se conto en la auditoria, el fisico',
				  `cantidad_sistema` DOUBLE NULL COMMENT 'la cantidad que tiene el sistema en el momento de iniciar el arqueo o de contar el primer producto',
				  `observacion_adicional` VARCHAR(1000) NULL COMMENT 'alguna ainformacion que puede digitar el usuario sobre el inventario de este producto',
				  PRIMARY KEY (`id`),
				  INDEX `fk_detalle_auditoria_auditoria_inventario1_idx` (`id_auditoria` ASC),
				  INDEX `fk_detalle_auditoria_producto1_idx` (`producto_id` ASC),
				  CONSTRAINT `fk_detalle_auditoria_auditoria_inventario1`
				    FOREIGN KEY (`id_auditoria`)
				    REFERENCES `auditoria_inventario` (`id`)
				    ON DELETE NO ACTION
				    ON UPDATE NO ACTION,
				  CONSTRAINT `fk_detalle_auditoria_producto1`
				    FOREIGN KEY (`producto_id`)
				    REFERENCES `producto` (`id`)
				    ON DELETE CASCADE
				    ON UPDATE CASCADE)
				ENGINE = InnoDB";
		$this->connection->query($sql);
    }

    public function ajustar_auditoria($data,$id_almacen){
      
       $rows_affected = 0;
        foreach($data as $value){
            $data_ajuste = array(
                "unidades" => $value["cantidad_contada"]
            );  
          
            if(is_numeric($value["cantidad_contada"])){      
                //busco id en stock actual            
                $stock_colunmas=$this->stock->update_array ($id_almacen,$value["producto_id"],$data_ajuste);
                $stock_actual_des = $this->stock->get_by_prod_almac($id_almacen,$value["producto_id"]); 
                
                if($stock_actual_des['unidades']==$value["cantidad_contada"]){
                    $rows_affected=1;
                }                
            }
        }               
        
        return $rows_affected;
        
    }

    public function camposanulaciones(){
        //ingresar columna
            $sql = "SHOW COLUMNS FROM auditoria_inventario LIKE 'motivo'";
            $existe = $this->connection->query($sql);                
            if($existe->num_rows == 0)
            {
                $sql ="ALTER TABLE `auditoria_inventario` 
                        ADD COLUMN `motivo` TEXT DEFAULT NULL, 
                        ADD COLUMN `id_user_anulacion` INT(11) NULL,
                        ADD COLUMN `fecha_anulacion` DATETIME DEFAULT NULL;";                            
                $this->connection->query($sql);
                
            }
    }

    public function afectar_inventario_si_no($id,$limit,$registros)
	{           
        if(!empty($limit)){
            $limit="limit ".$limit;
        }else{
            $limit="";
        }
       
        $sql = "SELECT * FROM movimiento_inventario 
                WHERE codigo_factura=$id 
                AND (tipo_movimiento='entrada_auditoria' OR tipo_movimiento='salida_auditoria') $limit";        
        $query = $this->connection->query($sql)->result();
        
        if(!empty($query)) {            
            if(!empty($registros)){
                 return $query;
            }else{
                return 1;
            }
            
        }else{
            return 0;
        }             
                
    }

}
?>