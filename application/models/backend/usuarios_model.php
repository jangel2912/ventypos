<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of usuarios_model
 *
 * @author locho
 */
class Usuarios_model  extends CI_Model{
   
	// Constructor
	public function __construct()
	{
		parent::__construct();		
	}
   
	public function get_total()
	{
		$query = $this->db->query("SELECT count(*) as cantidad FROM users");
		return $query->row()->cantidad;								
	}
        
        public function get_total_active(){
            $query = $this->db->query("SELECT count(*) as cantidad FROM users where active = 1");
            return $query->row()->cantidad;		
        }
        
        public function get_total_deactive(){
            $query = $this->db->query("SELECT count(*) as cantidad FROM users where active = 0");
            return $query->row()->cantidad;		
        }

        /*public function get_total_tenant($db_config_id)
	{
		$query = $this->db->query("SELECT count(*) as cantidad FROM users where db_config_id = $db_config_id");
		return $query->row()->cantidad;
	}
	
	public function get_all($offset)
	{
		$db_config_id = $this->session->userdata('db_config_id');
		$query = $this->db->query("SELECT * FROM users where db_config_id = $db_config_id ORDER BY username DESC limit $offset, 8");
		return $query->result();
	}*/
        
        public function get_ajax_data(){
            $aColumns = array('first_name', 'last_name', 'email', 'active', 'username', 'id');
            $sIndexColumn = "id";
            $sTable = "users";
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
        
         
        public function eliminar($id){
            $this->db->where('id', $id);
            $this->db->delete('users');
        }
}

?>