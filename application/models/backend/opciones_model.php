<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of opciones_model
 *
 * @author Locho
 */
class opciones_model extends CI_Model {
    //put your code here
    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
    }
    
    public function get_ajax_data(){
        $aColumns = array('nombre_opcion', 'mostrar_opcion', 'valor_opcion', 'id_opcion');
        $sIndexColumn = "id_opcion";
        $sTable = "opciones";
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
                                $sOrder .= " ".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]." ".
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
                                $sWhere .= " ".$aColumns[$i]."  LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
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
            SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
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
    
    function add(){
        $array_datos = array(
                "nombre_opcion"        => $this->input->post('nombre_opcion'),
                "mostrar_opcion"  	=> $this->input->post('mostrar_opcion'),
                "valor_opcion"  	=> $this->input->post('valor_opcion')
        );

        $this->db->insert("opciones", $array_datos);
    }
    
    public function update()
    {	
            $array_datos = array(
                    "nombre_opcion"        => $this->input->post('nombre_opcion'),
                    "mostrar_opcion"  	=> $this->input->post('mostrar_opcion'),
                    "valor_opcion"  	=> $this->input->post('valor_opcion')
            );

            $this->db->where('id_opcion', $_POST['id']);
            $this->db->update("opciones", $array_datos);
    }
    
    public function get_by_id($id = 0)
    {
            $query = $this->db->query("SELECT * FROM  opciones WHERE id_opcion = '".$id."'");

            return $query->row_array();								
    }
    
    public function delete($id)
    {	
            $this->db->where('id_opcion', $id);
            $this->db->delete("opciones");	
    }
    
    
    function get_activation(){
        $this->db->where('nombre_opcion', 'email_activate');
        $this->db->select('mostrar_opcion');
        $query = $this->db->get('opciones');
        return $query->row()->mostrar_opcion;
    }
    
    function save_activation(){
        $data_array = array(
            'mostrar_opcion' => $this->input->post('email')
        );
        $this->db->where('nombre_opcion', 'email_activate');
        $this->db->update('opciones', $data_array);
    }
}

?>