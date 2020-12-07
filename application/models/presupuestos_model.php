<?php

class Presupuestos_model extends CI_Model

{

	var $connection;

	// Constructor

	public function __construct()

	{

		parent::__construct();

		//$this->load->model('opciones_model','opciones');	

	}

	

        public function initialize($connection)

        {

            $this->connection = $connection;

        }

        

	public function get_total()

	{

		$query = $this->connection->query("SELECT count(*) as cantidad FROM  presupuestos");

		return $query->row()->cantidad;								

	}

        

        public function get_ajax_data(){

            $aColumns = array('numero', 'nombre_comercial', 'monto', 'fecha', 'id_presupuesto');

            $sIndexColumn = "id_presupuesto";

            $sTable = "presupuestos";

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

		FROM   $sTable inner join clientes on clientes.id_cliente = $sTable.id_cliente

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
              //$aColumns = array('numero', 'nombre_comercial', 'monto', 'fecha', 'id_presupuesto');aca
                for($i = 0; $i<count($aColumns) ; $i++){
                    if($aColumns[$i]=='monto'){
                       $data[] =$this->opciones_model->formatoMonedaMostrar($row[$aColumns[$i]]);
                    }else{
                        $data[] = $row[$aColumns[$i]];
                    }
                    

                }

                $output['aaData'][] = $data;

            }

            return $output; 

        }

	

	public function get_max_cod()

	{

		$query = $this->connection->query("SELECT MAX(RIGHT(numero,6)) as cantidad FROM  presupuestos");

		return $query->row()->cantidad; 								

	}

	

	public function get_all($offset)

	{

		

		$query = $this->connection->query("SELECT * FROM presupuestos p, clientes c 

										WHERE p.id_cliente = c.id_cliente 

											ORDER BY p.id_presupuesto DESC LIMIT $offset, 8");

		return $query->result();

	}

        

        public function get_sum()

	{

		

		$query = $this->connection->query("SELECT sum(monto) as cantidad FROM presupuestos");

		return $query->row()->cantidad;

	}

	

	

	public function get_by_id($id = 0)

	{

		$query = $this->connection->query("SELECT * FROM 
			                                             presupuestos p inner join clientes c on p.id_cliente = c.id_cliente 

													     WHERE p.id_presupuesto = '".$id."' 

														 ORDER BY p.id_presupuesto DESC");

		return $query->row_array();								

	}

	

	

	public function get_term()

	{

		$q  = $this->input->post("q");

		$fi = $this->input->post("fi");

		$ff = $this->input->post("ff");

		

		if($fi != '' && $ff != '') $wfecha = " AND p.fecha BETWEEN '".$fi."' AND '".$ff."'";

		if($q != '') $wcl = " AND c.id_cliente = '".$q."'";

		 

		$query = $this->connection->query("SELECT p.id_presupuesto id, p.numero, c.nombre_comercial, p.monto, 

												DATE_FORMAT(p.fecha , '%d/%m/%Y') fecha

												FROM presupuestos p, clientes c 

												WHERE p.id_cliente = c.id_cliente 

													".$wfecha."

													".$wcl."

												ORDER BY p.id_presupuesto DESC");	

																								

		return $query->result_array();

	}



	public function eliminar_producto_actualizar($coti, $id)

	{	  

		$this->connection->where('id_presupuesto_detalle', $id);

		$this->connection->delete("presupuestos_detalles");	
			


			    $total_venta = 0;
		        $total_impuesto = 0;
				$total_precio_venta = 0;
   $product = "SELECT 
	 SUM( (`precio` - `descuento`) * impuesto / 100 *  `cantidad` ) AS impuestos
	 ,SUM( `precio` * `cantidad` ) AS total_precio_venta
	  ,(SUM( `cantidad` * `precio` ) * descuento / 100) AS total_descuento
	 FROM  `presupuestos_detalles`
	 WHERE id_presupuesto  IN (".$coti.")";		
                 $product = $this->connection->query($product)->result();
				 foreach ($product as $dat) {
                   $total_venta = ($dat->total_precio_venta - $dat->total_descuento) + $dat->impuestos;
				   $total_impuesto = $dat->impuestos;
				   $total_precio_venta = $dat->total_precio_venta;
                 }
	
	
                    $monto = array(

                        'monto' => $total_venta 

                        ,'monto_siva' => $total_precio_venta

                        ,'monto_iva' =>  $total_impuesto

                    );
				
			   $this->connection->where('id_presupuesto', $coti);
               $this->connection->update('presupuestos', $monto);
							

	}	


	public function agregar_producto_coti($coti)

	{	  

                $data_detalles = array();

                    $data_detalles[] = array(

                        'id_presupuesto' => $coti

                        ,'fk_id_producto' => $this->input->post('id_producto_ac')

                        ,'precio' =>  $this->input->post('precio_ac')

                        ,'cantidad' =>  $this->input->post('cantidad_ac')

                        ,'descuento' =>  $this->input->post('descuento_ac')

                        ,'impuesto' =>  $this->input->post('id_impuesto')

                        ,'descripcion_d' =>  $this->input->post('descripcion_ac')

                    );

                $this->connection->insert_batch("presupuestos_detalles",$data_detalles);


			    $total_venta = 0;
		        $total_impuesto = 0;
				$total_precio_venta = 0;
   $product = "SELECT 
	 SUM( (`precio` - `descuento`) * impuesto / 100 *  `cantidad` ) AS impuestos
	 ,SUM( `precio` * `cantidad` ) AS total_precio_venta
	  ,(SUM( `cantidad` * `precio` ) * descuento / 100) AS total_descuento
	 FROM  `presupuestos_detalles`
	 WHERE id_presupuesto  IN (".$coti.")";		
                 $product = $this->connection->query($product)->result();
				 foreach ($product as $dat) {
                   $total_venta = ($dat->total_precio_venta - $dat->total_descuento) + $dat->impuestos;
				   $total_impuesto = $dat->impuestos;
				   $total_precio_venta = $dat->total_precio_venta;
                 }
	
	
                    $monto = array(

                        'monto' => $total_venta 

                        ,'monto_siva' => $total_precio_venta

                        ,'monto_iva' =>  $total_impuesto

                    );
				
			   $this->connection->where('id_presupuesto', $coti);
               $this->connection->update('presupuestos', $monto);
					
	}	



	public function get_detail($id = 0){

		$query = $this->connection->query("
			SELECT id_presupuesto_detalle, 
			       precio, 
			       cantidad, 
			       descuento, 
			       presupuestos_detalles.impuesto AS imp, 
			       descripcion_d, 
			       nombre, 
                               producto.codigo as codigo,
			       fk_id_producto ,
				   id_impuesto
			FROM presupuestos_detalles 
			         inner join producto on producto.id = presupuestos_detalles.fk_id_producto
			         left join impuesto on impuesto.porciento = presupuestos_detalles.impuesto
			WHERE  id_presupuesto = '".$id."'
			GROUP BY presupuestos_detalles.id_presupuesto_detalle
			ORDER BY id_presupuesto_detalle ASC"
		);

		return $query->result_array();								

	}

	public function check_column_nota_cotizacion(){
                $sql = "SHOW COLUMNS FROM presupuestos LIKE 'nota'";
                $existeCampo = $this->connection->query($sql)->result();
                if (count($existeCampo) == 0) {
                        $sql="ALTER TABLE presupuestos   
                ADD COLUMN `nota` TEXT NULL  COMMENT 'nota de cotizacion general' ";
                $this->connection->query($sql);
                }
        }

	public function add(){	

        $array_datos = array(

			"id_cliente" => $this->input->post('id_cliente'),

			"numero" => $this->input->post('numero'),

			"monto"	=> $this->input->post('input_total_civa'),

                        "monto_siva" => $this->input->post('monto_siva'),

                        "monto_iva" => $this->input->post('monto_iva'),

                        "fecha" => $this->input->post('fecha'),

                        "nota" => $this->input->post('nota_cotizacion')

		);

		
                //print_r($array_datos);
		$this->connection->insert("presupuestos",$array_datos);

		$id = $this->connection->insert_id();

                

                $return_array_datos = $array_datos;

		$return_array_datos['id_factura'] = $id;

		

                $data_detalles = array();

                foreach ($_POST['productos'] as $value) {

                    $data_detalles[] = array(

                        'id_presupuesto' => $id

                        ,'fk_id_producto' => $value['fk_id_producto']

                        ,'precio' => $value['precio']

                        ,'cantidad' => $value['cantidad']

                        ,'descuento' => $value['descuento']

                        ,'impuesto' => $value['impuesto']

                        ,'descripcion_d' => $value['descripcion']

                    );

                }

                

                $this->connection->insert_batch("presupuestos_detalles",$data_detalles);

                

                return $return_array_datos;

		

	}

	

	public function actualizar_coti(){
        $array_datos = array(

			"id_cliente" => $this->input->post('id_cliente'),

			"numero" => $this->input->post('numero'),

			"monto"	=> $this->input->post('input_total_civa'),

            "monto_siva" => $this->input->post('monto_siva'),

            "monto_iva" => $this->input->post('monto_iva'),

                        "fecha"  => $this->input->post('fecha'),
                        
                        "nota" => $this->input->post('nota_cotizacion')

		);

			   $this->connection->where('id_presupuesto', $this->input->post('id_presupuesto'));
               $this->connection->update('presupuestos', $array_datos);

  
		$this->connection->where('id_presupuesto', $this->input->post('id_presupuesto'));
		$this->connection->delete("presupuestos_detalles");      
		

                $data_detalles = array();

                foreach ($_POST['productos'] as $value) {

                    $data_detalles[] = array(

                        'id_presupuesto' => $this->input->post('id_presupuesto')

                        ,'fk_id_producto' => $value['fk_id_producto']

                        ,'precio' => $value['precio']

                        ,'cantidad' => $value['cantidad']

                        ,'descuento' => $value['descuento']

                        ,'impuesto' => $value['impuesto']

                        ,'descripcion_d' => $value['descripcion']

                    );

                }

                

                $this->connection->insert_batch("presupuestos_detalles",$data_detalles);

                

                return $return_array_datos;

								
	}

	public function delete($id)

	{	

		$this->connection->where('id_presupuesto', $id);

		$this->connection->delete("presupuestos");

		

		$this->connection->where('id_presupuesto', $id);

		$this->connection->delete("presupuestos_detalles");	

	}

}

?>