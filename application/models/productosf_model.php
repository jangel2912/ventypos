<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of productosf_model
 *
 * @author danbuchi
 */
class Productosf_model extends CI_Model
{
	var $connection;
	// Constructor
	public function __construct()
	{
		parent::__construct();		
	}
        
        public function initialize($connection)
        {
            $this->connection = $connection;
        }
	
	public function get_total()
	{
		$query = $this->connection->query("SELECT count(*) as cantidad FROM  productosf s Inner Join impuestos i on s.id_impuesto = i.id_impuesto");
		return $query->row()->cantidad;								
	}
	
	public function get_all($offset)
	{
		
		$query = $this->connection->query("SELECT * FROM productosf s Inner Join impuestos i on s.id_impuesto = i.id_impuesto ORDER BY id_producto DESC limit $offset, 8");
		return $query->result();
	}
        
        public function get_ajax_data(){
            
            $aColumns = array('nombre', 'codigo','descripcion', 'precio_compra', 'precio', 'nombre_impuesto', 'id_producto');
            $sIndexColumn = "id_producto";
            $sTable = "productosf";
            $sLimit = "";
            if ( isset( $_GET['iDisplayStart'] ) && $_GET['iDisplayLength'] != '-1' )
            {
                    $sLimit = "LIMIT ".intval( $_GET['iDisplayStart'] ).", ".
                            intval( $_GET['iDisplayLength'] );
            }
            $sOrder = "";
            if ( isset( $_GET['iSortCol_0'] ) )
            {
                    $sOrder = "ORDER BY  ";
                    for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )
                    {
                            if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )
                            {
                                    $sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".
                                            ($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
                            }
                    }

                    $sOrder = substr_replace( $sOrder, "", -2 );
                    if ( $sOrder == "ORDER BY" )
                    {
                            $sOrder = "";
                    }
            }
            $sWhere = "";
            if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
            {
                    $sWhere = "WHERE (";
                    for ( $i=0 ; $i<count($aColumns) ; $i++ )
                    {
                            if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
                            {
                                    $sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
                            }
                    }
                    $sWhere = substr_replace( $sWhere, "", -3 );
                    $sWhere .= ')';
            }
            /* Individual column filtering */
            for ( $i=0 ; $i<count($aColumns) ; $i++ )
            {
                    if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
                    {
                            if ( $sWhere == "" )
                            {
                                    $sWhere = "WHERE ";
                            }
                            else
                            {
                                    $sWhere .= " AND ";
                            }
                            $sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
                    }
            }
            
            $sQuery = "
		SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
		FROM   $sTable s left Join impuesto i on s.id_impuesto = i.id_impuesto
		$sWhere  
		$sOrder
		$sLimit
            ";
           
            $rResult =  $this->connection->query($sQuery);
            /* Data set length after filtering */
            $sQuery = "
                    SELECT FOUND_ROWS() as cantidad
            ";
             $rResultFilterTotal = $this->connection->query($sQuery);
             //$aResultFilterTotal = $rResultFilterTotal->result_array();
             $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
             
             $sQuery = "
		SELECT COUNT(`".$sIndexColumn."`) as cantidad
		FROM   $sTable
            ";
            $rResultTotal = $this->connection->query($sQuery);
            $iTotal = $rResultTotal->row()->cantidad; 
            
            $output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
            );
            
           
            foreach($rResult->result_array() as $row)
            {
                $data = array();
                for($i = 0; $i<count($aColumns) ; $i++){
                    $data[] = $row[ $aColumns[$i] ];
                }
                $output['aaData'][] = $data;
            }
            return $output; 
           
        }
        
        public function get_by_name($name){
            $query = $this->connection->query("select id_producto from productosf where nombre = '$name'");
            if($query->num_rows() > 0){
                return $query->row()->id_producto;
            }
            
            return "";
        }
	
	public function get_term_orden_compra($q='',$precioOrdenCompra)
	{

        /**
         * Jeisson Rodriguez Dev
         * 04-09-2019
         * 
         * I added filters (where) to the query (AND s.ingredientes = '0' AND s.combo = '0')
        */
            $query = $this->connection->query("SELECT * FROM producto s
                LEFT JOIN impuesto i ON s.impuesto = i.id_impuesto 
                WHERE s.activo='1' AND s.ingredientes = '0' AND s.combo = '0'
                AND CONCAT(nombre,' ',ifNull(codigo, '')) LIKE '%$q%' LIMIT 0,30" );
            if($precioOrdenCompra == 1)
            {
                $query = $this->connection->query("SELECT *,s.precio_venta as precio_compra, s.precio_venta as precio_venta  FROM producto s
                LEFT JOIN impuesto i ON s.impuesto = i.id_impuesto 
                WHERE s.activo='1' AND s.ingredientes = '0' AND s.combo = '0'
                AND CONCAT(nombre,' ',ifNull(codigo, '')) LIKE '%$q%' LIMIT 0,30" );
            }
            
            return $query->result_array();
	}
        
        public function get_term($q='')
	{
	
		$query = $this->connection->query("SELECT * FROM producto s
                LEFT JOIN impuesto i ON s.impuesto = i.id_impuesto
                    WHERE CONCAT(nombre,' ',ifNull(codigo, '')) LIKE '%$q%' LIMIT 0,30" );

		return $query->result_array();
	}
	

    public function filtro_prod_lista_precios($id, $cli){
	 
	 
          if($cli == 1 || $cli == '' || $cli == '0'){
		 
            $query = $this->connection->query("select * from producto where id = '$id'");
		 
		  }			
        else{
		
		 $id_lista_detalles = 0;  $id_lista = 0;
		         $lista_precios = $this->dbConnection->query("SELECT id  FROM lista_precios where grupo_cliente_id = '$cli' ")->result();
                 foreach ($lista_precios as $dat_1) {   $id_lista = $dat_1->id;   }	
                    				  
		      $lista_precios_1 = $this->connection->query("SELECT id FROM  lista_detalle_precios where id_lista_precios = '$id_lista' and id_producto = '$id' ")->result();				              foreach ($lista_precios_1 as $dat_1) {   $id_lista_detalles = $dat_1->id;   }	
			 
		     if($id_lista_detalles != '0' ){       
			  $query = $this->connection->query("SELECT precio as precio_venta FROM  lista_detalle_precios where id = '$id_lista_detalles' ");	
			 }
			 else{ $query = $this->connection->query("select * from producto where id = '$id'");  }
			 $cli=0;
		 }


        if($query->num_rows() > 0){

            return $query->row_array();

        }

        return null;
    }

	
	
	public function get_term_orden($q='')
	{
	
		$query = $this->connection->query("SELECT s.nombre as nombre_producto, s.id as id_produco, codigo, porciento, porciento, descripcion, precio_compra, u.nombre as uni_nombre, u.id as uni_id
FROM producto s
LEFT JOIN impuesto i ON s.impuesto = i.id_impuesto
LEFT JOIN unidades u ON s.unidad_id = u.id
            WHERE s.nombre LIKE '%$q%' LIMIT 0,30"
        );

		return $query->result_array();
	}	
	
	
	public function get_by_id($id = 0)
	{
		$query = $this->connection->query("SELECT * FROM  productosf WHERE id_producto = '".$id."'");
		
		return $query->row_array();								
	}
	
	public function add()
	{	
		
		$array_datos = array(
                    "nombre"        => $this->input->post('nombre'),
                     "codigo"        => $this->input->post('codigo'),
			"descripcion"  	=> $this->input->post('descripcion'),
			"precio"  	=> $this->input->post('precio'),
                        "precio_compra" => $this->input->post('precio_compra'),
                        "id_impuesto"  	=> $this->input->post('id_impuesto')
		);
		
                $this->connection->insert("productosf", $array_datos);
                $array_datos['id_producto'] = $this->connection->insert_id();
                return $array_datos;
	}
	
	public function update()
	{	
		$array_datos = array(
			"nombre"        => $this->input->post('nombre'),
                        "codigo"        => $this->input->post('codigo'),
			"descripcion"  	=> $this->input->post('descripcion'),
			"precio"  	=> $this->input->post('precio'),
                        "precio_compra" => $this->input->post('precio_compra'),
                        "id_impuesto"  	=> $this->input->post('id_impuesto')
		);
				
		$this->connection->where('id_producto', $_POST['id']);
		$this->connection->update("productosf", $array_datos);
	}
	
	public function delete($id)
	{	
		$this->connection->where('id_producto', $id);
		$this->connection->delete("productosf");	
	}
        
        public function excel(){
            $this->connection->select("id_producto, nombre, descripcion, precio, nombre_impuesto, porciento");
            $this->connection->from("productosf");
            $this->connection->join('impuestos', 'impuestos.id_impuesto = productosf.id_impuesto');
            $query = $this->connection->get();
            return $query->result();
        }
        
        public function excel_exist($nombre, $precio){
           
           $this->connection->where("nombre", $nombre);
           $this->connection->where("precio", $precio);
           $this->connection->from("productosf");
           $this->connection->select("*");
            
            $query = $this->connection->get();
            if($query->num_rows() > 0){
                return true;
            }
            else {
                return false;
            }   
        }
        
        public function excel_add($array_datos){
            $query = "INSERT INTO `productosf` (`nombre`, `descripcion`, `precio`, `id_impuesto`) VALUES ('".$array_datos['nombre']."', '".$array_datos['descripcion']."', ".$array_datos['precio'].", ".$array_datos['id_impuesto'].");";
            $this->connection->query($query);
        }
}

?>
