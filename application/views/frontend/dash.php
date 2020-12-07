<?php

$resultPermisos = getPermisos();
$permisos = $resultPermisos["permisos"];
$isAdmin = $resultPermisos["admin"];

if (isset($data["estado"]) && ($data["estado"] == 2) && isset($data["wizard_tiponegocio"]) && $data["wizard_tiponegocio"] == 0) {
    $active = "inicio";
} else {
    $active = "graficas";
    if (isset($data["tipo_negocio"])) {
        if ($data["tipo_negocio"] == "restaurante" && ($isAdmin == 't' || $isAdmin == 'a')) {
            $active = "graficas";
        } else if ($data["tipo_negocio"] == "restaurante" && ($isAdmin != 't' && $isAdmin != 'a')) {
            $active = "restaurante";
        }
    }
}

$nombre_empresa = (!empty($data["datos_empresa_ap"][0]->nombre_empresa)) ? $data["datos_empresa_ap"][0]->nombre_empresa : "No existe nombre";
?>

<?php
if(isset($data["email"]) && isset($data["new"]) && $data["new"]) {
?>
<script>
    (function(){
        var i = document.createElement('iframe');
        //i.style.display = 'none';
        i.style.width = '10px';
        i.style.height = '10px';
        i.onload = function() { /*i.parentNode.removeChild(i);*/ };
        i.src = "https://vendty.com/gracias.php?var=<?php echo $data["email"] ?>";
        document.body.appendChild(i);
    })();
</script>
<?php
}
?>

<script>
    console.log('------------');
    var token = localStorage.getItem('api_auth');
    var newToken = <?php echo $data["token"] ?>;
    if(token == '' || !token) {
        if(newToken){
            localStorage.setItem('api_auth',JSON.stringify(newToken));
        }
    }
    console.log('-------------');
</script>

<?php if ($this->session->userdata("soy_nuevo") == 1) { ?>
    <iframe src="https://vendty.com/gracias.html" width="800" height="1000" scrolling="no" class="hidden"></iframe>
    <?php
    $this->session->set_userdata('soy_nuevo', 0);
} ?>

<?php if (isset($data["estado"]) && $data["estado"] == 2 && $isAdmin == 't' && isset($data["wizard_tiponegocio"]) && $data["wizard_tiponegocio"] == 0): ?>
    <?php include "application/views/wizard.php"; ?>
<?php else: ?>
    <style>
        body {
            overflow: hidden !important;
        }

        #modal-contact-content {
            display: none;
            width: calc(100% + 100px);
            height: calc(100% + 100px);
            position: absolute;
            background-color: rgba(0, 0, 0, .5);
            top: -37px;
            left: -100px;
            z-index: 9999999999;
        }

        #modal-contact {
            width: 500px;
            height: 195px;
            background-color: #fff;
            padding: 25px;
            margin: auto;
            position: relative;
            left: 0;
            top: 30%;
            right: 0;
        }

        @media (max-width: 575px) {
            #modal-contact {
                width: 100%;
                left: 50px;
                right: 0;
            }

            #form-contact {
                padding: 0 43px 0 32px;
            }
        }

        #modal-contact #close {
            font-size: 2rem;
            line-height: 45px;
            text-align: center;
            width: 50px;
            height: 50px;
            background-color: #fff;
            border-radius: 5px;
            position: absolute;
            left: -55px;
            top: -100px;
            cursor: pointer;
        }

        #modal-contact #message {
            color: #fff;
            width: 100%;
            padding: 18px;
            background-color: #3a9ce2;
            position: absolute;
            top: -100px;
            left: 0;
        }

        #modal-contact input {
            width: 100%;
            margin-bottom: 20px;
        }

        #modal-contact button {
            font-size: 1.8rem;
            font-weight: 600;
            color: #fff;
            position: absolute;
            width: 100%;
            left: 0;
            background-color: #62cb31;
            height: 50px;
            border: none;
            border-radius: 0 0 10px 10px;
        }

        .module-redirect {
            color: #fff;
            font-size: 2rem;
            width: 291px;
            background-color: #505050;
            padding: 10px 20px;
            border-radius: 10px 0 0 10px;
            position: absolute;
            top: 3%;
            right: -225px;
            transition: all 0.5s;
        }

        .module-redirect:hover {
            right: 0;
        }

        .module-redirect img {
            width: 30px;
            margin-right: 20px;
        }

        .module-redirect a {
            color: #fff;
        }

        iframe {
            width: 100%;
            height: 100vh;
        }
    </style>

    <div id="modal-contact-content">
        <div id="modal-contact">
            <div id="close">
                X
            </div>
            <div id="message">
                <h4 style="color:#fff;text-align:center">¡Urgente!</h4>
                <h5 style="color:#fff">no hemos podido contactarde, actualiza tus datos por favor.</h5>
            </div>
            <form id="form-contact">
                <input type="text" id="full_name" placeholder="Nombre Completo" autofocus required>
                <br>
                <input type="email" id="email" placeholder="Correo Electrónico" required>
                <br>
                <input type="tel" id="phone" placeholder="Teléfono Movil" required>
                <button type="submit">Guardar</button>
            </form>
        </div>
    </div>

    <div>
        <?php if (in_array("1000", $permisos) || $isAdmin == 't'): ?>
            <div id="dash">
                <iframe src="<?php echo base_url(); ?>index.php/frontend/iframe" frameborder="0"></iframe>
            </div>
        <?php endif; ?>

        <?php if (isset($data["tipo_negocio"]) && $data["tipo_negocio"] == "restaurante" && (in_array("1038", $permisos) || $isAdmin == 't')): ?>
            <div class="module-redirect">
                <img src="https://pos.vendty.com/uploads/mesas/dining-table_blanco_64.png" alt="barra">
                <a href="<?php echo site_url('frontend/zones'); ?>" id="text-module">Mesas y Toma Pedido</a>
            </div>
        <?php endif; ?>
    </div>

    <script src="<?php echo base_url(); ?>assets/api_url.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script>
        
        $(document).ready(function () {
            
            var auth = JSON.parse(localStorage.getItem('api_auth'));

            if(auth.hasOwnProperty('modal_contact')){
                console.log(auth.modal_contact);

                if (auth.modal_contact) {
                    $('#modal-contact-content').show();
                }

                $(window).keyup(function (event) {
                    if (event.keyCode === 27) {
                        $('#modal-contact-content').hide();
                    }
                });

                $('#close').click(function () {
                    $('#modal-contact-content').hide();
                });

                $('#form-contact').submit(function (event) {

                    event.preventDefault();

                    if ($('#full_name').val() === "" || $('#email').val() === "" || $('#phone').val() === "") {

                    } else {
                        axios.post(api_url + '/license-contact-info-update', {
                            full_name: $('#full_name').val(),
                            email: $('#email').val(),
                            phone: $('#phone').val(),
                        }, {
                            headers: {
                                'Authorization': 'Bearer ' + auth.token
                            }
                        }).then(function (response) {
                            if (response.data.status) {

                                auth.modal_contact = false;
                                localStorage.setItem('api_auth', JSON.stringify(auth));

                                $('#modal-contact-content').hide();
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    }
                });
            }

            //console.clear();
            
        });
    </script>
<?php endif; ?>

