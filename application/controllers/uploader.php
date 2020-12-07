<?php

class Uploader extends CI_Controller 
{
    private $dbu;

    public function __construct()
    {
    	parent::__construct();
    }

    public function productos_con_atributos($database)
    {
        //$dns = 'mysql://root:@localhost/'.$database;
        $dns = "mysql://vendtyMaster:ro_ar_8027*_na@169.53.12.166/$database";
        $this->dbu = $this->load->database($dns, true);


    	$path = 'files/plantilla.xlsx';
    	$this->load->library('phpexcel');

    	$obj_excel = PHPExcel_IOFactory::load($path);

        $excel_data = $obj_excel->getActiveSheet()->toArray(null, true, true, true);

        $errores_productos = '';
        $errores_productos_atributos = '';
        $errores_stock = '';

        $productos = [];
        $categorias = [];
        $clasificaciones = [
        	'Marca' => [],
        	'Proveedor' => [],
        	'Color' => [],
        	'Talla' => [],
        	'Lineas' => [],
        	'Materiales' => [],
        	'Tipos' => []
        ];

        $clasificaciones_en_archivo = [
            'Talla' => 'D',
            'Color' => 'E',
            'Proveedor' => 'F',
            'Marca' => 'G',
            'Materiales' => 'H',
            'Tipos' => 'I',
            'Lineas' => 'J'
        ];

        foreach ($excel_data as $index => $row) 
        {
        	if($index == 1)
        		continue;
        	
            if(!is_null($row['A']))
            {
                array_push($productos, $row);

            	if(!in_array($row['A'], $categorias))
            		array_push($categorias, $row['A']);

            	if(!in_array($row['D'], $clasificaciones['Talla']) && trim($row['D']) !== '')
            		array_push($clasificaciones['Talla'], $row['D']);

            	if(!in_array($row['E'], $clasificaciones['Color']) && trim($row['E']) !== '')
            		array_push($clasificaciones['Color'], $row['E']);

            	if(!in_array($row['F'], $clasificaciones['Proveedor']) && trim($row['F']) !== '')
            		array_push($clasificaciones['Proveedor'], $row['F']);

            	if(!in_array($row['G'], $clasificaciones['Marca']) && trim($row['G']) !== '')
            		array_push($clasificaciones['Marca'], $row['G']);
            	
            	if(!in_array($row['H'], $clasificaciones['Materiales']) && trim($row['H']) !== '')
            		array_push($clasificaciones['Materiales'], $row['H']);
            	
            	if(!in_array($row['I'], $clasificaciones['Tipos']) && trim($row['I']) !== '')
            		array_push($clasificaciones['Tipos'], $row['I']);
            	
            	if(!in_array($row['J'], $clasificaciones['Lineas']) && trim($row['J']) !== '')
            		array_push($clasificaciones['Lineas'], $row['J']);
            }
        }

        if(count($productos) > 0)
        {
            // validar categorias
            if(count($categorias) > 0)
            {
                $s_categorias = $this->dbu->select('nombre')
                                ->from('categoria')
                				->where_in('nombre', $categorias)
                                ->get();

                $i_categorias = [];
                if(!is_bool($s_categorias))
                {
                    $categorias_existentes = array_column($s_categorias->result_array(), 'nombre');
                    $categorias_nuevas = array_diff($categorias, $categorias_existentes);

                    foreach ($categorias_nuevas as $categoria) 
                    {
                        array_push($i_categorias, [
                            'codigo' => '',
                            'nombre' => trim($categoria),
                            'imagen' => '',
                            'padre' => '',
                            'activo' => '1'                        
                        ]);
                    }
                }

                if(count($i_categorias) > 0)
                {
                    $this->dbu->insert_batch('categoria', $i_categorias);
                    echo count($i_categorias).' Categorias creadas';
                }
            }

            // validar productos

            $s_productos = $this->dbu->from('producto')
                                ->get();
            $codigo_barra = $s_productos->num_rows();
            /*
            $productos_a_insertar = array_column($productos, 'B');
           

            $productos_existentes = array_column($s_productos->result_array(), 'codigo');
            $productos_nuevos = array_diff($productos_a_insertar, $productos_existentes);
            */

            // validar clasificaciones
            foreach ($clasificaciones as $key => $clasificacion) 
            {
                $id_clasificacion = $this->dbu->select('id')
                                            ->from('atributos')
                                            ->where('nombre', $key)
                                            ->get()
                                            ->first_row();

                $s_clasificaciones = $this->dbu->select('valor')
                                            ->from('atributos_detalle')
                                            ->where('atributo_id', $id_clasificacion->id)
                                            ->where_in('valor', $clasificacion)
                                            ->get();

                $i_clasificaciones = [];
                if(!is_bool($s_clasificaciones))
                {
                    $clasificaciones_existentes = array_column($s_clasificaciones->result_array(), 'valor');
                    $clasficaciones_nuevas = array_diff($clasificacion, $clasificaciones_existentes);

                    $i_clasificaciones = [];
                    foreach ($clasficaciones_nuevas as $clasifiacion_nueva) 
                    {
                        array_push($i_clasificaciones, [
                            'valor' => trim($clasifiacion_nueva),
                            'descripcion' => '',
                            'atributo_id' => $id_clasificacion->id
                        ]);
                    }

                    if(count($i_clasificaciones) > 0)
                    {
                        $this->dbu->insert_batch('atributos_detalle', $i_clasificaciones);
                        echo count($i_clasificaciones).' Clasificaciones creadas para '.$key.'<br>';
                    }
                }
            }

            // preparar categorias y clasificaciones
            $s_categorias = $this->dbu->select('id, TRIM(nombre) as nombre')
                                ->from('categoria')
                                ->get();

            $s_unidad = $this->dbu->select('id, TRIM(nombre) as nombre')
                                ->from('unidades')
                                ->get();

            $s_impuestos = $this->dbu->select('id_impuesto, TRIM(nombre_impuesto) as nombre_impuesto')
                                ->from('impuesto')
                                ->get();

            $s_clasificaciones = $this->dbu->select('id, TRIM(valor) as valor, atributo_id')
                                ->from('atributos_detalle')
                                ->get();

            $s_almacenes = $this->dbu->select('id, TRIM(nombre) as nombre')
                                ->from('almacen')
                                ->get();

            // insertar atributos productos y productos
            $i_productos_atributos = [];
            $i_productos = [];

            $index_row = 0;

            foreach ($productos as $producto) 
            {
                $codigo_barra++;
                $index_row++;
                $cat = $this->__search_multidimensional_helper(['nombre' => trim($producto['A'])], $s_categorias->result_array());
                $imp = $this->__search_multidimensional_helper(['nombre_impuesto' => trim($producto['N'])], $s_impuestos->result_array());
                $uni = $this->__search_multidimensional_helper(['nombre' => trim($producto['O'])], $s_unidad->result_array());
                $alm = $this->__search_multidimensional_helper(['nombre' => trim($producto['R'])], $s_almacenes->result_array());

                $index = 0;
                if($alm)
                {
                    foreach ($clasificaciones as $key_cla => $clasificacion) 
                    {
                        $index ++;
                        if($cat)
                        {
                            $row_clasificacion = $clasificaciones_en_archivo[$key_cla];
                            $cla = $this->__search_multidimensional_helper(['atributo_id' => $index, 'valor' => trim($producto[$row_clasificacion])], $s_clasificaciones->result_array());
                            if($cla)
                            {
                                array_push($i_productos_atributos, [
                                    'nombre_producto' => $producto['C'], 
                                    'codigo_interno' => $codigo_barra, 
                                    'codigo_barras' => $producto['B'], 
                                    'id_categoria' => $cat['id'], 
                                    'nombre_categoria' => $cat['nombre'], 
                                    'id_atributo' => $cla['atributo_id'], 
                                    'nombre_atributo' => $key_cla, 
                                    'id_clasificacion' => $cla['id'], 
                                    'nombre_clasificacion' => $cla['valor']
                                ]);
                            } else {
                                if (!empty($producto[$row_clasificacion]))
                                    $errores_productos_atributos .= 'No se encuentra la clasificacion especificada en '.$row_clasificacion.'['.$index_row.']('.$producto[$row_clasificacion].')<br>';
                            }
                        } else {
                            $errores_productos_atributos .= 'No se encuentra la categoria especificada en A['.$index_row.']<br>';;
                        } 
                    }
                    
                    if($cat)
                    {
                        array_push($i_productos, [
                            'categoria_id' => $cat['id'],
                            'codigo' => trim($producto['B']),
                            'nombre' => trim($producto['C'].'/'.$producto['G'].'/'.$producto['F'].'/'.$producto['E'].'/'.$producto['D'].'/'.$producto['J'].'/'.$producto['H'].'/'.$producto['I']),
                            'codigo_barra' => $codigo_barra,
                            'precio_compra' => $producto['K'],
                            'precio_venta' => $producto['L'],
                            'stock_minimo' => $producto['Q'],
                            'descripcion' => $producto['M'],
                            'activo' => '1',
                            'fecha_vencimiento' => '',
                            'ubicacion' => '',
                            'ganancia' => '0',
                            'imagen1' => '',
                            'imagen2' => '',
                            'imagen3' => '',
                            'imagen4' => '',
                            'imagen5' => '',
                            'impuesto' => is_null($imp) ? '1' : $imp['id_impuesto'],
                            'unidad_id' => is_null($uni) ? '1' : $uni['id'],
                            'stock_maximo' => '0'
                        ]);
                    } else {
                        $errores_productos .= 'No se encuentra la categoria especificada en A['.$index_row.']<br>';
                    }
                } else {
                    $errores_productos .= 'No se encuentra el almacen especificado en R['.$index_row.']<br>';
                }
            }

            if(strlen($errores_productos_atributos) == 0 && strlen($errores_productos) == 0)
            {
                $this->dbu->insert_batch('atributos_productos', $i_productos_atributos);
                $this->dbu->insert_batch('producto', $i_productos);
            
                //preparar productos
                $s_productos = $this->dbu->select('id, TRIM(nombre) as nombre, TRIM(codigo) as codigo')
                                    ->from('producto')
                                    ->get();

                //insertar stocks
                $i_stock = [];
                foreach ($productos as $producto) 
                {
                    $nombre = trim($producto['C'].'/'.$producto['G'].'/'.$producto['F'].'/'.$producto['E'].'/'.$producto['D'].'/'.$producto['J'].'/'.$producto['H'].'/'.$producto['I']);
                    $codigo = trim($producto['B']);
                    $alm = $this->__search_multidimensional_helper(['nombre' => trim($producto['R'])], $s_almacenes->result_array());
                    $pro = $this->__search_multidimensional_helper(['codigo' => $codigo, 'nombre' => $nombre], $s_productos->result_array());
                    array_push($i_stock, [
                        'almacen_id' => $alm['id'],
                        'producto_id' => $pro['id'],
                        'unidades' => $producto['P']
                    ]);
                }

                $this->dbu->insert_batch('stock_actual', $i_stock);
            }
        } else {
            echo 'No hay productos para insertar<br>';
        }

        echo 'Carga de productos procesada<br><br>';
        echo $errores_productos.'<br><br>';
        echo $errores_productos_atributos.'<br><br>';

        /* 
            Insertar atributos productos
			=CONCATENAR("INSERT INTO atributos_productos(nombre_producto, codigo_barras, id_categoria, nombre_categoria, id_atributo, nombre_atributo, id_clasificacion, nombre_clasificacion) VALUES (|";ESPACIOS(A2);"|, |";C2;"|, ";D2;", |";E2;"|, ";F2;", |";G2;"|, ";H2;", |";I2;"| );")

            Insertar producto
            ="INSERT INTO producto (categoria_id, codigo, nombre, codigo_barra, precio_compra, precio_venta, stock_minimo, descripcion, activo, impuesto, unidad_id, stock_maximo) VALUES ("&N2&", "&CARACTER(34)&ESPACIOS(B2)&CARACTER(34)&", "&CARACTER(34)&ESPACIOS(C2)&"/"&F2&"/"&G2&"///"&CARACTER(34)&" , "&O2&", "&D2&", "&E2&", "&L2&", "&CARACTER(34)&ESPACIOS(H2)&CARACTER(34)&", 1, 1, 1, 0);"
    	*/
    }

    private function __search_multidimensional_helper(array $busqueda, array $array)
    {

        $respuesta = null;
        $keys = array_keys($busqueda);

        foreach ($array as $row) 
        {
            $iguales = true;
            foreach($keys as $key)
            {
                if(strcmp(''.$busqueda[$key], ''.$row[$key]) != 0)
                    $iguales = false;
            }

            if($iguales)
            {
               $respuesta = &$row;
               break;
            }
        }

        return $respuesta;
    }
}
