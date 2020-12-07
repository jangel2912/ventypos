<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    </head>
    <body>
        <div id="contenedor">
            <div id="print_area">
                <?php
                    $this->load->view("inventario/_imprime.php", array('data' => $data));
                ?>
            </div>
        </div>
    </body>
</html>
<script type="text/javascript">
    window.print();
</script>