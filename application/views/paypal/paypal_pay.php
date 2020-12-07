<!DOCTYPE html>

<html>

    <head>

        <title>Procesando pago...</title></head>

    <body onload="document.forms['paypal_forms'].submit();" >

       <form action="<?php echo $this->config->item('paypal_url');?>" method="post" name="paypal_forms">

                <input type="hidden" name="no_shipping" value="1">        

                <input type="hidden" name="cbt" value="Presione aqu&iacute; para guardar la operacion en <?php echo $this->config->item('site_title');?>">



                <input type="hidden" name="cmd" value="_xclick">



                <input type="hidden" name="rm" value="2">



                <input type="hidden" name="bn" value="<?php echo $data['datos_empresa']["data"]['nombre'];?>">



                <input type="hidden" name="business" value="<?php echo $data['datos_empresa']["data"]['paypal_email'];?>">



                <input type="hidden" name="item_name" value="Factura">



                <input type="hidden" name="item_number" value="<?php echo $data['data']['numero'];?>">



                <input type="hidden" name="amount" value="<?php echo $data['data']['monto']?>">



                <input type="hidden" name="custom" value="<?php echo $data['user_id'];?>">



                <input type="hidden" name="currency_code" value="<?php echo $data['datos_empresa']["data"]['moneda']?>">



                <input type="hidden" name="image_url" value="<?php echo base_url('uploads/'.$data['datos_empresa']["data"]['logotipo']);?>">



                <input type="hidden" name="return" value="<?php echo site_url('paypal/success');?>">

                <input type="hidden" name="notify_url" value="<?php echo site_url('paypal/notify');?>">



                <input type="hidden" name="cancel_return" value="<?php echo site_url('paypal/cancel');?>">

                <input type="hidden" name="no_note" value="0"> 

        </form> 

    </body>

</html>

   