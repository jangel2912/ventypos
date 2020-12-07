
<style>
    .content-register{border: solid 1px lightgray; padding:20px; box-sizing:border-box; margin-left:10px;}
</style>



<h4>Nuevo cliente</h4>
<hr><br>
<p>A continuación podra ingresar un nuevo cliente</p>
<br>
<div class="row">
    <div class="col-md-8 content-register">
        <form>
            <div class="col-md-6">
                <input type="hidden" class="form-control" name="creation_user" id="creation_user" value="<?php echo $this->session->userdata('user_id'); ?>" >
                <input type="hidden" class="form-control" name="creation_distribuidor" id="creation_distribuidor" value="<?php echo $creation_distribuidor; ?>" >
                <label for="Last_Name">Nombre</label>
                <input type="text" class="form-control" name="Last_Name" id="Last_Name" placeholder="Ingrese nombre" required>
            </div>
            
            <div class="col-md-6 form-group">
                <label for="correo">Correo electrónico</label>
                <input type="email" class="form-control" id="Email" name="Email" placeholder="Ejemplo@gmail.com" required>
            </div>

            <div class="col-md-6 form-group">
                <label for="pais">Seleccione país</label>
                <select name="Pais" id="Pais" data-value="1">
                    <option value="Colombia" selected>Colombia</option>
                    <option value="Anguilla">Anguilla</option>
                    <option value="Aruba">Aruba</option>
                    <option value="Bahamas">Bahamas</option>
                    <option value="Belize">Belize</option>
                    <option value="Barbados">Barbados</option>
                    <option value="Åland">Åland</option>
                    <option value="Vatican">Vatican City</option>
                    <option value="Svalbard">Svalbard and Jan Mayen</option>
                    <option value="Spain">Spain</option>
                    <option value="Sweden">Sweden</option>
                    <option value="Switzerland">Switzerland</option>
                    <option value="United">United Kingdom</option>
                    <option value="Ukraine">Ukraine</option>
                    <option value="Bermuda">Bermuda</option>
                    <option value="Bonaire">Bonaire</option>
                    <option value="Greenland">Greenland</option>
                    <option value="El">El Salvador</option>
                    <option value="Grenada">Grenada</option>
                    <option value="Guadeloupe">Guadeloupe</option>
                    <option value="Haiti">Haiti</option>
                    <option value="Guatemala">Guatemala</option>
                    <option value="Dominican">Dominican Republic</option>
                    <option value="Dominica">Dominica</option>
                    <option value="Canada">Canada</option>
                    <option value="British">British Virgin Islands</option>
                    <option value="Cayman">Cayman Islands</option>
                    <option value="Costa">Costa Rica</option>
                    <option value="Curacao">Curacao</option>
                    <option value="Cuba">Cuba</option>
                    <option value="Slovenia">Slovenia</option>
                    <option value="Slovakia">Slovakia</option>
                    <option value="Ireland">Ireland</option>
                    <option value="Iceland">Iceland</option>
                    <option value="Italy">Italy</option>
                    <option value="Kosovo">Kosovo</option>
                    <option value="Liechtenstein">Liechtenstein</option>
                    <option value="Latvia">Latvia</option>
                    <option value="Hungary">Hungary</option>
                    <option value="Greece">Greece</option>
                    <option value="Faroe">Faroe Islands</option>
                    <option value="Estonia">Estonia</option>
                    <option value="Finland">Finland</option>
                    <option value="France">France</option>
                    <option value="Gibraltar">Gibraltar</option>
                    <option value="Germany">Germany</option>
                    <option value="Lithuania">Lithuania</option>
                    <option value="Luxembourg">Luxembourg</option>
                    <option value="Portugal">Portugal</option>
                    <option value="Poland">Poland</option>
                    <option value="Romania">Romania</option>
                    <option value="Russia">Russia</option>
                    <option value="Serbia">Serbia</option>
                    <option value="San">San Marino</option>
                    <option value="Norway">Norway</option>
                    <option value="Netherlands">Netherlands</option>
                    <option value="Malta">Malta</option>
                    <option value="Macedonia">Macedonia</option>
                    <option value="Moldova">Moldova</option>
                    <option value="Monaco">Monaco</option>
                    <option value="Montenegro">Montenegro</option>
                    <option value="Honduras">Honduras</option>
                    <option value="Jamaica">Jamaica</option>
                    <option value="Tokelau">Tokelau</option>
                    <option value="Tonga">Tonga</option>
                    <option value="Tuvalu">Tuvalu</option>
                    <option value="Vanuatu">Vanuatu</option>
                    <option value="U">U.S. Minor Outlying Islands</option>
                    <option value="Solomon">Solomon Islands</option>
                    <option value="Samoa">Samoa</option>
                    <option value="Norfolk">Norfolk Island</option>
                    <option value="Niue">Niue</option>
                    <option value="Northern">Northern Mariana Islands</option>
                    <option value="Palau">Palau</option>
                    <option value="Pitcairn">Pitcairn Islands</option>
                    <option value="Papua">Papua New Guinea</option>
                    <option value="Wallis">Wallis and Futuna</option>
                    <option value="Argentina">Argentina</option>
                    <option value="Paraguay">Paraguay</option>
                    <option value="Guyana">Guyana</option>
                    <option value="Peru">Peru</option>
                    <option value="Suriname">Suriname</option>
                    <option value="Venezuela">Venezuela</option>
                    <option value="Uruguay">Uruguay</option>
                    <option value="French">French Guiana</option>
                    <option value="Falkland">Falkland Islands</option>
                    <option value="Brazil">Brazil</option>
                    <option value="Bolivia">Bolivia</option>
                    <option value="Chile">Chile</option>
                    <option value="Ecuador">Ecuador</option>
                    <option value="New">New Zealand</option>
                    <option value="New">New Caledonia</option>
                    <option value="Saint">Saint Martin</option>
                    <option value="Saint">Saint Lucia</option>
                    <option value="Saint">Saint Pierre and Miquelon</option>
                    <option value="Saint">Saint Vincent and the Grenadines</option>
                    <option value="Trinidad">Trinidad and Tobago</option>
                    <option value="Sint">Sint Maarten</option>
                    <option value="Saint">Saint Kitts and Nevis</option>
                    <option value="Saint">Saint Barthélemy</option>
                    <option value="Mexico">Mexico</option>
                    <option value="Martinique">Martinique</option>
                    <option value="Montserrat">Montserrat</option>
                    <option value="Nicaragua">Nicaragua</option>
                    <option value="Puerto">Puerto Rico</option>
                    <option value="Panama">Panama</option>
                    <option value="Turks">Turks and Caicos Islands</option>
                    <option value="U">U.S. Virgin Islands</option>
                    <option value="Guam">Guam</option>
                    <option value="French">French Polynesia</option>
                    <option value="Kiribati">Kiribati</option>
                    <option value="Marshall">Marshall Islands</option>
                    <option value="Nauru">Nauru</option>
                    <option value="Micronesia">Micronesia</option>
                    <option value="Fiji">Fiji</option>
                    <option value="East">East Timor</option>
                    <option value="American">American Samoa</option>
                    <option value="United">United States</option>
                    <option value="Australia">Australia</option>
                    <option value="Cook">Cook Islands</option>
                    <option value="Denmark">Denmark</option>
                    <option value="Czech">Czech Republic</option>
                    <option value="Saint">Saint Helena</option>
                    <option value="Réunion">Réunion</option>
                    <option value="Senegal">Senegal</option>
                    <option value="Seychelles">Seychelles</option>
                    <option value="Somalia">Somalia</option>
                    <option value="Sierra">Sierra Leone</option>
                    <option value="Rwanda">Rwanda</option>
                    <option value="Republic">Republic of the Congo</option>
                    <option value="Morocco">Morocco</option>
                    <option value="Mayotte">Mayotte</option>
                    <option value="Mozambique">Mozambique</option>
                    <option value="Namibia">Namibia</option>
                    <option value="Nigeria">Nigeria</option>
                    <option value="Niger">Niger</option>
                    <option value="South">South Africa</option>
                    <option value="South">South Sudan</option>
                    <option value="Zimbabwe">Zimbabwe</option>
                    <option value="Zambia">Zambia</option>
                    <option value="Antarctica">Antarctica</option>
                    <option value="Bouvet">Bouvet Island</option>
                    <option value="Heard">Heard Island and McDonald Islands</option>
                    <option value="French">French Southern Territories</option>
                    <option value="Western">Western Sahara</option>
                    <option value="Uganda">Uganda</option>
                    <option value="Swaziland">Swaziland</option>
                    <option value="Sudan">Sudan</option>
                    <option value="São">São Tomé and Príncipe</option>
                    <option value="Tanzania">Tanzania</option>
                    <option value="Tunisia">Tunisia</option>
                    <option value="Togo">Togo</option>
                    <option value="Mauritius">Mauritius</option>
                    <option value="Mauritania">Mauritania</option>
                    <option value="Comoros">Comoros</option>
                    <option value="Chad">Chad</option>
                    <option value="Congo">Congo</option>
                    <option value="Djibouti">Djibouti</option>
                    <option value="Equatorial">Equatorial Guinea</option>
                    <option value="Egypt">Egypt</option>
                    <option value="Central">Central African Republic</option>
                    <option value="Cape">Cape Verde</option>
                    <option value="Benin">Benin</option>
                    <option value="Angola">Angola</option>
                    <option value="Botswana">Botswana</option>
                    <option value="Burkina">Burkina Faso</option>
                    <option value="Cameroon">Cameroon</option>
                    <option value="Burundi">Burundi</option>
                    <option value="Eritrea">Eritrea</option>
                    <option value="Ethiopia">Ethiopia</option>
                    <option value="Liberia">Liberia</option>
                    <option value="Lesotho">Lesotho</option>
                    <option value="Libya">Libya</option>
                    <option value="Madagascar">Madagascar</option>
                    <option value="Mali">Mali</option>
                    <option value="Malawi">Malawi</option>
                    <option value="Kenya">Kenya</option>
                    <option value="Ivory">Ivory Coast</option>
                    <option value="Gambia">Gambia</option>
                    <option value="Gabon">Gabon</option>
                    <option value="Ghana">Ghana</option>
                    <option value="Guinea">Guinea</option>
                    <option value="Guinea">Guinea-Bissau</option>
                    <option value="South">South Georgia and the South Sandwich Islands</option>
                    <option value="Afghanistan">Afghanistan</option>
                    <option value="Sri">Sri Lanka</option>
                    <option value="South">South Korea</option>
                    <option value="Syria">Syria</option>
                    <option value="Taiwan">Taiwan</option>
                    <option value="Thailand">Thailand</option>
                    <option value="Tajikistan">Tajikistan</option>
                    <option value="Singapore">Singapore</option>
                    <option value="Saudi">Saudi Arabia</option>
                    <option value="Oman">Oman</option>
                    <option value="North">North Korea</option>
                    <option value="Pakistan">Pakistan</option>
                    <option value="Palestine">Palestine</option>
                    <option value="Qatar">Qatar</option>
                    <option value="Philippines">Philippines</option>
                    <option value="Turkey">Turkey</option>
                    <option value="Turkmenistan">Turkmenistan</option>
                    <option value="Belgium">Belgium</option>
                    <option value="Belarus">Belarus</option>
                    <option value="Bosnia">Bosnia and Herzegovina</option>
                    <option value="Bulgaria">Bulgaria</option>
                    <option value="Cyprus">Cyprus</option>
                    <option value="Croatia">Croatia</option>
                    <option value="Austria">Austria</option>
                    <option value="Andorra">Andorra</option>
                    <option value="Uzbekistan">Uzbekistan</option>
                    <option value="United">United Arab Emirates</option>
                    <option value="Vietnam">Vietnam</option>
                    <option value="Yemen">Yemen</option>
                    <option value="Albania">Albania</option>
                    <option value="Nepal">Nepal</option>
                    <option value="Myanmar">Myanmar [Burma]</option>
                    <option value="Christmas">Christmas Island</option>
                    <option value="China">China</option>
                    <option value="Cocos">Cocos [Keeling] Islands</option>
                    <option value="Georgia">Georgia</option>
                    <option value="India">India</option>
                    <option value="Hong">Hong Kong</option>
              </select>
            </div>

            <div class="col-md-6 form-group">
                <label for="clave">Ciudad</label>
                <input type="text" class="form-control" name="Ciudad" id="Ciudad" placeholder="Bogotá D.C" required>
            </div>

            <div class="col-md-6 form-group">
                <label for="clave">Teléfono</label>
                <input type="text" class="form-control" name="Mobile" id="Mobile" placeholder="999-999-9233" required>
            </div>
            
            <div class="col-md-6 form-group">
                <label for="clave">Tipo de documento</label>
                <input type="text" class="form-control" name="TipoDocumento" id="TipoDocumento" placeholder="Nit - Rut" required>
            </div>
            <div class="col-md-6 form-group">
                <label for="clave">Número de documento</label>
                <input type="text" class="form-control" name="NumeroDocumento" id="NumeroDocumento" placeholder="Ej: 9006587412-9" required>
            </div>
            
            <div class="col-md-6 form-group">
                <label for="TipoNegocio">Tipo de negocio</label>
                <select name="TipoNegocio" id="TipoNegocio">
                    <option value="retail">Retail</option>
                    <option value="restaurante">Restaurante</option> 
                    <option value="moda">Moda</option>
                 </select>
            </div>

            <div class="col-md-12 form-group">
                <input type="checkbox" checked disabled> Crear licencia al crear cuenta.
            </div>

            <div class="row">
                <div class="col-md-12">
                     <button type="submit" class="btn btn-success btn_new_count pull-right">Registrar nuevo cliente</button>
                </div>
            </div>
            <!--<div class="loading-gif text-center hidden ">
                <img src="<?php echo base_url().'public/img/loader_gif.gif';?>" alt="Loading">
            </div>-->
        </form>
        
    </div>
</div>

<script>

    $(".btn_new_count").click(function(e){
        e.preventDefault();
        var User_id = $("#creation_user").val();
        var Distribuidor_id = $("#creation_distribuidor").val();
        var Last_Name = $("#Last_Name").val();
        var Email = $("#Email").val();
        var Mobile = $("#Mobile").val();
        var Pais = $("#Pais").val();
        var Ciudad = $("#Ciudad").val();
        var TipoDocumento = $("#TipoDocumento").val();
        var NumeroDocumento = $("#NumeroDocumento").val();
        var TipoNegocio = $("#TipoNegocio").val();
        $(".btn_new_count").addClass('disabled');
        $(".mask").removeClass('hidden');
        
        $.post("//sign.vendty.com/index.php/auth/nueva_cuenta_cliente_distribuidor",{
        //$.post("http://sign.vendty.com/index.php/auth/nueva_cuenta_distribuidor",{
            User_id: User_id,
            Distribuidor_id: Distribuidor_id,
            Last_Name: Last_Name,
            Email: Email,
            Mobile: Mobile,
            Pais: Pais,
            Ciudad: Ciudad,
            TipoDocumento: TipoDocumento,
            NumeroDocumento: NumeroDocumento,
            TipoNegocio: TipoNegocio
        },function(data){
        
            if(data == 1){
                $(".msj_mask").html("Se ha creado la cuenta correctamente ... <br> Redirigiendo");
                setTimeout(function(){
                    $(".mask").addClass('hidden');
                    location.href = "<?php echo site_url('administracion_vendty/distribuidores/suscripciones');?>";
                }, 2000);
            }else{
                $(".msj_mask").html("Error al crear la cuenta");
                setTimeout(function(){
                    $(".mask").addClass('hidden');
                    location.href = "<?php echo site_url('administracion_vendty/distribuidores/nueva_suscripcion');?>";
                }, 2000);
            }
        });
    })
    
</script>