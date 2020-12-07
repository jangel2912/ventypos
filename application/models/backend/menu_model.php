<?php

class Menu_model extends CI_Model{
    
    function __construct() {
        parent::__construct();
    }
    
    function get_menus($offset = 0)
    {
        $query = $this->db->query("select * from menu");
        return $query->result();
    }
    
    function get_total()
    {
        $query = $this->db->query("select count(*) as cantidad from menu");
        return $query->row()->cantidad;
    }
    
    function add(){
        $array_datos = array(
                "nombre_link"        => $this->input->post('nombre_link'),
                "direccion"  	=> $this->input->post('direccion'),
                "peso"  	=> $this->input->post('peso'),
                "icono"  	=> $this->input->post('icono'),
                "color"  	=> $this->input->post('color')
        );

        $this->db->insert("menu",$array_datos);
    }
    
    public function get_ajax_data(){
            $aColumns = array('icono', 'nombre_link', 'direccion', 'color', 'peso', 'id_menu');
            $sIndexColumn = "id_menu";
            $sTable = "menu";
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
            $rResult =  $this->db->query($sQuery);
            /* Data set length after filtering */
            $sQuery = "
                    SELECT FOUND_ROWS() as cantidad
            ";
             $rResultFilterTotal = $this->db->query($sQuery);
             //$aResultFilterTotal = $rResultFilterTotal->result_array();
             $iFilteredTotal = $rResultFilterTotal->row()->cantidad;
             
             $sQuery = "
		SELECT COUNT(`".$sIndexColumn."`) as cantidad
		FROM   $sTable
            ";
            $rResultTotal = $this->db->query($sQuery);
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
    
    public function update()
    {	
            $array_datos = array(
                "nombre_link"   => $this->input->post('nombre_link'),
                "direccion"  	=> $this->input->post('direccion'),
                "peso"  	=> $this->input->post('peso'),
                "icono"  	=> $this->input->post('icono'),
                "color"  	=> $this->input->post('color')
            );

            $this->db->where('id_menu', $_POST['id']);
            $this->db->update("menu", $array_datos);
    }
    
    public function get_by_id($id = 0)
    {
            $query = $this->db->query("SELECT * FROM  menu WHERE id_menu = '".$id."'");

            return $query->row_array();								
    }
    
    public function delete($id)
    {	
            $this->db->where('id_menu', $id);
            $this->db->delete("menu");	
    }
    
}
?>