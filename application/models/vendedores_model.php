<?php


class Vendedores_model extends CI_Model

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

		$query = $this->connection->query("SELECT * FROM vendedor");

                foreach ($query->result() as $value) {

                    $data[$value->id] = $value->nombre;

                }

                return $data;

	}

	

	public function get_total()

	{

		$query = $this->connection->query("SELECT count(*) as cantidad  FROM  proveedores");

		return $query->row()->cantidad;								

	}

   public function get(){
        $this->connection->select("*");
        $this->connection->from("vendedor");
        $result = $this->connection->get();
        if($result->num_rows() > 0){
            return $result->result();
        }else{
            return NULL;
        }
   }     

        public function get_ajax_data($estacion=0){
            
      //------------------------------------------------ almacen usuario  
        $user_id = $this->session->userdata('user_id');
        $id_user='';
        $almacen='';
        $nombre='';	
        
        $user = $this->db->query("SELECT id FROM users where id = '".$user_id."' limit 1")->result();
            foreach ($user as $dat) {
            $id_user = $dat->id;
            }	
				
			$user = $this->dbConnection->query("SELECT almacen_id, almacen.nombre FROM usuario_almacen left join almacen on almacen.id = almacen_id where usuario_id = '".$id_user."' limit 1")->result();
            foreach ($user as $dat) {
            $almacen = $dat->almacen_id;
            $nombre = $dat->nombre;
            }			 
	  //---------------------------------------------	  
        $is_admin = $this->session->userdata('is_admin');  
        $Where="";
        if($estacion!=1){
            if($is_admin == 's'){ 
                $Where=" where estacion=0 and almacen = '".$almacen."'"; 
                $sql = " SELECT SQL_CALC_FOUND_ROWS nombre, cedula, email,  telefono, id  from vendedor $Where"; 
            } 
            else{ 
                $Where=" where estacion=0";  
                $sql = " SELECT SQL_CALC_FOUND_ROWS nombre, cedula, email,  telefono, id  from vendedor $Where"; 
            } 
        }else{
            if($is_admin == 's'){ 
                $Where=" where almacen = '".$almacen."'";   
                $sql = " SELECT SQL_CALC_FOUND_ROWS nombre, cedula, email,  telefono, codigo, estacion, id  from vendedor $Where"; 
            } 
            else{  
                $sql = " SELECT SQL_CALC_FOUND_ROWS nombre, cedula, email,  telefono, codigo, estacion, id  from vendedor ";             
            } 
        }

        if($estacion!=1){
            $aColumns = array(           
                'id',
                'nombre', 
                'cedula', 
                'email',  
                'telefono',
                'id'
            );
        }else{
            $aColumns = array(           
                'id',
                'nombre', 
                'cedula', 
                'email',  
                'telefono',
                'estacion',
                'id'
            );
        }
        
        $sIndexColumn = "id";
        $sTable = "venta";
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
        $sWhere="";
        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            if(empty($Where)){ 
                $sWhere.= " WHERE (";
            }else{
                $sWhere.= " AND (";
            }

            for ($i = 0; $i < count($aColumns); $i++) {
                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {
                    $sWhere.= $aColumns[$i] . " LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $sWhere = substr_replace($sWhere, "", -3);
            $sWhere.= ')';
        }

        $sql .= "
        $sWhere           
        $sOrder
        $sLimit";
        
        $data = array();
        $rResult = $this->connection->query($sql);
        /* Data set length after filtering */
        $sQuery = "SELECT FOUND_ROWS() as cantidad";
        $rResultFilterTotal = $this->connection->query($sQuery);
        // $aResultFilterTotal = $rResultFilterTotal->result_array();
        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
        $sQuery = " SELECT COUNT(id) as cantidad FROM vendedor $Where";
        $rResultTotal = $this->connection->query($sQuery);
        $iTotal = $rResultTotal->row()->cantidad;
        $output = array(
            "sEcho" => intval($_GET['sEcho']) ,
            "iTotalRecords" => $iTotal,
            "iTotalDisplayRecords" => $iFilteredTotal,
            "aaData" => array()
        );

        if($estacion != 1){
            foreach ($rResult->result() as $value) {
                $data[] = array(
                    $value->id,
                    $value->nombre,
                    $value->cedula,
                    $value->email,
                    $value->telefono,
                    $value->id
                );
            }
        }else{
            if($estacion==1){
                foreach ($rResult->result() as $value) {
                    $data[] = array(
                        $value->id,
                        $value->nombre,
                        $value->cedula,
                        $value->email,
                        $value->telefono,                                               
                        ($value->estacion==1)?"Si":"No",
                        $value->id
                    );
                }
            }
        }

        $output['aaData'] = $data;
        return $output;
        /*return array(
            'aaData' => $data
        );*/

    }

	public function get_all_invoce2()
    {

        

        $query = $this->connection->query("SELECT * FROM vendedor");

        return $query->result();

    }


	public function get_all($offset)

	{

		

		$query = $this->connection->query("SELECT * FROM proveedores c ORDER BY id_proveedor DESC limit $offset, 8");

		return $query->result();

	}

	public function get_term_vendedor($q=''){

     $is_admin = $this->session->userdata('is_admin');
		/*if($is_admin == 's'){
      //------------------------------------------------ almacen usuario  
		$user_id = $this->session->userdata('user_id');
		$id_user='';
		$almacen='';
		$nombre='';	
        $user = $this->db->query("SELECT id FROM users where id = '".$user_id."' limit 1")->result();
                 foreach ($user as $dat) {
                   $id_user = $dat->id;
                 }	
				
			$user = $this->dbConnection->query("SELECT almacen_id, almacen.nombre FROM usuario_almacen left join almacen on almacen.id = almacen_id where usuario_id = '".$id_user."' limit 1")->result();
                 foreach ($user as $dat) {
				   $almacen = $dat->almacen_id;
                   $nombre = $dat->nombre;
                 }			 
	  //---------------------------------------------	 		
        $query = $this->connection->query("SELECT id,nombre as value FROM vendedor WHERE nombre LIKE '%$q%' and almacen = '".$almacen."' LIMIT 0, 30");
        } 
	    else{*/
		$query = $this->connection->query("SELECT id,nombre as value FROM vendedor WHERE nombre LIKE '%$q%' LIMIT 0, 30");
		//}
		
        return $query->result_array();

    }


	public function get_term($q='')

	{

		

		$query = $this->connection->query("SELECT c.id_proveedor as id, CONCAT(c.nombre_comercial,' (', ifNull(c.razon_social, ''),')') as value, CONCAT(ifNull(c.nif_cif, ''), ', ', ifNull(direccion, ''), ', ', ifNull(poblacion, ''), ', ', ifNull(pais, ''), ',', ifNull(provincia, ''),', ',ifNull(cp, '')) as descripcion 

											FROM proveedores c

											WHERE CONCAT(nombre_comercial,' ',ifNull(c.razon_social, ''),' ',ifNull(c.poblacion, '')) LIKE '%$q%' LIMIT 0, 30");

		return $query->result_array();

	}

	

	public function get_by_id($id = 0)

	{

		$query = $this->connection->query("SELECT * FROM  vendedor

										WHERE id = '".$id."'");

		

		return $query->row_array();								

	}

	

	public function add($data)

	{	

		$this->connection->insert("vendedor",$data);

	}

        

	public function update($data)

	{

		$this->connection->where('id', $data['id']);

		$this->connection->update("vendedor",$data);

	}

	

	public function delete($id)

	{	

		$this->connection->where('id', $id);

		$this->connection->delete("vendedor");	

	}

        

         public function excel(){

            $this->connection->select("*");

            $this->connection->from("proveedores");

            $query = $this->connection->get();

            return $query->result();

        }

        

        public function excel_exist($id_proveedor, $id_impuesto, $fecha, $cantidad){

           

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

    public function actualizarTablaparaEstacion()
    {
        $sql = "SHOW COLUMNS FROM vendedor LIKE 'codigo'";
        $existeCampo = $this->connection->query($sql)->result();
        if(count($existeCampo) == 0)
        {
            $sql = "ALTER TABLE `vendedor`   
                ADD COLUMN `estacion` BOOLEAN DEFAULT 0 COMMENT 'Para saber si el vendedor pertenece a estación de pedido',
                ADD COLUMN `sesion_estacion` BOOLEAN DEFAULT 0 COMMENT 'Para controlar la cantidad de veces que esta dentro del sistema',
                ADD COLUMN `codigo` VARCHAR(4) DEFAULT NULL COMMENT 'codigo para ingresar a la estacion de Pedidos';
            ";
            $this->connection->query($sql);
        }
    }

    public function existeCodigo($where){

        $this->connection->where($where);
        $this->connection->select('id');
        $query = $this->connection->get('vendedor')->result();
        
        if(!empty($query)){
            return 1;
        }
        else{
            return 0;
        }
    }

    public function verificarclave($where){

        $this->connection->where($where);
        $this->connection->select('*');
        $query = $this->connection->get('vendedor')->result();
        
        if(!empty($query)){
            return $query;
        }
        else{
            return 0;
        }
    }

    public function validate_sesion(){
        $this->connection->select("*");
        $this->connection->from("vendedor");
        $this->connection->where("id",$this->session->userdata('vendedor_estacion_actual_id'));
        $this->connection->where("sesion_estacion",1);
        $result = $this->connection->get();

        if($result->num_rows() > 0){
            return TRUE;
        }else{
            return NULL;
        }
    }
}

?>