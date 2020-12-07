<?php

// Proyecto: Sistema Facturacion

// Version: 1.0

// Programador: Jorge Linares

// Framework: Codeigniter

// Clase: Productos



class Ingredientes_model extends CI_Model{

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

		//$query = $this->connection->query("SELECT count(*) as cantidad FROM  producto s Inner Join impuestos i on s.id_impuesto = i.id_impuesto");

        $query = $this->connection->query("SELECT count(*) as cantidad FROM  producto s");

		return $query->row()->cantidad;								

	}

	

	public function get_all($offset)

	{

		$query = $this->connection->query("SELECT * FROM producto s Inner Join impuestos i on s.id_impuesto = i.id_impuesto ORDER BY id_producto DESC limit $offset, 8");

		return $query->result();

	}

        

        public function get_ajax_data(){

           /* $aColumns = array('imagen', 'nombre', 'codigo', 'precio_compra', '(select nombre from unidades where id = unidad_id) as unidad_id', 'nombre_impuesto', 'id');*/
           $aColumns = array('imagen', 'nombre', 'codigo', 'precio_compra', 'unidad_id', 'nombre_impuesto', 'id');

            $sIndexColumn = "id";

            $sTable = "producto";

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

        //Tipo ingrediente
        if($sWhere=='')
            $sWhere = 'where material=1'; 
        else
            $sWhere = $sWhere.' AND material =1 ';
            
        //se modificó la consulta para que diera los valores reales
        $sQuery = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
    		FROM   $sTable s Left Join impuesto i on s.impuesto = i.id_impuesto
    		$sWhere 
    		$sOrder
    		$sLimit";
           /*echo $sQuery;die;*/
            $rResult =  $this->connection->query($sQuery);
            /* Data set length after filtering */
            $sQuery = "SELECT FOUND_ROWS() as cantidad";
            $rResultFilterTotal = $this->connection->query($sQuery);
            //$aResultFilterTotal = $rResultFilterTotal->result_array();
            $iFilteredTotal = $rResultFilterTotal->row()->cantidad;    
            $sQuery = "SELECT COUNT(`".$sIndexColumn."`) as cantidad FROM $sTable where material=1";
            $rResultTotal = $this->connection->query($sQuery);
            $iTotal = $rResultTotal->row()->cantidad;   
            $output = array(
                "sEcho" => intval($_GET['sEcho']),
                "iTotalRecords" => $iTotal,
                "iTotalDisplayRecords" => $iFilteredTotal,
                "aaData" => array()
            );

            

           

            foreach($rResult->result_array() as $row){

                $data = array();

                for($i = 0; $i<count($aColumns) ; $i++){

                    if($i==4)//Unidades (gramos,onza, etc).
                    {
                        $unidades_query = $this->connection->query("SELECT nombre FROM  unidades where id= ".$row[ $aColumns[$i] ]);
                        $row[ $aColumns[$i] ] = $unidades_query->row()->nombre;
                    }else if($i == 3)
                    {
                        $row[ $aColumns[$i] ] =  $this->opciones_model->formatoMonedaMostrar($row[ $aColumns[$i] ]);
                    }/*else{
                        $data[] = $row[ $aColumns[$i] ];
                    }*/$data[] = $row[ $aColumns[$i] ];
                }

                /*var_dump($data);*/
                $output['aaData'][] = $data;

            }

            return $output; 

           

        }

        

        public function get_by_name($name){

            $query = $this->connection->query("select id from producto where nombre = '$name'");

            if($query->num_rows() > 0){

//                return $query->row()->id_producto;// Leonardo: Cambio por error en aplicacion
                return $query->row()->id;

            }

            

            return "";

        }

	

	/*public function get_term($q='')

	{

		

		$query = $this->connection->query("SELECT id_producto as id, nombre, precio, i.nombre_impuesto, i.porciento, descripcion FROM producto s Inner Join impuestos i on s.id_impuesto = i.id_impuesto WHERE nombre LIKE '%$q%' LIMIT 0,30");

		return $query->result_array();

	}*/


    public function get_by_codigo($codigo, $usuario){

        $query = $this->connection->query("select producto.imagen, producto.nombre, producto.codigo, precio_compra, precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id from producto left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id left join impuesto on impuesto.id_impuesto = producto.impuesto where codigo_barra = '$codigo' and usuario_almacen.usuario_id = $usuario");

        if($query->num_rows() > 0){

            return $query->row_array();

        }

        return null;

    }

    public function get_by_category($categoria, $usuario){

        if($categoria!=0)
            $query = $this->connection->query("select producto.imagen, producto.nombre, producto.codigo, precio_compra, precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id from producto left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id left join impuesto on impuesto.id_impuesto = producto.impuesto where categoria_id = '$categoria' and usuario_almacen.usuario_id = $usuario");
        else
            $query = $this->connection->query("select producto.imagen, producto.nombre, producto.codigo, precio_compra, precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id from producto left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id left join impuesto on impuesto.id_impuesto = producto.impuesto where  usuario_almacen.usuario_id = $usuario");
        
        if($query->num_rows() > 0){

            return $query->result();

        }

        return null;
    }


    public function get_term($q='',$usuario)
    {
            $str_query = "select impuesto.id_impuesto,producto.nombre, producto.codigo, producto.precio_compra, producto.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id from producto inner join categoria on categoria.id = producto.categoria_id left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id left join impuesto on impuesto.id_impuesto = producto.impuesto  where (upper(producto.nombre) like '%".strtoupper($q)."%' OR upper(categoria.nombre) like '%".strtoupper($q)."%') and usuario_almacen.usuario_id = $usuario AND producto.material= 1 ORDER BY nombre";
            $query = $this->connection->query($str_query);
            return $query->result();
    }


	

	public function get_by_id($id = 0)

	{

		$query = $this->connection->query("SELECT * FROM  producto WHERE id = '".$id."'");

		

		return $query->row_array();								

	}

	

	public function add($data, $usuario)

	{	
         /*       var_dump($data);
                var_dump($_POST['Stock']);
                die();
*/
                $this->connection->insert("producto", $data);

                $id = $this->connection->insert_id();

                //$almacenes = $this->connection->query('select * from almacen');

                $data_stock_actual = array();

                $data_stock_diario = array();

                foreach ($_POST['Stock'] as $key => $value) {

                    $data_stock_actual[] = array(

                            'almacen_id' => $key,

                            'producto_id' => $id,

                            'unidades' => $value

                    );

                    if($value > 0){

                        $data_stock_diario[] =  array(

                                'producto_id' =>$id

                              , 'almacen_id' => $key

                              , 'fecha' => date('Y-m-d')

                              , 'unidad' => $value

                              , 'precio' => $data['precio_venta']

                              //, 'cod_documento' => "Adicion"

                              ,'usuario' => $usuario

                              , 'razon' => 'E'

                            );

                    }

                }
                if(!empty($data_stock_actual)){
                    $this->connection->insert_batch('stock_actual', $data_stock_actual);    
                }
                if(!empty($data_stock_diario)){
                    $this->connection->insert_batch('stock_diario', $data_stock_diario);
                }

                

                /*echo $this->connection->last_query();

                die;*/

                //$array_datos['id_producto'] = $this->connection->insert_id();

                //return $array_datos;

	}

	

	public function update($data, $usuario){			

		$this->connection->where('id', $data['id']);

		$this->connection->update("producto", $data);

        $data_stock_diario = array();
        

        foreach ($_POST['Stock'] as $key => $value) {

            if($value > 0){

               $values_actual =  $this->connection->where('almacen_id', $key)

                    ->where('producto_id', $data['id'])

                    ->get('stock_actual')->row();

                $this->connection->where('almacen_id', $key)

                    ->where('producto_id', $data['id'])

                    ->update('stock_actual', array('unidades' => $value + $values_actual->unidades ));

            

                $data_stock_diario[] =  array(

                        'producto_id' =>$data['id']

                      , 'almacen_id' => $key

                      , 'fecha' => date('Y-m-d')

                      , 'unidad' => $value

                      , 'precio' => $data['precio_venta']

                      //, 'cod_documento' => "Actualizacion"

                      ,'usuario' => $usuario

                      , 'razon' => 'E'

                    );

            }

        }
        if(!empty($data_stock_diario)){
            $this->connection->insert_batch('stock_diario', $data_stock_diario);            
        }
        
	}

	

	public function delete($id)

	{	

		$this->connection->where('id', $id);

		$this->connection->delete("producto");	

	}

        

        public function excel(){

            //$this->connection->select("id_producto, nombre, descripcion, precio, nombre_impuesto, porciento");

            //$this->connection->from("productof");

            //$this->connection->join('impuesto', 'impuesto.id_impuesto = productof.id_impuesto');

            $str_query = "select * from producto inner join impuesto i on  producto.impuesto = i.id_impuesto";

            

            $query = $this->connection->query($str_query);

            return $query->result();

        }

        

        public function excel_exist($nombre, $precio){

           

           $this->connection->where("nombre", $nombre);

           $this->connection->where("precio", $precio);

           $this->connection->from("producto");

           $this->connection->select("*");

            

            $query = $this->connection->get();

            if($query->num_rows() > 0){

                return true;

            }

            else {

                return false;

            }   

        }


    public function get_term_existencias($q, $almacen)

    {   

            $sql = "SELECT p.id, s.unidades, p.codigo, p.nombre, p.precio_compra from producto p inner join stock_actual s on s.producto_id = p.id WHERE (p.nombre LIKE '%$q%' or p.codigo LIKE '%$q%') and s.almacen_id = $almacen LIMIT 0,30";

            $query = $this->connection->query($sql);

            return $query->result_array();

    }

        

        public function excel_add($array_datos){

            $query = "INSERT INTO `producto` (`nombre`, `descripcion`, `precio`, `id_impuesto`) VALUES ('".$array_datos['nombre']."', '".$array_datos['descripcion']."', ".$array_datos['precio'].", ".$array_datos['id_impuesto'].");";

            $this->connection->query($query);

        }
        
        public function eliminarConfirmacion($id)
        {
            $combos = $this->connection->get_where("producto_combos",array("id_producto"=>$id))->result();
            $ingredientes = $this->connection->get_where("producto_ingredientes",array("id_ingrediente"=>$id))->result();

            if(count($combos) != 0 || count($ingredientes) != 0)
            {
                return 2;
            }

            return 1;
        }

        public function get_productos_por_ingrediente($id_ingrediente){
            $productos = $this->connection->get_where("producto_ingredientes",array("id_ingrediente"=>$id_ingrediente) );
            return $productos->result();
        }


        public function get_ingrediente($where){
            $this->connection->where($where);
            $this->connection->select('producto.*,unidades.nombre as nombre_unidad');
            $this->connection->from('producto');
            $this->connection->join('unidades','unidades.id=producto.unidad_id');
            $query = $this->connection->get();
            return $query->result();
        }

}

?>