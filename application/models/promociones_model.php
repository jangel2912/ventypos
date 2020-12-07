<?php

class Promociones_model extends CI_Model {

	var $connection;

    public function __construct()
    {
		parent::__construct();
    }

    public function initialize($connection)
    {
		$this->connection = $connection;
	}

    public function getAjaxData($start,$limit)
    {
    	$aColumns = array('nombre', 'dias', 'fecha_inicial', 'fecha_final', 'activo','tipo','id');
        $sIndexColumn = "id";
        $sTable = "promociones";

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
			FROM $sTable 
			$sWhere  
			$sOrder
			LIMIT ".$start.",".$limit."
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
			//"sEcho" => intval($_GET['sEcho']),
			"iTotalRecords" => $iTotal,
			"iTotalDisplayRecords" => $iFilteredTotal,
			"aaData" => array()
		);

		foreach($rResult->result_array() as $row)
		{
			$data = array();

			for($i = 0; $i < count($aColumns) ; $i++){
				if($i == 5 && ($row['tipo'] == 'progresivo' || empty($row['tipo']))){
					$data[] = "Obsequio";
				} else {
					$data[] = $row[ $aColumns[$i] ];
				}
				
			}

			$output['aaData'][] = $data;
		}

		return $output; 
	}

	public function store(array $input)
	{
		$data = [
			'nombre' => $input['nombre'],
			'dias' => implode(',', $input['dias']),
			'fecha_inicial' => $input['fecha_inicial'],
			'fecha_final' => $input['fecha_final'],
			'hora_inicio' => $input['hora_inicial'],
			'hora_fin' => $input['hora_final'],
			'tipo' => $input['tipo'],
			'activo' => isset($input['activo']) ? 1 : 0
		];

		$this->connection->insert('promociones', $data);
		$id = $this->connection->insert_id();

		$this->sync_almacenes(explode(',', $input['almacenes']), $id);

		return $id;
	}

	public function update(array $input)
	{
		$data = [
			'nombre' => $input['nombre'],
			'dias' => implode(',', $input['dias']),
			'fecha_inicial' => $input['fecha_inicial'],
			'fecha_final' => $input['fecha_final'],
			'hora_inicio' => $input['hora_inicial'],
			'hora_fin' => $input['hora_final'],
			'tipo' => isset($input['tipo']) && !empty($input['tipo']) ? $input['tipo'] : "cantidad",
			'activo' => isset($input['activo']) ? 1 : 0
		];

		$this->connection->where('id', $input['id'])
					->update('promociones', $data);

		$this->sync_almacenes(explode(',', $input['almacenes']), $input['id']);

		return $input['id'];
	}

	public function delete($id)
	{
		$res = $this->connection->delete('promociones', ['id' => $id]);
		print_r($res);
		die;
		return $res;
	}

	public function sync_almacenes(array $almacenes, $id)
	{
		$i_almacenes = [];

		$this->connection->delete('promociones_almacenes', array('id_promocion' => $id)); 

		foreach($almacenes as $almacen)
		{
			array_push($i_almacenes, [
				'id_promocion' => $id,
				'id_almacen' => $almacen
			]);
		}

		if(count($i_almacenes) > 0) @$this->connection->insert_batch('promociones_almacenes', $i_almacenes);
	}

	public function get($id)
	{
		$promocion = $this->connection->select('*')
									->where('id', $id)
									->get('promociones');

		$almacenes = $this->connection->select('*')
									->where('id_promocion', $id)
									->get('promociones_almacenes');

		$promo = $promocion->first_row();
		$promo->almacenes = @$almacenes->result();

		return $promo;
	}
        public function getId($id)
        {
            $promocion = $this->connection->select('*')
		->where('id', $id)
                ->get('promociones');
            return $promocion->row_array();
        }

	public function obtenerHabilitados($almacen=0)
	{
		$fecha = date('Y-m-d');
		$hora = date('H:i:s');
		$dia = date('N');

		$query = 'SELECT * FROM promociones JOIN promociones_almacenes ON promociones.id = promociones_almacenes.id_promocion AND promociones_almacenes.id_almacen = '.$almacen.' WHERE (? BETWEEN fecha_inicial AND fecha_final) AND (? BETWEEN hora_inicio AND hora_fin) AND dias LIKE "%'.$dia.'%" AND activo = 1';		
                $promociones = $this->connection->query($query, [$fecha, $hora, $dia])->result();

		return $promociones;
	}

	public function obtener($id_promocion)
	{
		return  $this->connection->get_where('promociones',array('id'=> $id_promocion))->row();
	}

	public function obtenerDetallePromocion($id_promocion)
	{
		$promocion = $this->connection->select('*')
                    ->where('id_promocion', $id_promocion)
                    ->order_by('producto_pos', 'asc')
                    ->get('promociones_descripcion');
                
		return $promocion->result();
	}

	public function productos($id_promocion)
	{
		$query = 'SELECT p.*, i.*, IF(ISNULL(pr_p.id), 0, 1) AS activo_promocion FROM producto p LEFT JOIN impuesto i ON p.`impuesto` = i.`id_impuesto` LEFT JOIN promociones_productos pr_p ON pr_p.`id_producto` = p.`id` AND pr_p.`id_promocion` = '.$id_promocion.' ORDER BY activo_promocion DESC, nombre ASC';
		$productos = $this->connection->query($query);

		return $productos->result_array();
	}


	public function productospromocion($search=null,$id_promocion,$start,$limit)
	{	

		if($search != null)
		$search = "and p.nombre like '%$search%' or p.codigo like '%$search%'";
		else
			$search = '';  

		$query = 'SELECT p.*, i.*, IF(ISNULL(pr_p.id), 0, 1) AS activo_promocion FROM producto p LEFT JOIN impuesto i ON p.`impuesto` = i.`id_impuesto` LEFT JOIN promociones_productos pr_p ON pr_p.`id_producto` = p.`id` AND pr_p.`id_promocion` = '.$id_promocion.' '.$search.' ORDER BY activo_promocion DESC, nombre ASC LIMIT '.$start.','.$limit.' ';
		

		$data = array();
        foreach ($this->connection->query($query)->result() as $value) {
            $data[] = array(
                $value->nombre,
                $value->codigo,
                $value->precio_compra,
                $value->precio_venta,
                $value->nombre_impuesto,
                $value->id,
                'activo_promocion' => $value->activo_promocion,

            );
        }

        return array(
            'aaData' => $data
        );

	}

        
        public function productosDescuento($id_promocion)
	{
            $query = 'SELECT p.*, i.*, IF(ISNULL(pr_p.id), 0, 1) AS activo_promocion FROM producto p LEFT JOIN impuesto i ON p.`impuesto` = i.`id_impuesto` LEFT JOIN promocionesProductosDescuento pr_p ON pr_p.`id_producto` = p.`id` AND pr_p.`id_promocion` = '.$id_promocion.' ORDER BY activo_promocion DESC, nombre ASC';
            $productos = $this->connection->query($query);

            return $productos->result_array();
	}
        
        public function existePromocionesPD($db)
        {
            $sql = "SHOW TABLES WHERE Tables_in_$db = 'promocionesProductosDescuento'";
            return $this->connection->query($sql);
        }
        
        public function crearPromocionesPD($db)
        {
            $sql = "
                CREATE TABLE IF NOT EXISTS `promocionesProductosDescuento` (
                    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                    `id_promocion` bigint(20) unsigned NOT NULL,
                    `id_producto` int(11) NOT NULL,
                    PRIMARY KEY (`id`)
                  )";
            return $this->connection->query($sql);
        }

	public function sync_productos($data)
	{
		$productos = substr($data['productos'], 0, strlen($data['productos']) - 1);
		$id = $data['id'];
		
		//Check if promo exist
		if(!empty($this->getId($id))){
			if($data['accion'] == 1)
			{
				$this->connection->delete('promociones_productos', array('id_promocion' => $id));
			}else
			{
				$this->connection->delete('promocionesProductosDescuento', array('id_promocion' => $id));
			}

			if(strlen($productos) > 0)
			{
				$array_productos = explode(',', $productos);
				$i_productos = [];

				foreach ($array_productos as $p) {
					//Verify product exist
					$producto = $this->connection->select('*')
						->where('id', $p)
						->get('producto')
						->row_array();
					if(!empty($producto)){
						if($data['accion'] == 1)
						{
							$this->connection->insert('promociones_productos',array('id_promocion' => $id,'id_producto' => $p));
						}else
						{
							$this->connection->insert('promocionesProductosDescuento',array('id_promocion' => $id,'id_producto' => $p));
						}   	
					}
				}
			}
		}
		return true;
	}

	public function reglas($id_promocion)
	{
		$reglas = $this->connection->select('*')
                    ->where('id_promocion', $id_promocion)
                    ->get('promociones_descripcion');
		return $reglas->row_array();
	}
        public function reglasAll($id_promocion)
	{
            $reglas = $this->connection->select('*')
                ->where('id_promocion', $id_promocion)
                ->get('promociones_descripcion');
            return $reglas->result_array();
	}
        
        public function addReglas($data)
        {
            $id = $data['id_promocion'];
            $this->connection->delete('promociones_descripcion', array('id_promocion' => $id));
            //var_dump($data);die;
            $this->connection->insert_batch('promociones_descripcion', $data);

            return true;
        }

	public function sync_reglas($data)
	{
            $id = $data['id_promocion'];
            $registro = $this->connection->get_where('promociones_descripcion', array('id_promocion' => $id));
            if(!empty($registro->result_array()))
            {   
                $this->connection->where('id_promocion', $id)
                    ->update('promociones_descripcion', $data);
            }else{
                $this->connection->insert('promociones_descripcion',$data);
            }
	}
        
    public function sync_reglasTipo($reglas)
	{
		$id = $reglas['id_promocion'];

		$this->connection->delete('promociones_descripcion', array('id_promocion' => $id));

		$this->connection->insert('promociones_descripcion',$reglas);
	}
        
	public function sync_reglasAll($data)
	{
		$reglas = $data['reglas'];
		$id = $data['id'];

		$this->connection->delete('promociones_descripcion', array('id_promocion' => $id));

		$i_reglas = [];
		for($i=0; $i<$reglas; $i++)
		{
			array_push($i_reglas, [
				'id_promocion' => $id,
				'producto_pos' => $data['pos_'.$i],
				'cantidad' => $data['cantidad_'.$i],
				'descuento' => $data['descuento_'.$i],
				'tipo' => $data['tipo_'.$i]
			]);
		}

		if(count($i_reglas) > 0) @$this->connection->insert_batch('promociones_descripcion', $i_reglas);
		
		return true;
	}

	public function validarProducto($id_promocion, $id_producto)
	{
		$promocion = $this->connection->select('*')
									->where('id_producto', $id_producto)
									->where('id_promocion', $id_promocion)
									->get('promociones_productos');

		return $promocion->first_row();
	}

        public function validarProductoD($id_promocion, $id_producto)
	{
		$promocion = $this->connection->select('*')
                    ->where('id_producto', $id_producto)
                    ->where('id_promocion', $id_promocion)
                    ->get('promocionesProductosDescuento');

		return $promocion->first_row();
	}
}