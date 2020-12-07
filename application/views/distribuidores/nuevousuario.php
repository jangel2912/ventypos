
<style>
    .content-register{border: solid 1px lightgray; padding:20px; box-sizing:border-box; margin-left:10px;}
</style>

<div class="mask">
</div>

<h4>Nuevo usuario</h4>
<hr><br>
<p>A continuación podra ingresar un nuevo usuario</p>
<br>
<div class="row">
    <div class="col-md-5 content-register">
        <form>
            <div class="form-group">
                <label for="correo">Nombre</label>
                <input type="hidden" class="form-control" name="creation_distribuidor" id="creation_distribuidor" value="<?php echo $creation_distribuidor; ?>" >
                <input type="text" class="form-control" name="Last_Name" id="Last_Name" placeholder="Ingrese nombre" required>
            </div>
            <div class="form-group">
                <label for="correo">Correo electrónico</label>
                <input type="email" class="form-control" id="Email" name="Email" placeholder="Ejemplo@gmail.com" required>
            </div>
            <div class="form-group">
                <label for="clave">Teléfono</label>
                <input type="text" class="form-control" name="Mobile" id="Mobile" placeholder="999-999-9233" required>
            </div>
           <a class="btn btn-default" href="<?php echo site_url('administracion_vendty/distribuidores/configuracion');?>">Cancelar</a>
           <button type="submit" class="btn btn-default btn_new_count">Registrar nuevo usuario</button>
            
            <div class="loading-gif text-center hidden ">
                <img src="<?php echo base_url().'public/img/loader_gif.gif';?>" alt="Loading">
            </div>
        </form>
        
    </div>
</div>

<script>


    $(".btn_new_count").click(function(e){
        e.preventDefault();
        var Distribuidor_id = $("#creation_distribuidor").val();
        var Last_Name = $("#Last_Name").val();
        var Email = $("#Email").val();
        var Mobile = $("#Mobile").val();
        $(".btn_new_count").addClass('disabled');
        $(".loading-gif").removeClass('hidden');
        
        $.post("https://sign.vendty.com/index.php/auth/nueva_cuenta_distribuidor",{
        //$.post("<?php echo site_url('administracion_vendty/distribuidores/nuevo_usuario_distribuidor');?>",{
            Distribuidor_id: Distribuidor_id,
            Last_Name: Last_Name,
            Email: Email,
            Mobile: Mobile
        },function(data){
            $(".loading-gif").addClass('hidden');
            if(data == 1){
                alert("La cuenta ha sido creada correctamente");
            }else{
                alert("Error al crear la cuenta");
            }
        });
    })
    
</script>