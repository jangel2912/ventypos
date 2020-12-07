<style>
    .content-title, .content-file{border-bottom: solid 1px lightgray; padding: 5px; margin-bottom: 5px;}
    .vendty{color:rgb(104, 175, 39);}
    .formulario-recomendacion label{color:rgb(104, 175, 39);}
    .formulario-recomendacion button{background-color: rgb(104, 175, 39); color: #fff;}
    .icono-recomendado{width:21px; margin-right:4px;}
    .btn-icon{background-color: #e0e0e0; color: #333; border-radius:31px; padding: 8px !important;}
    .btn-icon:hover{background-color: lightgray; }
    .form-control:not(select){padding-top:17px;padding-bottom:17px;}
    .close-page{float: right;font-size: 23px;cursor: pointer; color: lightslategray;}
</style>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h4 class="content-title">Recomendar a un Amigo<a href="<?php echo site_url();?>" class="close-page">x</a></h4>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10">
            <h3>Le gusta usar <span class="vendty">Vendty?</span> Cuénteselo a sus amigos</h3>
            <br>
            <button class="btn btn-icon"><img class="icono-recomendado" src="<?php echo base_url().'public/img/fb.png'?>"> Facebook</button>
            <button class="btn btn-icon"><img class="icono-recomendado" src="<?php echo base_url().'public/img/tw.png'?>">Twitter</button>
            <button class="btn btn-icon"><img class="icono-recomendado" src="<?php echo base_url().'public/img/yt.png'?>">Youtube</button>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-md-6">
        <form class="formulario-recomendacion" action="<?php echo site_url('frontend/enviar_recomendacion')?>" method="post">
            <div class="form-group">
                <label for="correo_recomendacion">Correo electrónico</label>
                <input type="email" class="form-control" name="correo_recomendacion" id="correo_recomendacion" placeholder="El correo electrónico empresarial de su amigo" required>
            </div>
            <div class="form-group">
                <label for="asunto_recomendacion">Asunto</label>
                <input type="text" class="form-control" name="asunto_recomendacion" id="asunto_recomendacion" placeholder="Obtenga más información sobre Vendty" required>
            </div>
            <div class="form-group">
                <label for="mensaje_recomendacion">Mensaje</label>
                <textarea class="form-control" rows="8" name="mensaje_recomendacion" required>Hola,

Me encanta facturar con Vendty y creo que será fantástico también para su negocio.
Dele una oportunidad y compruébelo usted mismo.

Saludos</textarea>
            </div>
            <button type="submit" class="btn btn-success">Enviar a un Amigo</button>
        </form>
        </div>
    </div>
</div>
