<?php
$config = array(
            'clientes' =>   array(
                array(
                            'field' => 'nombre_comercial',
                            'label' => 'Nombre comercial',
                            'rules' => 'required|max_length[100]|xss_clean'
                     ),
                array(
                            'field' => 'razon_social',
                            'label' => 'Raz&oacute;n social',
                            'rules' => 'max_length[100]|xss_clean'
                     ),
                array(
                            'field' => 'email',
                            'label' => 'Email',
                            'rules' => 'valid_email|max_length[254]|xss_clean'
                     ),
                array(
                            'field' => 'pais',
                            'label' => 'Pais',
                            'rules' => 'max_length[254]|xss_clean'
                     ),
                array(
                            'field' => 'provincia',
                            'label' => 'Provincia',
                            'rules' => 'max_length[254]|xss_clean'
                     ),
                array(
                            'field' => 'contacto',
                            'label' => 'Contacto',
                            'rules' => 'max_length[100]|xss_clean'
                     ),
                array(
                            'field' => 'nif_cif',
                            'label' => 'NIF/CIF',
                            'rules' => 'required|max_length[15]|callback_nif_check|xss_clean'
                     ),
                array(
                            'field' => 'cp',
                            'label' => 'Código postal',
                            'rules' => 'max_length[5]|xss_clean'
                     ),
                array(
                            'field' => 'telefono',
                            'label' => 'Teléfono',
                            'rules' => 'max_length[10]|xss_clean'
                     ),
                array(
                            'field' => 'fax',
                            'label' => 'Fax',
                            'rules' => 'max_length[10]|xss_clean'
                     ),
                array(
                            'field' => 'tipo_empresa',
                            'label' => 'Tipo de Empresa',
                            'rules' => 'max_length[80]|xss_clean'
                     )
            ),
            'clientes_en_ajax' => array(

                array(
                            'field' => 'nombre_comercial',
                            'label' => 'Nombre comercial',
                            'rules' => 'required|max_length[100]|xss_clean'
                     ),
                array(
                            'field' => 'razon_social',
                            'label' => 'Raz&oacute;n social',
                            'rules' => 'max_length[100]|xss_clean'
                     ),
                array(
                            'field' => 'email',
                            'label' => 'Email',
                            'rules' => 'required|valid_email|max_length[80]|xss_clean'
                     ),
                array(
                            'field' => 'nif_cif',
                            'label' => 'NIF/CIF',
                            'rules' => 'required|max_length[15]|callback_nif_check|xss_clean'
                     ),

                ),
            'clientes_en_venta' =>   array(
                array(
                            'field' => 'nombre_comercial',
                            'label' => 'Nombre comercial',
                            'rules' => 'required|max_length[100]|xss_clean'
                     ),
                array(
                            'field' => 'razon_social',
                            'label' => 'Raz&oacute;n social',
                            'rules' => 'max_length[100]|xss_clean'
                     ),
                array(
                            'field' => 'pais',
                            'label' => 'Pais',
                            'rules' => 'required|xss_clean'
                     ),
                array(
                            'field' => 'provincia',
                            'label' => 'Provincia',
                            'rules' => 'required|xss_clean'
                     ),
                array(
                            'field' => 'contacto',
                            'label' => 'Contacto',
                            'rules' => 'max_length[100]|xss_clean'
                     ),
                array(
                            'field' => 'nif_cif',
                            'label' => 'NIF/CIF',
                            'rules' => 'max_length[15]|callback_nif_check_with_empty|xss_clean'
                     ),
                array(
                            'field' => 'cp',
                            'label' => 'Código postal',
                            'rules' => 'max_length[5]|xss_clean'
                     ),
                array(
                            'field' => 'telefono',
                            'label' => 'Teléfono',
                            'rules' => 'max_length[10]|xss_clean'
                     ),
                array(
                            'field' => 'fax',
                            'label' => 'Fax',
                            'rules' => 'max_length[10]|xss_clean'
                     ),
                array(
                            'field' => 'tipo_empresa',
                            'label' => 'Tipo de Empresa',
                            'rules' => 'max_length[80]|xss_clean'
                     )
            ),
            'vendedor' => array(
                array(
                            'field' => 'nombre',
                            'label' => 'Nombre',
                            'rules' => 'trim|required|max_length[254]|xss_clean'
                     ),
                array(
                            'field' => 'telefono',
                            'label' => 'Teléfono',
                            'rules' => 'trim|max_length[15]|xss_clean'
                     ),
                array(
                            'field' => 'cedula',
                            'label' => 'Cedula',
                            'rules' => 'trim|required|max_length[20]|xss_clean'
                     ),
                array(
                            'field' => 'almacen',
                            'label' => 'almacen',
                            'rules' => 'trim|required|xss_clean'
                     ),
                array(
                            'field' => 'email',
                            'label' => 'Email',
                            'rules' => 'valid_email|max_length[254]|xss_clean'
                ),
            ),   
            'domiciliario' => array(
                array(
                            'field' => 'nombre',
                            'label' => 'Nombre',
                            'rules' => 'required|trim|xss_clean|max_length[250]'
                     )                
            ),          
            'roles' => array(
                array(
                            'field' => 'nombre_rol',
                            'label' => 'Nombre',
                            'rules' => 'required|max_length[254]|xss_clean'
                     ),
                array(
                            'field' => 'descripcion',
                            'label' => 'Descripcion',
                            'rules' => 'xss_clean'
                     ),
                array(
                            'field' => 'permisos',
                            'label' => 'Permisos',
                            'rules' => 'xss_clean'
                     )
            ),
            'proveedores' =>   array(
                array(
                            'field' => 'nombre_comercial',
                            'label' => 'Nombre comercial',
                            'rules' => 'required|max_length[100]|xss_clean'
                     ),
                array(
                            'field' => 'razon_social',
                            'label' => 'Raz&oacute;n social',
                            'rules' => 'required|max_length[100]|xss_clean'
                     ),
                array(
                            'field' => 'pais',
                            'label' => 'Pais',
                            'rules' => 'required|xss_clean'
                     ),
                array(
                            'field' => 'provincia',
                            'label' => 'Provincia',
                            'rules' => 'required|xss_clean'
                     ),
                array(
                            'field' => 'contacto',
                            'label' => 'Contacto',
                            'rules' => 'max_length[100]|xss_clean'
                     ),
                array(
                            'field' => 'nif_cif',
                            'label' => 'NIF/CIF',
                            'rules' => 'required|max_length[15]|callback_nif_check|xss_clean'
                     ),
                array(
                            'field' => 'cp',
                            'label' => 'Código postal',
                            'rules' => 'max_length[5]|xss_clean'
                     ),
                array(
                            'field' => 'telefono',
                            'label' => 'Teléfono',
                            'rules' => 'max_length[10]|xss_clean'
                     ),
                array(
                            'field' => 'fax',
                            'label' => 'Fax',
                            'rules' => 'max_length[10]|xss_clean'
                     ),
                array(
                            'field' => 'tipo_empresa',
                            'label' => 'Tipo de Empresa',
                            'rules' => 'max_length[80]|xss_clean'
                     )
            ),
            'productos' =>   array(
                        array(
                                    'field' => 'nombre',
                                    'label' => 'Nombre',
                                    'rules' => 'required|max_length[254]|xss_clean'
                            ), 
                        array(
                                    'field' => 'codigo',
                                    'label' => 'Codigo',
                                    'rules' => 'required|max_length[254]|xss_clean'
                            ),
                            array(
                                    'field' => 'precio_compra',
                                    'label' => 'Precio de compra',
                                    'rules' => 'required|xss_clean'
                            ),
                        array(
                                    'field' => 'precio',
                                    'label' => 'Precio de venta',
                                    'rules' => 'required|xss_clean'
                            ),
                        array(
                                    'field' => 'id_impuesto',
                                    'label' => 'Impuesto',
                                    'rules' => 'numeric'
                            ),
                        array(
                                    'field' => 'categoria_id',
                                    'label' => 'Categoria',
                                    'rules' => 'numeric'
                            )    
                    ),
            'productos_almacen' =>   array(
                        array(
                                    'field' => 'nombre',
                                    'label' => 'Nombre',
                                    'rules' => 'required|max_length[254]|xss_clean'
                            ), 
                        array(
                                    'field' => 'codigo',
                                    'label' => 'Codigo',
                                    'rules' => 'max_length[254]|xss_clean'
                            ),
                        array(
                                    'field' => 'categoria_id',
                                    'label' => 'Categoria',
                                    'rules' => 'numeric'
                            )    
                    ),
              'productosf' => array(
                        array(
                                    'field' => 'nombre',
                                    'label' => 'Nombre',
                                    'rules' => 'required|max_length[254]|callback_product_check|xss_clean'
                            ), 
                        array(
                                    'field' => 'codigo',
                                    'label' => 'Codigo',
                                    'rules' => 'max_length[254]|xss_clean'
                            ),
                            array(
                                    'field' => 'precio_compra',
                                    'label' => 'Precio de compra',
                                    'rules' => 'required|numeric|xss_clean'
                            ),
                        array(
                                    'field' => 'precio',
                                    'label' => 'Precio de venta',
                                    'rules' => 'required|numeric|xss_clean'
                            ),
                        array(
                                    'field' => 'id_impuesto',
                                    'label' => 'Impuesto',
                                    'rules' => 'numeric'
                            )
              ),      
             'categorias' =>   array(
                        array(
                                    'field' => 'nombre',
                                    'label' => 'Nombre',
                                    'rules' => 'required|max_length[254]|callback_category_check|xss_clean'
                            ), 
                        array(
                                    'field' => 'codigo',
                                    'label' => 'Codigo',
                                    'rules' => 'max_length[254]|xss_clean'
                            )
                            
                    ),
            'almacenes' =>   array(
                         array(
                                    'field' => 'razon_social',
                                    'label' => 'Razon social',
                                    'rules' => ''
                            ), 
                        array(
                                    'field' => 'nombre',
                                    'label' => 'Nombre',
                                    'rules' => 'required|max_length[254]|callback_almacen_check|xss_clean'
                            ), 
                        /*array(
                            'field' => 'facturacion_electronica',
                            'label' => 'Facturación Electrónica',
                            'rules' => 'required|xss_clean'
                        ),*/
                        array(
                            'field' => 'prefijo_dian',
                            'label' => 'Prefijo DIAN:',
                            'rules' => 'callback_prefijo_dian_check|xss_clean'
                        ),
                        array(
                                    'field' => 'direccion',
                                    'label' => 'Direccion',
                                    'rules' => 'xss_clean'
                            ), 
                        array(
                                    'field' => 'prefijo',
                                    'label' => 'Prefijo',
                                    'rules' => 'required|max_length[254]|xss_clean'
                            ),
                        array(
                                    'field' => 'consecutivo',
                                    'label' => 'Consecutivo',
                                    'rules' => 'required|integer|xss_clean|callback_consecutivo_check'
                            ),
                        array(
                                    'field' => 'telefono',
                                    'label' => 'Telefono',
                                    'rules' => 'required|max_length[20]|xss_clean'
                            ),
                        array(
                                    'field' => 'correo_electronico',
                                    'label' => 'Correo electrónico',
                                    'rules' => 'required|valid_email|max_length[250]|xss_clean'
                            ),
                        array(
                                    'field' => 'meta_diaria',
                                    'label' => 'Meta Diaria',
                                    'rules' => 'numeric|xss_clean'
                        )
                            
                    ),
                'bodegas' =>   array(
                        
                       array(
                                   'field' => 'nombre',
                                   'label' => 'Nombre',
                                   'rules' => 'required|max_length[254]|callback_almacen_check|xss_clean'
                           ), 
                       array(
                                   'field' => 'direccion',
                                   'label' => 'Direccion',
                                   'rules' => 'xss_clean'
                           )                                                                         
                   ),
                'almacenesbodegasadm' =>   array(                        
                       array(
                                   'field' => 'nombre',
                                   'label' => 'nombre almacén',
                                   'rules' => 'required|max_length[254]|xss_clean'
                           ), 
                        array(
                                   'field' => 'id_plan',
                                   'label' => 'plan',
                                   'rules' => 'required'
                           ), 
                       array(
                                   'field' => 'bd',
                                   'label' => 'bd',
                                   'rules' => 'required'
                           )                                                                         
                   ),
            'servicios' =>   array(
                        array(
                                    'field' => 'nombre',
                                    'label' => 'Nombre',
                                    'rules' => 'required|callback_service_check|max_length[254]|xss_clean'
                            ), 
                        array(
                                    'field' => 'codigo',
                                    'label' => 'Codigo',
                                    'rules' => 'max_length[254]|xss_clean'
                            ),
                        array(
                                    'field' => 'precio',
                                    'label' => 'Raz&oacute;n social',
                                    'rules' => 'required|numeric|xss_clean'
                            ),
                        array(
                                    'field' => 'id_impuesto',
                                    'label' => 'Impuesto',
                                    'rules' => 'required'
                            )
                    ),
            'facturas' => array(
                        array(
                                    'field' => 'numero',
                                    'label' => 'N&uacute;mero',
                                    'rules' => 'required|xss_clean'
                        ),
                        array(
                                    'field' => 'fecha',
                                    'label' => 'Fecha',
                                    'rules' => 'required|xss_clean'
                        ),
                        array(
                            'field' => 'fecha_v',
                            'label' => 'Fecha de vencida',
                            'rules' => 'xss_clean'
                        ),
                         array(
                                    'field' => 'id_cliente',
                                    'label' => 'Cliente',
                                    'rules' => 'required|xss_clean'
                        ),
                        /* array(
                                    'field' => 'datos_cliente',
                                    'label' => 'Cliente',
                                    'rules' => 'required|xss_clean'
                        ),*/
                         array(
                                    'field' => 'input_total_civa',
                                    'label' => 'Monto',
                                    'rules' => 'required|numeric|xss_clean'
                        )
            ),
            'facturas_editar' => array(
                        array(
                                    'field' => 'estado',
                                    'label' => 'Estado',
                                    'rules' => 'required|numeric|xss_clean'
                        ),
                        array(
                                    'field' => 'id_factura',
                                    'label' => 'Factura',
                                    'rules' => 'required|numeric|xss_clean'
                        )
            ),
            'proformas' => array(
                        array(
                                    'field' => 'descripcion',
                                    'label' => 'Descripci&oacute;n',
                                    'rules' => 'max_length[254]|xss_clean'
                        ),
                        array(
                                    'field' => 'fecha',
                                    'label' => 'Fecha',
                                    'rules' => 'required|xss_clean'
                        ),
                        array(
                                    'field' => 'almacen',
                                    'label' => 'Almacen',
                                    'rules' => 'required|xss_clean'
                        ),
                         array(
                                    'field' => 'id_proveedor',
                                    'label' => 'Proveedor',
                                    'rules' => 'required|xss_clean'
                        ),
                         array(
                                    'field' => 'otros_datos',
                                    'label' => 'Datos',
                                    'rules' => 'required|xss_clean'
                        ),
                         array(
                                  'field' => 'id_impuesto',
                                    'label' => 'Impuesto',
                                    'rules' => 'numeric|xss_clean'
                        ),
                         array(
                                    'field' => 'cantidad',
                                    'label' => 'Cantidad',
                                    'rules' => 'required|numeric|xss_clean'
                        ),
                         array(
                                    'field' => 'valor',
                                    'label' => 'Valor',
                                    'rules' => 'required|numeric|xss_clean'
                        ),
                         


            ),
            'proformas_editar' => array(
                       /* array(
                                    'field' => 'estado',
                                    'label' => 'Estado',
                                    'rules' => 'required|numeric|xss_clean'
                        ),*/
                        array(
                                    'field' => 'id_proforma',
                                    'label' => 'Proforma',
                                    'rules' => 'required|numeric|xss_clean'
                        )
            ),
            'presupuestos' => array(
                        array(
                                    'field' => 'numero',
                                    'label' => 'N&uacute;mero',
                                    'rules' => 'required|xss_clean'
                        ),
                        array(
                                    'field' => 'fecha',
                                    'label' => 'Fecha',
                                    'rules' => 'required|xss_clean'
                        ),
                         array(
                                    'field' => 'id_cliente',
                                    'label' => 'Cliente',
                                    'rules' => 'required|xss_clean'
                        ),
                        /* array(
                                    'field' => 'datos_cliente',
                                    'label' => 'Cliente',
                                    'rules' => 'required|xss_clean'
                        ),*/
                         array(
                                    'field' => 'input_total_civa',
                                    'label' => 'Monto',
                                    'rules' => 'required|numeric|xss_clean'
                        )
            ),
            'provincias' => array(
                        array(
                                    'field' => 'nombre_provincia',
                                    'label' => 'Nombre',
                                    'rules' => 'required|xss_clean'
                        )
            ),
            'menu' => array(
                        array(
                                    'field' => 'nombre_link',
                                    'label' => 'Nombre',
                                    'rules' => 'required|xss_clean'
                        )
                        ,array(
                                    'field' => 'peso',
                                    'label' => 'Nombre',
                                    'rules' => 'numeric'
                        )
            ),
            'sub_menu' => array(
                        array(
                                    'field' => 'nombre_link',
                                    'label' => 'Nombre',
                                    'rules' => 'required|xss_clean'
                        )
                        ,array(
                                    'field' => 'peso',
                                    'label' => 'Nombre',
                                    'rules' => 'numeric'
                        )
                        ,array(
                                    'field' => 'menu_id',
                                    'label' => 'Menu',
                                    'rules' => 'required'
                        )
            ),
            'mi_empresa_config' => array(
                array(
                    'field' => 'nombre',
                    'rules' => 'required|xss_clean'
                )
            ),
            'mi_empresa_factura' => array(
                array(
                    'field' => 'nombre_empresa',
                    'label' => 'Nombre Empresa',
                    'rules' => 'required|xss_clean'
                ),
                array(
                    'field' => 'tipo_identificacion',
                    'label' => 'Tipo Identificacion',
                    'rules' => 'required|xss_clean'
                ),
                array(
                    'field' => 'numero_identificacion',
                    'label' => 'Numero Identificacion',
                    'rules' => 'required|xss_clean'
                ),
                array(
                    'field' => 'correo_factura',      
                    'label' => 'Correo Electrónico',              
                    'rules' => 'required|valid_email|max_length[80]|xss_clean'
                ),
                array(
                    'field' => 'contacto_factura',
                    'label' => 'Nombre Contacto',
                    'rules' => 'xss_clean'
                ),
                array(
                    'field' => 'telefono_factura',
                    'label' => 'Teléfono',
                    'rules' => 'xss_clean'
                ),
                array(
                    'field' => 'pais_factura',
                    'label' => 'País',
                    'rules' => 'required|xss_clean'
                ),
                array(
                    'field' => 'ciudad_factura',
                    'label' => 'Ciudad',
                    'rules' => 'required|xss_clean'
                ),
                array(
                    'field' => 'direccion_factura',
                    'label' => 'Dirección',
                    'rules' => 'required|xss_clean'
                )
                
            ),
            'header_temrs_config' => array(
                array(
                    'field' => 'plantilla_general',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'titulo_venta',
                    'rules' => 'required'
                ),
                 array(
                    'field' => 'terms',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'header',
                    'rules' => 'required'
                )
            ),
            'numero_prefijo_config' => array(
                array(
                    'field' => 'numero_presupuesto',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'prefijo_presupuesto',
                    'rules' => 'required'
                ),
                 array(
                    'field' => 'numero_devolucion',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'prefijo_devolucion',
                    'rules' => 'required'
                )
            ),
            'impuestos_config' => array(
                array(
                    'field' => 'nombre',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'porciento',
                    'rules' => 'required'
                ),
            ),
            
            
            'mi_empresa' => array(
                        array(
                                    'field' => 'nombre',
                                    'label' => 'Nombre',
                                    'rules' => 'required|xss_clean'
                        )
                        ,array(
                                    'field' => 'email',
                                    'label' => 'Email',
                                    'rules' => 'valid_email'
                        )
                        ,array(
                                    'field' => 'paypal_email',
                                    'label' => 'Email para paypal',
                                    'rules' => 'valid_email'
                        )
            ),
            'header_temrs' => array(
                        array(
                                    'field' => 'terms',
                                    'label' => 'Terminos',
                                    'rules' => 'xss_clean'
                        )
                        ,array(
                                    'field' => 'header',
                                    'label' => 'Cabecera',
                                    'rules' => 'xss_clean'
                        )
            ),
            'impuestos' => array(
                        array(
                                    'field' => 'nombre',
                                    'label' => 'Nombre',
                                    'rules' => 'required|max_length[254]|xss_clean'
                        )
                        ,array(
                                    'field' => 'porciento',
                                    'label' => 'Porciento',
                                    'rules' => 'required|numeric'
                        )
            ),
            'import_libro_precios' => array(
                array(
                    'field' => 'inicio',
                    'label' => 'Fecha inicio',
                    'rules' => 'required|date'
                ),
                array(
                    'field' => 'termina',
                    'label' => 'Fecha fin',
                    'rules' => 'required|date'
                ),
                array(
                    'field' => 'nombre',
                    'label' => 'Nombre',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'grupo',
                    'label' => 'Grupo',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'almacen',
                    'label' => 'Almacen',
                    'rules' => 'required'
                )
            ),
            'promociones' => array(
                array(
                    'field' => 'nombre',
                    'label' => 'Nombre',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'fecha_inicial',
                    'label' => 'Fecha inicial',
                    'rules' => 'required|date'
                ),
                array(
                    'field' => 'hora_inicial',
                    'label' => 'Hora inicial',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'fecha_final',
                    'label' => 'Fecha final',
                    'rules' => 'required|date'
                ),
                array(
                    'field' => 'hora_final',
                    'label' => 'Hora final',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'almacenes',
                    'label' => 'Almacenes',
                    'rules' => 'required'
                ),
                array(
                    'field' => 'dias',
                    'label' => 'Días',
                    'rules' => 'required'
                ),
            ),
            'import_excel' => array(
                        /*array(
                                    'field' => 'archivo',
                                    'label' => 'Archivo',
                                    'rules' => 'required'
                        )*/
            ),
            'pagos' => array(
                        array(
                                    'field' => 'fecha_pago',
                                    'label' => 'Fecha',
                                    'rules' => 'required|xss_clean'
                        )
                        ,array(
                                    'field' => 'id_factura',
                                    'label' => 'Factura',
                                    'rules' => 'required|numeric'
                        )
                        ,array(
                                    'field' => 'cantidad',
                                    'label' => 'Cantidad',
                                    'rules' => 'required|numeric|callback_cantidad'
                        )
                        ,array(
                                    'field' => 'tipo',
                                    'label' => 'Tipo de pago',
                                    'rules' => 'required|max_length[254]'
                        )
                        ,array(
                                    'field' => 'importe_retencion',
                                    'label' => 'Importe de la retencion',
                                    'rules' => 'numeric'
                            
                        )
            ),
             'numero_prefijo' => array(
                        array(
                                    'field' => 'numero_factura',
                                    'label' => 'Numero',
                                    'rules' => 'required|integer|xss_clean'
                        )
                        ,array(
                                    'field' => 'numero_presupuesto',
                                    'label' => 'Factura',
                                    'rules' => 'required|integer|xss_clean'
                        )
                        ,array(
                                    'field' => 'prefijo_presupuesto',
                                    'label' => 'Cantidad',
                                    'rules' => 'max_length[10]'
                        )
                        ,array(
                                    'field' => 'prefijo_factura',
                                    'label' => 'Tipo de pago',
                                    'rules' => 'max_length[10]'
                        )
            ),
             'opciones' => array(
                        array(
                                    'field' => 'nombre_opcion',
                                    'label' => 'Nombre de la opcion',
                                    'rules' => 'required|xss_clean'
                        )
                        ,array(
                                    'field' => 'mostrar_opcion',
                                    'label' => 'Mostrar de la opcion',
                                    'rules' => 'required|xss_clean'
                        )
                        ,array(
                                    'field' => 'valor_opcion',
                                    'label' => 'Valor de la opcion',
                                    'rules' => 'required|xss_clean'
                        )
            ),
            'forma_pago' => array(
                array(
                    'field'=>'nombre',
                    'label'=>'Nombre de la forma de pago',
                    'rules'=>'required|xss_clean'
                ),
            ),
            'secciones_almacen' =>array(
                  array(
                        'field' => 'codigo',
                        'label' => 'codigo de la seccion',
                        'rules' =>'xss_clean'
                    ),
                    array(
                         'field' => 'nombre' ,
                         'label' => 'nombre de la seccion',
                         'rules' => 'required|xss_clean'  
                    ),  
                    array(
                         'field' => 'almacen' ,
                         'label' => 'almacen al que pertenece la seccion',
                         'rules' => 'required|is_natural_no_zero'  
                    ),
                    array(
                         'field' => 'descripcion' ,
                         'label' => 'la descripcion de la seccion',
                         'rules' => 'xss_clean'  
                    ),
                ),
                'mesas_secciones' => array(
                    array(
                          'field' => 'nombre' ,
                          'label' => 'nombre de la mesa ',
                          'rules' => 'required|xss_clean'    
                    ),
                    array(
                          'field' => 'seccion' ,
                          'label' => ' seccion de la mesa ',
                          'rules' => 'required|is_natural_no_zero'    
                    ),
                ),
                'tamanos_productos' => array(
                    array(
                        'field' => 't_nombre_tamano',
                        'label' => 'Nombre del nuevo tamaño',
                        'rules' => 'required|xss_clean'

                    ),
                    array(
                        'field' => 's_categorias_prducto',
                        'label' => 'Categorias a las que pertence el tamaño',
                        'rules' => 'required|xss_clean'   
                    ),

                ),
                'empresas_clientes' => array(
                    array(
                        'field' => 'nombre_empresa',
                        'label' => 'Nombre empresa',
                        'rules' => 'required|xss_clean|trim'
                    ),
                    array(
                        'field' => 'tipo_identificacion',
                        'label' => 'Tipo Identificacion',
                        'rules' => 'required|xss_clean'   
                    ),
                    array(
                        'field' => 'identificacion_empresa',
                        'label' => 'Identificacion Empresa',
                        'rules' => 'required|xss_clean'   
                    ),
                    array(
                        'field' => 'razon_social_empresa',
                        'label' => 'Raz&oacute;n social',
                        'rules' => 'max_length[100]|xss_clean'
                    ),
                    array(
                        'field' => 'direccion_empresa',
                        'label' => 'Direccion',
                        'rules' => 'xss_clean'
                    ), 
                    array(
                        'field' => 'telefono_contacto',
                        'label' => 'Telefono',
                        'rules' => 'max_length[20]|xss_clean'
                    ),                   
                    array(
                        'field' => 'id_db_config',
                        'label' => 'Id BD Config',
                        'rules' => 'required|xss_clean'
                    ),
                    array(
                        'field' => 'id_distribuidores_licencia',
                        'label' => 'Distribuidor Licencia',
                        'rules' => 'required|xss_clean'   
                    ),
                    array(
                        'field' => 'id_user_distribuidor',
                        'label' => 'Usuario Distribuidor',
                        'rules' => 'required|xss_clean'   
                    ),
                    array(
                        'field' => 'pais',
                        'label' => 'Pais',
                        'rules' => 'required|xss_clean'   
                    ),
                    array(
                        'field' => 'provincia',
                        'label' => 'Departamento Empresa',
                        'rules' => 'required|xss_clean'   
                    ),
                    array(
                        'field' => 'ciudad_empresa',
                        'label' => 'Ciudad Empresa',
                        'rules' => 'required|xss_clean'   
                    ),

                ),
                'licencias_empresas' => array(
                    
                    array(
                        'field' => 'idempresas_clientes',
                        'label' => 'idempresas_clientes es requerido',
                        'rules' => 'required|xss_clean'
                    ),
                    array(
                        'field' => 'id_almacen',
                        'label' => 'almacen es requerido',
                        'rules' => 'required|xss_clean'   
                    ),
                    array(
                        'field' => 'id_plan',
                        'label' => 'plan es requerido',
                        'rules' => 'required|xss_clean'   
                    ),
                    array(
                        'field' => 'fecha_inicio_licencia',
                        'label' => 'fecha_inicio_licencia es requerido',
                        'rules' => 'required|xss_clean'   
                    ),
                    array(
                        'field' => 'fecha_vencimiento',
                        'label' => 'fecha_vencimiento es requerido',
                        'rules' => 'required|xss_clean'   
                    ),

                ),
                'formas_pagos' => array(
                    array(
                        'field'=>'nombre_forma',
                        'label'=>'Nombre de la forma de pago',
                        'rules'=>'required|xss_clean'
                    ),
                    array(
                        'field'=>'descripcion',
                        'label'=>'Descripción de la forma de pago',
                        'rules'=>'required|xss_clean'
                    ),
                    array(
                        'field'=>'numero_cuenta',
                        'label'=>'Número de la forma de pago',
                        'rules'=>'required|xss_clean'
                    ),
                    array(
                        'field'=>'nombre_cuenta',
                        'label'=>'Nombre de la cuenta de pago',
                        'rules'=>'required|xss_clean'
                    ),
                    array(
                        'field'=>'activo_forma',
                        'label'=>'Activa la cuenta de pago',
                        'rules'=>'required|xss_clean'
                    ),
                ),
                'pagos_licencias' => array(
                    array(
                        'field'=>'id_almacen',
                        'label'=>'El almacen',
                        'rules'=>'required'
                    ),
                    array(
                        'field'=>'formapago',
                        'label'=>'La forma de pago',
                        'rules'=>'required'
                    ),
                    array(
                        'field'=>'valorpago',
                        'label'=>'Valor del pago',
                        'rules'=>'required|xss_clean'
                    ),
                    array(
                        'field'=>'estado',
                        'label'=>'Estado del pago',
                        'rules'=>'required'
                    ),
                    array(
                        'field'=>'fecha_pago',
                        'label'=>'fecha del pago',
                        'rules'=>'required'
                    ),
                ),'bancos' => array(
                    array(
                        'field'=>'nombre_cuenta',
                        'label'=>'nombre de cuenta',
                        'rules'=>'required'
                    ),
                    array(
                        'field'=>'numero_cuenta',
                        'label'=>'número de cuenta',
                        'rules'=>'required'
                    ),
                ),
                'crear_movimiento' => array(
                    array(
                        'field'=>'referencia',
                        'label'=>'referencia',
                        'rules'=>'required'
                    ),
                    array(
                        'field'=>'banco',
                        'label'=>'banco',
                        'rules'=>'required'
                    ),
                    array(
                        'field'=>'tipo_movimiento',
                        'label'=>'tipo de movimiento',
                        'rules'=>'required'
                    ),
                    array(
                        'field'=>'valor',
                        'label'=>'valor',
                        'rules'=>'required'
                    ),
                ),
                'crear_tipo_movimiento' => array(
                    array(
                        'field'=>'nombre_movimiento',
                        'label'=>'nombre de movimiento',
                        'rules'=>'required'
                    ),
                    array(
                        'field'=>'tipo_movimiento',
                        'label'=>'tipo de movimiento',
                        'rules'=>'required'
                    ),
                ),
    );
?>
