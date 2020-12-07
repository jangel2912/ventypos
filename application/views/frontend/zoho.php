<?php

$zohoNombre = $data["formNombre"];
$zohoCorreo = $data["formEmail"];
$zohoTel = $data["formTelefono"];
$zohoFuente = $data["formFuente"];
$zohoEstado = $data["formEstado"];

?>

<!--
<?php print_r($data); ?>
-->

<div id="formulario" style="background-color: rgb(0, 157, 99); padding: 10px 0px 30px; margin-bottom:40px;display:none;">
    <div class="center_cont">
        <h3 style="float: left; color: #fff; margin: 20px 20px 0px 0px;">Descarga Cotización</h3>
        <form action='https://crm.zoho.com/crm/WebToLeadForm' name=WebToLeads3036510000006240561 method='POST' onSubmit='javascript:document.charset = "UTF-8"; return checkMandatery()' accept-charset='UTF-8' ">
            <input type='text' style='display:none;' name='xnQsjsdp' value='jZc-ARvRC7Q$' />
            <input type='hidden' name='zc_gad' id='zc_gad' value='' />
            <input type='text' style='display:none;' name='xmIwtLD' value='mV9P0u8tfuMKvw5Ju4-9agB9X0hHrsFE' />
            <input type='text' style='display:none;' name='actionType' value='TGVhZHM=' />
            <input type='text' style='display:none;' name='returnURL' value='http&#x3a;&#x2f;&#x2f;vendty.com&#x2f;gracias.html' />
            <input id="fnombre" type="text" name="Last Name" class="campo_nombre1" placeholder=" Nombre ..." onfocus="if (this.value == ' Nombre ...')
                        this.value = '';" onblur="if (this.value == '')
                                    this.value = ' Nombre ...';" style="width: 205px; margin: 16px 10px 0px 0px;">
            <input id="ftelefono" type="text" name="Mobile" class="campo_telefono1" placeholder=" Celular..." onfocus="if (this.value == ' Celular...')
                        this.value = '';" onblur="if (this.value == '')
                                    this.value = ' Celular...';" style="width: 165px; margin: 20px 10px 0px 0px;">
            <input id="femail" type="text" name="Email" class="campo_email1" placeholder=" Correo Electrónico ..." onfocus="if (this.value == ' Correo Electrónico ...')
                        this.value = '';" onblur="if (this.value == '')
                                    this.value = ' Correo Electrónico ...';" style="width: 245px; margin: 20px 10px 0px 0px;">
            <input type='hidden' style='width:250px;' maxlength='100' name='Designation' value='com' />
            <tr>
                <td></td>
            </tr>
            </table>
            <button type="submit" id="submit1" style=" width: 150px; height: 48px; background-color: #FB5D2B;
                    float: right; margin: 20px 0px 0px 0px; border-radius: 0px;"><span style="font-size:18px; line-height:11px;">Descargar</span></button>
        </form>
    </div>
</div>   

<script src="<?php echo base_url('public/js/plugins/jquery/jquery-1.9.1.min.js'); ?>" type="text/javascript"></script>
<script>


        var mndFileds = new Array('Last Name');
        var fldLangVal = new Array('Apellido');
        
        var zohoFuente = "<?php echo $zohoFuente; ?>";

        function reloadImg() {
            if (document.getElementById('imgid').src.indexOf('&d') !== -1) {
                document.getElementById('imgid').src = document.getElementById('imgid').src.substring(0, document.getElementById('imgid').src.indexOf('&d')) + '&d' + new Date().getTime();
            } else {
                document.getElementById('imgid').src = document.getElementById('imgid').src + '&d' + new Date().getTime();
            }
        }
        
        function gracias(){
            window.location.replace("http://www.vendty.com/gracias.html");
        }
        
        function checkMandatery() {

            var name = '';
            var email = '';
            for (i = 0; i < mndFileds.length; i++) {
                var fieldObj = document.forms['WebToLeads3036510000006240561'][mndFileds[i]];
                if (fieldObj) {
                    if (((fieldObj.value).replace(/^\s+|\s+$/g, '')).length == 0) {
                        alert(fldLangVal[i] + ' no puede estar vacío');
                        fieldObj.focus();
                        return false;
                    } else if (fieldObj.nodeName == 'SELECT') {
                        if (fieldObj.options[fieldObj.selectedIndex].value == '-None-') {
                            alert(fldLangVal[i] + ' no puede ser nulo');
                            fieldObj.focus();
                            return false;
                        }
                    } else if (fieldObj.type == 'checkbox') {
                        if (fieldObj.checked == false) {
                            alert('Please accept  ' + fldLangVal[i]);
                            fieldObj.focus();
                            return false;
                        }
                    }
                    try {
                        if (fieldObj.name == 'Last Name') {
                            name = fieldObj.value;
                        }
                    } catch (e) {
                    }
                }
            }
            try {
                if ($zoho) {
                    var firstnameObj = document.forms['WebToLeads3036510000006240561']['First Name'];
                    if (firstnameObj) {
                        name = firstnameObj.value + ' ' + name;
                    }
                    $zoho.salesiq.visitor.name(name);
                    var emailObj = document.forms['WebToLeads3036510000006240561']['Email'];
                    if (emailObj) {
                        email = emailObj.value;
                        $zoho.salesiq.visitor.email(email);
                    }
                }
            } catch (e) {
            }


            var dir = "http://www.vendty.com/invoice_test/index.php/frontend/index/new";
            var form = $("#formulario form");


            $.ajax({
                type: "POST",
                url: "https://crm.zoho.com/crm/WebToLeadForm?callback?",
                cache: false,
                async: false,
                data: form.serialize(),
                dataType: 'text',
                success: function (response) {
                    gracias();
                },
                error: function (xhr, textStatus, errorThrown) {
                    gracias();
                }
            });
                       

            return false;

        }



            setTimeout( function () {

                                                                       
              $("#formulario input[name='Last Name']").val("<?php echo $zohoNombre; ?>");
              $("#femail").val("<?php echo $zohoCorreo; ?>");
              $("#ftelefono").val("<?php echo $zohoTel; ?>");                                                                   
                                                           
              $("#formulario form").submit();
              //gracias();
              
            }, 2);

    </script>
