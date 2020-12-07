
<?php 
    $idUsuario = $this->session->userdata('user_id');    
?>


<style>
    
    .container{        
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        font-family: sans-serif;
        font-size: 20px;
    }
    
</style>
    
<div class="container">
    
    Iniciando Backup Offline...
    
</div>

<script src="<?php echo base_url('public/js/plugins/jquery/jquery-1.9.1.min.js'); ?>" type="text/javascript"></script>
<script>



            setTimeout( function () {                                             
                
                $.ajax({
                    type: "POST",
                    url: "<?php echo site_url(); ?>/frontend/recuperarManifest",
                    async: false,
                    dataType: 'text',
                    success: function (response) {                                            
                        window.location = "<?php echo site_url(); ?>/frontend?offline=backup";
                    },
                    error: function (xhr, textStatus, errorThrown) {                    
                    }
                });             
            },1000);

    </script>

    
     

<!-- APP OFFLINE -->
<iframe id="frameOffline" style="display: none;"></iframe>
<script>  document.getElementById('frameOffline').src = "<?php echo base_url(); ?>uploads/offline/offlineLoaderV2_<?php echo $idUsuario; ?>.html?v6.2" </script>