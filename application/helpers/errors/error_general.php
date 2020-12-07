<!DOCTYPE html>
<html lang="en">
<head>
<title>Error</title>
<link rel="icon" type="image/ico" href="favicon.ico"/>
    
    <link href="<?php echo base_url();?>public/css/stylesheets.css" rel="stylesheet" type="text/css" />
    <!--[if lte IE 7]>
        <link href="<?php echo base_url();?>public/css/ie.css" rel="stylesheet" type="text/css" />
        <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/other/lte-ie7.js'></script>
    <![endif]-->    
    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/jquery/jquery-1.9.1.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/jquery/jquery-ui-1.10.1.custom.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/jquery/jquery-migrate-1.1.1.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/jquery/globalize.js'></script>
    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/other/excanvas.js'></script>
    
    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/other/jquery.mousewheel.min.js'></script>
        
    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/bootstrap/bootstrap.min.js'></script>
    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/cookies/jquery.cookies.2.2.0.min.js'></script>   
    
    <script type='text/javascript' src="<?php echo base_url();?>public/js/plugins/uniform/jquery.uniform.min.js"></script>
    
    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/shbrush/XRegExp.js'></script>
    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/shbrush/shCore.js'></script>
    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/shbrush/shBrushXml.js'></script>
    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/shbrush/shBrushJScript.js'></script>
    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins/shbrush/shBrushCss.js'></script>    
    
    <script type='text/javascript' src='<?php echo base_url();?>public/js/plugins.js'></script>
    <script type='text/javascript' src='<?php echo base_url();?>public/js/charts.js'></script>
    
    <script type='text/javascript' src='<?php echo base_url();?>public/js/actions.js'></script>
</head>
<body>
         <div id="loader"><img src="<?php echo base_url();?>public/img/loader.gif"/></div>
         <div class="errorContainer">
            <h1>ERROR</h1>
            <h2><?php echo $message;?></h2>
            <button class="btn btn-primary btn-large" onClick="document.location.href = '<?php echo site_url();?>';">Ir al inicio</button> <button class="btn btn-large" onClick="history.back();">Ir atras</button>
         </div>
</body>
</html>