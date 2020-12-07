<?php
    if (isset($data)){  
        extract($data);
    }
?>

<div class="page animsition vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out" style="animation-duration: 800ms; opacity: 1;">
    
    <div class="page-content vertical-align-middle">
        <img src="<?php echo base_url('public/img/logo_login.png'); ?>" alt="Logo"/>
        <?php if (!empty($message)): ?>
            <div class="alert alert-error">
                <?php echo $message; ?>
            </div>
        <?php endif;?>
        <h2>¿Olvidaste tu contraseña ?</h2>
        <p>Ingrese su correo electrónico para restablecer su contraseña</p>

        <?php echo form_open("auth/forgot_password"); ?>
            <div class="form-group">
                <?php echo form_input($email); ?>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block" style="background: #31cc33 !important; border:1px #31cc33 !important;">Restablecer su contraseña</button>
            </div>
            
            <div class="alert alert-danger" data-keyboard="true" data-backdrop="static" role="alert">
                <strong>Nota: </strong>Si usted no es el administrador  del sistema solicite al mismo el reincio de la clave de acceso
            </div>
        <?php echo form_close(); ?>
    </div>
</div>


<div class="social">
		<ul>
			<!--<li><a href="#myModalvideo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>-->
			<li><a href="#myModalvideovimeo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>			
		</ul>
	</div>       
     <!-- vimeo-->    
    <div id="myModalvideovimeo" class="container modal fade" style="margin-left: 20%;margin-top: 5%;">
        <div style="padding:56.25% 0 0 0;position:relative;">
            <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266924811?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:60%;height:60%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        </div>
    </div>
      <!-- youtuve-->    
     <!--
    <div id="myModalvideo" class="modal fade">  
         <iframe id="cartoonVideo" src="https://www.youtube.com/embed/30VaTI8pFj4?rel=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>                     
    </div>  -->

