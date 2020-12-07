<style>
    .containre{
        margin-top: 50px;
    }
    .panel {
        padding: 0px;
    }
    .block{
        margin-bottom: 0px !important;
    }
    hr{
        margin: 0px;
        border-color: #dcdcdc;
    }
    
    .titulo{        
        padding: 12px;
        padding-left: 25px;
    }
    .titulo h4{        
        color: #313131 !important;
    }
    
    .titulo2 h5{
        text-align: center;
        padding: 0px;
        margin: 0px;
        margin-bottom: 10px; 
        font-size: 16px;
        margin-bottom: 18px !important;
    }
    ol{
        font-weight: 500;
    }    
    ol li{
        margin-bottom: 15px !important;        
    }
    li span{
        font-weight: 100;
    }
    
    .fondo2{
        background-color: #fff;
    }
    code{
        margin: 0px 3px;
        white-space:nowrap;
        font-weight: 500;
    }

    .body .content #validate .btn {
        background: #eaeaea !important;
        border-color: transparent !important;
        padding: 3px 6px;
        margin-bottom: 4px;
        border-radius: 0px 4px 4px 0px !important;
        color: #656565;
        border: 1px solid #bbbbbb !important;
    }
    .input-append input{
        border-radius: 4px 0px 0px 4px !important;
        border-right: 1px solid #bbbbbb !important;
        background-color: #f2f6f9;
    }
    
    #btnEnviar{
        float: right;
        padding: 12px;
    }
    
    #btnEnviar .btn{
        padding: 3px 25px;
        font-size: 14px;
        /* font-weight: 100; */
        margin-right: 10px !important;
    }
    .listado .fila{
        border-bottom: 1px solid #dadada !important;
        padding: 5px;
        font-size: 14px;
    }
    
    .listado .fila:last-child{
        border-bottom: none !important;
    }
    
    .listado .filaCanjeada{
        background-color: #fbf4f4;
    }
    .listado .filaVendida{
        background-color: #effde1;
    }
    .listado .filaActiva{
        background-color: #f6fbff;
    }
    

    .filaCanjeada .estatus,.filaCanjeada .icono{
        font-size: 12px;
        color: #e44f26;
        font-weight: 500;        
    }
    .filaVendida .estatus, .filaVendida .icono{
        font-size: 12px;
        color: #5ABD2C;
        font-weight: 500;
    }
    
    .filaActiva .estatus, .filaActiva .icono{
        font-size: 12px;
        color: #59a2b9;
        font-weight: 500;
    }
    
    .fila .icono{
        height: 40px;
        width: 40px;
        float: left;
        font-size: 20px;
        padding: 5px 0px 0px 7px;
    }
    

    .informacion{
        color: #464646;
    }
    
    .titulo a{
        font-weight: 100;
        float: right;
        font-size: 14px;
        margin: 2px 10px 0px 0px;
    }
    .left{
        float:left;
    }
    
    .right{
        float:right;
    }
    
    #btnBuscar{
        background: #eaeaea !important;
        border-color: transparent !important;
        padding: 3px 6px;
        margin-bottom: 0px;
        border-radius: 0px 4px 4px 0px !important;
        color: #656565;
        border: 1px solid #bbbbbb !important;    
        float: right;
        height: 25px;
        padding-top: 4px;
        font-size: 10px;
    }
    
    #buscar{
        border-radius: 4px 0px 0px 4px !important;
        border-right: none;
        background-color: #f2f6f9;
        width: 150px;
        height: 25px;
    }
    
    #filter{
        padding: 9px 10px 5px 15px;
        height: 42px;
    }
    
    #filter .left a{
        margin-right: 5px;
    }
    
    #filter .left{
        margin-top: 2px;
    }
    
    #cantidad{
        color: #313131;
    }
    
    #cantidadContainer{
        border-left: 1px solid #d2d2d2;
        padding-left: 10px;
        margin-left: 10px;
        font-weight: 500;
    }

    
</style>
<div class="page-header">    
    <div class="icon">
        <img alt="GiftCards" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_giftcards']['original'] ?>">        
    </div>
     <h1 class="sub-title"><?php echo custom_lang("GiftCards", "GiftCards");?></h1>
</div>

<div class="row containre" >
    
    <div class="col-md-3"></div>
    
    <div class="col-md-6">
        
        <div class="panel newPanel">

            <div class="titulo fondo2">
                <h4 style=" margin-bottom:3px">
                    <div class="icon iconbotones"> <img alt="exportar" src="<?php echo $this->session->userdata('new_imagenes')['giftcards']['original'] ?>" ></div>
                    GiftCards
                    <a href="<?php echo site_url(); ?>/productos/import_excel_gift/"><div class="icon iconbotones"> <img alt="exportar" src="<?php echo $this->session->userdata('new_imagenes')['importar']['original'] ?>" ></div> Importar</a>
                </h4>                 
            </div>
            <div class="block"><hr></div>
            <div id="filter" >
                
                <div class="left">
                    <a href="javascript:filtro('todos')">Todos</a>
                    <a href="javascript:filtro('activos')">Activos</a>
                    <a href="javascript:filtro('pagados')">Pagados</a>
                    <a href="javascript:filtro('canjeados')">Canjeados</a>
                </div>
                <div id="cantidadContainer" class="left">
                    <span style=" font-weight: 100"> Total : </span><span id="cantidad" > <?php echo $data['cantidad']['total']; ?> </span>
                </div>
                <div class="right">
                    <input id="buscar" type="text" placeholder="Buscar código"/>
                    <button id="btnBuscar" class="btn" type="button"><i class="icon glyphicon glyphicon-search" aria-hidden="true"></i></button>
                </div>
                
            </div>
            <div class="block"><hr></div>
            <div class="block listado" style=" padding:0px;">
                
                
                <?php foreach( $data['data'] as $val ){ ?>

                
                <?php if( $val["estado"] == 0 ){ ?>
                
                <div id="<?php echo $val["codigo"]; ?>" class="fila filaCanjeada" >
                    <div class="icono"><div class="icon iconbotones"> <img alt="giftcards" src="<?php echo $this->session->userdata('new_imagenes')['giftcards_canjeada']['original'] ?>" ></div></div>
                    <div class="informacion">
                        GiftCard: <strong><?php echo $val["codigo"]; ?></strong> - Precio: <strong>$ <?php echo $val["valor"]; ?></strong>
                    </div>
                    <div class="estatus">Canjeada</div>
                </div> 
                
                <?php } ?>

                <?php if( $val["estado"] == 2 ){ ?>
                
                <div id="<?php echo $val["codigo"]; ?>" class="fila filaVendida" >
                    <div class="icono"><div class="icon iconbotones"> <img alt="giftcards" src="<?php echo $this->session->userdata('new_imagenes')['giftcards_paga']['original'] ?>" ></div></div>
                    <div class="informacion">
                        GiftCard: <strong><?php echo $val["codigo"]; ?></strong> - Precio: <strong>$ <?php echo $val["valor"]; ?></strong>
                    </div>
                    <div class="estatus">Pagada</div>
                </div> 
                
                <?php } ?>

                <?php if( $val["estado"] == 1 ){ ?>
                
                <div id="<?php echo $val["codigo"]; ?>" class="fila filaActiva" >
                    <div class="icono"><div class="icon iconbotones"> <img alt="giftcards" src="<?php echo $this->session->userdata('new_imagenes')['giftcards_activa']['original'] ?>" ></div></div>
                    <div class="informacion">
                        GiftCard: <strong><?php echo $val["codigo"]; ?></strong> - Precio: <strong>$ <?php echo $val["valor"]; ?></strong>
                    </div>
                    <div class="estatus">Activa</div>
                </div> 
                
                <?php } ?>
                
                
                <?php } ?>            
                            
            </div> 
                               
                                
            <div class="block" ><hr></div>
            
            <div class="block fondo2">
                
                <div id="btnEnviar" class="toolbar bottom tar">
                                        
                    <a class="btn btn-success" href="<?php echo site_url(); ?>/productos/index/" > Continuar </a>
                    
                </div>      
                
            </div>
            
        </div>
        
    </div>
    
    <div class="col-md-3"></div>
    

</div>

<script>
    /*
     <?php print_r($data); ?>
     */
    var total = "<?php echo $data['cantidad']['total']; ?>" ;
    var activo = "<?php echo $data['cantidad']['activo']; ?>" ;
    var pagado = "<?php echo $data['cantidad']['pagado']; ?>" ;
    var canjeado = "<?php echo $data['cantidad']['canjeado']; ?>" ;
    
    function ocultar(){
        $(".fila").hide();
    }
    
    function buscar(codigo){
        ocultar();
        $("#"+codigo).show();
    }
    
    function filtro(tipo){
        
        ocultar();
        
        if( tipo == "todos" ) {
            $(".fila").show();
            $("#cantidad").html(total);
        }
        if( tipo == "activos" ) {
            $(".filaActiva").show();
            $("#cantidad").html(activo);
        }
        if( tipo == "pagados" ){
            $(".filaVendida").show();
            $("#cantidad").html(pagado);
        }
        if( tipo == "canjeados" ){
            $(".filaCanjeada").show();
            $("#cantidad").html(canjeado);
        }
        
    }
    
    $( document ).ready(function(){                           
        
        $("#btnBuscar").click(function(){
            buscar( $("#buscar").val() );
            $("#cantidad").html("");
        });
        
        $("#buscar").keydown(function( event ) {
            if ( event.which == 13 ) {
                buscar( $("#buscar").val() );
                event.preventDefault();
                $("#cantidad").html("");
            }
        });

    });

</script>