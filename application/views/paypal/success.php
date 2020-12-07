<div class="wrapper fixed">
    <div class="body">
        <div class="content">
                        <div class="page-header">
                        <div class="icon">
                            <span class="ico-cube"></span>
                        </div>
                        <h1><?php echo $this->config->item('site_title');?> <small>BIENVENIDO AL SITIO</small></h1>
                    </div>

<div class="block title">
    <div class="head">
        <h2>Nuevo usuario</h2>                                          
    </div>
</div>
<div class="row-fluid">
    <div class="block">
            <div class="head blue">
                <div class="icon"><i class="ico-layout-9"></i></div>
                <h2><?php echo custom_lang('paypal_success_message', "Gracias por su pago");?></h2>
            </div>
            <div class="data-fluid">
                <dl class="dl-horizontal">
                    <dt>Numero de factura</dt>
                    <dd><?php echo $data['item_number'];?></dd>
                    <dt>Email</dt>
                    <dd><?php echo $data['payer_email'];?></dd>
                    <dd>Nombre</dd>
                    <dt><?php echo $data['first_name'];?></dt>
                    <dd>Estado del pago</dd>
                    <dt><?php echo $data['payment_status'];?></dt>
                </dl>
            </div>
    </div>
</div> </div>
    </div>    
</div>