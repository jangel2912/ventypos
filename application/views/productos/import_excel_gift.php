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
        padding-bottom: 6px;
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
        background-color: #fafafa;
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

               <h4><i class="icon wb-upload" aria-hidden="true"></i> Importar GiftCard</h4> 
            
            </div>
            <div class="block" ><hr></div>
            <div class="block" style=" padding: 20px;">
                
                <div class="titulo2" ><h5>Instrucciones<h5></div> 
                <div class="intrucciones">
                    <ol>
                        <li><span>Descargue la plantilla de excel haciendo click <strong><a href="<?php echo base_url("/uploads1/Plantilla GiftCard.xlsx")."?".date("ytdGis"); ?>">AQUÍ</a></strong></span></li>
                        <li><span>Ingrese la información en la plantilla,  sin cambiar el formato de las celdas</span></li>
                        <li><span>Haga click en el botón buscar y seleccione la plantilla con los datos registrados anteriormente</span></li> 	
                        <li><span>Click en Guardar</span></li>
                    </ol>
                </div>

                            
                <?php echo validation_errors(); ?>

                <div class="data-fluid" style="overflow: hidden;">

                    <?php echo form_open_multipart("productos/import_excel_gift", array("id" => "validate")); ?>

                    <div class="row-form">

                        <div class="col-md-12">                            

                            <div class="input-append file">

                                <input type="file" name="archivo" placeholder="Archivo"/>

                                <input type="text" placeholder="Archivo"/>

                                <button class="btn" type="button">Buscar</button>

                            </div> 

                            <?php echo $data['data']['upload_error']; ?>

                        </div>

                    </div> 

                    </form>

                </div>
                            
            </div> 
            
                                
            <?php $message = $this->session->flashdata('message');
            if (!empty($message)){ ?>
                <div class="alert alert-error"><?php echo $message; ?></div>
            <?php } ?>                                
                                
            <div class="block" ><hr></div>
            
            <div class="block fondo2">
                
                <div id="btnEnviar" class="toolbar bottom tar">
                    
                    <button class="btn btn-success"  onclick="javascript:enviarFormulario();"  type="button">Enviar</button>
                    
                </div>      
                
            </div>
            
        </div>
        
    </div>
    
    <div class="col-md-3"></div>
    

</div>

<script>

    function enviarFormulario(){
        $("#validate").submit();
        $("#btnEnviar .btn").attr("onclick","javascript:void(0)");
    }
    
</script>