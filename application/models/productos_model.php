<?php

class Productos_model extends CI_Model{

    var $connection;
    
    public function __construct()
    {
        parent::__construct();
        $this->load->model("opciones_model", "opciones");
        $this->opciones->initialize($this->dbConnection);
        //$this->load->model("productos_model", 'productos');
        //$this->productos->initialize($this->dbConnection);  
    }

    public function initialize($connection)
    {
        $this->connection = $connection;
    } 
    
    
    public function cantidadProductos( ){        
        $query = $this->connection->query(" SELECT COUNT(producto.id) AS total FROM producto ");
        return $query->row()->total;
    }
    
    //===============================================
    // GIFTCARDS
    //===============================================
    
     public function crearTablaPagosGiftCard(){
         
         if ( !$this->db->table_exists('venta_pago_giftcard') ){
             
            $sql = "
                CREATE TABLE IF NOT EXISTS ventas_pago_giftcard (
                         id INT(12) UNSIGNED NOT NULL AUTO_INCREMENT,
                         id_venta INT(12),
                         codigo_gift VARCHAR(15),
                         PRIMARY KEY (id)
                ) ENGINE=INNODB DEFAULT CHARSET=utf8;
                ";

            $this->connection->query( $sql );
        
         }
       

     }
     
     public function existeGift($id){

         $query = $this->connection->get_where('producto' ,array('codigo' => $id ));

         if( $query->num_rows == 0 ){
            return false;
         }else{
            return true;
         }

     }

     

    // Tabla productos campo activo
    //
    // activo = 1 ->  Se puede vender esa giftcard
    // activo = 2 ->  Ya fue pagada, pero no se ha utilizado
    // activo = 0 ->  La giftcard ya fue comprada y utilizada
     
    public function pagarGiftCard( $listaGiftCards ){
                      
        // se usara es estado 2
        
        foreach ($listaGiftCards as $val){
            $data = array(
                'activo' => 2
            );
            $this->connection->where('id', $val);
            $this->connection->update('producto', $data);            
        }
    }
    
    //giftcard que fueron canjeads por otro producto
    public function cancelarGiftCard( $listaGiftCards ){        
        
        // se usara es estado 0
        
        foreach ($listaGiftCards as $val){
            $data = array(
                'activo' => 0
            );
            $this->connection->where('codigo', $val);
            $this->connection->update('producto', $data);            
        }
    }
    
    
    
    //----------------------------------------------------
        
    public function listaGiftCards( ){        
        $query = $this->connection->query(" SELECT producto.codigo AS codigo, producto.precio_venta AS valor, producto.activo AS estado FROM producto INNER JOIN categoria ON categoria.id = producto.categoria_id WHERE categoria.nombre = 'GiftCard' order by producto.id asc ");
        return $query->result_array();
    }

    public function cantidadGiftCards( ){
        $query = $this->connection->query(" SELECT producto.activo AS estado, COUNT( producto.activo) AS cantidad FROM producto  INNER JOIN categoria ON categoria.id = producto.categoria_id  WHERE categoria.nombre = 'GiftCard' GROUP BY producto.activo ")->result_array();
        
        $cantidades = Array();
        $total = 0;
        
        $cantidades["canjeado"] = 0;
        $cantidades["activo"] = 0;
        $cantidades["pagado"] = 0;           
            
        foreach( $query AS $val ){                       
            
            if( $val["estado"] == "0" ) $cantidades["canjeado"] = $val["cantidad"];
            if( $val["estado"] == "1" ) $cantidades["activo"] = $val["cantidad"];
            if( $val["estado"] == "2" ) $cantidades["pagado"] = $val["cantidad"]; 
            
            $total = $total + $val["cantidad"];
        }
        
        $cantidades["total"] = $total;
        
        return $cantidades;
        
    }
    
    public function estadoGiftCard( $codigo ){        
        
        $query = $this->connection->query("SELECT producto.precio_venta AS valor, producto.nombre AS nombre, producto.activo AS activo FROM producto INNER JOIN categoria ON categoria.id = producto.categoria_id WHERE categoria.nombre = 'GiftCard' AND producto.codigo = '$codigo' LIMIT 1");
                
        $estado = "";        
        $nombre = ""; 
        $valor = ""; 
        
        if( $query->num_rows() > 0){
            
            $result = $query->row()->activo ;
            
            if($result == "0") $estado = "cancelado" ;
            if($result == "1") $estado = "activo" ;
            if($result == "2") $estado = "pagado" ;
            
            $nombre = $query->row()->nombre;
            $valor = $query->row()->valor;
            
        }else{

            $estado = "empty";
            $nombre = "";
            $valor = "";
        }
        
        $obj["estado"] = $estado;
        $obj["nombre"] = $nombre;
        $obj["valor"] = $valor;

                
        return $obj;

    }
    
    //===============================================
    // FIN GIFTCARDS
    //===============================================



    //===============================================
    // IMPORTACION PRODUCTOS NUEVO
    //===============================================    
    
    
    // Funcion para saber si existe un palabra esta en un array de palabras
    public function textInArray( $str="", $array="" ){
                
        foreach($array as $key =>$val){
            
            if( strpos( strtolower($val), strtolower($str) ) !== false) {
                return $key;
            }
        }
        return false;
    }  
    
    public function importExcelNewValidar( $dataExcel ) {
               
        $sheetData = $dataExcel;
        
        // PRIMERO HACEMOS TRIM A CADA CELDA PARA EVITAR MUCHOS ERRORES !!!!
        foreach ( $sheetData as $i => $row) {
            foreach( $row as $letra => $val){
                $sheetData[$i][$letra] = trim( $val );
            }            
        }
//        var_dump($sheetData[1]);die;
        
        // validamos si fue generado dinamicamente y si existe, returnamos el key en el array, de lo contrario false
        $existUnid = $this->textInArray("unidad",$sheetData[1]);
        $existProv = $this->textInArray("proveedor",$sheetData[1]);
        

        // Validamos si fueron generados dinamicamente las columnas ( Activo, Tienda, ExistenciaTienda )
        $existAct = $this->textInArray("activo",$sheetData[1]);
        $existTie = $this->textInArray("tienda",$sheetData[1]);
        $existTienExis = $this->textInArray("existencia",$sheetData[1]);
        
        // Validamos si fueron generados dinamicamente las columnas ( Compuesto, Ingrediente)
        $existComp = $this->textInArray("compuesto",$sheetData[1]);
        $existIngred = $this->textInArray("ingredientes",$sheetData[1]);
        $existCombo = $this->textInArray("combo",$sheetData[1]);
       
        //Campos a validar
        $categorias = Array();
        $impuesto = Array();
        $unidades = Array();
        $proveedor = Array();
        
        
        
        // En esta lista se guardarán las filas de las columnas ( Activo, Tienda, ExistenciaTienda ) que no tengan si o no en sus celdas
        $listaSiNo = Array();
        $headerListaSiNo = ["Excel"];
        if( $existAct != false) $headerListaSiNo[] = "Activo"; 
        if( $existTie != false) $headerListaSiNo[] = "Tienda"; 
        if( $existTienExis != false) $headerListaSiNo[] = "Tienda E."; 
        
        // En esta lista se guardarán las filas de las columnas ( Compuesto, Ingrediente) que no tengan si o no en sus celdas
        if( $existComp != false) $headerListaSiNo[] = "Compuesto"; 
        if( $existIngred != false) $headerListaSiNo[] = "Ingredientes";
        if( $existCombo != false) $headerListaSiNo[] = "Combo";
        
        $listaSiNo[] = $headerListaSiNo; // Añadimos el encabezado según las columnas dinamicas
        
        
        
        
        $codigosExcel = Array();
        $codigosDB = Array();
        
        // Para guardar todos los codigos con su respectivo nombre
        $listaCodigosNombre = Array();
        $listaCodigosNombre2 = Array();
        // Se inicializan para validar que si es ingrediente no puede ser ni combo ni compuesto
        // Si puede existir productos que sean combos y compuestos a la vez
        $tmpCombo = "";
        $tmpIngred = "";
        $tmpComp = "";

        foreach ( $sheetData as $i => $val) {
            
            // Si no somos la primer línea y si no hay informacion en categoria, nombre producto, precio venta e impuesto, no se crea el producto
            if( $i != 1 && trim($val['A']) != "" && trim($val['C']) != "" && trim($val['E']) != "" && trim($val['F']) != "" ){
                                
                if( $val['B'] != "" ){
                    
                    $codigosExcel[] = trim($val['B']);
                    $tmpDbArray = array( "i" => $i, "val" => trim($val['B']) );
                    $codigosDB[] = $tmpDbArray;   
                                        
                    $tmpCod = array();
                    $tmpCod["k"] = trim($val['B']); // key 
                    $tmpCod["v"] = trim($val['C']); // val
                    $tmpCod["i"] = $i; // val
                    $listaCodigosNombre[] = $tmpCod;
                    $listaCodigosNombre2[trim($val['B'])] = trim($val['C']);
                }
                        
                $categorias[] = trim($val['A']);
                $impuesto[] = trim($val['F']);                
                if( $existUnid != false) $unidades[] = trim($val[ $existUnid ]);
                if( $existProv != false) $proveedor[] = trim($val[ $existProv ]);
                
                
                //----------------------------------------------------------------------------------
                // Validación SI / NO de las columnas ( Activo, Tienda, ExistenciaTienda )
                //----------------------------------------------------------------------------------
                $errorRowSiNo = 0; // Si hay un error en la fila entonces cambiamos a 1
                $tmpSiNoRow = [];
                $tmpSiNoRow[] = $i; // añadimos la fila actual del excel
                
                // Activo
                if( $existAct != false){ 
                    $tmpSiNoRow[] = $val[ $existAct ];
                    $tmpV = strtolower($val[ $existAct ]);                    
                    if( $tmpV != "si" && $tmpV != "no" ) $errorRowSiNo = 1;
                }

                // Tienda
                if( $existTie != false){ 
                    $tmpSiNoRow[] = $val[ $existTie ];
                    $tmpV = strtolower($val[ $existTie ]);                    
                    if( $tmpV != "si" && $tmpV != "no" ) $errorRowSiNo = 1;
                }
                
                // Tienda Existencia
                if( $existTienExis != false){ 
                    $tmpSiNoRow[] = $val[ $existTienExis ];
                    $tmpV = strtolower($val[ $existTienExis ]);                    
                    if( $tmpV != "si" && $tmpV != "no" ) $errorRowSiNo = 1;
                }
                
                // Compuesto
                if( $existComp != false){ 
                    $tmpSiNoRow[] = $val[ $existComp ];
                    $tmpV = strtolower($val[ $existComp ]); 
                    $tmpComp = $tmpV;
                    if( $tmpV != "si" && $tmpV != "no" ) $errorRowSiNo = 1;
                }
                
                // Ingrediente
                if( $existIngred != false){ 
                    $tmpSiNoRow[] = $val[ $existIngred ];
                    $tmpV = strtolower($val[ $existIngred ]); 
                    $tmpIngred = $tmpV;
                    if( $tmpV != "si" && $tmpV != "no" ) $errorRowSiNo = 1;
                }
                
                // Combo
                if( $existCombo != false){ 
                    $tmpSiNoRow[] = $val[ $existCombo ];
                    $tmpV = strtolower($val[ $existCombo ]);    
                    $tmpCombo = $tmpV;
                    if( $tmpV != "si" && $tmpV != "no" ) $errorRowSiNo = 1;
                }
                
                if($tmpIngred == "si" && $tmpIngred == $tmpComp)$errorRowSiNo = 1;
                if($tmpIngred == "si" && $tmpIngred == $tmpCombo)$errorRowSiNo = 1;
//                 var_dump($tmpComp,$tmpIngred,$tmpCombo,$i);              
                // Si hubo un error en la fila, lo añadimos a la fila maestra
                if( $errorRowSiNo == 1){
                    $listaSiNo[] = $tmpSiNoRow;
                }
                
                //----------------------------------------------------------------------------------
                    
        
            }
            
        }
        
        
        // eliminamos elementos duplicados
        $categorias = array_unique( $categorias );
        $impuestos = array_unique( $impuesto );
        $unidades = array_unique( $unidades );
        $proveedores = array_unique( $proveedor );
        
        

               
        // ====================================================================================================
        // Consultamos si existen en DB las categorias, impuestos, unidades y proveedores, posteriormente
        // ====================================================================================================


        $masterResult = Array();
        
        
        // En estos array almacenaremos los datos que no existen en db
        $categoriasRes = [];
        $impuestoRes = [];
        $unidadesRes = [];
        $proveedorRes = [];
                
        // CATEGORIAS
        foreach( $categorias as $val ){
            $val = trim($val);
            $query = $this->connection->query("SELECT id FROM categoria WHERE nombre = '$val' ");
            if( $query->num_rows() == 0) $categoriasRes[] = $val;
        }
        
        // IMPUESTOS
        foreach( $impuestos as $val ){
            
            $val = str_replace("%", "", $val); 
            $val = trim($val);
            
            // Si es un valor numerico buscamos en porciento, si no en el campo del nombre
            if( !is_numeric( $val ) ) $query = $this->connection->query(" SELECT id_impuesto FROM impuesto WHERE nombre_impuesto = '$val' ");
            if( is_numeric( $val ) ) $query = $this->connection->query(" SELECT id_impuesto FROM impuesto WHERE porciento = '$val' ");            
            if( $query->num_rows() == 0) $impuestoRes[] = $val;
            
        }
        
        // UNIDADES
        if( $existUnid != false){
            foreach( $unidades as $val ){
                $val = trim($val);
                $query = $this->connection->query(" SELECT id FROM unidades WHERE nombre = '$val' ");
                if( $query->num_rows() == 0) $unidadesRes[] = $val;
            }
        }
        
        // PROVEEDORES
        if( $existProv != false){
            foreach( $proveedores as $val ){
                $val = trim($val);
                $query = $this->connection->query(" SELECT id_proveedor FROM proveedores WHERE nombre_comercial = '$val' ");
                if( $query->num_rows() == 0) $proveedorRes[] = $val;
            }
        }  
        
        
        // CODIGOS REPETIDOS EN EXCEL
        $codigosExcelRes = [];
        // Contamos los valores duplicados
        $contCodigos = array_count_values($codigosExcel);        
        foreach( $contCodigos as $key => $val ){
            if( $val != 1 ){                
                $codigosExcelRes[] = $key;
            }
        }
        
        // CODIGOS REPETIDOS EN DB
        $codigosDBRes = [];        
        foreach( $codigosDB as $item ){
            
            $i = $item["i"];
            $val = trim( $item["val"] );
            
//            $query = $this->connection->query(" SELECT codigo, nombre FROM producto WHERE codigo = '$val' LIMIT 1 ");
            $val = iconv('UTF-8', 'us-ascii//TRANSLIT', $val);// Para caracteres especiales
            $query = $this->connection->select('codigo,nombre')
                    ->where('codigo',$val)
                    ->get('producto',1);
            
            if( $query->num_rows() != 0) {
                $tmpCod = array();
                $tmpCod["c"] = $val;
                $tmpCod["i"] = $i;
                $tmpCod["ex"] = $listaCodigosNombre2[$val];
                $tmpCod["db"] = $query->row()->nombre;
                $codigosDBRes[] = $tmpCod;
                
            }
        }
        
        // ======================================================================================
        // Añadimos los resultados al arrayMaster y si añadimos los datos reales
        // ======================================================================================
        
        $listaErrores = [];
        $realData = [];
        
        
        // Cmapos obligatorios
        if( count( $categoriasRes ) > 0){ 
            $masterResult["categorias"] = $categoriasRes;
            $listaErrores[] = "categorias";
            $query = $this->connection->query(" SELECT id AS 'k', nombre AS 'v' FROM categoria WHERE nombre <> 'GiftCard' ");
            $realData["categorias"] = $query->result_array();
        }
        if( count( $impuestoRes ) > 0){ 
            $masterResult["impuestos"] = $impuestoRes;
            $listaErrores[] = "impuestos";
            $query = $this->connection->query(" SELECT id_impuesto AS 'k', TRIM( REPLACE(nombre_impuesto, '%', '') ) AS 'v' FROM impuesto ");
            $realData["impuestos"] = $query->result_array();
        }
        
        // Campos Opcionales
        if( $existUnid != false){
            if( count( $unidadesRes ) > 0){
                $masterResult["unidades"] = $unidadesRes;
                $listaErrores[] = "unidades";
                $query = $this->connection->query(" SELECT id AS 'k', nombre AS 'v' FROM unidades ");
                $realData["unidades"] = $query->result_array();
            }
        }
        if( $existProv != false){
            if( count( $proveedorRes ) > 0){
                $masterResult["proveedores"] = $proveedorRes;
                $listaErrores[] = "proveedores";
                $query = $this->connection->query(" SELECT id_proveedor AS 'k', nombre_comercial AS 'v' FROM proveedores ");
                $realData["proveedores"] = $query->result_array();
            }
        }
        
        // Codigos duplicados en excel
        $codigoExcelFinal = [];
        if( count( $codigosExcelRes ) > 0){             
            
            // generamos los productos repetidos con su nombre
            foreach($codigosExcelRes as $val){
                foreach($listaCodigosNombre as $valFinal){
                    if( $val == $valFinal["k"]){
                        $tmpCod = array();
                        $tmpCod["i"] = $valFinal["i"];
                        $tmpCod["c"] = $valFinal["k"];                        
                        $tmpCod["ex"] = $valFinal["v"];
                        $codigoExcelFinal[]= $tmpCod;
                    }                    
                }
                $codigoExcelFinal[]= array("c"=>"-","ex"=>"-");
            }                       
            
            $listaErrores[] = "codigosExcel";
            $masterResult["codigosExcel"] = $codigoExcelFinal;
        }
        
        
        // codigos duplicados en DB
        if( count( $codigosDBRes ) > 0){ 
            $masterResult["codigosDB"] = $codigosDBRes;
            $listaErrores[] = "codigosDB";
        }        
        
        
        // Si hay errores de ( SI / NO ) 
        // Si almenos una columna ( SI / NO ) fue activa entonces añadimos a lista de errores
        if( $existAct != false || $existTie != false || $existTienExis != false || $existComp != false || $existIngred != false || $existCombo != false ){ 
//          if( $listaSiNo > 1){ // Se ajusta validacion: Error al contar los campos del arreglo, debe ser mayor a 1 pues en la primer posicion tiene las cabeceras
            if( count($listaSiNo) > 1){
                $masterResult["sino"] = $listaSiNo;
                $listaErrores[] = "sino";    
                
            }            
        }
        
        
        //----------------------------------------------------------
        // Consultamos Almacenes
        //----------------------------------------------------------
        $almacenesLetras = [];
        $almacenesMapLetras = [];
        foreach($sheetData[1] as $key => $val ){
            if( strpos( strtolower($val), "cantidad" ) !== false ){                
                $nombreAlmacen = 

                $val = str_replace(")", "", $val);
                $val = explode('(', $val)[1];
                $val = strtolower(trim($val));

                $almacenesLetras[] = $key;

                $query = $this->connection->query(" SELECT id FROM almacen WHERE nombre = '$val' ");

                
                // Si no existe ningun alamcen añaimos el error
                if( $query->num_rows() == 0 ){                    
                    $listaErrores[] = "almacen";                    
                }

            }
        }

        //==============================================================
        // Compilamos resultado
        //==============================================================
        
        $resultado = array();
        
        $resultado["errores"] = $listaErrores;
        $resultado["objErrores"] = $masterResult;
        $resultado["realData"] = $realData;
                
        return $resultado;
        
    }
    
    public function importExcelNewImportar( $dataExcel, $errorFix, $tipoAccion ) {
        
        $sheetData = $dataExcel;
        
        // PRIMERO HACEMOS TRIM A CADA CELDA PARA EVITAR MUCHOS ERRORES !!!!
        foreach ( $sheetData as $i => $row) {
            foreach( $row as $letra => $val){
                $sheetData[$i][$letra] = trim( $val );
            }            
        }

        
        
        try{
            
            $this->connection->trans_begin();    

            $idUser = $this->session->userdata('user_id');

            //$errorFix = '{"categorias":[{"k":"shop & more","v":"Camisa"},{"k":"pantalones","v":"Zapatos"},{"k":"Gorras","v":"Categoría 4"},{"k":"Categoría 1","v":"Categoría 5"}],"impuestos":[{"k":"2","v":"IMPOCONSUMO"},{"k":"iva 20","v":"Sin Impuesto"},{"k":"20","v":"Iva 5"}],"unidades":[{"k":"kilos","v":"kilogramo"},{"k":"Litros","v":"gramo"}],"proveedores":[{"k":"claro@claro.com.co","v":"giseth salazar"},{"k":"asda","v":"CLARO"},{"k":"b","v":"Johana "}]}';
            $errorFix = json_decode( $errorFix, true);


            // validamos si fue generado dinamicamente y si existe, returnamos el key en el array, de lo contrario false
            $existMin = $this->textInArray("mínimo",$sheetData[1]);
            $existMax = $this->textInArray("máximo",$sheetData[1]);
            $existUbi = $this->textInArray("ubicación",$sheetData[1]);
            $existUnid = $this->textInArray("unidad",$sheetData[1]);
            $existFecha = $this->textInArray("fecha",$sheetData[1]);
            $existAct = $this->textInArray("activo",$sheetData[1]);
            $existTie = $this->textInArray("tienda",$sheetData[1]);
            $existTienExis = $this->textInArray("existencia",$sheetData[1]);
            $existProv = $this->textInArray("proveedor",$sheetData[1]);

            $existComp = $this->textInArray("compuesto",$sheetData[1]);
            $existIngred = $this->textInArray("ingredientes",$sheetData[1]);
            $existCombo = $this->textInArray("combo",$sheetData[1]);
            
            //pr($errorFix);
            //pr($sheetData);               


            // SOBREESCRIBIMOS CATEGORIAS, IMPUESTOS, UNIDADES Y PROVEEDORES ERRONEOS y CREAMOS ARRAY PARA FOREING KEYS
            foreach ( $sheetData as $i => $val) {

                
                // Si no somos la primer línea y si no hay informacion en categoria, nombre producto, precio venta e impuesto, no se crea el producto
                if( $i != 1 ){                                                


                    if (array_key_exists('categorias', $errorFix)){
                        foreach( $errorFix["categorias"] as $fixObj ){
                            if( trim( $val['A'] ) == trim( $fixObj["k"] ) ){    
                                $sheetData[$i]["A"] = trim( $fixObj["v"] );
                            }
                        }                    
                    }

                    if (array_key_exists('impuestos', $errorFix)){
                        foreach( $errorFix["impuestos"] as $fixObj ){
                            $tmpImp = str_replace("%", "", $val['F'] );
                            if( trim( $tmpImp ) == trim( $fixObj["k"] ) ){    
                                $sheetData[$i]["F"] = trim( $fixObj["v"] );
                            }
                        }                    
                    }

                    if (array_key_exists('unidades', $errorFix)){
                        foreach( $errorFix["unidades"] as $fixObj ){
                            if( trim( $val[$existUnid] ) == trim( $fixObj["k"] ) ){    
                                $sheetData[$i][$existUnid] = trim( $fixObj["v"] );
                            }
                        }                    
                    }

                    if (array_key_exists('proveedores', $errorFix)){
                        foreach( $errorFix["proveedores"] as $fixObj ){
                            if( trim( $val[$existProv] ) == trim( $fixObj["k"] ) ){    
                                $sheetData[$i][$existProv] = trim( $fixObj["v"] );
                            }
                        }                    
                    }                                                                  

                }

            }


            //----------------------------------------------------------
            // Consultamos Almacenes
            //----------------------------------------------------------
            $almacenesLetras = [];
            $almacenesMapLetras = [];
            foreach($sheetData[1] as $key => $val ){
                if( strpos( strtolower($val), "cantidad" ) !== false ){                
                    $nombreAlmacen = 

                    $val = str_replace(")", "", $val);
                    $val = explode('(', $val)[1];
                    $val = strtolower(trim($val));

                    $almacenesLetras[] = $key;

                    $query = $this->connection->query(" SELECT id FROM almacen WHERE nombre = '$val' ");
                    
                    $id = 1;
                    
                    // Si existe el almacen
                    if($query->num_rows() > 0){
                        $id = $query->row()->id;
                    }
                    

                    // info completa
                    $tmpAlm = array(
                        "n" => $val,
                        "id" => $id
                        );
                    $almacenesMapLetras[$key] = $tmpAlm;

                }
            }

            //----------------------------------------------------------
            // Consultamos tablas foreing con los respectivos datos
            //----------------------------------------------------------

            $categoriasMap = Array();
            $query = $this->connection->query(" SELECT TRIM(nombre) AS 'k', id AS 'v' FROM categoria WHERE nombre <> 'GiftCard' ");
            $categorias = $query->result_array();
            foreach($categorias as $val){
                $categoriasMap[strtolower($val["k"])] = $val["v"];
            }

            $impuestosMap = Array();
            $impuestosPorcenMap = Array();
            $query = $this->connection->query(" SELECT TRIM( REPLACE(nombre_impuesto, '%', '') ) AS 'k', TRIM(porciento) AS 'kn', id_impuesto AS 'v' FROM impuesto ");
            $impuesto = $query->result_array();
            foreach($impuesto as $val){
                $impuestosMap[strtolower($val["k"])] = $val["v"];
                $impuestosPorcenMap[$val["kn"]] = $val["v"];                        
            }

            $unidadesMap = Array();
            $query = $this->connection->query(" SELECT trim(nombre) AS 'k', id AS 'v' FROM unidades ");
            $unidades = $query->result_array();
            foreach($unidades as $val){
                $unidadesMap[strtolower($val["k"])] = $val["v"];            
            }

            $proveedoresMap = Array();
            $query = $this->connection->query(" SELECT TRIM(nombre_comercial) AS 'k', id_proveedor AS 'v' FROM proveedores ");
            $proveedor = $query->result_array();
            foreach($proveedor as $val){
                $proveedoresMap[strtolower($val["k"])] = $val["v"];
            }
            
            
            //----------------------------------------------------
            // Array para mostrar reporte de resultados
            //----------------------------------------------------
            $productosImportadosHeader = [
                "s", // estado del producto en la importacion, si es importado o no
                "Excel",
                "Categoría",
                "Código",
                "Nombre",      
                "PC",                
                "PV",                
                "Imp",
                "Descripción"
            ];
            
            // Si los campos fueron generados dinamicamente en el excel los añadimos al reporte
            if( $existMin != false ) $productosImportadosHeader[] = 'S. Mín';
            if( $existMax != false ) $productosImportadosHeader[] = 'S. Máx';            
            if( $existUbi != false ) $productosImportadosHeader[] = 'Ubi.';            
            if( $existUnid != false ) $productosImportadosHeader[] = 'Uni.';            
            if( $existFecha != false ) $productosImportadosHeader[] = 'FV';            
            if( $existAct != false ) $productosImportadosHeader[] = 'Act.';
            if( $existTie != false ) $productosImportadosHeader[] = 'Tie.';
            if( $existTienExis != false ) $productosImportadosHeader[] = 'TE';
            if( $existProv != false ) $productosImportadosHeader[] = 'Prov.';
            if( $existComp != false ) $productosImportadosHeader[] = 'Comp.';
            if( $existIngred != false ) $productosImportadosHeader[] = 'Ingred.';
            if( $existCombo != false ) $productosImportadosHeader[] = 'Combo';
           
            
            //añadimos los almacenes al encabezado
            foreach( $almacenesLetras as $valLetraAlm ){            
                $nombreAlmacen = $almacenesMapLetras[ $valLetraAlm ]["n"];
                $productosImportadosHeader[] = ucwords($nombreAlmacen);
            }
                        
            
            //añadimos el header ( títulos ) a la lista de importados
            $masterReport[] = $productosImportadosHeader;
            
            //--------------------------
            //FIN array Reportes 
            //--------------------------

            // AGREGAMOS PRODUCTOS
            foreach ( $sheetData as $i => $val) {

                // Si no somos la primer línea y si no hay informacion en categoria, nombre producto, precio venta e impuesto, no se crea el producto
                if( $i != 1 && trim($val['A']) != "" && trim($val['C']) != "" && trim($val['E']) != "" && trim($val['F']) != "" ){                     

                    // CAMPOS OBLIGATORIOS
                    
                    // CODIGO                
                    $codigo;
                    if( trim($val['B']) == "" ){
                        $codigo = substr( strtoupper(md5(microtime())) ,0,15);
                    }else{
                        $codigo = trim($val['B']);
                    }
                    
                    // PRECIO COMPRA
                    $precioC;
                    if( trim($val['D']) == "" ){
                        $precioC = 0;
                    }else{
                        $precioC = $this->toNum( trim($val['D']) );
                    }
                    
                    // PRECIO VENTA
                    $precioV;
                    if( trim($val['E']) == "" ){
                        $precioV = 0;
                    }else{
                        $precioV = $this->toNum( trim($val['E']) );
                    }
                    

                    // CAMPOS DINAMICOS                

                    // activo
                    $activo = 1;
                    if( $existAct != false ){
                        if( strtolower(trim($val[ $existAct ])) == "no" || strtolower(trim($val[ $existAct ])) == "0"  ) $activo = 0;
                        if( strtolower(trim($val[ $existAct ])) == "si" || strtolower(trim($val[ $existAct ])) == "1" || strtolower(trim($val[ $existAct ])) == "" ) $activo = 1;
                    }else{
                        $activo = 1;
                    }
                    
                    // tienda
                    $tienda = 1;
                    if( $existTie != false ){
                        if( strtolower(trim($val[ $existTie ])) == "no" || strtolower(trim($val[ $existTie ])) == "0" || strtolower(trim($val[ $existTie ])) == "" ) $tienda = 0;
                        if( strtolower(trim($val[ $existTie ])) == "si" || strtolower(trim($val[ $existTie ])) == "1") $tienda = 1;
                    }else{
                        $tienda = 1;
                    }
                    
                    // tienda existencias
                    $tiendaExis = 0;
                    if($existTienExis != false ) {
                        if( strtolower(trim($val[ $existTienExis ])) == "no" || strtolower(trim($val[ $existTienExis ])) == "0" || strtolower(trim($val[ $existTienExis ])) == "" ) $tiendaExis = 0;
                        if( strtolower(trim($val[ $existTienExis ])) == "si" || strtolower(trim($val[ $existTienExis ])) == "1" ) $tiendaExis = 1;
                    }else{
                        $tiendaExis = 0;
                    }
                    
                    // Compuesto
                    $compuesto = 0;
                    if( $existComp != false ){
                        if( strtolower(trim($val[ $existComp ])) == "no" || strtolower(trim($val[ $existComp ])) == "0"  ) $compuesto = 0;
                        if( strtolower(trim($val[ $existComp ])) == "si" || strtolower(trim($val[ $existComp ])) == "1" || strtolower(trim($val[ $existComp ])) == "" ) $compuesto = 1;
                    }else{
                        $compuesto = 0;
                    }
                    
                    // Ingrediente
                    $ingrediente = 0;
                    if( $existIngred != false ){
                        if( strtolower(trim($val[ $existIngred ])) == "no" || strtolower(trim($val[ $existIngred ])) == "0"  ) $ingrediente = 0;
                        if( strtolower(trim($val[ $existIngred ])) == "si" || strtolower(trim($val[ $existIngred ])) == "1" || strtolower(trim($val[ $existIngred ])) == "" ) $ingrediente = 1;
                    }else{
                        $ingrediente = 0;
                    }
                    
                    // Combo
                    $combo = 0;
                    if( $existCombo != false ){
                        if( strtolower(trim($val[ $existCombo ])) == "no" || strtolower(trim($val[ $existCombo ])) == "0"  ) $combo = 0;
                        if( strtolower(trim($val[ $existCombo ])) == "si" || strtolower(trim($val[ $existCombo ])) == "1" || strtolower(trim($val[ $existCombo ])) == "" ) $combo = 1;
                    }else{
                        $combo = 0;
                    }
                    
                    // stock minimo
                    $min = 0;
                    if($existMin != false ) {
                        if( trim($val[ $existMin ]) == "") $min = 0;
                        else $min = trim($val[ $existMin ]);
                    }else{
                        $min = 0;
                    }

                    // stock maximo
                    $max = 1;
                    if($existMax != false ) {
                        if( trim($val[ $existMax ]) == "") $max = 1;
                        else $max = trim($val[ $existMax ]);
                    }else{
                        $max = 1;
                    }

                    // fehca vencimiento
                    $fechaV = $existFecha != false ?  trim($val[ $existFecha ]) : '';
                    
                    // ubicacion
                    $ubicacion = $existUbi != false ?  trim($val[ $existUbi ]) : '';

                    
                    // FOREING TABLES
                    $id_proveedor = $existProv != false ?  $proveedoresMap[ strtolower( trim($val[ $existProv ])) ] : 0;
                    $id_unidad = $existUnid != false ?  $unidadesMap[ strtolower(trim($val[ $existUnid ])) ] : 1;
                    $id_categoria = $categoriasMap[ strtolower(trim( $val['A'] )) ];

                    // IMPUESTO
                    $id_impuesto = 0;
                    $tmpImp = str_replace("%", "", $val['F'] ); 

                    if( !is_numeric( trim( $tmpImp ) ) ){ $id_impuesto = $impuestosMap[ strtolower(trim( $tmpImp )) ]; }
                    if( is_numeric( trim( $tmpImp ) ) ){ $id_impuesto = $impuestosPorcenMap[ trim( $tmpImp ) ]; }
                    
                    //-----------------------------------------------------------------
                    
                    //----------------------------------------------------------------
                    // Solo para el reporte de productos importados correctamente
                    //----------------------------------------------------------------                                       
                    
                    $reportProveedor = $existProv != false ? trim($val[ $existProv ]) : "";
                    $reportUnidad = $existUnid != false ? trim($val[ $existUnid ]) : "";
                    
                    $reportActivo = $activo == 1 ? "Si" : "No";
                    $reportTienda = $tienda == 1 ? "Si" : "No";
                    $reportTiendaE = $tiendaExis == 1 ? "Si" : "No";
                    
                    $reportCompuesto = $compuesto == 1 ? "Si" : "No";
                    $reportIngrediente = $ingrediente == 1 ? "Si" : "No";
                    $reportCombo = $combo == 1 ? "Si" : "No";
                    
                    
                    
                    $tempProductosImportados = [
                        1, // Significa que el producto será importado
                        $i, // fila en el excel
                        trim( $val['A'] ), // Categoria
                        $codigo,
                        trim( $val['C'] ), // Nombre                        
                        $precioC,                      
                        $precioV,                          
                        trim( $val['F'] ), // Impuesto                                                
                        trim( $val['G'] )
                    ];
                    
                    // Si los campos fueron generados dinamicamente en el excel los añadimos al reporte
                    if( $existMin != false ) $tempProductosImportados[] = $min;
                    if( $existMax != false ) $tempProductosImportados[] = $max;
                    if( $existUbi != false ) $tempProductosImportados[] = $ubicacion;
                    if( $existUnid != false ) $tempProductosImportados[] = $reportUnidad;
                    if( $existFecha != false ) $tempProductosImportados[] = $fechaV;
                    if( $existAct != false ) $tempProductosImportados[] = $reportActivo;
                    if( $existTie != false ) $tempProductosImportados[] = $reportTienda;
                    if( $existTienExis != false ) $tempProductosImportados[] = $reportTiendaE;
                    if( $existProv != false ) $tempProductosImportados[] = $reportProveedor;
                    
                    if( $existComp != false ) $tempProductosImportados[] = $reportCompuesto;
                    if( $existIngred != false ) $tempProductosImportados[] = $reportIngrediente;
                    if( $existCombo != false ) $tempProductosImportados[] = $reportCombo;
                    
                    //----------------------------------------------------------------
                    // Fin productos importados correctamente
                    //----------------------------------------------------------------
                    
                    $data = array(
                        'imagen' => "product-dummy.png",
                        "nombre" => trim( $val['C'] ),
                        "codigo" => $codigo,
                        "precio_venta" => $precioV,
                        "precio_compra" => $precioC,
                        "categoria_id" => $id_categoria,
                        "impuesto" => $id_impuesto,
                        "descripcion" =>trim( $val['G'] ),
                        "id_proveedor" => $id_proveedor,
                        "unidad_id" => $id_unidad,
                        'activo' => $activo,
                        'tienda' => $tienda,
                        'muestraexist' => $tiendaExis,
                        'fecha_vencimiento' => $fechaV,
                        'ubicacion' => $ubicacion,
//                        'material' => 0,
                        'stock_minimo' => $min,
                        'stock_maximo' => $max,
                            
                        'ingredientes' => $compuesto,
                        'material' => $ingrediente,
                        'combo' => $combo,
                        'ganancia' => 0
                    );


                    if( $tipoAccion == "guardar"){
                        
                        // AGREGAMOS EL PRODUCTO
                        $this->connection->insert("producto", $data);
                        $id_producto = $this->connection->insert_id();
                        
                    }else{
                        $id_producto = 0;
                    }
                    

                    // ACTUALIZAMOS STOCK ACTUAL Y DIARIO
                    foreach( $almacenesLetras as $valLetraAlm ){
                        
                        $idProd = $id_producto;

                        $idAlmacen = $almacenesMapLetras[ $valLetraAlm ]["id"];
                        $nombreAlmacen = $almacenesMapLetras[ $valLetraAlm ]["n"];
                        $cantidad = trim( $val[ $valLetraAlm ] );
                        
                         
                        // Si no se especifica la cantidad o no es numerico
                        if( $cantidad == "" || !is_numeric($cantidad)) {
                            $cantidad = "0";
                        } 
                        
                       //------------------------------------------------------------------------
                        // Añadimos unidaes al reporte final de objetos importados correctamente
                        //------------------------------------------------------------------------                         
                        $tempProductosImportados[] = $cantidad;
                        //------------------------
                        // Fin reporte
                        //------------------------
                        
                        // Falta! Ajustar este insert debe actualizar el precio en stock_actual...
                        //verificar si tiene precio por almacen
                        
                        $sqlprecio = $this->connection->query(" SELECT valor_opcion FROM opciones WHERE nombre_opcion='precio_almacen' ");
                        $sqlprecio = $sqlprecio->result_array();
                        $precioalmacen=$sqlprecio[0]["valor_opcion"];     
                         
                        if($precioalmacen==1){
                            $data_stock_actual = array(
                                'almacen_id' => $idAlmacen,
                                'producto_id' => $idProd,
                                'unidades' => $cantidad,
                                'precio_compra' => $precioC,
                                'precio_venta' => $precioV,                                
                                "impuesto" => $id_impuesto,
                                'activo' => $activo,
                                'fecha_vencimiento' => $fechaV,
                                'stock_minimo' => $min
                            );
                        }else{
                            $data_stock_actual = array(
                                'almacen_id' => $idAlmacen,
                                'producto_id' => $idProd,
                                'unidades' => $cantidad
                            );
                        }                                                
              
                        $data_stock_diario =  array(
                            'producto_id' => $idProd,
                            'almacen_id' => $idAlmacen,
                            'fecha' => date('Y-m-d'),
                            'unidad' => $cantidad,
                            'precio' => $precioV,
                            'usuario' => $idUser,
                            'razon' => 'E'
                        );  
                        
                        if( $tipoAccion == "guardar"){
                            
                            $this->connection->insert('stock_actual', $data_stock_actual);
                            $this->connection->insert('stock_diario', $data_stock_diario);
                            
                        }
                                        
                    } 
                    
                    
                    
                    // agregamos el objeto importado a la lista
                    $masterReport[] = $tempProductosImportados;

                    
                }else{ // ERRORES EN EL EXCEL
                    
                    // Si no estamos en la primer fila que es el encabezado
                    if( $i != 1){
                        
                        // Si todas la celdas estan en blanco no lo añadimos al reporte de no importados
                         if( trim($val['A']) == "" && trim($val['B']) == "" && trim($val['C']) == "" && trim($val['D']) == "" && trim($val['E']) == "" && trim($val['F']) == "" ){ }
                         else{

                            // creamos la lista de los productos que no serán importados  
                             
                             $a = trim($val['A']) == "" ? "?" : trim($val['A']);                             
                             $c = trim($val['C']) == "" ? "?" : trim($val['C']);                             
                             $e = trim($val['E']) == "" ? "?" : $this->toNum(trim($val['E']));
                             $f = trim($val['F']) == "" ? "?" : trim($val['F']);
                             
                             
                            $tmpNoImportado = [
                                0, // Significa que el producto NO será importado
                                $i,
                                $a,
                                trim($val['B']),
                                $c,
                                $this->toNum( trim($val['D']) ),
                                $e,
                                $f,
                                trim($val['G']),//Descripcion
                            ];
                            
                            // Si los campos fueron generados dinamicamente en el excel los añadimos al reporte
                            if( $existMin != false ) $tmpNoImportado[] = "";
                            if( $existMax != false ) $tmpNoImportado[] =  "";
                            if( $existUbi != false ) $tmpNoImportado[] =  "";
                            if( $existUnid != false ) $tmpNoImportado[] =  "";
                            if( $existFecha != false ) $tmpNoImportado[] =  "";
                            if( $existAct != false ) $tmpNoImportado[] =  "";
                            if( $existTie != false ) $tmpNoImportado[] =  "";
                            if( $existTienExis != false ) $tmpNoImportado[] =  "";
                            if( $existProv != false ) $tmpNoImportado[] =  "";
                            
                            if( $existComp != false ) $tmpNoImportado[] =  "";
                            if( $existIngred != false ) $tmpNoImportado[] =  "";
                            if( $existCombo != false ) $tmpNoImportado[] =  "";

                            
                            // Añadimos las celdas vacias de los almacenes
                            foreach( $almacenesLetras as $valLetraAlm ){
                                $tmpNoImportado[] = "";
                            }
                            
                            
                            // agregamos el objeto no importado a la lista
                            $masterReport[] = $tmpNoImportado;

                        } 
                    }
                }
            }

            if ($this->connection->trans_status() === FALSE){
                $this->connection->trans_rollback();
            } else {
                $this->connection->trans_commit();
            }
            
                       
            // Retornamos reporte
            return $masterReport;
            
                
        } catch (Exception $e) {
            // $this->connection->trans_rollback();
             print_r($e);
             die;
        }
        
    }    
    
    
    // Cambiamos comas por puntos    
    // Elimina el signo de la modena,
    // Retorna un numero sin decimales si no es necesario
    private function toNum($str){
        $str = str_replace(",", ".", $str);       
        $number = preg_replace("/([^0-9\\.])/i", "", $str);
        return (float)$number + 0;
    }    
    
    
    public function getImportExcelData(){
        
        $result = array();
        $result["categorias"] = $this->connection->query(" SELECT nombre FROM categoria WHERE nombre <> 'GiftCard' ")->result();
        $result["impuestos"] = $this->connection->query(" SELECT nombre_impuesto FROM impuesto ")->result();
        $result["unidades"] = $this->connection->query(" SELECT nombre FROM unidades ")->result();
        $result["proveedores"] = $this->connection->query(" SELECT nombre_comercial FROM proveedores ")->result();
        
        return $result;
        
    }
    
    public function creacionRapidaNewImportar(){
        
        $tipo = $this->input->post("tipo");
        $nombre = $this->input->post("nombre");
        $porcentajeImp = $this->input->post("valorPorcentaje");
        
        if( $tipo == "categoria" ){
            $arrayData = array( "nombre" => $nombre, "codigo" => '0', "imagen" => '' );
            $this->connection->insert('categoria', $arrayData);            
        }
        
        if( $tipo == "impuesto" ){
            $arrayData = array( "nombre_impuesto" => $nombre, "porciento" => $porcentajeImp );
            $this->connection->insert('impuesto', $arrayData);            
        }
        
        if( $tipo == "unidad" ){
            $arrayData = array( "nombre" => $nombre );
            $this->connection->insert('unidades', $arrayData);            
        }
        
        if( $tipo == "proveedor" ){
            $arrayData = array( "nombre_comercial" => $nombre );
            $this->connection->insert('proveedores', $arrayData);            
        }
        
        return "ok";
        
    }
    
    //===============================================
    // FIN  IMPORTACION PRODUCTOS NUEVO
    //===============================================    
    
    
    public function get_total()
    {
        //$query = $this->connection->query("SELECT count(*) as cantidad FROM  producto s Inner Join impuestos i on s.id_impuesto = i.id_impuesto");
        $query = $this->connection->query("SELECT count(*) as cantidad FROM  producto s");
        return $query->row()->cantidad;                             
    }

    public function getList()
    {
        $query = $this->connection->query("SELECT * FROM producto s LEFT JOIN impuesto i ON s.impuesto = i.id_impuesto ORDER BY s.id DESC");
        return $query->result();
    }
    public function getList_NULL()
    { //solo precio por almacen
        $query = $this->connection->query("SELECT p.* , i.* FROM producto p 
            LEFT JOIN impuesto i ON p.impuesto = i.id_impuesto
            INNER JOIN stock_actual s ON p.id=s.producto_id
            WHERE (s.impuesto IS NULL OR s.precio_compra IS NULL OR s.precio_venta IS NULL)
            ORDER BY p.id DESC");
        return $query->result();
    }

    public function getListProductos($id_almacen = NULL){


        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        if($precio_almacen == 0){// Si no tiene precio diferente por almacen  
            $query = $this->connection->query("SELECT * FROM producto s LEFT JOIN impuesto i ON s.impuesto = i.id_impuesto ORDER BY s.id DESC");
            return $query->result();
        }else{
            //Stock
            $this->connection->select("p.codigo,p.nombre,s.precio_venta");
            $this->connection->from("producto p");
            $this->connection->join("stock_actual s","s.producto_id = p.id");
            $this->connection->where("p.activo",1);
            $this->connection->where("s.almacen_id",$id_almacen);
            $this->connection->where("p.material",0);
            $this->connection->order_by("p.nombre","ASC");
            $productos = $this->connection->get();
            return $productos->result();
        }
    }

    public function get_all($offset)
    {
        $query = $this->connection->query("SELECT * FROM producto s Inner Join impuestos i on s.id_impuesto = i.id_impuesto ORDER BY id_producto DESC limit $offset, 8");
        return $query->result();
    }

    public function productoLike($q = null){
        if(isset($q))
            $where = "where nombre like '%$q%' and activo = 1";
        $query = $this->connection->query("SELECT id,nombre as name,codigo,precio_compra,precio_venta,impuesto,imagen1 as ThumbnailImage FROM producto ".$where);
        
        
        if ($query->num_rows() > 0) 
        {
            $data['count'] = 2500;
            foreach($query->result_array() as $row) {
                $data['items'][] =  array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'codigo' => $row['codigo'],
                    'precio_compra' => $row['precio_compra'],
                    'precio_venta' => $row['precio_venta'],
                    'impuesto' => $row['impuesto'],
                    'ThumbnailImage' => isset($row['ThumbnailImage']) ? $row['ThumbnailImage'] : 'uploads/'
                );
            }

            
            //$data['datos'] = array_map("utf8_decode", $query->result_array()); 
            //
            return $data;
        }
    }

    public function materialLike($q = null){
        if(isset($q))
            $where = "and nombre like '%$q%' and activo = 1";
        $query = $this->connection->query("SELECT id,nombre as name,codigo,precio_compra,precio_venta,impuesto,imagen1 as ThumbnailImage FROM producto where (material = 1 OR ingredientes = 1 OR combo = 1 OR (material = 0 AND ingredientes = 0 AND combo = 0)) ".$where);
        
   
        if ($query->num_rows() > 0) 
        {
            $data['count'] = 2500;
            foreach($query->result_array() as $row) {
                $data['items'][] =  array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'codigo' => $row['codigo'],
                    'precio_compra' => $row['precio_compra'],
                    'precio_venta' => $row['precio_venta'],
                    'impuesto' => $row['impuesto'],
                    'ThumbnailImage' => isset($row['ThumbnailImage']) ? $row['ThumbnailImage'] : 'uploads/'
                );
            }

            
            //$data['datos'] = array_map("utf8_decode", $query->result_array()); 
            //
            return $data;
        }
    }
    
    public function paginacion($start,$offset,$search = null,$filterBy = null){
        $inicio=$start*$offset;
        $data = [];
        $categoria="";
        $where="";

        if((!empty($filterBy))&&(!empty($search))){
            $categoria = " AND categoria_id=".$filterBy;
        }            
        else{
            if(!empty($filterBy)){
                $categoria = " where categoria_id=".$filterBy;
            }            
        }

        if(isset($search))
            $where = "where nombre like '%".$search."%'";

        $count = $this->connection->query("SELECT * FROM producto");
        $data['count'] = $count->num_rows();
        $query = $this->connection->query("SELECT id,nombre as name,codigo,precio_compra,precio_venta,impuesto,imagen1 as ThumbnailImage FROM producto $where $categoria order by id DESC LIMIT $inicio,$offset");        
        if ($query->num_rows() > 0) 
        {

            foreach($query->result_array() as $row) {
                $data['datos'][] =  array(
                    'id' => $row['id'],
                    'name' => $row['name'],
                    'codigo' => $row['codigo'],
                    'precio_compra' => $row['precio_compra'],
                    'precio_venta' => $row['precio_venta'],
                    'impuesto' => $row['impuesto'],
                    'ThumbnailImage' => isset($row['ThumbnailImage']) ? $row['ThumbnailImage'] : 'uploads/'
                );
            }

            
            //$data['datos'] = array_map("utf8_decode", $query->result_array()); 
            //            
            return $data;
        }
    }

        public function get_ajax_data(){

            //Jeisson Rodriguez (16/07/2019)
            $precio_almacen = $this->opciones->getOpcion('precio_almacen');
            
            $user_id = $this->session->userdata('user_id');
            $sql_almacen_actual = "select a.id from almacen a inner join usuario_almacen u ON a.id = u.almacen_id WHERE u.usuario_id = '".$user_id."' LIMIT 1"; 
            $result = $this->connection->query($sql_almacen_actual);
            $almacen = $result->result()[0]->id;

            if(!(int)$precio_almacen){
                $aColumns = array('s.imagen', 's.nombre', 's.codigo', 's.precio_compra', 's.precio_venta', 'nombre_impuesto','categoria.nombre as nombre_categoria' ,'st.unidades','s.id');
            }else{
                $aColumns = array('s.imagen', 's.nombre', 's.codigo', 's.precio_compra', 'st.precio_venta', 'nombre_impuesto','categoria.nombre as nombre_categoria' ,'st.unidades','s.id');
            }

            $sIndexColumn = "s.id";

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
                    for($i=0; $i<count($aColumns);$i++){
                        if($_GET['iSortCol_0'] == $i){
                            $buscar = $aColumns[$i];
                            if($buscar == 'categoria.nombre as nombre_categoria'){
                                $buscar = 'categoria.nombre';
                            }
                            $sOrder .= "".$buscar." ".
                            ($_GET['sSortDir_0']==='asc' ? 'asc' : 'desc') .", ";
                        }
                    }
                    /*for ( $i=0 ; $i<intval( $_GET['iSortingCols'] ) ; $i++ )

                    {
                        $buscar = $aColumns[$i];
                        if($buscar == 'categoria.nombre as nombre_categoria'){
                            $buscar = 'categoria.nombre';
                        }

                            if ( $_GET[ 'bSortable_'.intval($_GET['iSortCol_'.$i]) ] == "true" )

                            {

                                    $sOrder .= "".$buscar." ".

                                            ($_GET['sSortDir_'.$i]==='asc' ? 'asc' : 'desc') .", ";

                            }

                    }*/



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
                        $buscar = $aColumns[$i];
                        if($buscar == 'categoria.nombre as nombre_categoria'){
                            $buscar = 'categoria.nombre';
                        }

                            if ( isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" )

                            {
                               /*  echo "filtrar ".$_GET['sSearch'];*/
                                    $sWhere .= "".$buscar." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";

                            }else{
                                 $sWhere .= "".$buscar." LIKE '%".mysql_real_escape_string( $_GET['sSearch'] )."%' OR ";
                            }

                    }

                    $sWhere = substr_replace( $sWhere, "", -3 );

                    $sWhere .= ')';

            }

          /*  echo $sWhere ;*/

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

            
            if($sWhere=='')
                $sWhere = 'where material <> 1 and st.almacen_id = "'.$almacen.'"'; 
            else
                $sWhere = $sWhere.' AND material <> 1 and st.almacen_id = '.$almacen;
                
                if(!(int)$precio_almacen){
                    $sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
                                FROM   $sTable s Left Join impuesto i on s.impuesto = i.id_impuesto
                                inner join categoria on categoria.id = s.categoria_id  
                                inner join stock_actual st on st.producto_id = s.id  
                                $sWhere $sOrder $sLimit";
                }else{
                    $sQuery = " SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $aColumns))."
                                FROM   $sTable s Left Join impuesto i on s.impuesto = i.id_impuesto
                                inner join categoria on categoria.id = s.categoria_id  
                                inner join stock_actual st on st.producto_id = s.id and st.producto_id = s.id
                                $sWhere $sOrder $sLimit";
                }
                
                
            //print_r($sQuery);
            //die();
            //se modificó la consulta para que trajera los valores reales
                $rResult =  $this->connection->query($sQuery);
                /* Data set length after filtering */
                $sQuery = "SELECT FOUND_ROWS() as cantidad";
                $rResultFilterTotal = $this->connection->query($sQuery);
                //$aResultFilterTotal = $rResultFilterTotal->result_array();
                $iFilteredTotal = $rResultFilterTotal->row()->cantidad;             
                $sQuery = "SELECT COUNT(".$sIndexColumn.") as cantidad FROM   $sTable s where material <> 1";
                $rResultTotal = $this->connection->query($sQuery);
                $iTotal = $rResultTotal->row()->cantidad;   
                $output = array(
                    "sEcho" => intval($_GET['sEcho']),
                    "iTotalRecords" => $iTotal,
                    "iTotalDisplayRecords" => $iFilteredTotal,
                    "aaData" => array()
                );            
                $columnas_devueltas_bd = array('imagen', 'nombre', 'codigo', 'precio_compra', 'precio_venta', 'nombre_impuesto','nombre_categoria' ,'unidades','id'); 
                foreach($rResult->result_array() as $row)

                {

                    $data = array();
                    //var_dump($row);die();
                    for($i = 0; $i<count($columnas_devueltas_bd) ; $i++){
                        //$aColumns = array('imagen', 'nombre', 'codigo', 'precio_compra', 'precio_venta', 'nombre_impuesto', 'id');
                        if($columnas_devueltas_bd[$i]=='imagen'){
                            $row[$columnas_devueltas_bd[$i]]= $this->devolver_ruta_imagen($row[ $columnas_devueltas_bd[$i] ]);
                        }
                        if($columnas_devueltas_bd[$i] == 'precio_compra' || $columnas_devueltas_bd[$i] == 'precio_venta')
                        {
                            $row[$columnas_devueltas_bd[$i]] = $this->opciones_model->formatoMonedaMostrar($row[$columnas_devueltas_bd[$i]]);
                            /*$row[$aColumns[$i]] = number_format(
                                $row[$aColumns[$i]],
                                $moneda->decimales,
                                $moneda->tipo_separador_decimales,
                                $moneda->tipo_separador_miles
                            );*/
                        }    
                        
                        $data[] = $row[ $columnas_devueltas_bd[$i] ];
                    }

                    $output['aaData'][] = $data;

                }

                
                return $output; 

           

        }

        

        public function get_by_name($name){
            $name = mysql_real_escape_string($name);
            $query = $this->connection->query("select * from producto where lower(nombre) = '".strtolower($name)."'");
//            var_dump($this->connection->last_query());
            if($query->num_rows() > 0){
                return $query->row();
            }
            return false;
        }


    public function get_term($q='',$usuario){
        
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        if($precio_almacen == 0){// Si no tiene precio diferente por almacen  
            $str_query = "SELECT stock_actual.unidades as uni, producto.vendernegativo, IF(producto.material=0, IF(producto.ingredientes=0, IF(producto.combo=0, 1, 3), IF(producto.combo=0, 2, 'NO')), 'NO') AS tipo_producto, producto.codigo_barra, producto.nombre, producto.codigo, producto.precio_compra, ubicacion as ubic, producto.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id, IF(categoria.nombre = 'GiftCard', 1, 0) AS gc from producto inner join categoria on categoria.id = producto.categoria_id left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id left join impuesto on impuesto.id_impuesto = producto.impuesto AND producto.activo = true  where   producto.material=0 and (upper(producto.nombre) like '%".strtoupper($q)."%' OR upper(producto.codigo) like '%".strtoupper($q)."%' OR upper(categoria.nombre) like '%".strtoupper($q)."%') and usuario_almacen.usuario_id = $usuario  AND producto.activo = true limit 100";
        }else{
            $str_query = "SELECT stock_actual.unidades as uni, producto.vendernegativo, IF(producto.material=0, IF(producto.ingredientes=0, IF(producto.combo=0, 1, 3), IF(producto.combo=0, 2, 'NO')), 'NO') AS tipo_producto, producto.codigo_barra, producto.nombre, producto.codigo, stock_actual.precio_compra, ubicacion as ubic, stock_actual.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id, IF(categoria.nombre = 'GiftCard', 1, 0) AS gc from producto inner join categoria on categoria.id = producto.categoria_id left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id left join impuesto on impuesto.id_impuesto = stock_actual.impuesto where  producto.material=0 and (upper(producto.nombre) like '%".strtoupper($q)."%' OR upper(producto.codigo) like '%".strtoupper($q)."%' OR upper(categoria.nombre) like '%".strtoupper($q)."%') and usuario_almacen.usuario_id = $usuario and stock_actual.activo = true  AND producto.activo = true limit 100";
        }

        $query = $this->connection->query($str_query);

        return $query->result();

    }
        
    public function get_term_combo($q='',$c='',$usuario){

        $where_combo = "";
        if($c != ""){
            $where_combo = "AND producto.codigo != '$c'";
        }

        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        if($precio_almacen == 0){// Si no tiene precio diferente por almacen  
        $str_query = "select stock_actual.unidades as uni, producto.vendernegativo, producto.codigo_barra, producto.nombre, producto.codigo, producto.precio_compra, ubicacion as ubic, producto.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id, IF(categoria.nombre = 'GiftCard', 1, 0) AS gc from producto inner join categoria on categoria.id = producto.categoria_id left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id left join impuesto on impuesto.id_impuesto = producto.impuesto  where   producto.material=0 and (upper(producto.nombre) like '%".strtoupper($q)."%' OR upper(producto.codigo) like '%".strtoupper($q)."%' OR upper(categoria.nombre) like '%".strtoupper($q)."%') and usuario_almacen.usuario_id = $usuario and producto.activo = 1 $where_combo limit 100";
        }else{
        $str_query = "select stock_actual.unidades as uni, producto.vendernegativo, producto.codigo_barra, producto.nombre, producto.codigo, stock_actual.precio_compra, ubicacion as ubic, stock_actual.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id, IF(categoria.nombre = 'GiftCard', 1, 0) AS gc from producto inner join categoria on categoria.id = producto.categoria_id left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id left join impuesto on impuesto.id_impuesto = stock_actual.impuesto  where  producto.material=0 and (upper(producto.nombre) like '%".strtoupper($q)."%' OR upper(producto.codigo) like '%".strtoupper($q)."%' OR upper(categoria.nombre) like '%".strtoupper($q)."%') and usuario_almacen.usuario_id = $usuario and stock_actual.activo = 1 $where_combo limit 100";
        }

        $query = $this->connection->query($str_query);

        return $query->result();

    }


    public function get_by_codigo($codigo, $usuario){
        
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        if($precio_almacen == 0){// Si no tiene precio diferente por almacen 
       // $sql = "select producto.vendernegativo, producto.imagen, producto.nombre, producto.codigo, producto.precio_compra,  producto.ubicacion as ubic, producto.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id, IF(categoria.nombre = 'GiftCard', 1, 0) AS gc from producto left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id INNER JOIN categoria ON producto.categoria_id = categoria.id left join impuesto on impuesto.id_impuesto = producto.impuesto where producto.material=0 and producto.codigo = '$codigo' and usuario_almacen.usuario_id = $usuario and producto.activo = 1 ";
        $query = $this->connection->query("SELECT stock_actual.unidades as uni, producto.vendernegativo, IF(producto.material=0, IF(producto.ingredientes=0, IF(producto.combo=0, 1, 3), IF(producto.combo=0, 2, 'NO')), 'NO') AS tipo_producto, producto.imagen, producto.nombre, producto.codigo, producto.precio_compra,  producto.ubicacion as ubic, producto.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id, IF(categoria.nombre = 'GiftCard', 1, 0) AS gc from producto left join stock_actual on producto.id = stock_actual.producto_id AND producto.activo=true inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id INNER JOIN categoria ON producto.categoria_id = categoria.id left join impuesto on impuesto.id_impuesto = producto.impuesto AND producto.activo = true where producto.material=0 and producto.codigo = '$codigo' and usuario_almacen.usuario_id = $usuario ");
        
    }else{
        $query = $this->connection->query("SELECT stock_actual.unidades as uni, producto.vendernegativo, IF(producto.material=0, IF(producto.ingredientes=0, IF(producto.combo=0, 1, 3), IF(producto.combo=0, 2, 'NO')), 'NO') AS tipo_producto, producto.imagen, producto.nombre, producto.codigo, stock_actual.precio_compra,  producto.ubicacion as ubic, stock_actual.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id, IF(categoria.nombre = 'GiftCard', 1, 0) AS gc from producto left join stock_actual on producto.id = stock_actual.producto_id AND producto.activo=true inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id INNER JOIN categoria ON producto.categoria_id = categoria.id left join impuesto on impuesto.id_impuesto = stock_actual.impuesto AND producto.activo = true where producto.material=0 and producto.codigo = '$codigo' and usuario_almacen.usuario_id = $usuario and stock_actual.activo = 1 ");
        }

        
        if($query->num_rows() > 0){

            $producto = $query->row_array();
            $producto_imei = $this->connection->query('SELECT producto_seriales.serial,producto_seriales.serial_vendido from producto_seriales where id_producto = "'.$producto["id"].'"');
            
            if($producto_imei->num_rows() > 0){
                $producto["imei"] = 1; // tiene imeis
            }else{
                $producto["imei"] = 0; // no tiene imeis
            }
            
            return $producto;
        }
        return null;
    }

    public function get_by_imei($producto_id, $imei, $usuario){
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        if($precio_almacen == 0){// Si no tiene precio diferente por almacen 
            $query = $this->connection->query("SELECT producto_seriales.serial,producto_seriales.serial_vendido, stock_actual.unidades as uni, producto.vendernegativo, producto.imagen, producto.nombre, producto.codigo, producto.precio_compra,  producto.ubicacion as ubic, producto.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id, IF(categoria.nombre = 'GiftCard', 1, 0) AS gc from producto left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id INNER JOIN producto_seriales ON producto.id = producto_seriales.id_producto  INNER JOIN categoria ON producto.categoria_id = categoria.id left join impuesto on impuesto.id_impuesto = producto.impuesto AND producto.activo=true where producto.material=0 and producto.id = '$producto_id' and usuario_almacen.usuario_id = $usuario and producto.activo = 1 AND producto_seriales.serial = '$imei' AND producto_seriales.serial_vendido = 0");  
        }else{
            $query = $this->connection->query("SELECT producto_seriales.serial,producto_seriales.serial_vendido, stock_actual.unidades as uni, producto.vendernegativo, producto.imagen, producto.nombre, producto.codigo, stock_actual.precio_compra,  producto.ubicacion as ubic, stock_actual.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id, IF(categoria.nombre = 'GiftCard', 1, 0) AS gc from producto left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id INNER JOIN producto_seriales ON producto.id = producto_seriales.id_producto INNER JOIN categoria ON producto.categoria_id = categoria.id left join impuesto on impuesto.id_impuesto = stock_actual.impuesto AND producto.activo=true where producto.material=0 and producto.id = '$producto_id' and usuario_almacen.usuario_id = $usuario and stock_actual.activo = 1 AND producto_seriales.serial = '$imei' AND producto_seriales.serial_vendido = 0");
        }

        if($query->num_rows() > 0){
            return $query->row_array();
        }
        return null;
    }

    public function get_by_codigo_barras($codigo)
    {
        $query = $this->connection->query('SELECT * FROM producto WHERE codigo LIKE "'.$codigo.'"');

        if($query->num_rows() > 0)
            return $query->num_rows();

        return 0;
    }

    public function validate_tipo_producto_imei(){
        $this->connection->select("*");
        $this->connection->from("producto_tipo");
        $this->connection->where("nombre","serial");
        $result = $this->connection->get();

        if($result->num_rows() <= 0){
            $data = array(
                "nombre" => "serial"
            );
            $this->connection->insert("producto_tipo",$data);
        }
    }


    public function get_by_category($categoria, $usuario, $list){ //echo $categoria;
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        if($precio_almacen == 0){// Si no tiene precio diferente por almacen  
            if($categoria!=0){
                $query = $this->connection->query("select stock_actual.unidades as uni, producto.vendernegativo, IF(producto.material=0, IF(producto.ingredientes=0, IF(producto.combo=0, 1, 3), IF(producto.combo=0, 2, 'NO')), 'NO') AS tipo_producto, producto.imagen, producto.nombre, producto.codigo, producto.precio_compra, producto.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id, IF(categoria.nombre = 'GiftCard', 1, 0) AS gc from producto left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id INNER JOIN categoria ON producto.categoria_id = categoria.id left join impuesto on impuesto.id_impuesto = producto.impuesto where producto.material=0 and categoria_id = '$categoria' and usuario_almacen.usuario_id = $usuario and producto.activo = 1 order by producto.id asc")->result();
                    }	
            else{  
                $query = $this->connection->query("select stock_actual.unidades as uni, producto.vendernegativo, IF(producto.material=0, IF(producto.ingredientes=0, IF(producto.combo=0, 1, 3), IF(producto.combo=0, 2, 'NO')), 'NO') AS tipo_producto, producto.imagen, producto.nombre, producto.codigo, producto.precio_compra, producto.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id, IF(categoria.nombre = 'GiftCard', 1, 0) AS gc from producto left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id INNER JOIN categoria ON producto.categoria_id = categoria.id left join impuesto on impuesto.id_impuesto = producto.impuesto where producto.material=0 and usuario_almacen.usuario_id = $usuario and producto.activo = 1  order by producto.id asc limit 100")->result();
            }
        }else{
            if($categoria!=0){
                $query = $this->connection->query("select stock_actual.unidades as uni, producto.vendernegativo, IF(producto.material=0, IF(producto.ingredientes=0, IF(producto.combo=0, 1, 3), IF(producto.combo=0, 2, 'NO')), 'NO') AS tipo_producto, producto.imagen, producto.nombre, producto.codigo, stock_actual.precio_compra, stock_actual.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id, IF(categoria.nombre = 'GiftCard', 1, 0) AS gc from producto left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id INNER JOIN categoria ON producto.categoria_id = categoria.id left join impuesto on impuesto.id_impuesto = stock_actual.impuesto where producto.material=0 and categoria_id = '$categoria' and usuario_almacen.usuario_id = $usuario and stock_actual.activo = 1 order by producto.id asc")->result();
                    }	
            else{  
                $query = $this->connection->query("select stock_actual.unidades as uni, producto.vendernegativo, IF(producto.material=0, IF(producto.ingredientes=0, IF(producto.combo=0, 1, 3), IF(producto.combo=0, 2, 'NO')), 'NO') AS tipo_producto, producto.imagen, producto.nombre, producto.codigo, stock_actual.precio_compra, stock_actual.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id, IF(categoria.nombre = 'GiftCard', 1, 0) AS gc from producto left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id INNER JOIN categoria ON producto.categoria_id = categoria.id left join impuesto on impuesto.id_impuesto = stock_actual.impuesto where producto.material=0 and usuario_almacen.usuario_id = $usuario and stock_actual.activo = 1  order by producto.id asc limit 100")->result();
            }
        }
        //dd($this->connection->last_query());
	  $precio_venta = 0;
	      $data = array();
          foreach ($query as $value) {
		 		  
            $query1 = "select precio from  lista_detalle_precios where id_producto = '$value->id' and id_lista_precios = '$list' ";	
			if($this->connection->query($query1)->num_rows() > 0){
			   foreach ($this->connection->query($query1)->result() as $value1) { 
			    $precio_venta = $value1->precio;
			   }
			}	
			else{
			   $precio_venta = $value->precio_venta;
			}  
			
            $imei = 0;
            $producto_imei = $this->connection->query('select producto_seriales.serial,producto_seriales.serial_vendido from producto_seriales where id_producto = "'.$value->id.'" ');
            
            if($producto_imei->num_rows() > 0){
                $imei = 1; // tiene imeis
            }
            
            $data[] = array(
                'uni' => $value->uni,
                'vendernegativo' => $value->vendernegativo,
                'tipo_producto' => $value->tipo_producto,
                'imagen' => $value->imagen,
                'nombre' => $value->nombre,
                'codigo' => $value->codigo,
                'precio_venta' => $precio_venta,
                'precio_compra' => $value->precio_compra,
                'stock_minimo' => $value->stock_minimo,
                'impuesto' => $value->impuesto,
                'imei' => $imei,
                'id' => $value->id,
                'gc' => $value->gc
            );
          }
		  
            return $data;
         // print_r($data);

      //  return null;
    }



    public function get_term_two($q='',$usuario)
    {
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        if($precio_almacen == 0){// Si no tiene precio diferente por almacen  
        $str_query = "SELECT impuesto.id_impuesto,producto.nombre, producto.codigo, producto.precio_compra, producto.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id from producto inner join categoria on categoria.id = producto.categoria_id left join stock_actual on producto.id = stock_actual.producto_id AND producto.activo = true inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id left join impuesto on impuesto.id_impuesto = producto.impuesto  where (upper(producto.nombre) like '%".strtoupper($q)."%' OR upper(categoria.nombre) like '%".strtoupper($q)."%') and usuario_almacen.usuario_id = $usuario";
        }else{
        $str_query = "SELECT impuesto.id_impuesto,producto.nombre, producto.codigo, stock_actual.precio_compra, stock_actual.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id from producto inner join categoria on categoria.id = producto.categoria_id left join stock_actual on producto.id = stock_actual.producto_id AND producto.activo=true inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id left join impuesto on impuesto.id_impuesto = producto.impuesto  where (upper(producto.nombre) like '%".strtoupper($q)."%' OR upper(categoria.nombre) like '%".strtoupper($q)."%') and usuario_almacen.usuario_id = $usuario";
        }
        
         // echo $str_query;
            $query = $this->connection->query($str_query);
            return $query->result();
    }

    public function get_by_id($id = 0)
    {
        $query = $this->connection->query("SELECT producto.*, IF(producto.material=0, IF(producto.ingredientes=0, IF(producto.combo=0, 1, 3), IF(producto.combo=0, 2, 'NO')), 'NO') AS tipo_producto FROM  producto WHERE id = '".$id."'");

        return $query->row_array();                             
    }

    public function get_impuesto_by_id($id = 0)
    {
        $query = $this->connection->query("SELECT * FROM  impuesto WHERE id_impuesto = '".$id."'");

        return $query->row_array();                             
    }

    public function cloneProducto($id,$almacenes=null){
        //CLonamos el producto       
        $sql = "SHOW COLUMNS FROM producto LIKE 'destacado_tienda'";
        $existeCampo = $this->connection->query($sql)->result();
        if(count($existeCampo) == 0){
            $sql = "ALTER TABLE `producto`   
                ADD COLUMN `destacado_tienda` INT(11) UNSIGNED NULL AFTER `id_proveedor`;
            ";
            $this->connection->query($sql);
        }
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        if($precio_almacen == 1){
            $query="SELECT categoria_id, MD5(RAND()) AS codigo, CONCAT(nombre,'_Copy_',FLOOR(1000+(RAND()*8999))) AS nombre, codigo_barra,
            sa.precio_compra,sa.precio_venta, sa.stock_minimo,descripcion,sa.activo,sa.impuesto,
            fecha,imagen,material,ingredientes,combo,unidad_id,stock_maximo,
            sa.fecha_vencimiento,ubicacion,ganancia,muestraexist,tienda,
            imagen1,imagen2,imagen3,imagen4,imagen5,id_proveedor,
            destacado_tienda,id_tipo_producto 
            FROM  producto 
            INNER JOIN stock_actual sa ON producto.id=sa.producto_id
            WHERE producto.id ='".$id."' LIMIT 1";
        }else{
            $query="SELECT categoria_id, MD5(RAND()) AS codigo, CONCAT(nombre,'_Copy_',FLOOR(1000+(RAND()*8999))) AS nombre, codigo_barra,precio_compra,precio_venta,stock_minimo,descripcion,activo,impuesto,fecha,imagen,material,ingredientes,combo,unidad_id,stock_maximo,fecha_vencimiento,ubicacion,ganancia,muestraexist,tienda,imagen1,imagen2,imagen3,imagen4,imagen5,id_proveedor,destacado_tienda,id_tipo_producto FROM  producto WHERE id =".$id;
        }

        $query= $this->connection->query($query);

        $this->connection->insert_batch("producto", $query->result_array());        
        $pn=$this->connection->insert_id();  
        
        $producto =  $query->result_array();
        // Clonacion del material 
       /* if($producto[0]['material'] == 1){
            //vericamos los ingredientes
            $query_ing = $this->connection->query("SELECT $pn as id_producto,id_ingrediente,cantidad FROM  producto_ingredientes WHERE id_producto = '".$id."'");
            if(count($query_ing->result_array()))
                $this->connection->insert_batch("producto_ingredientes", $query_ing->result_array());    
        }*/
        /*
        if($producto[0]['combo'] == 1){
            //vericamos los ingredientes
            $query_combo = $this->connection->query("SELECT id_combo, $pn as id_producto, cantidad FROM  producto_combos WHERE id_producto = '".$id."'");
            if(count($query_combo->result_array()))
                $this->connection->insert_batch("producto_combos", $query_combo->result_array());    
        }*/

        //Verificamos si tiene adicionales  y modificaciones e ingredientes
        $query_adicional = $this->connection->query("SELECT $pn as id_producto,id_adicional,cantidad,precio FROM  producto_adicional WHERE id_producto = '".$id."'");
        if(count($query_adicional->result_array()))
            $this->connection->insert_batch("producto_adicional", $query_adicional->result_array());    

        $query_modificacion = $this->connection->query("SELECT $pn as id_producto,nombre FROM  producto_modificacion WHERE id_producto = '".$id."'");
            if(count($query_modificacion->result_array()))
                $this->connection->insert_batch("producto_modificacion", $query_modificacion->result_array());               
                                
        $query_ing = $this->connection->query("SELECT $pn as id_producto,id_ingrediente,cantidad FROM  producto_ingredientes WHERE id_producto = '".$id."'");
            if(count($query_ing->result_array()))
                $this->connection->insert_batch("producto_ingredientes", $query_ing->result_array()); 
                
        $query_combo = $this->connection->query("SELECT id_combo, $pn as id_producto, cantidad FROM  producto_combos WHERE id_producto = '".$id."'");
            if(count($query_combo->result_array()))
                $this->connection->insert_batch("producto_combos", $query_combo->result_array());    

        //ingresar en stock actual
        if(!empty($almacenes)){
            foreach($almacenes as $key => $almacen){
                if($precio_almacen == 1){
                    $data_stock_actual = array(
                        'almacen_id' => $almacen->id,
                        'producto_id' => $pn,
                        'unidades' => 0 ,
                        'precio_compra'=>$producto[0]['precio_compra'],
                        'precio_venta'=>$producto[0]['precio_venta'],
                        'impuesto'=>$producto[0]['impuesto'],
                        'stock_minimo'=>$producto[0]['stock_minimo']
                    );  
                }
                else{
                    $data_stock_actual = array(
                        'almacen_id' => $almacen->id,
                        'producto_id' => $pn,
                        'unidades' => 0 
                    );  
                }
                            
                $this->productos->generaStockInicial($data_stock_actual);               
            }
        }

        return $pn;        
    }

    public function deleteProducto($id){
        //CLonamos el producto
        $query = $this->connection->query("SELECT categoria_id, codigo, nombre,codigo_barra,precio_compra,precio_venta,stock_minimo,descripcion,activo,impuesto,fecha,imagen,material,ingredientes,combo,unidad_id,stock_maximo,fecha_vencimiento,ubicacion,ganancia,muestraexist,tienda,imagen1,imagen2,imagen3,imagen4,imagen5,id_proveedor,destacado_tienda,id_tipo_producto FROM  producto WHERE id = '".$id."'");

        $producto =  $query->result_array();

        // Clonacion del material 
       /* if($producto[0]['material'] == 1){
            //vericamos los ingredientes
            
            $this->connection->where('id_producto', $id);
            $this->connection->delete("producto_ingredientes");  
        }

        if($producto[0]['combo'] == 1){
            
            $this->connection->where('id_producto', $id);
            $this->connection->delete("producto_combos");  
            
           
        }*/

        //Verificamos si tiene adicionales  y modificaciones
        $this->connection->where('id_producto', $id);
        $this->connection->delete("producto_adicional");          

        $this->connection->where('id_producto', $id);
        $this->connection->delete("producto_modificacion");  

        $this->connection->where('id_producto', $id);
        $this->connection->delete("producto_ingredientes"); 
        
        $this->connection->where('id_producto', $id);
        $this->connection->delete("producto_combos");  
                
        $this->connection->where('id', $id);
        $this->connection->delete("producto");        

        return true;        
    }
    
    public function get($id=null, $codigo=null, $nombre=null)
    {
        if($id)
            $this->connection->where('producto.id', $id);

        if($codigo)
            $this->connection->where('TRIM(producto.codigo)', $codigo);
        
        if($nombre)
            $this->connection->where('TRIM(producto.nombre)', $nombre);


        $query = $this->connection->get('producto');
        if(!$query)
        {
            return false;
        }
        
        return $query->result();
    }

    public function get_ingredientes($id = 0){

        $query = $this->connection->query("SELECT * FROM  producto_ingredientes WHERE id_producto = '".$id."'");

        $ingredientes = $query->result();

        foreach ($ingredientes as $key => $value) {

                $producto = $this->get_by_id($value->id_ingrediente);
                $producto['cantidad_ingrediente'] = $value->cantidad ;
                $ingredientes[$key] = $producto;
           
        }
        return $ingredientes;         
    }

    public function get_productos_combo($id = 0){
  $productos='';
        $query = $this->connection->query("SELECT * FROM  producto_combos WHERE id_combo = '".$id."'");

        $productos_combo = $query->result();

        foreach ($productos_combo as $key => $value) {

                $producto = $this->get_by_id($value->id_producto);
                $producto['cantidad_producto'] = $value->cantidad ;                
                $productos[$key] = $producto;
           
        }
        return $productos;         
    }

    public function delete_ingredientes($id = 0){
        $this->connection->where('id_producto', $id);
        $this->connection->delete("producto_ingredientes");  
    }

    public function delete_productos_combo($id = 0){
        $this->connection->where('id_combo', $id);
        $this->connection->delete("producto_combos");  
    }
    private function limpiar($String){
        $String = str_replace(array('�','�','�','�','�','�'),"a",$String);
        $String = str_replace(array('�','�','�','�','�'),"A",$String);
        $String = str_replace(array('�','�','�','�'),"I",$String);
        $String = str_replace(array('�','�','�','�'),"i",$String);
        $String = str_replace(array('�','�','�','�'),"e",$String);
        $String = str_replace(array('�','�','�','�'),"E",$String);
        $String = str_replace(array('�','�','�','�','�','�'),"o",$String);
        $String = str_replace(array('�','�','�','�','�'),"O",$String);
        $String = str_replace(array('�','�','�','�'),"u",$String);
        $String = str_replace(array('�','�','�','�'),"U",$String);
        $String = str_replace(array('[','^','�','`','�','~',']'),"",$String);
        $String = str_replace("�","c",$String);
        $String = str_replace("�","C",$String);
        $String = str_replace("�","n",$String);
        $String = str_replace("�","N",$String);
        $String = str_replace("�","Y",$String);
        $String = str_replace("�","y",$String);   
        $String = str_replace("&aacute;","a",$String);
        $String = str_replace("&Aacute;","A",$String);
        $String = str_replace("&eacute;","e",$String);
        $String = str_replace("&Eacute;","E",$String);
        $String = str_replace("&iacute;","i",$String);
        $String = str_replace("&Iacute;","I",$String);
        $String = str_replace("&oacute;","o",$String);
        $String = str_replace("&Oacute;","O",$String);
        $String = str_replace("&uacute;","u",$String);
        $String = str_replace("&Uacute;","U",$String);
        return $String;
    }

    private function datosBasicosProductos($nombre_almacen,$nombre_categoria,$nombre_impuesto){
        $almacen = $this->connection->select('id')->from('almacen')->like('nombre', $nombre_almacen)->limit(1)->get();
        if($almacen->num_rows() > 0){
            foreach ($almacen->result() as $dat)
            {
                $id_alm  = $dat->id;
            } 
        } else {
            $id_alm  = false;
        }
        $categoria = $this->connection->select('id')->from('categoria')->like('nombre', $nombre_categoria)->limit(1)->get();
        if($categoria->num_rows() > 0){
            foreach ($categoria->result() as $dat)
            {
                $id_cate  = $dat->id;
            } 
        } else {
            $id_cate  = false;
        }
        $impuesto = $this->connection->select('id_impuesto')->from('impuesto')->like('nombre_impuesto', $nombre_impuesto)->limit(1)->get();
        if($impuesto->num_rows() > 0){
            foreach ($impuesto->result() as $dat)
            {
                $id_uni = $dat->id_impuesto;
            } 
        } else {
            $id_uni = false;
        }
        return array ($id_alm,$id_cate,$id_uni);
    }

	/* 
	Nombre del metodo: add_csv	
	Fecha: 27/10/15
	Descripcion: Almacena en bd los datos de los productos contenidos en el excel, validando si existe ya categoria y el almacen. Si existe el producto solo almacena el stock.
	
	Cambios:
	Fecha					||			Creador			||			Descripci�n
	
	
	
	*/
   public function add_csv($data, $usuario){
        $nombre_categoria = trim($this->limpiar(utf8_decode($data['categoria_id'])));
        $nombre_almacen = trim($this->limpiar(utf8_decode($data['almacen'])));
        $nombre_impuesto = trim($data['impuesto']);
        $id_imp = 0; 
        $id_alm = 0; 
        $res_data = array(
            'almacen_id' => 0,
            'producto_id' => 0,
            'existencias' => 0
        );
        $almcant = 0;

        list($id_alm, $id_cate, $id_imp) = $this->datosBasicosProductos($nombre_almacen,$nombre_categoria,$nombre_impuesto);        
        if(!$id_alm){ return array(FALSE,"No hay almacen vinculado o no existe el almacen", []); }
        if(!$id_cate){ return array(FALSE,"La categor�a no existe", []); }

        //almacen_id para inventario
        $res_data['almacen_id'] = $id_alm;
        
        $this->connection->where('codigo', $data['codigo']);
	    $this->connection->where('nombre', $data['nombre']);
        $existencia_productos = $this->connection->select('id')->from('producto')->limit(1)->get();
        if ($existencia_productos->num_rows() > 0)
        {
            foreach ($existencia_productos->result() as $dat)
            {
                $producto_exist  = $dat->id;
            }

            //producto_id y existencias para inventario cuando el producto existe
            $existencias = $this->obtener_existencias($producto_exist, $id_alm);
            if(count($existencias) > 0)
            {
                $res_data['existencias'] = $existencias[0]['unidades'];
                return array(FALSE, "El producto ya existe en este almacen", []);
            }

            $res_data['producto_id'] = $producto_exist;
		
            $data_stock_actual = array(
                'almacen_id'    => $id_alm,
                'producto_id'   => $producto_exist,
                'unidades'      => $data['cantidad']
            ); 
			 
            $this->connection->insert('stock_actual', $data_stock_actual);
        } else {
            $array_datos = array(
                "categoria_id"  => $id_cate,
                "codigo"        => $data['codigo'],
                "nombre"        => $data['nombre'],
                "precio_compra" => $data['precio_compra'],
                "precio_venta"  => $data['precio_venta'],
                "descripcion"   => $data['descripcion'], 
                "stock_minimo"   => $data['stockmin'],                  
                "impuesto"      => $id_imp,
                "imagen"        => ''
                );  
            $this->db->trans_start();
            $this->connection->insert("producto", $array_datos);
            $id = $this->connection->insert_id();
            $data_stock_actual = array(
                'almacen_id'    => $id_alm,
                'producto_id'   => $id,
                'unidades'      => $data['cantidad']
            );

            //producto_id y existencias para inventario cuando el producto NO existe
            $res_data['producto_id'] = $id;
            $res_data['existencias'] = $data['cantidad'];

            $this->connection->insert('stock_actual', $data_stock_actual);
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE)
            {
                return  array(FALSE, "Falló el almacenamiento de los productos", $res_data);
            }
        }
        
        return  array(TRUE, "Exito", $res_data);                
    }

    public function createProducto($data, $usuario){
        
        $this->connection->insert("producto", $data);
        
        $id = $this->connection->insert_id();

        return $id;
        
    }

    public function add($data, $usuario) {   
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        $this->connection->insert("producto", $data);
        $id = $this->connection->insert_id();
        $data_stock_actual = array();
        $data_stock_diario = array();
        
        foreach ($_POST['Stock'] as $key => $value) {
            if($precio_almacen == 0) {
                $data_stock_actual[] = array(
                    'almacen_id' => $key,
                    'producto_id' => $id,
                    'unidades' => $value
                );
            } else {
                $Stock_minimor=isset($_POST['Stock_minimo'])? $_POST['Stock_minimo'][$key]: 0;
                $Precio_comprar=isset($_POST['Precio_compra'])? $_POST['Precio_compra'][$key]: $data['precio_compra'];
                $Precio_ventar=isset($_POST['Precio_venta'])? $_POST['Precio_venta'][$key]: $data['precio_venta'];
                //$Impuestor=isset($_POST['Impuesto'])? $_POST['Impuesto'][$key]: 0;
                $Impuestor=isset($_POST['id_impuesto'])? $_POST['id_impuesto']: 0;
                $Fecha_vencimientor=isset($_POST['Fecha_vencimiento'])? $_POST['Fecha_vencimiento'][$key]: 0;
                //$Activor=isset($_POST['Activo'])? $_POST['Activo'][$key]: 0;
                $Activor=isset($_POST['activo']) ? 1 : 0;

                $data['precio_compra']=$Precio_comprar;
                $data['precio_venta']=$Precio_ventar;

                $data_stock_actual[] = array(
                    'almacen_id' => $key,
                    'producto_id' => $id,                                
                    'unidades' => $value,
                    'stock_minimo' => $Stock_minimor,
                    'precio_compra' => $Precio_comprar,
                    'precio_venta' => $Precio_ventar,
                    'impuesto' => $Impuestor,
                    'fecha_vencimiento' => $Fecha_vencimientor,
                    'activo' => $Activor
                );
            }

            if($value > 0){
                $data_stock_diario[] =  array(
                    'producto_id' =>$id,
                    'almacen_id' => $key,
                    'fecha' => date('Y-m-d'),
                    'unidad' => $value,
                    'precio' => $data['precio_venta'],
                    'usuario' => $usuario,
                    'razon' => 'E'
                );

                //insertar movimiento
                $total_inventario=($data['precio_compra'] * $value);
                $this->connection->insert('movimiento_inventario', 
                array('fecha' => date('Y-m-d H:i:s'), 
                    'almacen_id' => $key, 
                    'tipo_movimiento' =>'entrada_producto', 
                    'user_id' => $usuario, 
                    'total_inventario' => $total_inventario,
                    'proveedor_id' => $data['id_proveedor'])
                );

                $id_inventario = $this->connection->insert_id();

                $data_detalles[] = array(
                    'id_inventario' => $id_inventario,
                    'codigo_barra' => $data['codigo'],
                    'cantidad' => $value,
                    'precio_compra' => $data['precio_compra'],
                    'existencias' => 0,
                    'nombre' => $data['nombre'],
                    'total_inventario' => $total_inventario,
                    'producto_id' =>$id                                               
                );
            }
        }
        
        if(!empty($data_stock_actual)){
            $this->connection->insert_batch('stock_actual', $data_stock_actual);    
        }

        if(!empty($data_stock_diario)){
            $this->connection->insert_batch('stock_diario', $data_stock_diario);    
        }

        if(!empty($data_detalles)){                
            $this->connection->insert_batch("movimiento_detalle", $data_detalles);
        }

        return $id;
    }

    public function generaStockInicial($data_stock_actual){
        $this->connection->insert('stock_actual', $data_stock_actual);    
        return true;
    }

    /*Rutina para saber si el c�digo del producto ya existe
        *Recibe valor del c�digo
        *Retorna "1" si el c�digo ya existe
        *Retorna "0" si no existe
    */
    public function validatecodigo($codigo){
        $result = 0;
		$response = 0;
        $query =  "SELECT codigo FROM producto WHERE codigo = '".$codigo."'";

        foreach($this->connection->query($query)->result() as $value) {
            $result = $value->codigo;           
        }

        if($result == 0){
            $response = 0;
        } else{
		   $response = 1;
        }
        
        return $result;
    }

    /*Rutina para saber si el c�digo del producto ya existe
        *Recibe valor del c�digo
        *Retorna "1" si el c�digo ya existe
        *Retorna "0" si no existe
    */
    public function validatecodigoPuntosLeal($codigo){
        $result = 0;
		$response = 0;
        $query =  "SELECT codigo_puntos_leal FROM producto WHERE codigo_puntos_leal = '".$codigo."'";

        foreach($this->connection->query($query)->result() as $value) {
            $result = $value->codigo_puntos_leal;           
        }

        if($result == 0){
            $response = 0;
        } else{
		   $response = 1;
        }
        
        return $result;
    }  

    /*Agregar ingredientes*/
    public function addIngredient($data,$cant=0){
        if($cant>0){
            $this->connection->insert_batch("producto_ingredientes", $data);         
        }
        else{
            $this->connection->insert("producto_ingredientes", $data);
        }                         
         return true;
    }
    

    public function deleteIngredientById($id){
        $this->connection->where('id_producto', $id);        
        $this->connection->delete('producto_ingredientes');
        return true;
        
   }

    public function deleteAdicionalById($id){
        $this->connection->where('id_producto', $id);        
        $this->connection->delete('producto_adicional');
        return true;
    }

    public function delete_Adicion($where){
        $this->connection->where($where);        
        $this->connection->delete('producto_adicional');
        return true;
    }

    public function deleteIngrediente($where){
        $this->connection->where($where);
        $this->connection->delete("producto_ingredientes");  
    }

    public function addAdicional($data){
        $this->connection->insert_batch("producto_adicional", $data);
        return true;
    }

    public function addModificacion($data){
        $this->connection->insert("producto_modificacion", $data);
        return true;
    }

    public function verificarexistenciaModificacion($data){
        $this->connection->where($data);        
        $this->connection->from("producto_modificacion");
        $this->connection->select("*");      
        $query = $this->connection->get();
        if($query->num_rows() > 0){
            return 1;
        }
        else {
            return 0;
        }         
    }

    public function deleteModificacionById($id){
        $this->connection->where('id_producto', $id);        
        $this->connection->delete('producto_modificacion');
        return true;
    }

    public function withIngredients($id_producto){
        $this->connection->where('id', $id_producto);
        $this->connection->update("producto", array('ingredientes'=>1) );
    }

    public function notWithIngredients($id_producto){
        $this->connection->where('id', $id_producto);
        $this->connection->update("producto", array('ingredientes'=>0) );
    }
    
    /*Agregar productos a combo*/
    public function addProductCombo($data){
         $this->connection->insert("producto_combos", $data);
    }

    public function isCombo($id_producto){
        $this->connection->where('id', $id_producto);
        $this->connection->update("producto", array('combo'=>1) );
    }

    public function isNotCombo($id_producto){
        $this->connection->where('id', $id_producto);
        $this->connection->update("producto", array('combo'=>0) );
    }

    public function updateById($data){
        $this->connection->where('id', $data['id']);
        
        $this->connection->update("producto", $data);
    }

    public function update($data, $usuario)
    {          
                
        $precio_almacen = $this->opciones->getOpcion('precio_almacen');
        $this->connection->where('id', $data['id']);

        $this->connection->update("producto", $data);

                $data_stock_diario = array();
                $error_men="";
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

                             //ingreso el movimiento
                             $total_inventario=($data['precio_compra'] * $value);
                            $this->connection->insert('movimiento_inventario', 
                            array('fecha' => date('Y-m-d H:i:s'), 
                                'almacen_id' => $key, 
                                'tipo_movimiento' =>'entrada_producto', 
                                'user_id' => $usuario, 
                                'total_inventario' => $total_inventario,
                                'proveedor_id' => $data['id_proveedor']));

                            $id_inventario = $this->connection->insert_id();

                            $data_detalles[] =  array(

                                'id_inventario' => $id_inventario

                                , 'codigo_barra' => $data['codigo']

                                , 'cantidad' => $value

                                , 'precio_compra' => $data['precio_compra']

                                ,'existencias' => $values_actual->unidades

                                , 'nombre' => $data['nombre']

                                ,'total_inventario' => $total_inventario
                                
                                , 'producto_id' =>$data['id']                                               

                            );
                    }else{
                        if($value!=0){
                            $alma=$this->connection->where('id', $key)->get('almacen')->row();
                            $error_men.="<br>No se actualizó la cantidad actual del producto en el almacén: ".$alma->nombre." por ser menor a 0 ";                                                
                        }
                    }

                }
                
                if(!empty($data_stock_diario)){
                    $this->connection->insert_batch('stock_diario', $data_stock_diario);
                   // movimiento_inventario - //id_almacen,tipo_movimiento, user_id,total_inventario, proveedor
                    // movimiento-detalle - // id_inventario - codigo_barra-cantidad - precio_comprea - existencia - nombre - total_inventario - producto_id
                   
                    if(!empty($data_detalles)){                
                        $this->connection->insert_batch("movimiento_detalle", $data_detalles);
                    }   
                }
                
               
                // Si tiene precios por almacen se actualizan estos campos
                if($precio_almacen == 1){
                    
                    foreach ($_POST['Stock_minimo'] as $key => $value) {
                        $this->connection->where('almacen_id', $key)
                            ->where('producto_id', $data['id'])
                            ->update('stock_actual', array('stock_minimo' => $value));
                    }
                    
                    foreach ($_POST['Precio_compra'] as $key => $value) {
                        $this->connection->where('almacen_id', $key)
                            ->where('producto_id', $data['id'])
                            ->update('stock_actual', array('precio_compra' => $value));
                    }
                    
                    foreach ($_POST['Precio_venta'] as $key => $value) {
                        $this->connection->where('almacen_id', $key)
                            ->where('producto_id', $data['id'])
                            ->update('stock_actual', array('precio_venta' => $value));
                    }
                    
                    foreach ($_POST['Impuesto'] as $key => $value) {
                        $this->connection->where('almacen_id', $key)
                            ->where('producto_id', $data['id'])
                            ->update('stock_actual', array('impuesto' => $value));
                    }
                    
                    foreach ($_POST['Fecha_vencimiento'] as $key => $value) {
                        $this->connection->where('almacen_id', $key)
                            ->where('producto_id', $data['id'])
                            ->update('stock_actual', array('fecha_vencimiento' => $value));
                    }
                    
                    foreach ($_POST['Activo'] as $key => $value) {
                        $this->connection->where('almacen_id', $key)
                            ->where('producto_id', $data['id'])
                            ->update('stock_actual', array('activo' => $value));
                    }
                }
                      return $error_men;                                     

    }

    

    public function delete($id){    

        /*Elimina producto*/
        $this->connection->where('id', $id);

        $this->connection->delete("producto");  

        /*Elimina ingredientes de producto*/
        $this->connection->where('id_producto', $id);

        $this->connection->delete("producto_ingredientes");  

        /*Elimina productos de combo*/
        $this->connection->where('id_combo', $id);

        $this->connection->delete("producto_combos");  


    }

        

    public function excel(){

        //$this->connection->select("id_producto, nombre, descripcion, precio, nombre_impuesto, porciento");

        //$this->connection->from("productof");

        //$this->connection->join('impuesto', 'impuesto.id_impuesto = productof.id_impuesto');

        //$str_query = "select * from producto inner join impuesto i on  producto.impuesto = i.id_impuesto";

        $str_query = "SELECT p.*,i.*, c.nombre AS categoria FROM producto p
                        INNER JOIN impuesto i ON  `p`.`impuesto` = `i`.`id_impuesto` 
                        LEFT JOIN categoria c ON  `p`.`categoria_id` = `c`.`id`";

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


    public function get_term_existencias($q, $almacen) {   

            $sql = "SELECT p.id, s.unidades, p.codigo, p.nombre, p.precio_compra,p.codigo_barra from producto p inner join stock_actual s on s.producto_id = p.id and p.activo = true WHERE (p.nombre LIKE '%$q%' or p.codigo LIKE '%$q%') and s.almacen_id = $almacen LIMIT 0,50";

            $query = $this->connection->query($sql);

            return $query->result_array();

    }

    public function get_codigo_existencias($q, $almacen) {   

            $sql = "SELECT p.id, s.unidades, p.codigo, p.nombre, p.precio_compra,p.codigo_barra from producto p inner join stock_actual s on s.producto_id = p.id WHERE (p.codigo liKE '$q' ) and s.almacen_id = $almacen LIMIT 0,50";

            $query = $this->connection->query($sql);

            return $query->result_array();

    }


    public function obtener_existencias($producto_id, $almacen_id) {
        $sql = "SELECT p.id, s.unidades, p.codigo, p.nombre, p.precio_compra FROM producto p INNER JOIN stock_actual s ON s.producto_id = p.id WHERE p.id = $producto_id AND s.almacen_id = $almacen_id LIMIT 0,30";
        $query = $this->connection->query($sql);
        return $query->result_array();
    }

        

        public function excel_add($array_datos){

            $query = "INSERT INTO `producto` (`nombre`, `descripcion`, `precio`, `id_impuesto`) VALUES ('".$array_datos['nombre']."', '".$array_datos['descripcion']."', ".$array_datos['precio'].", ".$array_datos['id_impuesto'].");";

            $this->connection->query($query);

        }

    public function get_base($campos)
    {
        $fields = [];

        foreach ($campos as $campo) 
        {
            switch ($campo) {
                case 'id':
                    array_push($fields, 'id');
                break;
                case 'nombre':
                    array_push($fields, 'nombre');
                break;
                case 'codigo':
                    array_push($fields, 'codigo');
                break;
                case 'Codigo':
                    array_push($fields, 'codigo');
                break;
                case 'Categoria':
                    array_push($fields, '(SELECT nombre FROM categoria WHERE categoria.id = producto.categoria_id) AS categoria');
                break;
                case 'Precio compra':
                    array_push($fields, 'precio_compra');
                break;
                case 'Precio venta':
                    array_push($fields, 'precio_venta');
                break;
                case 'Stock minimo':
                    array_push($fields, 'stock_minimo');
                break;
                case 'Stock maximo':
                    array_push($fields, 'stock_maximo');
                break;
                case 'Impuesto':
                    array_push($fields, '(SELECT porciento FROM impuesto WHERE id_impuesto = impuesto) AS impuesto');
                break;
                case 'Descripcion':
                    array_push($fields, 'descripcion');
                break;
                case 'Activo':
                    array_push($fields, 'IF(activo = 1, "SI", "NO")');
                break;
                case 'Fecha vencimiento':
                    array_push($fields, 'fecha_vencimiento');
                break;
               
                case 'Venta negativo':
                    array_push($fields, 'IF(vendernegativo = 1, "SI", "NO")');
                break;

                case 'Proveedor':   
                    array_push($fields, '(SELECT nombre_comercial FROM proveedores WHERE proveedores.id_proveedor = producto.id_proveedor) AS proveedor');
                break;

                case 'Tienda':
                    array_push($fields, 'IF(tienda = 1, "SI", "NO")');
                break;
            }
        }

        $query = 'SELECT '.implode(',', $fields).' FROM producto ORDER BY nombre ASC';

        $s_productos = $this->connection->query($query);

        if(!is_bool($s_productos))
            return $s_productos->result_array();
        else
            return false;
    }

    public function update_base($data)
    {   
        if($this->backup_table('producto'))
        {
            $resultado = @$this->connection->update_batch('producto', $data, 'id');
            return $resultado;
        } else {
            return false;
        }
    }

    protected function backup_table($table)
    {
        $time = date('YmdHis');
        $res_table = $this->connection->query('CREATE TABLE '.$table.$time.' LIKE '.$table);
        $res_datas = $this->connection->query('INSERT '.$table.$time.' SELECT * FROM '.$table);

        return $res_table && $res_datas;
    }
    
    public function consultarWC()
    {
        $sql = "SELECT
                *
                FROM `stock_actual`
                ";
        
        $productos = $this->connection->query($sql);
        var_dump($this->connection);
        return $productos->result_array();;
    }
    
    public function eliminarConfirmacion($id)
    {
                //var_dump($id);die;
        $codigo = $this->connection->get_where("producto",array("id"=>$id))->row()->codigo;
        $ventas = $this->connection->get_where("detalle_venta",array("codigo_producto"=>$codigo))->result();
        if(count($ventas) != 0)
        {
            return 2;
        }
        
        $combos = $this->connection->get_where("producto_combos",array("id_producto"=>$id))->result();
        $ingredientes = $this->connection->get_where("producto_ingredientes",array("id_ingrediente"=>$id))->result();
        
        if(count($combos) != 0 || count($ingredientes) != 0)
        {
            return 3;
        }
        
        return 1;
        
    }
    

    public function get_by_code($code){
        $query = $this->connection->select('id,nombre')
                ->where('codigo',$code)
                ->get('producto');
        
        if(!$query)
        {
            return false;
        }
        return $query->row();
    }
    
    public function get_compuestos()
    {//Almovit
        $query = $this->connection->query("SELECT * FROM producto s LEFT JOIN impuesto i 
                                            ON s.impuesto = i.id_impuesto 
                                            WHERE s.activo = 1
                                            AND ingredientes = 1 
                                            ORDER BY nombre ASC");
        return $query->result();
    }

    public function get_compuestos_by_produccion()
    {
        $query = $this->connection->query("SELECT * FROM producto s LEFT JOIN impuesto i 
                                            ON s.impuesto = i.id_impuesto 
                                            WHERE ingredientes = 1 
                                            ORDER BY nombre ASC");
        return $query->result();
    }
    
    public function codigoBarras($codigo = false)
    {
        if($codigo != false)
        {
            return $this->connection->get_where('producto',array('codigo'=>$codigo))->row();
        }
        return false;
    }
    
    public function set_producto_ingredientes($array){
        
        $query = null; //emptying in case 

        $id_producto   = $array['id_producto']; //getting from post value
        $id_ingrediente = $array['id_ingrediente'];

        $query = $this->connection->get_where('producto_ingredientes', array(//making selection
            'id_producto' => $id_producto,
            'id_ingrediente' => $id_ingrediente,
        ));
        
        $count = $query->num_rows(); //counting result from query
        
        if ($count === 0) {
            $this->connection->insert('producto_ingredientes', $array);
        }else{
            $array['id'] = $query->row()->id;
            $this->connection->replace('producto_ingredientes', $array);
        }
        
        return $this->connection->affected_rows();

    }
    
    public function get_producto_final(){
        $query = $this->connection->select('*')
                ->where('material',0)
                ->where('ingredientes',0)
                ->where('combo',0)
                ->where('activo',1)
                ->order_by('nombre','asc')
                ->get('producto');
        
        if(!$query)
        {
            return false;
        }
        return $query->result_array();
    }
    /*
    public function get_producto_material(){
        $query = $this->connection->select('*')
                ->where('material',1)
                ->order_by('nombre','asc')
                ->get('producto');
        
        if(!$query)
        {
            return false;
        }
        return $query->result_array();
    }*/

    public function get_producto_material($id_product = null)
    {
            $usuario=$this->session->userdata('user_id');
            $str_query = "select impuesto.id_impuesto,producto.nombre, producto.codigo, producto.precio_compra, producto.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id from producto inner join categoria on categoria.id = producto.categoria_id left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id left join impuesto on impuesto.id_impuesto = producto.impuesto  where usuario_almacen.usuario_id = $usuario AND producto.material= 1 ORDER BY nombre;";
            if($id_product != null && is_numeric($id_product)){
                $str_query = "select impuesto.id_impuesto,producto.nombre, producto.codigo, producto.precio_compra, producto.precio_venta, stock_actual.unidades as stock_minimo, IFNULL(impuesto.porciento, 0) as impuesto, producto.imagen, producto.id from producto inner join categoria on categoria.id = producto.categoria_id left join stock_actual on producto.id = stock_actual.producto_id inner join usuario_almacen on usuario_almacen.almacen_id = stock_actual.almacen_id left join impuesto on impuesto.id_impuesto = producto.impuesto  where usuario_almacen.usuario_id = $usuario AND producto.material= 1 AND producto.id <> $id_product ORDER BY nombre;";
            }
            
            $query = $this->connection->query($str_query);
            return $query->result_array();
    }



    public function check_tabla_seriales(){

       $crear_tabla_seriales = " CREATE TABLE IF NOT EXISTS `producto_seriales`(  
            `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'autoincremental de la table y primary key',
            `fecha_creacion` DATETIME COMMENT 'fecha en que se creo el registro',
            `creado_por` INT COMMENT 'el usuario que creo el registro',
            `fecha_modificacion` DATETIME COMMENT 'ultima fecha en que se modifico el registro',
            `modificado_por` INT COMMENT 'el usuario que realizo la ultima modificacion',
            `id_producto` INT(11) COMMENT 'referencia la tabla productos el producto que contiene el serial',
            `serial` VARCHAR(250) COMMENT 'el serial',
            `serial_vendido` TINYINT DEFAULT 0 COMMENT '0 para no 1 para si, si el serial esta vendido para que no se liste',
            `id_venta` int(11) DEFAULT NULL COMMENT 'venta asociada si fue vendido el imei',
            `id_detalle_venta` int(11) DEFAULT NULL,
            PRIMARY KEY (`id`),
            CONSTRAINT `fk_producto_seriales` FOREIGN KEY (`id_producto`) REFERENCES `producto`(`id`) ON UPDATE CASCADE ON DELETE CASCADE
         )
        COMMENT='tabla que contiene los seriales de un producto';";
       // echo $crear_tabla_seriales;die();
        $this->connection->query($crear_tabla_seriales);

        //id_venta
        $sql = "SHOW COLUMNS FROM producto_seriales LIKE 'id_venta'";
        $existeCampo = $this->connection->query($sql)->result();
        if(count($existeCampo) == 0){
            $sql = "ALTER TABLE `producto_seriales`   
                ADD COLUMN `id_venta` INT(11) DEFAULT NULL AFTER `serial_vendido`;
            ";
            $this->connection->query($sql);
        }

         //id_detalle_venta
         $sql = "SHOW COLUMNS FROM producto_seriales LIKE 'id_detalle_venta'";
         $existeCampo = $this->connection->query($sql)->result();
         if(count($existeCampo) == 0){
             $sql = "ALTER TABLE `producto_seriales`   
                 ADD COLUMN `id_detalle_venta` INT(11) DEFAULT NULL AFTER `id_venta`;
             ";
             $this->connection->query($sql);
         }
    }

    public function check_ventas_negativo(){
        $sql = "SHOW COLUMNS FROM producto LIKE 'vendernegativo'";
        $existeCampo = $this->connection->query($sql)->result();
        if(count($existeCampo) == 0)
        {
            $sql = "ALTER TABLE `producto`   
                ADD COLUMN `vendernegativo` TINYINT(1) DEFAULT 1 AFTER `precio_venta`;
            ";

            $this->connection->query($sql);
        }
    }

    public function get_productos_almacen($id_almacen){
        
        $this->connection->select('p.id, s.unidades, p.codigo, p.nombre, p.precio_compra,p.codigo_barra');
        $this->connection->from('producto p');
        $this->connection->join('stock_actual s','s.producto_id=p.id')
                        ->where('p.activo',true);
        $this->connection->where(array('almacen_id'=>$id_almacen));
        $query = $this->connection->get();
        return $query->result();
    }

    public function agregar_columna_destacado_tienda(){
        $sql = "SHOW COLUMNS FROM producto LIKE 'destacado_tienda'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {// Validamos si el campo no existe y los creamos
            $sql = "
                ALTER TABLE `producto` 
                ADD COLUMN `destacado_tienda` int NULL DEFAULT 0 AFTER `id_proveedor`;
            ";
            $this->connection->query($sql);
        }
    }

    public function update_producto_destacado($data,$where){
        $this->connection->where($where);
        $this->connection->update('producto',$data);
    }

    public function devolver_ruta_imagen($imagen){
        if(is_null($imagen) or empty($imagen) or $imagen == '' or $imagen == 'NULL'){
            $imagen = 'default.svg';
        }
        $ruta_imagen = '';
        //$ruta_imagen= base_url("uploads/".$imagen);
        // Verificamos si la imagen existe en S3
        $info_img = $this->s3->getObjectInfo('vendty-img',$this->session->userdata('base_dato')."/imagenes_productos/".$imagen,true);
        
        if(!empty($info_img))
            $ruta_imagen= "https://vendty-img.s3-us-west-2.amazonaws.com/".$this->session->userdata('base_dato')."/imagenes_productos/".$imagen;
        else 
            $ruta_imagen= "https://vendty-img.s3-us-west-2.amazonaws.com/".$imagen;  

        return $ruta_imagen;
    }

    public function columna_tipo_producto(){
        $sql = "SHOW COLUMNS FROM producto LIKE 'id_tipo_producto'";
        $existeCampo = $this->connection->query($sql)->result();
        if (count($existeCampo) == 0) {
            $sql="ALTER TABLE `producto`   
                ADD COLUMN `id_tipo_producto` INT(11) NULL  COMMENT 'referencia la tabla tipo de producto, esta columna se agrega para los productos de restaurante'";
            $this->connection->query($sql);    
        }
    }
    public function getModificaciones($id){
        $where = array();
        
                if ($id) {
                    $where = array('id' => $id);
                }
                
                $str_query = "select * from producto_modificacion  where id_producto = $id";    
                
                $query = $this->connection->query($str_query);
                return $query->result_array();
    }

    public function getAdicionByid($producto,$id){
        
                
                $str_query = "select a.*, p.nombre from producto p, producto_adicional a  where a.id_producto = $producto and a.id_adicional = $id and p.id = a.id_adicional";    
                $query = $this->connection->query($str_query);
                return $query->result_array();
    }

    public function getAdicionales($id){
        $where = array();
        
                if ($id) {
                    $where = array('id' => $id);
                }
                
                $str_query = "select a.*, p.nombre from producto_adicional a, producto p where a.id_adicional = p.id and a.id_producto = $id";    
             
                $query = $this->connection->query($str_query);
                return $query->result_array();
    }

    public function creartablas_producto_modificacion_producto_adicional_producto_ingredientes_secciones_almacen(){
        
        $sql="CREATE TABLE IF NOT EXISTS producto_adicional (
            id int(11) NOT NULL AUTO_INCREMENT,
            id_producto int(11) DEFAULT NULL,
            id_adicional int(11) DEFAULT NULL,
            cantidad int(11) DEFAULT NULL,
            precio double DEFAULT NULL,
            PRIMARY KEY (id)
          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";

        $this->connection->query($sql);

        $sql="CREATE TABLE IF NOT EXISTS producto_ingredientes (
            id int(11) NOT NULL AUTO_INCREMENT,
            id_producto int(11) DEFAULT NULL,
            id_ingrediente int(11) DEFAULT NULL,
            cantidad int(11) DEFAULT NULL,
            PRIMARY KEY (id)
          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";

        $this->connection->query($sql);

        $sql="CREATE TABLE IF NOT EXISTS secciones_almacen (
            id int(11) NOT NULL AUTO_INCREMENT COMMENT 'incremental de la tabla y clave primaria',
            fecha_creacion datetime DEFAULT NULL COMMENT 'fecha en que se crea el registro',
            creado_por int(11) DEFAULT NULL COMMENT 'el usuario que creo que el registro',
            fecha_modificacion datetime DEFAULT NULL COMMENT 'fecha de ultima actualizacion del registro',
            modificado_por int(11) DEFAULT NULL COMMENT 'el usuario que realizo la ultima modificacion',
            activo tinyint(4) DEFAULT '1' COMMENT 'activo 1 desactivo 0',
            id_almacen int(11) DEFAULT NULL COMMENT 'referencia la tabla almacenes, el almacen al que pertenece esta seccion',
            codigo_seccion varchar(10) DEFAULT NULL COMMENT 'codigo identificador para informes',
            nombre_seccion varchar(50) DEFAULT NULL COMMENT 'nombre de la seccion o piso',
            descripcion_seccion varchar(500) DEFAULT NULL COMMENT 'descripcion de la seccion de mesas',
            PRIMARY KEY (id),
            KEY fk_almacen_seccion (id_almacen),
            CONSTRAINT fk_almacen_seccion FOREIGN KEY (id_almacen) REFERENCES almacen (id) ON DELETE CASCADE ON UPDATE CASCADE
          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1 COMMENT='para las mesas contiene las secciones o pisos donde hay mesa';";

        $this->connection->query($sql);
        $sql="CREATE TABLE IF NOT EXISTS producto_modificacion (
            id int(11) NOT NULL AUTO_INCREMENT,
            id_producto int(11) DEFAULT NULL,
            nombre varchar(100) DEFAULT NULL,
            PRIMARY KEY (id)
          ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=latin1;";

        $this->connection->query($sql);
    }

    public function get_product_by_code($codigo_producto){

        $sql = "SELECT * FROM producto WHERE codigo='".$codigo_producto."' LIMIT 1";
        $result = $this->connection->query($sql);
        if($result->num_rows() > 0){
            return $result->row();
        }else{
            return NULL;
        }
    }

    public function get_total_inventario_producto($codigo_producto, $id_almacen){
        $sql = "SELECT  p.*, s.unidades AS stock_actual, (p.precio_compra * s.unidades) AS total_inventario FROM producto AS p INNER JOIN stock_actual AS s ON p.id = s.producto_id WHERE p.id='".$codigo_producto."' AND s.almacen_id = '".$id_almacen."' LIMIT 1";
        $result = $this->connection->query($sql);
        if($result->num_rows() > 0){
            return $result->row();  
        }else{
            return NULL;
        }
    }

    public function getProductoCombo($id){
        $sql = "SELECT C.cantidad, C.id_combo, P.precio_compra FROM producto P JOIN producto_combos C ON P.id=C.id_producto ";
        $sql .= " WHERE C.id_producto = $id "; 
        $result = $this->connection->query($sql);
        if($result->num_rows() > 0){
            return $result->result_array();
        }else{
            return NULL;
        }
    }

    public function getProducto($id){
        $sql = " SELECT * FROM producto WHERE id = $id "; 
        $result = $this->connection->query($sql);
        return $result->result_array();
    }

    public function getStockActualo($id, $almacen){
        $sql = " SELECT * FROM stock_actual WHERE producto_id = $id AND almacen_id = $almacen"; 
        $result = $this->connection->query($sql);
        return $result->result_array();
    }

    public function updatePrecioCombo($id, $precio){
        $sql = " UPDATE producto SET precio_compra = $precio WHERE id = $id ";
        $this->connection->query($sql);
    }

    public function getProductoComboAlmacen($id, $almacen){
        $sql = "SELECT C.cantidad, C.id_combo, S.precio_compra FROM stock_actual S JOIN producto_combos C ON S.producto_id=C.id_producto ";
        $sql .= "WHERE C.id_producto = $id AND S.almacen_id = $almacen";
        $result = $this->connection->query($sql);
        if($result->num_rows() > 0){
            return $result->result_array();
        }else{
            return NULL;
        }
    }

    public function get_producto_envio(){
        $this->connection->select("*");
        $this->connection->from("producto");
        $this->connection->where("nombre","envio");
        $this->connection->limit(1);
        $result = $this->connection->get("");
        if($result->num_rows() > 0){
            return $result->row_array();
        }else{
            return NULL;
        }
    }
    
    public function existe_codigo_update($where){
        $this->connection->where($where);        
        $query= $this->connection->get('producto')->result();        
        return $query;        
   }
}
?>
