<?php

class Puntos_model extends CI_Model{

	var $connection;

    

	 public function __construct()

            {

                parent::__construct();

                

            }

         

        public function initialize($connection){

            $this->connection = $connection;

        }    


	public function valor_punto(){

		$query = $this->connection->query("SELECT valor_opcion FROM opciones where nombre_opcion = 'punto_valor' ");

		return $query->row_array();		

	}

	public function porcompras_valor(){

		$query = $this->connection->query("SELECT valor_opcion FROM opciones where nombre_opcion = 'por_compras_puntos_acumulados' ");

		return $query->row_array();		

	}
	
	public function valor_punto_redimir($cli){

		$query = "SELECT sum(puntos) as total_puntos FROM puntos_acumulados where cliente = '$cli' ";
                 $queryresult = $this->connection->query($query)->result();  
				 $total_puntos = '0';        
                 foreach ($queryresult as $dat) {
                     $total_puntos = $dat->total_puntos;
                 }   
		$query = "SELECT valor_opcion FROM opciones where nombre_opcion = 'punto_valor'";
                 $queryresult = $this->connection->query($query)->result();  
				 $valor_punto = '0';        
                 foreach ($queryresult as $dat) {
                     $valor_punto = $dat->valor_opcion;
                 }  				 
		return ($total_puntos * $valor_punto);		

	}


	public function si_no_plan_punto(){

		$query = "SELECT count(*) as total_puntos FROM plan_puntos  ";
                 $queryresult = $this->connection->query($query)->result();  
				 $total_puntos = '0';        
                 foreach ($queryresult as $dat) {
                     $total_puntos = $dat->total_puntos;
                 }   				 
		return ($total_puntos);		

	}

	public function por_compras_puntos($total){

		$query = "SELECT valor_opcion FROM opciones where nombre_opcion = 'por_compras_puntos_acumulados'  ";
                 $queryresult = $this->connection->query($query)->result();  
				 $por_compras_puntos_acumulados = '0';  $opc = '';       
                 foreach ($queryresult as $dat) {
                     $por_compras_puntos_acumulados = $dat->valor_opcion;
                 }   	
			
			if($total >= $por_compras_puntos_acumulados){   $opc =  'si'; }	 
				 
				 			 
		return ($opc);		

	}
	
	public function puntos_acumulados_cliente($id, $tipo){
      
	  if($tipo == 'factura'){
		$query = "SELECT cliente_id FROM venta where id = '$id' ";
                 $queryresult = $this->connection->query($query)->result();  
				 $cliente_id = '0';        
                 foreach ($queryresult as $dat) {
                     $cliente_id = $dat->cliente_id;
                 }   
		
                $query = $this->connection->get_where("puntos_acumulados",array('cliente'=>$cliente_id, 'factura'=>$id))->row();
                $total_puntos = '0';        
                
                if(count($query) != 0)
                {
                    $total_puntos = $query->puntos;
                }
            }
		
	  if($tipo == 'acumulado'){
		$query = "SELECT cliente_id FROM venta where id = '$id' ";
                 $queryresult = $this->connection->query($query)->result();  
				 $cliente_id = '0';        
                 foreach ($queryresult as $dat) {
                     $cliente_id = $dat->cliente_id;
                 }   
		$query = "SELECT sum(puntos) as total_puntos FROM puntos_acumulados where cliente = '$cliente_id'  ";
                 $queryresult = $this->connection->query($query)->result();  
				 $total_puntos = '0';        
                 foreach ($queryresult as $dat) {
                     $total_puntos = $dat->total_puntos;
                 } 
		}
						  				 
		return ($total_puntos);		

	}

	public function edit_plan($id = 0){

		$plan = $this->connection->query("SELECT * FROM plan_puntos where id_puntos = '$id' ");
		$options = $this->connection->query("SELECT * FROM opciones where nombre_opcion = 'punto_valor' || nombre_opcion = 'por_compras_puntos_acumulados' ");
		$data = array(
			'plan' => $plan->result(),
			'options' => $options->result()
		);
		return $data;		

	}

	public function clientes_con_puntos(){
		$query = $this->connection->query("SELECT * FROM cliente_plan_punto");	
		return $query->num_rows();
	}

	public function edit_clientes_plan($id = 0){

		$query = $this->connection->query("SELECT * FROM cliente_plan_punto AS cli_plan  left join clientes on clientes.id_cliente = cli_plan.id_cliente  where id = '$id' ");

		return $query->row_array();		

	}
	
     public function get_ajax_plan_puntos(){

		$aColumns = array(           
            'nombre',
            'puntos', 
            'valor',
            'iva',           
            'tiempo_caducidad'
		);
		       
        //limit
        $sLimit = "";
        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {
            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
        }
        //order
        $sOrder = "";
        if (isset($_GET['iSortCol_0'])) {
            $sOrder = "ORDER BY  ";
            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {
                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i]) ] == "true") {
                    $sOrder.= $aColumns[intval($_GET['iSortCol_' . $i]) ] . ' ' . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }

            $sOrder = substr_replace($sOrder, "", -2);
            if ($sOrder == "ORDER BY") {
                $sOrder = "";
            }
        }
        //Search
        $Where="";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            $Where.= "AND (";
            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
                    $Where.= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $Where = substr_replace($Where, "", -3);
            $Where.= ')';
        }

		$sql = "SELECT SQL_CALC_FOUND_ROWS * from plan_puntos where id_puntos >0 
		$Where                  
		$sOrder
		$sLimit";
		
        $data = array();
        $rResult = $this->connection->query($sql);
        $sQuery = "SELECT FOUND_ROWS() as cantidad";
        $rResultFilterTotal = $this->connection->query($sQuery);
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        $sQuery = "SELECT COUNT(id_puntos) AS cantidad FROM plan_puntos WHERE id_puntos >0";
        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;
        $output = array(
            "sEcho" => intval($_GET['sEcho']) ,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            //"iTotalDisplayRecords" => 142,
            "aaData" => array()
        );

        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->nombre,
                $value->puntos,
                number_format($value->valor),
				$value->iva,
				$value->tiempo_caducidad,
				$value->id_puntos
            );
        }
        /*return array(
            'aaData' => $data
		);*/
		$output['aaData'] = $data;
        return $output;

    }
	
     public function get_ajax_cliente_plan_puntos(){

		$aColumns = array(
			"nombre_comercial",
			"nif_cif",
			"plan_puntos.nombre",
			"codinterna",
			 "cliente_plan_punto.id",
			"clientes.id_cliente"
		);

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

                    $sOrder .= $aColumns[intval($_GET['iSortCol_' . $i])] . ' ' . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }



            $sOrder = substr_replace($sOrder, "", -2);

            if ($sOrder == "ORDER BY") {

                $sOrder = "";
            }
        }

        $sWhere = " ";

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {
            
            $sWhere .= "WHERE (";

            for ($i = 0; $i < count($aColumns); $i++) {

                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {

                    $sWhere .= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $sWhere = substr_replace($sWhere, "", -3);

            $sWhere .= ')';
		}
		
        $sql = "SELECT SQL_CALC_FOUND_ROWS nombre_comercial, nif_cif, plan_puntos.nombre, codinterna,  cliente_plan_punto.id, clientes.id_cliente 
		from cliente_plan_punto 
		left join plan_puntos on plan_id = id_puntos 
		left join clientes on clientes.id_cliente = cliente_plan_punto.id_cliente
		$sWhere                  
		$sOrder
		$sLimit
		";
		$data = array();
		$rResult = $this->connection->query($sql);
		/* Data set length after filtering */
		$sQuery = "SELECT FOUND_ROWS() as cantidad";
		$rResultFilterTotal = $this->connection->query($sQuery);		
		$iFilteredTotal = $rResultFilterTotal->row()->cantidad;
		$sQuery = " SELECT COUNT(id) as cantidad FROM cliente_plan_punto";
		$rResultTotal = $this->connection->query($sQuery);
		$iTotal = $rResultTotal->row()->cantidad;
		$output = array(
			"sEcho" => intval($_GET['sEcho']) ,
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);

        foreach ($rResult->result() as $value) {
			$puntos_acumulados = 0;
			$total_redimible = 0;

			//Total puntos acumulados
			$this->connection->select("SUM(puntos) as puntos_acumulados");
			$this->connection->from("puntos_acumulados");
			$this->connection->where("cliente",$value->id_cliente);
			$result = $this->connection->get();
			if($result->num_rows() > 0):
				$puntos_acumulados = $result->result()[0]->puntos_acumulados; 
			endif;
			
			//Total redimible
			$total_redimible = $puntos_acumulados * getGeneralOptions("punto_valor")->valor_opcion;

            $data[] = array(
                $value->nombre_comercial,
                $value->nif_cif,
				$value->nombre,
				$value->codinterna,
				$puntos_acumulados,
				$total_redimible,
				$value->id
            );
		}
		$output['aaData'] = $data;
        return $output;
        /*return array(
            'aaData' => $data
        );*/

    }
	
	public function update(){	
		$array_datos = array(
			"nombre"   => $this->input->post('nombre'),
            "puntos"  => $this->input->post('no_puntos'),
			"valor"	=> $this->input->post('valor'),
			"iva"  => $this->input->post('impuesto'),
			"tiempo_caducidad" => $this->input->post('tiempo_caducidad')
		);
		$this->connection->where('id_puntos', $this->input->post('id_puntos'));
		$this->connection->update("plan_puntos",$array_datos);
		
		$data_options = array(
			array(
				'nombre_opcion' => 'punto_valor',
				'valor_opcion' => $this->input->post('punto_redimir')
			),
			array(
				'nombre_opcion' => 'por_compras_puntos_acumulados',
				'valor_opcion' => $this->input->post('compras')
			),
			array(
				'nombre_opcion' => 'puntos_correo_bienvenida',
				'valor_opcion' => ($this->input->post('correo_bienvenida') == 'on')? 1 : 0
			)
		);
		
		$this->connection->update_batch("opciones",$data_options,'nombre_opcion');
		if($this->input->post('agregar_clientes') == 'on'):
			
			$this->add_clients_points($this->input->post('id_puntos'));
		// 	echo("asda");
		// die;
		else:
			$this->remove_clients_points($this->input->post('id_puntos'));
		endif;
	}

	
	public function actualizar_punto(){	
		$array_datos = array(
			"valor_opcion"   => $this->input->post('punto_val')
		);

		$this->connection->where('nombre_opcion', 'punto_valor');
		$this->connection->update("opciones",$array_datos);
	}
	
	public function actualizar_porcompras(){	
		$array_datos = array(
			"valor_opcion"   => $this->input->post('porcompras')
		);
		
		$this->connection->where('nombre_opcion', 'por_compras_puntos_acumulados');
		$this->connection->update("opciones",$array_datos);
	}
	

	public function add(){	

		$array_datos = array(
			"nombre"   => $this->input->post('nombre'),
            "puntos"  => $this->input->post('no_puntos'),
			"valor"	=> $this->input->post('valor'),
			"iva"  => $this->input->post('impuesto'),
			"tiempo_caducidad" => $this->input->post('tiempo_caducidad')
		);
		$this->connection->insert("plan_puntos",$array_datos);
		$id = $this->connection->insert_id();
		
		$data_options = array(
			array(
				'nombre_opcion' => 'punto_valor',
				'valor_opcion' => $this->input->post('punto_redimir')
			),
			array(
				'nombre_opcion' => 'por_compras_puntos_acumulados',
				'valor_opcion' => $this->input->post('compras')
			),
			array(
				'nombre_opcion' => 'puntos_correo_bienvenida',
				'valor_opcion' => ($this->input->post('correo_bienvenida') == 'on')? 1 : 0
			)
		);
		$this->connection->update_batch("opciones",$data_options,'nombre_opcion');

		if($this->input->post('agregar_clientes') == 'on'):
            $this->add_clients_points($id);
		endif;
	}
	
	public function add_clients_points($id){
		$this->connection->select("id_cliente as id");
		$this->connection->from("clientes");
		$this->connection->where("id_cliente <>", -1);
		$result = $this->connection->get();
		if($result->num_rows() > 1):
			$this->connection->where('id >',0);
			$this->connection->delete("cliente_plan_punto");
			$clients = $result->result();
			foreach($clients as $client):

				$array_datos = array(
					"id_cliente"   => $client->id,
					"plan_id"  => $id,
					"codinterna" => ''
				);
				$this->connection->insert("cliente_plan_punto",$array_datos);	
			endforeach;
		endif;
	}

	public function remove_clients_points($id){
		$this->connection->where('plan_id',$id);
		$this->connection->delete("cliente_plan_punto");
	}


	public function cliente_plan_nuevo()

	{	

		//consultamos si ya esta asignado el cliente al plan
		$where = array('id_cliente'=>$this->input->post('id_cliente'),'plan_id'=>$this->input->post('plan_puntos'));
		$this->connection->where($where);
		$query = $this->connection->get('cliente_plan_punto');
		if($query->num_rows() > 0){
			return false;
		}
		
		$array_datos = array(
			"id_cliente"=> $this->input->post('id_cliente'),
            "plan_id"  => $this->input->post('plan_puntos'),
			"codinterna"=> $this->input->post('cod')
		);	
		
		$this->connection->insert("cliente_plan_punto", $array_datos);
		return true;
		
	}	
	
	public function update_cliente_plan_nuevo()

	{	

		$array_datos = array(
			"id_cliente"   => $this->input->post('id_cliente'),
            "plan_id"  => $this->input->post('plan_puntos'),
			"codinterna"	=> $this->input->post('cod')
		);

		$this->connection->where('id', $this->input->post('plan_puntos'));
		$this->connection->update("cliente_plan_punto",$array_datos);
	}
	
	public function delete($id)

	{	
		$this->connection->where('plan_id', $id);
        $this->connection->delete("cliente_plan_punto");		
	
		$this->connection->where('id_puntos', $id);
        $this->connection->delete("plan_puntos");	
	}

	public function eliminar_cliente_plan($id)

	{	
		$this->connection->where('id', $id);
        $this->connection->delete("cliente_plan_punto");	
	}	

    public function plan_puntos(){

           $this->connection->select('id_puntos, nombre'); 

           $query = $this->connection->get_where('plan_puntos');

           $result = array();

           foreach ($query->result() as $value) {

               $result[$value->id_puntos] = $value->nombre;

           }

           return $query->result();

     }
	 

	 public function update_table_puntos(){
		 /**
		 * Crea la columna fecha_caducidad si no existe el en la tabla
		 */
		$sql = "SHOW COLUMNS FROM `plan_puntos` LIKE 'tiempo_caducidad'";
		$existeCampo = $this->connection->query($sql)->result();
		
		if(count($existeCampo) == 0)
		{
			$sql = "ALTER TABLE `plan_puntos`   
				ADD COLUMN `tiempo_caducidad` INT(11) NULL default 0 COMMENT 'tiempo LIMITE en meses para redimir los puntos';
			";

			$this->connection->query($sql);
		}

		/**
		 * Crea la opción puntos_correo_bienvenida si no existe en opciones
		 */
		$this->connection->select("*");
		$this->connection->from("opciones");
		$this->connection->where("nombre_opcion","puntos_correo_bienvenida");
		$result = $this->connection->get();
		if($result->num_rows() == 0):
			$data = array(
				"nombre_opcion" => "puntos_correo_bienvenida",
				"valor_opcion" => 0
			);
			$this->connection->insert("opciones",$data);
		endif;
	}

	 
			
	 public function activate_plan(){

		 $this->db->select("*");
		 $this->db->from("modulos_clientes m");
		 $this->db->where("m.db_config_id",$this->session->userdata('db_config_id'));
		 $this->db->where("m.modulo_id",4);
		 $result = $this->db->get();
		
		 if($result->num_rows() <= 0){
			$data = array(
				'db_config_id' => $this->session->userdata('db_config_id'),
				'modulo_id' => 4,
				'estado' => 1 
			);
			$this->db->insert("modulos_clientes",$data);
		}else{
			$data = array(
				'estado' => 1 
			);
			$this->db->where('db_config_id',$this->session->userdata('db_config_id'));
			$this->db->where('modulo_id',4);
			$this->db->update("modulos_clientes",$data);
		}
	 }


	 public function get_count_plan(){
		 $this->connection->select("*");
		 $this->connection->from("plan_puntos");
		 $result = $this->connection->get();
		 return $result->num_rows(); 
	 }

	 public function get_plan($id){
		$this->connection->select("*");
		$this->connection->from("plan_puntos");
		$this->connection->where("id_puntos",$id);
		$this->connection->limit(1);
		$result = $this->connection->get();
		if($result->num_rows() > 0):
			return $result->result()[0];
		else:
			return NULL;
		endif;
	 }

}

?>