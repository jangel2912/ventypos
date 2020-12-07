<?php

class Atributos_model extends CI_Model {

    var $connection;

    // Constructor

    public function __construct() {

        parent::__construct();      
    }

    public function initialize($connection) {

        $this->connection = $connection;
    }

    /* --------------------------------------------------------- */    

    // Solo categorias que tengan atributos relacionados
    public function queryPivote( $atributos ) {
        
        $lista = array();
        
        if($atributos["marca"] > 0){
            $val = $atributos["marca"];
            $lista[] = " AND pivot.id_marca = '$val'";
        }
        if($atributos["color"] > 0){
            $val = $atributos["color"];
            $lista[] = " AND pivot.id_color = '$val'";
        }
        if($atributos["talla"] > 0){
            $val = $atributos["talla"];
            $lista[] = " AND pivot.id_talla = '$val'";
        }
        if($atributos["proveedor"] > 0){
            $val = $atributos["proveedor"];
            $lista[] = " AND pivot.id_proveedor = '$val'";
        }
        if($atributos["material"] > 0){
            $val = $atributos["material"];
            $lista[] = " AND pivot.id_materiales = '$val'";
        }
        if($atributos["linea"] > 0){
            $val = $atributos["linea"];
            $lista[] = " AND pivot.id_lineas = '$val'";
        }
        if($atributos["almacen"] > 0){
            $val = $atributos["almacen"];
            $lista[] = " AND almacen_id = '$val'";
        }        
        if($atributos["categoria"] > 0){
            $val = $atributos["categoria"];
            $lista[] = " AND categoria_id = '$val'";
        }           
        if($atributos["tipo"] > 0){
            $val = $atributos["tipo"];
            $lista[] = " AND id_tipo = '$val'";
        }        
      
        
        
        if( count($lista) >0 ){
            $first = str_replace('AND ', '', $lista[0]);
            $lista[0] = $first;                    
        }
        
        
        
        $query = " 

SELECT pivot.*, sk.unidades ,alm.nombre AS nombre_almacen, (pivot.precio_compra * sk.unidades) AS vr_inventario FROM(
	SELECT pro.*, p.referencia, p.codigo_interno, p.nombre_producto, p.nombre_categoria,
        MAX(IF( p.nombre_atributo = 'Marca', p.id_clasificacion, NULL)) AS id_marca,
        MAX(IF( p.nombre_atributo = 'Marca', p.nombre_clasificacion, NULL)) AS nombre_marca,
        MAX(IF( p.nombre_atributo = 'Color', p.id_clasificacion, NULL)) AS id_color,
	MAX(IF( p.nombre_atributo = 'Color', p.nombre_clasificacion, NULL)) AS nombre_color,        
        MAX(IF( p.nombre_atributo = 'Talla', p.id_clasificacion, NULL)) AS id_talla,
	MAX(IF( p.nombre_atributo = 'Talla', p.nombre_clasificacion, NULL)) AS nombre_talla,
        MAX(IF( p.nombre_atributo = 'Proveedor', p.id_clasificacion, NULL)) AS id_proveedor,
	MAX(IF( p.nombre_atributo = 'Proveedor', p.nombre_clasificacion, NULL)) AS nombre_proveedor,
        MAX(IF( p.nombre_atributo = 'Materiales', p.id_clasificacion, NULL)) AS id_materiales,
        MAX(IF( p.nombre_atributo = 'Materiales', p.nombre_clasificacion, NULL)) AS nombre_materiales,
        MAX(IF( p.nombre_atributo = 'Lineas', p.id_clasificacion, NULL)) AS id_lineas,
	MAX(IF( p.nombre_atributo = 'Lineas', p.nombre_clasificacion, NULL)) AS nombre_lineas,
        MAX(IF( p.nombre_atributo = 'Tipos', p.id_clasificacion, NULL)) AS id_tipo,
	MAX(IF( p.nombre_atributo = 'Tipos', p.nombre_clasificacion, NULL)) AS nombre_tipos
	FROM atributos_productos p
	INNER JOIN producto AS pro ON p.codigo_interno = pro.codigo_barra
	GROUP BY p.codigo_interno
) AS pivot
INNER JOIN stock_actual AS sk ON pivot.id = sk.producto_id
INNER JOIN almacen AS alm ON almacen_id = alm.id
" ;

        if(count($lista)>0){
            
            $query = $query." WHERE ";
            
            for( $i = 0; $i < count($lista); $i++){
                $query = $query."".$lista[$i];
            }            
            
        }

        $query .= 'GROUP BY id';
        
        $query = $this->connection->query($query);                
        return $query->result();        
        
    }
    


    public function ajaxAtributosExcel() {
        
        $query = " 
SELECT pivot.*, sk.unidades, sk.almacen_id ,alm.nombre AS nombre_almacen, (pivot.precio_compra * sk.unidades) AS vr_inventario FROM(
    SELECT  pro.*,pro.`id_proveedor` AS idproveedor, p.codigo_interno, p.nombre_producto, p.nombre_categoria,
        MAX(IF( p.nombre_atributo = 'Marca', p.id_clasificacion, NULL)) AS id_marca,
        MAX(IF( p.nombre_atributo = 'Marca', p.nombre_clasificacion, NULL)) AS nombre_marca,
        MAX(IF( p.nombre_atributo = 'Color', p.id_clasificacion, NULL)) AS id_color,
    MAX(IF( p.nombre_atributo = 'Color', p.nombre_clasificacion, NULL)) AS nombre_color,        
        MAX(IF( p.nombre_atributo = 'Talla', p.id_clasificacion, NULL)) AS id_talla,
    MAX(IF( p.nombre_atributo = 'Talla', p.nombre_clasificacion, NULL)) AS nombre_talla,
        MAX(IF( p.nombre_atributo = 'Proveedor', p.id_clasificacion, NULL)) AS id_proveedor1,
    MAX(IF( p.nombre_atributo = 'Proveedor', p.nombre_clasificacion, NULL)) AS nombre_proveedor,
        MAX(IF( p.nombre_atributo = 'Materiales', p.id_clasificacion, NULL)) AS id_materiales,
        MAX(IF( p.nombre_atributo = 'Materiales', p.nombre_clasificacion, NULL)) AS nombre_materiales,
        MAX(IF( p.nombre_atributo = 'Lineas', p.id_clasificacion, NULL)) AS id_lineas,
    MAX(IF( p.nombre_atributo = 'Lineas', p.nombre_clasificacion, NULL)) AS nombre_lineas,
        MAX(IF( p.nombre_atributo = 'Tipos', p.id_clasificacion, NULL)) AS id_tipo,
    MAX(IF( p.nombre_atributo = 'Tipos', p.nombre_clasificacion, NULL)) AS nombre_tipos        
    FROM atributos_productos p
    INNER JOIN producto AS pro ON p.codigo_interno = pro.codigo_barra
    GROUP BY p.codigo_interno
) AS pivot
INNER JOIN stock_actual AS sk ON pivot.id = sk.producto_id
INNER JOIN almacen AS alm ON almacen_id = alm.id
ORDER BY codigo_barra
" ;

        $query = $this->connection->query($query);                
        return $query->result();        
        
    }
    

    public function setAjaxAtributosExcel($dataObj){
    
        foreach($dataObj as $value){            
            
            $stockActual = $value->stock;
            $unidades = $value->unidades;  
            $id_user = $this->session->userdata('user_id');
            $fecha = date('Y-m-d H:i:s');
            
            
            $total = intval($value->stock)+intval($value->unidades);   
            $idProducto = $value->idProducto;
            $idAlmacen = $value->idAlmacen;
            
            $query = "
            UPDATE stock_actual
            SET unidades = $total
            WHERE producto_id = $idProducto AND almacen_id = $idAlmacen;
            ";
            
            $precioVenta = $this->connection->query(" SELECT precio_venta FROM producto WHERE id = '$idProducto' ")->row()->precio_venta;
            
            
            
            $query = $this->connection->query($query);
            
                        

            
            $row = Array(
                    "producto_id" => $idProducto,
                    "almacen_id" => $idAlmacen,
                    "fecha" => $fecha,
                    "razon" => "E",
                    "cod_documento"=> "",
                    "unidad"=>$unidades,
                    "precio"=> $precioVenta,
                    "usuario"=> $id_user,
            );

            $this->connection->insert("stock_diario", $row);

            $query = $this->connection->query($query);            
            
        
        }
        
    }
    
    
    /* --------------------------------------------------------- */

    // Solo categorias que tengan atributos relacionados
    public function getCategorias() {

        $query = '
            SELECT DISTINCT id,nombre
            FROM atributos_posee_categorias
            INNER JOIN categoria
            ON atributos_posee_categorias.categoria_id = categoria.id            
            ';

        $query = $this->connection->query($query);
        return $query->result();
    }

    public function getAtributos() {
        $query = $this->connection->get("atributos");
        return $query->result();
    }

    public function getAtributoByName($nombre) {
        $query = $this->connection->query('SELECT id, nombre FROM atributos WHERE nombre LIKE "'.$nombre.'"');
        
        if($query->row())
            return $query->row()->id;
        else
            return '';
    }

    public function create_or_get($valor, $id_atributo)
    {
        $valor = strtolower($valor);
        $id = $id_atributo;

        $q_valor = $this->connection->select('id, valor')
                        ->from('atributos_detalle')
                        ->where('TRIM(LOWER(valor)) LIKE TRIM(LOWER("'.$valor.'"))')
                        ->where('atributo_id', $id)
                        ->get();

        if ($q_valor->num_rows() > 0)
        {
            $val = $q_valor->row_array();
            return [
                'id' => $val['id'],
                'valor' => $val['valor'],
                'nueva' => '0'
            ];
        } else {
            $array = [
                'valor' => trim($valor),
                'atributo_id' => $id_atributo
            ];
            $this->connection->insert('atributos_detalle', $array);
            $insert_id = $this->connection->insert_id();

            return [
                'id' => $insert_id,
                'valor' => trim($valor),
                'nueva' => '1'
            ];
        }
    }

    public function categoria_posee_clasificacion($id_clasificacion, $id_categoria) {
        $query = $this->connection->query('SELECT * FROM atributos_posee_categorias WHERE categoria_id = '.$id_categoria.' AND atributo_id = '.$id_clasificacion);

        if($query->num_rows() > 0)
            return $query->num_rows();

        return 0;
    }

    public function getDetalleAtributos($id) {
        $detalle = $this->connection->select('id, valor, descripcion, atributo_id')
                                ->from('atributos_detalle')
                                ->where('atributo_id', $id)
                                ->get();

        return $detalle->result_array();
    }

    public function getImpuestos() {
        $query = $this->connection->get("impuesto");
        return $query->result();
    }

    public function getProveedores() {
        $query = $this->connection->get("proveedores");
        return $query->result();
    }

    // Data from table atributos_detalle
    public function getMarcas() {
        $this->connection->where('atributo_id', "1");
        $query = $this->connection->get("atributos_detalle");
        return $query->result();
    }

    public function getColores() {
        $this->connection->where('atributo_id', "3");
        $query = $this->connection->get("atributos_detalle");
        return $query->result();
    }

    public function getTallas() {
        $this->connection->where('atributo_id', "4");
        $query = $this->connection->get("atributos_detalle");
        return $query->result();
    }

    public function getLineas() {
        $this->connection->where('atributo_id', "5");
        $query = $this->connection->get("atributos_detalle");
        return $query->result();
    }

    public function getMateriales() {
        $this->connection->where('atributo_id', "6");
        $query = $this->connection->get("atributos_detalle");
        return $query->result();
    }
    
    public function getTipos() {
        $this->connection->where('atributo_id', "7");
        $query = $this->connection->get("atributos_detalle");
        return $query->result();
    }
    /* --------------------------------------------------------- */


    public function getAjaxProductoExiste($nombreAtributos){
        $query = $this->connection->query("SELECT id FROM producto WHERE nombre = '$nombreAtributos'");        
        return $query->num_rows();
    }
    
    public function getProductoAtributo($idProducto) {
        
        $data = array();
        
        $query = $this->connection->query("SELECT nombre_atributo, nombre_producto,nombre_categoria, codigo_barras, nombre_clasificacion  FROM atributos_productos WHERE codigo_interno = $idProducto");
        $data["atributos"] = $query->result();

        $query = $this->connection->query("SELECT * FROM impuesto");
        $data["impuestos"] = $query->result();        

        $query = $this->connection->query("SELECT * FROM producto WHERE codigo_barra = $idProducto");
        $data["producto"] = $query->row();
        
        
        $id = $data["producto"]->id;

        
        $queryString ='
            
            SELECT almacen_id, nombre,unidades
            FROM stock_actual
            INNER JOIN almacen
            ON stock_actual.almacen_id = almacen.id
            WHERE stock_actual.producto_id = '.$id.'
            
                ';       
                    
        $query = $this->connection->query( $queryString );
        $data["almacenes"] = $query->result();                                
                
        return $data;        
        
    }
    
    public function setProductoAtributo( $data, $idProducto, $imagen ) {

        $datos = $data->producto1;
               
        
        $listaAlmacenes = $datos->lista_almacenes;
        $cantidadAlmacenes = $listaAlmacenes->cantidad_almacenes;
        $impuesto = $datos->impuesto;

        $activo = $datos->activo;
        $tienda = $datos->tienda;
        
        $compra = $datos->compra;
        $venta = $datos->venta;
        
        
        
        $strQuery1 = "
            UPDATE producto
            SET precio_venta = $venta, precio_compra = $compra, imagen = '$imagen', activo = $activo, tienda = $tienda, impuesto = $impuesto
            WHERE codigo_barra = $idProducto;
        ";  

        
        $this->connection->query($strQuery1);
        
        
        // Id pero en stock
        $strQuery2 = "
            SELECT id FROM producto WHERE codigo_barra = $idProducto;
        ";      
        
        $idStock = $this->connection->query($strQuery2)->result();
        $idStock = $idStock[0]->id;
        
        
        for($i = 1; $i <= $cantidadAlmacenes; $i++ ){
            
            $almacen = "almacen".$i;
            $idAlmacen = $listaAlmacenes->$almacen->id;
            $unidades = $listaAlmacenes->$almacen->unidades;
            
            $strQuery3 = "
                UPDATE stock_actual
                SET unidades = $unidades
                WHERE producto_id = $idStock AND almacen_id = $idAlmacen;
            ";        
            
            $this->connection->query($strQuery3);
            
        }
        
    }    
    

    public function ajaxAlmacenes() {

        $query = $this->connection->query("SELECT id, nombre FROM almacen");
        return $query->result();
        
    }    
    
    public function ajaxCategorias() {

        $query = $this->connection->query("SELECT * FROM categoria");
        return $query->result();
        
    }    
    
    public function ajaxAtributosAdd($valor) {

        $row = Array(
            "nombre" => $valor
        );
        $this->connection->insert("atributos", $row);
    }

    public function ajaxAtributosDel($data) {

        $this->connection->delete('atributos_posee_categorias', array('atributo_id' => $data["id"]));
        $this->connection->delete('atributos_detalle', array('atributo_id' => $data["id"]));
        $this->connection->delete('atributos', array('id' => $data["id"]));
    }

    public function ajaxAtributosSet($datos) {

        $data = array(
            'nombre' => $datos["valor"]
        );

        $id = $datos["id"];

        $this->connection->where('id', $id);
        $this->connection->update('atributos', $data);
    }

    public function ajaxClasificacionAdd($valor, $atributo) {


        $row = Array(
            "valor" => $valor,
            "atributo_id" => $atributo
        );

        $this->connection->insert("atributos_detalle", $row);
    }

    public function ajaxClasificacionDel($data) {
        $attr = $this->connection->get_where('atributos_detalle',array('id'=>$data["id"]))->row();
        //var_dump($attr);echo "1";
        $productos = $this->connection->get_where('atributos_productos',array('id_atributo'=>$attr->atributo_id,'id_clasificacion'=>$attr->id))->result();
        //var_dump($productos);echo "2";
        foreach ($productos as $p)
        {
            $prod = $this->connection->get_where('producto',array('codigo'=>substr($p->codigo_barras,0,15)))->row();
            
            if(count($prod) > 0)
            {
                if(strpos($prod->nombre,"/".$p->nombre_clasificacion."/") !== false)
                {
                    $nombre = str_replace("/".$p->nombre_clasificacion."/",'/',$prod->nombre);
                }else
                {
                    $nombre = str_replace("/".$p->nombre_clasificacion,'',$prod->nombre);
                }
                
                $this->connection->where('id',$prod->id)
                    ->update('producto',array('nombre'=>$nombre));
            }
            $this->connection->delete('atributos_productos',array('id'=>$p->id));
        }
        
        $this->connection->delete('atributos_detalle', array('id' => $data["id"]));
        
    }

    public function ajaxClasificacionSet($datos) {


        $data = array(
            'valor' => $datos["valor"]
        );

        $id = $datos["id"];

        $this->connection->where('id', $id);
        $this->connection->update('atributos_detalle', $data);
    }

    public function ajaxSeleccionados($id) {
        $query = $this->connection->query("SELECT * FROM atributos_posee_categorias WHERE categoria_id = '$id'");
        return $query->result();
    }

    public function ajaxAtributos() {
        $query = $this->connection->query("SELECT * FROM atributos");
        return $query->result();
    }

    public function ajaxClasificacion($id) {
        $query = $this->connection->query("SELECT * FROM atributos_detalle WHERE atributo_id = $id");
        return $query->result();
    }

    public function atributosToCategoria($data) {

        if (count($data["atributos"]) > 0) {

            if ($data["idCategoria"] != 0) {

                //Primero eliminamos los registros existentes a esa categoria
                $this->connection->delete('atributos_posee_categorias', array('categoria_id' => $data["idCategoria"]));

                //Agregamos las relaciones
                foreach ($data["atributos"] as $key => $value) {

                    $row = Array(
                        "categoria_id" => $data["idCategoria"],
                        "atributo_id" => $value
                    );

                    $this->connection->insert("atributos_posee_categorias", $row);
                }
            }
        } else {

            //Primero eliminamos los registros existentes a esa categoria
            $this->connection->delete('atributos_posee_categorias', array('categoria_id' => $data["idCategoria"]));
        }
    }

    public function getAllCategorias() {

        $query = $this->connection->query("SELECT * FROM categoria");
        return $query->result();
    }

    public function get_attr($id) {

        $query = $this->connection->query("SELECT valor FROM atributos_detalle where id = '" . $id . "' ");
        $data = 0;
        if($query->num_rows() > 0)
        {
            foreach ($query->result() as $value) {
                $data = $value->valor;
            }
        }
        return $data;
    }

    public function setAddProductoAttr($data_productos, $data_almacen, $data_atr_prodcuto) {

        $codigoInterno = 0;
        $codigoBarra = 0;
		$codigobarraaleatoreo = 0;

        for ($i = 0; $i < count($data_atr_prodcuto["atributos"]); $i++) {

            $nodoAtributos = $data_atr_prodcuto["atributos"][$i];
            $dataAtributosProductos = Array();

            // Se intercambio codigo de barra en codigo interno

            $dataAtributosProductos["codigo_interno"] = $data_atr_prodcuto["codigo_interno"];
            $dataAtributosProductos["referencia"] = $data_atr_prodcuto["referencia_producto"];
            $dataAtributosProductos["nombre_producto"] = $data_atr_prodcuto["nombre_producto"];
            $dataAtributosProductos["codigo_barras"] = $data_atr_prodcuto["codigo_barras"];
            $dataAtributosProductos["id_categoria"] = $data_atr_prodcuto["id_categoria"];
            $dataAtributosProductos["nombre_categoria"] = $data_atr_prodcuto["nombre_categoria"];
            $dataAtributosProductos["id_atributo"] = $nodoAtributos["id_atributo"];
            $dataAtributosProductos["nombre_atributo"] = $nodoAtributos["nombre_atributo"];
            $dataAtributosProductos["id_clasificacion"] = $nodoAtributos["id_clasificacion"];
            $dataAtributosProductos["nombre_clasificacion"] = $nodoAtributos["nombre_clasificacion"];


            $this->connection->insert("atributos_productos", $dataAtributosProductos);

            // Saving codigo of new product
            $codigoInterno = $dataAtributosProductos["codigo_interno"];
            $codigoBarra = $dataAtributosProductos["codigo_barras"];
			
			$codigobarraaleatoreo = $codigobarraaleatoreo.$nodoAtributos["id_clasificacion"];
			
        }

        // Rewrite codigo(Codigo barras) in array $data for table productos
        $data_productos["codigo"] = $codigoBarra;
        $data_productos["codigo_barra"] = $codigoInterno;


        //Saving in productos
        $this->connection->insert("producto", $data_productos);
        // Capture id in productos, not codigo_interno
        $id = $this->connection->insert_id();
		
		if($data_productos["codigo"] == ''){
		$codigobarraaleatoreo = substr($codigobarraaleatoreo, 1);
        $codigo = "update producto set codigo = '".$codigobarraaleatoreo."' where id = '".$id."' ;";
        $this->connection->query($codigo);	
        $data_productos["codigo"]=$codigobarraaleatoreo;
		}	

        $cantAlmacenes = $data_almacen["lista_almacenes"]->cantidad_almacenes;

        //Save list of almacenes in stock_actual
        for ($i = 1; $i <= $cantAlmacenes; $i++) {

            $almacenes = "almacen" . $i;
            //verifico si tiene precio por almacen
            $precio_almacen = get_option('precio_almacen');
            
            if($precio_almacen==1){
                $data = array();

                $data_stock_actual = array(
                    'almacen_id' => $data_almacen["lista_almacenes"]->$almacenes->id,
                    'producto_id' => $id,
                    'unidades' => $data_almacen["lista_almacenes"]->$almacenes->unidades,
                    'precio_compra' => floatval($data_productos["precio_compra"]),
                    'precio_venta' => floatval($data_productos["precio_venta"]),
                    'stock_minimo' => 0,
                    'impuesto' => floatval($data_productos["impuesto"]),
                    'fecha_vencimiento' => 0,
                    'activo' => intval($data_productos["activo"]),
                );            
                //$this->stock_actual->update_by_product($data,$rowProduct->id);   
            }else{
                $data_stock_actual = array(
                    'almacen_id' => $data_almacen["lista_almacenes"]->$almacenes->id,
                    'producto_id' => $id,
                    'unidades' => $data_almacen["lista_almacenes"]->$almacenes->unidades
                );                
            }

            if($data_almacen["lista_almacenes"]->$almacenes->unidades>0){                
                $data_stock_diario =  array(
                    'producto_id' =>$id
                    , 'almacen_id' => $data_almacen["lista_almacenes"]->$almacenes->id
                    , 'fecha' => date('Y-m-d')
                    , 'unidad' => $data_almacen["lista_almacenes"]->$almacenes->unidades
                    , 'precio' => floatval($data_productos["precio_venta"])
                    , 'usuario' => $this->session->userdata('user_id')
                    , 'razon' => 'E'
                );

                //insertar movimiento
                $total_inventario=(floatval($data_productos["precio_compra"]) * $data_almacen["lista_almacenes"]->$almacenes->unidades);
                $this->connection->insert('movimiento_inventario', 
                    array('fecha' => date('Y-m-d H:i:s'), 
                        'almacen_id' => $data_almacen["lista_almacenes"]->$almacenes->id,
                        'tipo_movimiento' =>'entrada_producto', 
                        'user_id' => $this->session->userdata('user_id'),
                        'total_inventario' => $total_inventario,
                        'proveedor_id' => null
                    )
                );

                $id_inventario = $this->connection->insert_id();
                $data_detalles =  array(
                    'id_inventario' => $id_inventario
                    , 'codigo_barra' => $data_productos["codigo"]
                    , 'cantidad' => $data_almacen["lista_almacenes"]->$almacenes->unidades
                    , 'precio_compra' => floatval($data_productos["precio_compra"])
                    ,'existencias' => 0
                    , 'nombre' => $data_productos['nombre']
                    ,'total_inventario' => $total_inventario                    
                    , 'producto_id' =>$id
                );
                $this->connection->insert('stock_diario', $data_stock_diario);
                $this->connection->insert('movimiento_detalle', $data_detalles);
            }

            

            $this->connection->insert('stock_actual', $data_stock_actual);
            

            // This should return id stock_actual list
            // $id = $this->connection->insert_id();
        }

        // Return id of stock_actual
        return $id;
    }

    /* ======================================================================================================= */

    public function getAtributoCategoria($id) {
        $query = $this->connection->query("SELECT nombre FROM categoria where id = " . $id . " ");
        return $query->row()->nombre;
    }

    public function getNombreAtributo($id) {
        $query = $this->connection->query("SELECT nombre FROM atributos where id = " . $id . " ");
        return $query->row()->nombre;
    }

    public function getCategoriasAtributo($id) {
        $query = $this->connection->query("SELECT GROUP_CONCAT(categoria_id) as categorias FROM atributos_posee_categorias where atributo_id = ".$id);
        if($query->row())
            return $query->row()->categorias;
        else
            return '';
    }

    public function getIdPrductoAtributos() {
        $query = $this->connection->query("SELECT codigo_interno FROM atributos_productos ORDER BY codigo_interno DESC LIMIT 1");
        if ($query->num_rows() > 0)
            return $query->row()->codigo_interno;
        else
            $this->connection->query("ALTER TABLE atributos_productos AUTO_INCREMENT = 1");
        return 0;
    }

    public function getClasificacionProveedor($id) {
        $query = $this->connection->query("SELECT nombre_comercial FROM proveedores WHERE id_proveedor = " . $id . " ");
        return $query->row()->nombre_comercial;
    }

    public function getClasificacionImpuesto($id) {
        $query = $this->connection->query("SELECT nombre_comercial FROM proveedores WHERE id_proveedor = " . $id . " ");
        return $query->row()->nombre_comercial;
    }

    public function getClasificacionMarca($id) {
        $query = $this->connection->query("SELECT valor FROM atributos_detalle where id = " . $id . " ");
        return $query->row()->valor;
    }

    /* ======================================================================================================= */

    public function get_marca($id) {

        $query = $this->connection->query("SELECT valor FROM atributos_detalle where id = '" . $id . "' ");
        foreach ($query->result() as $value) {
            $data = $value->valor;
        }
        return $data;
    }

    /* -------------------------------------------------- */

    public function get_data($offset = false) {

        $query = $this->connection->query("SELECT * FROM atributos");

        return $query->result();
    }

    public function get_total() {

        $query = $this->connection->query("SELECT count(*) as cantidad FROM  productos s Inner Join impuestos i on s.id_impuesto = i.id_impuesto");

        return $query->row()->cantidad;
    }

    public function get_combo_data() {

        $data = array();

        $query = $this->connection->query("SELECT * FROM atributos ORDER BY id DESC");

        foreach ($query->result() as $value) {

            $data[$value->id] = $value->nombre;
        }

        return $data;
    }

    public function get_combo_data_stock_actual($id) {

        $query = $this->connection->query("SELECT * FROM almacen left join stock_actual on almacen.id = stock_actual.almacen_id where stock_actual.producto_id = $id ORDER BY almacen_id DESC");

        return $query->result();
    }

    public function get_all($offset = false) {

        if ($offset) {
            $limit = '';
        }

        $query = $this->connection->query("SELECT * FROM almacen $limit");

        return $query->result();
    }

    public function posee_categorias($id, $campo) {

        $where = array();
        if ($campo && $id) {
            $where[$campo] = $id;
        }

        $query = $this->connection->get_where('atributos_posee_categorias', $where);
        $result = $query->result();
        //$data_result = $this->get_by_id($result[0]->atributo_id);
        $data = array();

        foreach ($result as $key => $value) {
            $data[$value->atributo_id]['atributo'] = $this->get_by_id($value->atributo_id);
            $data[$value->atributo_id]['detalle'] = $this->get_detail($value->atributo_id);
        }


        $data['valores'] = $this->get_by_id($result[0]->atributo_id);

        return $data;
    }

    public function posee_categorias2($id, $campo) {

        $where = array();
        $where[$campo] = $id;

        $query = $this->connection->get_where('atributos_posee_categorias', $where);
        $result = $query->result();

        //$data_result = $this->get_by_id($result[0]->atributo_id);      


        $data = array();

        foreach ($result as $key => $value) {
            $data[$value->atributo_id]['atributo'] = $this->get_by_id($value->atributo_id);
            $data[$value->atributo_id]['detalle'] = $this->get_detail($value->atributo_id);
        }

        //$data['valores'] = $this->get_by_id($result[0]->atributo_id);
        //return $result;

        return $data;
    }

    public function get_detail($id_atributo) {
        $query = $this->connection->query("SELECT * FROM atributos_detalle WHERE atributo_id = $id_atributo");

        return $query->result();
    }

    public function get_all_categorias2() {
        $query = $this->connection->get('atributos_categorias');

        return $query->result();
    }

    public function get_ajax_dataTest() {


        $aColumns = array('imagen', 'codigo', 'nombre', 'id');

        $sIndexColumn = "id";

        $sTable = "categoria";

        $sLimit = "";


        if (isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1') {

            $sLimit = "LIMIT " . intval($_GET['iDisplayStart']) . ", " .
                    intval($_GET['iDisplayLength']);
        }

        $sOrder = "";

        if (isset($_GET['iSortCol_0'])) {

            $sOrder = "ORDER BY  ";

            for ($i = 0; $i < intval($_GET['iSortingCols']); $i++) {

                if ($_GET['bSortable_' . intval($_GET['iSortCol_' . $i])] == "true") {

                    $sOrder .= "`" . $aColumns[intval($_GET['iSortCol_' . $i])] . "` " .
                            ($_GET['sSortDir_' . $i] === 'asc' ? 'asc' : 'desc') . ", ";
                }
            }



            $sOrder = substr_replace($sOrder, "", -2);

            if ($sOrder == "ORDER BY") {

                $sOrder = "";
            }
        }

        $sWhere = "";

        if (isset($_GET['sSearch']) && $_GET['sSearch'] != "") {

            $sWhere = "WHERE (";

            for ($i = 0; $i < count($aColumns); $i++) {

                if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true") {

                    $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch']) . "%' OR ";
                }
            }

            $sWhere = substr_replace($sWhere, "", -3);

            $sWhere .= ')';
        }

        /* Individual column filtering */

        for ($i = 0; $i < count($aColumns); $i++) {

            if (isset($_GET['bSearchable_' . $i]) && $_GET['bSearchable_' . $i] == "true" && $_GET['sSearch_' . $i] != '') {

                if ($sWhere == "") {

                    $sWhere = "WHERE ";
                } else {

                    $sWhere .= " AND ";
                }

                $sWhere .= "`" . $aColumns[$i] . "` LIKE '%" . mysql_real_escape_string($_GET['sSearch_' . $i]) . "%' ";
            }
        }



        $sQuery = "

		SELECT SQL_CALC_FOUND_ROWS `" . str_replace(" , ", " ", implode("`, `", $aColumns)) . "`

		FROM   $sTable s 

		$sWhere  

		$sOrder

		$sLimit

            ";



        $rResult = $this->connection->query($sQuery);

        /* Data set length after filtering */

        $sQuery = "

                    SELECT FOUND_ROWS() as cantidad

            ";

        $rResultFilterTotal = $this->connection->query($sQuery);

        //$aResultFilterTotal = $rResultFilterTotal->result_array();

        $iFilteredTotal = $rResultFilterTotal->row()->cantidad;



        $sQuery = "

		SELECT COUNT(`" . $sIndexColumn . "`) as cantidad

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





        foreach ($rResult->result_array() as $row) {

            $data = array();

            for ($i = 0; $i < count($aColumns); $i++) {

                $data[] = $row[$aColumns[$i]];
            }

            $output['aaData'][] = $data;
        }

        return $output;
    }

    public function get_ajax_data() {

        $sql = "SELECT id, nombre FROM atributos";

        $data = array();
        foreach ($this->connection->query($sql)->result() as $value) {
            $data[] = array(
                $value->nombre,
                $value->id
            );
        }
        return array(
            'aaData' => $data
        );
    }

    public function get_categoriasTest($id = false) {

        if ($id) {
            $sql = "SELECT id FROM categoria where id = '" . $id . "' ";

            $data = array();
            foreach ($this->connection->query($sql)->result() as $value) {
                $id = $value->id;
            }
            return $id;
        } else {
            $sql = "SELECT id, nombre FROM categoria";

            $data = array();
            foreach ($this->connection->query($sql)->result() as $value) {
                $data[] = array(
                    $value->nombre,
                    $value->id
                );
            }
            return array(
                'aaData' => $data
            );
        }
    }

    public function get_categorias($id = false) {

        if ($id) {
            $sql = "SELECT id FROM categoria where id = '" . $id . "' ";

            $data = array();
            foreach ($this->connection->query($sql)->result() as $value) {
                $id = $value->id;
            }
            return $id;
        } else {
            $sql = "SELECT id, nombre FROM categoria";

            $data = array();
            foreach ($this->connection->query($sql)->result() as $value) {
                $data[] = array(
                    $value->nombre,
                    $value->id
                );
            }
            return array(
                'aaData' => $data
            );
        }
    }

    public function get_by_name($name) {

        $query = $this->connection->query("select id from almacen where nombre = '$name'");

        if ($query->num_rows() > 0) {

            return $query->row()->id;
        }



        return "";
    }

    public function get_term($q = '') {


        $query = $this->connection->query("SELECT id_producto as id, nombre, precio, i.nombre_impuesto, i.porciento, descripcion FROM productos s Inner Join impuestos i on s.id_impuesto = i.id_impuesto WHERE nombre LIKE '%$q%' LIMIT 0,30");

        return $query->result_array();
    }

    public function get_by_id($id = 0) {

        $query = $this->connection->query("SELECT * FROM  atributos WHERE id = '" . $id . "'");
        return $query->row_array();
    }

    public function add($data) {
        $this->connection->insert("atributos", $data);
    }

    public function update($data) {

        $this->connection->where('id', $data['id']);

        $this->connection->update("atributos", $data);
    }

    public function delete($id) {

        $this->connection->where('id', $id);

        $this->connection->delete("atributos");
    }

    public function excel() {

        $this->connection->select("id_producto, nombre, descripcion, precio, nombre_impuesto, porciento");

        $this->connection->from("productos");

        $this->connection->join('impuestos', 'impuestos.id_impuesto = productos.id_impuesto');

        $query = $this->connection->get();

        return $query->result();
    }

    public function excel_exist($nombre, $precio) {



        $this->connection->where("nombre", $nombre);

        $this->connection->where("precio", $precio);

        $this->connection->from("productos");

        $this->connection->select("*");



        $query = $this->connection->get();

        if ($query->num_rows() > 0) {

            return true;
        } else {

            return false;
        }
    }

    public function excel_add($array_datos) {

        $query = "INSERT INTO `productos` (`nombre`, `descripcion`, `precio`, `id_impuesto`) VALUES ('" . $array_datos['nombre'] . "', '" . $array_datos['descripcion'] . "', " . $array_datos['precio'] . ", " . $array_datos['id_impuesto'] . ");";

        $this->connection->query($query);
    }

    public function get_almacen_usuario($id) {

        $this->connection->where("usuario_id", $id);

        $result = $this->connection->get('usuario_almacen')->row();

        return $result;
    }
    
    public function atributosProducto($db)
    {
        $sql = "SHOW TABLES WHERE Tables_in_$db = 'atributos_productos'";      
        //die(var_dump($this->connection->query($sql)));
        return $this->connection->query($sql);
    }
}

?>