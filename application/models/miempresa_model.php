<?php

class Miempresa_model extends CI_Model
{
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

    public function update_data_empresa($data){
        //Actualizacion realizada para la configuracion inicial
        extract($data,EXTR_SKIP);
        
        if(isset($plantilla))
            $this->connection->where('nombre_opcion' ,'plantilla_empresa')->update('opciones', array('valor_opcion' => $plantilla));

        if(isset($titulo_venta))
            $this->connection->where('nombre_opcion' ,'titulo_venta')->update('opciones', array('valor_opcion' => $titulo_venta));
            
        
        if(isset($nombre_empresa))
            $this->connection->where('nombre_opcion' ,'nombre_empresa')->update('opciones', array('valor_opcion' => $nombre_empresa));  
        
        if(isset($resolucion_factura))
            $this->connection->where('nombre_opcion' ,'resolucion_factura')->update('opciones', array('valor_opcion' => $resolucion_factura));

        if(isset($contacto))
            $this->connection->where('nombre_opcion' ,'contacto_empresa')->update('opciones', array('valor_opcion' => $contacto));

        if(isset($email))
            $this->connection->where('nombre_opcion' ,'email_empresa')->update('opciones', array('valor_opcion' => $email));    

        if(isset($direccion))
            $this->connection->where('nombre_opcion' ,'direccion_empresa')->update('opciones', array('valor_opcion' => $direccion));

        if(isset($telefono))
            $this->connection->where('nombre_opcion' ,'telefono_empresa')->update('opciones', array('valor_opcion' => $telefono));

        if(isset($fax))
            $this->connection->where('nombre_opcion' ,'fax_empresa')->update('opciones', array('valor_opcion' => $fax));                

        if(isset($web))
            $this->connection->where('nombre_opcion' ,'web_empresa')->update('opciones', array('valor_opcion' => $web));       
        
        if(isset($moneda))
            $this->connection->where('nombre_opcion' ,'moneda_empresa')->update('opciones', array('valor_opcion' => $moneda));

        if(isset($paypal_email))
            $this->connection->where('nombre_opcion' ,'paypal_email')->update('opciones', array('valor_opcion' => $paypal_email));
        
        
        if(isset($sistema))
            $this->connection->where('nombre_opcion' ,'sistema')->update('opciones', array('valor_opcion' => $sistema));

        if(isset($nit))
            $this->connection->where('nombre_opcion' ,'nit')->update('opciones', array('valor_opcion' => $nit));

        if(isset($plantilla_cotizacion))
            $this->connection->where('nombre_opcion' ,'plantilla_cotizacion')->update('opciones', array('valor_opcion' => $plantilla_cotizacion));     
        
        if(isset($tipo_factura))
            $this->connection->where('nombre_opcion' ,'tipo_factura')->update('opciones', array('valor_opcion' => $tipo_factura));

        if(isset($numero))
            $this->connection->where('nombre_opcion' ,'numero')->update('opciones', array('valor_opcion' => $numero));     

        if(isset($sobrecosto))
            $this->connection->where('nombre_opcion' ,'sobrecosto')->update('opciones', array('valor_opcion' => $sobrecosto));

        if(isset($multiples_formas_pago))
            $this->connection->where('nombre_opcion' ,'multiples_formas_pago')->update('opciones', array('valor_opcion' => $multiples_formas_pago));

        if(isset($vendedor_impresion))
            $this->connection->where('nombre_opcion' ,'vendedor_impresion')->update('opciones', array('valor_opcion' => $vendedor_impresion));


        if(isset($valor_caja))
            $this->connection->where('nombre_opcion' ,'valor_caja')->update('opciones', array('valor_opcion' => $valor_caja));

        
        if(isset($filtro_ciudad))
            $this->connection->where('nombre_opcion' ,'filtro_ciudad')->update('opciones', array('valor_opcion' => $filtro_ciudad));
        
        if(isset($comanda))
            $this->connection->where('nombre_opcion' ,'comanda')->update('opciones', array('valor_opcion' => $comanda));
        
        if(isset($etienda))
            $this->connection->where('nombre_opcion' ,'etienda')->update('opciones', array('valor_opcion' => $etienda));

        if(isset($decimales_moneda))
            $this->connection->where('nombre_opcion' ,'decimales_moneda')->update('opciones', array('valor_opcion' => $decimales_moneda));

        if(isset($tipo_separador_decimales))
            $this->connection->where('nombre_opcion' ,'tipo_separador_decimales')->update('opciones', array('valor_opcion' => $tipo_separador_decimales));

        if(isset($tipo_separador_miles))
            $this->connection->where('nombre_opcion' ,'tipo_separador_miles')->update('opciones', array('valor_opcion' => $tipo_separador_miles)); 
        
        if(isset($precio_almacen))
            $this->connection->where('nombre_opcion' ,'precio_almacen')->update('opciones', array('valor_opcion' => $precio_almacen));
        
        if(isset($redondear_precios))
            $this->connection->where('nombre_opcion' ,'redondear_precios')->update('opciones', array('valor_opcion' => $redondear_precios));

        if(isset($pais))
            $this->connection->where('nombre_opcion' ,'pais')->update('opciones', array('valor_opcion' => $pais));
                    
        if(isset($offline))
            $this->connection->where('nombre_opcion' ,'offline')->update('opciones', array('valor_opcion' => $offline));
            

        if($this->validarOpcion('nueva_impresion_rapida', 'si', 'no') && isset($nueva_impresion_rapida)){
            $this->connection->where('nombre_opcion' ,'nueva_impresion_rapida');
            $this->connection->update('opciones', array('valor_opcion' => $nueva_impresion_rapida));

            if($nueva_impresion_rapida == "si"){
                $this->db->where('id_db' ,$this->session->userdata('db_config_id'));
                $this->db->update('crm_db_activas', array('nueva_impresion_rapida' => 1));
            } 
        } 

        if($this->validarOpcion('impresion_rapida', 'si', 'no') && isset($impresion_rapida)){
            $this->connection->where('nombre_opcion' ,'impresion_rapida');
            $this->connection->update('opciones', array('valor_opcion' => $impresion_rapida));

            if($impresion_rapida == "si"){
                $this->db->where('id_db' ,$this->session->userdata('db_config_id'));
                $this->db->update('crm_db_activas', array('impresion_rapida' => 1));
            } 
        } 

        if($this->validarOpcion('quick_service', 'si', 'no') && isset($quick_service)){
            $this->connection->where('nombre_opcion' ,'quick_service');
            $this->connection->update('opciones', array('valor_opcion' => $quick_service));
        } 

        if($this->validarOpcion('quick_service_command', 'si', 'no') && isset($quick_service_command)){
            $this->connection->where('nombre_opcion' ,'quick_service_command');
            $this->connection->update('opciones', array('valor_opcion' => $quick_service_command));
        } 

        if($this->validarOpcion('zona_horaria', true) && isset($zona_horaria))
        {
            $this->connection->where('nombre_opcion' ,'zona_horaria');
            $this->connection->update('opciones', array('valor_opcion' => $zona_horaria));    
        }

        if($this->validarOpcion('simbolo', true) && isset($simbolo))
        {
            $this->connection->where('nombre_opcion' ,'simbolo');
            $this->connection->update('opciones', array('valor_opcion' => $simbolo));    
        }

        if($this->validarOpcion('resolucion_factura_estado', true, 'no') && isset($resolucion_factura_estado))
        {
            $this->connection->where('nombre_opcion' ,'resolucion_factura_estado');
            $this->connection->update('opciones', array('valor_opcion' => $resolucion_factura_estado));
        }

        if($this->validarOpcion('documento', true, 'NIT') && isset($documento))
        {
            $this->connection->where('nombre_opcion' ,'documento');
            $this->connection->update('opciones', array('valor_opcion' => $documento));    
        }

        if($this->validarOpcion('multiples_vendedores', true, '0') && isset($multiples_vendedores))
        {
            $this->connection->where('nombre_opcion' ,'multiples_vendedores');
            $this->connection->update('opciones', array('valor_opcion' => $multiples_vendedores));    
        }
        $logotipo_empresa=isset($logotipo_empresa)?$logotipo_empresa:"";
        if(!empty($logotipo_empresa)||($logotipo_empresa=="bo")){
            $this->connection->where('nombre_opcion' ,'logotipo_empresa');
            $this->connection->update('opciones', array('valor_opcion' => ($logotipo_empresa !='bo')? $logotipo_empresa :'' ));
        }
        
        if($this->validarOpcion('eliminar_producto_comanda', 'si', 'no') && isset($eliminar_producto_comanda))
        {
            $this->connection->where('nombre_opcion' ,'eliminar_producto_comanda');
            $this->connection->update('opciones', array('valor_opcion' => $eliminar_producto_comanda));    
        }
        
        if($this->validarOpcion('permitir_formas_pago_pendiente', 'si', 'no') && isset($permitir_formas_pago_pendiente))
        {
            $this->connection->where('nombre_opcion' ,'permitir_formas_pago_pendiente');
            $this->connection->update('opciones', array('valor_opcion' => $permitir_formas_pago_pendiente));    
        }

        if($this->validarOpcion('domicilios', 'si', 'no') && isset($domicilios))
        {
            $this->connection->where('nombre_opcion' ,'domicilios');
            $this->connection->update('opciones', array('valor_opcion' => $domicilios));    
        }

        if($this->validarOpcion('comanda_virtual', 'si', 'no') && isset($comanda_virtual))
        {
            $this->connection->where('nombre_opcion' ,'comanda_virtual');
            $this->connection->update('opciones', array('valor_opcion' => $comanda_virtual));    
        }

        if($this->validarOpcion('enviar_valor_inventario', 'si', 'no') && isset($enviar_valor_inventario))
        {
            $this->connection->where('nombre_opcion' ,'enviar_valor_inventario');
            $this->connection->update('opciones', array('valor_opcion' => $enviar_valor_inventario));    
        }

        if($this->validarOpcion('stock_historico', 'si', 'no') && isset($stock_historico))
        {
            $this->connection->where('nombre_opcion' ,'stock_historico');
            $this->connection->update('opciones', array('valor_opcion' => $stock_historico));    
        }

        if(isset($correo_valor_inventario))
            $this->connection->where('nombre_opcion' ,'correo_valor_inventario')->update('opciones', array('valor_opcion' => $correo_valor_inventario));
        
        if(isset($correo_stock_historico))
            $this->connection->where('nombre_opcion' ,'correo_stock_historico')->update('opciones', array('valor_opcion' => $correo_stock_historico));

        if($this->validarOpcion('puntos_leal', 'si', 'no') && isset($puntos_leal))
        {
            $this->connection->where('nombre_opcion' ,'puntos_leal');
            $this->connection->update('opciones', array('valor_opcion' => $puntos_leal));    
        }

        if(isset($usuario_puntos_leal))
            $this->connection->where('nombre_opcion' ,'usuario_puntos_leal')->update('opciones', array('valor_opcion' => $usuario_puntos_leal));
        
        if(isset($contraseña_puntos_leal))
            $this->connection->where('nombre_opcion' ,'contraseña_puntos_leal')->update('opciones', array('valor_opcion' => $contraseña_puntos_leal));

        

        if(isset($tipo_negocio)){
            switch($tipo_negocio){
                case "restaurante":
                    if($this->validarOpcion('tipo_negocio', true, '0')){
                        $this->connection->where('nombre_opcion' ,'tipo_negocio');
                        $this->connection->update('opciones', array('valor_opcion' => "restaurante"));    
                    }else{
                        $this->crearOpcion('tipo_negocio','si');
                    }

                    if($this->validarOpcion('comanda', true, '0')){
                        $this->connection->where('nombre_opcion' ,'comanda');
                        $this->connection->update('opciones', array('valor_opcion' => "si"));    
                    }
                    if($this->validarOpcion('mesas', true, '0')){
                        $this->connection->where('nombre_opcion' ,'mesas');
                        $this->connection->update('opciones', array('valor_opcion' => "si"));    
                    }

                    if($this->validarOpcion('facturar_mesas', true, '0')){
                        $this->connection->where('nombre_opcion' ,'facturar_mesas');
                        $this->connection->update('opciones', array('valor_opcion' => "si"));    
                    }

                    if($this->validarOpcion('sobrecosto', true, '0')){
                        if($propina){
                            $this->connection->where('nombre_opcion' ,'sobrecosto');
                            $this->connection->update('opciones', array('valor_opcion' => "si"));

                            $this->connection->where('nombre_opcion' ,'sobrecosto_todos');
                            $this->connection->update('opciones', array('valor_opcion' => "1"));
                        }else{
                            $this->connection->where('nombre_opcion' ,'sobrecosto');
                            $this->connection->update('opciones', array('valor_opcion' => "no"));

                            $this->connection->where('nombre_opcion' ,'sobrecosto_todos');
                            $this->connection->update('opciones', array('valor_opcion' => "1"));
                        }   
                    }

                    if($this->validarOpcion('cierre_caja_mesas_abiertas',true,'si')){
                        if($cierre_caja_mesas_abiertas){
                            $this->connection->where('nombre_opcion' ,'cierre_caja_mesas_abiertas');
                            $this->connection->update('opciones', array('valor_opcion' => "si"));
                        }else{
                            $this->connection->where('nombre_opcion' ,'cierre_caja_mesas_abiertas');
                            $this->connection->update('opciones', array('valor_opcion' => "no"));
                        }
                    }

                    if($this->validarOpcion('plan_separe', true, '0')){
                        $this->connection->where('nombre_opcion' ,'plan_separe');
                        $this->connection->update('opciones', array('valor_opcion' => "no"));
                    } 
                break; 

                case "moda":

                    if($this->validarOpcion('tipo_negocio', true, '0')){
                        $this->connection->where('nombre_opcion' ,'tipo_negocio');
                        $this->connection->update('opciones', array('valor_opcion' => "moda"));    
                    }

                    if($this->validarOpcion('atributo', true, '0')){
                        $this->connection->where('nombre_opcion' ,'atributo');
                        $this->connection->update('opciones', array('valor_opcion' => "si"));    
                    }

                    if($this->validarOpcion('facturar_mesas', true, '0')){
                        $this->connection->where('nombre_opcion' ,'facturar_mesas');
                        $this->connection->update('opciones', array('valor_opcion' => "no"));    
                    }

                    if($this->validarOpcion('plan_separe', true, '0')){
                        $this->connection->where('nombre_opcion' ,'plan_separe');
                        $this->connection->update('opciones', array('valor_opcion' => "si"));
                    } 

                    if($this->validarOpcion('sobrecosto', true, '0')){
                        $this->connection->where('nombre_opcion' ,'sobrecosto');
                        $this->connection->update('opciones', array('valor_opcion' => "no"));
                    } 

                    if($this->validarOpcion('comanda', true, '0')){
                        $this->connection->where('nombre_opcion' ,'comanda');
                        $this->connection->update('opciones', array('valor_opcion' => "no"));    
                    }
                break;

                case "retail":
                    if($this->validarOpcion('tipo_negocio', true, '0')){
                        $this->connection->where('nombre_opcion' ,'tipo_negocio');
                        $this->connection->update('opciones', array('valor_opcion' => "retail"));    
                    }

                    if($this->validarOpcion('facturar_mesas', true, '0')){
                        $this->connection->where('nombre_opcion' ,'facturar_mesas');
                        $this->connection->update('opciones', array('valor_opcion' => "no"));    
                    }

                    if($this->validarOpcion('sobrecosto', true, '0')){
                        $this->connection->where('nombre_opcion' ,'sobrecosto');
                        $this->connection->update('opciones', array('valor_opcion' => "no"));
                    } 
                    
                    if($this->validarOpcion('comanda', true, '0')){
                        $this->connection->where('nombre_opcion' ,'comanda');
                        $this->connection->update('opciones', array('valor_opcion' => "no"));    
                    }

                    if($this->validarOpcion('plan_separe', true, '0')){
                        if($plan_separe){
                            $this->connection->where('nombre_opcion' ,'plan_separe');
                            $this->connection->update('opciones', array('valor_opcion' => "si"));
                        }else{
                            $this->connection->where('nombre_opcion' ,'plan_separe');
                            $this->connection->update('opciones', array('valor_opcion' => "no"));
                        }
                    } 
                break;

                default:
                    if($this->validarOpcion('tipo_negocio', true, '0')){
                        $this->connection->where('nombre_opcion' ,'tipo_negocio');
                        $this->connection->update('opciones', array('valor_opcion' => "retail"));    
                    }

                    if($this->validarOpcion('facturar_mesas', true, '0')){
                        $this->connection->where('nombre_opcion' ,'facturar_mesas');
                        $this->connection->update('opciones', array('valor_opcion' => "no"));    
                    }

                    if($this->validarOpcion('sobrecosto', true, '0')){
                        $this->connection->where('nombre_opcion' ,'sobrecosto');
                        $this->connection->update('opciones', array('valor_opcion' => "no"));
                    } 
                    
                    if($this->validarOpcion('comanda', true, '0')){
                        $this->connection->where('nombre_opcion' ,'comanda');
                        $this->connection->update('opciones', array('valor_opcion' => "no"));    
                    }

                    if($this->validarOpcion('plan_separe', true, '0')){
                        if($plan_separe){
                            $this->connection->where('nombre_opcion' ,'plan_separe');
                            $this->connection->update('opciones', array('valor_opcion' => "si"));
                        }else{
                            $this->connection->where('nombre_opcion' ,'plan_separe');
                            $this->connection->update('opciones', array('valor_opcion' => "no"));
                        }
                    } 
                break;
            }
        }
        
        //Imprimir factura automaticamnte
            if(isset($auto_factura))
            $this->connection->where('nombre_opcion' ,'auto_factura')->update('opciones', array('valor_opcion' => $auto_factura));
        
        //Pago efectivo automaticamente
        if(isset($auto_pago))
            $this->connection->where('nombre_opcion' ,'auto_pago')->update('opciones', array('valor_opcion' => $auto_pago));

        //Pago efectivo automaticamente
        if(isset($clientes_cartera))
            $this->connection->where('nombre_opcion' ,'clientes_cartera')->update('opciones', array('valor_opcion' => $clientes_cartera));
        
        // Sobrecosto para todos los productos
        if(isset($sobrecosto_todos))
            $this->connection->where('nombre_opcion' ,'sobrecosto_todos')->update('opciones', array('valor_opcion' => $sobrecosto_todos));
        

        // Cierre de caja automatico
        if(isset($cierre_automatico))
            $this->connection->where('nombre_opcion' ,'cierre_automatico')->update('opciones', array('valor_opcion' => $cierre_automatico));
        
        // Precios en orden de compra
            if(isset($orden_compra_precio))
            $this->connection->where('nombre_opcion' ,'orden_compra_precio')->update('opciones', array('valor_opcion' => $orden_compra_precio));

        
        // Plantilla orden de compra
        if(isset($plantilla_orden_compra))
            $this->connection->where('nombre_opcion' ,'plantilla_orden_compra')->update('opciones', array('valor_opcion' => $plantilla_orden_compra));
        
        
        // Plantilla general
        if(isset($plantilla_general))
            $this->connection->where('nombre_opcion' ,'plantilla_general')->update('opciones', array('valor_opcion' => $plantilla_general));
        
        // Enviar factura
        if(isset($enviar_factura))
            $this->connection->where('nombre_opcion' ,'enviar_factura')->update('opciones', array('valor_opcion' => $enviar_factura));
        
        
        // Costo Promedio
        if(isset($costo_promedio))
            $this->connection->where('nombre_opcion' ,'costo_promedio')->update('opciones', array('valor_opcion' => $costo_promedio));

        //facturar con mesas
        if(isset($facturar_mesas))
            $this->connection->where('nombre_opcion' ,'facturar_mesas')->update('opciones', array('valor_opcion' => $facturar_mesas));
        
        //datos de mexico
        if(isset($num_exterior))
            $this->connection->where('nombre_opcion' ,'num_exterior')->update('opciones', array('valor_opcion' => $num_exterior));

        if(isset($num_interior))
            $this->connection->where('nombre_opcion' ,'num_interior')->update('opciones', array('valor_opcion' => $num_interior));
        
        if(isset($colonia))
            $this->connection->where('nombre_opcion' ,'colonia')->update('opciones', array('valor_opcion' => $colonia));
        
        if(isset($localidad))
            $this->connection->where('nombre_opcion' ,'localidad')->update('opciones', array('valor_opcion' => $localidad));

        if(isset($estado))
            $this->connection->where('nombre_opcion' ,'estado')->update('opciones', array('valor_opcion' => $estado));

        if(isset($municipio))
            $this->connection->where('nombre_opcion' ,'municipio')->update('opciones', array('valor_opcion' => $municipio));

        if(isset($codigo_postal))
            $this->connection->where('nombre_opcion' ,'codigo_postal')->update('opciones', array('valor_opcion' => $codigo_postal));            
        
        //
        if(isset($cabecera_factura))
            $this->connection->where('nombre_opcion' ,'cabecera_factura')->update('opciones', array('valor_opcion' => $cabecera_factura));
        
        if(isset($terminos_condiciones))
        $this->connection->where('nombre_opcion' ,'terminos_condiciones')->update('opciones', array('valor_opcion' => $terminos_condiciones));
        
        if(isset($numero_factura))
        $this->connection->where('nombre_opcion' ,'numero_factura')->update('opciones', array('valor_opcion' => $numero_factura));
        
        if(isset($prefijo_factura))
        $this->connection->where('nombre_opcion' ,'prefijo_factura')->update('opciones', array('valor_opcion' => $prefijo_factura));


    }
    
    // Si no existe la opcion la creamos 
    public function crearOpcion( $opcion, $valor){
        $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => $opcion));
        if( $query->num_rows == 0 ){
            $row = Array(
                "nombre_opcion" => $opcion,
                "valor_opcion" => $valor
            );
            $this->connection->insert("opciones", $row);                
        }   
    }


        private function validarOpcion($opcion, $strict = false, $value = '')
        {
            $query = $this->connection->get_where('opciones', array('nombre_opcion' => $opcion));

            if ($strict)
            {
                if ($query->num_rows <= 0)
                {
                    $data = array(
                       'nombre_opcion' => $opcion,
                       'valor_opcion' => $value
                    );

                    $this->connection->insert('opciones', $data); 
                }
                return true;
            } else {
                return $query->num_rows > 0 ? true : false;
            }
        }

        public function obtenerOpcion($opcion)
        {
            $query = $this->connection->get_where('opciones', array('nombre_opcion' => $opcion));
            return $query->num_rows > 0 ? $query->row()->valor_opcion : '';
        }

        
        // ----------------------------------------------------------------------------------------------------
        //  Clientes Cartera
        // ----------------------------------------------------------------------------------------------------
        public function getCartera(){   
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => "clientes_cartera"));
            //var_dump´($query);die;
            if( $query->num_rows == 0 ){
                $this->crearOpcion( "clientes_cartera", "0");
                return "0";
            }else{
                return $query->row()->valor_opcion;
            }
        }
        // ----------------------------------------------------------------------------------------------------
        
        public function update_data_header_terms($data){

            $this->connection->where('nombre_opcion' ,'cabecera_factura');

            $this->connection->update('opciones', array('valor_opcion' => $data['cabecera']));

            $this->connection->where('nombre_opcion' ,'terminos_condiciones');

            $this->connection->update('opciones', array('valor_opcion' => $data['terminos']));

        }

        

        public function get_data_empresa($almacen=0) {
            $data = array();

            $this->connection->select('valor_opcion');
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'nombre_empresa'));
            $data['data']['nombre'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $this->connection->select('valor_opcion');
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'resolucion_factura'));
            $data['data']['resolucion'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'contacto_empresa'));
            $data['data']['contacto'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'email_empresa'));
            $data['data']['email'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'direccion_empresa'));
            $data['data']['direccion'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'telefono_empresa'));
            $data['data']['telefono'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'fax_empresa'));
            $data['data']['fax'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'web_empresa'));
            $data['data']['web'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'moneda_empresa'));
            $data['data']['moneda'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'resolucion_factura_estado'));
            $data['data']['resolucion_factura_estado'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'plantilla_empresa'));
            $data['data']['plantilla'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'paypal_email'));
            $data['data']['paypal_email'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'cabecera_factura'));
            $data['data']['cabecera_factura'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'terminos_condiciones'));
            $data['data']['terminos_condiciones'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'titulo_venta'));
            $data['data']['titulo_venta'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'sistema'));
            $data['data']['sistema'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'nit'));
            $data['data']['nit'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'plantilla_cotizacion'));
            $data['data']['plantilla_cotizacion'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";
            			
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'numero'));
            $data['data']['numero'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";          
			
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'sobrecosto'));
            $data['data']['sobrecosto'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";  

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'plan_separe'));
            $data['data']['plan_separe'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";  
			
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'multiples_formas_pago'));
            $data['data']['multiples_formas_pago'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";  
			
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'vendedor_impresion'));
            $data['data']['vendedor_impresion'] = $query->num_rows > 0 ? $query->row()->valor_opcion : ""; 
			
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'valor_caja'));
            $data['data']['valor_caja'] = $query->num_rows > 0 ? $query->row()->valor_opcion : ""; 		
			
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'filtro_ciudad'));
            $data['data']['filtro_ciudad'] = $query->num_rows > 0 ? $query->row()->valor_opcion : ""; 						

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'tipo_factura'));
            $data['data']['tipo_factura'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";
            
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'comanda'));
            $data['data']['comanda'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";
            
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'comanda_push'));        
            $data['data']['comanda_push'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "0";

            //tienda
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'etienda'));
            $data['data']['etienda'] = $query->num_rows > 0 ? $query->row()->valor_opcion : ""; 

            //zona_horaria
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'documento'));
            $data['data']['documento'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            //zona_horaria
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'zona_horaria'));
            $data['data']['zona_horaria'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";
            
            //simbolo
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'simbolo'));
            $data['data']['simbolo'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            //simbolo
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'multiples_vendedores'));
            $data['data']['multiples_vendedores'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "0";
            
            $data['data']['logotipo'] = $this->get_logotipo_empresa();
            
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'auto_factura'));
            $data['data']['auto_factura'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "estandar";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'auto_pago'));
            $data['data']['auto_pago'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "estandar";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'clientes_cartera'));
            $data['data']['clientes_cartera'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "0";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'sobrecosto_todos'));
            $data['data']['sobrecosto_todos'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "0";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'cierre_automatico'));
            $data['data']['cierre_automatico'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "1";
            
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'enviar_factura'));
            $data['data']['enviar_factura'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "no";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'facturar_mesas'));
            $data['data']['facturar_mesas'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "no";            
            
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'tipo_negocio'));
            $data['data']['tipo_negocio'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'eliminar_producto_comanda'));
            $data['data']['eliminar_producto_comanda'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";
            
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'permitir_formas_pago_pendiente'));
            $data['data']['permitir_formas_pago_pendiente'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'impresion_rapida'));
            $data['data']['impresion_rapida'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'domicilios'));
            $data['data']['domicilios'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'comanda_virtual'));
            $data['data']['comanda_virtual'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'quick_service'));
            $data['data']['quick_service'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'offline'));
            $data['data']['offline'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'precio_almacen'));
            $data['data']['precio_almacen'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'redondear_precios'));
            $data['data']['redondear_precios'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'plantilla_orden_compra'));
            $data['data']['plantilla_orden_compra'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'plantilla_general'));
            $data['data']['plantilla_general'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'orden_compra_precio'));
            $data['data']['orden_compra_precio'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";
            
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'costo_promedio'));
            $data['data']['costo_promedio'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'resolucion_factura'));
            $data['data']['resolucion_factura'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";
            
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'prefijo_factura'));
            $data['data']['prefijo_factura'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";
            
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'numero_factura'));
            $data['data']['numero_factura'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";
            
            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'puntos_leal'));
            $data['data']['puntos_leal'] = $query->num_rows > 0 ? $query->row()->valor_opcion : "";

            if(!empty($almacen)){
                if($data['data']['resolucion_factura_estado']=='si'){
                    $nit=$data['data']['nit'];
                    //buscar el nit del almacen
                    $this->connection->select('nit');
                    $query2 = $this->connection->get_where('almacen' ,array('id' => $almacen));                    
                    $data['data']['nit'] = $query2->num_rows > 0 ? $query2->row()->nit : $nit; 
                }
            }   

            return $data;
        }

        

        public function get_prefijo_presupuesto(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'prefijo_presupuesto'));

            return $query->row()->valor_opcion;

        }

        public function get_numero_presupuesto(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'numero_presupuesto'));

            return $query->row()->valor_opcion;

        }

        public function get_numero_factura(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'numero_factura'));

            return $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        }

        public function get_prefijo_factura(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'prefijo_factura'));

            return $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        }

        

      /*  public function last_prefijo_presupuesto(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'last_prefijo_presupuesto'));

            return $query->row()->valor_opcion;

        }

        

        public function last_prefijo_factura(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'last_prefijo_factura'));

            return $query->row()->valor_opcion;

        }*/

        

        public function last_numero_factura(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'last_numero_factura'));

            return $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        }

        public function last_numero_presupuesto(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'last_numero_presupuesto'));

            return $query->row()->valor_opcion;

        }

        public function update_data_numeros($data){

            
            //Actualizacion realizada para la configuracion inicial
            extract($data,EXTR_SKIP);
            
            

           // $last_numero_presupuesto = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'last_numero_presupuesto'));

           // $last_numero_factura = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'last_numero_factura'));

            $numero_factura = $this->get_numero_factura();

            $numero_presupuesto = $this->get_numero_presupuesto();

            $prefijo_factura = $this->get_prefijo_factura();

            $prefijo_presupuesto = $this->get_prefijo_presupuesto();

            if(empty($numero_factura) && isset($numero_factura)){

                  $this->connection->where('nombre_opcion' ,'last_numero_factura');

                $this->connection->update('opciones', array('valor_opcion' => $numero_factura));

              

            }

            else {

                 $this->connection->where('nombre_opcion' ,'last_numero_factura');

                $this->connection->update('opciones', array('valor_opcion' => $numero_factura));

               

            }

            

            if(empty($numero_presupuesto)){

                 $this->connection->where('nombre_opcion' ,'last_numero_presupuesto');

                $this->connection->update('opciones', array('valor_opcion' => $numero_presupuesto));

               

            }

            else {

                 $this->connection->where('nombre_opcion' ,'last_numero_presupuesto');

                $this->connection->update('opciones', array('valor_opcion' => $numero_presupuesto));

               

            }

            

           /* if(empty($prefijo_presupuesto)){

                $this->connection->where('nombre_opcion' ,'last_prefijo_presupuesto');

                $this->connection->update('opciones', array('valor_opcion' => $data['numero_presupuesto']));

            }

            else{

                $this->connection->where('nombre_opcion' ,'last_prefijo_presupuesto');

                $this->connection->update('opciones', array('valor_opcion' => $prefijo_presupuesto));

            }

            

            if(empty($prefijo_factura)){

                $this->connection->where('nombre_opcion' ,'last_prefijo_factura');

                $this->connection->update('opciones', array('valor_opcion' => $data['numero_factura']));

            }

            else{

                $this->connection->where('nombre_opcion' ,'last_prefijo_presupuesto');

                $this->connection->update('opciones', array('valor_opcion' => $prefijo_factura));

            }*/

            

            
            if(isset($prefijo_presupuesto))
                $this->connection->where('nombre_opcion' ,'prefijo_presupuesto')->update('opciones', array('valor_opcion' => $prefijo_presupuesto));

            if(isset($numero_presupuesto))
                $this->connection->where('nombre_opcion' ,'numero_presupuesto')->update('opciones', array('valor_opcion' => $numero_presupuesto));

            if(isset($numero_factura))
                $this->connection->where('nombre_opcion' ,'numero_factura')->update('opciones', array('valor_opcion' => $numero_factura));

            if(isset($prefijo_factura))
                $this->connection->where('nombre_opcion' ,'prefijo_factura')->update('opciones', array('valor_opcion' => $prefijo_factura));
   
             if(isset($fecha_factura))
                $this->connection->where('nombre_opcion' ,'fecha_factura')->update('opciones', array('valor_opcion' => $fecha_factura));
            
               
             if(isset($fecha_factura))
                $this->connection->where('nombre_opcion' ,'numero_factura_fin')->update('opciones', array('valor_opcion' => $numero_factura_fin));

            if(isset($numero_alerta_factura))
                $this->connection->where('nombre_opcion' ,'numero_alerta_factura')->update('opciones', array('valor_opcion' => $numero_alerta_factura));
            
            if(isset($dias_alerta_factura))
                $this->connection->where('nombre_opcion' ,'dias_alerta_factura')->update('opciones', array('valor_opcion' => $dias_alerta_factura));

             if(isset($prefijo_devolucion))
                $this->connection->where('nombre_opcion' ,'prefijo_devolucion')->update('opciones', array('valor_opcion' => $prefijo_devolucion));
            
             if(isset($numero_devolucion))
                $this->connection->where('nombre_opcion' ,'numero_devolucion')->update('opciones', array('valor_opcion' => $numero_devolucion));
            

        }

        

        public function update_last_numero_factura($numero_factura){

             $this->connection->where('nombre_opcion' ,'last_numero_factura');

            $this->connection->update('opciones', array('valor_opcion' => $numero_factura));

        }

        

        public function update_last_numero_presupuesto($numero){

            $this->connection->where('nombre_opcion' ,'last_numero_presupuesto');

            $this->connection->update('opciones', array('valor_opcion' => $numero));

        }

        

        

        public function get_terminos_condiciones(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'terminos_condiciones'));

            return $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        }

        

        public function get_cabecera_factura(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'cabecera_factura'));

            return $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        }

        

        public function get_logotipo_empresa(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'logotipo_empresa'));

            return $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        }

        

        public function get_email_empresa(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'email_empresa'));

            return $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        }

        

        public function get_nombre_empresa(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'nombre_empresa'));

            return $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        }

        

        public function get_moneda_empresa(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'moneda_empresa'));

            return $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        }

        

        public function get_sistema_empresa(){

            $query = $this->connection->get_where('opciones' ,array('nombre_opcion' => 'sistema'));

            return $query->num_rows > 0 ? $query->row()->valor_opcion : "";

        }

        public function get_permisos_description(){
            $this->connection->select('nombre_opcion,valor_opcion');  
            $this->connection->from('opciones');
            $query = $this->connection->get(); 
            return $query->result_array();

        }

        

        /*public function get_idiomas(){

            $query = $this->db->get_where('opciones' ,array('nombre_opcion' => 'idioma'));

            return $query->result_array();

        }*/

        

         public function get_nomen($nomen){

            $this->db->select('valor_opcion, mostrar_opcion');    

            $query = $this->db->get_where('opciones' ,array('nombre_opcion' => $nomen));

            $result = array();

            foreach ($query->result() as $value) {

                $result[$value->valor_opcion] = $value->mostrar_opcion;

            }

            return $result;

        }

        

        public function get_plantillas(){

            $this->db->select("mostrar_opcion, valor_opcion");

            $query = $this->db->get_where('opciones' ,array('nombre_opcion' => "plantilla"));

            $result = array();

             foreach ($query->result_array() as $value) {

                $result[$value["valor_opcion"]] = $value['mostrar_opcion'];

            }

            return $result;

        }

        public function get_info_factura_cliente($data){
            $this->db->select('*');
            $result = $this->db->get_where('crm_info_factura_clientes',$data)->result_array();
            return $result;
        }

        public function get_store(){
            $this->connection->select('almacen_id');
            $this->connection->from('usuario_almacen');
            $this->connection->where('usuario_id',$this->session->userdata('user_id'));
            $result = $this->connection->get();
            if($result->num_rows() > 0){
                return $result->result()[0]->almacen_id;
            }else{
                return NULL;
            }
        }
}

?>