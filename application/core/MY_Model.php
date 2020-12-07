<?php

class MY_Model extends CI_Model
{
	private $db_connection;
	private $options;

    public function __construct()
    {
		parent::__construct();
		$this->options = [
			'table' => '',
			'primaryKey' => '',
			'searchable' => [],
			'fillable' => [],
			'search_query' => '',
		];
    }

    public function initialize($db_connection, array $options=null)
    {
		$this->db_connection = $db_connection;
		if(!is_null($options))
			array_merge($this->options, $options);
	}

	public function getAjaxData()
    {
    	$aColumns = $this->searchable;
        $sIndexColumn = $this->index;
        $sTable = $this->table;
        
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
					$sOrder .= "`".$aColumns[ intval( $_GET['iSortCol_'.$i] ) ]."` ".($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";
            }

            $sOrder = substr_replace( $sOrder, "", -2 );
            if ( $sOrder == "ORDER BY" )
				$sOrder = "";
		}

		$sWhere = "";
		if ( isset($_GET['sSearch']) && $_GET['sSearch'] != "" )
        {
			$sWhere = "WHERE (";
			for ( $i=0 ; $i<count($aColumns) ; $i++ )
			{
				if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )
					$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
			}
			$sWhere = substr_replace( $sWhere, "", -3 );
			$sWhere .= ')';
		}

		for ( $i=0 ; $i<count($aColumns) ; $i++ )
        {
            if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '' )
            {
				if ( $sWhere == "" )
					$sWhere = "WHERE ";
				else
					$sWhere .= " AND ";

				$sWhere .= "`".$aColumns[$i]."` LIKE '%".mysql_real_escape_string($_GET['sSearch_'.$i])."%' ";
            }
        }

        /* Individual column filtering */
        $sQuery = "
			SELECT SQL_CALC_FOUND_ROWS `".str_replace(" , ", " ", implode("`, `", $aColumns))."`
			FROM $sTable inner join clientes on clientes.id_cliente = $sTable.id_cliente
			$sWhere  
			$sOrder
			$sLimit
        ";

        $rResult =  $this->connection->query($sQuery);
        /* Data set length after filtering */

		$sQuery = "SELECT FOUND_ROWS() as cantidad";

		$rResultFilterTotal = $this->connection->query($sQuery);
		//$aResultFilterTotal = $rResultFilterTotal->result_array();
		$iFilteredTotal = $rResultFilterTotal->row()->cantidad;
		$sQuery = "SELECT COUNT(`".$sIndexColumn."`) as cantidad FROM $sTable";
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
			for($i = 0; $i<count($aColumns) ; $i++)
				$data[] = $row[ $aColumns[$i] ];
			
			$output['aaData'][] = $data;
		}
		return $output; 
	}
}