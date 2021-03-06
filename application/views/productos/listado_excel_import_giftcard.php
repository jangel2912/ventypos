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
    
    .listado .filaError{
        background-color: #fbf4f4;
    }
    .listado .filaOk{
        background-color: #effde1;
    }

    .filaError .estatus,.filaError .icono{
        font-size: 12px;
        color: #e44f26;
        font-weight: 500;        
    }
    .filaOk .estatus, .filaOk .icono{
        font-size: 12px;
        color: #5ABD2C;
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
    
</style>
    


<div class="row containre" >
    
    <div class="col-md-3"></div>
    
    <div class="col-md-6">
        
        <div class="panel newPanel">

            <div class="titulo fondo2">

                <h4 style=" margin-bottom:3px"><i class="icon wb-list" aria-hidden="true" style=" margin-right: 15px;"></i> Resultado Importación GiftCard</h4> 
            
            </div>
            <div class="block" ><hr></div>
            <div class="block listado" style=" padding:0px;">
                
                
                <?php foreach( $data['data'] as $val ){ ?>

                
                <?php if( $val["result"] == 0 ){ ?>
                
                <div class="fila filaError" >
                    <div class="icono"><i class="icon wb-warning" aria-hidden="true" style=" margin-right: 15px;"></i></div>
                    <div class="informacion">
                        GiftCard: <strong><?php echo $val["codigo"]; ?></strong> - Precio: <strong>$ <?php echo $val["valor"]; ?></strong>
                    </div>
                    <div class="estatus">Código ya existe</div>
                </div> 
                
                <?php } ?>

                <?php if( $val["result"] == 1 ){ ?>
                
                <div class="fila filaOk" >
                    <div class="icono"><i class="icon wb-check" aria-hidden="true" style=" margin-right: 15px;"></i></div>
                    <div class="informacion">
                        GiftCard: <strong><?php echo $val["codigo"]; ?></strong> - Precio: <strong>$ <?php echo $val["valor"]; ?></strong>
                    </div>
                    <div class="estatus">Giftcard creada correctamente</div>
                </div> 
                
                <?php } ?>

                <?php if( $val["result"] == 2 ){ ?>
                
                <div class="fila filaError" >
                    <div class="icono"><i class="icon wb-warning" aria-hidden="true" style=" margin-right: 15px;"></i></div>
                    <div class="informacion">
                        GiftCard: <strong><?php echo $val["codigo"]; ?></strong> - Precio: <strong>$ <?php echo $val["valor"]; ?></strong>
                    </div>
                    <div class="estatus">Código mayor a 15 caracteres</div>
                </div> 
                
                <?php } ?>
                
                
                <?php } ?>            
                            
            </div> 
                               
                                
            <div class="block" ><hr></div>
            
            <div class="block fondo2">
                
                <div id="btnEnviar" class="toolbar bottom tar">
                                        
                    <a class="btn" href="<?php echo site_url(); ?>/productos/index/" > Continuar </a>
                    
                </div>      
                
            </div>
            
        </div>
        
    </div>
    
    <div class="col-md-3"></div>
    

</div>

<script>


</script>