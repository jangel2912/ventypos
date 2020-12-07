<?php

// Proyecto: Sistema Facturacion

// Version: 1.0

// Programador: Jorge Linares

// Framework: Codeigniter

// Clase: Productos



class Roles_model extends CI_Model

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

		$query = $this->connection->query("SELECT count(*) as cantidad FROM  productos s Inner Join impuestos i on s.id_impuesto = i.id_impuesto");

		return $query->row()->cantidad;								

	}

	

        public function get_combo_data()

	{

		$data = array();

		$query = $this->connection->query("SELECT * FROM rol");

                foreach ($query->result() as $value) {

                    $data[$value->id_rol] = $value->nombre_rol;

                }

                return $data;

	}

        

        public function get_combo_data_stock_actual($id){

		$query = $this->connection->query("SELECT * FROM almacen left join stock_actual on almacen.id = stock_actual.almacen_id where stock_actual.producto_id = $id ORDER BY almacen_id DESC");

                return $query->result();

        }

        

	public function get_all($offset)

	{

		

		$query = $this->connection->query("SELECT * FROM almacen");

		return $query->result();

	}

        

        public function get_ajax_data(){

            $aColumns = array('nombre_rol','descripcion', 'id_rol');

            $sIndexColumn = "id_rol";

            $sTable = "rol";

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

		FROM   $sTable s 

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

            $query = $this->connection->query("select id from almacen where nombre = '$name'");

            if($query->num_rows() > 0){

                return $query->row()->id;

            }

            

            return "";

        }

	

	public function get_term($q='')

	{

		

		$query = $this->connection->query("SELECT id_producto as id, nombre, precio, i.nombre_impuesto, i.porciento, descripcion FROM productos s Inner Join impuestos i on s.id_impuesto = i.id_impuesto WHERE nombre LIKE '%$q%' LIMIT 0,30");

		return $query->result_array();

	}

	

	public function get_by_id($id = 0)

	{

		$query = $this->connection->query("SELECT * FROM  rol WHERE id_rol = '".$id."'");

		

		return $query->row_array();								

	}

	

	public function add($data)

	{	

                $this->connection->insert("rol", $data['rol']);

                $id = $this->connection->insert_id();

                

                 /*echo "<pre>";

                    print_r($data);

                echo "</pre>";

                die;



               $productos = $this->connection->get('producto');*/

                $data_rol = array();

                foreach ($data['permisos'] as $permisos) {

                    $data_rol[] = array(

                            'id_permiso' => $permisos,

                            'id_rol' => $id

                        );

                }

                $this->connection->insert_batch('permiso_rol', $data_rol);

	}

	

	public function update($data)

	{	

		$this->connection->where('id_rol', $data['rol']['id_rol']);

		$this->connection->update("rol", $data['rol']);

                

                $this->connection->where('id_rol', $data['rol']['id_rol']);

                $this->connection->delete('permiso_rol');

                

                

                $data_rol = array();

                foreach ($data['permisos'] as $permisos) {

                    $data_rol[] = array(

                            'id_permiso' => $permisos,

                            'id_rol' => $data['rol']['id_rol']

                        );

                }

                $this->connection->insert_batch('permiso_rol', $data_rol);

	}

        

        public function get_permisos_rol($id){

            $this->connection->where('id_rol', $id);

            $this->connection->select('id_permiso');

            $permisos = $this->connection->get('permiso_rol');

            

            $permisos_result = array();

            foreach ($permisos->result() as $value) {

                $permisos_result[] = $value->id_permiso;

            }

            

            return $permisos_result;

        }

	

	public function delete($id)

	{	

		$this->connection->where('id', $id);

		$this->connection->delete("almacen");	

	}

        

        public function excel(){

            $this->connection->select("id_producto, nombre, descripcion, precio, nombre_impuesto, porciento");

            $this->connection->from("productos");

            $this->connection->join('impuestos', 'impuestos.id_impuesto = productos.id_impuesto');

            $query = $this->connection->get();

            return $query->result();

        }

        

        public function excel_exist($nombre, $precio){

           

           $this->connection->where("nombre", $nombre);

           $this->connection->where("precio", $precio);

           $this->connection->from("productos");

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

            $query = "INSERT INTO `productos` (`nombre`, `descripcion`, `precio`, `id_impuesto`) VALUES ('".$array_datos['nombre']."', '".$array_datos['descripcion']."', ".$array_datos['precio'].", ".$array_datos['id_impuesto'].");";

            $this->connection->query($query);

        }
        
        public function eliminarRol($id)
        {
            $usuarios = $this->db->get_where('users',array('db_config_id'=>$this->session->userdata('db_config_id'),'rol_id'=>$id))->result();
            if(count($usuarios) != 0)
            {
                return 0;
            }else
            {
                $this->connection->where('id_rol',$id)->delete('permiso_rol');
                $this->connection->where('id_rol',$id)->delete('rol');
                return 1;
            }
        }

}

?>