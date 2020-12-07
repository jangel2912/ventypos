<?php

ini_set('display_errors', 1);

class Job extends CI_Controller
{

    private $site = 'http://www.vendty.com/invoice/index.php/';
    public $dbConnection;
    public $connection;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('Encryption');
        $this->load->library('Spark');
        $this->load->model("crm_facturas_model", 'crm_facturas_model');
        $this->load->model("crm_model", 'crm_model');

        //Jeisson Rodriguez (03/07/2019)
        $this->load->helper('url');
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
    }

    public function facturas_vencidas()
    {
        $info = [
            "users" => [
                "id" => 2,
                "name" => "Pedro Ramirez",
                "empresa" => "Empresa de Prueba",
                "fecha_pago" => "01-01-2017",
                "email" => "desarrollo@vendty.com",
                "monto" => "300000",
                "almacen" => "General",
            ],
        ];

        $this->spark->sendEmailFacturaVencida($info);
    }

    public function prueba_cron()
    {
        $this->db->select("*");
        $this->db->from("users");
        $this->db->limit("10");
        $result = $this->db->get();
        $messages = $result->result_array();
        $email = "";

        foreach ($messages as $message) {
            $email .= $message["username"] . '<br>';
        }

        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty software POS Online');
        $this->email->to("desarrollo@vendty.com");
        $this->email->subject('Prueba de cron');
        $this->email->message($email);

        if (!$this->email->send()) {
            echo 'No se pudo enviar el mensaje';
            var_dump($this->email->print_debugger());
        }
    }

    public function historial_inventario()
    {
        $date = date('Y-m-d');
        $nuevafecha = strtotime('-1 day', strtotime($date));
        $nuevafecha = date('Y-m-d', $nuevafecha);
        $this->load->helper('file');
        $ruta_archivo = 'application/logs/' . 'log_historico_' . $nuevafecha . '.txt';
        /*obtener bases de datos de vendty*/
        $q_databases = $this->db->query('SELECT DISTINCT base_dato FROM view_activos');
        $log = 'Creando copia de ' . $q_databases->num_rows() . '<br>';
        write_file($ruta_archivo, "\r\n" . str_replace('<br>', '.', $log) . "\r\n", "a+");
        $contador = 0;

        foreach ($q_databases->result() as $key => $base_de_datos) {
            $mensaje = 'Iniciando bd ' . $base_de_datos->base_dato . '<br>';
            $log .= $mensaje;
            write_file($ruta_archivo, "\r\n" . str_replace('<br>', '.', $mensaje) . "\r\n", "a+");
            $existeDB = $this->db->query("SHOW DATABASES WHERE `database` = '" . $base_de_datos->base_dato . "'");

            if ($existeDB->num_rows() == 0) {
                write_file($ruta_archivo, "\r\n" . str_replace('<br>', '.', 'la base de datos no existe ' . $base_de_datos->base_dato) . "\r\n", "a+");
                continue;
            }

            $existe_tabla = $this->db->query("SHOW TABLES FROM " . $base_de_datos->base_dato . " LIKE 'stock_historial'");

            if ($existe_tabla->num_rows() == 0) {
                write_file($ruta_archivo, "\r\n" . str_replace('<br>', '.', 'La tabla stock_historial no existe en la bd ' . $base_de_datos->base_dato) . "\r\n", "a+");
                continue;
            }

            //CREAMOS COLUMNAS ADICIONALES DE PRECIO DE COMPRA
            $sql = "SHOW COLUMNS FROM " . $base_de_datos->base_dato . ".stock_historial LIKE 'precio_compra_producto'";
            $existeCampo = $this->db->query($sql)->result();

            if (count($existeCampo) == 0) {
                $sql = "ALTER TABLE " . $base_de_datos->base_dato . ".stock_historial
                ADD COLUMN `precio_compra_producto` float NULL  COMMENT 'precio de compra de la tabla productos en el momento' ";
                $this->db->query($sql);
            }

            $q_validar = $this->db->query('SELECT * FROM ' . $base_de_datos->base_dato . '.stock_historial WHERE fecha = "' . $nuevafecha . '"')->result();

            if (count($q_validar) == 0) {
                $q_inventario = $this->db->query('SELECT s.*, p.`precio_venta`,p.`precio_compra` FROM ' . $base_de_datos->base_dato . '.stock_actual s INNER JOIN ' . $base_de_datos->base_dato . '.producto p ON (s.producto_id = p.id)');
                $data_insert = array();

                foreach ($q_inventario->result() as $key => $un_stock) {
                    $data_insert[$key] = array(
                        'fecha' => $nuevafecha,
                        'almacen_id' => $un_stock->almacen_id,
                        'producto_id' => $un_stock->producto_id,
                        'unidades' => $un_stock->unidades,
                        'precio' => $un_stock->precio_venta,
                        'precio_compra_producto' => $un_stock->precio_compra,
                    );
                }
                //hacemos insert en batch
                $mensaje = 'aca va el insert de ' . count($data_insert);
                write_file($ruta_archivo, "\r\n" . str_replace('<br>', '.', $mensaje) . "\r\n", "a+");

                if (!empty($data_insert)) {
                    $this->db->query("SET FOREIGN_KEY_CHECKS = 0");
                    $this->db->insert_batch($base_de_datos->base_dato . '.stock_historial', $data_insert);
                    $this->db->query("SET FOREIGN_KEY_CHECKS = 1");
                }

                $mensaje = '<br>Creado copia bd ' . $base_de_datos->base_dato . ' Correctamente, registros: ' . $q_inventario->num_rows() . '<br>';
                $log .= $mensaje;
                write_file($ruta_archivo, "\r\n" . str_replace('<br>', '.', $mensaje) . "\r\n", "a+");
                $contador++;
            }
        }

        $mensaje = '<br>Terminado de realizar copias de base de datos, el ciclo se ejecuto: ' . $contador;
        $log .= $mensaje;
        write_file($ruta_archivo, "\r\n" . str_replace('<br>', '.', $mensaje) . "\r\n", "a+");
        echo $log;
    }

    public function enviar_mensaje_auditoria($html)
    {
        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty software POS Online');
        $this->email->to("desarrollo@vendty.com");
        $this->email->subject('Log de copia historial de stock');
        $this->email->message($html);
    }

    public function alerta_inventario_minimo()
    {
        $date = date('Y-m-d');
        $this->load->library('email');
        $this->email->initialize();

        /*obtener bases de datos de vendty*/
        $q_databases = $this->db->query('SELECT * FROM vendty2.`view_clientes_modulos` WHERE nombre = "alerta inventario minimo" AND estado = 1');
        $databases = $this->filtrarActivos($q_databases->result());

        for ($i = 0; $i < count($databases); $i++) {
            $existeDB = $this->db->query("SHOW DATABASES WHERE `database` = '" . $databases[$i]->name . "'")->result();

            if (count($existeDB) == 0) {
                continue;
            }

            $q_inventario = $this->db->query('SELECT a.`nombre` almacen, c.`nombre` categoria, sa.`unidades`, p.`stock_minimo`,  p.`codigo`, p.`codigo_barra`, p.`nombre`, p.`precio_venta` FROM ((' . $databases[$i]->name . '.producto p JOIN ' . $databases[$i]->name . '.stock_actual sa ON  p.`id` = sa.`producto_id` AND (sa.`unidades` <= p.`stock_minimo` OR sa.unidades <= 0) LEFT JOIN ' . $databases[$i]->name . '.almacen a ON a.`id` = sa.`almacen_id`) LEFT JOIN ' . $databases[$i]->name . '.categoria c ON p.`categoria_id` = c.`id`) ORDER BY CAST(sa.`unidades` as SIGNED)')->result();

            if (!$q_inventario) {
                continue;
            }

            //si tiene inventario cercano al minimo permitido. obtener email de los administradores
            $q_admin = $this->db->query('SELECT * FROM (db_config dc JOIN users u ON dc.`id` = u.`db_config_id` AND u.`is_admin` = "t") WHERE dc.`base_dato` LIKE "' . $databases[$i]->name . '"')->result();
            $total = count($q_admin);

            if ($total > 0) {
                $id = 0;
                $nombre = "";

                foreach ($q_admin as $administrador) {
                    if (strlen(trim($administrador->email)) > 0) {
                        $id = $administrador->db_config_id;

                        if (is_null($administrador->first_name)) {
                            $nombre = strtoupper($administrador->username);
                        } else {
                            $nombre = strtoupper($administrador->first_name . ' ' . $administrador->last_name);
                        }

                        //codificar data url
                        $url_encode_data = $this->encryption->encode($databases[$i]->name . '~' . $id);
                        $ruta = $url_encode_data == '' ? 'informes/' : 'informes/inventarios_minimos_excel/';
                        $url = $this->site . $ruta . $url_encode_data;
                        $message = $this->load->view('email/minimum_inventory_alert', array('productos' => $q_inventario, 'url' => $url, 'nombre' => $nombre), true);
                        $this->email->from('no-responder@vendty.net', 'Vendty software POS Online');
                        $this->email->to($administrador->email);
                        $this->email->subject('Alerta inventario');
                        $this->email->message($message);

                        if (!$this->email->send()) {
                            echo 'No se pudo enviar el mensaje';
                            var_dump($this->email->print_debugger());
                        }
                    }
                }
            }
        }
    }

    public function wizard_incomplete()
    {
        $this->db->select("DISTINCT(u.id),d.fecha,u.username,u.email,p.step,p.type_business");
        $this->db->from("users u");
        $this->db->join("db_config d", "u.db_config_id = d.id");
        $this->db->join("primeros_pasos_usuarios p", "d.id = p.db_config");
        $this->db->where("u.is_admin", "t");
        $this->db->where("d.estado", 2);
        $result = $this->db->get();
        $clients = $result->result();
        $now = Date('y-m-d');
        $today = new DateTime($now);
        $this->load->library('email');
        $this->email->initialize();

        foreach ($clients as $client):
            if ($client->step > 1 && $client->step < 4):
                $date_create_account = new DateTime($client->fecha);
                $diference = $today->diff($date_create_account);
                if ($diference->days > 2 && $diference->days < 7):
                    $data = array(
                        'user' => $client->username,
                        'step' => 4 - $client->step,
                    );
                    $message = $this->load->view('email/wizard_incomplete', $data, true);
                    $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
                    $this->email->to($client->email);
                    $this->email->subject('Vendty - Primeros pasos ');
                    $this->email->message($message);
                endif;
            endif;
        endforeach;
    }

    public function helper_role_movimientos()
    {
        $q_databases = $this->db->query('SELECT SCHEMA_NAME AS `name` FROM information_schema.SCHEMATA WHERE SCHEMA_NAME IN (SELECT DISTINCT(TABLE_SCHEMA) FROM `information_schema`.TABLES WHERE TABLE_NAME = "permiso_rol" AND SCHEMA_NAME LIKE "%vendty2_db%")');
        $databases = $this->filtrarActivos($q_databases->result());

        $total = 0;
        for ($i = 0; $i < count($databases); $i++) {
            $q_roles = $this->db->query('SELECT id_rol FROM ' . $databases[$i]->name . '.rol WHERE id_rol NOT IN (SELECT id_rol FROM ' . $databases[$i]->name . '.permiso_rol WHERE id_permiso IN (1019, 1020, 1021))');
            if ($q_roles->num_rows() > 0) {
                $roles = $q_roles->result_array();
                foreach ($roles as $rol) {
                    $total++;
                    $q_insert = $this->db->query('INSERT INTO ' . $databases[$i]->name . '.permiso_rol (id_permiso, id_rol) VALUES (1019, ' . $rol['id_rol'] . '), (1020, ' . $rol['id_rol'] . '), (1021, ' . $rol['id_rol'] . ')');
                }
            }
        }

        echo $total . ' roles actualizados';
    }

    private function filtrarActivos($databases)
    {
        //filtro activos y existentes.
        for ($i = 0; $i < count($databases); $i++) {
            $q_activo = $this->db->query('SELECT estado FROM vendty2.db_config WHERE base_dato = "' . $databases[$i]->name . '"')->first_row();

            if (!$q_activo) {
                unset($databases[$i]);
                continue;
            }

            if ($q_activo->estado != '1') {
                unset($databases[$i]);
            }
        }

        $databases = array_values($databases);
        return $databases;
    }

    private function enviarEmail($configuracion)
    {
        $this->load->library('email');
        $this->email->initialize();

        foreach ($configuracion['destinos'] as $destino) {
            $correos = implode(',', $destino['correos']);
            $mensaje = '';
            $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
            $this->email->to($correos);
            $this->email->subject($configuracion['asunto']);

            switch ($configuracion['plantilla']) {
                case 'alerta':
                    $data = array(
                        "config" => $configuracion,
                        "destination" => $destino,
                    );
                    $message = $this->load->view('email/invoice_prefix_alert', $data, true);
                    break;
                default:
                    break;
            }

            $this->email->message($message);

            if (!$this->email->send()) {
                echo 'No se pudo enviar el mensaje';
                var_dump($this->email->print_debugger());
            }
        }
    }

    public function enviarEmailPlantilla($configuracion)
    {
        if (is_array($configuracion) && count($configuracion)) {
            $this->load->library('email');
            $this->email->initialize();

            foreach ($configuracion as $key) {
                foreach ($key as $email) {
                    $correo = $email['destino'];
                    $asunto = $email['asunto'];
                    $titulo = $email['titulo'];
                    $msg = $email['mensaje'];
                    $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
                    $this->email->to($correo);
                    $this->email->subject($asunto);
                    $mensaje = '
						<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
						<html xmlns="http://www.w3.org/1999/xhtml" style="font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif;box-sizing: border-box;font-size: 14px;margin: 0;">
						<head>
						<meta name="viewport" content="width=device-width" />
						<meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
						<title>Alerta</title>
						<style type="text/css">
						img {
						max-width: 100%;
						}
						body {
						-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6em;
						}
						body {
						background-color: #f6f6f6;
						}
						@media only screen and (max-width: 640px) {
							body {
							padding: 0 !important;
							}
							h1 {
							font-weight: 800 !important;margin: 20px 0 5px !important;
							}
							h2 {
							font-weight: 800 !important;margin: 20px 0 5px !important;
							}
							h3 {
							font-weight: 800 !important;margin: 20px 0 5px !important;
							}
							h4 {
							font-weight: 800 !important;margin: 20px 0 5px !important;
							}
							h1 {
							font-size: 22px !important;
							}
							h2 {
							font-size: 18px !important;
							}
							h3 {
							font-size: 16px !important;
							}
							.container {
							padding: 0 !important;width: 100% !important;
							}
							.content {
							padding: 0 !important;
							}
							.content-wrap {
							padding: 10px !important;
							}
							.invoice {
							width: 100% !important;
							}
						}
						</style>
						</head>
						<body itemscope itemtype="http://schema.org/EmailMessage" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;-webkit-font-smoothing: antialiased;-webkit-text-size-adjust: none;width: 100% !important;height: 100%;line-height: 1.6em;background-color: #f6f6f6;margin: 0;" bgcolor="#f6f6f6">
						<table class="body-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;width: 100%;background-color: #f6f6f6;margin: 0;" bgcolor="#f6f6f6"><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;margin: 0;"><td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;vertical-align: top;margin: 0;" valign="top"></td>
								<td class="container" width="600" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;vertical-align: top;display: block !important;max-width: 600px !important;clear: both !important;margin: 0 auto;" valign="top">
									<div class="content" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;max-width: 600px;display: block;margin: 0 auto;padding: 20px;">
										<table class="main" width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;border-radius: 3px;background-color: #fff;margin: 0;border: 1px solid #e9e9e9;" bgcolor="#fff"><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;margin: 0;"><td class="alert alert-warning" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 16px;vertical-align: top;color: #fff;font-weight: 500;text-align: center;border-radius: 3px 3px 0 0;background-color: #62CB31;margin: 0;padding: 20px;" align="center" bgcolor="#62CB31" valign="top">
													<img src="http://www.vendty.com/invoice/public/v2/img/logo_2.png" alt="">
												</td>
											</tr><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;margin: 0;"><td class="content-wrap" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;vertical-align: top;margin: 0;padding: 20px;" valign="top">
													<table width="100%" cellpadding="0" cellspacing="0" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;margin: 0;"><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;margin: 0;"><td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;vertical-align: top;margin: 0;padding: 0 0 20px;" valign="top">
																<strong>' . $titulo . '</strong><br>
															</td>
														</tr><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;margin: 0;"><td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;vertical-align: top;margin: 0;padding: 0;" valign="top">
																	<table class="invoice" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;text-align: left;width: 100%;margin: 20px auto;">
																		<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;margin: 0;"><td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;vertical-align: top;margin: 0;padding: 0;" valign="top">
																			' . $msg . '
																			</td>
																		</tr>
																</table>
															</td>
														</tr>

																												<tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;margin: 0;"><td class="content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;vertical-align: top;margin: 0;padding: 0 0 20px;" valign="top">
																<td>Gracias por elegirnos, equipo Vendty.
															</td>
														</tr></table></td>
											</tr></table><div class="footer" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;width: 100%;clear: both;color: #999;margin: 0;padding: 20px;">
											<table width="100%" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;margin: 0;"><tr style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;margin: 0;"><td class="aligncenter content-block" style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 12px;vertical-align: top;color: #999;text-align: center;margin: 0;padding: 0 0 20px;" align="center" valign="top"></td>
												</tr></table></div></div>
								</td>
								<td style="font-family: \'Helvetica Neue\',Helvetica,Arial,sans-serif;box-sizing: border-box;font-size: 14px;vertical-align: top;margin: 0;" valign="top"></td>
							</tr></table></body>
						</html>';

                    $this->email->message($mensaje);

                    if (!$this->email->send()) {
                        echo 'No se pudo enviar el mensaje';
                        var_dump($this->email->print_debugger());
                    }
                }
            }
        }
    }

    public function alertaPrefijoFactura()
    {
        $configuracion = [
            'titulo' => '¡Alerta!',
            'asunto' => 'Alerta Consecutivo Facturas',
            'mensaje' => '',
            'plantilla' => 'alerta',
            'destinos' => [],
        ];
        $q_databases = $this->db->query('SELECT * FROM vendty2.`view_clientes_modulos` WHERE nombre = "alerta prefijo facturas" AND estado = 1');
        $databases = $this->filtrarActivos($q_databases->result());

        for ($i = 0; $i < count($databases); $i++) {
            $existeDB = $this->db->query("SHOW DATABASES WHERE `database` = '" . $databases[$i]->name . "'")->result();

            if (count($existeDB) == 0) {
                continue;
            }
            //si la alerta es para todos los almacenes o por almacen
            $alertaGeneral = $this->db->query("select valor_opcion from " . $databases[$i]->name . ".opciones where nombre_opcion = 'numero'")->row()->valor_opcion;

            if ($alertaGeneral == "si") {
                $concecutivo = $this->db->query("select valor_opcion from " . $databases[$i]->name . ".opciones where nombre_opcion = 'last_numero_factura'")->row()->valor_opcion;
                $numeroFinal = $this->db->query("select valor_opcion from " . $databases[$i]->name . ".opciones where nombre_opcion = 'numero_fin_factura'")->row()->valor_opcion;
                $numeroAlerta = $this->db->query("select valor_opcion from " . $databases[$i]->name . ".opciones where nombre_opcion = 'numero_alerta_factura'")->row()->valor_opcion;
                $fechaVencimientofactura = $this->db->query("select valor_opcion from " . $databases[$i]->name . ".opciones where nombre_opcion = 'fecha_factura'")->row()->valor_opcion;
                $fechaAlerta = $this->db->query("select valor_opcion from " . $databases[$i]->name . ".opciones where nombre_opcion = 'dias_alerta_factura'")->row()->valor_opcion;
                $mensaje = "";

                if ($concecutivo == "" || $numeroFinal == "" || $numeroAlerta == "" || $fechaVencimientofactura == "" || $fechaAlerta == "") {
                    continue;
                }

                if ($concecutivo >= $numeroFinal) {
                    $mensaje = "El consecutivo de las facturas ha sobrepasado el número final configurado.<br>";
                } else if ($consecutivo >= $numeroAlerta) {
                    $numeroFaltantes = $numeroFinal - $concecutivo;
                    $mensaje = "El consecutivo de las facturas ha sobrepasado el número de alerta configurado.<br>";
                }

                $hoy = date("Y-m-d");
                $fechaAlertaActivar = date("Y-m-d", strtotime($fechaVencimientofactura));
                $fechaAlertaActivar = strtotime("-" . $fechaAlerta . " day", strtotime($fechaAlertaActivar));

                if ($hoy >= $fechaVencimientofactura) {
                    $mensaje .= " Su resolución de factura se ha vencido. Por favor renuévela lo más pronto posible.<br>";
                } else if ($hoy >= $fechaAlertaActivar) {
                    $mensaje .= " Su resolución de factura está pronta a cumplir su fecha de vencimiento. Solo faltan " . $interval->format('%R%a ') . " días para llegar al plazo.<br>";
                }

                if ($mensaje == "") {
                    continue;
                }

                $mensaje .= " Actualice los datos de facturación.";
                $correos = array();
                $roles = $this->db->query("SELECT * FROM " . $databases[$i]->name . ".permiso_rol where id_permiso = 1026 ")->result();

                foreach ($roles as $r) {
                    $usuarios = $this->db->query('SELECT * FROM (db_config dc JOIN users u ON dc.`id` = u.`db_config_id`) WHERE u.rol_id = ' . $r->id_rol . ' AND dc.`base_dato` LIKE "' . $databases[$i]->name . '"')->result();
                    foreach ($usuarios as $u) {
                        if (trim($u->email) != "") {
                            array_push($correo, $u->email);
                        }
                    }
                }

                if (count($correos) == 0) {
                    $usuarios = $this->db->query('SELECT * FROM (db_config dc JOIN users u ON dc.`id` = u.`db_config_id` AND u.`is_admin` = "t") WHERE dc.`base_dato` LIKE "' . $databases[$i]->name . '"')->result();

                    foreach ($usuarios as $u) {
                        if (trim($u->email) != "") {
                            array_push($correos, $u->email);
                        }
                    }
                }

                $configuracion['destinos'] = array();
                array_push($configuracion['destinos'], ['correos' => $correos, 'mensaje' => $mensaje]);
                $this->enviarEmail($configuracion);
            } else {
                $almacenes = $this->db->query("SELECT * FROM " . $databases[$i]->name . ".almacen where activo=1 AND bodega=0")->result();

                foreach ($almacenes as $a) {
                    if (
                        $a->consecutivo == "" ||
                        $a->numero_fin == "" ||
                        $a->numero_alerta == "" ||
                        $a->fecha_vencimiento == "" ||
                        $a->fecha_alerta == ""
                    ) {
                        continue;
                    }

                    $mensaje = "";

                    if ($a->consecutivo >= $a->numero_fin) {
                        $mensaje = "* El consecutivo de las facturas ha sobrepasado el número final configurado.<br>";
                    } else {
                        if ($a->consecutivo >= $a->numero_alerta) {
                            $mensaje = "* El consecutivo de las facturas ha sobrepasado el número de alerta configurado.<br>";
                        }
                    }

                    $hoy = date("Y-m-d");

                    if ($a->fecha_vencimiento == '0000-00-00') {
                        $fechaAlertaActivar = date("Y-m-d", strtotime("+1 year", strtotime($hoy)));
                        $a->fecha_vencimiento = $fechaAlertaActivar;
                    } else {
                        $fechaAlertaActivar = date("Y-m-d", strtotime($a->fecha_vencimiento));
                        $fechaAlertaActivar = date("Y-m-d", strtotime("-$a->fecha_alerta day", strtotime($fechaAlertaActivar)));
                    }

                    if ($hoy >= $a->fecha_vencimiento) {
                        $mensaje .= "* Su resolución de factura se ha vencido. Por favor renuévela lo más pronto posible.<br>";
                    } else {
                        if ($hoy >= $fechaAlertaActivar) {
                            $datetime1 = date_create($hoy);
                            $datetime2 = date_create($a->fecha_vencimiento);
                            $interval = date_diff($datetime2, $datetime1);
                            $mensaje .= "* Su resolución de factura está pronta a cumplir su fecha de vencimiento. Solo faltan " . abs($interval->format('%R%a ')) . " días para llegar al plazo.<br>";
                        }
                    }

                    if ($mensaje == "") {
                        continue;
                    }

                    $mensaje .= " Actualice los datos de facturación.";
                    $configuracion['titulo'] = "Alerta de consecutivos de factura en almacen <i>" . $a->nombre . "</i>";
                    $correos = array();

                    $roles = $this->db->query("SELECT * FROM " . $databases[$i]->name . ".permiso_rol where id_permiso = 1026 ")->result();
                    foreach ($roles as $r) {
                        $usuarios = $this->db->query('SELECT * FROM (db_config dc JOIN users u ON dc.`id` = u.`db_config_id`) WHERE u.rol_id = ' . $r->id_rol . ' AND dc.`base_dato` LIKE "' . $databases[$i]->name . '"')->result();

                        foreach ($usuarios as $u) {
                            if (trim($u->email) != "") {
                                $almacen = $this->db->query('SELECT * FROM ' . $databases[$i]->name . '.usuario_almacen WHERE usuario_id = ' . $u->id)->row();

                                if (count($almacen) != 0) {
                                    if ($almacen->almacen_id == $a->id || $almacen->almacen_id == 0) {
                                        if (!in_array($u->email, $correos)) {
                                            array_push($correos, $u->email);
                                        }
                                    }
                                }
                            }
                        }

                        $usuarios = $this->db->query('SELECT * FROM (db_config dc JOIN users u ON dc.`id` = u.`db_config_id` AND u.`is_admin` = "t" AND u.`active` = 1) WHERE dc.`base_dato` LIKE "' . $databases[$i]->name . '"')->result();

                        foreach ($usuarios as $u) {
                            if (trim($u->email) != "") {
                                if (!in_array($u->email, $correos)) {
                                    array_push($correos, $u->email);
                                }
                            }
                        }
                    }

                    //enviar correos
                    $configuracion['destinos'] = array();
                    array_push($configuracion['destinos'], ['correos' => $correos, 'mensaje' => $mensaje]);
                    $this->enviarEmail($configuracion);
                }
            }
        }
    }

    public function alerta2dia()
    {
        $arraglo = array(
            "dia" => 1,
            "vista" => "job/correodia2",
            "asunto" => "¿Dejaste pasar 1 día sin usar Vendty? permite que te guie …",
            "desde" => "asesor@vendty.com",
        );
        $this->enviarNuevosClientes($arraglo);
    }

    public function alerta7dia()
    {
        $arraglo = array(
            "dia" => 6,
            "vista" => "job/correodia7",
            "asunto" => "Hoy Acaba tu prueba gratis en Vendty, pero te quiero hacer una propuesta…",
            "desde" => "comunicaciones@vendty.com",
        );
        $this->enviarNuevosClientes($arraglo);
    }

    public function alerta15dia()
    {
        $arraglo = array(
            "dia" => 14,
            "vista" => "job/correodia15",
            "asunto" => "Se te agotará el tiempo muy pronto",
            "desde" => "info@vendty.com",
        );
        $this->enviarNuevosClientes($arraglo);
    }

    public function enviarNuevosClientes($array = false)
    {
        if ($array != false) {
            $hoy = date("Y-m-d");
            $fecha = date("Y-m-d", strtotime("-" . $array['dia'] . " day", strtotime($hoy)));
            $empresas = $this->db->get_where("db_config", array('estado' => 3, 'fecha' => $fecha))->result();

            foreach ($empresas as $e) {
                $nombre = "";
                //consultar productos, si no tiene enviar correo
                $productos = $this->db->query("SELECT COUNT(id) AS cuantos FROM $e->base_dato.producto limit 1")->row();

                if ($productos->cuantos == 0) {
                    $usuarios = $this->db->get_where('users', array('db_config_id' => $e->id, "is_admin" => 't'))->result();
                    $correos = array();

                    foreach ($usuarios as $u) {
                        array_push($correos, $u->email);
                    }

                    $nombre = $u->first_name;
                }

                $this->load->library('email');
                $this->email->initialize();

                $data = array(
                    "name" => $nombre,
                    "day" => $array['dia'],
                );
                $message = $this->load->view('email/alert_day', $data, true);

                $this->email->message($message);
                $this->email->from($array['desde'], 'Vendty');
                $this->email->to("arnulfo@vendty.com");
                $this->email->bcc("desarrollo@vendty.com, arnulfoospino@gmail.com");
                $this->email->subject($array['asunto']);

                if (!$this->email->send()) {
                    echo 'No se pudo enviar el mensaje';
                    var_dump($this->email->print_debugger());
                }
            }
        }
    }

    public function enviarPublicidad()
    {
        $usuarios = $this->db->get('usuarios_promocion')->result();
        $this->load->library('email');
        set_time_limit(50);

        foreach ($usuarios as $u) {
            set_time_limit(50);
            $this->email->initialize();
            $mensaje = $this->load->view('job/navidad', array("nombre" => $u->nombre), true);
            $this->email->message($mensaje);
            $this->email->from("no-responder@vendty.net", 'Vendty POS y Tienda Virtual');
            $this->email->to($u->email);
            $this->email->subject('[Promoción 2x1] El 30 de Diciembre  finaliza la promoción Vendty 2x1. ¡Faltan 3 dias!');

            if (!$this->email->send()) {
                echo 'No se pudo enviar el mensaje';
                var_dump($this->email->print_debugger());
            }

            set_time_limit(50);
        }
    }

    public function email_renovacion_servicio()
    {
        $date = date('Y-m-d');
        $this->load->library('email');
        $this->email->initialize();

        /*obtener bases de datos de vendty*/
        $q_databases = $this->db->query('SELECT nombre_contacto,email,fecha_programada, ADDDATE(fecha_programada,INTERVAL 4 DAY) AS fecha_limite,valor_renovacion FROM vendty2.`crm_registro_renovaciones` WHERE estado = 0');

        foreach ($q_databases->result_array() as $rowReg) {
            $mensaje = $this->load->view('job/vencimiento_vendy', array('usuarios' => $rowReg), true);
            $this->email->from('no-responder@vendty.net', 'Vendty software POS Online');
            $this->email->to($rowReg['email']);
            $this->email->bcc(array('roxanna.vergara@gmail.com', 'soporte@vendty.com', 'asesor@vendty.com'));
            $this->email->subject('Aviso de Vencimiento de Vendty - Sistema POS Cloud');
            $this->email->message($mensaje);

            if (!$this->email->send()) {
                echo 'No se pudo enviar el mensaje';
                var_dump($this->email->print_debugger());
            }
        }
    }

    public function email_7_dias()
    {
        $this->load->library('email');
        $this->email->initialize();

        /*obtener bases de datos de vendty*/
        $q_databases = $this->db->query('SELECT
            u.email,
            u.first_name,
            u.last_name,
            db.fecha,
            db.estado,
            DATEDIFF(CURDATE(),db.fecha)+1 AS diffdate
            FROM vendty2.users u
            INNER JOIN vendty2.db_config db ON u.db_config_id = db.id
            WHERE db.estado > 1 AND db.fecha > DATE_SUB(CURDATE(), INTERVAL 7 DAY)');

        foreach ($q_databases->result_array() as $rowReg) {
            $mensaje = $this->load->view('job/email7dias_' . $rowReg['diffdate'], array('usuarios' => $rowReg), true);
            echo ($mensaje);
            $this->email->from('no-responder@vendty.net', 'Vendty software POS Online');
            $this->email->to('arnulfo@vendty.com');
            $this->email->bcc('desarrollo@vendty.com');
            $this->email->subject('Vendty - Sistema POS Cloud');
        }
    }

    public function alerta_factura()
    {
        //verificamos clientes activos
        $q_databases = $this->db->query('SELECT base_dato,email FROM view_activos where active = 1 and is_admin = "t"');
        $count = 1;
        $configuracion = array();
        $bd = array();
        $correos = "";

        foreach ($q_databases->result() as $base_de_datos) {
            if (isset($bd[$base_de_datos->base_dato])) {
                $correos .= ",'" . $base_de_datos->email . "',";
                $correos = trim($correos, ",");
                $bd[$base_de_datos->base_dato]['email'] = $correos;
            } else {
                $correos = "";
                $correos .= "'" . $base_de_datos->email . "',";
                $correos = trim($correos, ",");
                $bd[$base_de_datos->base_dato]['email'] = $correos;
            }
        }

        foreach ($bd as $base_de_datos => $value) {
            $configuracion = array();
            //verificamos si factura por almacen o General
            $query = $this->db->query("select * from " . $base_de_datos . ".almacen WHERE numero_alerta <>0");
            $almacen = 1;
            $limite = '';

            if ($query->num_rows() > 0) {
                $alma = array();

                foreach ($query->result() as $datos) {
                    //verificamos alerta por numero de factura
                    if (isset($datos->numero_alerta) && isset($datos->consecutivo) && $datos->numero_alerta != 0) {
                        $numero_alerta = ($datos->numero_alerta - ($datos->numero_alerta * 10 / 100));

                        if ($datos->consecutivo > $datos->numero_alerta) {
                            $limite = 'excedido';
                            $tipo = 2;
                            $alma[$almacen] = [
                                'almacen' => $datos->nombre,
                                'consecutivo' => $datos->consecutivo,
                                'numero_alerta' => $datos->numero_alerta,
                                'numero_fin' => $datos->numero_fin,
                            ];
                        } else if ($datos->consecutivo > $numero_alerta) {
                            $limite = 'casi_excedido';
                            $tipo = 2;
                            $alma[$almacen] = [
                                'almacen' => $datos->nombre,
                                'consecutivo' => $datos->consecutivo,
                                'numero_alerta' => $datos->numero_alerta,
                                'numero_fin' => $datos->numero_fin,
                            ];
                        }
                    }

                    $almacen++;
                }

                if (count($alma)) {
                    switch ($limite) {
                        case 'casi_excedido':
                            $configuracion[$tipo][$count] = [
                                'destino' => $value['email'],
                                'asunto' => 'Urgente consecutivo por ajustarse',
                                'titulo' => "El numero de consecutivo ya casi excede el limite configurado",
                                'msg' => "Estimado usuario se ha originado un alerta por consecutivo al 10% o menos del limite configurado",
                                'almacen' => $alma,
                            ];
                            break;
                        case 'excedido':
                            $configuracion[$tipo][$count] = [
                                'destino' => $value['email'],
                                'asunto' => 'Urgente consecutivo por ajustarse',
                                'titulo' => "El numero de consecutivo ha excedido el limite configurado",
                                'msg' => "Estimado usuario se ha originado un alerta por consecutivo excedido al limite configurado",
                                'almacen' => $alma,
                            ];
                            break;
                        default:
                            $configuracion[$tipo][$count] = [
                                'destino' => $value['email'],
                                'asunto' => 'Urgente consecutivo por ajustarse',
                                'titulo' => "El numero de consecutivo ya casi excede el limite configurado",
                                'msg' => "Estimado usuario se ha originado un alerta por consecutivo al 10% o menos del limite configurado",
                                'almacen' => $alma,
                            ];
                    }
                }
            }

            $count++;
            $this->enviarEmailPlantillaxAlmacen($configuracion);
        }
    }

    public function enviarEmailPlantillaxAlmacen($configuracion)
    {
        if (is_array($configuracion) && count($configuracion)) {
            $this->load->library('email');
            $this->email->initialize();

            foreach ($configuracion as $tipo) {
                //por cliente
                foreach ($tipo as $cliente) {
                    //Enviamos por almacen
                    $correo = $cliente['destino'];
                    $asunto = $cliente['asunto'];
                    $titulo = $cliente['titulo'];
                    $msg = $cliente['msg'];
                    $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
                    $this->email->to($correo);
                    $this->email->subject($asunto);
                    $data = array(
                        'title' => $titulo,
                        'message' => $msg,
                        'stores' => $cliente["almacen"],
                    );
                    $message = $this->load->view('email/invoice_alert_consecutive', $data, true);
                    $this->email->message($message);

                    if (!$this->email->send()) {
                        echo 'No se pudo enviar el mensaje';
                        var_dump($this->email->print_debugger());
                    }
                }
            }
        }
    }

    public function emailConfirmarPago($idLicencia = null)
    {
        $to = '';
        $table = '';
        $this->load->library('email');
        $this->email->initialize();
        $asunto = "Tu servicio ha sido renovado ";
        $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
        $this->email->bcc(array('roxanna.vergara@gmail.com', 'soporte@vendty.com', 'asesor@vendty.com'));
        $this->email->subject($asunto);

        for ($x = 0; $x < count($idLicencia); $x++) {
            $id = is_array($idLicencia) ? $idLicencia[$x] : $idLicencia;
            $query = "SELECT L.fecha_inicio_licencia, L.fecha_vencimiento, L.id_almacen, P.nombre_plan, P.valor_plan, ";
            $query .= " D.servidor, D.base_dato, D.usuario, D.clave, U.email, U.username ";
            $query .= " FROM crm_licencias_empresa L JOIN crm_planes P ON L.planes_id=P.id JOIN db_config D ON D.id=L.id_db_config ";
            $query .= " JOIN users U ON U.db_config_id=D.id ";
            $query .= " WHERE L.idlicencias_empresa = $id ORDER BY U.id LIMIT 0,1 ";
            $res = $this->db->query($query);
            $data = $res->result_array();

            $to .= ($to == '') ? $data[0]['email'] : ', ' . $data[0]['email'];
            $username = $data[0]['username'];
            $servidor = $data[0]['servidor'];
            $base_dato = $data[0]['base_dato'];
            $usuario = $data[0]['usuario'];
            $clave = $data[0]['clave'];

            $dns = "mysql://$usuario:$clave@$servidor/$base_dato";
            $this->connection = $this->load->database($dns, true);
            $idAlmacen = $data[0]['id_almacen'];
            $sql = " SELECT * FROM almacen WHERE id = $idAlmacen ";
            $res = $this->connection->query($sql);
            $almacen = $res->result_array();

            $table .= '<tr align="center" valign="middle" style="color:black">';
            $table .= '<td>' . $data[0]['nombre_plan'] . '</td>';
            $table .= '<td style="color:black">' . $data[0]['fecha_inicio_licencia'] . '</td>';
            $table .= '<td style="color:black">' . $data[0]['fecha_vencimiento'] . '</td>';
            $table .= '<td style="color:black">' . $almacen[0]['nombre'] . '</td>';
            $table .= '<td style="color:black">' . $data[0]['valor_plan'] . '</td></tr>';
        }

        $this->email->to($to);

        $data = array(
            'name' => strtoupper($username),
            'table' => $table,
        );
        $message = $this->load->view('email/confirm_payment_license', $data, true);

        $this->email->message($message);

        if (!$this->email->send()) {
            echo '<br>No se pudo enviar el mensaje a:' . $to;
            var_dump($this->email->print_debugger());
        }
    }

    /*********Licencias x vencer 0 dias*******/
    public function email_licenciasxvencer0dias()
    {
        $this->load->library('email');
        $this->email->initialize();
        /*obtener bases de datos de vendty*/
        $query = $this->db->query('
			SELECT
			u.first_name, u.last_name,u.email, u.phone,
			e.nombre_empresa, e.id_db_config, bd.base_dato,
			l.idlicencias_empresa,l.estado_licencia,l.id_almacen,l.fecha_inicio_licencia,l.fecha_vencimiento,l.planes_id,
			p.nombre_plan,p.valor_plan, p.dias_vigencia,
			(SELECT DATEDIFF(l.fecha_vencimiento, CURDATE())) AS dias
			FROM
			crm_empresas_clientes e,
			crm_licencias_empresa l,
			users u,
			crm_planes p,
			db_config bd
				WHERE e.idempresas_clientes = l.idempresas_clientes
				AND e.idusuario_creacion = u.id
				AND bd.id=l.id_db_config
				AND l.planes_id = p.id
				AND l.planes_id >1
				HAVING dias IN (0) AND dias_vigencia IN(360,90,30)
				ORDER BY l.id_db_config,dias');

        $licencias = $query->result_array();
        $users = "";

        if (count($licencias) > 0) {
            foreach ($licencias as $key) {
                $users[$key['email']] = $key['email'];
                $usersnombres[$key['email']] = $key['first_name'] . ' ' . $key['last_name'];
            }

            $asunto = "Tu servicio Vendty será suspendido";

            foreach ($users as $user) {
                $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
                $this->email->subject($asunto);
                $this->email->to($user);
                $mensaje = '
					<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
					<html xmlns="http://www.w3.org/1999/xhtml">

					<head>
					<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
					<title>VENDTY - MODELO DE CORREO ELECTRONICO</title>
					<style type="text/css">
					body {margin: 0;padding: 0;min-width: 100%!important;}
					img {height: auto;}
					.content {width: 100%;max-width: 800px;}
					.header {padding: 20px 0px 0px 0px;background:F3F3F3}
					.innerpadding {padding: 30px 30px 30px 30px;}
					.innerpadding2{padding: 10px;}
					.borderbottom {border-bottom: 1px solid #f2eeed;}
					.content-email{ border: solid 1px lightgray;border-radius: 10px;background:#fff;padding: 20px 10px 20px 10px;}
					.subhead {font-size: 15px;color: #ffffff;font-family: sans-serif;letter-spacing: 10px;}
					.h1, .h2, .bodycopy {color: #153643;font-family: sans-serif;}
					.h1 {font-size: 33px;line-height: 38px;font-weight: bold;}
					.h2 {padding: 0 0 15px 0;font-size: 24px;line-height: 28px;font-weight: bold;}
					.bodycopy {font-size: 16px;line-height: 22px;}
					.button {text-align: center;font-size: 17px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px;}
					.button a {color: #ffffff;text-decoration: none;}
					.footer {background:F3F3F3}
					.footercopy {font-family: sans-serif;font-size: 14px;color: #979798;}
					.footercopy a {color: #979798;text-decoration: underline;}
					.footercopy .title a{color: #45af6d;font-weight: bold;text-decoration:none;}
					.footercopy .tel{color:#696969;font-weight: bold;}
					@media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
					body[yahoo] .hide {display: none!important;}
					body[yahoo] .buttonwrapper {background-color: transparent!important;}
					body[yahoo] .button {padding: 0px!important;}
					body[yahoo] .button a {background-color: #e05443;padding: 15px 15px 13px!important;}
					body[yahoo] .unsubscribe {display: block;margin-top: 20px;padding: 10px 50px;background: #2f3942;border-radius: 5px;text-decoration: none!important;font-weight: bold;}
					}

						.logo{padding:20px 0 20px 20px;}
								h1{padding-left:5px;color:#424242;font-family:Arial,Helvetica,sans-serif;font-size:19px;line-height:20px;padding-bottom:5px;margin:0}

							h3{color:#424242;text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding-bottom:5px;margin:0;margin-bottom:10px;}
								.fecha,h2{color:#9e9e9e;padding-left:5px;font-family:sans-serif;font-size:14px;font-weight:normal;line-height:16px;margin:0 0 10px 0;border-bottom:1px solid #e5e5e5}
								.templateColumns{border: solid 1px lightgray;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}
								.column-table{background-color:#f5f5f5;padding: 10px 0px;}
								.flex{padding: 20px;box-sizing: border-box;border: solid 1px lightgray;-webkit-border-radius: 0px 0px 0px 5px;-moz-border-radius: 0px 0px 0px 5px;border-radius: 0px 0px 5px 5px;border-top:none;}
								.title-span{width:100%; border-bottom:solid 1px #eceff1;color:#333;font-family:Arial,Helvetica,sans-serif;font-size:15px;text-transform:uppercase;padding:5px 10px 8px}
								.description-span{color:#37474f;padding-left:10px;font-family:sans-serif;font-weight:bold;font-size:26px;line-height:22px;margin:0;margin-top:12px;}
								.intro .cuenta{float:right;}
								.boton{text-align:right;}
								@media only screen and (max-width: 480px) {
									.logo{padding: 20px 0 20px 0px;display: inline-block !important;}
									.boton{margin-bottom: 17px;text-align:center;}
									/*.intro tbody{text-align:center;}*/
									.intro tbody tr td{display:block;}
									.templateColumnContainer{display:block !important;width:100% !important;}
									.templateColumnContainer tbody {width: 100%;display: block;}
									.templateColumnContainer tbody tr{ width: 100%;display: inline-table;padding-left: 5%;}
									.templateColumnContainer tbody tr td{ vertical-align: top;}
								}

					/*@media only screen and (min-device-width: 601px) {
						.content {width: 600px !important;}
						.col425 {width: 425px!important;}
						.col380 {width: 380px!important;}
						}*/

					</style>
					</head>

					<body yahoo bgcolor="F3F3F3">
					<table width="100%" bgcolor="F3F3F3" border="0" cellpadding="0" cellspacing="0">
					<tr>
					<td>
						<!--[if (gte mso 9)|(IE)]>
						<table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
							<tr>
							<td>
						<![endif]-->
						<table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td class="header">
							<table width="200" align="left" border="0" cellpadding="0" cellspacing="0">
								<tr>
								<td height="70" style="padding: 0 20px 20px 0;">
									<img class="fix" src="https://vendty.com/wp-content/uploads/2019/05/logo.png" width="100%" border="0" alt="" style="margin-left:2rem;"/>
								</td>
								</tr>
							</table>
							<!--[if (gte mso 9)|(IE)]>
								<table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
								<tr>
									<td>
							<![endif]-->
							<!--<table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%;max-width: 425px;">
								<tr>
								<td height="50">
									<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tr>
										<td class="subhead" style="padding: 0 0 0 3px;">
										CREATING
										</td>
									</tr>
									<tr>
										<td class="h1" style="padding: 5px 0 0 0;">
										Responsive Email Magic
										</td>
									</tr>
									</table>
								</td>
								</tr>
							</table>-->
							<!--[if (gte mso 9)|(IE)]>
									</td>
								</tr>
							</table>
							<![endif]-->
							</td>
						</tr>
						<tr>
							<td class="innerpadding2" style="background:F3F3F3;">
							<table width="100%" border="0" cellspacing="0" cellpadding="0" class="innerpadding2 content-email">
											<tr>
											<td class="h2">
												TU LICENCIA SERÁ SUSPENDIDA
											</td>
											</tr>
											<tr>
											<td class="bodycopy">
												<p>
														Estimado(a) <strong>' . strtoupper($usersnombres[$user]) . '</strong><br>
														Tu servicio Vendty están pronto a vencerse.<br><br>
														<table border="1" cellpadding="0" cellspacing="0"  width="100%" class="licenciatable" style=" margin:auto;border-radius: 3px 3px 0 0;">
														<thead>
															<th align="center">Licencia</th>
															<th align="center">Fecha Inicio</th>
															<th align="center">Fecha Vencimiento</th>
															<th align="center">Almacén</th>
															<th align="center">Valor</th>
															<th align="center">Acción</th>
														</thead>
														<tbody>';

                foreach ($licencias as $key) {
                    if ($user == $key['email']) {
                        $almacen = $this->db->query("SELECT nombre FROM " . $key['base_dato'] . ".almacen where id =" . $key['id_almacen'])->row_array();
                        $mensaje = $mensaje . '
																<tr align="center" valign="middle" style="color:#505050">
																	<td>' . ucfirst(strtoupper($key['nombre_plan'])) . '</td>
																	<td style="color:#505050">' . $key['fecha_inicio_licencia'] . '</td>
																	<td style="color:#505050">' . $key['fecha_vencimiento'] . '</td>
																	<td style="color:#505050">' . ucfirst(strtoupper($almacen['nombre'])) . '</td>
																	<td style="color:#505050">' . $key['valor_plan'] . '</td>
																	<td style="color:#505050;padding: 5px 5px;"><a class="btn btn-success" target=_blank href="http://pos.vendty.com/index.php/frontend/configuracion">Pagar</a></td>
																</tr>';
                    }
                }
                $mensaje = $mensaje . '
														</tbody>
													</table>
													<br>
													Gracias por elegirnos, equipo Vendty.
												</p>
											</td>
											</tr>
											<tr>
												<td colspan=6 class="alert alert-warning" align="justify" valign="top">
													<b style="color:red">NOTA <br></b>
													<i style="font-size:13px ">Nos encantaría que siguiera usando nuestros servicios, pero como en Vendty no hay cláusula de permanencia es su decisión continuar.
													Si no desea renovar le pedimos que descargue su información en archivos de Excel en la opción de Informes del sistema. Recuerde que su información será borrada de nuestros servidores pasados 30 días calendario y no podrá acceder a ella si está suspendido.</i>
												</td>
											</tr>
										</table>
										</td>
									</tr>

									<tr>
										<td class="footer" bgcolor="#fff">
										<table width="100%" border="0" cellspacing="0" cellpadding="0">
											<tr>
											<td align="center" style="padding: 20px 0 0 0;">
												<table border="0" cellspacing="0" cellpadding="0">
												<tr>
													<td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
													<a target="_blank" href="https://twitter.com/vendtyapps">
														<img src="http://pos.vendty.com/uploads/tw.png" width="30" height="30" alt="Twitter" border="0" />
													</a>
													</td>
													<td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
													<a target="_blank" href="https://www.facebook.com/vendtycom">
														<img src="http://pos.vendty.com/uploads/fb.png" width="30" height="30" alt="Facebook" border="0" />
													</a>
													</td>
													<td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
													<a target="_blank" href="https://www.youtube.com/user/VendtyApps">
														<img src="http://pos.vendty.com/uploads/yt.png" width="30" height="30" alt="Youtube" border="0" />
													</a>
													</td>
													<td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
													<a target="_blank" href="https://www.linkedin.com/company/vendty-apps/">
														<img src="http://pos.vendty.com/uploads/in.png" width="30" height="30" alt="Linkedin" border="0" />
													</a>
													</td>
												</tr>
												</table>
											</td>
											</tr>

											<tr>
											<td align="center" class="footercopy">
											<br>
												<span class="title"><a target="_blank" href="https://ayuda.vendty.com/help">Soporte<a> - <a target="_blank" href="https://ayuda.vendty.com/help">Ayuda</a></span><br/><br/>
												<span class="tel">3194751398</span> - <span class="tel">+57(1)636-7799</span><br/>
												Lunes - Viernes 8AM - 6PM<br/>
												Sábado: 8AM - 1PM<br/><br/>
											<br/><br/>
											</td>
										</tr>

										</table>
									</td>
									</tr>
								</table>
								<!--[if (gte mso 9)|(IE)]>
										</td>
									</tr>
								</table>
								<![endif]-->
								</td>
								</tr>
							</table>

				</body>
				</html>';
                $this->email->message($mensaje);

                if (!$this->email->send()) {
                    echo '<br>No se pudo enviar el mensaje a:' . $key;
                    var_dump($this->email->print_debugger());
                }
            }
        }
    }

    /*********Licencias x vencer 1 dias*******/
    public function email_licenciasxvencer1dias()
    {
        $this->load->library('email');
        $this->email->initialize();
        /*obtener bases de datos de vendty*/

        $query = $this->db->query('
        SELECT
        u.first_name, u.last_name,u.email, u.phone,
        e.nombre_empresa, e.id_db_config, bd.base_dato,
        l.idlicencias_empresa,l.estado_licencia,l.id_almacen,l.fecha_inicio_licencia,l.fecha_vencimiento,l.planes_id,
        p.nombre_plan,p.valor_plan, p.dias_vigencia,
        (SELECT DATEDIFF(l.fecha_vencimiento, CURDATE())) AS dias
        FROM
        crm_empresas_clientes e,
        crm_licencias_empresa l,
        users u,
        crm_planes p,
        db_config bd
            WHERE e.idempresas_clientes = l.idempresas_clientes
            AND e.idusuario_creacion = u.id
            AND bd.id=l.id_db_config
            AND l.planes_id = p.id
            AND l.planes_id >1
            HAVING dias IN (1) AND dias_vigencia IN(360,90,30)
            ORDER BY l.id_db_config,dias');

        $licencias = $query->result_array();
        $users = "";
        $destination = array();
        $userParams = array();
        $message = '[licenseExpires3]';
        $sms = 'Vendty te informa que tu licencia vence mañana, cualquier duda comunícate al 3194751398, o escríbenos a nuestro WhatsApp http://bit.ly/2xATBSZ';
        $globalParams = rawurlencode('{"licenseExpires3":"Vendty te informa que tu licencia vence mañana, cualquier duda comunícate al 3194751398, o escríbenos a nuestro WhatsApp http://bit.ly/2xATBSZ"}');

        if (count($licencias) > 0) {
            foreach ($licencias as $key) {
                $users[$key['email']] = $key['email'];
                $usersnombres[$key['email']] = $key['first_name'] . ' ' . $key['last_name'];

                if (!array_search($key['phone'], $destination) && strlen($key['phone']) >= 10) {
                    array_push($destination, $key['phone']);
                    array_push($userParams, '"' . $key['phone'] . '":{"name":"' . $key['first_name'] . '"}');
                }
            }

            if (strlen(implode(",", $destination)) > 0) {
                $this->send_sms(rawurlencode(implode(",", $destination)), rawurlencode($message), $globalParams, rawurlencode("{" . implode(",", $userParams) . "}"));
                $mensaje = 'En la función "email_licenciasxvencer1dias", se enviaron a los números "' . implode(",", $destination) . '", el siguiente mensaje "' . $sms . '", en la siguiente fecha "' . date('Y-m-d H:i:s') . '", Cantidad de mensajes enviados: ' . count($destination);
                $this->send_sms_slack($mensaje);
            }

            $asunto = "Mañana vence tu servicio Vendty";

            foreach ($users as $user) {
                $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
                $this->email->subject($asunto);
                $this->email->to($user);
                $mensaje = '

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <title>VENDTY - MODELO DE CORREO ELECTRONICO</title>
  <style type="text/css">
  body {margin: 0;padding: 0;min-width: 100%!important;}
  img {height: auto;}
  .content {width: 100%;max-width: 800px;}
  .header {padding: 20px 0px 0px 0px;background:F3F3F3}
  .innerpadding {padding: 30px 30px 30px 30px;}
  .innerpadding2{padding: 10px;}
  .borderbottom {border-bottom: 1px solid #f2eeed;}
  .content-email{ border: solid 1px lightgray;border-radius: 10px;background:#fff;padding: 20px 10px 20px 10px;}
  .subhead {font-size: 15px;color: #ffffff;font-family: sans-serif;letter-spacing: 10px;}
  .h1, .h2, .bodycopy {color: #153643;font-family: sans-serif;}
  .h1 {font-size: 33px;line-height: 38px;font-weight: bold;}
  .h2 {padding: 0 0 15px 0;font-size: 24px;line-height: 28px;font-weight: bold;}
  .bodycopy {font-size: 16px;line-height: 22px;}
  .button {text-align: center;font-size: 17px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px;}
  .button a {color: #ffffff;text-decoration: none;}
  .footer {background:F3F3F3}
  .footercopy {font-family: sans-serif;font-size: 14px;color: #979798;}
  .footercopy a {color: #979798;text-decoration: underline;}
  .footercopy .title a{color: #45af6d;font-weight: bold;text-decoration:none;}
  .footercopy .tel{color:#696969;font-weight: bold;}
  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
  body[yahoo] .hide {display: none!important;}
  body[yahoo] .buttonwrapper {background-color: transparent!important;}
  body[yahoo] .button {padding: 0px!important;}
  body[yahoo] .button a {background-color: #e05443;padding: 15px 15px 13px!important;}
  body[yahoo] .unsubscribe {display: block;margin-top: 20px;padding: 10px 50px;background: #2f3942;border-radius: 5px;text-decoration: none!important;font-weight: bold;}



  }

    .logo{padding:20px 0 20px 20px;}
            h1{padding-left:5px;color:#424242;font-family:Arial,Helvetica,sans-serif;font-size:19px;line-height:20px;padding-bottom:5px;margin:0}

           h3{color:#424242;text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding-bottom:5px;margin:0;margin-bottom:10px;}
            .fecha,h2{color:#9e9e9e;padding-left:5px;font-family:sans-serif;font-size:14px;font-weight:normal;line-height:16px;margin:0 0 10px 0;border-bottom:1px solid #e5e5e5}
            .templateColumns{border: solid 1px lightgray;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}
            .column-table{background-color:#f5f5f5;padding: 10px 0px;}
            .flex{padding: 20px;box-sizing: border-box;border: solid 1px lightgray;-webkit-border-radius: 0px 0px 0px 5px;-moz-border-radius: 0px 0px 0px 5px;border-radius: 0px 0px 5px 5px;border-top:none;}
            .title-span{width:100%; border-bottom:solid 1px #eceff1;color:#333;font-family:Arial,Helvetica,sans-serif;font-size:15px;text-transform:uppercase;padding:5px 10px 8px}
            .description-span{color:#37474f;padding-left:10px;font-family:sans-serif;font-weight:bold;font-size:26px;line-height:22px;margin:0;margin-top:12px;}
            .intro .cuenta{float:right;}
            .boton{text-align:right;}
            @media only screen and (max-width: 480px) {
                .logo{padding: 20px 0 20px 0px;display: inline-block !important;}
                .boton{margin-bottom: 17px;text-align:center;}
                /*.intro tbody{text-align:center;}*/
                .intro tbody tr td{display:block;}
                .templateColumnContainer{display:block !important;width:100% !important;}
                .templateColumnContainer tbody {width: 100%;display: block;}
                .templateColumnContainer tbody tr{ width: 100%;display: inline-table;padding-left: 5%;}
                .templateColumnContainer tbody tr td{ vertical-align: top;}
            }

  /*@media only screen and (min-device-width: 601px) {
    .content {width: 600px !important;}
    .col425 {width: 425px!important;}
    .col380 {width: 380px!important;}
    }*/

  </style>
</head>

<body yahoo bgcolor="F3F3F3">
<table width="100%" bgcolor="F3F3F3" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td>
    <!--[if (gte mso 9)|(IE)]>
      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
    <![endif]-->
    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td class="header">
          <table width="200" align="left" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td height="70" style="padding: 0 20px 20px 0;">
                <img class="fix" src="https://vendty.com/wp-content/uploads/2019/05/logo.png" width="100%" border="0" alt="" style="margin-left:2rem;"/>
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]-->
          <!--<table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%;max-width: 425px;">
            <tr>
              <td height="50">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="subhead" style="padding: 0 0 0 3px;">
                      CREATING
                    </td>
                  </tr>
                  <tr>
                    <td class="h1" style="padding: 5px 0 0 0;">
                      Responsive Email Magic
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>-->
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
      <tr>
        <td class="innerpadding2" style="background:F3F3F3;">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="innerpadding2 content-email">
                            <tr>
                              <td class="h2">
                                TU LICENCIA SERÁ SUSPENDIDA
                              </td>
                            </tr>
                            <tr>
                              <td class="bodycopy">
                                <p>
                                        Estimado(a) <strong>' . strtoupper($usersnombres[$user]) . '</strong><br>
                                        Tu servicio Vendty están pronto a vencerse.<br><br>
                                        <table border="1" cellpadding="0" cellspacing="0"  width="100%" class="licenciatable" style=" margin:auto;border-radius: 3px 3px 0 0;">
                                            <thead>
                                                <th align="center">Licencia</th>
                                                <th align="center">Fecha Inicio</th>
                                                <th align="center">Fecha Vencimiento</th>
                                                <th align="center">Almacén</th>
                                                <th align="center">Valor</th>
                                                <th align="center">Acción</th>
                                            </thead>
                                            <tbody>';

                foreach ($licencias as $key) {
                    if ($user == $key['email']) {
                        if ($key['base_dato'] == "vendty2_db_restaurante_vendty") {
                            $almacen = $this->db->query("SELECT nombre FROM " . $key['base_dato'] . ".almacen where id =" . $key['id_almacen'])->row_array();
                            $mensaje = $mensaje . '
                                                        <tr align="center" valign="middle" style="color:#505050">
                                                            <td>' . ucfirst(strtoupper($key['nombre_plan'])) . '</td>
                                                            <td style="color:#505050">' . $key['fecha_inicio_licencia'] . '</td>
                                                            <td style="color:#505050">' . $key['fecha_vencimiento'] . '</td>
                                                            <td style="color:#505050">' . ucfirst(strtoupper($almacen['nombre'])) . '</td>
                                                            <td style="color:#505050">' . $key['valor_plan'] . '</td>
                                                            <td style="color:#505050;padding: 5px 5px;"><a class="btn btn-success" target=_blank href="http://pos.vendty.com/index.php/frontend/configuracion">Pagar</a></td>
                                                        </tr>';
                        }
                    }
                }
                $mensaje = $mensaje . '
                                            </tbody>
                                        </table>
                                    <br>
                                    Gracias por elegirnos, equipo Vendty.
                                </p>
                              </td>
                            </tr>
                            <tr>
                                <td colspan=6 class="alert alert-warning" align="justify" valign="top">
                                    <b style="color:red">NOTA <br></b>
                                    <i style="font-size:13px ">Nos encantaría que siguiera usando nuestros servicios, pero como en Vendty no hay cláusula de permanencia es su decisión continuar.
                                    Si no desea renovar le pedimos que descargue su información en archivos de Excel en la opción de Informes del sistema. Recuerde que su información será borrada de nuestros servidores pasados 30 días calendario y no podrá acceder a ella si está suspendido.</i>
                                </td>
                            </tr>
                          </table>
                        </td>
                      </tr>

                      <tr>
                        <td class="footer" bgcolor="#fff">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                               <tr>
                              <td align="center" style="padding: 20px 0 0 0;">
                                <table border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://twitter.com/vendtyapps">
                                        <img src="http://pos.vendty.com/uploads/tw.png" width="30" height="30" alt="Twitter" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.facebook.com/vendtycom">
                                        <img src="http://pos.vendty.com/uploads/fb.png" width="30" height="30" alt="Facebook" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.youtube.com/user/VendtyApps">
                                        <img src="http://pos.vendty.com/uploads/yt.png" width="30" height="30" alt="Youtube" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.linkedin.com/company/vendty-apps/">
                                        <img src="http://pos.vendty.com/uploads/in.png" width="30" height="30" alt="Linkedin" border="0" />
                                      </a>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>

                            <tr>
                            <td align="center" class="footercopy">
                              <br>
                                <span class="title"><a target="_blank" href="https://ayuda.vendty.com/help">Soporte<a> - <a target="_blank" href="https://ayuda.vendty.com/help">Ayuda</a></span><br/><br/>
                                <span class="tel">3194751398</span> - <span class="tel">+57(1)636-7799</span><br/>
                                Lunes - Viernes 8AM - 6PM<br/>
                                Sábado: 8AM - 1PM<br/><br/>
                              <br/><br/>
                            </td>
                          </tr>

                        </table>
                      </td>
                    </tr>
                  </table>
                  <!--[if (gte mso 9)|(IE)]>
                        </td>
                      </tr>
                  </table>
                  <![endif]-->
                  </td>
                </tr>
              </table>

              </body>
              </html>';
                $this->email->message($mensaje);
                if (!$this->email->send()) {
                    echo '<br>No se pudo enviar el mensaje a:' . $key;
                    var_dump($this->email->print_debugger());
                }
            }
        }
    }

    public function email_ventas_diarias2()
    {
        $count = 0;
        $emails = '';
        $data = array();
        $fecha = date('Y-m-j 01:00:00');
        $fecha_vencimiento = date('Y-m-d');
        $nuevafecha = strtotime('-1 day', strtotime($fecha));
        $fecha_inicial = date('Y-m-j 01:00:00', $nuevafecha);
        $fecha_final = date('Y-m-j 23:59:59', $nuevafecha);
        $fecha_ayer = date('Y-m-j', $nuevafecha);
        $this->load->library('email');
        $this->email->initialize();

        $sql = "SELECT username,email,base_dato,d.servidor AS servidor,d.usuario AS usuario,d.clave AS clave, l.id_almacen
            FROM vendty2.users AS u
            INNER JOIN vendty2.db_config AS d ON u.db_config_id = d.id
            INNER JOIN vendty2.crm_licencias_empresa AS l ON d.id=l.id_db_config
            WHERE (d.estado = 1 || d.estado = 2)
            AND l.fecha_vencimiento>='$fecha_vencimiento'
            AND l.planes_id NOT IN (15,16,17)
            AND u.is_admin='t'
            AND servidor NOT IN ('0.0.0.0','10.0.0.7')
            GROUP BY u.db_config_id,l.id_almacen";
        $result = $this->db->query($sql);
        $databases = $result->result_array();

        foreach ($databases as $database) {
            try {
                $username_admin = $database["username"];
                $user_admin = $database["email"];
                $db = $database["base_dato"];
                $id_almacen = $database["id_almacen"];
                $usuario = $database["usuario"];
                $clave = $database["clave"];
                $servidor = $database["servidor"];
                var_dump($servidor);
                var_dump($db);
                $base_datos = "vendty2";
                $dns = "mysql://$usuario:$clave@$servidor/$base_datos";
                $this->dbConnection = $this->load->database($dns, true);
                $existeBeta = $this->dbConnection->query("SHOW DATABASES WHERE `database` = '" . $db . "'")->result();

                if (count($existeBeta) == 0) {
                    continue;
                } else {
                    $dns = "mysql://$usuario:$clave@$servidor/$db";
                    $this->connection = $this->load->database($dns, true);
                    $this->connection->db_debug = false;
                }

                try {
                    $query = $this->connection->get_where($db . '.opciones', array('nombre_opcion' => 'simbolo'));
                } catch (Exception $e) {
                }

                $simbolo = ($query == null || $query->num_rows() == 0 || empty($query->row()->valor_opcion)) ? "$" : $query->row()->valor_opcion;

                $sql = "SELECT * FROM  $db.almacen WHERE activo = 1 AND bodega = 0 AND id=$id_almacen";

                try {
                    $almacenes = $this->connection->query($sql);
                } catch (Exception $e) {
                }

                $almacenes = $almacenes == null ? [] : $almacenes->result();

                if (count($almacenes) > 0) {
                    foreach ($almacenes as $almacen) {
                        //Realizamos los calculos por almacen
                        //Ventas diarias
                        $sql = "SELECT  SUM((dv.unidades * dv.descuento)) AS total_descuento
                        ,SUM(((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) AS impuesto
                        ,SUM((dv.precio_venta * dv.unidades)) AS total_precio_venta
                        ,SUM(((dv.precio_venta - dv.descuento) * dv.impuesto / 100 * dv.unidades)) + SUM((dv.precio_venta * dv.unidades)) AS total
                        FROM $db.venta v
                        INNER JOIN $db.detalle_venta dv ON v.id=dv.venta_id
                        WHERE v.fecha BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "' AND estado = 0 AND almacen_id =" . $almacen->id;

                        // Devoluciones (NC)
                        $subtotal_devoluciones = 0;
                        ####todos
                        $totaldevoluciones = 0;
                        $total_devoluciones = "SELECT v.id, v.factura, v.fecha, SUM(d.valor) AS total_devolucion
                        FROM $db.devoluciones d
                        INNER JOIN $db.venta v ON d.factura=v.factura
                        WHERE v.fecha BETWEEN '" . $fecha_inicial . "' AND '" . $fecha_final . "' AND estado = '0' AND almacen_id = " . $almacen->id . " GROUP BY v.factura";

                        $total_devoluciones = $this->connection->query($total_devoluciones)->result();
                        $idFactura = "";

                        foreach ($total_devoluciones as $key1 => $value1) {
                            $totaldevoluciones += $value1->total_devolucion;
                            $idFactura .= $value1->id . ",";
                        }

                        $result = $this->connection->query($sql);
                        $result_array = $result->row_array();
                        $ventas_diarias = $result_array["total"] - $result_array["total_descuento"] - $totaldevoluciones;
                        $ventas_diarias = $simbolo . ' ' . $this->formatoMonedaMostrar($db, $ventas_diarias);

                        //Gastos
                        $sql = "SELECT SUM(valor) as total_gastos FROM $db.proformas WHERE fecha = '" . $fecha_ayer . "'  AND id_almacen = " . $almacen->id;
                        $result = $this->connection->query($sql);
                        $total_gastos = $result->row_array();
                        $total_gastos = $simbolo . ' ' . $this->formatoMonedaMostrar($db, $total_gastos["total_gastos"]);

                        //Utilidad
                        $sql = "SELECT  SUM( dv.margen_utilidad) AS total_margen_utilidad
                            FROM $db.venta AS v INNER JOIN $db.detalle_venta AS dv ON v.id = dv.venta_id
                            WHERE DATE(v.fecha) BETWEEN '" . $fecha_ayer . "'  AND  '" . $fecha_ayer . "'  AND  almacen_id =" . $almacen->id . "  AND estado = 0";
                        $result = $this->connection->query($sql);
                        $total_utilidad = $result->row_array();
                        $total_utilidad = $simbolo . ' ' . $this->formatoMonedaMostrar($db, $total_utilidad["total_margen_utilidad"]);

                        //Ventas por formas de pago
                        $sql = "select v.id as id_venta, sum(vp.valor_entregado) - sum(vp.cambio)  as total_venta, count(vp.forma_pago) as cantidad, vp.forma_pago
                            from $db.ventas_pago  AS vp
                            inner join $db.venta AS v on vp.id_venta = v.id
                            where DATE(v.fecha) BETWEEN '" . $fecha_ayer . "'  AND  '" . $fecha_ayer . "'  AND  almacen_id = " . $almacen->id . " AND estado = 0  group by forma_pago  ORDER BY cantidad";
                        $result = $this->connection->query($sql);
                        $total_formas_pago = $result->result_array();

                        //Productos mas vendidos
                        $sql = " SELECT producto.imagen, producto.nombre, SUM(unidades) AS count_productos, SUM(margen_utilidad) AS utilidad, dv.precio_venta
							FROM $db.detalle_venta AS dv
							INNER JOIN $db.venta AS v ON dv.venta_id = v.id
							INNER JOIN $db.producto ON dv.producto_id = producto.id
							where DATE(v.fecha) BETWEEN '" . $fecha_ayer . "'  AND  '" . $fecha_ayer . "' AND  v.almacen_id =" . $almacen->id . "  AND estado = 0
							GROUP BY nombre_producto
							ORDER BY count_productos
							DESC LIMIT 3";
                        $result = $this->connection->query($sql);
                        $productos_mas_vendidos = $result->result_array();
                        $data = array(
                            "fecha" => $fecha_ayer,
                            "user" => $username_admin ? strtoupper($username_admin) : "",
                            "almacen" => $almacen->nombre ? strtoupper($almacen->nombre) : "",
                            "ventas_diarias" => $ventas_diarias,
                            "total_utilidad" => $total_utilidad,
                            "devoluciones" => $simbolo . ' ' . $this->formatoMonedaMostrar($db, $totaldevoluciones),
                            "total_gastos" => $total_gastos,
                            "total_formas_pago" => $total_formas_pago,
                            "productos_mas_vendidos" => $productos_mas_vendidos,
                        );
                        $message = $this->load->view("email/daily_sales", $data, true);

                        $this->email->from('no-responder@vendty.net', 'Vendty - POS y Tienda Virtual - Resumen de ventas del dia: ' . $fecha_ayer . '(' . $almacen->nombre . ')');
                        $this->email->to($user_admin);
                        $this->email->subject('Informe de ventas');
                        $this->email->message($message);
                        $this->email->send();
                        $count++;
                        $emails .= $almacen->nombre . ' - ' . $user_admin . '<br>';
                    }
                }
            } catch (Exception $e) {
                echo 'Excepción capturada: ', $database, ' - ', $e->getMessage(), "\n";
            }
        }

        $this->email->from('no-responder@vendty.net', 'Vendty - POS y Tienda Virtual - Correos de Resumen de ventas del dia: ' . $fecha_ayer);
        $this->email->to('desarrollo@vendty.com');
        $this->email->bcc(array('soporte@vendty.com', 'asesor@vendty.com', 'arnulfo@vendty.com', 'info@vendty.com'));
        $this->email->subject('Informe de ventas');
        $this->email->message('<p><b>Total de correo enviados:</b> ' . $count . ',</p><p><b>Emails:</b><br>' . $emails . '</p>');
        $this->email->send();
    }

    public function BienvenidoaVendty($idbd)
    {
        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
        $this->email->to('no-responder@vendty.net');
        $this->email->bcc(array('arnulfo@vendty.com', 'desarrollo@vendty.com', 'roxanna.vergara@gmail.com', 'soporte@vendty.com', 'asesor@vendty.com'));
        $this->email->subject('Bienvenido a Vendty - Agenda Tú Capacitación');

        $sql = "SELECT username, email, phone FROM users
			WHERE db_config_id=$idbd
			AND is_admin='t' LIMIT 1";
        $sql = $this->db->query($sql)->result();
        $destination = array();
        $userParams = array();
        $message = '[welcome]';
        $globalParams = rawurlencode('{"welcome":"Bienvenido(a) a Vendty, Si necesitas ayuda con tu prueba Gratis puedes agendar una demo en https://app.hubspot.com/meetings/capacitacion/resolucion-dudas o Chatear por WhatsApp http://bit.ly/2RSQeAg"}');

        foreach ($sql as $key => $value) {
            if (!array_search($value->phone, $destination) && strlen($value->phone) >= 10 && $value->phone == '3015262684') {
                array_push($destination, $value->phone);
                array_push($userParams, '"' . $value->phone . '":{"name":"' . $key['first_name'] . '"}');
            }

            $this->email->to($value->email);
            $data = array(
                'name' => $value->username,
            );
            $message = $this->load->view('email/welcome_to_vendty', $data, true);
            $this->email->message($message);

            if (!$this->email->send()) {
                echo '<br>No se pudo enviar el mensaje a:' . $to;
                var_dump($this->email->print_debugger());
            }
        }
    }

    public function actualizar_licencias_vencidas()
    {
        $fecha = date('Y-m-d');
        $fecha = date("Y-m-d", strtotime($fecha . "- 1 days"));
        $sqlupdate = "UPDATE crm_licencias_empresa
        SET estado_licencia=15
        WHERE fecha_vencimiento='$fecha'
        AND estado_licencia !=15";
        $sqlupdate = $this->db->query($sqlupdate);
        $sqlupdate = $this->db->affected_rows($sqlupdate);

        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
        $this->email->to('no-responder@vendty.net');
        $this->email->bcc(array('arnulfo@vendty.com', 'desarrollo@vendty.com', 'roxanna.vergara@gmail.com', 'soporte@vendty.com', 'asesor@vendty.com'));
        $this->email->subject('Regresa a Vendty');

        if ($sqlupdate > 0) {
            $destination = array();
            $userParams = array();
            $message = '[vencidas]';
            $sms = 'No hemos detectado el pago de tu renovación.  ¿Necesitas ayuda? Aquí estamos para ti, escríbenos a nuestro WhatsApp http://bit.ly/2xATBSZ.';
            $globalParams = rawurlencode('{"vencidas":"No hemos detectado el pago de tu renovación.  ¿Necesitas ayuda? Aquí estamos para ti, escríbenos a nuestro WhatsApp http://bit.ly/2xATBSZ"}');
            $sqllicencias = "SELECT vl.*, u.email, u.username, u.phone FROM v_crm_licencias vl
                inner join users u on vl.id_db_config=u.db_config_id
                WHERE vl.fecha_vencimiento='$fecha'
                and vl.id_plan !=1
                and(u.is_admin='t')
                group by vl.id_db_config";
            $licencias = $this->db->query($sqllicencias)->result();

            foreach ($licencias as $key => $value) {
                if (!array_search($value->phone, $destination) && strlen($value->phone) >= 10) {
                    array_push($destination, $value->phone);
                    array_push($userParams, '"' . $value->phone . '":{"name":"' . $key['first_name'] . '"}');
                }

                post_curl('baremetrics/cancel_subscription', json_encode([
                    'license_id' => $value->id_licencia,
                ]));

                $this->email->to($value->email);
                $data = array(
                    "user" => $value->username,
                );
                $message = $this->load->view('email/update_expired_licenses', $data, true);
                $this->email->message($message);

                if (!$this->email->send()) {
                    echo '<br>No se pudo enviar el mensaje a:' . $value->email;
                    var_dump($this->email->print_debugger());
                }
            }

            //Se coloca al final del ciclo para que soloo se envien la peticion a la API una sola vez
            if (strlen(implode(",", $destination)) > 0) {
                $this->send_sms(rawurlencode(implode(",", $destination)), rawurlencode($message), $globalParams, rawurlencode("{" . implode(",", $userParams) . "}"));
                $mensaje = 'En la función "actualizar_licencias_vencidas", se enviaron a los números "' . implode(",", $destination) . '", el siguiente mensaje "' . $sms . '", en la siguiente fecha "' . date('Y-m-d H:i:s') . '", Cantidad de mensajes enviados: ' . count($destination);
                $this->send_sms_slack($mensaje);
            }
        }
    }

    public function actualizar_licencias_vencidas_mes()
    {
        $hoy = date('Y-m-d');
        $fecha = date("Y-m-d", strtotime($hoy . "- 1 months"));
        $sqlupdate = "UPDATE crm_licencias_empresa
        SET estado_licencia=15
        WHERE fecha_vencimiento BETWEEN '$fecha' AND '$hoy'
        AND estado_licencia !=15";
        $sqlupdate = $this->db->query($sqlupdate);
        $sqlupdate = $this->db->affected_rows($sqlupdate);

        $this->load->library('email');
        $this->email->initialize();
        $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
        $this->email->to('no-responder@vendty.net');
        $this->email->bcc(array('arnulfo@vendty.com', 'desarrollo@vendty.com', 'roxanna.vergara@gmail.com', 'soporte@vendty.com', 'asesor@vendty.com'));
        $this->email->subject('Regresa a Vendty');

        if ($sqlupdate > 0) {
            $destination = array();
            $userParams = array();
            $message = '[vencidas]';
            $sms = 'No hemos detectado el pago de tu renovación.  ¿Necesitas ayuda? Aquí estamos para ti, escríbenos a nuestro WhatsApp http://bit.ly/2xATBSZ.';
            $globalParams = rawurlencode('{"vencidas":"No hemos detectado el pago de tu renovación.  ¿Necesitas ayuda? Aquí estamos para ti, escríbenos a nuestro WhatsApp http://bit.ly/2xATBSZ"}');
            $sqllicencias = "SELECT vl.*, u.email, u.username, u.phone FROM v_crm_licencias vl
          inner join users u on vl.id_db_config=u.db_config_id
          WHERE vl.fecha_vencimiento BETWEEN '$fecha' AND '$hoy'
          and vl.id_plan !=1
          and(u.is_admin='t')
          group by vl.id_db_config";
            $licencias = $this->db->query($sqllicencias)->result();

            foreach ($licencias as $key => $value) {
                if (!array_search($value->phone, $destination) && strlen($value->phone) >= 10) {
                    array_push($destination, $value->phone);
                    array_push($userParams, '"' . $value->phone . '":{"name":"' . $key['first_name'] . '"}');
                }

                $this->email->to($value->email);
                $data = array(
                    "user" => $value->username,
                );
                $message = $this->load->view('email/update_expired_licenses', $data, true);
                $this->email->message($message);

                if (!$this->email->send()) {
                    echo '<br>No se pudo enviar el mensaje a:' . $value->email;
                    var_dump($this->email->print_debugger());
                }
            }

            //Se coloca al final del ciclo para que soloo se envien la peticion a la API una sola vez
            if (strlen(implode(",", $destination)) > 0) {
                $this->send_sms(rawurlencode(implode(",", $destination)), rawurlencode($message), $globalParams, rawurlencode("{" . implode(",", $userParams) . "}"));
                $mensaje = 'En la función "actualizar_licencias_vencidas", se enviaron a los números "' . implode(",", $destination) . '", el siguiente mensaje "' . $sms . '", en la siguiente fecha "' . date('Y-m-d H:i:s') . '", Cantidad de mensajes enviados: ' . count($destination);
                $this->send_sms_slack($mensaje);
            }
        }

        return $sqlupdate;
    }

    public function formatoMonedaMostrar($db, $numero)
    {
        $dataDecimales = $this->getDataMoneda($db);
        $numero = number_format($numero, $dataDecimales->decimales, $dataDecimales->tipo_separador_decimales, $dataDecimales->tipo_separador_miles);

        return $numero;
    }

    public function getDataMoneda($db)
    {
        $sql = "
           select
              (select if (o.valor_opcion is null or o.valor_opcion ='', '0', o.valor_opcion ) from $db.opciones o where nombre_opcion='decimales_moneda' limit 1)  decimales,
              (select if (o.valor_opcion is null or o.valor_opcion ='', 'COP', o.valor_opcion ) from $db.opciones o where nombre_opcion='tipo_moneda' limit 1)  tipo_moneda,
              (select if (o.valor_opcion is null or o.valor_opcion ='', ',', o.valor_opcion ) from $db.opciones o where nombre_opcion='tipo_separador_miles' limit 1)  tipo_separador_miles,
              (select if (o.valor_opcion is null or o.valor_opcion ='', '.', o.valor_opcion )from $db.opciones o where nombre_opcion='tipo_separador_decimales' limit 1)  tipo_separador_decimales,
              (select if (o.valor_opcion is null or o.valor_opcion ='', '$', o.valor_opcion )from $db.opciones o where nombre_opcion='simbolo' limit 1)  simbolo,
              (select if (o.valor_opcion is null or o.valor_opcion ='', '0', o.valor_opcion )from $db.opciones o where nombre_opcion='redondear_precios' limit 1)  redondear
             from $db.opciones
            limit 1";
        $rest = $this->connection->query($sql)->row();
        $rest1 = $this->connection->query($sql)->row_array();

        if ($rest1['decimales'] == null) {
            $sql1 = "INSERT INTO $db.opciones (`nombre_opcion`,`valor_opcion`)VALUES ('decimales_moneda','0')";
            $this->connection->query($sql1);
            $rest = $this->connection->query($sql)->row();
        }

        if ($rest1['tipo_moneda'] == null) {
            $sql1 = "INSERT INTO $db.opciones (`nombre_opcion`,`valor_opcion`)VALUES ('tipo_moneda','COP')";
            $this->connection->query($sql1);
            $rest = $this->connection->query($sql)->row();
        }

        if ($rest1['tipo_separador_miles'] == null) {
            $sql1 = "INSERT INTO $db.opciones (`nombre_opcion`,`valor_opcion`)VALUES ('tipo_separador_miles',' ')";
            $this->connection->query($sql1);
            $rest = $this->connection->query($sql)->row();
        }

        if ($rest1['tipo_separador_decimales'] == null) {
            $sql1 = "INSERT INTO $db.opciones (`nombre_opcion`,`valor_opcion`)VALUES ('tipo_separador_decimales','.')";
            $this->connection->query($sql1);
            $rest = $this->connection->query($sql)->row();
        }

        if ($rest1['simbolo'] == null) {
            $sql1 = "INSERT INTO $db.opciones (`nombre_opcion`,`valor_opcion`)VALUES ('simbolo','$')";
            $this->connection->query($sql1);
            $rest = $this->connection->query($sql)->row();
        }

        if ($rest1['redondear'] == null) {
            $sql1 = "INSERT INTO $db.opciones (`nombre_opcion`,`valor_opcion`)VALUES ('redondear_precios','0')";
            $this->connection->query($sql1);
            $rest = $this->connection->query($sql)->row();
        }

        return $rest;
    }

    /*********Licencias x vencer 3 dias*******/
    public function email_licenciasxvencer3dias()
    {
        $this->load->library('email');
        $this->email->initialize();
        /*obtener bases de datos de vendty*/
        $query = $this->db->query('
			SELECT
			u.first_name, u.last_name,u.email, u.phone,
			e.nombre_empresa, e.id_db_config, bd.base_dato,
			l.idlicencias_empresa,l.estado_licencia,l.id_almacen,l.fecha_inicio_licencia,l.fecha_vencimiento,l.planes_id,
			p.nombre_plan,p.valor_plan, p.dias_vigencia,
			(SELECT DATEDIFF(l.fecha_vencimiento, CURDATE())) AS dias
			FROM
			crm_empresas_clientes e,
			crm_licencias_empresa l,
			users u,
			crm_planes p,
			db_config bd
				WHERE e.idempresas_clientes = l.idempresas_clientes
				AND e.idusuario_creacion = u.id
				AND bd.id=l.id_db_config
				AND l.planes_id = p.id
				AND l.planes_id >1
				HAVING dias IN (3) AND dias_vigencia IN(360,90,30)
				ORDER BY l.id_db_config,dias');
        $licencias = $query->result_array();
        $users = "";
        $destination = array();
        $userParams = array();
        $message = '[licenseExpires3]';
        $sms = 'Vendty te informa que tu licencia vence en tres (3) días, cualquier duda comunícate al 3194751398, o escríbenos a nuestro WhatsApp http://bit.ly/2xATBSZ';
        $globalParams = rawurlencode('{"licenseExpires3":"Vendty te informa que tu licencia vence en tres (3) días, cualquier duda comunícate al 3194751398, o escríbenos a nuestro WhatsApp http://bit.ly/2xATBSZ"}');

        if (count($licencias) > 0) {
            foreach ($licencias as $key) {
                $users[$key['email']] = $key['email'];
                $usersnombres[$key['email']] = $key['first_name'] . ' ' . $key['last_name'];

                if (!array_search($key['phone'], $destination) && strlen($key['phone']) >= 10) {
                    array_push($destination, $key['phone']);
                    array_push($userParams, '"' . $key['phone'] . '":{"name":"' . $key['first_name'] . '"}');
                }
            }

            if (strlen(implode(",", $destination)) > 0) {
                $this->send_sms(rawurlencode(implode(",", $destination)), rawurlencode($message), $globalParams, rawurlencode("{" . implode(",", $userParams) . "}"));
                $mensaje = 'En la función "email_licenciasxvencer3dias",se enviaron a los números "' . implode(",", $destination) . '", el siguiente mensaje "' . $sms . '", en la siguiente fecha "' . date('Y-m-d H:i:s') . '", Cantidad de mensajes enviados: ' . count($destination);
                $this->send_sms_slack($mensaje);
            }

            $asunto = "URGENTE tú servicio Vendty vencerá en 3 días";

            foreach ($users as $user) {
                $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
                $this->email->subject($asunto);
                $this->email->to($user);
                $mensaje = '
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <title>VENDTY - MODELO DE CORREO ELECTRONICO</title>
  <style type="text/css">
  body {margin: 0;padding: 0;min-width: 100%!important;}
  img {height: auto;}
  .content {width: 100%;max-width: 800px;}
  .header {padding: 20px 0px 0px 0px;background:F3F3F3}
  .innerpadding {padding: 30px 30px 30px 30px;}
  .innerpadding2{padding: 10px;}
  .borderbottom {border-bottom: 1px solid #f2eeed;}
  .content-email{ border: solid 1px lightgray;border-radius: 10px;background:#fff;padding: 20px 10px 20px 10px;}
  .subhead {font-size: 15px;color: #ffffff;font-family: sans-serif;letter-spacing: 10px;}
  .h1, .h2, .bodycopy {color: #153643;font-family: sans-serif;}
  .h1 {font-size: 33px;line-height: 38px;font-weight: bold;}
  .h2 {padding: 0 0 15px 0;font-size: 24px;line-height: 28px;font-weight: bold;}
  .bodycopy {font-size: 16px;line-height: 22px;}
  .button {text-align: center;font-size: 17px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px;}
  .button a {color: #ffffff;text-decoration: none;}
  .footer {background:F3F3F3}
  .footercopy {font-family: sans-serif;font-size: 14px;color: #979798;}
  .footercopy a {color: #979798;text-decoration: underline;}
  .footercopy .title a{color: #45af6d;font-weight: bold;text-decoration:none;}
  .footercopy .tel{color:#696969;font-weight: bold;}
  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
  body[yahoo] .hide {display: none!important;}
  body[yahoo] .buttonwrapper {background-color: transparent!important;}
  body[yahoo] .button {padding: 0px!important;}
  body[yahoo] .button a {background-color: #e05443;padding: 15px 15px 13px!important;}
  body[yahoo] .unsubscribe {display: block;margin-top: 20px;padding: 10px 50px;background: #2f3942;border-radius: 5px;text-decoration: none!important;font-weight: bold;}
  }

    .logo{padding:20px 0 20px 20px;}
            h1{padding-left:5px;color:#424242;font-family:Arial,Helvetica,sans-serif;font-size:19px;line-height:20px;padding-bottom:5px;margin:0}

           h3{color:#424242;text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding-bottom:5px;margin:0;margin-bottom:10px;}
            .fecha,h2{color:#9e9e9e;padding-left:5px;font-family:sans-serif;font-size:14px;font-weight:normal;line-height:16px;margin:0 0 10px 0;border-bottom:1px solid #e5e5e5}
            .templateColumns{border: solid 1px lightgray;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}
            .column-table{background-color:#f5f5f5;padding: 10px 0px;}
            .flex{padding: 20px;box-sizing: border-box;border: solid 1px lightgray;-webkit-border-radius: 0px 0px 0px 5px;-moz-border-radius: 0px 0px 0px 5px;border-radius: 0px 0px 5px 5px;border-top:none;}
            .title-span{width:100%; border-bottom:solid 1px #eceff1;color:#333;font-family:Arial,Helvetica,sans-serif;font-size:15px;text-transform:uppercase;padding:5px 10px 8px}
            .description-span{color:#37474f;padding-left:10px;font-family:sans-serif;font-weight:bold;font-size:26px;line-height:22px;margin:0;margin-top:12px;}
            .intro .cuenta{float:right;}
            .boton{text-align:right;}
            @media only screen and (max-width: 480px) {
                .logo{padding: 20px 0 20px 0px;display: inline-block !important;}
                .boton{margin-bottom: 17px;text-align:center;}
                /*.intro tbody{text-align:center;}*/
                .intro tbody tr td{display:block;}
                .templateColumnContainer{display:block !important;width:100% !important;}
                .templateColumnContainer tbody {width: 100%;display: block;}
                .templateColumnContainer tbody tr{ width: 100%;display: inline-table;padding-left: 5%;}
                .templateColumnContainer tbody tr td{ vertical-align: top;}
            }

  /*@media only screen and (min-device-width: 601px) {
    .content {width: 600px !important;}
    .col425 {width: 425px!important;}
    .col380 {width: 380px!important;}
    }*/

  </style>
</head>

<body yahoo bgcolor="F3F3F3">
<table width="100%" bgcolor="F3F3F3" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td>
    <!--[if (gte mso 9)|(IE)]>
      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
    <![endif]-->
    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td class="header">
          <table width="200" align="left" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td height="70" style="padding: 0 20px 20px 0;">
                <img class="fix" src="https://vendty.com/wp-content/uploads/2019/05/logo.png" width="100%" border="0" alt="" style="margin-left:2rem;"/>
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]-->
          <!--<table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%;max-width: 425px;">
            <tr>
              <td height="50">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="subhead" style="padding: 0 0 0 3px;">
                      CREATING
                    </td>
                  </tr>
                  <tr>
                    <td class="h1" style="padding: 5px 0 0 0;">
                      Responsive Email Magic
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>-->
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
      <tr>
        <td class="innerpadding2" style="background:F3F3F3;">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="innerpadding2 content-email">
                        <tr>
                              <td class="h2">
                                TU LICENCIA SERÁ SUSPENDIDA
                              </td>
                            </tr>

                            <tr>
                              <td class="bodycopy">
                                <p>
                                        Estimado(a) <strong>' . strtoupper($usersnombres[$user]) . '</strong><br>
                                        Tu servicio Vendty están pronto a vencerse.<br><br>
                                        <table border="1" cellpadding="0" cellspacing="0"  width="100%" class="licenciatable" style=" margin:auto;border-radius: 3px 3px 0 0;">
                                            <thead>
                                                <th align="center">Licencia</th>
                                                <th align="center">Fecha Inicio</th>
                                                <th align="center">Fecha Vencimiento</th>
                                                <th align="center">Almacén</th>
                                                <th align="center">Valor</th>
                                                <th align="center">Acción</th>
                                            </thead>
                                            <tbody>';
                foreach ($licencias as $key) {
                    if ($user == $key['email']) {
                        $almacen = $this->db->query("SELECT nombre FROM " . $key['base_dato'] . ".almacen where id =" . $key['id_almacen'])->row_array();
                        $mensaje = $mensaje . '
                                                <tr align="center" valign="middle" style="color:#505050">
                                                    <td>' . ucfirst(strtoupper($key['nombre_plan'])) . '</td>
                                                    <td style="color:#505050">' . $key['fecha_inicio_licencia'] . '</td>
                                                    <td style="color:#505050">' . $key['fecha_vencimiento'] . '</td>
                                                    <td style="color:#505050">' . ucfirst(strtoupper($almacen['nombre'])) . '</td>
                                                    <td style="color:#505050">' . $key['valor_plan'] . '</td>
                                                    <td style="color:#505050;padding: 5px 5px;"><a class="btn btn-success" target=_blank href="http://pos.vendty.com/index.php/frontend/configuracion">Pagar</a></td>
                                                </tr>';
                    }
                }
                $mensaje = $mensaje . '
                                        </tbody>
                                        </table>
                                    <br>
                                    Gracias por elegirnos, equipo Vendty.
                                </p>
                              </td>
                            </tr>
                            <tr>
                                <td colspan=6 class="alert alert-warning" align="justify" valign="top">
                                    <b style="color:red">NOTA <br></b>
                                    <i style="font-size:13px ">Nos encantaría que siguiera usando nuestros servicios, pero como en Vendty no hay cláusula de permanencia es su decisión continuar.
                                    Si no desea renovar le pedimos que descargue su información en archivos de Excel en la opción de Informes del sistema. Recuerde que su información será borrada de nuestros servidores pasados 30 días calendario y no podrá acceder a ella si está suspendido.</i>
                                </td>
                            </tr>
                          </table>
                        </td>
                      </tr>

                      <tr>
                        <td class="footer" bgcolor="#fff">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                               <tr>
                              <td align="center" style="padding: 20px 0 0 0;">
                                <table border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://twitter.com/vendtyapps">
                                        <img src="http://pos.vendty.com/uploads/tw.png" width="30" height="30" alt="Twitter" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.facebook.com/vendtycom">
                                        <img src="http://pos.vendty.com/uploads/fb.png" width="30" height="30" alt="Facebook" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.youtube.com/user/VendtyApps">
                                        <img src="http://pos.vendty.com/uploads/yt.png" width="30" height="30" alt="Youtube" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.linkedin.com/company/vendty-apps/">
                                        <img src="http://pos.vendty.com/uploads/in.png" width="30" height="30" alt="Linkedin" border="0" />
                                      </a>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>

                            <tr>
                            <td align="center" class="footercopy">
                              <br>
                                <span class="title"><a target="_blank" href="https://ayuda.vendty.com/help">Soporte<a> - <a target="_blank" href="https://ayuda.vendty.com/help">Ayuda</a></span><br/><br/>
                                <span class="tel">3194751398</span> - <span class="tel">+57(1)636-7799</span><br/>
                                Lunes - Viernes 8AM - 6PM<br/>
                                Sábado: 8AM - 1PM<br/><br/>
                              <br/><br/>
                            </td>
                          </tr>

                        </table>
                      </td>
                    </tr>
                  </table>
                  <!--[if (gte mso 9)|(IE)]>
                        </td>
                      </tr>
                  </table>
                  <![endif]-->
                  </td>
                </tr>
              </table>

              </body>
              </html>';
                $this->email->message($mensaje);
                if (!$this->email->send()) {
                    echo '<br>No se pudo enviar el mensaje a:' . $key;
                    var_dump($this->email->print_debugger());
                }
            }
        }
    }

    /*********Licencias x vencer 7 dias*******/
    public function email_licenciasxvencer7dias()
    {
        $this->load->library('email');
        $this->email->initialize();
        /*obtener bases de datos de vendty*/
        $query = $this->db->query('
        SELECT
        u.first_name, u.last_name,u.email,
        e.nombre_empresa, e.id_db_config, bd.base_dato,
        l.idlicencias_empresa,l.estado_licencia,l.id_almacen,l.fecha_inicio_licencia,l.fecha_vencimiento,l.planes_id,
        p.nombre_plan,p.valor_plan, p.dias_vigencia,
        (SELECT DATEDIFF(l.fecha_vencimiento, CURDATE())) AS dias
        FROM
        crm_empresas_clientes e,
        crm_licencias_empresa l,
        users u,
        crm_planes p,
        db_config bd
            WHERE e.idempresas_clientes = l.idempresas_clientes
            AND e.idusuario_creacion = u.id
            AND bd.id=l.id_db_config
            AND l.planes_id = p.id
            AND l.planes_id >1
            HAVING dias IN (7) AND dias_vigencia IN(360,90,30)
            ORDER BY l.id_db_config,dias');

        $licencias = $query->result_array();
        $users = "";

        if (count($licencias) > 0) {
            foreach ($licencias as $key) {
                $users[$key['email']] = $key['email'];
                $usersnombres[$key['email']] = $key['first_name'] . ' ' . $key['last_name'];
            }

            $asunto = "Tu servicio Vendty está próximo a vencer en 7 días";

            foreach ($users as $user) {
                $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
                $this->email->subject($asunto);
                $this->email->to($user);
                $mensaje = '
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <title>VENDTY - MODELO DE CORREO ELECTRONICO</title>
  <style type="text/css">
  body {margin: 0;padding: 0;min-width: 100%!important;}
  img {height: auto;}
  .content {width: 100%;max-width: 800px;}
  .header {padding: 20px 0px 0px 0px;background:F3F3F3}
  .innerpadding {padding: 30px 30px 30px 30px;}
  .innerpadding2{padding: 10px;}
  .borderbottom {border-bottom: 1px solid #f2eeed;}
  .content-email{ border: solid 1px lightgray;border-radius: 10px;background:#fff;padding: 20px 10px 20px 10px;}
  .subhead {font-size: 15px;color: #ffffff;font-family: sans-serif;letter-spacing: 10px;}
  .h1, .h2, .bodycopy {color: #153643;font-family: sans-serif;}
  .h1 {font-size: 33px;line-height: 38px;font-weight: bold;}
  .h2 {padding: 0 0 15px 0;font-size: 24px;line-height: 28px;font-weight: bold;}
  .bodycopy {font-size: 16px;line-height: 22px;}
  .button {text-align: center;font-size: 17px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px;}
  .button a {color: #ffffff;text-decoration: none;}
  .footer {background:F3F3F3}
  .footercopy {font-family: sans-serif;font-size: 14px;color: #979798;}
  .footercopy a {color: #979798;text-decoration: underline;}
  .footercopy .title a{color: #45af6d;font-weight: bold;text-decoration:none;}
  .footercopy .tel{color:#696969;font-weight: bold;}
  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
  body[yahoo] .hide {display: none!important;}
  body[yahoo] .buttonwrapper {background-color: transparent!important;}
  body[yahoo] .button {padding: 0px!important;}
  body[yahoo] .button a {background-color: #e05443;padding: 15px 15px 13px!important;}
  body[yahoo] .unsubscribe {display: block;margin-top: 20px;padding: 10px 50px;background: #2f3942;border-radius: 5px;text-decoration: none!important;font-weight: bold;}
  }

    .logo{padding:20px 0 20px 20px;}
            h1{padding-left:5px;color:#424242;font-family:Arial,Helvetica,sans-serif;font-size:19px;line-height:20px;padding-bottom:5px;margin:0}

           h3{color:#424242;text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding-bottom:5px;margin:0;margin-bottom:10px;}
            .fecha,h2{color:#9e9e9e;padding-left:5px;font-family:sans-serif;font-size:14px;font-weight:normal;line-height:16px;margin:0 0 10px 0;border-bottom:1px solid #e5e5e5}
            .templateColumns{border: solid 1px lightgray;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}
            .column-table{background-color:#f5f5f5;padding: 10px 0px;}
            .flex{padding: 20px;box-sizing: border-box;border: solid 1px lightgray;-webkit-border-radius: 0px 0px 0px 5px;-moz-border-radius: 0px 0px 0px 5px;border-radius: 0px 0px 5px 5px;border-top:none;}
            .title-span{width:100%; border-bottom:solid 1px #eceff1;color:#333;font-family:Arial,Helvetica,sans-serif;font-size:15px;text-transform:uppercase;padding:5px 10px 8px}
            .description-span{color:#37474f;padding-left:10px;font-family:sans-serif;font-weight:bold;font-size:26px;line-height:22px;margin:0;margin-top:12px;}
            .intro .cuenta{float:right;}
            .boton{text-align:right;}
            @media only screen and (max-width: 480px) {
                .logo{padding: 20px 0 20px 0px;display: inline-block !important;}
                .boton{margin-bottom: 17px;text-align:center;}
                /*.intro tbody{text-align:center;}*/
                .intro tbody tr td{display:block;}
                .templateColumnContainer{display:block !important;width:100% !important;}
                .templateColumnContainer tbody {width: 100%;display: block;}
                .templateColumnContainer tbody tr{ width: 100%;display: inline-table;padding-left: 5%;}
                .templateColumnContainer tbody tr td{ vertical-align: top;}
            }

  /*@media only screen and (min-device-width: 601px) {
    .content {width: 600px !important;}
    .col425 {width: 425px!important;}
    .col380 {width: 380px!important;}
    }*/

  </style>
</head>

<body yahoo bgcolor="F3F3F3">
<table width="100%" bgcolor="F3F3F3" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td>
    <!--[if (gte mso 9)|(IE)]>
      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
    <![endif]-->
    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td class="header">
          <table width="200" align="left" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td height="70" style="padding: 0 20px 20px 0;">
                <img class="fix" src="https://vendty.com/wp-content/uploads/2019/05/logo.png" width="100%" border="0" alt="" style="margin-left:2rem;"/>
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]-->
          <!--<table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%;max-width: 425px;">
            <tr>
              <td height="50">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="subhead" style="padding: 0 0 0 3px;">
                      CREATING
                    </td>
                  </tr>
                  <tr>
                    <td class="h1" style="padding: 5px 0 0 0;">
                      Responsive Email Magic
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>-->
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
      <tr>
        <td class="innerpadding2" style="background:F3F3F3;">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="innerpadding2 content-email">
                        <tr>
                              <td class="h2">
                                TU LICENCIA SERÁ SUSPENDIDA
                              </td>
                            </tr>

                            <tr>
                              <td class="bodycopy">
                                <p>
                                        Estimado(a) <strong>' . strtoupper($usersnombres[$user]) . '</strong><br>
                                        Tu servicio Vendty están pronto a vencerse.<br><br>
                                        <table border="1" cellpadding="0" cellspacing="0"  width="100%" class="licenciatable" style=" margin:auto;border-radius: 3px 3px 0 0;">
                                            <thead>
                                                <th align="center">Licencia</th>
                                                <th align="center">Fecha Inicio</th>
                                                <th align="center">Fecha Vencimiento</th>
                                                <th align="center">Almacén</th>
                                                <th align="center">Valor</th>
                                                <th align="center">Acción</th>
                                            </thead>
                                            <tbody>';

                foreach ($licencias as $key) {
                    if ($user == $key['email']) {
                        $almacen = $this->db->query("SELECT nombre FROM " . $key['base_dato'] . ".almacen where id =" . $key['id_almacen'])->row_array();
                        $mensaje = $mensaje . '
                                                <tr align="center" valign="middle" style="color:#505050">
                                                    <td>' . ucfirst(strtoupper($key['nombre_plan'])) . '</td>
                                                    <td style="color:#505050">' . $key['fecha_inicio_licencia'] . '</td>
                                                    <td style="color:#505050">' . $key['fecha_vencimiento'] . '</td>
                                                    <td style="color:#505050">' . ucfirst(strtoupper($almacen['nombre'])) . '</td>
                                                    <td style="color:#505050">' . $key['valor_plan'] . '</td>
                                                    <td style="color:#505050;padding: 5px 5px;"><a class="btn btn-success" target=_blank href="http://pos.vendty.com/index.php/frontend/configuracion">Pagar</a></td>
                                                </tr>';
                    }
                }
                $mensaje = $mensaje . '
                                        </tbody>
                                        </table>
                                    <br>
                                    Gracias por elegirnos, equipo Vendty.
                                </p>
                              </td>
                            </tr>
                            <tr>
                                <td colspan=6 class="alert alert-warning" align="justify" valign="top">
                                    <b style="color:red">NOTA <br></b>
                                    <i style="font-size:13px ">Nos encantaría que siguiera usando nuestros servicios, pero como en Vendty no hay cláusula de permanencia es su decisión continuar.
                                    Si no desea renovar le pedimos que descargue su información en archivos de Excel en la opción de Informes del sistema. Recuerde que su información será borrada de nuestros servidores pasados 30 días calendario y no podrá acceder a ella si está suspendido.</i>
                                </td>
                            </tr>
                          </table>
                        </td>
                      </tr>

                      <tr>
                        <td class="footer" bgcolor="#fff">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                               <tr>
                              <td align="center" style="padding: 20px 0 0 0;">
                                <table border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://twitter.com/vendtyapps">
                                        <img src="http://pos.vendty.com/uploads/tw.png" width="30" height="30" alt="Twitter" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.facebook.com/vendtycom">
                                        <img src="http://pos.vendty.com/uploads/fb.png" width="30" height="30" alt="Facebook" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.youtube.com/user/VendtyApps">
                                        <img src="http://pos.vendty.com/uploads/yt.png" width="30" height="30" alt="Youtube" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.linkedin.com/company/vendty-apps/">
                                        <img src="http://pos.vendty.com/uploads/in.png" width="30" height="30" alt="Linkedin" border="0" />
                                      </a>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>

                            <tr>
                            <td align="center" class="footercopy">
                              <br>
                                <span class="title"><a target="_blank" href="https://ayuda.vendty.com/help">Soporte<a> - <a target="_blank" href="https://ayuda.vendty.com/help">Ayuda</a></span><br/><br/>
                                <span class="tel">3194751398</span> - <span class="tel">+57(1)636-7799</span><br/>
                                Lunes - Viernes 8AM - 6PM<br/>
                                Sábado: 8AM - 1PM<br/><br/>
                              <br/><br/>
                            </td>
                          </tr>

                        </table>
                      </td>
                    </tr>
                  </table>
                  <!--[if (gte mso 9)|(IE)]>
                        </td>
                      </tr>
                  </table>
                  <![endif]-->
                  </td>
                </tr>
              </table>

              </body>
              </html>';
                $this->email->message($mensaje);
                if (!$this->email->send()) {
                    echo '<br>No se pudo enviar el mensaje a:' . $key;
                    var_dump($this->email->print_debugger());
                }
            }
        }
    }

    /*********Licencias x vencer 15 dias*******/
    public function email_licenciasxvencer15dias()
    {
        $this->load->library('email');
        $this->email->initialize();
        /*obtener bases de datos de vendty*/
        $query = $this->db->query('
        SELECT
        u.first_name, u.last_name,u.email,
        e.nombre_empresa, e.id_db_config, bd.base_dato,
        l.idlicencias_empresa,l.estado_licencia,l.id_almacen,l.fecha_inicio_licencia,l.fecha_vencimiento,l.planes_id,
        p.nombre_plan,p.valor_plan, p.dias_vigencia,
        (SELECT DATEDIFF(l.fecha_vencimiento, CURDATE())) AS dias
        FROM
        crm_empresas_clientes e,
        crm_licencias_empresa l,
        users u,
        crm_planes p,
        db_config bd
            WHERE e.idempresas_clientes = l.idempresas_clientes
            AND e.idusuario_creacion = u.id
            AND bd.id=l.id_db_config
            AND l.planes_id = p.id
            AND l.planes_id >1
            HAVING dias IN (15) AND dias_vigencia IN(360,90)
            ORDER BY l.id_db_config,dias');

        $licencias = $query->result_array();

        $users = "";
        if (count($licencias) > 0) {
            foreach ($licencias as $key) {
                $users[$key['email']] = $key['email'];
                $usersnombres[$key['email']] = $key['first_name'] . ' ' . $key['last_name'];
            }

            $asunto = "Tu servicio Vendty está próximo a vencer en 15 días";
            foreach ($users as $user) {
                $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
                //$this->email->bcc(array('no-responder@vendty.net','arnulfo@vendty.com','desarrollo@vendty.com','roxanna.vergara@gmail.com','soporte@vendty.com','asesor@vendty.com'));
                $this->email->subject($asunto);
                $this->email->to($user);
                $mensaje = '
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <title>VENDTY - MODELO DE CORREO ELECTRONICO</title>
  <style type="text/css">
  body {margin: 0;padding: 0;min-width: 100%!important;}
  img {height: auto;}
  .content {width: 100%;max-width: 800px;}
  .header {padding: 20px 0px 0px 0px;background:F3F3F3}
  .innerpadding {padding: 30px 30px 30px 30px;}
  .innerpadding2{padding: 10px;}
  .borderbottom {border-bottom: 1px solid #f2eeed;}
  .content-email{ border: solid 1px lightgray;border-radius: 10px;background:#fff;padding: 20px 10px 20px 10px;}
  .subhead {font-size: 15px;color: #ffffff;font-family: sans-serif;letter-spacing: 10px;}
  .h1, .h2, .bodycopy {color: #153643;font-family: sans-serif;}
  .h1 {font-size: 33px;line-height: 38px;font-weight: bold;}
  .h2 {padding: 0 0 15px 0;font-size: 24px;line-height: 28px;font-weight: bold;}
  .bodycopy {font-size: 16px;line-height: 22px;}
  .button {text-align: center;font-size: 17px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px;}
  .button a {color: #ffffff;text-decoration: none;}
  .footer {background:F3F3F3}
  .footercopy {font-family: sans-serif;font-size: 14px;color: #979798;}
  .footercopy a {color: #979798;text-decoration: underline;}
  .footercopy .title a{color: #45af6d;font-weight: bold;text-decoration:none;}
  .footercopy .tel{color:#696969;font-weight: bold;}
  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
  body[yahoo] .hide {display: none!important;}
  body[yahoo] .buttonwrapper {background-color: transparent!important;}
  body[yahoo] .button {padding: 0px!important;}
  body[yahoo] .button a {background-color: #e05443;padding: 15px 15px 13px!important;}
  body[yahoo] .unsubscribe {display: block;margin-top: 20px;padding: 10px 50px;background: #2f3942;border-radius: 5px;text-decoration: none!important;font-weight: bold;}



  }

    .logo{padding:20px 0 20px 20px;}
            h1{padding-left:5px;color:#424242;font-family:Arial,Helvetica,sans-serif;font-size:19px;line-height:20px;padding-bottom:5px;margin:0}

           h3{color:#424242;text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding-bottom:5px;margin:0;margin-bottom:10px;}
            .fecha,h2{color:#9e9e9e;padding-left:5px;font-family:sans-serif;font-size:14px;font-weight:normal;line-height:16px;margin:0 0 10px 0;border-bottom:1px solid #e5e5e5}
            .templateColumns{border: solid 1px lightgray;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}
            .column-table{background-color:#f5f5f5;padding: 10px 0px;}
            .flex{padding: 20px;box-sizing: border-box;border: solid 1px lightgray;-webkit-border-radius: 0px 0px 0px 5px;-moz-border-radius: 0px 0px 0px 5px;border-radius: 0px 0px 5px 5px;border-top:none;}
            .title-span{width:100%; border-bottom:solid 1px #eceff1;color:#333;font-family:Arial,Helvetica,sans-serif;font-size:15px;text-transform:uppercase;padding:5px 10px 8px}
            .description-span{color:#37474f;padding-left:10px;font-family:sans-serif;font-weight:bold;font-size:26px;line-height:22px;margin:0;margin-top:12px;}
            .intro .cuenta{float:right;}
            .boton{text-align:right;}
            @media only screen and (max-width: 480px) {
                .logo{padding: 20px 0 20px 0px;display: inline-block !important;}
                .boton{margin-bottom: 17px;text-align:center;}
                /*.intro tbody{text-align:center;}*/
                .intro tbody tr td{display:block;}
                .templateColumnContainer{display:block !important;width:100% !important;}
                .templateColumnContainer tbody {width: 100%;display: block;}
                .templateColumnContainer tbody tr{ width: 100%;display: inline-table;padding-left: 5%;}
                .templateColumnContainer tbody tr td{ vertical-align: top;}
            }

  /*@media only screen and (min-device-width: 601px) {
    .content {width: 600px !important;}
    .col425 {width: 425px!important;}
    .col380 {width: 380px!important;}
    }*/

  </style>
</head>

<body yahoo bgcolor="F3F3F3">
<table width="100%" bgcolor="F3F3F3" border="0" cellpadding="0" cellspacing="0">
<tr>
  <td>
    <!--[if (gte mso 9)|(IE)]>
      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
        <tr>
          <td>
    <![endif]-->
    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
      <tr>
        <td class="header">
          <table width="200" align="left" border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td height="70" style="padding: 0 20px 20px 0;">
                <img class="fix" src="https://vendty.com/wp-content/uploads/2019/05/logo.png" width="100%" border="0" alt="" style="margin-left:2rem;"/>
              </td>
            </tr>
          </table>
          <!--[if (gte mso 9)|(IE)]>
            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
              <tr>
                <td>
          <![endif]-->
          <!--<table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%;max-width: 425px;">
            <tr>
              <td height="50">
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="subhead" style="padding: 0 0 0 3px;">
                      CREATING
                    </td>
                  </tr>
                  <tr>
                    <td class="h1" style="padding: 5px 0 0 0;">
                      Responsive Email Magic
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>-->
          <!--[if (gte mso 9)|(IE)]>
                </td>
              </tr>
          </table>
          <![endif]-->
        </td>
      </tr>
      <tr>
        <td class="innerpadding2" style="background:F3F3F3;">
          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="innerpadding2 content-email">
                        <tr>
                              <td class="h2">
                                TU LICENCIA SERÁ SUSPENDIDA
                              </td>
                            </tr>

                            <tr>
                              <td class="bodycopy">
                                <p>
                                        Estimado(a) <strong>' . strtoupper($usersnombres[$user]) . '</strong><br>
                                        Tu servicio Vendty están pronto a vencerse.<br><br>
                                        <table border="1" cellpadding="0" cellspacing="0"  width="100%" class="licenciatable" style=" margin:auto;border-radius: 3px 3px 0 0;">
                                            <thead>
                                                <th align="center">Licencia</th>
                                                <th align="center">Fecha Inicio</th>
                                                <th align="center">Fecha Vencimiento</th>
                                                <th align="center">Almacén</th>
                                                <th align="center">Valor</th>
                                                <th align="center">Acción</th>
                                            </thead>
                                            <tbody>';
                foreach ($licencias as $key) {
                    if ($user == $key['email']) {
                        $almacen = $this->db->query("SELECT nombre FROM " . $key['base_dato'] . ".almacen where id =" . $key['id_almacen'])->row_array();
                        $mensaje = $mensaje . '
                                                <tr align="center" valign="middle" style="color:#505050">
                                                    <td>' . ucfirst(strtoupper($key['nombre_plan'])) . '</td>
                                                    <td style="color:#505050">' . $key['fecha_inicio_licencia'] . '</td>
                                                    <td style="color:#505050">' . $key['fecha_vencimiento'] . '</td>
                                                    <td style="color:#505050">' . ucfirst(strtoupper($almacen['nombre'])) . '</td>
                                                    <td style="color:#505050">' . $key['valor_plan'] . '</td>
                                                    <td style="color:#505050;padding: 5px 5px;"><a class="btn btn-success" target=_blank href="http://pos.vendty.com/index.php/frontend/configuracion">Pagar</a></td>
                                                </tr>';
                    }
                }
                $mensaje = $mensaje . '
                                        </tbody>
                                        </table>
                                    <br>
                                    Gracias por elegirnos, equipo Vendty.
                                </p>
                              </td>
                            </tr>
                            <tr>
                                <td colspan=6 class="alert alert-warning" align="justify" valign="top">
                                    <b style="color:red">NOTA <br></b>
                                    <i style="font-size:13px ">Nos encantaría que siguiera usando nuestros servicios, pero como en Vendty no hay cláusula de permanencia es su decisión continuar.
                                    Si no desea renovar le pedimos que descargue su información en archivos de Excel en la opción de Informes del sistema. Recuerde que su información será borrada de nuestros servidores pasados 30 días calendario y no podrá acceder a ella si está suspendido.</i>
                                </td>
                            </tr>
                          </table>
                        </td>
                      </tr>

                      <tr>
                        <td class="footer" bgcolor="#fff">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                               <tr>
                              <td align="center" style="padding: 20px 0 0 0;">
                                <table border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://twitter.com/vendtyapps">
                                        <img src="http://pos.vendty.com/uploads/tw.png" width="30" height="30" alt="Twitter" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.facebook.com/vendtycom">
                                        <img src="http://pos.vendty.com/uploads/fb.png" width="30" height="30" alt="Facebook" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.youtube.com/user/VendtyApps">
                                        <img src="http://pos.vendty.com/uploads/yt.png" width="30" height="30" alt="Youtube" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.linkedin.com/company/vendty-apps/">
                                        <img src="http://pos.vendty.com/uploads/in.png" width="30" height="30" alt="Linkedin" border="0" />
                                      </a>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>

                            <tr>
                            <td align="center" class="footercopy">
                              <br>
                                <span class="title"><a target="_blank" href="https://ayuda.vendty.com/help">Soporte<a> - <a target="_blank" href="https://ayuda.vendty.com/help">Ayuda</a></span><br/><br/>
                                <span class="tel">3194751398</span> - <span class="tel">+57(1)636-7799</span><br/>
                                Lunes - Viernes 8AM - 6PM<br/>
                                Sábado: 8AM - 1PM<br/><br/>
                              <br/><br/>
                            </td>
                          </tr>

                        </table>
                      </td>
                    </tr>
                  </table>
                  <!--[if (gte mso 9)|(IE)]>
                        </td>
                      </tr>
                  </table>
                  <![endif]-->
                  </td>
                </tr>
              </table>

              </body>
              </html>';
                $this->email->message($mensaje);
                if (!$this->email->send()) {
                    echo '<br>No se pudo enviar el mensaje a:' . $key;
                    var_dump($this->email->print_debugger());
                }
            }
        }
    }

    /*********Licencias x vencer 30 dias*******/
    public function email_licenciasxvencer30dias()
    {
        $this->load->library('email');
        $this->email->initialize();
        /*obtener bases de datos de vendty*/
        $query = $this->db->query('
        SELECT
        u.first_name, u.last_name,u.email,
        e.nombre_empresa, e.id_db_config, bd.base_dato,
        l.idlicencias_empresa,l.estado_licencia,l.id_almacen,l.fecha_inicio_licencia,l.fecha_vencimiento,l.planes_id,
        p.nombre_plan,p.valor_plan, p.dias_vigencia,
        (SELECT DATEDIFF(l.fecha_vencimiento, CURDATE())) AS dias
        FROM
        crm_empresas_clientes e,
        crm_licencias_empresa l,
        users u,
        crm_planes p,
        db_config bd
            WHERE e.idempresas_clientes = l.idempresas_clientes
            AND e.idusuario_creacion = u.id
            AND bd.id=l.id_db_config
            AND l.planes_id = p.id
            AND l.planes_id >1
            HAVING dias IN (30) AND dias_vigencia IN(360,90)
            ORDER BY l.id_db_config,dias');

        $licencias = $query->result_array();
        $users = "";
        if (count($licencias) > 0) {
            foreach ($licencias as $key) {
                $users[$key['email']] = $key['email'];
                $usersnombres[$key['email']] = $key['first_name'] . ' ' . $key['last_name'];
            }

            $asunto = "Tu servicio Vendty está próximo a vencer";
            foreach ($users as $user) {
                $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
                //$this->email->bcc(array('no-responder@vendty.net','arnulfo@vendty.com','desarrollo@vendty.com','roxanna.vergara@gmail.com','soporte@vendty.com','asesor@vendty.com'));
                $this->email->subject($asunto);
                $this->email->to($user);
                $mensaje = '
                <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
                <html xmlns="http://www.w3.org/1999/xhtml">

                <head>
                  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
                  <title>VENDTY - MODELO DE CORREO ELECTRONICO</title>
                  <style type="text/css">
                  body {margin: 0;padding: 0;min-width: 100%!important;}
                  img {height: auto;}
                  .content {width: 100%;max-width: 800px;}
                  .header {padding: 20px 0px 0px 0px;background:F3F3F3}
                  .innerpadding {padding: 30px 30px 30px 30px;}
                  .innerpadding2{padding: 10px;}
                  .borderbottom {border-bottom: 1px solid #f2eeed;}
                  .content-email{ border: solid 1px lightgray;border-radius: 10px;background:#fff;padding: 20px 10px 20px 10px;}
                  .subhead {font-size: 15px;color: #ffffff;font-family: sans-serif;letter-spacing: 10px;}
                  .h1, .h2, .bodycopy {color: #153643;font-family: sans-serif;}
                  .h1 {font-size: 33px;line-height: 38px;font-weight: bold;}
                  .h2 {padding: 0 0 15px 0;font-size: 24px;line-height: 28px;font-weight: bold;}
                  .bodycopy {font-size: 16px;line-height: 22px;}
                  .button {text-align: center;font-size: 17px;font-family: sans-serif;font-weight: bold;padding: 0 30px 0 30px;}
                  .button a {color: #ffffff;text-decoration: none;}
                  .footer {background:F3F3F3}
                  .footercopy {font-family: sans-serif;font-size: 14px;color: #979798;}
                  .footercopy a {color: #979798;text-decoration: underline;}
                  .footercopy .title a{color: #45af6d;font-weight: bold;text-decoration:none;}
                  .footercopy .tel{color:#696969;font-weight: bold;}
                  @media only screen and (max-width: 550px), screen and (max-device-width: 550px) {
                  body[yahoo] .hide {display: none!important;}
                  body[yahoo] .buttonwrapper {background-color: transparent!important;}
                  body[yahoo] .button {padding: 0px!important;}
                  body[yahoo] .button a {background-color: #e05443;padding: 15px 15px 13px!important;}
                  body[yahoo] .unsubscribe {display: block;margin-top: 20px;padding: 10px 50px;background: #2f3942;border-radius: 5px;text-decoration: none!important;font-weight: bold;}



                  }

                    .logo{padding:20px 0 20px 20px;}
                            h1{padding-left:5px;color:#424242;font-family:Arial,Helvetica,sans-serif;font-size:19px;line-height:20px;padding-bottom:5px;margin:0}

                           h3{color:#424242;text-align:center;font-family:Arial,Helvetica,sans-serif;font-size:16px;line-height:20px;padding-bottom:5px;margin:0;margin-bottom:10px;}
                            .fecha,h2{color:#9e9e9e;padding-left:5px;font-family:sans-serif;font-size:14px;font-weight:normal;line-height:16px;margin:0 0 10px 0;border-bottom:1px solid #e5e5e5}
                            .templateColumns{border: solid 1px lightgray;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}
                            .column-table{background-color:#f5f5f5;padding: 10px 0px;}
                            .flex{padding: 20px;box-sizing: border-box;border: solid 1px lightgray;-webkit-border-radius: 0px 0px 0px 5px;-moz-border-radius: 0px 0px 0px 5px;border-radius: 0px 0px 5px 5px;border-top:none;}
                            .title-span{width:100%; border-bottom:solid 1px #eceff1;color:#333;font-family:Arial,Helvetica,sans-serif;font-size:15px;text-transform:uppercase;padding:5px 10px 8px}
                            .description-span{color:#37474f;padding-left:10px;font-family:sans-serif;font-weight:bold;font-size:26px;line-height:22px;margin:0;margin-top:12px;}
                            .intro .cuenta{float:right;}
                            .boton{text-align:right;}
                            @media only screen and (max-width: 480px) {
                                .logo{padding: 20px 0 20px 0px;display: inline-block !important;}
                                .boton{margin-bottom: 17px;text-align:center;}
                                /*.intro tbody{text-align:center;}*/
                                .intro tbody tr td{display:block;}
                                .templateColumnContainer{display:block !important;width:100% !important;}
                                .templateColumnContainer tbody {width: 100%;display: block;}
                                .templateColumnContainer tbody tr{ width: 100%;display: inline-table;padding-left: 5%;}
                                .templateColumnContainer tbody tr td{ vertical-align: top;}
                            }

                  /*@media only screen and (min-device-width: 601px) {
                    .content {width: 600px !important;}
                    .col425 {width: 425px!important;}
                    .col380 {width: 380px!important;}
                    }*/

                  </style>
                </head>

                <body yahoo bgcolor="F3F3F3">
                <table width="100%" bgcolor="F3F3F3" border="0" cellpadding="0" cellspacing="0">
                <tr>
                  <td>
                    <!--[if (gte mso 9)|(IE)]>
                      <table width="600" align="center" cellpadding="0" cellspacing="0" border="0">
                        <tr>
                          <td>
                    <![endif]-->
                    <table bgcolor="#ffffff" class="content" align="center" cellpadding="0" cellspacing="0" border="0">
                      <tr>
                        <td class="header">
                          <table width="200" align="left" border="0" cellpadding="0" cellspacing="0">
                            <tr>
                              <td height="70" style="padding: 0 20px 20px 0;">
                                <img class="fix" src="https://vendty.com/wp-content/uploads/2019/05/logo.png" width="100%" border="0" alt="" style="margin-left:2rem;"/>
                              </td>
                            </tr>
                          </table>
                          <!--[if (gte mso 9)|(IE)]>
                            <table width="425" align="left" cellpadding="0" cellspacing="0" border="0">
                              <tr>
                                <td>
                          <![endif]-->
                          <!--<table class="col425" align="left" border="0" cellpadding="0" cellspacing="0" style="width: 100%;max-width: 425px;">
                            <tr>
                              <td height="50">
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td class="subhead" style="padding: 0 0 0 3px;">
                                      CREATING
                                    </td>
                                  </tr>
                                  <tr>
                                    <td class="h1" style="padding: 5px 0 0 0;">
                                      Responsive Email Magic
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>
                          </table>-->
                          <!--[if (gte mso 9)|(IE)]>
                                </td>
                              </tr>
                          </table>
                          <![endif]-->
                        </td>
                      </tr>
                      <tr>
                        <td class="innerpadding2" style="background:F3F3F3;">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0" class="innerpadding2 content-email">
                                        <tr>
                                              <td class="h2">
                                                TU LICENCIA SERÁ SUSPENDIDA
                                              </td>
                                            </tr>

                                            <tr>
                                              <td class="bodycopy">
                                                <p>
                                                        Estimado(a) <strong>' . strtoupper($usersnombres[$user]) . '</strong><br>
                                                        Tu servicio Vendty están pronto a vencerse.<br><br>
                                                        <table border="1" cellpadding="0" cellspacing="0"  width="100%" class="licenciatable" style=" margin:auto;border-radius: 3px 3px 0 0;">
                                                            <thead>
                                                                <th align="center">Licencia</th>
                                                                <th align="center">Fecha Inicio</th>
                                                                <th align="center">Fecha Vencimiento</th>
                                                                <th align="center">Almacén</th>
                                                                <th align="center">Valor</th>
                                                                <th align="center">Acción</th>
                                                            </thead>
                                                            <tbody>';
                foreach ($licencias as $key) {
                    if ($user == $key['email']) {
                        $almacen = $this->db->query("SELECT nombre FROM " . $key['base_dato'] . ".almacen where id =" . $key['id_almacen'])->row_array();
                        $mensaje = $mensaje . '
                                                <tr align="center" valign="middle" style="color:#505050">
                                                    <td>' . ucfirst(strtoupper($key['nombre_plan'])) . '</td>
                                                    <td style="color:#505050">' . $key['fecha_inicio_licencia'] . '</td>
                                                    <td style="color:#505050">' . $key['fecha_vencimiento'] . '</td>
                                                    <td style="color:#505050">' . ucfirst(strtoupper($almacen['nombre'])) . '</td>
                                                    <td style="color:#505050">' . $key['valor_plan'] . '</td>
                                                    <td style="color:#505050;padding: 5px 5px;"><a class="btn btn-success" target=_blank href="http://pos.vendty.com/index.php/frontend/configuracion">Pagar</a></td>
                                                </tr>';
                    }
                }
                $mensaje = $mensaje . '
                                        </tbody>
                                        </table>
                                    <br>
                                    Gracias por elegirnos, equipo Vendty.
                                </p>
                              </td>
                            </tr>
                            <tr>
                                <td colspan=6 class="alert alert-warning" align="justify" valign="top">
                                    <b style="color:red">NOTA <br></b>
                                    <i style="font-size:13px ">Nos encantaría que siguiera usando nuestros servicios, pero como en Vendty no hay cláusula de permanencia es su decisión continuar.
                                    Si no desea renovar le pedimos que descargue su información en archivos de Excel en la opción de Informes del sistema. Recuerde que su información será borrada de nuestros servidores pasados 30 días calendario y no podrá acceder a ella si está suspendido.</i>
                                </td>
                            </tr>
                          </table>
                        </td>
                      </tr>

                      <tr>
                        <td class="footer" bgcolor="#fff">
                          <table width="100%" border="0" cellspacing="0" cellpadding="0">
                               <tr>
                              <td align="center" style="padding: 20px 0 0 0;">
                                <table border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://twitter.com/vendtyapps">
                                        <img src="http://pos.vendty.com/uploads/tw.png" width="30" height="30" alt="Twitter" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.facebook.com/vendtycom">
                                        <img src="http://pos.vendty.com/uploads/fb.png" width="30" height="30" alt="Facebook" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.youtube.com/user/VendtyApps">
                                        <img src="http://pos.vendty.com/uploads/yt.png" width="30" height="30" alt="Youtube" border="0" />
                                      </a>
                                    </td>
                                    <td width="25%" style="text-align: center;padding: 0 10px 0 10px;">
                                      <a target="_blank" href="https://www.linkedin.com/company/vendty-apps/">
                                        <img src="http://pos.vendty.com/uploads/in.png" width="30" height="30" alt="Linkedin" border="0" />
                                      </a>
                                    </td>
                                  </tr>
                                </table>
                              </td>
                            </tr>

                            <tr>
                            <td align="center" class="footercopy">
                              <br>
                                <span class="title"><a target="_blank" href="https://ayuda.vendty.com/help">Soporte<a> - <a target="_blank" href="https://ayuda.vendty.com/help">Ayuda</a></span><br/><br/>
                                <span class="tel">3194751398</span> - <span class="tel">+57(1)636-7799</span><br/>
                                Lunes - Viernes 8AM - 6PM<br/>
                                Sábado: 8AM - 1PM<br/><br/>
                              <br/><br/>
                            </td>
                          </tr>

                        </table>
                      </td>
                    </tr>
                  </table>
                  <!--[if (gte mso 9)|(IE)]>
                        </td>
                      </tr>
                  </table>
                  <![endif]-->
                  </td>
                </tr>
              </table>

              </body>
              </html>';
                $this->email->message($mensaje);
                if (!$this->email->send()) {
                    echo '<br>No se pudo enviar el mensaje a:' . $key;
                    var_dump($this->email->print_debugger());
                }
            }
        }
    }

    public function emailFacturaPago($idfactura)
    {
        $to = '';
        $table = '';
        $this->load->library('email');
        $this->email->initialize();

        /****sql */
        $factura = $this->crm_facturas_model->get_facturas(array('crm_factura_licencia.id_factura_licencia' => $idfactura));
        $detalle_factura = $this->crm_facturas_model->get_detalle_factura(array('crm_factura_licencia.id_factura_licencia' => $idfactura));
        $vendty = $this->crm_model->get_info_vendty();
        $empresa = $this->crm_model->get_info_empresa(array('id_empresa_cliente' => $detalle_factura[0]->idempresas_clientes));
        $nombre_empresa = $empresa->nombre_empresa;
        $to = $empresa->correo;
        $numero_factura = $factura[0]->numero_factura;
        $asunto = "Factura - #$numero_factura de VENDTY SAS";

        $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
        $this->email->bcc(array('roxanna.vergara@gmail.com', 'soporte@vendty.com', 'asesor@vendty.com'));
        $this->email->subject($asunto);
        $this->email->to($to);
        $this->email->attach("factura_$idfactura.pdf");

        /*$mensaje="Estimado/a <b>$nombre_empresa</b>:<br><br>
        Le agradecemos el interés mostrado.<br><br>
        Su factura $numero_factura se puede ver, imprimir o descargar como PDF mediante el adjunto. <br><br>
        Esperamos seguir trabajando con usted.<br><br>
        Saludos cordiales,<br>
        Roxanna Vergara A.<br>
        <b>VENDTY SAS</b>";
         */

        $data = array(
            "name" => $nombre_empresa,
            "invoice" => $numero_factura,
        );
        $message = $this->load->view('email/invoice_payment', $data, true);
        $this->email->message($message);
        // echo $mensaje;
        if (!$this->email->send()) {
            echo '<br>No se pudo enviar el mensaje a:' . $to;
            var_dump($this->email->print_debugger());
        }
    }

    public function actualizar_plan_bodegas()
    {

        $sqlbodegas = "SELECT
                    bd.id, bd.base_dato,bd.usuario,bd.clave,
                    l.idlicencias_empresa,l.estado_licencia,l.id_almacen,l.fecha_inicio_licencia,l.fecha_vencimiento,l.planes_id,
                    p.dias_vigencia
                    FROM
                    crm_licencias_empresa l,
                    crm_planes p,
                    db_config bd
                    WHERE bd.id=l.id_db_config
                    AND l.planes_id = p.id
                    AND l.planes_id IN (15,16,17)";
        $sqlbodegas = $this->db->query($sqlbodegas)->result_array();

        if ($sqlbodegas > 0) {
            foreach ($sqlbodegas as $key => $value) {
                echo "<br>value=" . $value['base_dato'];
                echo "<br>value=" . $value['id_almacen'];
                $existeDB = $this->db->query("SHOW DATABASES WHERE `database` = '" . $value['base_dato'] . "'");

                if ($existeDB->num_rows() == 1) {
                    $sqlupdate = "UPDATE " . $value['base_dato'] . ".almacen
                                SET bodega=0
                                WHERE id=" . $value['id_almacen'];
                    echo "<br>sqlupdate=" . $sqlupdate;
                    $sqlbodegas = $this->db->query($sqlupdate);
                }
            }
        }
    }
    public function plan_bodegas()
    {

        $sqlbodegas = "SELECT
            bd.id, bd.base_dato,bd.usuario,bd.clave, e.nombre_empresa,
            l.id_almacen, 0 as nombre_almacen, l.planes_id,p.nombre_plan,l.idlicencias_empresa,l.estado_licencia,l.fecha_inicio_licencia,l.fecha_vencimiento,
            p.dias_vigencia
            FROM
            crm_licencias_empresa l
            INNER JOIN crm_planes p ON l.`planes_id`=p.`id`
            INNER JOIN db_config bd ON bd.id=l.id_db_config
            INNER JOIN crm_empresas_clientes e ON l.`idempresas_clientes`=e.`idempresas_clientes`
            WHERE l.planes_id IN (15,16,17)
            ORDER BY id, id_almacen";
        $sqlbodegas = $this->db->query($sqlbodegas)->result_array();

        if ($sqlbodegas > 0) {
            foreach ($sqlbodegas as $key => $value) {
                $existeDB = $this->db->query("SHOW DATABASES WHERE `database` = '" . $value['base_dato'] . "'");
                if ($existeDB->num_rows() == 1) {
                    $sql = "select * FROM " . $value['base_dato'] . ".almacen WHERE id=" . $value['id_almacen'];
                    $sqla = $this->db->query($sql)->result_array();
                    $sqlbodegas[$key]['nombre_almacen'] = $sqla[0]['nombre'];
                }
            }
            echo '
            <table>
                <thead>
                    <th align="center">Id</th>
                    <th align="center">Base_dato</th>
                    <th align="center">Nombre_empresa</th>
                    <th align="center">Id_almacen</th>
                    <th align="center">Nombre_almacen</th>
                    <th align="center">Planes_id</th>
                    <th align="center">Nombre_plan</th>
                    <th align="center">Id_licencia</th>
                    <th align="center">Estado_licencia</th>
                    <th align="center">Fecha Inicio</th>
                    <th align="center">Fecha Vencimiento</th>
                    <th align="center">dias_vigencia</th>
                </thead>
                <tbody>
                    ';
            foreach ($sqlbodegas as $key => $value) {
                echo '<tr>
                            <td>' . $value['id'] . '</td>
                            <td>' . $value['base_dato'] . '</td>
                            <td>' . $value['nombre_empresa'] . '</td>
                            <td>' . $value['id_almacen'] . '</td>
                            <td>' . $value['nombre_almacen'] . '</td>
                            <td>' . $value['planes_id'] . '</td>
                            <td>' . $value['nombre_plan'] . '</td>
                            <td>' . $value['idlicencias_empresa'] . '</td>
                            <td>' . $value['estado_licencia'] . '</td>
                            <td>' . $value['fecha_inicio_licencia'] . '</td>
                            <td>' . $value['fecha_vencimiento'] . '</td>
                            <td>' . $value['dias_vigencia'] . '</td>
                        </tr>';
            }
            echo '
                </tbody>
            </table>';
        }
    }

    public function almacenes()
    {

        $sqlactivas = "SELECT db.`id`,l.`idempresas_clientes`, db.`base_dato`
                    FROM crm_licencias_empresa l
                    INNER JOIN db_config db ON l.`id_db_config`=db.`id`
                    WHERE l.`planes_id` NOT IN(1,15,16,17)
                    AND db.`estado`=1
                    AND db.`servidor` NOT IN ('ec2-35-163-242-38.us-west-2.compute.amazonaws.com')
                    GROUP BY l.`id_db_config` ";
        $sqlactivas = $this->db->query($sqlactivas)->result_array();

        if (!empty($sqlactivas)) {
            //inserto en crm_db_activas bd activas
            foreach ($sqlactivas as $key => $value) {

                //verifico si no esta insertada en crm_db_activas
                $existedb = 'SELECT * FROM crm_db_activas WHERE id_db=' . $value["id"];
                $existedb = $this->db->query($existedb)->result_array();
                if (empty($existedb)) {
                    //inserto en crm_db_activas
                    $sqlinsertdb = "INSERT INTO vendty2.crm_db_activas (id_db, id_empresa, tipo_negocio, nombre_empresa_config, tipo_documento_config, numero_documento_config, direccion_empresa_config, email_empresa_config, contacto_empresa_config, telefono_empresa_config, nombre_pais_config ) VALUES( " . $value['id'] . "," . $value['idempresas_clientes'] . ",(SELECT valor_opcion FROM " . $value['base_dato'] . ".opciones WHERE nombre_opcion='tipo_negocio'),(SELECT valor_opcion FROM " . $value['base_dato'] . ".opciones WHERE nombre_opcion='nombre_empresa'),(SELECT valor_opcion FROM " . $value['base_dato'] . ".opciones WHERE nombre_opcion='documento'),(SELECT valor_opcion FROM " . $value['base_dato'] . ".opciones WHERE nombre_opcion='nit'),(SELECT valor_opcion FROM	" . $value['base_dato'] . ".opciones WHERE nombre_opcion='direccion_empresa'),(SELECT valor_opcion FROM	" . $value['base_dato'] . ".opciones WHERE nombre_opcion='email_empresa'),(SELECT valor_opcion FROM	" . $value['base_dato'] . ".opciones WHERE nombre_opcion='contacto_empresa'),(SELECT valor_opcion FROM " . $value['base_dato'] . ".opciones WHERE nombre_opcion='telefono_empresa'),(SELECT nombre_pais AS pais FROM vendty2.pais WHERE id_pais IN(SELECT valor_opcion AS pais_confi FROM " . $value['base_dato'] . ".opciones WHERE nombre_opcion='pais')));";
                    $this->db->query($sqlinsertdb);
                }
            }

            $sqlBD = "SELECT l.`idlicencias_empresa`,db.`id`,l.`id_almacen` ,db.`base_dato`,db.`servidor`
                    FROM crm_licencias_empresa l
                    INNER JOIN db_config db ON l.`id_db_config`=db.`id`
                    WHERE l.`planes_id` NOT IN(1,15,16,17)
                    AND db.`estado`=1
                    AND db.`servidor` NOT IN ('ec2-35-163-242-38.us-west-2.compute.amazonaws.com')
                    GROUP BY l.`id_db_config` ,l.`idlicencias_empresa`";

            $sqlBD = $this->db->query($sqlBD)->result_array();

            if (!empty($sqlBD)) {
                foreach ($sqlBD as $key => $value) {
                    $sqlpais = 'SHOW COLUMNS FROM ' . $value["base_dato"] . '.almacen LIKE "pais"';
                    $sqlrazon = 'SHOW COLUMNS FROM ' . $value["base_dato"] . '.almacen LIKE "razon_social"';
                    $existeCampo = $this->db->query($sqlpais);
                    $existeCamporazon = $this->db->query($existeCamporazon);
                    if ($existeCampo->num_rows() > 0) {
                        $existeCampo = $existeCampo->result();
                    }

                    if ($existeCamporazon->num_rows() > 0) {
                        $existeCamporazon = $existeCamporazon->result();
                    }

                    //verifico si ya esta en  crm_db_activa_almacenes
                    $existedbalmacen = 'SELECT * FROM crm_db_activa_almacenes WHERE id_db_config=' . $value["id"] . ' AND id_licencia=' . $value["idlicencias_empresa"];
                    $existedbalmacen = $this->db->query($existedbalmacen)->result_array();
                    if (empty($existedbalmacen)) {

                        $sqlinsert = 'insert into crm_db_activa_almacenes (id_licencia,id_db_config,id_almacen,nombre_almacen,direccion_almacen,telefono_almacen,razon_social_almacen,pais_almacen,ciudad_almacen,numero_documento_almacen)
                                values(' . $value["idlicencias_empresa"] . '	, ' . $value["id"] . ',	' . $value["id_almacen"] . ',(SELECT nombre FROM ' . $value["base_dato"] . '.almacen WHERE id=	' . $value["id_almacen"] . '),(SELECT direccion FROM ' . $value["base_dato"] . '.almacen WHERE id=	' . $value["id_almacen"] . '),(SELECT telefono FROM	' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '),';

                        if (count($existeCamporazon) == 0) {
                            $sqlinsert .= '"",';
                        } else {
                            $sqlinsert .= '(SELECT razon_social FROM ' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '),';
                        }

                        if (count($existeCampo) == 0) {
                            //no existe
                            $sqlinsert .= '"",';
                        } else {
                            //existe
                            $sqlinsert .= '(SELECT pais FROM ' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '),';
                        }

                        $sqlinsert .= '(SELECT ciudad FROM ' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '),(SELECT nit FROM ' . $value["base_dato"] . '.almacen WHERE id=' . $value["id_almacen"] . '))';

                        //insertar
                        $this->db->query($sqlinsert);
                    }
                }
            }
        }
    }

    public function info_fiscal_cliente()
    {
        /*
        $usuario = "vendtyMaster";
        $clave = "ro_ar_8027*_na";
        $servidor = "34.208.35.242";
        $base_datos = "vendty2";
        $dns = "mysql://$usuario:$clave@$servidor/$base_datos";
        $this->db1 = $this->load->database($dns, true);*/

        /*$sql="SELECT COUNT(*) FROM vendty2_db_restaurante_vendty.venta";
        $sqlactivas=$this->db1->query($sql)->result_array();

        print_r($sqlactivas);die();*/

        /*$sqlactivas="SELECT db.`id`, db.`base_dato`
        FROM crm_licencias_empresa l
        INNER JOIN db_config db ON l.`id_db_config`=db.`id`
        WHERE l.`planes_id` NOT IN(1,15,16,17)
        AND db.`estado`=1
        AND db.`servidor` NOT IN ('ec2-35-163-242-38.us-west-2.compute.amazonaws.com')
        AND db.`base_dato` IN ('vendty2_db_restaurante_vendty','vendty2_db_13110_prueb2018','vendty2_db_5412eb5c76c20','vendty2_db_11423_gener2017','vendty2_db_11091_modav2017')
        GROUP BY l.`id_db_config` ";*/
        $sqlactivas = "SELECT db.`id`, db.`base_dato`
                    FROM crm_licencias_empresa l
                    INNER JOIN db_config db ON l.`id_db_config`=db.`id`
                    WHERE l.`planes_id` NOT IN(1,15,16,17)
                    AND db.`estado`=1
                    AND l.`estado_licencia` != 15
                    #AND db.`servidor` NOT IN ('ec2-35-163-242-38.us-west-2.compute.amazonaws.com')
                    AND db.`base_dato` NOT IN ('vendty2_db_3559_giova2016','vendty2_db_8544_Llore2017','vendty2_db_5500a0c4159d4')
                    #AND db.`base_dato` IN ('vendty2_db_restaurante_vendty','vendty2_db_13110_prueb2018','vendty2_db_5412eb5c76c20','vendty2_db_11423_gener2017','vendty2_db_11091_modav2017')
                    GROUP BY l.`id_db_config`";
        $sqlactivas = $this->db->query($sqlactivas)->result_array();
        $fechai = "2018-10-01";
        $fechaf = "2018-10-31";
        if (!empty($sqlactivas)) {
            //calcular los totales
            foreach ($sqlactivas as $keybd => $valuebd) {
                //verificar que exista bd
                $bd = $valuebd["base_dato"];
                $sqlbd = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$bd'";
                $existe = $this->db->query($sqlbd)->result_array();

                if (!empty($existe)) {

                    //facturas y totales
                    $sql_select_ventas = "SELECT " . $valuebd['id'] . " AS id_bd, CONCAT(YEAR(fecha),'-',MONTH(fecha)) AS fecha , (SELECT COUNT(*) AS cantidad_productos FROM " . $valuebd['base_dato'] . ".producto) AS cantidad_productos
                                ,(SELECT COUNT(*) AS cantidad_usuarios FROM vendty2.users WHERE db_config_id=11152) AS cantidad_usuarios,(SELECT COUNT(*) AS usuarios FROM vendty2.users WHERE db_config_id='11152' AND active=1 ) AS cantidad_usuarios_activos,(SELECT COUNT(*) AS usuarios FROM vendty2.users WHERE db_config_id='11152' AND active=0 ) AS cantidad_usuarios_inactivos
                                ,(SELECT COUNT(*) AS cantidad_cajas FROM " . $valuebd['base_dato'] . ".cajas) AS cantidad_cajas
                                ,(SELECT COUNT(*) AS cantidad_almacenes FROM " . $valuebd['base_dato'] . ".almacen WHERE bodega=0) AS cantidad_almacenes ,(SELECT COUNT(*) AS cantidad_bodegas FROM " . $valuebd['base_dato'] . ".almacen WHERE bodega=1) AS cantidad_bodegas
                                ,(SELECT COUNT(*) AS cantidad_forma_pago FROM " . $valuebd['base_dato'] . ".forma_pago) AS cantidad_forma_pago, (SELECT COUNT(*) AS cantidad_forma_pago FROM " . $valuebd['base_dato'] . ".forma_pago WHERE activo=1) AS cantidad_forma_pago_activas,(SELECT COUNT(*) AS cantidad_forma_pago FROM " . $valuebd['base_dato'] . ".forma_pago WHERE activo=0) AS cantidad_forma_pago_inactivas
                                ,(SELECT valor_opcion FROM " . $valuebd['base_dato'] . ".opciones WHERE nombre_opcion='tipo_moneda' LIMIT 1 ) AS tipo_moneda, (SELECT valor_opcion FROM " . $valuebd['base_dato'] . ".opciones WHERE nombre_opcion='simbolo' LIMIT 1) AS simbolo
                                ,COUNT(*) AS total_facturas, SUM(total_venta) AS total_ventas
                                FROM " . $valuebd['base_dato'] . ".venta
                                WHERE fecha BETWEEN '$fechai' AND '$fechaf'
                                GROUP BY YEAR(fecha),MONTH(fecha)";
                    $sql_select_ventas = $this->db->query($sql_select_ventas)->result_array();

                    //formas de pagos
                    $sqlpagos = "SELECT CONCAT(YEAR(fecha),'-',MONTH(fecha)) AS fecha, vp.forma_pago, SUM(vp.valor_entregado-vp.cambio) AS total_pagos, COUNT(*) AS cantidad_forma
                                FROM " . $valuebd['base_dato'] . ".venta v
                                INNER JOIN " . $valuebd['base_dato'] . ".ventas_pago vp ON v.id=vp.id_venta
                                WHERE v.fecha BETWEEN '$fechai' AND '$fechaf'
                                GROUP BY YEAR(fecha),MONTH(fecha), vp.forma_pago";

                    $sqlpagos = $this->db->query($sqlpagos)->result_array();

                    foreach ($sql_select_ventas as $key => $value) {
                        $total_efectivo = 0;
                        $cantidad_forma_efectivo = 0;
                        $total_credito = 0;
                        $cantidad_forma_credito = 0;
                        $total_puntos = 0;
                        $cantidad_forma_puntos = 0;
                        $total_gift_card = 0;
                        $cantidad_forma_gift_card = 0;
                        $total_nota_credito = 0;
                        $cantidad_forma_nota_credito = 0;
                        $total_bancolombia = 0;
                        $cantidad_forma_bancolombia = 0;
                        $total_tarjeta_debito = 0;
                        $cantidad_forma_tarjeta_debito = 0;
                        $total_tarjeta_credito = 0;
                        $cantidad_forma_tarjeta_credito = 0;
                        $total_tarjeta_debito_masterCard = 0;
                        $cantidad_forma_tarjeta_debito_masterCard = 0;
                        $total_tarjeta_credito_masterCard = 0;
                        $cantidad_forma_tarjeta_credito_masterCard = 0;
                        $total_tarjeta_debito_visa = 0;
                        $cantidad_forma_tarjeta_debito_visa = 0;
                        $total_tarjeta_credito_visa = 0;
                        $cantidad_forma_tarjeta_credito_visa = 0;
                        $total_otras = 0;
                        $cantidad_forma_otras = 0;
                        $cantidad_pagos_en_factura = 0;
                        foreach ($sqlpagos as $keyp => $valuep) {

                            if (($valuep['fecha']) == ($value['fecha'])) {

                                switch ($valuep['forma_pago']) {
                                    case 'efectivo':
                                        $total_efectivo = $valuep['total_pagos'];
                                        $cantidad_forma_efectivo = $valuep['cantidad_forma'];
                                        $cantidad_pagos_en_factura += intval($cantidad_forma_efectivo);
                                        break;

                                    case 'Credito':
                                        $total_credito = $valuep['total_pagos'];
                                        $cantidad_forma_credito = $valuep['cantidad_forma'];
                                        $cantidad_pagos_en_factura += intval($cantidad_forma_credito);
                                        break;

                                    case 'Puntos':
                                        $total_puntos = $valuep['total_pagos'];
                                        $cantidad_forma_puntos = $valuep['cantidad_forma'];
                                        $cantidad_pagos_en_factura += intval($cantidad_forma_puntos);
                                        break;

                                    case 'Gift_Card':
                                        $total_gift_card = $valuep['total_pagos'];
                                        $cantidad_forma_gift_card = $valuep['cantidad_forma'];
                                        $cantidad_pagos_en_factura += intval($cantidad_forma_gift_card);
                                        break;

                                    case 'nota_credito':
                                        $total_nota_credito = $valuep['total_pagos'];
                                        $cantidad_forma_nota_credito = $valuep['cantidad_forma'];
                                        $cantidad_pagos_en_factura += intval($cantidad_forma_nota_credito);
                                        break;

                                    case 'Bancolombia':
                                        $total_bancolombia = $valuep['total_pagos'];
                                        $cantidad_forma_bancolombia = $valuep['cantidad_forma'];
                                        $cantidad_pagos_en_factura += intval($cantidad_forma_bancolombia);
                                        break;

                                    case 'tarjeta_debito':
                                    case 'debito':
                                        $total_tarjeta_debito = $valuep['total_pagos'];
                                        $cantidad_forma_tarjeta_debito = $valuep['cantidad_forma'];
                                        $cantidad_pagos_en_factura += intval($cantidad_forma_tarjeta_debito);
                                        break;

                                    case 'tarjeta_credito':
                                        $total_tarjeta_credito += floatval($valuep['total_pagos']);
                                        $cantidad_forma_tarjeta_credito += floatval($valuep['cantidad_forma']);
                                        $cantidad_pagos_en_factura += intval($cantidad_forma_tarjeta_credito);
                                        break;

                                    case 'MasterCard debito':
                                    case 'MasterCard_debito':
                                        $total_tarjeta_debito_masterCard += floatval($valuep['total_pagos']);
                                        $cantidad_forma_tarjeta_debito_masterCard += floatval($valuep['cantidad_forma']);
                                        $cantidad_pagos_en_factura += intval($cantidad_forma_tarjeta_debito_masterCard);
                                        break;

                                    case 'MasterCard Credito':
                                    case 'MasterCard_Credito':
                                        $total_tarjeta_credito_masterCard += floatval($valuep['total_pagos']);
                                        $cantidad_forma_tarjeta_credito_masterCard += floatval($valuep['cantidad_forma']);
                                        $cantidad_pagos_en_factura += intval($cantidad_forma_tarjeta_credito_masterCard);
                                        break;

                                    case 'Visa_debito':
                                    case 'Visa debito':
                                        $total_tarjeta_debito_visa += floatval($valuep['total_pagos']);
                                        $cantidad_forma_tarjeta_debito_visa += floatval($valuep['cantidad_forma']);
                                        $cantidad_pagos_en_factura += intval($cantidad_forma_tarjeta_debito_visa);
                                        break;

                                    case 'Visa_credito':
                                    case 'Visa credito':
                                        $total_tarjeta_credito_visa += floatval($valuep['total_pagos']);
                                        $cantidad_forma_tarjeta_credito_visa += floatval($valuep['cantidad_forma']);
                                        $cantidad_pagos_en_factura += intval($cantidad_forma_tarjeta_credito_visa);
                                        break;

                                    default:
                                        $total_otras += floatval($valuep['total_pagos']);
                                        $cantidad_forma_otras += floatval($valuep['cantidad_forma']);
                                        $cantidad_pagos_en_factura += intval($cantidad_forma_otras);
                                        break;
                                }
                            }
                        }

                        //verificar si ya esta ingresado en crm_totales_bd_activas
                        $existedbtotales = 'SELECT * FROM crm_totales_bd_activas WHERE id_db=' . $value["id_bd"] . ' AND mes_anio="' . $value["fecha"] . '-01"';
                        $existedbtotales = $this->db->query($existedbtotales)->result();
                        if (empty($existedbtotales)) {
                            //insert en bd
                            $sqlinsert = "INSERT INTO crm_totales_bd_activas (id_db,mes_anio,cantidad_productos,cantidad_usuarios,cantidad_usuarios_activos,cantidad_usuarios_inactivos,
                                    cantidad_cajas,cantidad_almacenes,cantidad_bodegas,cantidad_formas_pago,cantidad_formas_pago_activas,cantidad_formas_pago_inactivas,tipo_moneda,simbolo_moneda,cantidad_facturas,cantidad_pagos_facturas,cantidad_pagos_en_facturas,cantidad_efectivo,total_efectivo,cantidad_credito,total_credito,cantidad_puntos,total_puntos,cantidad_gift_card,total_gift_card,cantidad_nota_credito,total_nota_credito,cantidad_bancolombia,total_bancolombia,cantidad_tarjeta_debito,total_tarjeta_debito,cantidad_tarjeta_credito,total_tarjeta_credito,cantidad_tarjeta_debito_masterCard,total_tarjeta_debito_masterCard,cantidad_tarjeta_credito_masterCard,total_tarjeta_credito_masterCard,cantidad_tarjeta_debito_visa,total_tarjeta_debito_visa,cantidad_tarjeta_credito_visa,total_tarjeta_credito_visa,cantidad_otros,total_otros)
                                    VALUES(" . $value['id_bd'] . ",'" . $value['fecha'] . "-01'," . $value['cantidad_productos'] . "," . $value['cantidad_usuarios'] . "," . $value['cantidad_usuarios_activos'] . "," . $value['cantidad_usuarios_inactivos'] . "," . $value['cantidad_cajas'] . "," . $value['cantidad_almacenes'] . "," . $value['cantidad_bodegas'] . "," . $value['cantidad_forma_pago'] . "," . $value['cantidad_forma_pago_activas'] . "," . $value['cantidad_forma_pago_inactivas'] . ",'" . $value['tipo_moneda'] . "','" . $value['simbolo'] . "'," . $value['total_facturas'] . "," . $value['total_ventas'] . "," . $cantidad_pagos_en_factura . "," . $cantidad_forma_efectivo . "," . $total_efectivo . "," . $cantidad_forma_credito . "," . $total_credito . "," . $cantidad_forma_puntos . "," . $total_puntos . "," . $cantidad_forma_gift_card . "," . $total_gift_card . "," . $cantidad_forma_nota_credito . "," . $total_nota_credito . "," . $cantidad_forma_bancolombia . "," . $total_bancolombia . "," . $cantidad_forma_tarjeta_debito . "," . $total_tarjeta_debito . "," . $cantidad_forma_tarjeta_credito . "," . $total_tarjeta_credito . "," . $cantidad_forma_tarjeta_debito_masterCard . "," . $total_tarjeta_debito_masterCard . "," . $cantidad_forma_tarjeta_credito_masterCard . "," . $total_tarjeta_credito_masterCard . "," . $cantidad_forma_tarjeta_debito_visa . "," . $total_tarjeta_debito_visa . "," . $cantidad_forma_tarjeta_credito_visa . "," . $total_tarjeta_credito_visa . "," . $cantidad_forma_otras . "," . $total_otras . ");";

                            //echo "<br><br>".$sqlinsert;
                            $this->db->query($sqlinsert);
                        }
                    }
                } //if existe bd
            } //foreach
        }
        echo "Termine";
    }

    public function inventario_valor_total()
    {
       // $sqlbdactivas = "SELECT * FROM `users` INNER JOIN db_config on users.db_config_id = db_config.id where users.email = 'comidasaludable@vendty.com'";
        $fecha_vencimiento = date('Y-m-d');
        $sqlbdactivas = "SELECT username,email,base_dato,d.servidor AS servidor,d.usuario AS usuario,d.clave AS clave, l.id_almacen
              FROM vendty2.users AS u
              INNER JOIN vendty2.db_config AS d ON u.db_config_id = d.id
              INNER JOIN vendty2.crm_licencias_empresa AS l ON d.id=l.id_db_config
              WHERE (d.estado = 1 || d.estado = 2)
              AND l.fecha_vencimiento>='$fecha_vencimiento'
              AND l.planes_id NOT IN (15,16,17)
              AND u.is_admin='t'
              AND servidor NOT IN ('0.0.0.0','10.0.0.7')
              GROUP BY u.db_config_id,l.id_almacen";
        
        $sqlbdactivas = $this->db->query($sqlbdactivas)->result_array();
        $allowed_servers = array(
          'produccion.cgog1qhbqtxl.us-west-2.rds.amazonaws.com',
          'produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com',
          'produccion-3.cgog1qhbqtxl.us-west-2.rds.amazonaws.com',
          'produccion-5.cgog1qhbqtxl.us-west-2.rds.amazonaws.com',
          'ec2-35-163-242-38.us-west-2.compute.amazonaws.com'
        );
        $this->load->library('email');
        $this->email->initialize();
        $this->load->model("informes_model", "informes");

        //To track emails
        $emails = "";
        $count_emails = 0;
        foreach ($sqlbdactivas as $key => $value) {
          $this->email->clear(true);
          $bd = $value['base_dato'];
          $servidor = $value['servidor'];
          $usuario = $value['usuario'];
          $clave = $value['clave'];
          $dns = "mysql://$usuario:$clave@$servidor/vendty2";

          if(in_array($servidor, $allowed_servers)){

            $this->dbConnection = $this->load->database($dns, TRUE);
            $this->dbConnection->db_debug = false;

            echo "Se encontró la base de datos: $bd <br/>";

            $sqlbd = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$bd'";
            $existe = $this->dbConnection->query($sqlbd)->result_array();

          if(count($existe) > 0) {
            echo "Resultado para si existe la BD: Existe <br/>";
            $this->dbConnection->close();
            //Recreate DNS because database exist
            $dns = "mysql://$usuario:$clave@$servidor/$bd";
            $this->dbConnection = $this->load->database($dns, TRUE);
            $this->dbConnection->db_debug = false;

            $existetabla = "SHOW TABLES WHERE `Tables_in_$bd` = 'opciones'";
            $existetabla = $this->dbConnection->query($existetabla)->result();

            if (count($existetabla) > 0) {
              $sqlopcion = "SELECT * FROM opciones WHERE nombre_opcion='enviar_valor_inventario'";
              $existeopcion = $this->dbConnection->query($sqlopcion)->row();

              if (!is_null($existeopcion) && $existeopcion->valor_opcion == 'si') {
                  echo "La opcion enviar_valor_inventario existe y es si <br/>";
                  //busco el correo a enviar
                  $emailQuery = "SELECT * FROM opciones WHERE nombre_opcion='correo_valor_inventario'";
                  $email = $this->dbConnection->query($emailQuery)->row();
                  $email = !is_null($email) ? $email->valor_opcion : "";

                  if (!empty($email)) {
                      echo "Enviar correo al email: $email <br/>";
                      $emails .= "$email <br/>";
                      $count_emails += 1;
                      //generar el archivo
                      //verifico si tiene precio x almacenes
                      $sqlprecio = "SELECT * FROM opciones WHERE nombre_opcion='precio_almacen'";
                      $precio_almacen = $this->dbConnection->query($sqlprecio)->row();
                      if (!is_null($precio_almacen)) {
                          $precio_almacen = $precio_almacen->valor_opcion;
                      } else {
                          $precio_almacen = 0;
                      }

                      echo "Precio por almacen es: $precio_almacen <br/>";

                      $this->informes->initialize($this->dbConnection);
                      $result = $this->informes->valor_inventario_cron($precio_almacen);

                      $row = 3;
                      $count = 0;
                      //excel
                      $this->load->library('phpexcel');
                      $this->phpexcel->disconnectWorksheets();
                      $this->phpexcel->createSheet();
                      $this->phpexcel->setActiveSheetIndex(0);
                      $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Valor del Inventario');
                      $this->phpexcel->getActiveSheet()->setCellValue('A2', 'Productos');

                      $this->phpexcel->getActiveSheet()->getStyle('A1:A2')->applyFromArray(
                          array(
                              'borders' => array(
                                  'allborders' => array(
                                      'style' => PHPExcel_Style_Border::BORDER_THIN,
                                      'color' => array('rgb' => '76933c'),
                                  ),
                              ),
                              'fill' => array(
                                  'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                  'startcolor' => array('rgb' => 'c6efce'),
                              ),
                              'font' => array(
                                  'bold' => true,
                                  'color' => array('rgb' => '32482b'),
                              ),
                          )
                      );

                      $greenNotBold = array(
                          'borders' => array(
                              'allborders' => array(
                                  'style' => PHPExcel_Style_Border::BORDER_THIN,
                                  'color' => array('rgb' => '76933c'),
                              ),
                          ),
                          'fill' => array(
                              'type' => PHPExcel_Style_Fill::FILL_SOLID,
                              'startcolor' => array('rgb' => 'f1f3f6'),
                          ),
                          'font' => array(
                              'bold' => true,
                              'color' => array('rgb' => '32482b'),
                          ),
                      );

                      foreach ($result['almacenes'] as $value2) {

                          $utilidad = $value2['valor_venta'] - $value2['valor_inventario'];
                          $porcen = 0;
                          if ($utilidad != 0) {
                              $porcen = number_format(($utilidad / $value2['valor_venta']) * 100);
                          }

                          if ($count >= 0) {
                              $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, html_entity_decode($value2['almacen_nombre'], ENT_QUOTES, 'UTF-8'))->getStyle('A' . $row)->applyFromArray($greenNotBold);
                              $row++;
                              $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, html_entity_decode('Valor del Inventario:', ENT_QUOTES, 'UTF-8'));
                              $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, html_entity_decode($value2['valor_inventario'], ENT_QUOTES, 'UTF-8'));
                              $row++;
                              $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, html_entity_decode('Valor a Vender:', ENT_QUOTES, 'UTF-8'));
                              $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, html_entity_decode($value2['valor_venta'], ENT_QUOTES, 'UTF-8'));
                              $row++;
                              $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, html_entity_decode('Total de Unidades:', ENT_QUOTES, 'UTF-8'));
                              $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, html_entity_decode($value2['total_unidades'], ENT_QUOTES, 'UTF-8'));
                              $row++;
                              $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, html_entity_decode('Valor Utilidad:', ENT_QUOTES, 'UTF-8'));
                              $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, html_entity_decode($utilidad, ENT_QUOTES, 'UTF-8'));
                              $row++;
                              $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, html_entity_decode('Procentaje de Utilidad:', ENT_QUOTES, 'UTF-8'));
                              $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, html_entity_decode($porcen . '%', ENT_QUOTES, 'UTF-8'));
                              $row++;
                          }
                          $count++;
                          $row++;
                      }

                      $fecha = date('Y-m-d');
                      $nuevafecha = strtotime('-1 day', strtotime($fecha));
                      $nuevafecha = date('Y-m-d', $nuevafecha);
                      $random = uniqid();
                      $nombre_archivo = 'reportevalorinventario_' . $random . '_' . $nuevafecha . '.xls';

                      $this->phpexcel->getActiveSheet()->setTitle('Valor del Inventario');
                      $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
                      $objWriter->save($nombre_archivo);
                      
                      echo "Se generó el archivo $nombre_archivo <br/>";
                      //enviar por correo
                      /**Start send email**/
                      /*$asunto = "Reporte Valor del Inventario";
                      $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
                      $this->email->subject($asunto);
                      $this->email->to($email);
                      $this->email->attach($nombre_archivo);
                      $data = array(
                          'fecha' => $nuevafecha,
                      );
                      $message = $this->load->view('email/total_inventory_by_month', $data, true);
                      $this->email->message($message);
                      $this->email->send();*/
                      /**End send email**/
                      if (file_exists($nombre_archivo)) {
                          unlink($nombre_archivo);
                      }
                      $this->dbConnection->close();
                      echo "Se envió el correo satisfactoriamente.<br/>";
                  }
              } else {
                  echo "La opcion enviar_valor_inventario no existe o es no <br/>";
              }
          } else {
            echo "La tabla opciones No existe <br/>";
          }
          } else {
            echo "Resultado para si existe la BD: No Existe <br/>";
          }
          }
        }

        $this->email->clear(true);
        $this->email->from('no-responder@vendty.net', 'Notificaciones Vendty');
        $this->email->to('soporte@vendty.com');
        $this->email->bcc(array('losada24@gmail.com', 'asesor@vendty.com', 'arnulfo@vendty.com', 'info@vendty.com'));
        $this->email->subject('Stock de inventario: ' . date('Y-m-d'));
        $this->email->message('<p><b>Total de correo enviados:</b> ' . $count_emails . ',</p><p><b>Emails:</b><br>' . $emails . '</p>');
        $this->email->send();
        echo "Se terminó";
    }

    public function historial_inventario2()
    {
        $date = date('Y-m-d');
        $nuevafecha = strtotime('-1 day', strtotime($date));
        $nuevafecha = date('Y-m-d', $nuevafecha);
        /*obtener bases de datos de vendty con licencias activas*/
        $sqlbdactivas = "SELECT username,email,base_dato,d.servidor AS servidor,d.usuario AS usuario,d.clave AS clave, l.id_almacen
            FROM vendty2.users AS u
            INNER JOIN vendty2.db_config AS d ON u.db_config_id = d.id
            INNER JOIN vendty2.crm_licencias_empresa AS l ON d.id=l.id_db_config
            WHERE (d.estado = 1 || d.estado = 2)
            AND l.fecha_vencimiento>='$date'
            AND l.planes_id NOT IN (15,16,17)
            AND u.is_admin='t'
            AND servidor NOT IN ('0.0.0.0','10.0.0.7')
            GROUP BY u.db_config_id,l.id_almacen";
        
        $allowed_servers = array(
          'produccion.cgog1qhbqtxl.us-west-2.rds.amazonaws.com',
          'produccion-2.cgog1qhbqtxl.us-west-2.rds.amazonaws.com',
          'produccion-3.cgog1qhbqtxl.us-west-2.rds.amazonaws.com',
          'produccion-5.cgog1qhbqtxl.us-west-2.rds.amazonaws.com',
          'ec2-35-163-242-38.us-west-2.compute.amazonaws.com'
        );
        //$sqlbdactivas = "SELECT * FROM `users` INNER JOIN db_config on users.db_config_id = db_config.id where users.email = 'comidasaludable@vendty.com'";
        $sqlbdactivas = $this->db->query($sqlbdactivas)->result_array();
        $this->load->library('email');
        $this->email->initialize();
        $this->load->model("informes_model", "informes");
        $emails = "";
        $count_emails = 0;

        foreach ($sqlbdactivas as $key => $value) {
          $this->email->clear(true);
          $bd = $value['base_dato'];
          $servidor = $value['servidor'];
          $usuario = $value['usuario'];
          $clave = $value['clave'];
          $dns = "mysql://$usuario:$clave@$servidor/vendty2";

          if(in_array($servidor, $allowed_servers)){
            echo "Se encontró la base de datos: $bd <br/>";
            $this->dbConnection = $this->load->database($dns, TRUE);
            $this->dbConnection->db_debug = false;

            $sqlbd = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$bd'";
            $existe = $this->dbConnection->query($sqlbd)->result_array();

            if (count($existe) > 0) {

                $this->dbConnection->close();
                $dns = "mysql://$usuario:$clave@$servidor/$bd";
                $this->dbConnection = $this->load->database($dns, TRUE);
                $this->dbConnection->db_debug = false;
                echo "Resultado para si existe la BD: Existe <br/>";
                
                $existetabla = "SHOW TABLES WHERE `Tables_in_$bd` = 'opciones'";
                $existetabla = $this->dbConnection->query($existetabla)->result();

                if (count($existetabla) > 0) {

                    $sqlopcion = "SELECT * FROM opciones WHERE nombre_opcion='stock_historico'";
                    $existeopcion = $this->dbConnection->query($sqlopcion)->row();

                    if (!is_null($existeopcion) && $existeopcion->valor_opcion == 'si') {
                        //busco el correo a enviar
                        $emailQuery = "SELECT * FROM opciones WHERE nombre_opcion='correo_stock_historico'";
                        $email = $this->dbConnection->query($emailQuery)->row();
                        $email = !is_null($email) ? $email->valor_opcion : "";
                        
                        if (!empty($email)) {
                          echo "Enviar correo al email: $email <br/>";
                          $emails .= "$email <br/>";
                          $count_emails += 1;
                            //verifico si tiene precio x almacenes
                            $sqlprecio = "SELECT * FROM opciones WHERE nombre_opcion='precio_almacen'";
                            $precio_almacen = $this->dbConnection->query($sqlprecio)->row();
                            if (!is_null($precio_almacen)) {
                                $precio_almacen = $precio_almacen->valor_opcion;
                            } else {
                                $precio_almacen = 0;
                            }

                            echo "Precio por almacen es: $precio_almacen <br/>";

                            //genero tabla si no existe
                            $existetabla = "SHOW TABLES WHERE `Tables_in_$bd` = 'stock_historial'";
                            $existetabla = $this->dbConnection->query($existetabla)->result();
                            if (count($existetabla) <= 0) { //creo la tabla
                                $stock_historial = "CREATE TABLE `stock_historial`(
                                `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
                                `fecha` DATE NOT NULL,
                                `almacen_id` INT(11),
                                `producto_id` INT(11),
                                `unidades` INT(11),
                                `precio` INT(11),
                                `precio_compra_producto` double NULL  COMMENT 'precio de compra de la tabla productos en el momento',
                                PRIMARY KEY (`id`),
                                INDEX `stock_historial_almacen_id_index` (`almacen_id`),
                                INDEX `stock_historial_producto_id_index` (`producto_id`),
                                CONSTRAINT `stock_historial_almacen_id_foreign` FOREIGN KEY (`almacen_id`) REFERENCES `almacen`(`id`) ON UPDATE CASCADE ON DELETE CASCADE,
                                CONSTRAINT `stock_historial_producto_id_foreign` FOREIGN KEY (`producto_id`) REFERENCES `producto`(`id`) ON UPDATE CASCADE ON DELETE CASCADE
                                )";
                                $this->dbConnection->query($stock_historial);
                            }
                            //si no tiene el campo precio_compra_producto
                            $sqlprecio = "SHOW COLUMNS FROM stock_historial LIKE 'precio_compra_producto'";
                            $existeCampo = $this->dbConnection->query($sqlprecio)->result();
                            if (count($existeCampo) == 0) {
                                $sql_campo = "ALTER TABLE stock_historial
                                ADD COLUMN `precio_compra_producto` double NULL COMMENT 'precio de compra de la tabla productos en el momento'";
                                $this->dbConnection->query($sql_campo);
                            }
                            //llenar tabla stock_historial

                            $q_validar = $this->dbConnection->query('SELECT * FROM stock_historial WHERE fecha = "' . $nuevafecha . '"')->result();
                            if (count($q_validar) == 0) {
                                if ($precio_almacen == 1) {
                                    $q_inventario = $this->dbConnection->query('SELECT s.* FROM stock_actual s');
                                } else {
                                    $q_inventario = $this->dbConnection->query('SELECT s.*, p.`precio_venta`,p.`precio_compra` FROM stock_actual s INNER JOIN producto p ON (s.producto_id = p.id)');
                                }

                                $data_insert = array();
                                //echo $base_de_datos->base_dato.'<br>';
                                foreach ($q_inventario->result() as $key => $un_stock) {
                                    $data_insert[$key] = array(
                                        'fecha' => $nuevafecha,
                                        'almacen_id' => $un_stock->almacen_id,
                                        'producto_id' => $un_stock->producto_id,
                                        'unidades' => $un_stock->unidades,
                                        'precio' => $un_stock->precio_venta,
                                        'precio_compra_producto' => $un_stock->precio_compra,
                                    );
                                }
                                //hacemos insert en batch
                                if (!empty($data_insert)) {
                                    $this->dbConnection->query("SET FOREIGN_KEY_CHECKS = 0");
                                    $this->dbConnection->insert_batch('stock_historial', $data_insert);
                                    $this->dbConnection->query("SET FOREIGN_KEY_CHECKS = 1");
                                }
                            }
                            //generar el archivo excel
                            //excel
                            $this->load->library('phpexcel');
                            $this->phpexcel->disconnectWorksheets();
                            $this->phpexcel->createSheet();
                            $this->phpexcel->setActiveSheetIndex(0);
                            $this->phpexcel->getActiveSheet()->setCellValue('A1', 'Almacén');
                            $this->phpexcel->getActiveSheet()->setCellValue('B1', 'Categoría');
                            $this->phpexcel->getActiveSheet()->setCellValue('C1', 'Producto');
                            $this->phpexcel->getActiveSheet()->setCellValue('D1', 'Código');
                            $this->phpexcel->getActiveSheet()->setCellValue('E1', 'Unidad');
                            $this->phpexcel->getActiveSheet()->setCellValue('F1', 'Precio Compra');
                            $this->phpexcel->getActiveSheet()->setCellValue('G1', 'Precio Venta');
                            $this->phpexcel->getActiveSheet()->setCellValue('H1', 'Unidades');
                            $this->phpexcel->getActiveSheet()->setCellValue('I1', 'Valor Inventario');
                            $this->phpexcel->getActiveSheet()->setCellValue('J1', 'Ubicación');
                            $this->phpexcel->getActiveSheet()->setCellValue('K1', 'Fecha Vencimiento');
                            $this->phpexcel->getActiveSheet()->setCellValue('L1', 'Descripción');
                            $this->phpexcel->getActiveSheet()->setCellValue('M1', 'Proveedor');

                            $this->informes->initialize($this->dbConnection);
                            //busco los datos
                            $query = "";
                            $query = $this->informes->get_ajax_data_existensias_inventario_excel_cron(0, false, $precio_almacen);

                            $row = 2;

                            foreach ($query['aaData'] as $value) {

                                $this->phpexcel->getActiveSheet()->setCellValue('A' . $row, $value[0]);
                                $this->phpexcel->getActiveSheet()->setCellValue('B' . $row, $value[1]);
                                $this->phpexcel->getActiveSheet()->setCellValue('C' . $row, $value[2]);
                                $this->phpexcel->getActiveSheet()->setCellValue('D' . $row, $value[3]);
                                $this->phpexcel->getActiveSheet()->setCellValue('E' . $row, $value[4]);
                                $this->phpexcel->getActiveSheet()->setCellValue('F' . $row, $value[5]);
                                $this->phpexcel->getActiveSheet()->setCellValue('G' . $row, $value[6]);
                                $this->phpexcel->getActiveSheet()->setCellValue('H' . $row, $value[7]);
                                $this->phpexcel->getActiveSheet()->setCellValue('I' . $row, $value[8]);
                                $this->phpexcel->getActiveSheet()->setCellValue('J' . $row, $value[9]);
                                $this->phpexcel->getActiveSheet()->setCellValue('K' . $row, $value[10]);
                                $this->phpexcel->getActiveSheet()->setCellValue('L' . $row, $value[11]);
                                $this->phpexcel->getActiveSheet()->setCellValue('M' . $row, $value[12]);

                                $row++;
                            }

                            $this->phpexcel->getActiveSheet()->getStyle('A1:M1')->applyFromArray(
                                array(
                                    'alignment' => array(
                                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                                    ),
                                    'borders' => array(
                                        'allborders' => array(
                                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                                            'color' => array('rgb' => '76933c'),
                                        ),
                                    ),
                                    'fill' => array(
                                        'type' => PHPExcel_Style_Fill::FILL_SOLID,
                                        'startcolor' => array('rgb' => 'c6efce'),
                                    ),
                                    'font' => array(
                                        'bold' => true,
                                        'color' => array('rgb' => '32482b'),
                                    ),
                                )
                            );

                            $random = uniqid();
                            $nombre_archivo = 'reporteExistenciasInventario_' . $random . '_' . $nuevafecha . '.xls';
                            $this->phpexcel->getActiveSheet()->setTitle('Existencias de Inventario');
                            $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel5');
                            $objWriter->save($nombre_archivo);
                            echo "Se generó el archivo $nombre_archivo <br/>";
                            //enviar por correo
                            /**Start send email**/
                            /*$asunto = "Reporte Existencias de Inventario";
                            $this->email->from('no-responder@vendty.net', 'Vendty POS y Tienda Virtual');
                            $this->email->subject($asunto);
                            $this->email->to($email);
                            $this->email->attach($nombre_archivo);
                            $data = array(
                                "fecha" => $nuevafecha,
                            );
                            $message = $this->load->view('email/stock_of_inventory', $data, true);
                            $this->email->message($message);
                            $this->email->send();*/
                            /**End send email**/
                            if (file_exists($nombre_archivo)) {
                              unlink($nombre_archivo);
                            }
                            $this->dbConnection->close();
                            echo "Se envió el correo satisfactoriamente.<br/>";
                        }
                    } else {
                        echo "La opcion stock_historico no existe o es no <br/>";
                    }
                } else {
                    echo "La tabla opciones No existe <br/>";
                }
            } else {
                echo "La base de datos no existe";
            }
          }    
        }

        $this->email->clear(true);
        $this->email->from('no-responder@vendty.net', 'Notificaciones Vendty');
        $this->email->to('soporte@vendty.com');
        $this->email->bcc(array('losada24@gmail.com', 'asesor@vendty.com', 'arnulfo@vendty.com', 'info@vendty.com'));
        $this->email->subject('Stock de histórico: ' . date('Y-m-d'));
        $this->email->message('<p><b>Total de correo enviados:</b> ' . $count_emails . ',</p><p><b>Emails:</b><br>' . $emails . '</p>');
        $this->email->send();
        echo "Se terminó";
    }

    public function send_sms($destination = false, $message = false, $globalParams = false, $userParams = false)
    {
        $user = rawurlencode('vendty');
        $password = rawurlencode('V3ndt1-2016+');

        //Numero por defecto
        if (!$destination) {
            $destination = rawurlencode('3015262684');
        }

        //Mensaje o plantilla por defecto, tiene saludos y un nombre
        if (!$message) {
            $message = rawurlencode('[regards], [firstname]');
        }

        //Plantilla para el saludo
        if (!$globalParams) {
            $globalParams = rawurlencode('{"regards":"Saludos de Vendty, el sistema POS para negocios"}');
        }

        //Agrupacion de los parametros para enviar la peticion GET
        $url = 'https://contactalos.com/services/rs/sendsms.php?user=' . $user . '&password=' . $password . '&destination=' . $destination . '&message=' . $message . '&globalParams=' . $globalParams . '&userParams=' . $userParams;

        // append the header putting the secret key and hash

        $request_headers = array();
        $request_headers[] = 'Authorization: Bearer ';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $data = curl_exec($ch);

        if (curl_errno($ch)) {
            print "Error: " . curl_error($ch);
        } else {
            // Show me the result
            $transaction = json_decode($data, true);
            curl_close($ch);
            var_dump($transaction);
        }
    }

    public function test()
    {
        $correosEnviados;
        $date = date('Y-m-d');
        $nuevafecha = strtotime('-1 day', strtotime($date));
        $nuevafecha = date('Y-m-d', $nuevafecha);
        /*obtener bases de datos de vendty*/
        $sqlbdactivas = "SELECT * FROM `db_config` WHERE estado= 1";
        $sqlbdactivas = $this->db->query($sqlbdactivas)->result_array();
        $this->load->library('email');
        $this->load->model("informes_model", "informes");
        $this->email->initialize();

        foreach ($sqlbdactivas as $key => $value) {
            $this->email->clear(true);
            $bd = $value['base_dato'];
            $sqlbd = "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '$bd'";
            $existe = $this->db->query($sqlbd)->result_array();

            if (!empty($existe)) {
                //creo la variable de conexion
                $usuario = $value['usuario'];
                $clave = $value['clave'];
                $servidor = $value['servidor'];
                $base_datos = $value['base_dato'];
                $dns = "mysql://$usuario:$clave@$servidor/$base_datos";
                $this->dbConnection = $this->load->database($dns, true);

                $existetabla = "SHOW TABLES WHERE `Tables_in_$bd` = 'opciones'";
                $existetabla = $this->dbConnection->query($existetabla)->result();
                if (count($existetabla) > 0) {
                    $sqlopcion = "SELECT * FROM " . $value['base_dato'] . ".opciones WHERE nombre_opcion='stock_historico'";
                    $existeopcion = $this->db->query($sqlopcion)->result_array();

                    if ((count($existeopcion) > 0) && ($existeopcion[0]['valor_opcion'] == 'si')) {
                        //busco el correo a enviar
                        $email = "SELECT * FROM " . $value['base_dato'] . ".opciones WHERE nombre_opcion='correo_stock_historico'";

                        $email = $this->db->query($email)->result_array();
                        $email = $email[0]['valor_opcion'];

                        $correosEnviados[] = $email;
                    }
                }
            }
        }

        echo "historial_inventario2";
        echo "<pre>";
        dd($correosEnviados);
    }

    public function send_sms_slack($message)
    {
        $payload = array('payload' => json_encode(array('text' => $message)));
        //url generada por slack
        $c = curl_init('https://hooks.slack.com/services/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');
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($c, CURLOPT_POST, true);
        curl_setopt($c, CURLOPT_POSTFIELDS, $payload);
        curl_exec($c);
        curl_close($c);
    }

    public function sendMails()
    {
        $this->sendMailRegisterFirst();
        $this->sendMailRegisterSecond();
        $this->sendMailRegisterThird();
        $this->sendMailRegisterFourth();
        $this->sendMailRegisterFifth();
        $this->sendMailRegisterSixth();
    }

    public function sendMailRegisterFirst()
    {
        $emails = '';
        $count = 0;
        $start_date = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 1, date('Y')));
        $end_date = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('d') - 1, date('Y')));
        $users = $this->db->query("SELECT correo, nombre, apellidos FROM registros WHERE created_at >= '" . $start_date . "' AND created_at <= '" . $end_date . "' AND suscripcion = TRUE")->result();

        $this->load->library('email');
        $this->email->initialize();

        try {
            foreach ($users as $user) {
                $data = array(
                    'correo' => $user->correo,
                );
                $html = $this->load->view('email/new_account_first_day', $data, true);

                $this->email->from('notificaciones@vendty.net', 'Eloy de Vendty');
                $this->email->to($user->correo);
                $this->email->subject('🔥Vende más con un Software Punto de Venta Con Tienda Virtual Integrada');
                $this->email->message($html);
                $this->email->send();

                $count++;
                $emails .= $user->nombre . ' ' . $user->apellidos . ' - ' . $user->correo . '<br>';
            }
        } catch (Exception $e) {
            $emails .= 'Excepción capturada: ' . $e->getMessage() . '<br>';
        }

        $this->email->from('no-responder@vendty.net', 'Eloy de Vendty');
        $this->email->to('desarrollo@vendty.com');
        $this->email->bcc(array('soporte@vendty.com', 'asesor@vendty.com', 'arnulfo@vendty.com', 'info@vendty.com'));
        $this->email->subject('Correos día 2: ' . date('Y-m-d'));
        $this->email->message('<p><b>Total de correo enviados:</b> ' . $count . ',</p><p><b>Emails:</b><br>' . $emails . '</p>');
        $this->email->send();
    }

    public function sendMailRegisterSecond()
    {
        $emails = '';
        $count = 0;
        $start_date = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 2, date('Y')));
        $end_date = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('d') - 2, date('Y')));
        $users = $this->db->query("SELECT correo, nombre, apellidos FROM registros WHERE created_at >= '" . $start_date . "' AND created_at <= '" . $end_date . "' AND suscripcion = TRUE")->result();

        $this->load->library('email');
        $this->email->initialize();

        try {
            foreach ($users as $user) {
                $data = array(
                    'apellidos' => $user->apellidos,
                    'correo' => $user->correo,
                    'nombre' => $user->nombre,
                );
                $html = $this->load->view('email/new_account_second_day', $data, true);
                $html2 = $this->load->view('email/new_account_second_day2', $data, true);

                $this->email->from('notificaciones@vendty.net', 'Eloy de Vendty');
                $this->email->to($user->correo);
                $this->email->subject('🛍️Configura tu Tienda Virtual en 5 Minutos y empieza Vender');
                $this->email->message($html);
                $this->email->send();

                $count++;
                $emails .= $user->nombre . ' ' . $user->apellidos . ' - ' . $user->correo . '(Correo 1)<br>';

                $this->email->subject('📆 Agenda una Demostración Guiada 5');
                $this->email->message($html2);
                $this->email->send();

                $count++;
                $emails .= $user->nombre . ' ' . $user->apellidos . ' - ' . $user->correo . '(Correo 2)<br>';
            }
        } catch (Exception $e) {
            $emails .= 'Excepción capturada: ' . $e->getMessage() . '<br>';
        }

        $this->email->from('no-responder@vendty.net', 'Eloy de Vendty');
        $this->email->to('desarrollo@vendty.com');
        $this->email->bcc(array('soporte@vendty.com', 'asesor@vendty.com', 'arnulfo@vendty.com', 'info@vendty.com'));
        $this->email->subject('Correos día 3: ' . date('Y-m-d'));
        $this->email->message('<p><b>Total de correo enviados:</b> ' . $count . ',</p><p><b>Emails:</b><br>' . $emails . '</p>');
        $this->email->send();
    }

    public function sendMailRegisterThird()
    {
        $emails = '';
        $count = 0;
        $start_date = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 3, date('Y')));
        $end_date = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('d') - 3, date('Y')));
        $users = $this->db->query("SELECT correo, nombre, apellidos FROM registros WHERE created_at >= '" . $start_date . "' AND created_at <= '" . $end_date . "' AND suscripcion = TRUE")->result();

        $this->load->library('email');
        $this->email->initialize();

        try {
            foreach ($users as $user) {
                $data = array(
                    'apellidos' => $user->apellidos,
                    'correo' => $user->correo,
                    'nombre' => $user->nombre,
                );
                $html = $this->load->view('email/new_account_third_day', $data, true);

                $this->email->from('notificaciones@vendty.net', 'Eloy de Vendty');
                $this->email->to($user->correo);
                $this->email->subject('🖥️ Requisitos para usar Vendty y App para tu Negocio');
                $this->email->message($html);
                $this->email->send();

                $count++;
                $emails .= $user->nombre . ' ' . $user->apellidos . ' - ' . $user->correo . '<br>';
            }
        } catch (Exception $e) {
            $emails .= 'Excepción capturada: ' . $e->getMessage() . '<br>';
        }

        $this->email->from('no-responder@vendty.net', 'Eloy de Vendty');
        $this->email->to('desarrollo@vendty.com');
        $this->email->bcc(array('soporte@vendty.com', 'asesor@vendty.com', 'arnulfo@vendty.com', 'info@vendty.com'));
        $this->email->subject('Correos día 4: ' . date('Y-m-d'));
        $this->email->message('<p><b>Total de correo enviados:</b> ' . $count . ',</p><p><b>Emails:</b><br>' . $emails . '</p>');
        $this->email->send();
    }

    public function sendMailRegisterFourth()
    {
        $emails = '';
        $count = 0;
        $start_date = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 4, date('Y')));
        $end_date = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('d') - 4, date('Y')));
        $users = $this->db->query("SELECT correo, nombre, apellidos FROM registros WHERE created_at >= '" . $start_date . "' AND created_at <= '" . $end_date . "' AND suscripcion = TRUE")->result();

        $this->load->library('email');
        $this->email->initialize();

        try {
            foreach ($users as $user) {
                $data = array(
                    'apellidos' => $user->apellidos,
                    'correo' => $user->correo,
                    'nombre' => $user->nombre,
                );
                $html = $this->load->view('email/new_account_fourth_day', $data, true);

                $this->email->from('notificaciones@vendty.net', 'Eloy de Vendty');
                $this->email->to($user->correo);
                $this->email->subject('🎥Conoce los negocios que están usando Vendty');
                $this->email->message($html);
                $this->email->send();

                $count++;
                $emails .= $user->nombre . ' ' . $user->apellidos . ' - ' . $user->correo . '<br>';
            }
        } catch (Exception $e) {
            $emails .= 'Excepción capturada: ' . $e->getMessage() . '<br>';
        }

        $this->email->from('no-responder@vendty.net', 'Eloy de Vendty');
        $this->email->to('desarrollo@vendty.com');
        $this->email->bcc(array('soporte@vendty.com', 'asesor@vendty.com', 'arnulfo@vendty.com', 'info@vendty.com'));
        $this->email->subject('Correos día 5: ' . date('Y-m-d'));
        $this->email->message('<p><b>Total de correo enviados:</b> ' . $count . ',</p><p><b>Emails:</b><br>' . $emails . '</p>');
        $this->email->send();
    }

    public function sendMailRegisterFifth()
    {
        $emails = '';
        $count = 0;
        $start_date = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 5, date('Y')));
        $end_date = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('d') - 5, date('Y')));
        $users = $this->db->query("SELECT correo, nombre, apellidos FROM registros WHERE created_at >= '" . $start_date . "' AND created_at <= '" . $end_date . "' AND suscripcion = TRUE")->result();

        $this->load->library('email');
        $this->email->initialize();

        try {
            foreach ($users as $user) {
                $data = array(
                    'correo' => $user->correo,
                );
                $html = $this->load->view('email/new_account_fifth_day', $data, true);
                $html2 = $this->load->view('email/new_account_fifth_day2', $data, true);

                $this->email->from('notificaciones@vendty.net', 'Eloy de Vendty');
                $this->email->to($user->correo);
                $this->email->subject('[OFERTA💰] Últimas Horas 40% de descuento');
                $this->email->message($html);
                $this->email->send();

                $count++;
                $emails .= $user->nombre . ' ' . $user->apellidos . ' - ' . $user->correo . '(Correo 1)<br>';

                $this->email->subject('🤔Las10 preguntas frecuentes sobre Vendty');
                $this->email->message($html2);
                $this->email->send();

                $count++;
                $emails .= $user->nombre . ' ' . $user->apellidos . ' - ' . $user->correo . '(Correo 2)<br>';
            }
        } catch (Exception $e) {
            $emails .= 'Excepción capturada: ' . $e->getMessage() . '<br>';
        }

        $this->email->from('no-responder@vendty.net', 'Eloy de Vendty');
        $this->email->to('desarrollo@vendty.com');
        $this->email->bcc(array('soporte@vendty.com', 'asesor@vendty.com', 'arnulfo@vendty.com', 'info@vendty.com'));
        $this->email->subject('Correos día 6: ' . date('Y-m-d'));
        $this->email->message('<p><b>Total de correo enviados:</b> ' . $count . ',</p><p><b>Emails:</b><br>' . $emails . '</p>');
        $this->email->send();
    }

    public function sendMailRegisterSixth()
    {
        $emails = '';
        $count = 0;
        $start_date = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m'), date('d') - 6, date('Y')));
        $end_date = date('Y-m-d H:i:s', mktime(23, 59, 59, date('m'), date('d') - 6, date('Y')));
        $users = $this->db->query("SELECT correo, nombre, apellidos FROM registros WHERE created_at >= '" . $start_date . "' AND created_at <= '" . $end_date . "' AND suscripcion = TRUE")->result();

        $this->load->library('email');
        $this->email->initialize();

        try {
            foreach ($users as $user) {
                $data = array(
                    'apellidos' => $user->apellidos,
                    'correo' => $user->correo,
                    'nombre' => $user->nombre,
                );
                $html = $this->load->view('email/new_account_sixth_day', $data, true);

                $this->email->from('notificaciones@vendty.net', 'Eloy de Vendty');
                $this->email->to($user->correo);
                $this->email->subject('🚩 Hoy Finaliza tu prueba con Vendty');
                $this->email->message($html);
                $this->email->send();

                $count++;
                $emails .= $user->nombre . ' ' . $user->apellidos . ' - ' . $user->correo . '<br>';
            }
        } catch (Exception $e) {
            $emails .= 'Excepción capturada: ' . $e->getMessage() . '<br>';
        }

        $this->email->from('no-responder@vendty.net', 'Eloy de Vendty');
        $this->email->to('desarrollo@vendty.com');
        $this->email->bcc(array('soporte@vendty.com', 'asesor@vendty.com', 'arnulfo@vendty.com', 'info@vendty.com'));
        $this->email->subject('Correos día 7: ' . date('Y-m-d'));
        $this->email->message('<p><b>Total de correo enviados:</b> ' . $count . ',</p><p><b>Emails:</b><br>' . $emails . '</p>');
        $this->email->send();
    }
}
