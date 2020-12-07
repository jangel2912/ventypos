<style>
    .nav-tabs>li.active>a, .nav-tabs>li.active>a:focus, .nav-tabs>li.active>a:hover {
        color: #fff;
        background-color: #5cb85c;
        border-color: transparent;
        border-bottom-color: #5cb85c;
        border-radius: 5px 5px 0px 0px;
    }
</style>
<ul class="nav nav-tabs" id="tab_secciones_mesas">
    <?php  
    $active = 0;
    foreach ($secciones as $key => $una_seccion) {
        if($active == 0){
            $class_active = 'active';
        }else{
            $class_active = '';
        }
    ?>    
     <li class="<?php echo $class_active ?>">
        <a href="<?php echo '#tabseccion_'.$una_seccion->id?>" data-toggle="tab">
            <?php echo $una_seccion->nombre_seccion?> 
        </a>
     </li>
    <?php 
       $active+=1; 
    } 
    ?>
 </ul>   
<div class="tab-content">
    <?php 
    $active = 0;
    foreach ($mesas_secciones as $key => $una_seccion) {
         if($active == 0){
            $class_active = 'active';
        }else{
            $class_active = '';
        }
        ?>
        <div class="tab-pane <?php echo $class_active ?>" id="<?php echo 'tabseccion_'.$key?>">
        <?php foreach ($una_seccion as $key_2 => $una_mesa){ ?>
            <div class="span2 col-xs-2">
            <?php if($una_mesa->pedidos_en_mesa){ 
                    $url_imagen =  base_url().'uploads1/mesas/mesas_ocupadas_641.png';  
                }else{ 
                    $url_imagen =  base_url().'uploads1/mesas/mesas_641.png';
                  } ?>
                <img data-nombre="<?php echo $una_mesa->nombre_mesa ?>" data-mesa="<?php echo $una_mesa->id ?>" src="<?php echo $url_imagen ?>"  style="cursor: pointer;" onclick="seleccionar_mesa(this)"/>
                <center><?php echo $una_mesa->nombre_mesa ?> </center>
            </div>
        <?php } ?> 
        </div>      
     <?php
        $active +=1;
        } ?>    
</div>        
              
<!--mixpanel-->
<script>
        var id='<?php echo $this->session->userdata('user_id') ?>';       
        var email='<?php echo $this->session->userdata('email') ?>';
        var nombre_empresa='<?php echo $this->session->userdata('nombre_empresa') ?>';
        mixpanel.identify(id);   
</script>
<?php 
    if($data['estado']==2){?>
        
    <script>
        
        mixpanel.track("Mesas en Ventas Prueba", { 
            "$email": email,   
            "$empresa": nombre_empresa,    
        });    

    </script>

<?php
    }else{ ?>
        
    <script>
        
        mixpanel.track("Mesas en Ventas", { 
            "$email": email,   
            "$empresa": nombre_empresa,    
        });    

    </script>
<?php
    }?>