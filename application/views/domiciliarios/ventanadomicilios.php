<style>
    .domiciliarios{
        opacity: 0;
        visibility: hidden;
        transition: opacity 1s ease;
        -webkit-transition: opacity 1s ease;
    }
    .domiciliarios .slick-initialized ,
    .domiciliarios.slick-initialized {
        visibility: visible;
        opacity: 1;    
    }
    .slick-initialized{
        display:block !important;
    }
    .domiciliarios .slick-slide{
        min-width:123px;
    }
</style>

<div id="dialog-domicilio-form" style="display:none" title="<?php echo custom_lang('sima_pay_information', "Domicilios"); ?>">
    <form id="client-form" style="overflow: hidden !important;">
        <div class="col-md-12">
            <div class="domiciliarios">                                              
                <?php
                foreach($data['domiciliarios'] as $key => $value):

                    if(!empty($value['logo'])){
                        $nombre=base_url('uploads/'.$this->session->userdata('base_dato').'/domiciliarios/'.$value['logo']);
                    }else{
                        $nombre=base_url().'uploads/default.png';
                    }
                    ?>
                    <div>
                        <a class="domiciliarios-option" data-id="<?php echo $value['id']; ?>" id="<?php echo $value['id']; ?>">
                            <img style="width: 50px; height: 50px;" src="<?= $nombre;?>" alt="<?= $value['id']; ?>">
                            <p><?php echo $value['descripcion']; ?></p>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="col-md-12" style="margin-top:3%">
            <div class="col-md-4">
                <div class="input-group" style="padding-left: 0px !important;">    
                    <input type="hidden" name="domiciliario" id="domiciliario" >                                                             
                    <input type="hidden" value="" name="id_cliente_domicilio" id="id_cliente_domicilio"/>
                    <input type="hidden" value="no" name="presione_domicilio" id="presione_domicilio"/>
                    <input list="cliente_domicilio" autocomplete=off type="text" data-id="0" class="form-control" required placeholder="Cliente" value="<?php echo set_value('datos_cliente'); ?>" name="datos_cliente_domicilio" id="datos_cliente_domicilio" />
                    <span class="input-group-addon" id="add-new-client">
                        <div><span class="icon ico-plus vender"></span></div>
                    </span>
                </div>            
            </div>    
            <div class="col-md-4">                        
                <input type="text" class="form-control" placeholder="Teléfono" value="" required name="telefono_domicilio" id="telefono_domicilio"/>                                                    
            </div>     
            <div class="col-md-4">                                        
                    <input type="text" class="form-control" placeholder="direccion" value="" required name="direccion_domicilio" id="direccion_domicilio"/>
            </div>   
        </div>

        <div class="col-md-12">
            <div id="cliente_domicilio">
                <div class="row-form">
                    <div id="contenedor-lista-clientes-domicilios">
                        <ul id="lista-clientes-domicilios"> </ul>
                    </div>
                </div>
            </div> 
        </div>  
    </form>
</div>
                

<script>
$(document).ready(function () {
    
    $("#dialog-domicilio-form").dialog({
        autoOpen: false,
        height: 400,
        width: 500,
        modal: true,
        buttons: {
            "Guardar": function () {                   
                domiciliario=$("#domiciliario").val();
                datos_cliente_domicilio=$("#datos_cliente_domicilio").val();
                telefono_domicilio=$("#telefono_domicilio").val();
                direccion_domicilio=$("#direccion_domicilio").val();
                if((domiciliario!="") && (datos_cliente_domicilio !="") && (telefono_domicilio !="" ) && (direccion_domicilio !="")){
                    $("#presione_domicilio").val("si");
                    $("#sobrecostos_input").val(0);
                    $(this).dialog("close");
                }else{
                    $("#dialog-domicilio-form").dialog("close");
                    swal({
                        position: 'center',
                        type: 'error',
                        title: "Error",
                        html: "Todos los Campos son requeridos",
                        showConfirmButton: false,
                        timer: 1500
                    })
                    setTimeout(function(){ $("#dialog-domicilio-form").dialog("open");  }, 1600);                        
                }
                
            },
            "Cancelar": function () {                    
                $(this).dialog("close");
            }
        }
    });

    $(".domiciliarios-option").click(function() { 
        id=$(this).data('id');
        btn=$(this);
        $("#domiciliario").val(id);
        //busco los demas img y cambio de tamaño
        $(".domiciliarios-option").each(function(index) {
            console.log(index + ": " + $(this).text());
            console.log($(this).attr('id'));
            btn2=$(this);
            img2 = btn2.find('img'); 
            img2.css('width','50');
            img2.css('height','50');
        });

        img = btn.find('img'); 
        img.css('width','60');
        img.css('height','60');
        btn.find('p').css('color','#5ca745');
    });  

    $("#domicilio").click(function() {   
        $(".domiciliarios").slick("refresh");
        cliente = $("#id_cliente_domicilio").val();
        if (cliente!=""){        
            $("#datos_cliente_domicilio").val($("#datos_cliente").val());
            $("#datos_cliente_domicilio").prop('disabled',true);
        }
        
        $("#dialog-domicilio-form").dialog("open");
    });
});
</script>
<script type="text/javascript">
    $('.domiciliarios').slick({
       dots: false,
        infinite: true,
        speed: 300,
        prevArrow: '<div class="slick-prev" style="margin-top: -20px !important;"><img style="width: 15px; height: 15px;" src="<?php echo base_url();?>uploads/mesas/flechaizquierdaverdegruesa.png" alt="prev"></div>',
        nextArrow: '<div class="slick-next" style="margin-top: -20px !important;"><img style="width: 15px; height: 15px;" src="<?php echo base_url();?>uploads/mesas/flechaderechaverdegruesa.png" alt="ext"></div>',
        slidesToShow: 4,
        slidesToScroll: 3,
        responsive: [
            {
            breakpoint: 1024,
            settings: {
                slidesToShow: 3,
                slidesToScroll: 3,
                infinite: true,
                dots: true
            }
            },
            {
            breakpoint: 600,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 2
            }
            },
            {
            breakpoint: 480,
            settings: {
                slidesToShow: 1,
                slidesToScroll: 1
            }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });
    //$(".domiciliarios").trigger('click');
</script>