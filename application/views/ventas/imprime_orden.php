<html xmlns="http://www.w3.org/1999/xhtml">

    <head>

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket.css"); ?>" media="screen"/>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url("/public/css/ticket_print.css") ?>"  media="print"/>

    </head>

    <body>
    
        <div id="contenedor">

            <div id="print_area">

                <?php                    
                        $this->load->view("ventas/_imprime_orden.php", array('data' => $data));                    

                ?>

            </div>

        </div>

    </body>

</html>

<script type="text/javascript">

    window.print();

</script>