<?php
    $resultPermisos = getPermisos();
    $permisos = $resultPermisos["permisos"];
    $isAdmin = $resultPermisos["admin"];
?>
<style>
    .glyphicon {
        color: white !important;
    }
</style>

<div class="container-sidebar"> 
    <div class="sidebar">
    </div> 
</div>     
<!--div class="header-content">
    <h2><i class="fa fa-list-alt"></i>Orden de Compra <span>productos</span></h2>
    <div class="breadcrumb-wrapper hidden-xs">
        <span class="label">Estas aqui:</span>
        <ol class="breadcrumb">
            <li class="active">Orden de compra</li>
        </ol>
    </div>
</div-->
<div class="body-content">
    <div class="row">
        <!--div class="col-md-12">
            <form action="">
                <div class="row">
                    <div class="form-group">
                        <div class="col-md-6">
                            <div class="input-group mb-15">
                                <span class="input-group-addon bg-success"><i class="fa fa-search"></i></span>
                                <select name="" class="form-control" id="slt_producto"></select>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div-->
        <div class="col-md-7">
            <div class="col-md-12 content-carousel-toma-pedido">
                <div id="carousel-toma-pedido" class="owl-carousel carousel-toma-pedido">
                    <?php 
                        foreach($data['categorias']['registros'] as $categoria):
                    ?>
                    <div class="item-categorie"> 
                        <a class="category-option" data-id="<?php echo $categoria['id']; ?>">
                            <img width="50px" height="50px" src="<?php echo base_url().'uploads/'.$this->session->userdata('base_dato').'/categorias_productos/'.$categoria['imagen']; ?>" alt=""><br>
                            <span><?php echo strtoupper($categoria['nombre']); ?></span>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div><br>
            </div>

            <!--<div class="row">
                <div class="col-md-12"  id="product_category" >
                    <div class="panel">
                    <div class="panel-body">
                        
                      <div class="col-md-12">
                            <div class="product_category">
                                <ul class="nav-list list-inline">
                                
                                    <?php 
                                        foreach($data['categorias']['registros'] as $categoria):
                                    ?>
                                    <li>
                                        <a class="category-option" data-id="<?php echo $categoria['id']; ?>">
                                            <img width="50px" height="50px" src="<?php echo base_url().'uploads/'.$categoria['imagen']; ?>" alt="">
                                            <span><?php echo $categoria['nombre'] ?></span>
                                        </a>
                                    </li>
                                    <?php endforeach; ?>    
                                </ul>
                            </div>    
                        </div>
                       

                    </div>
                    </div>
                </div>
            </div>-->
            <div class="col-md-12">
                <div class="thumb-productos row">
                </div>
            </div>
        </div>
        <div class="col-md-5 scrollable" id="myScrollspy"  data-spy="affix" data-offset-top="50" data-offset-bottom="200">
            <div class="panel panel-border panel-success">
                <div class="panel-heading panel-orden">
                    <form action="">
                        <input type="hidden" id="txt_seccion" name="seccion" value="<?php echo $data['seccion']; ?>">
                        <input type="hidden" id="txt_mesa" name="mesa" value="<?php echo $data['mesa']; ?>">                        
                    </form>                        
                    <?php echo '<h5>Zona: '; if (isset($data["seccion_mesa"]->nombre_seccion)){ echo $data["seccion_mesa"]->nombre_seccion;  }else{ echo "Barra";}  echo' - Mesa: '; if (isset($data["seccion_mesa"]->nombre_mesa)){echo $data["seccion_mesa"]->nombre_mesa;}else{ echo"Barra"; } echo'</h5>';?>
                </div>
                <div class="panel-body">
                    <table id="tbl_orden" class="table hidden">
                        <thead>
                            <tr>
                                <td></td>
                                <td><strong>Producto</strong></td>
                                <td width="30%"><strong>Cantidad</strong></td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <div id="loading-indicator" class="text-center" style="display:none;">
                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                            </div>
                        </tbody>
                    </table>
                </div>
                <div class="panel-footer">
                    <button id="btn_cancelar" class="btn btn-default ">Cancelar Pedido</button>
                    <button id="btn_confirmar" class="btn btn-success">Comanda</button>
                    <?php if(in_array("1038", $permisos) || $isAdmin == 't'): ?>
                        <a href="<?php echo base_url().'index.php/ventas/nuevo/'.$data['seccion'].'/'.$data['mesa']; ?>"  class="btn btn-success pagarTomapedido">Pagar</a>
                    <?php endif; ?>

                    <button class="btn btn-success"  data-toggle="modal" data-target="#modal-cambio-mesa">Cambiar de mesa</button>

                    <div class="pull-right">
                        <div id="orden_result" class="text-center">
                            <h3>Total: $0</h3>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>    
    </div>
</div>        

<!-- Modal cambio de mesa -->
<div class="modal fade" id="modal-cambio-mesa" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center">Cambio de mesa</h4>
      </div>
      <div class="modal-body">
        <p>Por favor selecciona una de las mesas disponibles y haga clic en guardar.</p>
       
            <?php foreach($data["data_mesas_disponibles"]["zonas"] as $zona): ?>
                <div class="row zonas_mesas">    
                    <div class="col-md-12"><h4><?php echo $zona["nombre"]; ?></h4></div>
                    <?php foreach($data["data_mesas_disponibles"]["mesas"] as $mesa): ?>
                        <?php if($mesa->id_zona == $zona["id"] ): ?> 
                                <div class="col-md-2  text-center">
                                    <div class="mesa_home" data-idzona="<?php echo $zona['id'];?>" data-idmesa="<?php echo $mesa->id;?>">
                                        <img src="<?php echo base_url();?>public/img/mesa.png" alt="Mesa disponible">  <br>
                                        <?php echo $mesa->nombre;?>                   
                                    </div>            
                                </div>
                            
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary btn_cambiar_mesa">Cambiar mesa</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal  -->

<script>
    $(document).ready(function(){
      $("#carousel-toma-pedido").owlCarousel({
          loop:true,
          dots: false,
          nav:true,
          items:5,
          navSpeed: 1000,
          navText : ["<img src='<?php echo base_url().'public/img/prev-carousel.png'?>'>","<img src='<?php echo base_url().'public/img/next-carousel.png'?>'>"],
      });
    });

    var zona_seleccionada = 0;
    var mesa_seleccionada = 0;
    $(".zonas_mesas .mesa_home").each(function(index,element){
        $(this).click(function(){
            atualizar_mesa_seleccionada();
            $(this).css({"background-color":"#ffd54f"});
            zona_seleccionada = $(this)[0].dataset.idzona;
            mesa_seleccionada = $(this)[0].dataset.idmesa;
        })
    })
    function atualizar_mesa_seleccionada(){
        $(".zonas_mesas .mesa_home").each(function(){
            $(this).css({"background-color":"#62cb31"});
        });
    }
    $(".btn_cambiar_mesa").click(function(){
        if(zona_seleccionada == 0 && mesa_seleccionada == 0){
            alert("Debes seleccionar una mesa");
        }else{
            $.post("<?php echo site_url().'/orden_compra/changueOrden';?>",{
                zona_seleccionada : zona_seleccionada,
                mesa_seleccionada : mesa_seleccionada,
                zona_anterior : '<?php echo $this->uri->segment(3);?>',
                mesa_anterior : '<?php echo $this->uri->segment(4);?>'
            },function(data){
                if(data){
                    alert("Se ha cambiado la mesa satisfactoriamente");
                    setTimeout(function(){ 
                        location.href= "<?php echo site_url().'/tomaPedidos'?>";
                     }, 500);
                }else{
                    alert("Error al momento de cambiar de mesa");
                }
            });
        }
        
    })

    //pagarTomapedido
    $(".pagarTomapedido").click(function(e){
        
        e.preventDefault();
        zona= '<?php echo $this->uri->segment(3);?>';
        mesa= '<?php echo $this->uri->segment(4);?>';
        url=$(this).attr('href');
        
        $.post("<?php echo site_url().'/ventas/eliminar_dividir_cuenta';?>",{                
            zona : zona,
            mesa : mesa
        },function(data){
            if(data){                   
                setTimeout(function(){ 
                    location.href= url;
                }, 500);
            }
        });
    })
</script>