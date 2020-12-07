<?php

// Proyecto: Sistema Facturacion

// Version: 1.0

// Programador: Jorge Linares

// Framework: Codeigniter

// Clase: Productos



class Unidades_model extends CI_Model

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

	

    public function get_combo_data(){

		$data = array();

		$query = $this->connection->query("SELECT * FROM unidades ORDER BY id ASC");

        return $query->result();

	}


        public function get_combo_data_unidades()

	{

		$data = array();

		$query = $this->connection->query("SELECT * FROM unidades ORDER BY id ASC");

                foreach ($query->result() as $value) {

                    $data[$value->id] = $value->nombre;

                }

                return $data;

	}	

        public function get_combo_data_factura_unidades()

	{

		$data = array();

		$query = $this->connection->query("SELECT * FROM unidades ORDER BY id ASC");

                foreach ($query->result() as $value) {

                    $data[$value->nombre] = $value->nombre;

                }

                return $data;

	}	


        public function get_unidades_id($id=0){

		$data = array();

		$query = $this->connection->query("SELECT * FROM unidades where id='".$id."'");

        return $query->result();
        }
        
        public function get_unidades($where){
                $this->connection->select('*');
                $this->connection->from('unidades');
                $this->connection->where($where);
                $this->connection->limit("1");
                $result = $this->connection->get();
                if($result->num_rows() > 0){
                        return $result->row();
                }else{
                        return NULL;
                }
        }


    public function get_limit($offset){

        $data = array();

        $query = $this->connection->query("SELECT activo,codigo,id,imagen, SUBSTRING( nombre,1,7 ) as nombre ,padre FROM categoria ORDER BY id ASC limit $offset , 6");

        return $query->result();

    }

        

	public function get_all($offset){

		$query = $this->connection->query("SELECT * FROM categorias s Inner Join impuestos i on s.id_impuesto = i.id_impuesto ORDER BY id_producto DESC limit $offset, 8");

		return $query->result();

	}

        

        public function get_ajax_data(){

            $aColumns = array('imagen','codigo', 'nombre', 'activo', 'id');

            $sIndexColumn = "id";

            $sTable = "categoria";

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

            $query = $this->connection->query("select id from categoria where nombre = '$name'");

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

		$query = $this->connection->query("SELECT * FROM  categoria WHERE id = '".$id."'");

		

		return $query->row_array();								

	}

	

	public function add($data)

	{	

		

                $this->connection->insert("categoria", $data);

                //return $array_datos;

	}

	

	public function update($data)

	{	

		/*$array_datos = array(

			"nombre"        => $this->input->post('nombre'),

                        "codigo"        => $this->input->post('codigo'),

			"descripcion"  	=> $this->input->post('descripcion'),

			"precio"  	=> $this->input->post('precio'),

                        "precio_compra" => $this->input->post('precio_compra'),

                        "id_impuesto"  	=> $this->input->post('id_impuesto')

		);*/

				

		$this->connection->where('id', $data['id']);

		$this->connection->update("categoria", $data);

	}

	

	public function delete($id)

	{	

		$this->connection->where('id', $id);

		$this->connection->delete("categoria");	

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

        public function insertar_unidad($data)
	{	
                $this->connection->insert("unidades", $data);
                $id = $this->connection->insert_id();
                return $id;
        }
        
        public function eliminar_unidad($where)
	{	
                $this->connection->where($where);
                $this->connection->delete("unidades");
        }
        
        public function validar_unidad($where)
	{	
                $this->connection->select("*");
                $this->connection->where($where);
                $this->connection->from("unidades");

                $query = $this->connection->get();

                if ($query->num_rows() == 0) {
                        return 0;
                } else {
                        return 1;
                }
        }
        
        public function puedo_eliminar_unidad($where)
	{	
                $this->connection->select('*');
                $this->connection->from('unidades u');
                $this->connection->join('producto p', 'u.id = p.unidad_id', 'inner');
                $this->connection->where($where);
                $query = $this->connection->get();
               
                if ($query->num_rows() == 0) {
                        return 0;
                } else {
                        return 1;
                }
                
	}
}

?>