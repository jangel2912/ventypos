<?php

// Proyecto: Sistema Facturacion

// Version: 2.0

// Programador: Efrain Losaada

// Framework: Codeigniter

// Clase: Servicios



class Impuestos_model extends CI_Model

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
        $this->actualizarTabla();
		$query = $this->connection->query("SELECT count(*) as cantidad FROM  impuesto");

		return $query->row()->cantidad;								

	}

        

        public function get_ajax_data(){

            $this->actualizarTabla();
            $aColumns = array('nombre_impuesto',"predeterminado", 'porciento', 'id_impuesto');

            $sIndexColumn = "id_impuesto";

            $sTable = "impuesto";

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

            //$aResultTotal = $rResultTotal->result_array();

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

      public function get_impuesto($porciento){
        $this->actualizarTabla();
        $result = 0;
		$response = 0;
        $query =  "SELECT nombre_impuesto FROM impuesto WHERE porciento = '".$porciento."'";

        foreach($this->connection->query($query)->result() as $value) {
              
             $result = $value->nombre_impuesto;           
        }
        
        return $result;
		
    }         

    public function get_combo_data()
	{
        $this->actualizarTabla();
		$data = array();

		$query = $this->connection->query("SELECT * FROM impuesto ORDER BY id_impuesto ASC");

                foreach ($query->result() as $value) {

                    $data[$value->id_impuesto] = $value->nombre_impuesto;

                }

                return $data;

	}

    public function get_combo_data_impuesto()
	{
        $this->actualizarTabla();
		$data = array();

		$query = $this->connection->query("SELECT * FROM impuesto ORDER BY id_impuesto ASC");

         return $query->result();

	}      

    public function get_combo_data_factura()
	{
        $this->actualizarTabla();
		$data = array();

		$query = $this->connection->query("SELECT * FROM impuesto ORDER BY id_impuesto DESC");

                foreach ($query->result() as $value) {

                    $data[$value->porciento] = $value->nombre_impuesto;

                }

                return $data;

	}

	

	public function get_all($offset)

	{
        $this->actualizarTabla();		

		$query = $this->connection->query("SELECT * FROM impuesto  ORDER BY id_impuesto DESC limit $offset, 8");

		return $query->result();

	}

	

	public function get_term($q='')
	{
        $this->actualizarTabla();		

		$query = $this->connection->query("SELECT id_servicio as id, nombre, precio FROM servicios WHERE nombre LIKE '%$q%' LIMIT 0,30");

		return $query->result_array();

	}

	

	public function get_by_id($id = 0)
	{
        $this->actualizarTabla();
		$query = $this->connection->query("SELECT * FROM  impuesto WHERE id_impuesto = '".$id."'");
		return $query->row_array();								

	}

	

	public function add(){
        $this->actualizarTabla();
        $this->connection->select("*");
        $this->connection->from("impuesto");
        $this->connection->where("porciento",$this->input->post('porciento'));
        $result = $this->connection->get();

        if($result->num_rows() > 0){
            return false;
        }else{
            $predeterminado = $this->input->post('predeterminado') ? true : false;

            if( $predeterminado ){// cambia el Valor de todos los predeterminado de true a false
                $this->connection->where("predeterminado",true)
                                ->update('impuesto','predeterminado',false);
            }
            
            $array_datos = array(
                "nombre_impuesto"        => $this->input->post('nombre'),
                "porciento"  	=> $this->input->post('porciento'),
                'predeterminado' => $predeterminado
            );
            $this->connection->insert("impuesto",$array_datos);
            return true;
        }
		
	}

	

	public function update()
	{	
        $this->actualizarTabla();
        $predeterminado = $this->input->post('predeterminado') ? true : false;
		$array_datos = array(

			"nombre_impuesto" => $this->input->post('nombre'),
            "porciento"  	=> $this->input->post('porciento'),
            'predeterminado'=> $predeterminado

		);				

		$this->connection->where('id_impuesto', $_POST['id']);

        $this->connection->update("impuesto",$array_datos);
        
        if( $predeterminado == true){

            $this->connection->where("predeterminado",true)
                            ->where('id_impuesto !=', $_POST['id'])
                            ->update('impuesto','predeterminado',false);
        }
	}

	

	public function delete($id)
	{	
        $this->actualizarTabla();
		$this->connection->where('id_impuesto', $id);

		$this->connection->delete("impuesto");	

	}

        

    public function excel(){
        $this->actualizarTabla();
        $this->connection->select("*");

        $this->connection->from("impuesto");

        $query = $this->connection->get();

        return $query->result();

    }

        

        public function excel_exist($nombre, $porciento){
            $this->actualizarTabla();           

           $this->connection->where("nombre_impuesto", $nombre);

            $this->connection->where("porciento", $porciento);

            $this->connection->from("impuesto");

            $this->connection->select("*");

            

            $query = $this->connection->get();

            if($query->num_rows() > 0){

                return true;

            }

            else {

                return false;

            }

            

        }

        

        public function excel_exist_get_id($nombre, $porciento){
            $this->actualizarTabla();
            $this->connection->where("nombre_impuesto", $nombre);

            $this->connection->where("porciento", $porciento);

            $this->connection->from("impuesto");

            $this->connection->select("id_impuesto");

            

            $query = $this->connection->get();

            if($query->num_rows() > 0){

                return $query->row()->id_impuesto;

            }

            else {

                

		$array_datos = array(

			"nombre_impuesto"        => $nombre,

			"porciento"  	=> $porciento

		);

		

		$this->connection->insert("impuesto",$array_datos);

                return $this->connection->insert_id();

            }

        }

        

        public function excel_add($array_datos){
            $this->actualizarTabla();
		    $this->connection->insert("impuesto",$array_datos);

        }

        public function get_by_name($name)
        {
            $this->actualizarTabla();
            $query = $this->connection->query("select id_impuesto from impuesto where nombre_impuesto = '$name'");
            if($query->num_rows() > 0){
                return $query->row()->id_impuesto;
            }
            return "";
        }
        public function get_id($name,$por=null) 
        {
            $this->actualizarTabla();
            $name = strtoupper($name);
            $name = str_replace('%', '', $name);
            $query="";
            $query2="";
            if(!empty($por)){
                $query = $this->connection->query("select id_impuesto from impuesto where id_impuesto = '$name'");
            }else{
                $query = $this->connection->query("select id_impuesto from impuesto where upper(nombre_impuesto) = '".strtoupper($name)."'");
                $query2 = $this->connection->query("select id_impuesto from impuesto where porciento = '$name'");
            }            
            
            if($query->num_rows() > 0){
                return $query->row()->id_impuesto;
            }else{
                if($query2->num_rows() > 0){
                    return $query2->row()->id_impuesto;
                }
            }
            return "";
        }

        public function get_name_by_porcent($porcent){
            $this->actualizarTabla();
            $this->connection->select("*");
            $this->connection->from("impuesto");
            $this->connection->where("porciento",$porcent);
            $this->connection->limit("1");
            $result = $this->connection->get();
            if($result->num_rows() > 0){
                return $result->row();
            }else{
                return NULL;
            }
        }

        /**
         * Trae unicamente el ultimo porcentaje
         */
        public function getFisrt()
        {   
            $this->actualizarTabla();
            $query = $this->connection->select('porciento')
                        ->where('predeterminado',true)
                        ->get('impuesto');
            $query = $query->row();
           
            if(count($query)){
                $query1 = $this->connection->select('porciento')
                    ->where('porciento',$query->porciento)
                    ->order_by('id_impuesto','ASC')
                    ->limit(1)
                    ->get('impuesto');
                $query1 = $query1->row();
            }else{
                $query = $this->connection->select('porciento')
                    ->where('porciento',0)
                    ->order_by('id_impuesto','ASC')
                    ->limit(1)
                    ->get('impuesto');
                $query = $query->row();
            }           

            return $query;
        }

        public function actualizarTabla()
        {
            /**
             * Crea la columna predeterminado si no existe el en la tabla
             */
            $sql = "SHOW COLUMNS FROM `impuesto` LIKE 'predeterminado'";
            $existeCampo = $this->connection->query($sql)->result();
            if(count($existeCampo) == 0)
            {
                $sql = "ALTER TABLE `impuesto`   
                    ADD COLUMN `predeterminado` BOOLEAN NOT NULL default false;
                ";

                $this->connection->query($sql);
            }
        }

}

?>