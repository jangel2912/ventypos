<?php

class lista_precios_model extends CI_Model{

	var $connection;
	// Constructor
	public function __construct()
	{
		parent::__construct();		
	}
	  
	public function initialize($connection){

		$this->connection = $connection;

	} 
	  


	public function isExist($filtro){
		
		 $query = $this->connection->query("SELECT * FROM lista_precios WHERE nombre=   '".$filtro."'");
		
		return $query->result();
		
	}
        
        public function isExistEdit($filtro,$id){
            $query = $this->connection->query("SELECT * FROM lista_precios WHERE nombre='".$filtro."' and id <> $id");
            return $query->result();	
	}

	public function crear(){
            if( $_POST['almacen'] == "")
            {
                $query = "SET FOREIGN_KEY_CHECKS = 0;";
                $this->connection->query($query);
                $_POST['almacen'] = 0;
            }
            $query = "INSERT INTO `lista_precios` (`nombre`, `grupo_cliente_id`, `almacen_id`, `start`,`end`) 
				  VALUES (
					'".$_POST['nombre']."', 
					'".$_POST['grupo']."', 
					'".$_POST['almacen']."', 
					'".str_replace( '/','-',$_POST['inicio'])."','".str_replace( '/','-',$_POST['termina'])."'
					);";
		$this->connection->query($query);
		
		return  $id = $this->connection->insert_id();
	}
        
        public function modificar(){

            if($_POST['almacen'] == "" || $_POST['almacen'] == 0)
            {
                $query = "SET FOREIGN_KEY_CHECKS = 0;";
                $this->connection->query($query);
                $_POST['almacen'] = 0;
            }
            $query = "UPDATE `lista_precios` SET
                `nombre` = '".$_POST['nombre']."',
                `grupo_cliente_id`= '".$_POST['grupo']."',
                `almacen_id` = '".$_POST['almacen']."',
                `start` = '".str_replace( '/','-',$_POST['inicio'])."',
                `end` = '".str_replace( '/','-',$_POST['termina'])."'
                where id = ".$_POST['id'];   
            $this->connection->query($query);
            return  $_POST['id'];
	}

	public function get($id)
	{
		$query = $this->connection->query("SELECT lista_precios.*, (SELECT nombre FROM grupo_clientes WHERE lista_precios.grupo_cliente_id = grupo_clientes.id ) AS nom_group FROM  lista_precios WHERE lista_precios.id = ".$id);    
	   	return $query->result();
	}

	public function leer(){
	   $query = $this->connection->query("SELECT lista_precios.*, (SELECT nombre FROM grupo_clientes WHERE lista_precios.grupo_cliente_id = grupo_clientes.id ) AS nom_group FROM  lista_precios");    
	   return $query->result();  
	}

	public function get_by_id($grupo_cliente_id = 0){
	  $query = $this->connection->query("SELECT * FROM  lista_precios WHERE grupo_cliente_id = '".$grupo_cliente_id."'");
	  return $query->row_array();               
	}

	public function getMaxId(){       
	  $query=$this->connection->query("SELECT MAX(id) AS id FROM lista_precios");
	  return $query->result();        
	}

	public function delete($id_lista){
	  $query = "DELETE FROM lista_precios WHERE id = $id_lista;";
	  return $this->connection->query($query);  
	}

        public function get_ajax_data_index()
        {
            $aColumns = array(
                'Nombre',
                'Grupo',
                'Almacen',
                'Start',
                'End',
                'id'
            );
            $sIndexColumn = "id";
            $sLimit = "";

            if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1')
            {
                $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
            }

            $sOrder = "";
            if (isset($_GET['iSortCol_0']))
            {
                $sOrder = "ORDER BY  ";
                for ($i = 0; $i < intval($_GET['iSortingCols']); $i++)
                {
                    if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i]) ] == "true")
                    {
                        $sOrder.= "`" . $aColumns[intval($_GET['iSortCol_' . $i]) ] . "` " . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                    }
                }

                $sOrder = substr_replace($sOrder, "", -2);
                if ($sOrder == "ORDER BY")
                {
                    $sOrder = "";
                }
            }

            $sWhere = "";

            if (isset($_GET['sSearch']) && $_GET['sSearch'] != "")
            {
                $sWhere = "WHERE (";
                $sWhere.= " `nombre` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'";
                $sWhere.= ')';
                /*for ($i = 0; $i < count($aColumns); $i++)
                {
                    if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '')
                    {
                        if ($sWhere == "")
                        {
                            $sWhere = "WHERE ";
                        }
                        else
                        {
                            $sWhere.= " AND ";
                        }

                        $sWhere.= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
                    }
                } */  
            }
            

                $sQuery = "SELECT lista_precios.`nombre` AS `Nombre`, (SELECT nombre FROM grupo_clientes WHERE id = lista_precios.grupo_cliente_id) AS `Grupo`, (IF(lista_precios.almacen_id = 0, 'Todos los almacenes',(SELECT nombre FROM almacen WHERE id = lista_precios.almacen_id))) AS Almacen, lista_precios.start AS `Start`, lista_precios.end AS `End`, lista_precios.id as id FROM lista_precios
                        $sWhere 
                        $sOrder 
                        $sLimit";

		//echo $sQuery;die;

		$rResult = $this->connection->query($sQuery);
		/* Data set length after filtering */
		$sQuery = "SELECT lista_precios.`nombre` AS `Nombre`, (SELECT nombre FROM grupo_clientes WHERE id = lista_precios.grupo_cliente_id) AS `Grupo`, (IF(lista_precios.almacen_id = 0, 'Todos los almacenes',(SELECT nombre FROM almacen WHERE id = lista_precios.almacen_id))) AS almacen, lista_precios.start AS `Start`, lista_precios.end AS `End`,count(lista_precios.id) as cantidad FROM lista_precios
";

		$rResultFilterTotal = $this->connection->query($sQuery);

		// $aResultFilterTotal = $rResultFilterTotal->result_array();

		$iFilteredTotal = $rResultFilterTotal->row()->cantidad;
		$sQuery =  "SELECT lista_precios.`nombre` AS `Nombre`, (SELECT nombre FROM grupo_clientes WHERE id = lista_precios.grupo_cliente_id) AS `Grupo`, (IF(lista_precios.almacen_id = 0, 'Todos los almacenes',(SELECT nombre FROM almacen WHERE id = lista_precios.almacen_id))) AS almacen, lista_precios.start AS `Start`, lista_precios.end AS `End`,count(lista_precios.id) as cantidad FROM lista_precios        
                            $sWhere";
		$rResultTotal = $this->connection->query($sQuery);
		$iTotal = $rResultTotal->row()->cantidad;
		$output = array(
			"sEcho" => intval($_GET['sEcho']) ,
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);

		foreach($rResult->result_array() as $row)
		{
			$data = array();
			for ($i = 0; $i < count($aColumns); $i++)
			{
                            $data[] = $row[$aColumns[$i]];
			}

			$output['aaData'][] = $data;
		}

            return $output;
        }
        
	public function getAjaxData($libro=0)
	{
		$aColumns = array(
			'Codigo',
			'Producto',
			'V.Especial',
			'V.Especial + iva',
			'Ids',
			'Id impuesto'
		);
		$sLimit = "";

		if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1')
		{
			$sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " . intval($_GET['iDisplayLength']);
		}

		$sOrder = "";

		if (isset($_GET['iSortCol_0']))
		{
			$sOrder = "ORDER BY  ";
			for ($i = 0; $i < intval($_GET['iSortingCols']); $i++)
			{
				if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i]) ] == "true")
				{
					$sOrder.= "`" . $aColumns[intval($_GET['iSortCol_' . $i]) ] . "` " . ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
				}
			}

			$sOrder = substr_replace($sOrder, "", -2);
			if ($sOrder == "ORDER BY")
			{
				$sOrder = "";
			}
		}

		$sWhere = "";

		if (isset($_GET['sSearch']) && $_GET['sSearch'] != "")
		{
			$sWhere = "WHERE (";
				$sWhere.= "`codigo` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR `nombre` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%'";
			$sWhere.= ')';
		}

		/*  echo $sWhere ;*/
		/* Individual column filtering */

		for ($i = 0; $i < count($aColumns); $i++)
		{
			if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '')
			{
				if ($sWhere == "")
				{
					$sWhere = "WHERE ";
				}
				else
				{
					$sWhere.= " AND ";
				}

				$sWhere.= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
			}
		}

		if ($sWhere == '') 
			$sWhere = 'WHERE id_lista_precios = '.$libro.' AND lista_detalle_precios.`id_producto` = producto.`id`';
		else 
			$sWhere = $sWhere.' AND id_lista_precios = '.$libro.' AND lista_detalle_precios.`id_producto` = producto.`id`';
		$sQuery = "SELECT 
                        producto.`codigo` AS `Codigo`, 
						producto.`nombre` AS `Producto`, 
						lista_detalle_precios.`precio` AS `V.Especial`,
						lista_detalle_precios.`precio` + (lista_detalle_precios.`precio` * IF(ISNULL(lista_detalle_precios.`id_impuesto`), 0, (SELECT porciento FROM impuesto WHERE id_impuesto = lista_detalle_precios.`id_impuesto`)) / 100) AS `V.Especial + iva`,
						CONCAT(lista_detalle_precios.`id`, ',', lista_detalle_precios.`id_producto`) AS Ids,
						lista_detalle_precios.`id_impuesto` AS `Id impuesto`
					FROM  
							lista_detalle_precios, producto 
					$sWhere 
					$sOrder 
					$sLimit";

		// echo $sQuery;die;

		$rResult = $this->connection->query($sQuery);
		/* Data set length after filtering */
		$sQuery = "SELECT
						producto.`codigo` AS `Codigo`, 
						producto.`nombre` AS `Producto`, 
						lista_detalle_precios.`precio` AS `V.Especial`,
						lista_detalle_precios.`precio` + (lista_detalle_precios.`precio` * IF(ISNULL(lista_detalle_precios.`id_impuesto`), 0, (SELECT porciento FROM impuesto WHERE id_impuesto = lista_detalle_precios.`id_impuesto`)) / 100) AS `V.Especial + iva`,
						CONCAT(lista_detalle_precios.`id`, ',', lista_detalle_precios.`id_producto`) AS Ids,
						lista_detalle_precios.`id_impuesto` AS `Id impuesto`,
                        count(producto.`codigo`) as cantidad
					FROM  
						lista_detalle_precios, producto WHERE id_lista_precios = $libro AND lista_detalle_precios.`id_producto` = producto.`id`";

		$rResultFilterTotal = $this->connection->query($sQuery);

		// $aResultFilterTotal = $rResultFilterTotal->result_array();

		$iFilteredTotal = $rResultFilterTotal->row()->cantidad;
		$sQuery =  "SELECT
						producto.`codigo` AS `Codigo`, 
						producto.`nombre` AS `Producto`, 
						lista_detalle_precios.`precio` AS `V.Especial`,
						lista_detalle_precios.`precio` + (lista_detalle_precios.`precio` * IF(ISNULL(lista_detalle_precios.`id_impuesto`), 0, (SELECT porciento FROM impuesto WHERE id_impuesto = lista_detalle_precios.`id_impuesto`)) / 100) AS `V.Especial + iva`,
						CONCAT(lista_detalle_precios.`id`, ',', lista_detalle_precios.`id_producto`) AS Ids,
						lista_detalle_precios.`id_impuesto` AS `Id impuesto`,
                        count(producto.`codigo`) as cantidad
					FROM  
						lista_detalle_precios, producto 
					$sWhere";
		$rResultTotal = $this->connection->query($sQuery);
		$iTotal = $rResultTotal->row()->cantidad;
		$output = array(
			"sEcho" => intval($_GET['sEcho']) ,
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);

		foreach($rResult->result_array() as $row)
		{
			$data = array();
			for ($i = 0; $i < count($aColumns); $i++)
			{
				if($i == 2 || $i == 3)
					$data[] = number_format($row[$aColumns[$i]], 2);
				else	
					$data[] = $row[$aColumns[$i]];
			}

			$output['aaData'][] = $data;
		}

		return $output;
	}
        
        public function getForPorcentaje()
        {
            $query = "select lista_precios.id,lista_precios.nombre,opciones.valor_opcion as porcentaje from lista_precios inner join opciones on opciones.nombre_opcion = CONCAT ('listaPrecioPorcentaje_', lista_precios.id)";
            $data = $this->connection->query($query);
            return $data->result_array();
		}
	public function activa_lista($where,$orwhere){
		if(!empty($where)){
			$this->connection->where($where);
		}
		if(!empty($orwhere)){
			$this->connection->where($orwhere);
		}
		
		$this->connection->select("*");
        $this->connection->from("lista_precios");
		$query = $this->connection->get();
		//echo $this->connection->last_query(); 
        return $query->result();
	}
	

  }
?>