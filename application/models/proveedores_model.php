<?php

// Proyecto: Sistema Facturacion

// Version: 1.0

// Programador: Jorge Linares

// Framework: Codeigniter

// Clase: Proveedores



class Proveedores_model extends CI_Model

{

	

	var $connection;

    

        public function __construct()

        {

            parent::__construct();



        }

         

        public function initialize($connection){

            $this->connection = $connection;

        } 

        

        public function get_combo_data()

	{

		$data = array();

		$query = $this->connection->query("SELECT * FROM proveedores ORDER BY nombre_comercial");

                foreach ($query->result() as $value) {

                    $data[$value->id_proveedor] = $value->nombre_comercial;

                }

                return $data;

	}

	

	public function get_total()

	{

		$query = $this->connection->query("SELECT count(*) as cantidad  FROM  proveedores");

		return $query->row()->cantidad;								

	}

        

        public function get_ajax_data(){

            $aColumns = array('nombre_comercial', 'razon_social', 'nif_cif', 'contacto', 'email', 'id_proveedor');

            $sIndexColumn = "id_proveedor";

            $sTable = "proveedores";

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

		FROM   $sTable 

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

	

	public function get_all($offset)

	{

		

		$query = $this->connection->query("SELECT * FROM proveedores c ORDER BY id_proveedor DESC limit $offset, 8");

		return $query->result();

	}

	

	public function get_term($q='')

	{

		

		$query = $this->connection->query("SELECT c.id_proveedor as id, CONCAT(c.nombre_comercial,' (', ifNull(c.razon_social, ''),')') as value, CONCAT(ifNull(c.nif_cif, ''), ', ', ifNull(direccion, ''), ', ', ifNull(poblacion, ''), ', ', ifNull(pais, ''), ',', ifNull(provincia, ''),', ',ifNull(cp, '')) as descripcion 

											FROM proveedores c

											WHERE CONCAT(nombre_comercial,' ',ifNull(c.razon_social, ''),' ',ifNull(c.poblacion, '')) LIKE '%$q%' ORDER BY nombre_comercial LIMIT 0, 30");

		return $query->result_array();

	}

	

	public function get_by_id($id = 0)

	{

		$query = $this->connection->query("SELECT * FROM  proveedores

										WHERE id_proveedor = '".$id."'");

		

		return $query->row_array();								

	}

        public function get_by_name($name){
                $this->connection->select("id_proveedor");
                $this->connection->from("proveedores");
                $this->connection->where("nombre_comercial",$name);
                $this->connection->limit(1);
                $result = $this->connection->get();
                if($result->num_rows() > 0):
                        return $result->result()[0]->id_proveedor;
                else:
                        return null;
                endif;
        }
	

	public function add()

	{	

        $array_datos = array(

			"pais" 	 => $this->input->post('pais'),

            "provincia"  => $this->input->post('provincia'),

			"nombre_comercial"	=> $this->input->post('nombre_comercial'),

			"razon_social" 	=> $this->input->post('razon_social'),

			"nif_cif"  	=> $this->input->post('nif_cif'),

			"contacto"  			=> $this->input->post('contacto'),

			"pagina_web"  			=> $this->input->post('pagina_web'),

			"email"  				=> $this->input->post('email'),

			"poblacion"  			=> $this->input->post('poblacion'),

			"direccion"  			=> $this->input->post('direccion'),

			"cp"  					=> $this->input->post('cp'),

			"telefono"  			=> $this->input->post('telefono'),

			"movil"  				=> $this->input->post('movil'),

			"fax"  					=> $this->input->post('fax'),

			"tipo_empresa"  		=> $this->input->post('tipo_empresa'),

			"entidad_bancaria"  	=> $this->input->post('entidad_bancaria'),

			"numero_cuenta"  		=> $this->input->post('numero_cuenta'),

			"observaciones"  		=> $this->input->post('observaciones')

		);

		

		$this->connection->insert("proveedores",$array_datos);

                $array_datos['id_proveedor'] = $this->connection->insert_id();

                return $array_datos;

	}

        

       public function add_light($nombre_comercial, $razon_social, $nif_cif, $email){

            $array_datos = array(

                "nombre_comercial"		=> $nombre_comercial,

                "razon_social"  		=> $razon_social,

                "nif_cif"  				=> $nif_cif,

                "email"  				=> $email,

            );

            $this->connection->insert("proveedores",$array_datos);

            return $this->connection->insert_id();

        }

	

	public function update()

	{	

		$array_datos = array(

			"pais" 	        => $this->input->post('pais'),

                        "provincia" 	        => $this->input->post('provincia'),

			"nombre_comercial"		=> $this->input->post('nombre_comercial'),

			"razon_social"  		=> $this->input->post('razon_social'),

			"nif_cif"  				=> $this->input->post('nif_cif'),

			"contacto"  			=> $this->input->post('contacto'),

			"pagina_web"  			=> $this->input->post('pagina_web'),

			"email"  				=> $this->input->post('email'),

			"poblacion"  			=> $this->input->post('poblacion'),

			"direccion"  			=> $this->input->post('direccion'),

			"cp"  					=> $this->input->post('cp'),

			"telefono"  			=> $this->input->post('telefono'),

			"movil"  				=> $this->input->post('movil'),

			"fax"  					=> $this->input->post('fax'),

			"tipo_empresa"  		=> $this->input->post('tipo_empresa'),

			"entidad_bancaria"  	=> $this->input->post('entidad_bancaria'),

			"numero_cuenta"  		=> $this->input->post('numero_cuenta'),

			"observaciones"  		=> $this->input->post('observaciones')

		);

		

		$this->connection->where('id_proveedor', $this->input->post('id'));

		$this->connection->update("proveedores",$array_datos);

	}

	

	public function delete($id)

	{	

		$this->connection->where('id_proveedor', $id);

		$this->connection->delete("proveedores");	

	}

        

         public function excel(){

            $this->connection->select("*");

            $this->connection->from("proveedores");

            $query = $this->connection->get();

            return $query->result();

        }

        

        public function excel_exist($id_proveedor, $id_impuesto, $fecha, $cantidad=null){

           

           $this->connection->where("id_proveedor", $id_proveedor);

           $this->connection->where("id_impuesto", $id_impuesto);

           $this->connection->where("fecha", $fecha);

           $this->connection->where("cantidad", $cantidad);

           $this->connection->from("proformas");

           $this->connection->select("*");

           $flag = false; 

           $query = $this->connection->get();

            if($query->num_rows() > 0){

                $flag = true;

            }

            $query->free_result();

            return $flag;

        }

        

         public function excel_exist_get_id($nombre_comercial, $email, $nif_cif){

           $this->connection->where("nombre_comercial", $nombre_comercial);

           $this->connection->where("nif_cif", $nif_cif);

           $this->connection->where("email", $email);

           $this->connection->from("proveedores");

           $this->connection->select("*");

            $query = $this->connection->get();

            if($query->num_rows() > 0){

                return $query->row()->id_proveedor;

            }

            else {

                

		$array_datos = array(

			"nombre_comercial" => $nombre_comercial,

			"email" => $email,

                        "nif_cif"  => $nif_cif

		);

		

		$this->connection->insert("proveedores",$array_datos);

                return $this->connection->insert_id();

            }

        }

        

        public function excel_add($array_datos){

            $this->connection->insert("proveedores", $array_datos);

        }

        

         public function nif_check($str){

            $result = $this->connection->from('proveedores')

                        ->select('count(id_proveedor) as cantidad, id_proveedor')

                        ->where('nif_cif', $str)

                        ->get()

                        ->row();

            if($result->cantidad > 0 && $this->input->post('id') != $result->id_proveedor){

                return true;

            }

            return false;

        }

    public function obtenerProveedores()
    {
        $query = $this->connection->query("SELECT * FROM proveedores ORDER BY nombre_comercial asc");
        
        if($query->num_rows() > 0)
            return $query->result_array();
        
        return [];
    }

}

?>