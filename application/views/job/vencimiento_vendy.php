<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title></title>
        <style type="text/css">
            /* Estilo generales */
            html,
            body {
                margin: 0 auto !important;
                padding: 0 !important;
                height: 100% !important;
                width: 100% !important;
            }
            * {
                -ms-text-size-adjust: 100%;
                -webkit-text-size-adjust: 100%;
            }
            a{
                color: #0981E2 !important;
            }
            div[style*="margin: 16px 0"] {
                margin:0 !important;
            }
            table,
            td {
                mso-table-lspace: 0pt !important;
                mso-table-rspace: 0pt !important;
            }
            table {
                border-spacing: 0 !important;
                border-collapse: collapse !important;
                table-layout: fixed !important;
                Margin: 0 auto !important;
            }
            table table table {
                table-layout: auto; 
            }

            img {
                -ms-interpolation-mode:bicubic;
            }
            .mobile-link--footer a,
            a[x-apple-data-detectors] {
                color:inherit !important;
                text-decoration: underline !important;
            }

            /*Estilo de los botones*/
            .button-td,
            .button-a,
            .button-b {
                transition: all 100ms ease-in;
            }

            /* Media Queries */
            @media screen and (max-width: 550px) {

                .email-container {
                    width: 100% !important;
                    margin: auto !important;
                }
                .fluid,
                .fluid-centered {
                    max-width: 100% !important;
                    height: auto !important;
                    margin-left: auto !important;
                    margin-right: auto !important;
                }
                .fluid-centered {
                    margin-left: auto !important;
                    margin-right: auto !important;
                }
                .stack-column,
                .stack-column-center {
                    display: block !important;
                    width: 100% !important;
                    max-width: 100% !important;
                    direction: ltr !important;
                }
                .stack-column-center {
                    text-align: center !important;
                }
                .center-on-narrow {
                    text-align: center !important;
                    display: block !important;
                    margin-left: auto !important;
                    margin-right: auto !important;
                    float: none !important;
                }
                table.center-on-narrow {
                    display: inline-block !important;
                }
            }
        </style>

    </head>
    <body bgcolor="#fff" width="100%" style="margin: 0;" style="width: 100%; background: #f2f2f2;">
        <!--<center >
    
        <!-- Preheader Text: aparece después del asunto en algunos clientes de correo -->
        <div style="display:none;font-size:1px;line-height:1px;max-height:0px;max-width:0px;opacity:0;overflow:hidden;mso-hide:all;font-family: sans-serif;">

        </div>
        <!-- Preheader Text -->

        <!-- Header (logo) -->
        <table cellspacing="0" cellpadding="0" border="0" align="center" width="550" style="margin: auto;" class="email-container">
            <tr>
                <td style="padding: 15px 5px 5px 5px; text-align: left">

                </td>
            </tr>
            <tr>
                <td style="border-top-left-radius:5px;border-top-right-radius:5px;vertical-align:top;border-collapse:collapse;padding:2px;background-color:#09CA0E;font-size:4px;text-align:center">
                </td>
            </tr>
        </table>
        <!-- Header -->

        <!-- Body -->
        <table cellspacing="0" cellpadding="0" border="0" align="center" bgcolor="#ffffff" width="550" style="margin: auto; " class="email-container">

            <!-- Header image -->
            <tr>

            </tr>
            <!-- Header image -->

            <!-- Texto -->
            <tr><td></td>
            <tr>

                <td><a href="http://vendty.com"><img src="http://vendty.com/wp-content/uploads/imgbaner-correoinv.jpg" width="100%" height="auto" alt="Prueba gratis por 7 días" border="0" align="center" style="width: 100%; max-width: 550px;"></a></td>
            </tr>
            <tr>


            </tr>
            <tr>
                <td style="padding: 0px 0px 0px 0px; text-align: center; font-family: sans-serif; font-size: 12px; mso-height-rule: exactly; line-height: 0px; color: #555555;font-weight: lighter;">
                    <table width="100%" border="0" >
                        <tbody>
                            <tr><td style="padding: 5px 5px 5px 10px; text-align: left; font-family: sans-serif; font-size: 210%; mso-height-rule: exactly; line-height: 28px; color: #fff;font-weight: lighter; background-color:#5AD347">
                                    Notificación de renovación de servicio.
                                </td>


                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>

            <tr>
                <!-- Background image -->
                <td style="text-align: center; background-position: center center !important; background-size: cover !important; margin-top: 10px;">
                    <!--[if gte mso 9]>
                    <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:550px;height:175px; background-position: center center !important;">
                    <v:fill type="tile" src="http://i.imgur.com/43mVc8e.png" color="#EB6D12" />
                    <v:textbox inset="0,0,0,0">
                    <![endif]-->
                    <div>
                        <!--[if mso]>
                        <table border="0" cellspacing="0" cellpadding="0" align="center" width="550">
                        <tr>
                        <td align="center" valign="top" width="550">
                        <![endif]-->
                        <table border="0" cellpadding="0" cellspacing="0" align="center" width="100%" style="max-width:550px; margin: auto;">
                            <tr>
                                <td valign="middle" style="text-align: left; padding: 20px 20px 0px 20px; font-family: sans-serif; font-size: 100%; mso-height-rule: exactly; line-height: 25px; color: #444;font-weight:lighter">
                                    <strong style="font-size:20px;">Hola <?php echo $usuarios['nombre_contacto'] ?>,</strong>
                                    <br>
                                    &nbsp;

                                    <div style="margin-top:20px;">

                                        Le informamos que el <?= $usuarios['fecha_programada'] ?> venció su Plan de Vendty Sistema POS e inventarios Cloud.  
                                        Si gustan seguir disfrutando de nuestros servicios lo invitamos a realizar la renovación del plan con fecha límite <?= $usuarios['fecha_limite'] ?>.
                                        <br>
                                        Información para consignación:<br><br>
                                        <strong>Cuenta de ahorros</strong> No. 457 500 063 096 Banco Davivienda<br>
                                        
                                        <strong>A Nombre de:</strong> Vendty SAS, NIT 900.849.294-8<br>
                                        <br><br>
                                        Valor Anualidad $<?= number_format($usuarios['valor_renovacion']) ?> (Desc. 10% aplicado)<br>
                                        <br>
                                        Favor enviar el comprobante de pago escaneado.
                                        <br>
                                        Quedo atenta a servirles,
                                        <br><br>
                                    </div>

                                    <table width="510" border="0">
                                        <tbody>
                                            <tr>
                                              <!--<td><img src="http://vendty.com/ayuda/wp-content/uploads/2016/10/pie-de-correo.jpg" width="500" style="margin-top:20px;"></td>-->    </tr>
                                            <tr>
                                                <td><img src="http://vendty.com/ayuda/wp-content/uploads/2016/10/pie-coreo-2.jpg" width="500"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </table>
                        <!--[if (gte mso 9)|(IE)]>
                        </td>
                        </tr>
                        </table>
                        <![endif]-->
                    </div>
                    <!--[if gte mso 9]>
                    </v:textbox>
                    </v:rect>
                    <![endif]-->
                </td>
            </tr>
            <!-- Background image -->


            <!-- Imagen con texto a la derecha -->
            <tr> </tr>
            <!-- Imagen con texto a la derecha -->

            <!-- Imagen con texto a la derecha -->
            <tr> </tr>
            <!-- Imagen con texto a la derecha -->
            <tr> <td style="padding: 25px; text-align: center; font-family: sans-serif; font-size: 100%; mso-height-rule: exactly; line-height: 20px; color: #555555;font-weight: lighter;">
                    <table>
                        <tr>
                            <td width="33.33%" style="padding:10px;border:1px solid #dadada;"><img src="https://image.freepik.com/free-icon/telephone-handle-silhouette_318-41969.png" width="15px" alt=""><br>¿Necesitas ayuda?<br><span style="color:#0981E2">(319) 4751398 - (318) 8018675</span></td>
                            <td width="5%" style="padding:10px;border-radius: 5px;">&nbsp;</td>
                            <td width="33.33%" style="padding:15px;border:1px solid #dadada;border-radius: 5px;"><img src="https://cdn4.iconfinder.com/data/icons/defaulticon/icons/png/256x256/mail.png" width="15px" alt=""><br>Programa una demostración<br><a href="mailto:info@venty.com">info@venty.com</a></td>
                        </tr>
                    </table>
                </td></tr>

<!-- <tr>
    <td style="padding: 25px 25px 40px 25px; text-align: left; font-family: sans-serif; font-size: 15px; mso-height-rule: exactly; line-height: 20px; color: #555555;font-weight: lighter;">
       Gracias, <br>
       <b>Equipo de Vendty</b>
    </td>
</tr> -->
            <!-- Texto -->

            <tr>
                <td align="center" bgcolor="#4e4e4e">
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                        <tbody><tr>
                                <td align="center" style="padding: 15px 0px 15px 0px;font-family: sans-serif;" >
                                    <table border="0" cellspacing="0" cellpadding="0" >
                                        <tbody><tr>
                                                <td align="center" style="padding: 0px;">
                                                    <table border="0" cellspacing="0" cellpadding="0" >
                                                        <tbody><tr>
                                                                <td align="center">
                                                                    <span style="font-size: 12px;color:#fff;">Encuéntranos en nuestras redes sociales<br>
                                                                        <a href="www.facebook.com/vendtycom" style="margin: 0px 5px;"><img alt="Facebook" src="https://cdn1.iconfinder.com/data/icons/logotypes/32/square-facebook-128.png" border="0" style="height: 30px; width: 30px; border:none; " title="Facebook"></a>
                                                                        <a href="www.twitter.com/vendtyapps" style="margin: 0px 5px;"> <img alt="Twitter" src="http://www.coetail.com/seriously/files/2016/04/twitter-logo.png" border="0" style="height: 30px; width: 30px; border:none; " title="Twitter"> </a>
                                                                        <a href="https://www.youtube.com/channel/UCjjkzv4FmwcBen2TCVUg4gQ" style="margin: 0px 5px;"> <img alt="YouTube" src="http://icons.iconarchive.com/icons/marcus-roberto/google-play/256/YouTube-icon.png" border="0" style="height: 30px; width: 30px; border:none; " title="YouTubes"> </a>
                                                                </td>
                                                            </tr></tbody>
                                                    </table>
                                                </td>
                                            </tr></tbody>
                                    </table>
                                </td>
                            </tr></tbody>
                    </table>
                </td>
            </tr>
        </table>
        <!-- Body -->

        <!-- Footer -->
        <table cellspacing="0" cellpadding="0" border="0" align="center" width="450" style="margin: auto;" class="email-container">
            <tr>
                <td style="padding: 20px 10px;width: 100%;font-size: 12px; font-family: sans-serif; mso-height-rule: exactly; line-height:16px; text-align: center; color: #888888;">

                    Este e-mail fue enviado a usted por <a href="mailto:info@vendty.com">info@vendty.com</a>. Si usted ya no desea recibir mas mensajes de correo electrónico, puede <unsubscribe style="color:#0981E2; text-decoration:underline;">darse de baja</unsubscribe>
        </td>
    </tr>
</table>
<!-- Footer -->
<!--</center>-->
</body>
</html>