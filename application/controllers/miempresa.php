
<?php
class Miempresa extends CI_Controller

{
    var $dbConnection;
    function __construct()
    {
        parent::__construct();
        
        //$this->load->model("PaisModel");
        
        $usuario = $this->session->userdata('usuario');
        $clave = $this->session->userdata('clave');
        $servidor = $this->session->userdata('servidor');
        $base_dato = $this->session->userdata('base_dato');
        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
        
        $this->load->model('pais_model','pais');
        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("miempresa_model", 'miempresa');
        $this->miempresa->initialize($this->dbConnection);
        $this->load->model("almacenes_model", 'almacen');
        $this->almacen->initialize($this->dbConnection);
        $this->load->model("opciones_model", 'opciones');
        $this->opciones->initialize($this->dbConnection);
        $this->load->model("caja_model", 'caja');
        $this->caja->initialize($this->dbConnection);
        $this->load->model("productos_model", 'productos');
        $this->productos->initialize($this->dbConnection);
        $this->load->model("stock_actual_model", 'stock_actual');
        $this->stock_actual->initialize($this->dbConnection);
        $this->load->model("cuentas_siigo_model", 'cuentasSiigo');
        $this->cuentasSiigo->initialize($this->dbConnection);
        
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
        $idioma = $this->session->userdata('idioma');
        $this->lang->load('sima', $idioma);
    }

    public function index($offset = 0)
    {
        if (!$this->ion_auth->logged_in())
        {
            redirect('auth', 'refresh');
        }
        
        $data = array();
        $error_upload = "";
        $image_name = "";
        $timezones = array(
            'Pacific/Midway'       => "(GMT-11:00) Midway Island",
            'US/Samoa'             => "(GMT-11:00) Samoa",
            'US/Hawaii'            => "(GMT-10:00) Hawaii",
            'US/Alaska'            => "(GMT-09:00) Alaska",
            'US/Pacific'           => "(GMT-08:00) Pacific Time (US &amp; Canada)",
            'America/Tijuana'      => "(GMT-08:00) Tijuana",
            'US/Arizona'           => "(GMT-07:00) Arizona",
            'US/Mountain'          => "(GMT-07:00) Mountain Time (US &amp; Canada)",
            'America/Chihuahua'    => "(GMT-07:00) Chihuahua",
            'America/Mazatlan'     => "(GMT-07:00) Mazatlan",
            'America/Mexico_City'  => "(GMT-06:00) Mexico City",
            'America/Monterrey'    => "(GMT-06:00) Monterrey",
            'Canada/Saskatchewan'  => "(GMT-06:00) Saskatchewan",
            'US/Central'           => "(GMT-06:00) Central Time (US &amp; Canada)",
            'US/Eastern'           => "(GMT-05:00) Eastern Time (US &amp; Canada)",
            'US/East-Indiana'      => "(GMT-05:00) Indiana (East)",
            'America/Bogota'       => "(GMT-05:00) Bogota",
            'America/Lima'         => "(GMT-05:00) Lima",
            'America/Caracas'      => "(GMT-04:30) Caracas",
            'Canada/Atlantic'      => "(GMT-04:00) Atlantic Time (Canada)",
            'America/La_Paz'       => "(GMT-04:00) La Paz",
            'America/Santiago'     => "(GMT-04:00) Santiago",
            'Canada/Newfoundland'  => "(GMT-03:30) Newfoundland",
            'America/Buenos_Aires' => "(GMT-03:00) Buenos Aires",
            //'Greenland'            => "(GMT-03:00) Greenland",
            'Atlantic/Stanley'     => "(GMT-02:00) Stanley",
            'Atlantic/Azores'      => "(GMT-01:00) Azores",
            'Atlantic/Cape_Verde'  => "(GMT-01:00) Cape Verde Is.",
            'Africa/Casablanca'    => "(GMT) Casablanca",
            'Europe/Dublin'        => "(GMT) Dublin",
            'Europe/Lisbon'        => "(GMT) Lisbon",
            'Europe/London'        => "(GMT) London",
            'Africa/Monrovia'      => "(GMT) Monrovia",
            'Europe/Amsterdam'     => "(GMT+01:00) Amsterdam",
            'Europe/Belgrade'      => "(GMT+01:00) Belgrade",
            'Europe/Berlin'        => "(GMT+01:00) Berlin",
            'Europe/Bratislava'    => "(GMT+01:00) Bratislava",
            'Europe/Brussels'      => "(GMT+01:00) Brussels",
            'Europe/Budapest'      => "(GMT+01:00) Budapest",
            'Europe/Copenhagen'    => "(GMT+01:00) Copenhagen",
            'Europe/Ljubljana'     => "(GMT+01:00) Ljubljana",
            'Europe/Madrid'        => "(GMT+01:00) Madrid",
            'Europe/Paris'         => "(GMT+01:00) Paris",
            'Europe/Prague'        => "(GMT+01:00) Prague",
            'Europe/Rome'          => "(GMT+01:00) Rome",
            'Europe/Sarajevo'      => "(GMT+01:00) Sarajevo",
            'Europe/Skopje'        => "(GMT+01:00) Skopje",
            'Europe/Stockholm'     => "(GMT+01:00) Stockholm",
            'Europe/Vienna'        => "(GMT+01:00) Vienna",
            'Europe/Warsaw'        => "(GMT+01:00) Warsaw",
            'Europe/Zagreb'        => "(GMT+01:00) Zagreb",
            'Europe/Athens'        => "(GMT+02:00) Athens",
            'Europe/Bucharest'     => "(GMT+02:00) Bucharest",
            'Africa/Cairo'         => "(GMT+02:00) Cairo",
            'Africa/Harare'        => "(GMT+02:00) Harare",
            'Europe/Helsinki'      => "(GMT+02:00) Helsinki",
            'Europe/Istanbul'      => "(GMT+02:00) Istanbul",
            'Asia/Jerusalem'       => "(GMT+02:00) Jerusalem",
            'Europe/Kiev'          => "(GMT+02:00) Kyiv",
            'Europe/Minsk'         => "(GMT+02:00) Minsk",
            'Europe/Riga'          => "(GMT+02:00) Riga",
            'Europe/Sofia'         => "(GMT+02:00) Sofia",
            'Europe/Tallinn'       => "(GMT+02:00) Tallinn",
            'Europe/Vilnius'       => "(GMT+02:00) Vilnius",
            'Asia/Baghdad'         => "(GMT+03:00) Baghdad",
            'Asia/Kuwait'          => "(GMT+03:00) Kuwait",
            'Africa/Nairobi'       => "(GMT+03:00) Nairobi",
            'Asia/Riyadh'          => "(GMT+03:00) Riyadh",
            'Europe/Moscow'        => "(GMT+03:00) Moscow",
            'Asia/Tehran'          => "(GMT+03:30) Tehran",
            'Asia/Baku'            => "(GMT+04:00) Baku",
            'Europe/Volgograd'     => "(GMT+04:00) Volgograd",
            'Asia/Muscat'          => "(GMT+04:00) Muscat",
            'Asia/Tbilisi'         => "(GMT+04:00) Tbilisi",
            'Asia/Yerevan'         => "(GMT+04:00) Yerevan",
            'Asia/Kabul'           => "(GMT+04:30) Kabul",
            'Asia/Karachi'         => "(GMT+05:00) Karachi",
            'Asia/Tashkent'        => "(GMT+05:00) Tashkent",
            'Asia/Kolkata'         => "(GMT+05:30) Kolkata",
            'Asia/Kathmandu'       => "(GMT+05:45) Kathmandu",
            'Asia/Yekaterinburg'   => "(GMT+06:00) Ekaterinburg",
            'Asia/Almaty'          => "(GMT+06:00) Almaty",
            'Asia/Dhaka'           => "(GMT+06:00) Dhaka",
            'Asia/Novosibirsk'     => "(GMT+07:00) Novosibirsk",
            'Asia/Bangkok'         => "(GMT+07:00) Bangkok",
            'Asia/Jakarta'         => "(GMT+07:00) Jakarta",
            'Asia/Krasnoyarsk'     => "(GMT+08:00) Krasnoyarsk",
            'Asia/Chongqing'       => "(GMT+08:00) Chongqing",
            'Asia/Hong_Kong'       => "(GMT+08:00) Hong Kong",
            'Asia/Kuala_Lumpur'    => "(GMT+08:00) Kuala Lumpur",
            'Australia/Perth'      => "(GMT+08:00) Perth",
            'Asia/Singapore'       => "(GMT+08:00) Singapore",
            'Asia/Taipei'          => "(GMT+08:00) Taipei",
            'Asia/Ulaanbaatar'     => "(GMT+08:00) Ulaan Bataar",
            'Asia/Urumqi'          => "(GMT+08:00) Urumqi",
            'Asia/Irkutsk'         => "(GMT+09:00) Irkutsk",
            'Asia/Seoul'           => "(GMT+09:00) Seoul",
            'Asia/Tokyo'           => "(GMT+09:00) Tokyo",
            'Australia/Adelaide'   => "(GMT+09:30) Adelaide",
            'Australia/Darwin'     => "(GMT+09:30) Darwin",
            'Asia/Yakutsk'         => "(GMT+10:00) Yakutsk",
            'Australia/Brisbane'   => "(GMT+10:00) Brisbane",
            'Australia/Canberra'   => "(GMT+10:00) Canberra",
            'Pacific/Guam'         => "(GMT+10:00) Guam",
            'Australia/Hobart'     => "(GMT+10:00) Hobart",
            'Australia/Melbourne'  => "(GMT+10:00) Melbourne",
            'Pacific/Port_Moresby' => "(GMT+10:00) Port Moresby",
            'Australia/Sydney'     => "(GMT+10:00) Sydney",
            'Asia/Vladivostok'     => "(GMT+11:00) Vladivostok",
            'Asia/Magadan'         => "(GMT+12:00) Magadan",
            'Pacific/Auckland'     => "(GMT+12:00) Auckland",
            'Pacific/Fiji'         => "(GMT+12:00) Fiji",
        );

        $tz_stamp = time();
        foreach($timezones as $key => $timezone){
            date_default_timezone_set($key); 
            $timezones[$key] = "(GTM".date('P', $tz_stamp).") ".$key;
        }

        
        // Creamos opciones si no exiten
        $this->miempresa->crearOpcion("plantilla_orden_compra","estandar");
        $this->miempresa->crearOpcion("auto_factura","estandar");
        $this->miempresa->crearOpcion("auto_pago","estandar");
        $this->miempresa->crearOpcion("clientes_cartera","0");
        $this->miempresa->crearOpcion("redondear_precios","0");
        $this->miempresa->crearOpcion("sobrecosto_todos","0");
        $this->miempresa->crearOpcion("precio_almacen","0");
        $this->miempresa->crearOpcion("cierre_automatico","1");
        $this->miempresa->crearOpcion("plantilla_general","media_carta");
        $this->miempresa->crearOpcion("enviar_factura","no");
        $this->miempresa->crearOpcion("valor_caja","si");
        //opcion de mesas 
        $this->miempresa->crearOpcion("facturar_mesas","no");
        //opciones para mexico
        $this->miempresa->crearOpcion("num_exterior","");
        $this->miempresa->crearOpcion("num_interior","");
        $this->miempresa->crearOpcion("colonia","");
        $this->miempresa->crearOpcion("localidad","");
        $this->miempresa->crearOpcion("estado","");
        $this->miempresa->crearOpcion("municipio","");
        $this->miempresa->crearOpcion("codigo_postal","");
        
        $config['upload_path'] = 'uploads/';
        $config['allowed_types'] = 'JPG|JPEG|jpg|jpeg|PNG|png';
        $config['max_size'] = '50';
        $config['max_width'] = '250';
        $config['max_height'] = '250';
        $this->load->library('upload', $config);
        
        if (!empty($_FILES['logotipo']['name']))
            {
                if (!$this->upload->do_upload('logotipo'))
                {
                    $error_upload = $this->upload->display_errors('<p class="text-error">', '</p>');
                }
                else
                {
                    $upload_data = $this->upload->data();
                    $image_name = $upload_data['file_name'];
                    $logotipo_empresa = $this->miempresa->get_logotipo_empresa();
                    if (!empty($logotipo_empresa) && is_file("uploads/$logotipo_empresa"))
                    {
                        unlink("uploads/$logotipo_empresa");
                    }
                }
            }

        if(!empty($error_upload)){
            $error_upload.='<p class="text-error"> Tenga en cuenta: tamaño máximo de archivo '.$config['max_size'].' kb, alto y ancho de imagen: 250px.</p>';
        }    

        if ($this->form_validation->run('mi_empresa') == true && empty($error_upload))
        {
            //  $config = array();

            

            $plantilla = $this->input->post('plantilla');
            if ($this->input->post('sistema') == 'Pos')
            {
                $plantilla = $this->input->post('plantilla_pos');
            }

            $this->miempresa->update_data_empresa(array(
                'nombre_empresa' => $this->input->post('nombre'),
                'resolucion_factura' => $this->input->post('resolucion'),
                'contacto' => $this->input->post('contacto'),
                'email' => $this->input->post('email'),
                'direccion' => $this->input->post('direccion'),
                'telefono' => $this->input->post('telefono'),
                'fax' => $this->input->post('fax'),
                'web' => $this->input->post('web'),
                'moneda' => $this->input->post('moneda'),
                'resolucion_factura_estado' => $this->input->post('resolucion_factura_estado'),
                'plantilla' => $plantilla,
                'logotipo_empresa' => $image_name,
                'paypal_email' => $this->input->post('paypal_email'),
                'titulo_venta' => $this->input->post('titulo_venta'),
                'documento' => $this->input->post('documento'),
                'nit' => $this->input->post('nit'),
                'sistema' => $this->input->post('sistema'),
                'plantilla_cotizacion' => $this->input->post('plantilla_cotizacion'),
                'tipo_factura' => $this->input->post('tipo_factura'),
                'numero' => $this->input->post('numero'),
                'sobrecosto' => $this->input->post('sobrecosto'),
                'multiples_formas_pago' => $this->input->post('multiples_formas_pago'),
                'vendedor_impresion' => $this->input->post('vendedor_impresion'),
                'valor_caja' => $this->input->post('valor_caja'),
                'filtro_ciudad' => $this->input->post('filtro_ciudad'),
                'comanda' => $this->input->post('comanda'),
                'etienda' => $this->input->post('etienda'),
                'zona_horaria' => $this->input->post('zona_horaria'),
                'simbolo' => $this->input->post('simbolo'),
                'multiples_vendedores' => $this->input->post('multiples_vendedores'),
                'decimales_moneda' => $this->input->post('decimales'),
                'tipo_separador_decimales' => $this->input->post('separadorDecimales'),
                'tipo_separador_miles' => $this->input->post('separadorMiles'),
                'redondear_precios' => $this->input->post('redondear_precios'),
                'pais' => $this->input->post('pais'),
                'auto_factura' => $this->input->post('auto_factura'),
                'auto_pago' => $this->input->post('auto_pago'),
                'clientes_cartera' => $this->input->post('clientes_cartera'),
                'sobrecosto_todos' => $this->input->post('sobrecosto_todos'),
                'cierre_automatico' => $this->input->post('cierre_automatico'),
                'precio_almacen' => $this->input->post('precio_almacen'),
                'orden_compra_precio' => $this->input->post('orden_compra_precio'),
                'plantilla_orden_compra' => $this->input->post('plantilla_orden_compra'),
                "plantilla_general" => $this->input->post('plantilla_general'),
                "enviar_factura" => $this->input->post('enviar_factura'),
                "costo_promedio" => $this->input->post('costo_promedio'),
                "facturar_mesas" => $this->input->post('facturar_mesas'),
                "num_exterior"   => $this->input->post('t_num_exterior'),
                "num_interior"   => $this->input->post('t_num_interior'),
                "colonia"        => $this->input->post('t_colonia'),
                "localidad"      => $this->input->post('t_localidad'), 
                "estado"         => $this->input->post('t_estado'),
                "municipio"      => $this->input->post('t_municipio'),
                "codigo_postal"  => $this->input->post('t_codigo_posta'),
            ));
            if($this->input->post('precio_almacen') > 0){
                $this->updateDataProducts();
            }
            
            
            //------------------------------------------------------
            // Sólo si el usuario no prefiere un cierre automatico
            //------------------------------------------------------
            if( $this->input->post('cierre_automatico') == "0"){
                // Añadimos una columna a la tabla cierres_caja en el que se colocará la fecha final del cierre de caja
                $this->caja->addFechaCiierre();
            }
            //------------------------------------------------------

            $modulos = [
                'modulo_alertas' => 'alerta inventario minimo'
            ];

            foreach ($modulos as $key => $value) 
            {
                if($this->input->post($key) !== false && strlen($this->input->post($key)) > 0 )
                    $this->almacen->establecerModulo($value, $this->input->post($key));
            }    
        }

        $alertas = $this->almacen->obtenerModulo('alerta inventario minimo');
        $data = $this->miempresa->get_data_empresa();
        $data['data']['upload_error'] = $error_upload;
        $data['data']['timezones'] = $timezones;
        $data['data']['alertas'] = count($alertas) > 0 ? $alertas[0]->estado : -1;
        $data['moneda'] = $this->miempresa->get_nomen('moneda');
        $data['data']['decimales']= $this->opciones->getOpcion('decimales_moneda');
        $data['data']['separadorDecimales'] = $this->opciones->getOpcion('tipo_separador_decimales');
        $data['data']['separadorMiles'] = $this->opciones->getOpcion('tipo_separador_miles');
        $data['data']['redondear_precios'] = $this->opciones->getOpcion('redondear_precios');
        $data['data']['precio_almacen'] = $this->opciones->getOpcion('precio_almacen');
        $data['data']['orden_compra_precio'] = $this->opciones->getOpcion('orden_compra_precio');
        $data['data']['plantilla_orden_compra'] = $this->opciones->getOpcion('plantilla_orden_compra');
        $data['data']['plantilla_general'] = $this->opciones->getOpcion('plantilla_general');
        $data['data']['enviar_factura'] = $this->opciones->getOpcion('enviar_factura');
        $data['data']['costo_promedio'] = $this->opciones->getOpcion('costo_promedio');
        $data['data']['pais'] = $this->opciones->getOpcion('pais');
        $data['data']['paises'] = $this->pais->getAll();
        $data['data']['num_exterior'] = $this->opciones->getOpcion('num_exterior');
        $data['data']['num_interior'] = $this->opciones->getOpcion('num_interior');
        $data['data']['colonia'] = $this->opciones->getOpcion('colonia');
        $data['data']['localidad'] = $this->opciones->getOpcion('localidad');
        $data['data']['estado'] = $this->opciones->getOpcion('estado');
        $data['data']['municipio'] = $this->opciones->getOpcion('municipio');
        $data['data']['codigo_postal'] = $this->opciones->getOpcion('codigo_postal');

        $data['plantilla'] = $this->miempresa->get_plantillas();
        $this->layout->template('member')->show('miempresa/index', array(
            'data' => $data
        ));
    }
    
    public function updateDataProducts(){
        
        $product = $this->productos->getList();
        $data = array();
        foreach($product as $rowProduct){
            $data = array(
//                'producto_id' => $rowProduct->id,
                'precio_compra' => floatval($rowProduct->precio_compra),
                'precio_venta' => floatval($rowProduct->precio_venta),
                'stock_minimo' => intval($rowProduct->stock_minimo),
                'impuesto' => floatval($rowProduct->impuesto),
                'fecha_vencimiento' => floatval($rowProduct->fecha_vencimiento),
                'activo' => intval($rowProduct->activo),
            );
            $this->stock_actual->update_by_product($data,$rowProduct->id);
           
        }
        
        
                
    }

    public function terms_headers()
    {
        if ($this->form_validation->run('header_temrs') == true)
        {
            $this->miempresa->update_data_header_terms(array(
                'terminos' => $_POST['terms'], //$this->input->post('terms'),
                'cabecera' => $_POST['header'] //$this->input->post('header')
            ));
        }

        $cabecera = $this->miempresa->get_cabecera_factura();
        $terminos = $this->miempresa->get_terminos_condiciones();
        $this->layout->js(base_url("/public/js/plugins/cleditor/jquery.cleditor.js"))->template('member')->show('miempresa/terms_headers', array(
            'cabecera' => $cabecera,
            'terminos' => $terminos
        ));
    }

    public function numeros()
    {
        if ($this->form_validation->run('numero_prefijo') == true)
        {  
            $this->miempresa->update_data_numeros(array(
                'prefijo_presupuesto' => $this->input->post('prefijo_presupuesto') ,
                'numero_presupuesto' => $this->input->post('numero_presupuesto') ,
                'numero_factura' => $this->input->post('numero_factura') ,
                'prefijo_factura' => $this->input->post('prefijo_factura'),
                'fecha_factura' => $this->input->post('fecha_factura') ,
                'numero_factura_fin' => $this->input->post('numero_factura_fin'),
                "numero_alerta_factura" => $this->input->post('numero_alerta_factura'),
                "dias_alerta_factura" => $this->input->post('dias_alerta_factura'),
                'prefijo_devolucion' => $this->input->post('prefijo_devolucion'),
                'numero_devolucion' => $this->input->post('numero_devolucion'),
                
            ));
            //configuracion siigo
            if(isset($_POST['codigo1FP']) && isset($_POST['codigo1I']))
            {
                //datos de forma pago
                $dataFP = array(
                    "codigo1"=> $this->input->post('codigo1FP'),
                    "codigo2"=> $this->input->post('codigo2FP'),
                    "codigo3"=> $this->input->post('codigo3FP'),
                    "codigo4"=> $this->input->post('codigo4FP'),
                    "codigo5"=> $this->input->post('codigo5FP'),
                    "codigo6"=> $this->input->post('codigo6FP'),
                    "letra"=> $this->input->post('letraFP'),
                );
                
                $this->cuentasSiigo->modificarCodigo(4,$dataFP);
                
                //datos Inventario
                $dataI = array(
                    "codigo1"=> $this->input->post('codigo1I'),
                    "codigo2"=> $this->input->post('codigo2I'),
                    "codigo3"=> $this->input->post('codigo3I'),
                    "codigo4"=> $this->input->post('codigo4I'),
                    "codigo5"=> $this->input->post('codigo5I'),
                    "codigo6"=> $this->input->post('codigo6I'),
                    "letra"=> $this->input->post('letraI'),
                );
                
                $this->cuentasSiigo->modificarCodigo(5,$dataI);
                
            }
        }
        
        $prefijo_presupuesto = $this->miempresa->get_prefijo_presupuesto();
        $numero_presupuesto = $this->miempresa->get_numero_presupuesto();
        $numero_factura = $this->miempresa->get_numero_factura();
        $prefijo_factura = $this->miempresa->get_prefijo_factura();
        $numero_factura_fin = $this->opciones->getOpcion("numero_factura_fin");
        $fecha_factura = $this->opciones->getOpcion("fecha_factura");
        $numero_alerta_factura= $this->opciones->getOpcion("numero_alerta_factura");
        $dias_alerta_factura = $this->opciones->getOpcion("dias_alerta_factura");
        $prefijo_devolucion = $this->opciones->getOpcion("prefijo_devolucion");
        $numero_devolucion = $this->opciones->getOpcion("numero_devolucion");
        $arregloSiigo = array();
        $existeSiigo = $this->cuentasSiigo->existeCS($this->session->userdata('base_dato'));
        if($existeSiigo)
        {
            $arregloSiigo = $this->cuentasSiigo->getTipoMovimientoContable();
        }
        
        $this->layout->template('member')->show('miempresa/numeros', array(
            'prefijo_presupuesto' => $prefijo_presupuesto,
            'numero_presupuesto' => $numero_presupuesto,
            'numero_factura' => $numero_factura,
            'prefijo_factura' => $prefijo_factura,
            "numero_factura_fin" => $numero_factura_fin,
            "fecha_factura" => $fecha_factura,
            "numero_alerta_factura" => $numero_alerta_factura,
            "dias_alerta_factura" => $dias_alerta_factura,
            "prefijo_devolucion" => $prefijo_devolucion,
            "numero_devolucion" => $numero_devolucion,
            "arregloSiigo" => $arregloSiigo,
            "existeSiigo" => $existeSiigo,
        ));
    }

    public function obtenerOpcion($opcion){
        $this->output->set_content_type('application/json')->set_output(json_encode(
            $this->miempresa->obtenerOpcion($opcion)
        ));
    }
    
    public function cuentaSiigo()
    {
        var_dump($this->cuentasSiigo->crearTabla());
    }
    
}

?>
