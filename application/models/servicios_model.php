<?php
// Proyecto: Sistema Facturacion
// Version: 1.0
// Programador: Jorge Linares
// Framework: Codeigniter
// Clase: Servicios

class Servicios_model extends CI_Model
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
		$query = $this->connection->query("SELECT count(*) as cantidad FROM servicios s Inner Join impuestos i on s.id_impuesto = i.id_impuesto");
		return $query->row()->cantidad;								
	}
	
	public function get_all($offset)
	{
		
		$query = $this->connection->query("SELECT s.*, i.nombre_impuesto FROM servicios s Inner Join impuestos i on s.id_impuesto = i.id_impuesto ORDER BY id_servicio DESC limit $offset, 8");
		return $query->result();
	}
        
        public function get_ajax_data(){
            $aColumns = array('nombre', 'codigo', 'descripcion', 'precio', 'nombre_impuesto', 'id_servicio');
            $sIndexColumn = "id_servicio";
            $sTable = "servicios";
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
		FROM   $sTable s Inner Join impuestos i on s.id_impuesto = i.id_impuesto
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
	
	public function get_term($q='')
	{
		
		$query = $this->connection->query("SELECT id_servicio as id, nombre, precio, i.nombre_impuesto, i.porciento FROM servicios s Inner Join impuestos i on s.id_impuesto = i.id_impuesto  WHERE nombre LIKE '%$q%' LIMIT 0,30");
		return $query->result_array();
	}
        
        public function get_by_name($name){
            $query = $this->connection->query("select id_servicio from servicios where nombre = '$name'");
            if($query->num_rows() > 0){
                return $query->row()->id_servicio;
            }
            
            return "";
        }
	
	public function get_by_id($id = 0)
	{
		$query = $this->connection->query("SELECT * FROM  servicios WHERE id_servicio = '".$id."'");
		
		return $query->row_array();								
	}
	
	public function add()
	{	
		
		$array_datos = array(
			"nombre"        => $this->input->post('nombre'),
                        "codigo"        => $this->input->post('codigo'),
			"descripcion"  	=> $this->input->post('descripcion'),
			"precio"  	=> $this->input->post('precio'),
                        "id_impuesto"  	=> $this->input->post('id_impuesto')
		);
		
		$this->connection->insert("servicios",$array_datos);
                $array_datos['id_servicio'] = $this->connection->insert_id();
                return $array_datos;
	}
	
	public function update()
	{	
		$array_datos = array(
			"nombre"        => $this->input->post('nombre'),
                        "codigo"        => $this->input->post('codigo'),
			"descripcion"  	=> $this->input->post('descripcion'),
			"precio"  	=> $this->input->post('precio'),
                        "id_impuesto"  	=> $this->input->post('id_impuesto')
		);
				
		$this->connection->where('id_servicio', $_POST['id']);
		$this->connection->update("servicios",$array_datos);
	}
	
	public function delete($id)
	{	
		$this->connection->where('id_servicio', $id);
		$this->connection->delete("servicios");	
	}
        
        public function excel(){
            $this->connection->select("id_servicio, nombre, descripcion, precio, nombre_impuesto, porciento");
            $this->connection->from("servicios");
            $this->connection->join('impuestos', 'impuestos.id_impuesto = servicios.id_impuesto');
            $query = $this->connection->get();
            return $query->result();
        }
        
        public function excel_exist($nombre, $precio){
           
           $this->connection->where("nombre", $nombre);
           $this->connection->where("precio", $precio);
           $this->connection->from("servicios");
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
            $this->connection->insert("servicios", $array_datos);
        }
        
        
}
?>