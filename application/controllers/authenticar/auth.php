<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once APPPATH . 'libraries/facebook/facebook.php';
require_once APPPATH . 'libraries/google/Google_Client.php';
require_once APPPATH . 'libraries/google/contrib/Google_Oauth2Service.php';

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->helper('captcha');
        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));
        $this->load->model('backend/db_config/db_config_model', "dbconfig");
        $this->load->model('ion_auth_model');
        $this->lang->load('auth');
        $this->load->helper('language');
        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');
    }

    public function email_check($str)
    {
        $query = "select * from users where email = '" . $str . "'";

        if ($this->db->query($query)->num_rows() > 0) {
            $this->form_validation->set_message('email_check', 'El %s existe, por favor recupere su clave');

            return false;
        } else {
            return true;
        }
    }

    public function nueva_cuenta()
    {
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|callback_email_check');

        if ($this->form_validation->run() == true) {
            $salt = substr(md5(uniqid(rand(), true)), 0, 10);
            $password = substr($this->input->post('email'), 0, 5) . "2015";
            $password_send = $password;
            $conf_code = $salt . substr(sha1($salt . $password), 0, -10);
            $email = $this->input->post('email');
            $username = explode('@', $email);
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $query = "INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `db_config_id`, `idioma`, `pais`, `rol_id`, `is_admin`) VALUES (NULL, '" . $ip_address . "', '" . $username[0] . "', '" . $this->ion_auth->hash_password($password) . "', '" . $salt . "', '" . $email . "', '" . $conf_code . "', NULL, NULL, NULL, '" . time() . "', '" . time() . "', '0', NULL, NULL, NULL, NULL, '', 'spanish', '', '', 't');";
            $this->db->query($query);
            $id = $this->db->insert_id();
            $this->load->library('email');
            $this->email->clear();
            $this->email->from('info@vendty.com', 'Vendty');
            $this->email->to($email);
            $this->email->bcc('desarrollo@vendty.com, comercial@vendty.com, info@vendty.com, roxanna.vergara@gmail.com');
            $this->email->subject("Bienvenido a VendTy Tu Punto de Venta en la nube");
            $this->email->message('
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="margin: 0;padding: 0;background-color: #FAFAFA;height: 100% !important;width: 100% !important;">

             <tbody><tr>

                 <td align="center" valign="top" style="border-collapse: collapse;">

                        <!-- // Begin Template Preheader \\ -->

                        <table border="0" cellpadding="10" cellspacing="0" width="600" id="templatePreheader" style="background-color: #FAFAFA;">

                            <tbody><tr>

                                <td valign="top" class="preheaderContent" style="border-collapse: collapse;">



                                 <!-- // Begin Module: Standard Preheader \ -->

                                    <table border="0" cellpadding="10" cellspacing="0" width="100%">

                                     <tbody><tr>

                                       <td valign="top" bgcolor="#009900" style="border-collapse: collapse;"><span class="Estilo2" style="color: #FFFFFF;font-weight: bold;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 24px;">Bienvenido a VendTy</span></td>

                                            <!--  -->

                                      </tr>

                                    </tbody></table>

                                 <!-- // End Module: Standard Preheader \ -->



                                </td>

                            </tr>

                        </tbody></table>

                        <!-- // End Template Preheader \\ -->

                     <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer" style="border: 1px solid #DDDDDD;background-color: #FFFFFF;">

                         <tbody><tr>

                             <td align="center" valign="top" style="border-collapse: collapse;">

                                    <!-- // Begin Template Header \\ -->

                                 <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateHeader" style="background-color: #FFFFFF;border-bottom: 0;">

                                        <tbody><tr>

                                            <td class="headerContent" style="border-collapse: collapse;color: #202020;font-family: Arial;font-size: 16px;line-height: 100%;padding: 0;text-align: center;vertical-align: middle;">



                                              <p>

											   <!-- // Begin Module: Standard Header Image \\ -->

											 Comience de inmediato! Ingrese a su cuenta de VendTy desde esta <b>direcci&oacute;n</b>:</p>

                                              <b><a href="www.vendty.com/invoice" style="color: #336699;font-weight: normal;text-decoration: underline;">http://vendty.com/invoice</a></b><br>

                                              <p>Su nombre de <b>usuario</b> administrador es: <b>

											  <a style="color: #336699;font-weight: normal;text-decoration: underline;"> ' . $email . ' </a></b>

                                                <!-- // End Module: Standard Header Image \\ -->

                                               </p>

                                              <p>Su <b>contrase&ntilde;a</b> actual es: <b>' . $password . '</b></p></td>

                                        </tr>

                                    </tbody></table>

                                    <!-- // End Template Header \\ -->

                                </td>

                            </tr>

                         <tr>

                             <td align="center" valign="top" style="border-collapse: collapse;">

                                    <!-- // Begin Template Body \\ -->

                                 <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateBody">

                                     <tbody><tr>

                                         <td valign="top" width="400" style="border-collapse: collapse;">

                                             <table border="0" cellpadding="0" cellspacing="0" width="400">

                                                 <tbody><tr>

                                                     <td valign="top" style="border-collapse: collapse;">

                                             <table border="0" cellpadding="0" cellspacing="0" width="400">

                                                             <tbody><tr>

                                                                 <td valign="top" class="bodyContent" style="border-collapse: collapse;background-color: #FFFFFF;">



                                                                        <!-- // Begin Module: Standard Content \\ -->

                                                                        <!-- // End Module: Standard Content \\ --></td>

                                               </tr>

                                                            </tbody></table>

                                                 </td>

                                                    </tr>

                                                    <tr>

                                                     <td valign="top" style="border-collapse: collapse;">

                                                            <table border="0" cellpadding="0" cellspacing="0" width="400">

                                                                <tbody><tr>

                                                                    <td valign="top" width="180" class="leftColumnContent" style="border-collapse: collapse;background-color: #FFFFFF;">



                                                                        <!-- // Begin Module: Top Image with Content \\ -->

                                                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">

                                                                            <tbody><tr mc:repeatable="repeat_1" mc:repeatindex="0" mc:hideable="hideable_repeat_1_1" mchideable="hideable_repeat_1_1">

                                                                                <td valign="top" style="border-collapse: collapse;"><div style="color: #505050;font-family: Arial;font-size: 14px;line-height: 150%;text-align: left;"><h4 class="h4" style="color: #202020;display: block;font-family: Arial;font-size: 22px;font-weight: bold;line-height: 100%;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;">Comience con el pie derecho</h4>

<span style="font-size:14px">Con nuestra &uacute;til <a href="http://www.vendty.com/manual.pdf" target="_blank" style="color: #336699;font-weight: normal;text-decoration: underline;">Guia de introduccion</a>: comenzar&#65533; usted con el pie derecho, con una breve descripcion de como configurar y comenzar a usar su cuenta de VendTy.</span></div>

                                                                                </td>

                                                                            </tr>

                                                                        </tbody></table>

                                                                        <!-- // End Module: Top Image with Content \\ -->



                                                                    </td>

                                                                    <td valign="top" width="180" class="rightColumnContent" style="border-collapse: collapse;background-color: #FFFFFF;">



                                                                        <!-- // Begin Module: Top Image with Content \\ -->

                                                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">

                                                                            <tbody><tr mc:repeatable="repeat_2" mc:repeatindex="0" mc:hideable="hideable_repeat_2_1" mchideable="hideable_repeat_2_1">

                                                                                <td valign="top" style="border-collapse: collapse;"><div style="color: #505050;font-family: Arial;font-size: 14px;line-height: 150%;text-align: left;"><h4 class="h4" style="color: #202020;display: block;font-family: Arial;font-size: 22px;font-weight: bold;line-height: 100%;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;">Hable con el equipo</h4>

Llame ya al (1) 301 6991 o al 300 412 8887 y haga una pregunta a nuestro equipo de atenci&#65533;n al cliente, siga <a href="http://www.youtube.com/channel/UCjjkzv4FmwcBen2TCVUg4gQ" style="color: #336699;font-weight: normal;text-decoration: underline;">nuestros videos</a>, o aprenda y comparta consejos &uacute;tiles y noticias en Twitter y Facebook.</div>

                                                                                </td>

                                                                            </tr>

                                                                        </tbody></table>

                                                                        <!-- // End Module: Top Image with Content \\ -->



                                                                    </td>

                                                                </tr>

                                                            </tbody></table>

                                                        </td>

                                                    </tr>

                                                </tbody></table>

                                            </td>

                                            <!-- // Begin Sidebar \\ -->

                                         <td valign="top" width="200" id="templateSidebar" style="border-collapse: collapse;background-color: #FFFFFF;">

                                             <table border="0" cellpadding="0" cellspacing="0" width="200">

                                                 <tbody><tr>

                                                     <td valign="top" class="sidebarContent" style="border-collapse: collapse;">



                                                            <!-- // Begin Module: Social Block with Icons \\ -->

                                                            <!-- // End Module: Social Block with Icons \\ -->

                                                            <!-- // Begin Module: Top Image with Content \\ -->

<table border="0" cellpadding="20" cellspacing="0" width="100%">

                                                                <tbody><tr mc:repeatable="repeat_3" mc:repeatindex="0" mc:hideable="hideable_repeat_3_1" mchideable="hideable_repeat_3_1">

                                                                    <td valign="top" style="border-collapse: collapse;"><div style="color: #505050;font-family: Arial;font-size: 12px;line-height: 150%;text-align: left;"><h4 class="h4" style="color: #202020;display: block;font-family: Arial;font-size: 22px;font-weight: bold;line-height: 100%;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;">Sistema integral</h4>

<span style="font-size:14px">VendTy ofrece una soluci&oacute;n integral de punto de venta. Incluye un sistema de seguridad de usuarios y roles, personalizaci&oacute;n del recibo de pago, stock de productos, informes contables, integraci&oacute;n y soporte telef&oacute;nico.</span></div>

                                                                  </td>

                                                                </tr>

                                                       </tbody></table>

                                                            <!-- // End Module: Top Image with Content \\ -->



                                                   </td>

                                               </tr>

                                                </tbody></table>

                                            </td>

                                            <!-- // End Sidebar \\ -->

                                        </tr>

                                    </tbody></table>

                                    <!-- // End Template Body \\ -->

                                </td>

                            </tr>

                         <tr>

                             <td align="center" valign="top" style="border-collapse: collapse;">

                                    <!-- // Begin Template Footer \\ -->

                                 <table border="0" cellpadding="10" cellspacing="0" width="600" id="templateFooter" style="background-color: #FFFFFF;border-top: 0;">

                                     <tbody><tr>

                                         <td valign="top" class="footerContent" style="border-collapse: collapse;">



                                                <!-- // Begin Module: Standard Footer \\ -->

                                                <table border="0" cellpadding="10" cellspacing="0" width="100%">

                                                    <tbody><tr>

                                                        <td valign="middle" id="social" style="border-collapse: collapse;background-color: #FAFAFA;border: 0;">

                                                            <div style="color: #707070;font-family: Arial;font-size: 12px;line-height: 125%;text-align: center;"><img src="http://gallery.mailchimp.com/653153ae841fd11de66ad181a/images/sfs_icon_twitter.png" style="margin: 0 !important;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;display: inline;">&nbsp;<a href="www.twitter.com/vendtyapps" style="color: #336699;font-weight: normal;text-decoration: underline;">Siguenos en Twitter</a> | <img src="http://gallery.mailchimp.com/653153ae841fd11de66ad181a/images/sfs_icon_facebook.png" style="margin: 0 !important;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;display: inline;"> <a href="www.facebook.com/vendtycom" style="color: #336699;font-weight: normal;text-decoration: underline;">Encuentranos en Facebook</a> | <a href="www.vendty.com" style="color: #336699;font-weight: normal;text-decoration: underline;">www.vendTy.com</a>&nbsp;</div>                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td valign="top" align="center" style="border-collapse: collapse;">



                                                              <div align="center" style="color: #707070;font-family: Arial;font-size: 12px;line-height: 125%;text-align: left;">Sistematizamos SAS, Calle 145 #46-13, Bogot&#65533; - Colombia <br>

                                                                <strong>Escribanos a nuestra direcci&#65533;n de email:</strong>

                                                                <br>

info@vendty.com</div>

                                                                                                                </td>

                                                    </tr>

                                                    <tr>

                                                        <td valign="middle" id="utility" style="border-collapse: collapse;background-color: #FFFFFF;border: 0;">

                                                            <div style="color: #707070;font-family: Arial;font-size: 12px;line-height: 125%;text-align: center;">

                                                                &nbsp;Este e-mail fue enviado a usted por info@vendty.com.

Si usted ya no desea recibir mas mensajes de correo electr&#65533;nico desde info@vendty.com, <a href="mailto:info@vendty.com" style="color: #336699;font-weight: normal;text-decoration: underline;">desuscribase de esta lista.</a>&nbsp;                                                            </div>                                                        </td>

                                                    </tr>

                                                </tbody></table>

                                                <!-- // End Module: Standard Footer \\ -->



                                       </td>

                                        </tr>

                                    </tbody></table>

                                    <!-- // End Template Footer \\ -->

                                </td>

                            </tr>

                        </tbody></table>

                        <br>

                    </td>

                </tr>
            </tbody></table>
</body>
');

            if ($this->email->send() == true) {
                $this->activate_count($id, $conf_code);
                redirect("auth/login", 'refresh');
            }
        } else {
            if (!isset($_POST['email'])) {
                $this->layout->template("login")->show('auth/new_count');
            } else {
                $data = array();
                $data['email'] = array(
                    'name' => 'email',
                    'id' => 'email',
                    'type' => 'text',
                    'value' => $this->form_validation->set_value('email'),
                    'placeholder' => 'Correo electr&oacute;nico',
                );
                $data['message'] = "Usted tiene cuenta.<br/> Por favor envie para recuperar su clave";
                $this->layout->template("login")->show('auth/forgot_password', array('data' => $data));
            }
        }
    }

    public function nueva_cuenta_pagina_web()
    {
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|callback_email_check');

        if ($this->form_validation->run() == true) {
            $salt = substr(md5(uniqid(rand(), true)), 0, 10);
            $password = substr($this->input->post('email'), 0, 5) . "2015";
            $password_send = $password;
            $conf_code = $salt . substr(sha1($salt . $password), 0, -10);
            $email = $this->input->post('email');
            $username = explode('@', $email);
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $name = $this->input->post('name');
            $mobile = $this->input->post('mobile');
            $query = "INSERT INTO `users` (`id`, `ip_address`, `username`, `password`, `salt`, `email`, `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `db_config_id`, `idioma`, `pais`, `rol_id`, `is_admin`) VALUES (NULL, '" . $ip_address . "', '" . $name . "', '" . $this->ion_auth->hash_password($password) . "', '" . $salt . "', '" . $email . "', '" . $conf_code . "', NULL, NULL, NULL, '" . time() . "', '" . time() . "', '0', NULL, NULL, NULL, " . $mobile . ", '', 'spanish', '', '', 't');";
            $this->db->query($query);
            $id = $this->db->insert_id();
            $this->load->library('email');
            $this->email->clear();
            $this->email->from('info@vendty.com', 'Vendty');
            $this->email->to($email);
            $this->email->bcc('desarrollo@vendty.com, comercial@vendty.com, info@vendty.com, roxanna.vergara@gmail.com');
            $this->email->subject("Bienvenido a VendTy Tu Punto de Venta en la nube");
            $this->email->message('
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="margin: 0;padding: 0;background-color: #FAFAFA;height: 100% !important;width: 100% !important;">

             <tbody><tr>

                 <td align="center" valign="top" style="border-collapse: collapse;">

                        <!-- // Begin Template Preheader \\ -->

                        <table border="0" cellpadding="10" cellspacing="0" width="600" id="templatePreheader" style="background-color: #FAFAFA;">

                            <tbody><tr>

                                <td valign="top" class="preheaderContent" style="border-collapse: collapse;">



                                 <!-- // Begin Module: Standard Preheader \ -->

                                    <table border="0" cellpadding="10" cellspacing="0" width="100%">

                                     <tbody><tr>

                                       <td valign="top" bgcolor="#009900" style="border-collapse: collapse;"><span class="Estilo2" style="color: #FFFFFF;font-weight: bold;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 24px;">Bienvenido a VendTy</span></td>

                                            <!--  -->

                                      </tr>

                                    </tbody></table>

                                 <!-- // End Module: Standard Preheader \ -->



                                </td>

                            </tr>

                        </tbody></table>

                        <!-- // End Template Preheader \\ -->

                     <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer" style="border: 1px solid #DDDDDD;background-color: #FFFFFF;">

                         <tbody><tr>

                             <td align="center" valign="top" style="border-collapse: collapse;">

                                    <!-- // Begin Template Header \\ -->

                                 <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateHeader" style="background-color: #FFFFFF;border-bottom: 0;">

                                        <tbody><tr>

                                            <td class="headerContent" style="border-collapse: collapse;color: #202020;font-family: Arial;font-size: 16px;line-height: 100%;padding: 0;text-align: center;vertical-align: middle;">



                                              <p>

											   <!-- // Begin Module: Standard Header Image \\ -->

											 Comience de inmediato! Ingrese a su cuenta de VendTy desde esta <b>direcci&oacute;n</b>:</p>

                                              <b><a href="www.vendty.com/invoice" style="color: #336699;font-weight: normal;text-decoration: underline;">http://vendty.com/invoice</a></b><br>

                                              <p>Su nombre de <b>usuario</b> administrador es: <b>

											  <a style="color: #336699;font-weight: normal;text-decoration: underline;"> ' . $email . ' </a></b>

                                                <!-- // End Module: Standard Header Image \\ -->

                                               </p>

                                              <p>Su <b>contrase&ntilde;a</b> actual es: <b>' . $password . '</b></p></td>

                                        </tr>

                                    </tbody></table>

                                    <!-- // End Template Header \\ -->

                                </td>

                            </tr>

                         <tr>

                             <td align="center" valign="top" style="border-collapse: collapse;">

                                    <!-- // Begin Template Body \\ -->

                                 <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateBody">

                                     <tbody><tr>

                                         <td valign="top" width="400" style="border-collapse: collapse;">

                                             <table border="0" cellpadding="0" cellspacing="0" width="400">

                                                 <tbody><tr>

                                                     <td valign="top" style="border-collapse: collapse;">

                                             <table border="0" cellpadding="0" cellspacing="0" width="400">

                                                             <tbody><tr>

                                                                 <td valign="top" class="bodyContent" style="border-collapse: collapse;background-color: #FFFFFF;">



                                                                        <!-- // Begin Module: Standard Content \\ -->

                                                                        <!-- // End Module: Standard Content \\ --></td>

                                               </tr>

                                                            </tbody></table>

                                                 </td>

                                                    </tr>

                                                    <tr>

                                                     <td valign="top" style="border-collapse: collapse;">

                                                            <table border="0" cellpadding="0" cellspacing="0" width="400">

                                                                <tbody><tr>

                                                                    <td valign="top" width="180" class="leftColumnContent" style="border-collapse: collapse;background-color: #FFFFFF;">



                                                                        <!-- // Begin Module: Top Image with Content \\ -->

                                                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">

                                                                            <tbody><tr mc:repeatable="repeat_1" mc:repeatindex="0" mc:hideable="hideable_repeat_1_1" mchideable="hideable_repeat_1_1">

                                                                                <td valign="top" style="border-collapse: collapse;"><div style="color: #505050;font-family: Arial;font-size: 14px;line-height: 150%;text-align: left;"><h4 class="h4" style="color: #202020;display: block;font-family: Arial;font-size: 22px;font-weight: bold;line-height: 100%;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;">Comience con el pie derecho</h4>

<span style="font-size:14px">Con nuestra &uacute;til <a href="http://www.vendty.com/manual.pdf" target="_blank" style="color: #336699;font-weight: normal;text-decoration: underline;">Guia de introduccion</a>: comenzar&#65533; usted con el pie derecho, con una breve descripcion de como configurar y comenzar a usar su cuenta de VendTy.</span></div>

                                                                                </td>

                                                                            </tr>

                                                                        </tbody></table>

                                                                        <!-- // End Module: Top Image with Content \\ -->



                                                                    </td>

                                                                    <td valign="top" width="180" class="rightColumnContent" style="border-collapse: collapse;background-color: #FFFFFF;">



                                                                        <!-- // Begin Module: Top Image with Content \\ -->

                                                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">

                                                                            <tbody><tr mc:repeatable="repeat_2" mc:repeatindex="0" mc:hideable="hideable_repeat_2_1" mchideable="hideable_repeat_2_1">

                                                                                <td valign="top" style="border-collapse: collapse;"><div style="color: #505050;font-family: Arial;font-size: 14px;line-height: 150%;text-align: left;"><h4 class="h4" style="color: #202020;display: block;font-family: Arial;font-size: 22px;font-weight: bold;line-height: 100%;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;">Hable con el equipo</h4>

Llame ya al (1) 301 6991 o al 300 412 8887 y haga una pregunta a nuestro equipo de atenci&#65533;n al cliente, siga <a href="http://www.youtube.com/channel/UCjjkzv4FmwcBen2TCVUg4gQ" style="color: #336699;font-weight: normal;text-decoration: underline;">nuestros videos</a>, o aprenda y comparta consejos &uacute;tiles y noticias en Twitter y Facebook.</div>

                                                                                </td>

                                                                            </tr>

                                                                        </tbody></table>

                                                                        <!-- // End Module: Top Image with Content \\ -->



                                                                    </td>

                                                                </tr>

                                                            </tbody></table>

                                                        </td>

                                                    </tr>

                                                </tbody></table>

                                            </td>

                                            <!-- // Begin Sidebar \\ -->

                                         <td valign="top" width="200" id="templateSidebar" style="border-collapse: collapse;background-color: #FFFFFF;">

                                             <table border="0" cellpadding="0" cellspacing="0" width="200">

                                                 <tbody><tr>

                                                     <td valign="top" class="sidebarContent" style="border-collapse: collapse;">



                                                            <!-- // Begin Module: Social Block with Icons \\ -->

                                                            <!-- // End Module: Social Block with Icons \\ -->

                                                            <!-- // Begin Module: Top Image with Content \\ -->

<table border="0" cellpadding="20" cellspacing="0" width="100%">

                                                                <tbody><tr mc:repeatable="repeat_3" mc:repeatindex="0" mc:hideable="hideable_repeat_3_1" mchideable="hideable_repeat_3_1">

                                                                    <td valign="top" style="border-collapse: collapse;"><div style="color: #505050;font-family: Arial;font-size: 12px;line-height: 150%;text-align: left;"><h4 class="h4" style="color: #202020;display: block;font-family: Arial;font-size: 22px;font-weight: bold;line-height: 100%;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;">Sistema integral</h4>

<span style="font-size:14px">VendTy ofrece una soluci&oacute;n integral de punto de venta. Incluye un sistema de seguridad de usuarios y roles, personalizaci&oacute;n del recibo de pago, stock de productos, informes contables, integraci&oacute;n y soporte telef&oacute;nico.</span></div>

                                                                  </td>

                                                                </tr>

                                                       </tbody></table>

                                                            <!-- // End Module: Top Image with Content \\ -->



                                                   </td>

                                               </tr>

                                                </tbody></table>

                                            </td>

                                            <!-- // End Sidebar \\ -->

                                        </tr>

                                    </tbody></table>

                                    <!-- // End Template Body \\ -->

                                </td>

                            </tr>

                         <tr>

                             <td align="center" valign="top" style="border-collapse: collapse;">

                                    <!-- // Begin Template Footer \\ -->

                                 <table border="0" cellpadding="10" cellspacing="0" width="600" id="templateFooter" style="background-color: #FFFFFF;border-top: 0;">

                                     <tbody><tr>

                                         <td valign="top" class="footerContent" style="border-collapse: collapse;">



                                                <!-- // Begin Module: Standard Footer \\ -->

                                                <table border="0" cellpadding="10" cellspacing="0" width="100%">

                                                    <tbody><tr>

                                                        <td valign="middle" id="social" style="border-collapse: collapse;background-color: #FAFAFA;border: 0;">

                                                            <div style="color: #707070;font-family: Arial;font-size: 12px;line-height: 125%;text-align: center;"><img src="http://gallery.mailchimp.com/653153ae841fd11de66ad181a/images/sfs_icon_twitter.png" style="margin: 0 !important;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;display: inline;">&nbsp;<a href="www.twitter.com/vendtyapps" style="color: #336699;font-weight: normal;text-decoration: underline;">Siguenos en Twitter</a> | <img src="http://gallery.mailchimp.com/653153ae841fd11de66ad181a/images/sfs_icon_facebook.png" style="margin: 0 !important;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;display: inline;"> <a href="www.facebook.com/vendtycom" style="color: #336699;font-weight: normal;text-decoration: underline;">Encuentranos en Facebook</a> | <a href="www.vendty.com" style="color: #336699;font-weight: normal;text-decoration: underline;">www.vendTy.com</a>&nbsp;</div>                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td valign="top" align="center" style="border-collapse: collapse;">



                                                              <div align="center" style="color: #707070;font-family: Arial;font-size: 12px;line-height: 125%;text-align: left;">Sistematizamos SAS, Calle 145 #46-13, Bogot&#65533; - Colombia <br>

                                                                <strong>Escribanos a nuestra direcci&#65533;n de email:</strong>

                                                                <br>

info@vendty.com</div>

                                                                                                                </td>

                                                    </tr>

                                                    <tr>

                                                        <td valign="middle" id="utility" style="border-collapse: collapse;background-color: #FFFFFF;border: 0;">

                                                            <div style="color: #707070;font-family: Arial;font-size: 12px;line-height: 125%;text-align: center;">

                                                                &nbsp;Este e-mail fue enviado a usted por info@vendty.com.

Si usted ya no desea recibir mas mensajes de correo electr&#65533;nico desde info@vendty.com, <a href="mailto:info@vendty.com" style="color: #336699;font-weight: normal;text-decoration: underline;">desuscribase de esta lista.</a>&nbsp;                                                            </div>                                                        </td>

                                                    </tr>

                                                </tbody></table>

                                                <!-- // End Module: Standard Footer \\ -->



                                       </td>

                                        </tr>

                                    </tbody></table>

                                    <!-- // End Template Footer \\ -->

                                </td>

                            </tr>

                        </tbody></table>

                        <br>

                    </td>

                </tr>

            </tbody></table>

</body>');

            if ($this->email->send() == true) {
                $this->activate_count($id, $password, $conf_code);
                redirect("http://vendty.com/gracias.php?var=" . $email, 'refresh');
            }
        } else {

            if (!isset($_POST['email'])) {
                redirect("http://vendty.com/", 'refresh');
            } else {
                redirect("http://vendty.com/", 'refresh');
            }
        }
    }

    public function auto_cuenta()
    {
        $this->form_validation->set_rules('nombre', $this->lang->line('create_user_validation_email_label'), 'required');
        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|callback_email_check');

        if ($this->form_validation->run() == true) {
            $salt = substr(md5(uniqid(rand(), true)), 0, 10);
            $password = uniqid();
            $password_send = $password;
            $conf_code = $salt . substr(sha1($salt . $password), 0, -10);
            $email = $this->input->post('email');
            $nomb = $this->input->post('nombre');
            $telef_emp = $this->input->post('telefono');
            $username = explode('@', $email);
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $query = "INSERT INTO `users` (`id`, `ip_address`,      `username`,           `password`,                                       `salt`,     `email`,     `activation_code`, `forgotten_password_code`, `forgotten_password_time`, `remember_code`, `created_on`, `last_login`, `active`, `first_name`, `last_name`, `company`, `phone`, `db_config_id`, `idioma`, `pais`, `rol_id`, `is_admin`) "
            . "VALUES (NULL, '" . $ip_address . "', '" . $username[0] . "', '" . $this->ion_auth->hash_password($password) . "', '" . $salt . "', '" . $email . "', '" . $conf_code . "',    NULL,                      NULL,                      NULL,            '" . time() . "', '" . time() . "', '0',      '" . $nomb . "',         NULL,       '', '" . $telef_emp . "', '', 'spanish', '', '', 't');";
            $this->db->query($query);
            $id = $this->db->insert_id();
            $this->load->library('email');
            $this->email->clear();
            $this->email->from('info@vendty.com', 'Vendty');
            $this->email->to($email);
            $this->email->bcc('desarrollo@vendty.com , comercial@vendty.com , info@vendty.com');
            $this->email->subject("Bienvenido a VendTy Tu Punto de Venta en la nube");
            $message = '
<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="margin: 0;padding: 0;background-color: #FAFAFA;height: 100% !important;width: 100% !important;">
             <tbody><tr>
                 <td align="center" valign="top" style="border-collapse: collapse;">

                        <!-- // Begin Template Preheader \\ -->

                        <table border="0" cellpadding="10" cellspacing="0" width="600" id="templatePreheader" style="background-color: #FAFAFA;">

                            <tbody><tr>

                                <td valign="top" class="preheaderContent" style="border-collapse: collapse;">



                                 <!-- // Begin Module: Standard Preheader \ -->

                                    <table border="0" cellpadding="10" cellspacing="0" width="100%">

                                     <tbody><tr>

                                       <td valign="top" bgcolor="#009900" style="border-collapse: collapse;"><span class="Estilo2" style="color: #FFFFFF;font-weight: bold;font-family: Verdana, Arial, Helvetica, sans-serif;font-size: 24px;">Bienvenido a VendTy</span></td>

                                            <!--  -->

                                      </tr>

                                    </tbody></table>

                                 <!-- // End Module: Standard Preheader \ -->



                                </td>

                            </tr>

                        </tbody></table>

                        <!-- // End Template Preheader \\ -->

                     <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateContainer" style="border: 1px solid #DDDDDD;background-color: #FFFFFF;">

                         <tbody><tr>

                             <td align="center" valign="top" style="border-collapse: collapse;">

                                    <!-- // Begin Template Header \\ -->

                                 <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateHeader" style="background-color: #FFFFFF;border-bottom: 0;">

                                        <tbody><tr>

                                            <td class="headerContent" style="border-collapse: collapse;color: #202020;font-family: Arial;font-size: 16px;line-height: 100%;padding: 0;text-align: center;vertical-align: middle;">



                                              <p>

											   <!-- // Begin Module: Standard Header Image \\ -->

											 Comience de inmediato! Ingrese a su cuenta de VendTy desde esta <b>direcci&oacute;n</b>:</p>

                                              <b><a href="www.vendty.com/invoice" style="color: #336699;font-weight: normal;text-decoration: underline;">http://vendty.com/invoice</a></b><br>

                                              <p>Su nombre de <b>usuario</b> administrador es: <b>

											  <a style="color: #336699;font-weight: normal;text-decoration: underline;"> ' . $email . ' </a></b>

                                                <!-- // End Module: Standard Header Image \\ -->

                                               </p>

                                              <p>Su <b>contrase&ntilde;a</b> actual es: <b>' . $password . '</b></p></td>

                                        </tr>

                                    </tbody></table>

                                    <!-- // End Template Header \\ -->

                                </td>

                            </tr>

                         <tr>

                             <td align="center" valign="top" style="border-collapse: collapse;">

                                    <!-- // Begin Template Body \\ -->

                                 <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateBody">

                                     <tbody><tr>

                                         <td valign="top" width="400" style="border-collapse: collapse;">

                                             <table border="0" cellpadding="0" cellspacing="0" width="400">

                                                 <tbody><tr>

                                                     <td valign="top" style="border-collapse: collapse;">

                                             <table border="0" cellpadding="0" cellspacing="0" width="400">

                                                             <tbody><tr>

                                                                 <td valign="top" class="bodyContent" style="border-collapse: collapse;background-color: #FFFFFF;">



                                                                        <!-- // Begin Module: Standard Content \\ -->

                                                                        <!-- // End Module: Standard Content \\ --></td>

                                               </tr>

                                                            </tbody></table>

                                                 </td>

                                                    </tr>

                                                    <tr>

                                                     <td valign="top" style="border-collapse: collapse;">

                                                            <table border="0" cellpadding="0" cellspacing="0" width="400">

                                                                <tbody><tr>

                                                                    <td valign="top" width="180" class="leftColumnContent" style="border-collapse: collapse;background-color: #FFFFFF;">



                                                                        <!-- // Begin Module: Top Image with Content \\ -->

                                                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">

                                                                            <tbody><tr mc:repeatable="repeat_1" mc:repeatindex="0" mc:hideable="hideable_repeat_1_1" mchideable="hideable_repeat_1_1">

                                                                                <td valign="top" style="border-collapse: collapse;"><div style="color: #505050;font-family: Arial;font-size: 14px;line-height: 150%;text-align: left;"><h4 class="h4" style="color: #202020;display: block;font-family: Arial;font-size: 22px;font-weight: bold;line-height: 100%;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;">Comience con el pie derecho</h4>

<span style="font-size:14px">Con nuestra &uacute;til <a href="http://www.vendty.com/manual.pdf" target="_blank" style="color: #336699;font-weight: normal;text-decoration: underline;">Gu&iacute;a de introducci&oacute;n</a>: comenzar&aacute; usted con el pie derecho, con una breve descripci&oacute;n de c&oacute;mo configurar y comenzar a usar su cuenta de VendTy.</span></div>

                                                                                </td>

                                                                            </tr>

                                                                        </tbody></table>

                                                                        <!-- // End Module: Top Image with Content \\ -->



                                                                    </td>

                                                                    <td valign="top" width="180" class="rightColumnContent" style="border-collapse: collapse;background-color: #FFFFFF;">



                                                                        <!-- // Begin Module: Top Image with Content \\ -->

                                                                        <table border="0" cellpadding="20" cellspacing="0" width="100%">

                                                                            <tbody><tr mc:repeatable="repeat_2" mc:repeatindex="0" mc:hideable="hideable_repeat_2_1" mchideable="hideable_repeat_2_1">

                                                                                <td valign="top" style="border-collapse: collapse;"><div style="color: #505050;font-family: Arial;font-size: 14px;line-height: 150%;text-align: left;"><h4 class="h4" style="color: #202020;display: block;font-family: Arial;font-size: 22px;font-weight: bold;line-height: 100%;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;">Hable con el equipo</h4>

Llame ya al (1) 301 6991 o al 300 412 8887 y haga una pregunta a nuestro equipo de atenci&oacute;n al cliente, siga <a href="http://www.youtube.com/channel/UCjjkzv4FmwcBen2TCVUg4gQ" style="color: #336699;font-weight: normal;text-decoration: underline;">nuestros videos</a>, o aprenda y comparta consejos &uacute;tiles y noticias en Twitter y Facebook.</div>

                                                                                </td>

                                                                            </tr>

                                                                        </tbody></table>

                                                                        <!-- // End Module: Top Image with Content \\ -->



                                                                    </td>

                                                                </tr>

                                                            </tbody></table>

                                                        </td>

                                                    </tr>

                                                </tbody></table>

                                            </td>

                                            <!-- // Begin Sidebar \\ -->

                                         <td valign="top" width="200" id="templateSidebar" style="border-collapse: collapse;background-color: #FFFFFF;">

                                             <table border="0" cellpadding="0" cellspacing="0" width="200">

                                                 <tbody><tr>

                                                     <td valign="top" class="sidebarContent" style="border-collapse: collapse;">



                                                            <!-- // Begin Module: Social Block with Icons \\ -->

                                                            <!-- // End Module: Social Block with Icons \\ -->

                                                            <!-- // Begin Module: Top Image with Content \\ -->

<table border="0" cellpadding="20" cellspacing="0" width="100%">

                                                                <tbody><tr mc:repeatable="repeat_3" mc:repeatindex="0" mc:hideable="hideable_repeat_3_1" mchideable="hideable_repeat_3_1">

                                                                    <td valign="top" style="border-collapse: collapse;"><div style="color: #505050;font-family: Arial;font-size: 12px;line-height: 150%;text-align: left;"><h4 class="h4" style="color: #202020;display: block;font-family: Arial;font-size: 22px;font-weight: bold;line-height: 100%;margin-top: 0;margin-right: 0;margin-bottom: 10px;margin-left: 0;text-align: left;">Sistema integral</h4>

<span style="font-size:14px">VendTy ofrece una soluci&oacute;n integral de punto de venta. Incluye un sistema de seguridad de usuarios y roles, personalizaci&oacute;n del recibo de pago, stock de productos, informes contables, integraci&oacute;n y soporte telef&oacute;nico.</span></div>

                                                                  </td>

                                                                </tr>

                                                       </tbody></table>

                                                            <!-- // End Module: Top Image with Content \\ -->



                                                   </td>

                                               </tr>

                                                </tbody></table>

                                            </td>

                                            <!-- // End Sidebar \\ -->

                                        </tr>

                                    </tbody></table>

                                    <!-- // End Template Body \\ -->

                                </td>

                            </tr>

                         <tr>

                             <td align="center" valign="top" style="border-collapse: collapse;">

                                    <!-- // Begin Template Footer \\ -->

                                 <table border="0" cellpadding="10" cellspacing="0" width="600" id="templateFooter" style="background-color: #FFFFFF;border-top: 0;">

                                     <tbody><tr>

                                         <td valign="top" class="footerContent" style="border-collapse: collapse;">



                                                <!-- // Begin Module: Standard Footer \\ -->

                                                <table border="0" cellpadding="10" cellspacing="0" width="100%">

                                                    <tbody><tr>

                                                        <td valign="middle" id="social" style="border-collapse: collapse;background-color: #FAFAFA;border: 0;">

                                                            <div style="color: #707070;font-family: Arial;font-size: 12px;line-height: 125%;text-align: center;"><img src="http://gallery.mailchimp.com/653153ae841fd11de66ad181a/images/sfs_icon_twitter.png" style="margin: 0 !important;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;display: inline;">&nbsp;<a href="www.twitter.com/vendtyapps" style="color: #336699;font-weight: normal;text-decoration: underline;">Siguenos en Twitter</a> | <img src="http://gallery.mailchimp.com/653153ae841fd11de66ad181a/images/sfs_icon_facebook.png" style="margin: 0 !important;border: 0;height: auto;line-height: 100%;outline: none;text-decoration: none;display: inline;"> <a href="www.facebook.com/vendtycom" style="color: #336699;font-weight: normal;text-decoration: underline;">Encuentranos en Facebook</a> | <a href="www.vendty.com" style="color: #336699;font-weight: normal;text-decoration: underline;">www.vendTy.com</a>&nbsp;</div>                                                        </td>

                                                    </tr>

                                                    <tr>

                                                        <td valign="top" align="center" style="border-collapse: collapse;">



                                                              <div align="center" style="color: #707070;font-family: Arial;font-size: 12px;line-height: 125%;text-align: left;">Sistematizamos SAS, Calle 145 #46-13, Bogot&aacute; - Colombia <br>

                                                                <strong>Escribanos a nuestra direcci&oacute;n de email:</strong>

                                                                <br>

info@vendty.com</div>

                                                                                                                </td>

                                                    </tr>

                                                    <tr>

                                                        <td valign="middle" id="utility" style="border-collapse: collapse;background-color: #FFFFFF;border: 0;">

                                                            <div style="color: #707070;font-family: Arial;font-size: 12px;line-height: 125%;text-align: center;">

                                                                &nbsp;Este e-mail fue enviado a usted por info@vendty.com.

Si usted ya no desea recibir mas mensajes de correo electr&oacute;nico desde info@vendty.com, <a href="mailto:info@vendty.com" style="color: #336699;font-weight: normal;text-decoration: underline;">desuscribase de esta lista.</a>&nbsp;                                                            </div>                                                        </td>

                                                    </tr>

                                                </tbody></table>

                                                <!-- // End Module: Standard Footer \\ -->



                                       </td>

                                        </tr>

                                    </tbody></table>

                                    <!-- // End Template Footer \\ -->

                                </td>

                            </tr>

                        </tbody></table>

                        <br>

                    </td>

                </tr>

            </tbody></table>

</body>



				';

//echo $message;

            $this->email->message($message);

            // if ($this->email->send() == TRUE)

            //    {

            $this->activate_count($id, $conf_code);

            if ($this->ion_auth->login($email, $password, false)) {

                $this->session->set_flashdata('pa', $password);

                redirect('auth/crear_usuario_wizard/' . $id);

            } else {

                redirect('auth/auto_cuenta', 'refresh');

            }

            //redirect("auth/login", 'refresh');

            // }

        } else {

            if (!isset($_POST['email']) || $_POST['email'] == "") {

                $this->layout->template("login")->show('auth/new_account');

            } else {

                $data = array();

                $data['email'] = array(

                    'name' => 'email',

                    'id' => 'email',

                    'type' => 'text',

                    'value' => $this->form_validation->set_value('email'),

                    'placeholder' => 'Correo electr&oacute;nico',

                );

                $data['message'] = "Usted tiene cuenta.<br/> Por favor envie para recuperar su clave";

                $this->layout->template("login")->show('auth/forgot_password', array('data' => $data));

            }

        }

    }

    //redirect if needed, otherwise display the user list

    public function index()
    {

        if (!$this->ion_auth->logged_in()) {

            //redirect them to the login page

            redirect('auth/login', 'refresh');

        } elseif (!$this->ion_auth->is_admin()) {

            //redirect them to the home page because they must be an administrator to view this

            redirect('frontend/index', 'refresh');

        } else {

            redirect('backend/dashboard/index', 'refresh');

        }

    }

    public function change_languaje()
    {

        $languaje = $this->input->post('languaje');

        $this->session->set_userdata('idioma', $languaje);

        $this->db->where('id', $this->session->userdata('user_id'))->update('users', array('idioma' => $languaje));

    }

    public function googlelogin()
    {

        $gClient = new Google_Client();

        $gClient->setApplicationName($this->config->item('site_title'));

        $gClient->setClientId($this->config->item('google_client_id'));

        $gClient->setClientSecret($this->config->item('google_client_secret'));

        $gClient->setRedirectUri(site_url("auth/googlelogin"));

        $gClient->setDeveloperKey($this->config->item('google_developer_key'));

        $google_oauthV2 = new Google_Oauth2Service($gClient);

        //Redirect user to google authentication page for code, if code is empty.

        //Code is required to aquire Access Token from google

        //Once we have access token, assign token to session variable

        //and we can redirect user back to page and login.

        if (isset($_GET['code'])) {

            $gClient->authenticate($_GET['code']);

            $this->session->set_userdata('token', $gClient->getAccessToken());

            redirect(site_url("auth/googlelogin"));

        }

        $token = $this->session->userdata('token');

        if (!empty($token)) {

            $gClient->setAccessToken($this->session->userdata('token'));

        }

        if ($gClient->getAccessToken()) {

            //Get user details if user is logged in

            $user = $google_oauthV2->userinfo->get();

            $user_id = $user['id'];

            $user_name = filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);

            $email = filter_var($user['email'], FILTER_SANITIZE_EMAIL);

            //$profile_url          = filter_var($user['link'], FILTER_VALIDATE_URL);

            //$profile_image_url    = filter_var($user['picture'], FILTER_VALIDATE_URL);

            //$personMarkup         = "$email<div><img src='$profile_image_url?sz=50'></div>";

            //$_SESSION['token']    = ;

            $this->session->set_userdata('token', $gClient->getAccessToken());

        } else {

            //get google login url

            $authUrl = $gClient->createAuthUrl();

        }

        if (isset($authUrl)) //user is not logged in, show login button

        {

            redirect($authUrl);

        } else {

            if ($this->ion_auth_model->email_check($email)) {

                $query = $this->db->from('users')->where("email", $email)

                    ->select('id')

                    ->limit(1)

                    ->get();

                $this->_do_login($query->row()->id);

                $this->_do_login($query->row()->id);

            } else {

                $additional_data = array(

                    'first_name' => $user_name,

                    'last_name' => $user_name,

                    //'company'    => $this->input->post('company'),

                    //'phone'      => $this->input->post('phone1'),

                    //'db_config_id' => 0

                );

                $id = $this->ion_auth_model->register($user_name, $this->ion_auth_model->salt(), $email, $additional_data);

                $this->ion_auth_model->activate($id);

                $this->_create_db($id);

                $this->_do_login($id);

            }

            redirect("frontend/index");

        }

    }

    public function fblogin()
    {

        //get the Facebook appId and app secret from facebook.php which located in config directory for the creating the object for Facebook class

        $facebook = new Facebook(array(

            'appId' => $this->config->item('appID'),

            'secret' => $this->config->item('appSecret'),

        ));

        $user = $facebook->getUser(); // Get the facebook user id

        if ($user) {

            try {

                $user_profile = $facebook->api('/me'); //Get the facebook user profile data

                $this->session->set_userdata('logout', $facebook->getLogoutUrl(array('next' => site_url('auth/logout'))));

                $user_id = $user_profile['id'];

                $user_first_name = $user_profile['first_name'];

                $user_last_name = $user_profile['last_name'];

                $username = strtolower($user_profile['name']);

                $email = strtolower($user_profile['email']);

                if ($this->ion_auth_model->email_check($email)) {

                    $query = $this->db->from('users')->where("email", $email)

                        ->select('id')

                        ->limit(1)

                        ->get();

                    $this->_do_login($query->row()->id);

                } else {

                    $additional_data = array(

                        'first_name' => $user_first_name,

                        'last_name' => $user_last_name,

                        //'company'    => $this->input->post('company'),

                        //'phone'      => $this->input->post('phone1'),

                        //'db_config_id' => 0

                    );

                    $id = $this->ion_auth_model->register($username, $this->ion_auth_model->salt(), $email, $additional_data);

                    $this->ion_auth_model->activate($id);

                    $this->_create_db($id);

                    $this->_do_login($id);

                }

                redirect("frontend/index");

            } catch (FacebookApiException $e) {

                error_log($e);

                $user = null;

            }

        } else {

            $loginUrl = $facebook->getLoginUrl(array('redirect_uri' => site_url('auth/fblogin'), 'scope' => "email"));

            redirect($loginUrl);

        }

    }

    public function _do_login($id)
    {

        $query = $this->db->select('users.id, username, email, users.id, password, active, last_login, usuario, clave, servidor, base_dato, db_config_id, idioma')

            ->where("users.id", $id)

            ->join('db_config', 'db_config.id = users.db_config_id', 'left')

            ->limit(1)

            ->get('users');

        $user = $query->row();

        $this->ion_auth_model->set_session($user);

        $this->ion_auth_model->update_last_login($user->id);

        /*$usuario = $this->session->userdata('usuario');

    $clave = $this->session->userdata('clave');

    $servidor = $this->session->userdata('servidor');

    $base_dato = $this->session->userdata('base_dato');

    $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

    $dbConnection = $this->load->database($dns, true);

    $this->load->model("miempresa_model",'miempresa');

    $this->miempresa->initialize($dbConnection);

    $this->session->set_userdata('idioma', $this->miempresa->get_idioma_empresa());*/

    }

    public function terminos_condiciones()
    {

        $array_datos = array(

            "term_acept" => 'Si',

            "term_fecha" => date("Y/m/d"),

        );

        $this->db->where('username', $this->session->userdata('username'));

        $this->db->update("users", $array_datos);

        redirect("frontend/index");

    }

    //log the user in

    public function crear_usuario_wizard($user_id)
    {

        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        //echo $dns;

        $this->dbConnection = $this->load->database($dns, true);

        $this->load->model("productos_model", 'productos');

        $this->productos->initialize($this->dbConnection);

        $this->load->model("impuestos_model", 'impuestos');

        $this->impuestos->initialize($this->dbConnection);

        $this->load->model("categorias_model", 'categorias');

        $this->categorias->initialize($this->dbConnection);

        $this->load->model("almacenes_model", 'almacenes');

        $this->almacenes->initialize($this->dbConnection);

        $this->load->model("productos_tipo_model", 'producto_tipo');

        $this->producto_tipo->initialize($this->dbConnection);

        $this->load->model("unidades_model", 'unidades');

        $this->unidades->initialize($this->dbConnection);

        $this->load->model("miempresa_model", 'miempresa');

        $this->miempresa->initialize($this->dbConnection);

        $this->load->model("backend/db_config/db_config_model", 'db_config');

        $objUser = $this->ion_auth->identity_getbyId($user_id);

        $data['categorias'] = $this->categorias->get_combo_data();

        $data['impuestos'] = $this->impuestos->get_combo_data();

        $data['almacenes'] = $this->almacenes->get_combo_data();

        $data['tipo_productos'] = $this->producto_tipo->get_all();

        $data['unidades'] = $this->unidades->get_combo_data();

        $data['empresa'] = $this->miempresa->get_nombre_empresa();

        $data['estado'] = $this->db_config->get_estado($user_id);

        // print_r($data['estado']);

        $this->layout->template("login")->show('auth/wizard', array('data' => $data));

    }

    public function login()
    {

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $this->form_validation->set_rules('identity', 'Identity', 'required');

        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == true) {

            $remember = (bool) $this->input->post('remember');

            $objUser = $this->ion_auth->identity_get($this->input->post('identity'));

            if (isset($objUser)) {

                $query2 = $this->db->select('id, estado')

                    ->where('id', $objUser->db_config_id)

                    ->get('db_config');

                $db_config = $query2->row();

                /*print_r('<pre>');

                print_r($db_config);

                print_r('</pre>');*/

                if (isset($db_config)) {

                    if ($db_config->estado != 2) {

                        if ($this->ion_auth->login_confirm($this->input->post('identity'), $this->input->post('password'), $remember)) {

                            //$this->session->set_flashdata('id',$objUser->id);

                            redirect('auth/crear_usuario_wizard/' . $objUser->id);

                        }}

                    // else{

                    //    $this->session->set_flashdata('message', $this->ion_auth->errors());

                    //redirect('auth/login', 'refresh');

                    //}

                }

            }

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {

                //if the login is successful

                //redirect them back to the home page

                $this->session->set_flashdata('message', $this->ion_auth->messages());

                if ($this->ion_auth->is_admin()) {

                    redirect('backend/dashboard');

                } else {

                    /*$usuario = $this->session->userdata('usuario');

                    $clave = $this->session->userdata('clave');

                    $servidor = $this->session->userdata('servidor');

                    $base_dato = $this->session->userdata('base_dato');

                    $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

                    $dbConnection = $this->load->database($dns, true);

                    $this->load->model("miempresa_model",'miempresa');

                    $this->miempresa->initialize($dbConnection);

                    $this->session->set_userdata('idioma', $this->miempresa->get_idioma_empresa());*/

                    $username = $this->input->post('identity');

                    //  redirect("frontend/indexh");

                    $term = '';

                    $admin = '';

                    $user = $this->db->query("SELECT term_acept, is_admin FROM users where email = '" . $username . "' ")->result();

                    foreach ($user as $dat) {

                        $term = $dat->term_acept;

                        $admin = $dat->is_admin;

                    }

                    if ($term == '' && $admin == 't') {

                        $this->layout->template('login')

                            ->css(array(base_url('public/css/stylesheets.css')))

                            ->show('frontend/condiciones.php');

                    }

                    if ($term == 'Si') {

                        redirect("frontend/index");

                    }

                    if ($admin == 'f') {

                        redirect("frontend/index");

                    }

                }

            } else {

                //if the login was un-successful

                //redirect them back to the login page

                $this->session->set_flashdata('message', $this->ion_auth->errors());

                redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries

            }

        } else {

            //the user is not logging in so display the login page

            //set the flash data error message if there is one

            //$data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $data['identity'] = array('name' => 'identity',

                'id' => 'identity',

                'type' => 'text',

                'value' => $this->form_validation->set_value('identity'),

                'placeholder' => "login",

            );

            $data['password'] = array('name' => 'password',

                'id' => 'password',

                'type' => 'password',

                'placeholder' => "password",

            );

            $this->layout->template('login')->show('auth/login', array('data' => $data));

        }

    }

    //log the user out

    public function logout()
    {

        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');

        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);

        $usuario = '';

        $usuario = $this->session->userdata('user_id');

        $this->dbConnection->query("delete from  factura_espera where usuario_id = '" . $usuario . "' and id>0 ");

        $this->data['title'] = "Logout";

        $token = $this->session->userdata('token');

        if (!empty($token)) {

            $gClient = new Google_Client();

            $gClient->setApplicationName($this->config->item('site_title'));

            $gClient->setClientId($this->config->item('google_client_id'));

            $gClient->setClientSecret($this->config->item('google_client_secret'));

            $gClient->setRedirectUri(site_url("auth/googlelogin"));

            $gClient->setDeveloperKey($this->config->item('google_developer_key'));

            $google_oauthV2 = new Google_Oauth2Service($gClient);

            $this->session->unset_userdata('token');

            $gClient->revokeToken();

        }

        //log the user out

        $logout = $this->ion_auth->logout();

        //redirect them to the login page

        $this->session->set_flashdata('message', $this->ion_auth->messages());

        redirect('auth/login', 'refresh');

    }

    //change password

    public function change_password()
    {

        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');

        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');

        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        if (!$this->ion_auth->logged_in()) {

            redirect('auth/login', 'refresh');

        }

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() == false) {

            //display the form

            //set the flash data error message if there is one

            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');

            $this->data['old_password'] = array(

                'name' => 'old',

                'id' => 'old',

                'type' => 'password',

            );

            $this->data['new_password'] = array(

                'name' => 'new',

                'id' => 'new',

                'type' => 'password',

                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',

            );

            $this->data['new_password_confirm'] = array(

                'name' => 'new_confirm',

                'id' => 'new_confirm',

                'type' => 'password',

                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',

            );

            $this->data['user_id'] = array(

                'name' => 'user_id',

                'id' => 'user_id',

                'type' => 'hidden',

                'value' => $user->id,

            );

            //render

            $this->_render_page('auth/change_password', $this->data);

        } else {

            $identity = $this->session->userdata($this->config->item('identity', 'ion_auth'));

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {

                //if the password was successfully changed

                $this->session->set_flashdata('message', $this->ion_auth->messages());

                $this->logout();

            } else {

                $this->session->set_flashdata('message', $this->ion_auth->errors());

                redirect('auth/change_password', 'refresh');

            }

        }

    }

    //forgot password

    public function forgot_password()
    {

        $this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required');

        if ($this->form_validation->run() == false) {

            //setup the input

            $this->data['email'] = array('name' => 'email',

                'id' => 'email',

            );

            if ($this->config->item('identity', 'ion_auth') == 'username') {

                $this->data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');

            } else {

                $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');

            }

            //set any errors and display the form

            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

            //$this->_render_page('auth/forgot_password', $this->data);

            $this->layout->template('login')->show('auth/forgot_password', array('data' => $this->data));

        } else {

            // get identity for that email

            $config_tables = $this->config->item('tables', 'ion_auth');

            $identity = $this->db->where('email', $this->input->post('email'))->limit('1')->get($config_tables['users'])->row();

            //run the forgotten password method to email an activation code to the user

            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

            if ($forgotten) {

                //if there were no errors

                $this->session->set_flashdata('message', $this->ion_auth->messages());

                redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page

            } else {

                $this->session->set_flashdata('message', $this->ion_auth->errors());

                redirect("auth/forgot_password", 'refresh');

            }

        }

    }

    //reset password - final step for forgotten password

    public function reset_password($code = null)
    {

        if (!$code) {

            show_404();

        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {

            //if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');

            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() == false) {

                //display the form

                //set the flash data error message if there is one

                $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');

                $this->data['new_password'] = array(

                    'name' => 'new',

                    'id' => 'new',

                    'type' => 'password',

                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',

                );

                $this->data['new_password_confirm'] = array(

                    'name' => 'new_confirm',

                    'id' => 'new_confirm',

                    'type' => 'password',

                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',

                );

                $this->data['user_id'] = array(

                    'name' => 'user_id',

                    'id' => 'user_id',

                    'type' => 'hidden',

                    'value' => $user->id,

                );

                $this->data['csrf'] = $this->_get_csrf_nonce();

                $this->data['code'] = $code;

                //render

                $this->_render_page('auth/reset_password', $this->data);

            } else {

                // do we have a valid request?

                if ($this->_valid_csrf_nonce() === false || $user->id != $this->input->post('user_id')) {

                    //something fishy might be up

                    $this->ion_auth->clear_forgotten_password_code($code);

                    show_error($this->lang->line('error_csrf'));

                } else {

                    // finally change the password

                    $identity = $user->{$this->config->item('identity', 'ion_auth')};

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {

                        //if the password was successfully changed

                        $this->session->set_flashdata('message', $this->ion_auth->messages());

                        $this->logout();

                    } else {

                        $this->session->set_flashdata('message', $this->ion_auth->errors());

                        redirect('auth/reset_password/' . $code, 'refresh');

                    }

                }

            }

        } else {

            //if the code is invalid then send them back to the forgot password page

            $this->session->set_flashdata('message', $this->ion_auth->errors());

            redirect("auth/forgot_password", 'refresh');

        }

    }

    public function _create_db($id, $pass)
    {

        $username_multi = $this->config->item('multi_tenant_user');

        $clave_multi = $this->config->item('multi_tenant_pass');

        $servidor_multi = $this->config->item('multi_tenant_host');

        $conn = @mysql_connect($servidor_multi, $username_multi, $clave_multi);

        if (!$conn) {

            $this->session->set_flashdata('message', "No ha sido completado su registro, Intente mas tarde");

        } else {

            $uid = uniqid();

            $hoy = date("m_d");

            $database_name = "vendty2_db_" . $pass . "_" . $hoy;

            $sql = "CREATE DATABASE $database_name";

            if (mysql_query($sql, $conn)) {

                mysql_select_db($database_name, $conn);

                $sql_almacen = "CREATE TABLE IF NOT EXISTS `almacen` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `nombre` varchar(254) DEFAULT NULL,

                            `direccion` text,

                            `meta_diaria` float DEFAULT NULL,

                            `prefijo` varchar(254) DEFAULT NULL,

                            `consecutivo` int(10) unsigned DEFAULT NULL,

                            `activo` tinyint(1) DEFAULT '1',

                            `telefono` varchar(20) NOT NULL,

                            PRIMARY KEY (`id`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $sql_categoria = "CREATE TABLE IF NOT EXISTS `categoria` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `codigo` int(11) DEFAULT NULL,

                            `nombre` varchar(254) DEFAULT NULL,

                            `imagen` varchar(254) DEFAULT NULL,

                            `padre` varchar(254) DEFAULT NULL,

                            `activo` tinyint(1) DEFAULT '1',

                            PRIMARY KEY (`id`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $categoria_query = "INSERT INTO `categoria` (`id`, `codigo`, `nombre`, `imagen`, `padre`, `activo`) VALUES

                            (2, 0000000, 'General', '', NULL, 1);";

                $sql_clientes = "CREATE TABLE IF NOT EXISTS `clientes` (

                            `id_cliente` int(11) NOT NULL AUTO_INCREMENT,

                            `pais` varchar(254) NOT NULL,

                            `provincia` varchar(254) DEFAULT NULL,

                            `nombre_comercial` varchar(100) DEFAULT NULL,

                            `razon_social` varchar(100) DEFAULT NULL,

                            `nif_cif` varchar(15) DEFAULT NULL,

                            `contacto` varchar(100) DEFAULT NULL,

                            `pagina_web` varchar(150) DEFAULT NULL,

                            `email` varchar(80) DEFAULT NULL,

                            `poblacion` varchar(80) DEFAULT NULL,

                            `direccion` text,

                            `cp` varchar(5) CHARACTER SET latin1 DEFAULT NULL,

                            `telefono` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                            `movil` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                            `fax` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                            `tipo_empresa` varchar(80) DEFAULT NULL,

                            `entidad_bancaria` varchar(100) DEFAULT NULL,

                            `numero_cuenta` varchar(50) CHARACTER SET latin1 DEFAULT NULL,

                            `observaciones` text,

                            PRIMARY KEY (`id_cliente`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;



                                ";

                /* $sql_servicios = "CREATE TABLE IF NOT EXISTS `servicios` (

                `id_servicio` int(11) NOT NULL AUTO_INCREMENT,

                `nombre` varchar(254) NOT NULL,

                `codigo` varchar(254) DEFAULT NULL,

                `descripcion` text NOT NULL,

                `precio` float(10,2) NOT NULL,

                `id_impuesto` int(11) NOT NULL,

                PRIMARY KEY (`id_servicio`),

                KEY `servicios_FK1` (`id_impuesto`)

                ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;";*/

                $sql_detalle_venta = "CREATE TABLE IF NOT EXISTS `detalle_venta` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `venta_id` int(11) NOT NULL,

                            `codigo_producto` varchar(15) DEFAULT NULL,

                            `nombre_producto` varchar(254) DEFAULT NULL,

                            `unidades` int(11) DEFAULT NULL,

                            `precio_venta` float DEFAULT NULL,

                            `descuento` float DEFAULT NULL,

                            `impuesto` float DEFAULT NULL,

                            `linea` varchar(254) DEFAULT NULL,

                            `margen_utilidad` float DEFAULT NULL,

                            `activo` tinyint(1) DEFAULT '1',

                            PRIMARY KEY (`id`),

                            KEY `detalle_venta_FKIndex1` (`venta_id`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $sql_productos = "CREATE TABLE IF NOT EXISTS `producto` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `categoria_id` int(11) NOT NULL,

                            `codigo` varchar(15) DEFAULT NULL,

                            `nombre` varchar(254) DEFAULT NULL,

                            `codigo_barra` varchar(254) DEFAULT NULL,

                            `precio_compra` float DEFAULT NULL,

                            `precio_venta` float DEFAULT NULL,

                            `stock_minimo` int(11) DEFAULT NULL,

                            `descripcion` text,

                            `activo` tinyint(1) DEFAULT '1',

                            `impuesto` float DEFAULT NULL,

                            `fecha` date DEFAULT NULL,

                            `imagen` varchar(254) DEFAULT NULL,

                            PRIMARY KEY (`id`),

                            KEY `producto_FKIndex1` (`categoria_id`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $sql_impuestos = "CREATE TABLE IF NOT EXISTS `impuesto` (

                            `id_impuesto` int(11) NOT NULL AUTO_INCREMENT,

                            `nombre_impuesto` varchar(254) DEFAULT NULL,

                            `porciento` int(11) DEFAULT NULL,

                            PRIMARY KEY (`id_impuesto`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $insert_impusto = "INSERT INTO `impuesto` (`id_impuesto`, `nombre_impuesto`, `porciento`) VALUES (NULL, 'Sin Impuesto', '0');";

                $sql_opciones = "CREATE TABLE IF NOT EXISTS `opciones` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `nombre_opcion` varchar(254) NOT NULL DEFAULT '',

                            `valor_opcion` text NOT NULL,

                            PRIMARY KEY (`id`,`nombre_opcion`)

                          ) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=30 ;";

                $sql_nothing_tax = "INSERT INTO `impuestos` (`id_impuesto` ,`nombre_impuesto` ,`porciento`) VALUES (NULL , 'Ninguno', '0');";

                $sql_pagos = "CREATE TABLE IF NOT EXISTS `pagos` (

                            `id_pago` int(11) NOT NULL AUTO_INCREMENT,

                            `id_factura` int(11) NOT NULL,

                            `fecha_pago` date NOT NULL,

                            `cantidad` float(10,2) NOT NULL,

                            `tipo` varchar(254) NOT NULL,

                            `notas` text NOT NULL,

                            `importe_retencion` float(10,2) DEFAULT NULL,

                            PRIMARY KEY (`id_pago`),

                            KEY `pagos_FK1` (`id_factura`)

                            ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $sql_proveedores = "CREATE TABLE IF NOT EXISTS `proveedores` (

                            `id_proveedor` int(11) NOT NULL AUTO_INCREMENT,

                            `pais` varchar(254) NOT NULL,

                            `provincia` varchar(254) DEFAULT NULL,

                            `nombre_comercial` varchar(100) DEFAULT NULL,

                            `razon_social` varchar(100) DEFAULT NULL,

                            `nif_cif` varchar(15) DEFAULT NULL,

                            `contacto` varchar(100) DEFAULT NULL,

                            `pagina_web` varchar(150) DEFAULT NULL,

                            `email` varchar(80) DEFAULT NULL,

                            `poblacion` varchar(80) DEFAULT NULL,

                            `direccion` text,

                            `cp` varchar(5) CHARACTER SET latin1 DEFAULT NULL,

                            `telefono` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                            `movil` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                            `fax` varchar(10) CHARACTER SET latin1 DEFAULT NULL,

                            `tipo_empresa` varchar(80) DEFAULT NULL,

                            `entidad_bancaria` varchar(100) DEFAULT NULL,

                            `numero_cuenta` varchar(50) CHARACTER SET latin1 DEFAULT NULL,

                            `observaciones` text,

                            PRIMARY KEY (`id_proveedor`)

                            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

                $sql_proformas = "CREATE TABLE IF NOT EXISTS `proformas` (

                            `id_proforma` int(11) NOT NULL AUTO_INCREMENT,

                            `id_proveedor` int(11) NOT NULL,

                            `descripcion` varchar(254) NOT NULL,

                            `cantidad` float NOT NULL,

                            `valor` float NOT NULL,

                            `notas` text NOT NULL,

                            `fecha` date NOT NULL,

                            `id_impuesto` int(1) NOT NULL,

                            PRIMARY KEY (`id_proforma`),

                            KEY `proformas_FK1` (`id_impuesto`),

                            KEY `proformas_FK2` (`id_proveedor`)

                            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

                $sql_presupuestos = "CREATE TABLE IF NOT EXISTS `presupuestos` (

                              `id_presupuesto` int(11) NOT NULL AUTO_INCREMENT,

                              `id_cliente` int(11) NOT NULL,

                              `numero` varchar(10) CHARACTER SET latin1 NOT NULL,

                              `monto` float(10,2) NOT NULL,

                              `monto_siva` float(10,2) NOT NULL,

                              `monto_iva` float(10,2) NOT NULL,

                              `fecha` date NOT NULL,

                              PRIMARY KEY (`id_presupuesto`),

                              KEY `presupuestos_FK1` (`id_cliente`)

                            ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

                $sql_presupuestos_detalles = "CREATE TABLE IF NOT EXISTS `presupuestos_detalles` (

                            `id_presupuesto_detalle` int(11) NOT NULL AUTO_INCREMENT,

                            `id_presupuesto` int(11) NOT NULL,

                            `precio` float(10,2) NOT NULL,

                            `cantidad` int(11) NOT NULL,

                            `impuesto` float(10,2) NOT NULL,

                            `fk_id_producto` int(11) NOT NULL,

                            `descuento` float NOT NULL,

                            `descripcion_d` text NOT NULL,

                            PRIMARY KEY (`id_presupuesto_detalle`),

                            KEY `presupuestos_detalles_FK1` (`id_presupuesto`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

                $sql_facturas = "CREATE TABLE IF NOT EXISTS `facturas` (

                            `id_factura` int(11) NOT NULL AUTO_INCREMENT,

                            `id_cliente` int(11) NOT NULL,

                            `numero` varchar(10) CHARACTER SET latin1 NOT NULL,

                            `monto` float(10,2) NOT NULL,

                            `monto_siva` float(10,2) NOT NULL,

                            `monto_iva` float(10,2) NOT NULL,

                            `fecha` date NOT NULL,

                            `fecha_v` date DEFAULT NULL,

                            `estado` int(1) NOT NULL,

                            PRIMARY KEY (`id_factura`),

                            KEY `facturas_FK1` (`id_cliente`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";

                $sql_facturas_detalles = "CREATE TABLE IF NOT EXISTS `facturas_detalles` (

                            `id_factura_detalle` int(11) NOT NULL AUTO_INCREMENT,

                            `id_factura` int(11) NOT NULL,

                            `precio` float(10,2) NOT NULL,

                            `cantidad` int(11) NOT NULL,

                            `impuesto` float(10,2) NOT NULL,

                            `descuento` float NOT NULL,

                            `fk_id_producto` int(11) NOT NULL,

                            `descripcion_d` text NOT NULL,

                            PRIMARY KEY (`id_factura_detalle`),

                            KEY `facturas_detalles_FK1` (`id_factura`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;";

                $stock_actual = "CREATE TABLE IF NOT EXISTS `stock_actual` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `almacen_id` int(11) DEFAULT NULL,

                            `producto_id` int(11) DEFAULT NULL,

                            `unidades` int(11) DEFAULT NULL,

                            PRIMARY KEY (`id`),

                            KEY `stok_actual_FKIndex1` (`almacen_id`),

                            KEY `producto_stock_actual_fk_idx` (`producto_id`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $stock_diario = "CREATE TABLE IF NOT EXISTS `stock_diario` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `producto_id` int(11) NOT NULL,

                            `almacen_id` int(11) NOT NULL,

                            `fecha` date DEFAULT NULL,

                            `razon` varchar(254) DEFAULT NULL,

                            `cod_documento` varchar(254) DEFAULT NULL,

                            `unidad` int(11) DEFAULT NULL,

                            `precio` float DEFAULT NULL,

                            `usuario` int(11) DEFAULT NULL,

                            PRIMARY KEY (`id`),

                            KEY `stock_diario_FKIndex1` (`almacen_id`),

                            KEY `stock_diario_FKIndex2` (`producto_id`)

                          ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $usuario_almacen = "CREATE TABLE IF NOT EXISTS `usuario_almacen` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `usuario_id` int(11) NOT NULL,

                            `almacen_id` int(11) NOT NULL,

                            PRIMARY KEY (`id`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;";

                $vendedor = "CREATE TABLE IF NOT EXISTS `vendedor` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `nombre` varchar(254) NOT NULL,

                            `cedula` varchar(15) NOT NULL,

                            `email` varchar(254) NOT NULL,

                            `telefono` varchar(20) NOT NULL,

                            PRIMARY KEY (`id`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $forma_pago = "CREATE TABLE IF NOT EXISTS `forma_pago` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `codigo` varchar(15) DEFAULT NULL,

                            `nombre` varchar(254) DEFAULT NULL,

                            `activo` tinyint(1) DEFAULT '1',

                            PRIMARY KEY (`id`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $venta = "CREATE TABLE IF NOT EXISTS `venta` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `almacen_id` int(11) DEFAULT NULL,

                            `forma_pago_id` int(11) DEFAULT NULL,

                            `factura` varchar(254) DEFAULT NULL,

                            `fecha` datetime DEFAULT NULL,

                            `usuario_id` int(11) DEFAULT NULL,

                            `cliente_id` int(11) DEFAULT NULL,

                            `vendedor` int(11) DEFAULT NULL,

                            `cambio` varchar(254) DEFAULT NULL,

                            `activo` tinyint(1) DEFAULT '1',

                            `total_venta` float NOT NULL,

                            PRIMARY KEY (`id`),

                            KEY `venta_FKIndex1` (`forma_pago_id`),

                            KEY `venta_FKIndex2` (`almacen_id`),

                            KEY `venta_cliente_id` (`cliente_id`),

                            KEY `venta_vendedor_fk_idx` (`vendedor`)

                          ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $productosf = "CREATE TABLE `productosf` (

                            `id_producto` int(11) NOT NULL AUTO_INCREMENT,

                            `codigo` varchar(254) NOT NULL,

                            `descripcion` text NOT NULL,

                            `nombre` varchar(254) NOT NULL,

                            `id_impuesto` int(11) NOT NULL,

                            `precio` float DEFAULT NULL,

                            `precio_compra` float DEFAULT NULL,

                            PRIMARY KEY (`id_producto`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $ventas_pagos = "CREATE TABLE `ventas_pago` (
                    `id_pago` int(11) NOT NULL AUTO_INCREMENT,
                    `id_venta` int(11) NOT NULL,
                    `forma_pago` varchar(254) NOT NULL,
                    `valor_entregado` float NOT NULL,
                    `cambio` float NOT NULL,
                    `transaccion` varchar(25) DEFAULT NULL,
                    PRIMARY KEY (`id_pago`)
                ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $rol = "CREATE TABLE `rol` (

                            `id_rol` int(11) NOT NULL AUTO_INCREMENT,

                            `nombre_rol` varchar(254) NOT NULL,

                            `descripcion` text NOT NULL,

                            PRIMARY KEY (`id_rol`)

                          ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $rol_permisos = "CREATE TABLE `permiso_rol` (

                            `id_permiso_rol` int(11) NOT NULL AUTO_INCREMENT,

                            `id_permiso` int(11) NOT NULL,

                            `id_rol` int(11) NOT NULL,

                            PRIMARY KEY (`id_permiso_rol`)

                          ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $online_venta = "CREATE TABLE IF NOT EXISTS `online_venta` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `nombre` varchar(100) NOT NULL,

                            `nombre2` varchar(100) DEFAULT NULL,

                            `apellidos` varchar(100) NOT NULL,

                            `dni` varchar(15) NOT NULL,

                            `telefono` varchar(30) DEFAULT NULL,

                            `movil` varchar(30) DEFAULT NULL,

                            `fax` varchar(100) DEFAULT NULL,

                            `email` varchar(100) NOT NULL,

                            `cpostal` varchar(10) NOT NULL,

                            `direccion` text NOT NULL,

                            `notas` text,

                            `fecha` datetime NOT NULL,

                            `sub_total` int(20) NOT NULL,

                            `tasa_impuesto` int(20) NOT NULL,

                            `estado` int(11) NOT NULL,

                            `origen` varchar(30) DEFAULT 'tienda',

                            `almacen_id` int(11) DEFAULT '1',

                            PRIMARY KEY (`id`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                $online_venta_prod = "CREATE TABLE IF NOT EXISTS `online_venta_prod` (

                            `id` int(11) NOT NULL AUTO_INCREMENT,

                            `id_venta` int(11) NOT NULL,

                            `id_producto` int(11) NOT NULL,

                            `descripcion` text NOT NULL,

                            `precio` int(20) DEFAULT NULL,

                            `cantidad` int(20) DEFAULT NULL,

                            `total` int(20) DEFAULT NULL,

                            PRIMARY KEY (`id`)

                          ) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;";

                /*

                CREATE TABLE `permisos` (

                `id_permiso` int(11) NOT NULL AUTO_INCREMENT,

                `nombre_permiso` varchar(254) NOT NULL,

                `url` varchar(254) NOT NULL,

                PRIMARY KEY (`id_permiso`)

                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

                -- --------------------------------------------------------

                --

                -- Estructura de tabla para la tabla `permiso_rol`

                --

                CREATE TABLE `permiso_rol` (

                `id_permiso_rol` int(11) NOT NULL AUTO_INCREMENT,

                `id_permiso` int(11) NOT NULL,

                `id_rol` int(11) NOT NULL,

                PRIMARY KEY (`id_permiso_rol`)

                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

                -- --------------------------------------------------------

                --

                -- Estructura de tabla para la tabla `rol`

                --

                CREATE TABLE `rol` (

                `id_rol` int(11) NOT NULL AUTO_INCREMENT,

                `nombre_rol` varchar(254) NOT NULL,

                `descripcion` text NOT NULL,

                PRIMARY KEY (`id_rol`)

                ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

                 */

                $filtros_facturas = "ALTER TABLE `facturas`

                                ADD CONSTRAINT `facturas_FK1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_facturas_detalles = "ALTER TABLE `facturas_detalles`

                                ADD CONSTRAINT `facturas_detalles_FK1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id_factura`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_pagos = "ALTER TABLE `pagos`

                                ADD CONSTRAINT `pagos_FK1` FOREIGN KEY (`id_factura`) REFERENCES `facturas` (`id_factura`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_presupuestos = "ALTER TABLE `presupuestos`

                                ADD CONSTRAINT `presupuestos_FK1` FOREIGN KEY (`id_cliente`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_detalles = "ALTER TABLE `presupuestos_detalles`

                                ADD CONSTRAINT `presupuestos_detalles_FK1` FOREIGN KEY (`id_presupuesto`) REFERENCES `presupuestos` (`id_presupuesto`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_productos = "ALTER TABLE `producto`

                                 ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_proformas = "ALTER TABLE `proformas`

                                ADD CONSTRAINT `proformas_FK2` FOREIGN KEY (`id_proveedor`) REFERENCES `proveedores` (`id_proveedor`) ON DELETE CASCADE ON UPDATE CASCADE,

                                ADD CONSTRAINT `proformas_FK1` FOREIGN KEY (`id_impuesto`) REFERENCES `impuestos` (`id_impuesto`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_stock_actual = "ALTER TABLE `stock_actual`

                                ADD CONSTRAINT `producto_stock_actual_fk` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,

                                ADD CONSTRAINT `almacen_id_fk` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_stock_diario = "ALTER TABLE `stock_diario`

                                ADD CONSTRAINT `almacen_fk_stock_actual` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,

                                ADD CONSTRAINT `stock_diario_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `producto` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_ventas = "ALTER TABLE `venta`

                                ADD CONSTRAINT `venta_vendedor_fk` FOREIGN KEY (`vendedor`) REFERENCES `vendedor` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,

                                ADD CONSTRAINT `venta_almacen_fk` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,

                                ADD CONSTRAINT `venta_cliente_id` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE ON UPDATE CASCADE,

                                ADD CONSTRAINT `venta_forma_pago` FOREIGN KEY (`forma_pago_id`) REFERENCES `forma_pago` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;";

                $filtros_usuario_almacen = "ALTER TABLE `usuario_almacen`

                                ADD CONSTRAINT `usuario_almacen_fk` FOREIGN KEY (`almacen_id`) REFERENCES `almacen` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;";

                $filtro_insert_almacen = "INSERT INTO `almacen` (`id`, `nombre`, `direccion`, `prefijo`, `consecutivo`, `activo`, `telefono`, `meta_diaria`) VALUES (NULL, 'General', NULL, 'G', '1', '1', '', NULL);";

                $filtro_insert_usuario_almacen = "INSERT INTO `usuario_almacen` (`id`, `usuario_id`, `almacen_id`) VALUES (NULL, '$id', '1');";

                $insert_opciones = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES

                                (1, 'nombre_empresa', ''),

                                (2, 'resolucion_factura', ''),

                                (3, 'logotipo_empresa', ''),

                                (4, 'contacto_empresa', ''),

                                (5, 'email_empresa', ''),

                                (6, 'direccion_empresa', ''),

                                (7, 'telefono_empresa', ''),

                                (8, 'fax_empresa', ''),

                                (9, 'web_empresa', ''),

                                (17, 'moneda_empresa', 'USD'),

                                (20, 'plantilla_empresa', 'default'),

                                (21, 'paypal_email', ''),

                                (22, 'cabecera_factura', ''),

                                (23, 'terminos_condiciones', ''),

                                (24, 'prefijo_presupuesto', 'P'),

                                (25, 'numero_presupuesto', '1'),

                                (26, 'numero_factura', '1'),

                                (27, 'prefijo_factura', 'F'),

                                (28, 'last_numero_factura', '1'),

                                (29, 'last_numero_presupuesto', '1'),

                                (30, 'nit', ''),

                                (31, 'titulo_venta', ''),

                                (32, 'sistema', 'Pos');



";

                mysql_query($sql_almacen, $conn);

                mysql_query($sql_categoria, $conn);

                mysql_query($categoria_query, $conn);

                mysql_query($sql_clientes, $conn);

                mysql_query($sql_detalle_venta, $conn);

                mysql_query($sql_productos, $conn);

                mysql_query($productosf, $conn);

                mysql_query($sql_impuestos, $conn);

                mysql_query($insert_impusto, $conn);

                mysql_query($online_venta, $conn);

                mysql_query($online_venta_prod, $conn);

                mysql_query($sql_nothing_tax, $conn);

                mysql_query($sql_opciones, $conn);

                mysql_query($sql_pagos, $conn);

                mysql_query($sql_proveedores, $conn);

                mysql_query($sql_proformas, $conn);

                mysql_query($sql_presupuestos, $conn);

                mysql_query($sql_presupuestos_detalles, $conn);

                mysql_query($sql_facturas, $conn);

                mysql_query($sql_facturas_detalles, $conn);

                mysql_query($stock_actual, $conn);

                mysql_query($stock_diario, $conn);

                mysql_query($usuario_almacen, $conn);

                mysql_query($vendedor, $conn);

                mysql_query($forma_pago, $conn);

                mysql_query($venta, $conn);

                mysql_query($ventas_pagos, $conn);

                mysql_query($rol, $conn);

                mysql_query($rol_permisos, $conn);

                mysql_query($filtros_facturas, $conn);

                mysql_query($filtros_facturas_detalles, $conn);

                mysql_query($filtros_pagos, $conn);

                mysql_query($filtros_presupuestos, $conn);

                mysql_query($filtros_detalles, $conn);

                mysql_query($filtros_productos, $conn);

                mysql_query($filtros_proformas, $conn);

                mysql_query($filtros_stock_actual, $conn);

                mysql_query($filtros_stock_diario, $conn);

                mysql_query($filtros_ventas, $conn);

                mysql_query($filtros_usuario_almacen, $conn);

                mysql_query($filtro_insert_almacen, $conn);

                mysql_query($filtro_insert_usuario_almacen, $conn);

                mysql_query($insert_opciones, $conn);

                /*----------------------------------------------------------------/*

                | Julian 30/07/2014                                               |

                /*-----------------------------------------------------------------

                /*Nueva campo en tabla clientes*/

                $clientes_grupo_id = "ALTER TABLE `clientes` ADD COLUMN `grupo_clientes_id` integer not null default 1";

                mysql_query($clientes_grupo_id, $conn);

                /*Cliente general*/

                $insert_cliente_general = "INSERT INTO `clientes` (`id_cliente`,`pais`, `nombre_comercial`, `nif_cif`, `grupo_clientes_id`) VALUES ('-1','Colombia', 'general', '0', '1')";

                mysql_query($insert_cliente_general, $conn);

                /*Nueva tabla grupo de clientes*/

                $grupo_clientes = "CREATE TABLE `grupo_clientes` (

                      `id` int(11) NOT NULL AUTO_INCREMENT,

                      `nombre` VARCHAR(15) NOT NULL DEFAULT 'Unknown',

                      PRIMARY KEY (`id`)

                      /*foreign key (id) references producto(id)*/

                    ) ENGINE=InnoDB DEFAULT CHARSET=latin1";

                mysql_query($grupo_clientes, $conn);

                /*Sin grupo*/

                $sin_grupo = "INSERT INTO `grupo_clientes` VALUES (1,'sin grupo')";

                mysql_query($sin_grupo, $conn);

                /*Estado de venta Anulada - activa*/

                $venta_estado = "ALTER TABLE `venta` ADD COLUMN `estado` INT NULL DEFAULT 0 AFTER `total_venta`";

                mysql_query($venta_estado, $conn);

                /*Lista precios*/

                $lista_precios = "CREATE TABLE `lista_precios` (

                        `id` INT NOT NULL AUTO_INCREMENT,

                        `nombre` VARCHAR(45) NULL,

                        `grupo_cliente_id` INT NULL,

                        `almacen_id` INT NULL,

                        `start` DATE NULL,

                        `end` DATE NULL,

                        PRIMARY KEY (`id`),

                        foreign key (grupo_cliente_id) references grupo_clientes(id),

                        foreign key (almacen_id) references almacen(id)

                    );";

                mysql_query($lista_precios, $conn);

                /*Lista detalle precios*/

                $lista_detalle_precios = "CREATE TABLE `lista_detalle_precios`(

                        `id` INT NOT NULL AUTO_INCREMENT,

                        `id_producto` INT NULL,

                        `id_impuesto` INT NULL,

                        `id_lista_precios` INT NULL,

                        `precio` float DEFAULT NULL,

                        PRIMARY KEY (`id`)

                    );";

                mysql_query($lista_detalle_precios, $conn);

                /*Tabla anuladas*/

                $ventas_anuladas = "CREATE TABLE `ventas_anuladas` (

                      `id_venta_anulada` int(11) NOT NULL AUTO_INCREMENT,

                      `usuario_id` int(11) NOT NULL,

                      `fecha` datetime NOT NULL,

                      `motivo` text NOT NULL,

                      `venta_id` int(11) NOT NULL,

                      PRIMARY KEY (`id_venta_anulada`)

                    ) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8";

                mysql_query($ventas_anuladas, $conn);

                /*Comision en vendedor*/

                $comision_vendedor = "ALTER TABLE vendedor ADD `comision` int(11) NOT NULL DEFAULT '0'";

                mysql_query($comision_vendedor, $conn);

                /*Movimiento detalle*/

                $movimiento_detalle = "CREATE TABLE `movimiento_detalle` (

                        `id_detalle` int(11) NOT NULL AUTO_INCREMENT,

                        `id_inventario` int(11) NOT NULL,

                        `codigo_barra` varchar(254) NOT NULL,

                        `cantidad` int(11) NOT NULL,

                        `precio_compra` int(11) NOT NULL,

                        `existencias` int(11) NOT NULL,

                        `nombre` varchar(254) NOT NULL,

                        `total_inventario` int(11) NOT NULL,

                        PRIMARY KEY (`id_detalle`)

                    ) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=latin1;";

                mysql_query($movimiento_detalle, $conn);

                /*Movimiento inventario*/

                $movimiento_inventario = "CREATE TABLE `movimiento_inventario` (

                        `id` int(11) NOT NULL AUTO_INCREMENT,

                        `fecha` datetime NOT NULL,

                        `almacen_id` int(11) NOT NULL,

                        `almacen_traslado_id` int(11) DEFAULT NULL,

                        `tipo_movimiento` varchar(254) NOT NULL,

                        `codigo_factura` varchar(254) DEFAULT NULL,

                        `user_id` int(11) NOT NULL,

                        `total_inventario` int(11) NOT NULL,

                        `proveedor_id` int(11) DEFAULT NULL,

                        PRIMARY KEY (`id`)

                    ) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=latin1";

                mysql_query($movimiento_inventario, $conn);

                $plantilla_cotizacion = "INSERT INTO `opciones` (`nombre_opcion`, `valor_opcion`) VALUES ('plantilla_cotizacion', 'Estandar');";

                mysql_query($plantilla_cotizacion, $conn);

                /*INGREDIENTES =====================================================================================================*/

                $producto = "ALTER TABLE `producto`

                    ADD COLUMN `material` TINYINT(1) NULL DEFAULT '0' AFTER `imagen`,

                    ADD COLUMN `ingredientes` TINYINT(1) NULL DEFAULT '0' AFTER `material`,

                    ADD COLUMN `unidad_id` INT NULL DEFAULT '1' AFTER `ingredientes`;";

                mysql_query($producto, $conn);

                $producto_ingredientes = "CREATE TABLE `producto_ingredientes` (

                      `id` int(11) NOT NULL AUTO_INCREMENT,

                      `id_producto` int(11) DEFAULT NULL,

                      `id_ingrediente` int(11) DEFAULT NULL,

                      `cantidad` int(11) DEFAULT NULL,

                      PRIMARY KEY (`id`)

                    ) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=latin1;";

                mysql_query($producto_ingredientes, $conn);

                $unidades = "CREATE TABLE `unidades` (

                      `id` int(11) NOT NULL AUTO_INCREMENT,

                      `nombre` varchar(45) DEFAULT NULL,

                      PRIMARY KEY (`id`)

                    ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;";

                mysql_query($unidades, $conn);

                $unidades_default = "INSERT INTO `unidades` VALUES (1,'unidad'),(2,'gramo'),(3,'kilogramo'),(4,'libra'),(5,'litro'),(6,'mililitro'),(7,'onza');";

                mysql_query($unidades_default, $conn);

                /*....................................................................................................................*/

                /*Factura estandar y clasica */

                $tipo_factura = "INSERT INTO `opciones` (`nombre_opcion`, `valor_opcion`) VALUES ('tipo_factura', 'estandar');";

                mysql_query($tipo_factura, $conn);

                $tipo_factura_venta = "ALTER TABLE `venta` ADD COLUMN `tipo_factura` VARCHAR(10) NULL DEFAULT 'estandar' AFTER `estado`;";

                mysql_query($tipo_factura_venta, $conn);

                $venta_fecha_vencimiento = "ALTER TABLE `venta` ADD COLUMN `fecha_vencimiento` DATETIME NULL AFTER `tipo_factura`;";

                mysql_query($venta_fecha_vencimiento, $conn);

                /*Tipo producto*/

                $producto_tipo = "CREATE TABLE `producto_tipo` (

                      `id` INT NOT NULL ,

                      `nombre` VARCHAR(45) NULL,

                      PRIMARY KEY (`id`)

                    );";

                mysql_query($producto_tipo, $conn);

                $producto_tipo_AI = "ALTER TABLE `producto_tipo` CHANGE COLUMN `id` `id` INT(11) NOT NULL AUTO_INCREMENT ;";

                mysql_query($producto_tipo_AI, $conn);

                $insert_producto_tipo = "INSERT INTO `producto_tipo` (`nombre`) VALUES ('unico');";

                mysql_query($insert_producto_tipo, $conn);

                $insert_producto_tipo = "INSERT INTO `producto_tipo` (`nombre`) VALUES ('compuesto');";

                mysql_query($insert_producto_tipo, $conn);

                $insert_producto_tipo = "INSERT INTO `producto_tipo` (`nombre`) VALUES ('combo');";

                mysql_query($insert_producto_tipo, $conn);

                /*COMBO*/

                $alter_producto = "ALTER TABLE `producto` ADD COLUMN `combo` INT(11) NULL DEFAULT '0' AFTER `ingredientes`;";

                mysql_query($alter_producto, $conn);

                $producto_combos = "CREATE TABLE `producto_combos` (

                    `id` INT NOT NULL AUTO_INCREMENT,

                    `id_combo` INT(11) NULL DEFAULT NULL,

                    `id_producto` INT(11) NULL DEFAULT NULL,

                    `cantidad` INT(11) NULL DEFAULT NULL,

                    PRIMARY KEY (`id`));";

                mysql_query($producto_combos, $conn);

                $alter_producto = "ALTER TABLE `producto`

                      ADD COLUMN `combo` TINYINT(1) NULL DEFAULT '0' AFTER `material`;

                    ";

                mysql_query($alter_producto, $conn);

                //Pago servicios

                $pago = "CREATE TABLE `pago` (

                      `id_pago` int(11) NOT NULL AUTO_INCREMENT,

                      `id_factura` int(11) DEFAULT NULL,

                      `fecha_pago` date NOT NULL,

                      `cantidad` float(10,2) NOT NULL,

                      `tipo` varchar(254) NOT NULL,

                      `notas` text NOT NULL,

                      `importe_retencion` float(10,2) DEFAULT NULL,

                      PRIMARY KEY (`id_pago`)

                    ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;";

                mysql_query($pago, $conn);

                $alter_venta = "ALTER TABLE `venta`

                    ADD COLUMN `tipo_factura` VARCHAR(45) NULL DEFAULT 'estandar' AFTER `estado`,

                    ADD COLUMN `fecha_vencimiento` VARCHAR(45) NULL DEFAULT NULL AFTER `tipo_factura`;";

                mysql_query($alter_venta, $conn);

                $comision = "ALTER TABLE `vendedor`

                    CHANGE COLUMN `comision` `comision` FLOAT NULL DEFAULT 0 ";

                mysql_query($comision, $conn);

                $comision = "ALTER TABLE `detalle_venta`

                    ADD COLUMN `descripcion_producto` TEXT NULL AFTER `nombre_producto`;";

                mysql_query($comision, $conn);

                $detalle_orden_compra = "

                        CREATE TABLE `detalle_orden_compra` (

                          `id` int(11) NOT NULL AUTO_INCREMENT,

                          `venta_id` int(11) NOT NULL,

                          `codigo_producto` varchar(15) DEFAULT NULL,

                          `nombre_producto` varchar(254) DEFAULT NULL,

                          `descripcion_producto` text,

                          `unidades` int(11) DEFAULT NULL,

                          `precio_venta` float DEFAULT NULL,

                          `descuento` float DEFAULT NULL,

                          `impuesto` float DEFAULT NULL,

                          `impuesto_id` int(11) DEFAULT NULL,

                          `linea` varchar(254) DEFAULT NULL,

                          `margen_utilidad` float DEFAULT NULL,

                          `activo` tinyint(1) DEFAULT '1',

                          `id_unidad` int(11) DEFAULT NULL,

                          PRIMARY KEY (`id`),

                          KEY `detalle_venta_FKIndex1` (`venta_id`)

                        ) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

                      ";

                mysql_query($detalle_orden_compra, $conn);

                $orden_compra = "

                        CREATE TABLE `orden_compra` (

                          `id` int(11) NOT NULL AUTO_INCREMENT,

                          `almacen_id` int(11) DEFAULT NULL,

                          `forma_pago_id` int(11) DEFAULT NULL,

                          `factura` varchar(254) DEFAULT NULL,

                          `fecha` datetime DEFAULT NULL,

                          `usuario_id` int(11) DEFAULT NULL,

                          `cliente_id` int(11) DEFAULT NULL,

                          `vendedor` int(11) DEFAULT NULL,

                          `cambio` varchar(254) DEFAULT NULL,

                          `activo` tinyint(1) DEFAULT '1',

                          `total_venta` float NOT NULL,

                          `estado` int(11) DEFAULT '0',

                          `tipo_factura` varchar(30) DEFAULT 'estandar',

                          `fecha_vencimiento` date DEFAULT NULL,

                          PRIMARY KEY (`id`),

                          KEY `venta_FKIndex1` (`forma_pago_id`),

                          KEY `venta_FKIndex2` (`almacen_id`),

                          KEY `venta_cliente_id` (`cliente_id`),

                          KEY `venta_vendedor_fk_idx` (`vendedor`)

                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

                      ";

                mysql_query($orden_compra, $conn);

                $pago_orden_compra = "

                        CREATE TABLE `pago_orden_compra` (

                          `id_pago` int(11) NOT NULL AUTO_INCREMENT,

                          `id_factura` int(11) DEFAULT NULL,

                          `fecha_pago` date NOT NULL,

                          `cantidad` int(30) NOT NULL,

                          `tipo` varchar(254) NOT NULL,

                          `notas` text NOT NULL,

                          `importe_retencion` float(10,2) DEFAULT NULL,

                          PRIMARY KEY (`id_pago`)

                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

                      ";

                mysql_query($pago_orden_compra, $conn);

                $cambios1 = "ALTER TABLE `venta` ADD `nota` TEXT NOT NULL;";

                mysql_query($cambios1, $conn);

                $cambios2 = "ALTER TABLE `proformas` ADD `id_almacen` INT(30) NOT NULL;	";

                mysql_query($cambios2, $conn);

                $cambios3 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (35, 'numero', 'no');";

                mysql_query($cambios3, $conn);

                $cambios4 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (36, 'sobrecosto', 'no');";
                mysql_query($cambios4, $conn);

                $cambios5 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (37, 'multiples_formas_pago', 'si');	";
                mysql_query($cambios5, $conn);

                $cambios6 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (38, 'vendedor_impresion', '1'); ";
                mysql_query($cambios6, $conn);

                $cambios7 = "ALTER TABLE `proformas` ADD `forma_pago` VARCHAR(150) NOT NULL ; ";
                mysql_query($cambios7, $conn);

                $cambios8 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (39, 'valor_caja', 'no'); ";
                mysql_query($cambios8, $conn);

                $cambios9 = "ALTER TABLE `almacen` ADD `ciudad` VARCHAR(150) NOT NULL ; ";
                mysql_query($cambios9, $conn);

                $cambios10 = "
CREATE TABLE IF NOT EXISTS `cajas` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `id_Almacen` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
                mysql_query($cambios10, $conn);

                $cambios11 = "
CREATE TABLE IF NOT EXISTS `cierres_caja` (
  `id` int(200) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora_apertura` time NOT NULL,
  `hora_cierre` time NOT NULL,
  `id_Usuario` int(100) NOT NULL,
  `id_Caja` int(200) NOT NULL,
  `id_Almacen` int(50) NOT NULL,
  `total_egresos` varchar(200) NOT NULL,
  `total_ingresos` varchar(200) NOT NULL,
  `total_cierre` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
                mysql_query($cambios11, $conn);

                $cambios12 = "
CREATE TABLE IF NOT EXISTS `cuentas_dinero` (
  `id` int(100) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `tipo_cuenta` varchar(100) NOT NULL,
  `numero` varchar(50) NOT NULL,
  `banco` varchar(100) NOT NULL,
  `tipo_bancaria` varchar(100) NOT NULL,
  `id_almacen` int(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ; ";
                mysql_query($cambios12, $conn);

                $cambios13 = "
CREATE TABLE IF NOT EXISTS `movimientos_cierre_caja` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `Id_cierre` int(200) NOT NULL,
  `hora_movimiento` time NOT NULL,
  `id_usuario` int(100) NOT NULL,
  `tipo_movimiento` varchar(100) NOT NULL,
  `valor` varchar(200) NOT NULL,
  `forma_pago` varchar(200) NOT NULL,
  `numero` varchar(100) NOT NULL,
  `id_mov_tip` int(150) NOT NULL,
  `tabla_mov` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;	";
                mysql_query($cambios13, $conn);

                $cambios14 = "ALTER TABLE `usuario_almacen` ADD `id_Caja` INT(100) NOT NULL ; ";
                mysql_query($cambios14, $conn);

                $cambios15 = "ALTER TABLE `proformas` ADD `id_cuenta_dinero` INT(100) NOT NULL ; ";
                mysql_query($cambios15, $conn);

                $cambios16 = "ALTER TABLE `producto` ADD `stock_maximo` INT(100) NOT NULL ; ";
                mysql_query($cambios16, $conn);

                $cambios17 = "ALTER TABLE `producto` ADD `fecha_vencimiento` VARCHAR(100) NOT NULL ; ";
                mysql_query($cambios17, $conn);

                $cambios18 = "ALTER TABLE `producto` ADD `ubicacion` VARCHAR(150) NOT NULL ; ";
                mysql_query($cambios18, $conn);

                $cambios19 = "ALTER TABLE `producto` ADD `ganancia` INT(50) NOT NULL ; ";
                mysql_query($cambios19, $conn);

                $cambios20 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (41, 'filtro_ciudad', 'no');";
                mysql_query($cambios20, $conn);

                $cambios21 = "CREATE TABLE `factura_espera` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `almacen_id` int(11) DEFAULT NULL,
  `forma_pago_id` int(11) DEFAULT NULL,
  `factura` varchar(254) DEFAULT NULL,
  `no_factura` int(50) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  `cliente_id` int(11) DEFAULT NULL,
  `vendedor` int(11) DEFAULT NULL,
  `cambio` varchar(254) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `total_venta` float NOT NULL,
  `estado` int(11) DEFAULT '0',
  `tipo_factura` varchar(10) DEFAULT 'estandar',
  `fecha_vencimiento` datetime DEFAULT NULL,
  `nota` text NOT NULL,
  `sobrecosto` int(80) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
                mysql_query($cambios21, $conn);

                $cambios22 = "insert  into `factura_espera`(`id`,`almacen_id`,`forma_pago_id`,`factura`,`no_factura`,`fecha`,`usuario_id`,`cliente_id`,`vendedor`,`cambio`,`activo`,`total_venta`,`estado`,`tipo_factura`,`fecha_vencimiento`,`nota`,`sobrecosto`) values (-1,0,NULL,'Venta No ',NULL,'2015-06-24 00:32:06',0,-1,NULL,NULL,1,56010,0,'estandar','2015-06-24 00:32:06','',0); ";
                mysql_query($cambios22, $conn);

                $cambios23 = "CREATE TABLE `detalle_factura_espera` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venta_id` int(11) NOT NULL,
  `codigo_producto` varchar(15) DEFAULT NULL,
  `nombre_producto` varchar(254) DEFAULT NULL,
  `descripcion_producto` text,
  `unidades` varchar(150) DEFAULT NULL,
  `precio_venta` float DEFAULT NULL,
  `descuento` float DEFAULT NULL,
  `impuesto` float DEFAULT NULL,
  `impuesto_id` int(11) DEFAULT NULL,
  `linea` varchar(254) DEFAULT NULL,
  `margen_utilidad` float DEFAULT NULL,
  `activo` tinyint(1) DEFAULT '1',
  `id_producto` int(100) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `venta_id` (`venta_id`),
  CONSTRAINT `detalle_factura_espera_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `factura_espera` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1; ";
                mysql_query($cambios23, $conn);

                $cambios24 = "INSERT INTO `opciones` (`id`, `nombre_opcion`, `valor_opcion`) VALUES (42, 'comanda', 'no');";
                mysql_query($cambios24, $conn);

                @mysql_close($conn);

                unset($conn);

                $usuario = $this->db->username;

                $clave = $this->db->password;

                $servidor = $this->db->hostname;

                $base_dato = $this->db->database;

                $conn1 = @mysql_connect($servidor, $usuario, $clave);

                if (!$conn1) {

                    $this->session->set_flashdata('message', "No ha sido completado su registro, Intente mas tarde");

                } else {

                    mysql_select_db($base_dato, $conn1);

                    mysql_query("INSERT INTO `db_config` (`id` ,`servidor` ,`base_dato` ,`usuario` ,`clave` ,`fecha`, `estado`)VALUES (NULL , '$servidor_multi', '$database_name', '$username_multi', '$clave_multi', '" . date('Y-m-d') . "', '2');", $conn1);

                    $id_database = mysql_insert_id($conn1);

                    mysql_query("UPDATE `users` SET `db_config_id` = '$id_database' , is_admin = 't' WHERE `users`.`id` = $id;", $conn1);

                    @mysql_close($conn1);

                    unset($conn1);

                    $this->session->set_flashdata('message', "Su cuenta ha sido activada");

                }

            } else {

                $this->session->set_flashdata('message', "No ha sido completado su registro, Intente mas tarde");

            }

        }

    }

    //activate the user

    public function activate_count($id, $pass, $code = false)
    {
        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } else if ($this->ion_auth->is_admin()) {
            $activation = $this->ion_auth->activate($id);
        }

        if ($activation) {
            try {
                $this->_create_db($id, $pass);
                $this->session->set_flashdata('message', "Su cuenta ha sido creada. Por favor verifique su email");
            } catch (Exception $e) {
                redirect("auth", 'refresh');
            }

        } else {
            $this->session->set_flashdata('message', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    //deactivate the user

    public function deactivate($id = null)
    {

        $id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

        $this->load->library('form_validation');

        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');

        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() == false) {

            // insert csrf check

            $this->data['csrf'] = $this->_get_csrf_nonce();

            $this->data['user'] = $this->ion_auth->user($id)->row();

            $this->_render_page('auth/deactivate_user', $this->data);

        } else {

            // do we really want to deactivate?

            if ($this->input->post('confirm') == 'yes') {

                // do we have a valid request?

                if ($this->_valid_csrf_nonce() === false || $id != $this->input->post('id')) {

                    show_error($this->lang->line('error_csrf'));

                }

                // do we have the right userlevel?

                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {

                    $this->ion_auth->deactivate($id);

                }

            }

            //redirect them back to the auth page

            redirect('auth', 'refresh');

        }

    }

    public function captcha_check($str)
    {

        $expiration = time() - 7200; // Two hour limit

        $this->db->query("DELETE FROM captcha WHERE captcha_time < " . $expiration);

        // Then see if a captcha exists:

        $sql = "SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?";

        $binds = array($str, $this->input->ip_address(), $expiration);

        $query = $this->db->query($sql, $binds);

        $row = $query->row();

        if ($row->count == 0) {

            $this->form_validation->set_message('captcha_check', 'Por envie en el texto de la imagen');

            return false;

        }

        return true;

    }

    public function register_222()
    {

        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|xss_clean');

        //$this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|xss_clean');

        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email|callback_email_check');

        $this->form_validation->set_rules('idioma', "Idioma", 'required|xss_clean');

        $this->form_validation->set_rules('pais', "Pais", 'required|xss_clean');

        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');

        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        //$this->form_validation->set_rules('captcha', 'Captcha', 'required|callback_captcha_check');

        if ($this->form_validation->run() == true) {

            /*$username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));

            $email    = $this->input->post('email');

            $password = $this->input->post('password');

            $additional_data = array(

            'first_name' => $this->input->post('first_name'),

            'last_name'  => $this->input->post('last_name'),

            'company'    => $this->input->post('company'),

            'phone'      => $this->input->post('phone1'),

            //'db_config_id' => $this->input->post('db_config_id')

            );*/

            $username = strtolower($this->input->post('first_name')) /*. ' ' . strtolower($this->input->post('last_name'))*/;

            $email = $this->input->post('email');

            $password = $this->input->post('password');

            $additional_data = array(

                'first_name' => $this->input->post('first_name'),

                //'last_name'  => $this->input->post('last_name'),

                'pais' => $this->input->post('pais'),

                'idioma' => $this->input->post('idioma'),

                'is_admin' => 'td',

            );

        }

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data)) {

            //check to see if we are creating the user

            //redirect them back to the admin page

            $this->session->set_flashdata('message', "Su cuenta ha sido creada. Por favor verifique su email");

            redirect("auth/login", 'refresh');

        } else {

            $data = array();

            $data['first_name'] = array(

                'name' => 'first_name',

                'id' => 'first_name',

                'type' => 'text',

                'value' => $this->form_validation->set_value('first_name'),

                'placeholder' => 'Nombre',

            );

            $data['email'] = array(

                'name' => 'email',

                'id' => 'email',

                'type' => 'text',

                'value' => $this->form_validation->set_value('email'),

                'placeholder' => 'Correo electr&oacute;nico',

            );

            $data['password'] = array(

                'name' => 'password',

                'id' => 'password',

                'type' => 'password',

                'value' => $this->form_validation->set_value('password'),

                'placeholder' => 'Clave',

            );

            $data['password_confirm'] = array(

                'name' => 'password_confirm',

                'id' => 'password_confirm',

                'type' => 'password',

                'value' => $this->form_validation->set_value('password_confirm'),

                'placeholder' => 'Repita la clave',

            );

            /*  $vals = array(

            'img_path' => './public/captcha/',

            'img_url' => base_url()."/public/captcha/",

            'word' => rand(10000, 99999)

            );

            $cap = create_captcha($vals);

            $captcha = array(

            'captcha_time' => $cap['time'],

            'ip_address' => $this->input->ip_address(),

            'word' => $cap['word']

            );

            $query = $this->db->insert_string('captcha', $captcha);

            $this->db->query($query);

            $data['cap'] = $cap;*/

            $this->load->model("pais_provincia_model", 'pais_provincia');

            $data['pais'] = $this->pais_provincia->get_pais();

            $this->db->select('valor_opcion, mostrar_opcion');

            $query = $this->db->get_where('opciones', array('nombre_opcion' => 'idioma'));

            $idiomas = array();

            foreach ($query->result() as $value) {

                $idiomas[$value->valor_opcion] = $value->mostrar_opcion;

            }

            $data['idioma'] = $idiomas;

            $this->layout->template("login")->show('auth/register', array('data' => $data));

        }

    }

    //create a new user

    public function create_user()
    {

        $this->data['title'] = "Create User";

        if (!$this->ion_auth->logged_in() /*|| !$this->ion_auth->is_admin()*/) {

            redirect('auth', 'refresh');

        }

        //validate form input

        $this->form_validation->set_rules('first_name', $this->lang->line('create_user_validation_fname_label'), 'required|xss_clean');

        $this->form_validation->set_rules('last_name', $this->lang->line('create_user_validation_lname_label'), 'required|xss_clean');

        $this->form_validation->set_rules('email', $this->lang->line('create_user_validation_email_label'), 'required|valid_email');

        $this->form_validation->set_rules('phone1', $this->lang->line('create_user_validation_phone1_label'), 'required|xss_clean|min_length[3]|max_length[3]');

        $this->form_validation->set_rules('phone2', $this->lang->line('create_user_validation_phone2_label'), 'required|xss_clean|min_length[3]|max_length[3]');

        $this->form_validation->set_rules('phone3', $this->lang->line('create_user_validation_phone3_label'), 'required|xss_clean|min_length[4]|max_length[4]');

        $this->form_validation->set_rules('company', $this->lang->line('create_user_validation_company_label'), 'required|xss_clean');

        $this->form_validation->set_rules('password', $this->lang->line('create_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');

        $this->form_validation->set_rules('password_confirm', $this->lang->line('create_user_validation_password_confirm_label'), 'required');

        if ($this->form_validation->run() == true) {

            $username = strtolower($this->input->post('first_name')) . ' ' . strtolower($this->input->post('last_name'));

            $email = $this->input->post('email');

            $password = $this->input->post('password');

            $additional_data = array(

                'first_name' => $this->input->post('first_name'),

                'last_name' => $this->input->post('last_name'),

                'company' => $this->input->post('company'),

                'phone' => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),

            );

        }

        if ($this->form_validation->run() == true && $this->ion_auth->register($username, $password, $email, $additional_data)) {

            //check to see if we are creating the user

            //redirect them back to the admin page

            $this->session->set_flashdata('message', $this->ion_auth->messages());

            redirect("auth", 'refresh');

        } else {

            //display the create user form

            //set the flash data error message if there is one

            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['first_name'] = array(

                'name' => 'first_name',

                'id' => 'first_name',

                'type' => 'text',

                'value' => $this->form_validation->set_value('first_name'),

            );

            $this->data['last_name'] = array(

                'name' => 'last_name',

                'id' => 'last_name',

                'type' => 'text',

                'value' => $this->form_validation->set_value('last_name'),

            );

            $this->data['email'] = array(

                'name' => 'email',

                'id' => 'email',

                'type' => 'text',

                'value' => $this->form_validation->set_value('email'),

            );

            $this->data['company'] = array(

                'name' => 'company',

                'id' => 'company',

                'type' => 'text',

                'value' => $this->form_validation->set_value('company'),

            );

            $this->data['phone1'] = array(

                'name' => 'phone1',

                'id' => 'phone1',

                'type' => 'text',

                'value' => $this->form_validation->set_value('phone1'),

            );

            $this->data['phone2'] = array(

                'name' => 'phone2',

                'id' => 'phone2',

                'type' => 'text',

                'value' => $this->form_validation->set_value('phone2'),

            );

            $this->data['phone3'] = array(

                'name' => 'phone3',

                'id' => 'phone3',

                'type' => 'text',

                'value' => $this->form_validation->set_value('phone3'),

            );

            $this->data['password'] = array(

                'name' => 'password',

                'id' => 'password',

                'type' => 'password',

                'value' => $this->form_validation->set_value('password'),

            );

            $this->data['password_confirm'] = array(

                'name' => 'password_confirm',

                'id' => 'password_confirm',

                'type' => 'password',

                'value' => $this->form_validation->set_value('password_confirm'),

            );

            $this->_render_page('auth/create_user', $this->data);

        }

    }

    //edit a user

    public function edit_user($id)
    {

        $this->data['title'] = "Edit User";

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {

            redirect('auth', 'refresh');

        }

        $user = $this->ion_auth->user($id)->row();

        $groups = $this->ion_auth->groups()->result_array();

        $currentGroups = $this->ion_auth->get_users_groups($id)->result();

        //process the phone number

        if (isset($user->phone) && !empty($user->phone)) {

            $user->phone = explode('-', $user->phone);

        }

        //validate form input

        $this->form_validation->set_rules('first_name', $this->lang->line('edit_user_validation_fname_label'), 'required|xss_clean');

        $this->form_validation->set_rules('last_name', $this->lang->line('edit_user_validation_lname_label'), 'required|xss_clean');

        $this->form_validation->set_rules('phone1', $this->lang->line('edit_user_validation_phone1_label'), 'required|xss_clean|min_length[3]|max_length[3]');

        $this->form_validation->set_rules('phone2', $this->lang->line('edit_user_validation_phone2_label'), 'required|xss_clean|min_length[3]|max_length[3]');

        $this->form_validation->set_rules('phone3', $this->lang->line('edit_user_validation_phone3_label'), 'required|xss_clean|min_length[4]|max_length[4]');

        $this->form_validation->set_rules('company', $this->lang->line('edit_user_validation_company_label'), 'required|xss_clean');

        $this->form_validation->set_rules('groups', $this->lang->line('edit_user_validation_groups_label'), 'xss_clean');

        if (isset($_POST) && !empty($_POST)) {

            // do we have a valid request?

            if ($this->_valid_csrf_nonce() === false || $id != $this->input->post('id')) {

                show_error($this->lang->line('error_csrf'));

            }

            $data = array(

                'first_name' => $this->input->post('first_name'),

                'last_name' => $this->input->post('last_name'),

                'company' => $this->input->post('company'),

                'phone' => $this->input->post('phone1') . '-' . $this->input->post('phone2') . '-' . $this->input->post('phone3'),

            );

            //Update the groups user belongs to

            $groupData = $this->input->post('groups');

            if (isset($groupData) && !empty($groupData)) {

                $this->ion_auth->remove_from_group('', $id);

                foreach ($groupData as $grp) {

                    $this->ion_auth->add_to_group($grp, $id);

                }

            }

            //update the password if it was posted

            if ($this->input->post('password')) {

                $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');

                $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');

                $data['password'] = $this->input->post('password');

            }

            if ($this->form_validation->run() === true) {

                $this->ion_auth->update($user->id, $data);

                //check to see if we are creating the user

                //redirect them back to the admin page

                $this->session->set_flashdata('message', "User Saved");

                redirect("auth", 'refresh');

            }

        }

        //display the edit user form

        $this->data['csrf'] = $this->_get_csrf_nonce();

        //set the flash data error message if there is one

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        //pass the user to the view

        $this->data['user'] = $user;

        $this->data['groups'] = $groups;

        $this->data['currentGroups'] = $currentGroups;

        $this->data['first_name'] = array(

            'name' => 'first_name',

            'id' => 'first_name',

            'type' => 'text',

            'value' => $this->form_validation->set_value('first_name', $user->first_name),

        );

        $this->data['last_name'] = array(

            'name' => 'last_name',

            'id' => 'last_name',

            'type' => 'text',

            'value' => $this->form_validation->set_value('last_name', $user->last_name),

        );

        $this->data['company'] = array(

            'name' => 'company',

            'id' => 'company',

            'type' => 'text',

            'value' => $this->form_validation->set_value('company', $user->company),

        );

        $this->data['phone1'] = array(

            'name' => 'phone1',

            'id' => 'phone1',

            'type' => 'text',

            'value' => $this->form_validation->set_value('phone1', $user->phone[0]),

        );

        $this->data['phone2'] = array(

            'name' => 'phone2',

            'id' => 'phone2',

            'type' => 'text',

            'value' => $this->form_validation->set_value('phone2', $user->phone[1]),

        );

        $this->data['phone3'] = array(

            'name' => 'phone3',

            'id' => 'phone3',

            'type' => 'text',

            'value' => $this->form_validation->set_value('phone3', $user->phone[2]),

        );

        $this->data['password'] = array(

            'name' => 'password',

            'id' => 'password',

            'type' => 'password',

        );

        $this->data['password_confirm'] = array(

            'name' => 'password_confirm',

            'id' => 'password_confirm',

            'type' => 'password',

        );

        $this->_render_page('auth/edit_user', $this->data);

    }

    // create a new group

    public function create_group()
    {

        $this->data['title'] = $this->lang->line('create_group_title');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {

            redirect('auth', 'refresh');

        }

        //validate form input

        $this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash|xss_clean');

        $this->form_validation->set_rules('description', $this->lang->line('create_group_validation_desc_label'), 'xss_clean');

        if ($this->form_validation->run() == true) {

            $new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));

            if ($new_group_id) {

                // check to see if we are creating the group

                // redirect them back to the admin page

                $this->session->set_flashdata('message', $this->ion_auth->messages());

                redirect("auth", 'refresh');

            }

        } else {

            //display the create group form

            //set the flash data error message if there is one

            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['group_name'] = array(

                'name' => 'group_name',

                'id' => 'group_name',

                'type' => 'text',

                'value' => $this->form_validation->set_value('group_name'),

            );

            $this->data['description'] = array(

                'name' => 'description',

                'id' => 'description',

                'type' => 'text',

                'value' => $this->form_validation->set_value('description'),

            );

            $this->_render_page('auth/create_group', $this->data);

        }

    }

    //edit a group

    public function edit_group($id)
    {

        // bail if no group id given

        if (!$id || empty($id)) {

            redirect('auth', 'refresh');

        }

        $this->data['title'] = $this->lang->line('edit_group_title');

        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin()) {

            redirect('auth', 'refresh');

        }

        $group = $this->ion_auth->group($id)->row();

        //validate form input

        $this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash|xss_clean');

        $this->form_validation->set_rules('group_description', $this->lang->line('edit_group_validation_desc_label'), 'xss_clean');

        if (isset($_POST) && !empty($_POST)) {

            if ($this->form_validation->run() === true) {

                $group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);

                if ($group_update) {

                    $this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));

                } else {

                    $this->session->set_flashdata('message', $this->ion_auth->errors());

                }

                redirect("auth", 'refresh');

            }

        }

        //set the flash data error message if there is one

        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        //pass the user to the view

        $this->data['group'] = $group;

        $this->data['group_name'] = array(

            'name' => 'group_name',

            'id' => 'group_name',

            'type' => 'text',

            'value' => $this->form_validation->set_value('group_name', $group->name),

        );

        $this->data['group_description'] = array(

            'name' => 'group_description',

            'id' => 'group_description',

            'type' => 'text',

            'value' => $this->form_validation->set_value('group_description', $group->description),

        );

        $this->_render_page('auth/edit_group', $this->data);

    }

    public function _get_csrf_nonce()
    {

        $this->load->helper('string');

        $key = random_string('alnum', 8);

        $value = random_string('alnum', 20);

        $this->session->set_flashdata('csrfkey', $key);

        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);

    }

    public function _valid_csrf_nonce()
    {

        if ($this->input->post($this->session->flashdata('csrfkey')) !== false &&

            $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {

            return true;

        } else {

            return false;

        }

    }

    public function _render_page($view, $data = null, $render = false)
    {

        $this->viewdata = (empty($data)) ? $this->data : $data;

        $view_html = $this->load->view($view, $this->viewdata, $render);

        if (!$render) {
            return $view_html;
        }

    }

}
