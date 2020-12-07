<!DOCTYPE html>

<html>
    <head>
    <?php include "./application/views/analytics.php"; ?>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />

    <!--[if gt IE 8]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <![endif]-->

    <title>Vendty POS</title>
    <link rel="icon" type="image/ico" href="<?php echo base_url();?>public/img/favicon.ico?act=2"/>

    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/bootstrap-extend.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/site.min.css">

    <!-- Plugins -->
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/animsition.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/asScrollable.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/switchery.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/introjs.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/slidePanel.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/flag-icon.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/login-v2.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/web-icons.min.css">
    <link rel="stylesheet" href="<?php echo base_url();?>public/css/brand-icons.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
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

    <?php
        get_css("<link rel='stylesheet' href='$1'></script>");
        $background = rand(1, 6);
    ?>

    <style>
        .page-login-v2:before{
            background-image:url('<?php echo base_url('public/img/backgrounds/bannerhome_'.$background.'.png');?>');
        }
    </style>
    </head>

    <!--<body class="page-login-v2 layout-full page-dark">-->
    <body>
      <!--  <div id="loader"><img src="<?php echo base_url();?>public/img/loader.gif"/></div> -->
