<?php

//ultima actualizacion 2016-01-25 

class devoluciones_model extends CI_Model

{

    var $connection;

    // Constructor

    public function __construct(){

        parent::__construct();

    }

    

    public function initialize($connection) {

        $this->connection = $connection;

    }

    public function existeDevoluciones($db)
    {
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'devoluciones'";
        $existe = $this->connection->query($sql)->result();
        if(count($existe) == 0)
        {
            $sql = "CREATE TABLE `devoluciones` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `fecha` date DEFAULT NULL,
                    `movimiento_id` int(11) DEFAULT NULL,
                    `factura` varchar(50) DEFAULT NULL,
                    `valor` float DEFAULT NULL,
                    `cliente_cedula` int(11) DEFAULT NULL,
                    `cliente_id` int(11) DEFAULT NULL,
                    `usuario_id` int(11) DEFAULT NULL,
                    PRIMARY KEY (`id`)
                )";
            $this->connection->query($sql);
        }
        $sql = "SHOW COLUMNS FROM detalle_venta LIKE 'status'";
        $existeCampo = $this->connection->query($sql)->result();
        if(count($existeCampo) == 0)
        {
            $sql = "ALTER TABLE `detalle_venta`   
                    ADD COLUMN `status` INT(1) NULL AFTER `producto_id`";
            $this->connection->query($sql);
        }
    }

       public function get_ajax_data($estado = 0){
                  
        $aColumns = array('id', 'fecha', 'factura', 'valor', 'cliente_id', 'usuario_id','id');

        $sIndexColumn = "id";

        $sTable = "devoluciones";
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
			FROM $sTable 
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
                            if($i == 3)
                            {
                                $data[] = $this->opciones_model->formatoMonedaMostrar($row[$aColumns[$i]]);
                                //$data[] = $row[ $aColumns[$i] ];
                            }else if($i == 4)
                            { 
                                if($row[$aColumns[$i]] != "-1")
                                { //var_dump($row[$aColumns[$i]]);die;
                                    $cliente = $this->connection->get_where("clientes",array('id_cliente'=>$row[$aColumns[$i]]))->row();
                                    
                                    if(count($cliente) != 0)
                                    {
                                        $data[] = $cliente->nombre_comercial." (".$cliente->nif_cif.")";
                                    }else
                                    {
                                        $data[] = "";
                                    }
                                    
                                }else
                                {
                                    $data[] = "";
                                }
                            }else if($i == 5)
                            {
                                $usuario = $this->db->get_where("users",array("id"=>$row[ $aColumns[$i] ]))->row();
                                $data[] = isset($usuario->username)?$usuario->username:'';
                            }else
                            {
                                $data[] = $row[ $aColumns[$i] ];
                            }
			
			$output['aaData'][] = $data;
		}
		return $output;

        }
        
    public function detalle_venta ($venta_id, $producto_id,$serial)
    {
        if($serial != 0){
            $this->connection->select("dv.*");
            $this->connection->from("detalle_venta dv");
            $this->connection->join("producto_seriales ps","dv.id = ps.id_detalle_venta");
            $this->connection->where("ps.serial_vendido",1);
            $this->connection->where("ps.serial",$serial);
            $this->connection->where("dv.venta_id",$venta_id);
            $this->connection->where("dv.producto_id",$producto_id);
            $result = $this->connection->get();
            return $result->result_array()[0];
        }else{
            return $this->connection->get_where('detalle_venta', [
                "venta_id" => $venta_id,
                "producto_id" => $producto_id
            ])->result_array()[0];
        }
        
    }

    public function detalle_venta_by_id( $venta_id,$id_detalle_venta, $producto_id,$serial)
    {
        if($serial != 0){
            $this->connection->select("dv.*");
            $this->connection->from("detalle_venta dv");
            $this->connection->join("producto_seriales ps","dv.id = ps.id_detalle_venta");
            $this->connection->where("ps.serial_vendido",1);
            $this->connection->where("ps.serial",$serial);
            $this->connection->where("dv.venta_id",$venta_id);
            $this->connection->where("dv.producto_id",$producto_id);
            $result = $this->connection->get();
            return $result->result_array()[0];
        }else{
            return $this->connection->get_where('detalle_venta', [
                "id" => $id_detalle_venta,
                "venta_id" => $venta_id,
                "producto_id" => $producto_id
            ])->result_array()[0];
        }
        
    }
    
    public function updateDetalleVenta($id,$data)
    {
        $this->connection->where("id",$id)->update("detalle_venta",$data);
    }

    public function obtener ($where=[])
    {
        return $this->connection->get_where('devoluciones', $where)->result_array();
    }

    public function guardar_movimiento ($data=false)
    {
        if (!$data) return false;
        print_r($data);

        return $this->connection->insert('movimiento_inventario', $data);
    }

    public function producto ($id='')
    {
        return $this->connection->get_where("producto", ['id' => $id])->result_array();
    }
    
    public function productos($data = 0){
        $venta = $this->connection->get_where("venta", ['id' => $data]);
        $venta_result = $venta->result_array();
        $sql = "select * from detalle_venta where venta_id = $data and descripcion_producto <> '-1'";
        //echo $sql;die;
        //$query = $this->connection->where("venta_id",$data)->where("descripcion_venta <>","-1")->from("detalle_venta")->get();
        $query = $this->connection->query($sql);
        $result = [];
        $i = 0;
        //var_dump($query->result_array());die();

        //decimales? decimales_moneda
        $ocp = "SELECT id, nombre_opcion, valor_opcion FROM `opciones`  where nombre_opcion = 'decimales_moneda' ";
        $ocpresult = $this->connection->query($ocp)->result(); 
        foreach ($ocpresult as $dat) {
            $decimales_moneda = $dat->valor_opcion;
        }

        foreach ($query->result_array() as $factura) {
            if(is_null($factura['producto_id'])){
                continue;
            }
            $produto = $this->connection->select('id, imagen, nombre, codigo, precio_compra, precio_venta, impuesto')->get_where('producto', ['id' => $factura['producto_id']]);
            


            $impuesto = $this->connection->get_where('impuesto',['porciento'=>$factura['impuesto']])->row_array();
            //var_dump($factura['impuesto']);
           // $impuesto = $impuesto->row_array();
            //var_dump($impuesto);
            $detalle = $produto->row_array();
            
            $detalle_venta_id = $factura["id"];
            $venta_id = $factura["venta_id"];
            $cod_producto = $factura["producto_id"];

            $sql_imei = "SELECT * FROM producto_seriales ps WHERE id_producto = '$cod_producto' AND id_venta = '$venta_id' AND id_detalle_venta = '$detalle_venta_id' AND serial_vendido = 1 LIMIT 1"; 
            $result_imei = $this->connection->query($sql_imei);

            if($result_imei->num_rows() == 1){
                $detalle_imei = $result_imei->row_array();
                $detalle["imei"] = $detalle_imei["serial"]; 
            }else{
                $detalle["imei"] = "No aplica";
            } 


            $precioTotal = $factura['precio_venta']+($factura['precio_venta']*$factura['impuesto']/100);
            $precioDescuento = $factura['descuento']+($factura['descuento']*$factura['impuesto']/100);
            
            if($decimales_moneda==0){
                $precioTotal = ((float)$precioTotal-(float)$precioDescuento);
            }
            else{
                $precioTotal = ((float)$precioTotal-(float)$precioDescuento);
            }
            
            //$precioTotal = ($factura['precio_venta']*$impuesto['porciento']/100)-($factura['descuento']*$impuesto['porciento']/100);
            if($factura['descripcion_producto'] !== 0)
            {
                $json = json_decode($factura['descripcion_producto']);
                if(isset($json->modificacionDevolucion) && $json->modificacionDevolucion == 1)
                {
                    $factura['unidades'] = $json->cantidadSindevolver;
                }
            }
            

            if($factura['unidades'] != 0)
            {
                $imagen = (isset($detalle['imagen'])) ? $detalle['imagen'] : "";
                /*if(isset($detalle['imagen']))
                {*/
                    $result[] = array(
                        $imagen,
                        $detalle['nombre'],
                        $detalle['codigo'],
                        $detalle['imei'],
                        $precioTotal,
                        //($precioTotal+($precioTotal*$impuesto['porciento']/100)),
                        $factura['descuento'],
                        $impuesto['nombre_impuesto']." (".$impuesto['porciento'].")",
                        $factura['unidades'] ?: 0,
                        $factura['unidades'] ?: 0,
                        $detalle['id'],
                        $venta_result[0]['almacen_id'],
                        $factura['status'],
                        $detalle_venta_id
                        
                    );
                //}
                
            }
            ++$i;
        }
        return $result;
    }

    public function update_imei($data_imei){

        /*$data = array(
            'serial_vendido' => 0,
            'id_venta' => NULL,
            'id_detalle_venta' => NULL
        );*/

        $data = array(
            'serial_vendido' => 0
        );

        $this->connection->where('id_producto',$data_imei["producto_id"]);
        $this->connection->where('id_venta', $data_imei["venta_id"]);
        $this->connection->where('id_detalle_venta', $data_imei["id"]);
        $this->connection->update("producto_seriales",$data);
    }


    public function guardar_devolucion ($data=false)
    {
        if ($data) {
            $venta_query = $this->connection->get_where('venta', ['id' => $data['venta_id']]);
            $venta = $venta_query->result_object();
            $factura = $venta[0]->factura;
            $cliente_id = $venta[0]->cliente_id;
            $cliente_query = $this->connection->get_where('clientes', ['id_cliente' => $cliente_id]);
            $cliente = $cliente_query->result_object();
            $cliente_cedula = $cliente[0]->nif_cif;

            $item = [
                "fecha" => $data['fecha'],
                "movimiento_id" => $data['movimiento_id'],
                "factura" => $factura,
                "valor" => $data['valor'],
                "cliente_cedula" => $cliente_cedula,
                "cliente_id" => $cliente_id,
                "usuario_id" => $this->session->userdata('user_id')
            ];

            
            $this->connection->insert('devoluciones', $item); 
            return $this->connection->insert_id();
        }

        return false;
    }

    public function get_by_id($id = 0)
    {
        $query = $this->connection->get_where("devoluciones",array("id"=>$id));
        return $query->row();                       
    }
    
    public function detalleDevolucion($id = 0)
    {
        return array(
            "movimiento" => $this->connection->get_where("movimiento_inventario",array("id"=>$id))->row(),
            "detalle" => $this->connection->get_where("movimiento_detalle",array('id_inventario'=>$id))->result(),
        );
    }
  
    public function delete($id)
    {   
        $this->connection->where('id', $id);
        $this->connection->delete("devoluciones");
    }
    
    public function cambiar_status ($data=false)
    {
        if ($data) {
            return $this->connection->where('venta_id', $data['venta_id'])
                ->where('producto_id', $data['producto_id'])
                ->update('detalle_venta', array('status' => $data['status']));
        }

        return false;
    }
    
    public function facturaSindevolucion($id)
    {
        $factura = $this->connection->get_where("venta",array("id"=>$id))->row();
        if(isset($factura->factura))
        {
            return $this->connection->get_where("devoluciones",array("factura"=>$factura->factura))->result();
        }else
        {
            return array();
        }
        
    }
}
?>