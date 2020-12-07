<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
<script src="<?= base_url('public/fancybox/jquery.fancybox.js')?>"> </script>

<!-- Auto complete -->
<link rel="stylesheet" type="text/css" href="<?= base_url('public/easy-autocomplete/easy-autocomplete.min.css');?>">
<script type="text/javascript" src="<?= base_url('public/easy-autocomplete/jquery.easy-autocomplete.min.js'); ?>"></script>

<style>
/*General settings*/
.money-value,.forma-pago{transition-duration: 0.8s;}
.m-0{margin:0;}
.mb-0{margin-bottom:0px;}
.mt-0{margin-top:0px;}
.mb-5{margin-bottom:5px;}
.mr-5{margin-bottom:5px;}
.mb-10{margin-bottom:10px;}
.mt-2{margin-top:10px;}
.p-0{padding:0px;}
.pr-5{padding-right:5px;}
.d-block{display:block !important;}
.d-none{display:none !important;}
.d-flex{display:flex;}
.items-center{justify-content:center;align-items:center;}
.border-gray{border:solid 1px lightgray; }
.aquamarine{color: #2bbdb9!important;}
.vcenter {display: inline-block;vertical-align: middle;float: none;}

.swal2-container{z-index:2020;}

/* The Modal (background) */
.modal-generic {visibility: hidden;  position: fixed;;z-index: 1;top: 0;right:0;width: 100%;height: 100vh;overflow: auto;background-color: rgb(0,0,0);background-color: rgba(0,0,0,0.4);z-index: 1010;}
/* Modal Content */
.modal-content-generic {right:-50%;background-color: #eef1f5;/* margin: auto; */padding: 0px 0px 20px 0px;border: 1px solid #888;height: 100vh;float: right;position: relative;}

/* The Close Button */
/*.modal-content-generic .close-generic {display:none;color: #aaaaaa;font-size: 28px;font-weight: bold;position: absolute;right: 10px;top: 6px;}*/
.close-generic {position: absolute;width: 50px;height: 50px; top:10px; border: solid 1px #fff;display: flex;vertical-align: middle;align-items: center;justify-content: center;left: -8%;background-color: #fff;font-size: 40px;border-radius: 3px;text-align: center;border-radius: 10px;font-family: -webkit-body;}
.close-generic:hover,
.close-generic:focus {color: #000;text-decoration: none;cursor: pointer;}
.close-generic i{font-size:30px;}
/*End modal content*/

.title-generic { background-color: #505050; color: #fff; box-sizing:border-box;border-bottom: 1px solid #e5e5e5;    padding: 11px;margin-bottom: 10px;}
.title-generic h4{margin:0px;}



/*Modal Domicile*/
.modal-generic-center {visibility:hidden;position: fixed;z-index: 1001;padding-top: 100px; left: 0;top: 0;width: 100%; height: 100%;overflow: auto;background-color: rgb(0,0,0); background-color: rgba(0,0,0,0.4); }

/* Modal Content */
.modal-content-center {background-color: #fefefe;padding: 0px;border: 1px solid #888;}

/* The Close Button */
.close-modal-center {color: #fff;float: right;font-size: 28px;font-weight: bold;position: absolute;right: 5px;top: 9px;}
.close-modal-center:hover,
.close-modal-center:focus {color: #aeaeae;text-decoration: none;cursor: pointer;}
.title-domicile{background-color: #505050;color: #fff;text-align: center;padding: 10px;box-sizing: border-box;border-bottom: 1px solid #e5e5e5;margin-bottom: 10px;}
.title-domicile h4{margin:0px;}


.slide-domicile .item-domicile{display: flex;  align-items: center; box-sizing: border-box; max-height: 250px;  margin-right: 5px;}
.slide-domicile .item-domicile .content-item-domicile{border-radius: 7px 7px;  border: solid 1px lightgray;width:80%; margin:0 auto;margin-bottom: 6px; height:90px; display:flex;}
.slide-domicile .item-domicile img{width: 100%;  cursor:pointer;}
.slide-domicile .slick-prev{padding-top:35%;display: block;position: absolute;left: -2px;top: 32px !important;cursor: pointer;}
.slide-domicile .slick-next{padding-top:35%;display: block;position: absolute;right: 0px;top: 32px !important;cursor: pointer;}
.content-buttons-domicile{display: flex;justify-content: center;}

.edit-client-domicile button{margin-bottom: 5px;margin-right: 0px;background-color:#5cb85c;}
.edit-client-domicile button:hover{background-color:#5cb85c !important; opacity:0.9;}

#add-client{cursor:pointer;}

.control-edit-client{min-width: 39px;padding: 6px 12px; cursor:pointer;font-size: 14px;font-weight: 400;line-height: 1;color: #555;text-align: center;    width: 1%;white-space: nowrap;vertical-align: middle;}
.control-edit-client .fa-user-edit{color: #5cb85c;top: 2px;left: -2px;}
#search-clients{width:100%;}
.content-easy-client {width:100%;}
.content-easy-client .easy-autocomplete{width:100% !important;}

.input-total-payment{padding:10px; box-sizing:border-box; margin-bottom: 10px; background-color:#fff; color:#000;}
.input-total-payment #valor_recibido{    height: 64px;font-size: 49px !important;color: #000 !important;border-radius: 6px;text-align: right;}
.input-total-payment label{font-size:17px;}
.content-total-payment{border:solid 1px lightgray; border-radius:4px; height:100%; min-height:275px; padding-top:10px; box-sizing:border-box; background-color:#fff;}
.content-total-payment h4{color:#2bbdb9!important; text-align:center;}
.content-total-payment  .content-item-payment{font-size:14px;}
.return-changue{position:absolute;  bottom: 10px;right: 12px; width:80%; margin-left:10%;font-size: 14px;}
.return-changue .item-change{font-size:14px;}
.return-changue .label-change{color: #a94442;}
.content-calculator{border:solid 1px lightgray;padding:1rem; background:#fff; padding-bottom:0px; box-sizing:border-box; border-radius: 4px;}
.calculator .num{width: 4rem;height: 4rem;font-size:17px; border: solid 1px lightgray;border-radius: 50%;margin: 0 auto;display: flex;vertical-align: middle;justify-content: center;align-items: center;margin-bottom:1rem; cursor:pointer;}

.content-actions, .actions{display:flex; justify-content:center;}
.content-actions, .actions button:hover{}
.btn-generic{margin-right: 1rem; padding: 15px 60px;margin-top:10px; font-weight: bold;border: none;border: solid 1px;border-radius: 4px;}
.btn-cancel-generic{color: #fff;background-color: #aeaeae;border-color: #aeaeae;}
.btn-print{color: #fff;background-color: #5cb85c;border-color: #5cb85c;}
.btn-print-disabled{color: #fff;background-color: #5ca745;border-color: #aeaeae; cursor: no-drop !important;}
.btn-print-success{color: #fff;background-color: #5cb85c;border-color: #5cb85c;}
.btn-generic:hover{opacity:0.8;}

.content-slide-formas-pago{background-color:#fff;}
.content-options-payment{background-color:#fff; padding:5px; box-sizing:border-box; display:none;}
.slick-formas-pago {margin-top:0px;}
.slick-formas-pago  h4{ border: solid 1px #5cb85c; font-size: 13px; cursor:pointer; padding: 10px;box-sizing: border-box;border-radius: 6px;margin-right: 10px;}

.delete-payment{color:#c22439;cursor:pointer;}
.remove_icon{color: #c22439;cursor: pointer;margin-right: 5px;font-size: 11px;font-weight: bold;cursor: pointer;}

.money-value{ border: solid 1px lightgray;background:#fff; padding: 10px;box-sizing: border-box;margin-bottom: 1.1rem; border-radius:5px; cursor:pointer;}
.money-value:hover{background-color: #5cb85c; color:#fff; border-color:#5cb85c;}    

.forma-pago:hover{color: #fff;background-color: #5cb85c;border-color: #5cb85c;}
.backspace{font-size: 20px;}
.content-automatic-print{color:#333;}
.content-automatic-print .content-checkbox{align-items:center; justify-content: flex-end;}
.content-automatic-print .content-checkbox .switch{width: 28px;height: 5px;}
.content-automatic-print .content-checkbox .slider:before{height: 14px;width: 14px;left: -14px;}

/*SWITCH CHECKBOX*/
.switch {position: relative;display: inline-block;width: 60px;height: 34px;}

.switch input { opacity: 0;width: 0;height: 0;}
.slider {position: absolute;cursor: pointer;top: 0;left: 0;right: 0;bottom: 0;background-color: #ccc;-webkit-transition: .4s;transition: .4s;}
.slider:before {position: absolute;content: "";height: 26px;width: 26px;left: 4px;bottom: 4px;background-color: white;-webkit-transition: .4s;transition: .4s;}
input:checked + .slider {background-color: #2196F3;}
input:focus + .slider {box-shadow: 0 0 1px #2196F3;}
input:checked + .slider:before {-webkit-transform: translateX(26px);-ms-transform: translateX(26px);transform: translateX(26px);}

/* Rounded sliders */
.slider.round {border-radius: 34px;}
.slider.round:before {border-radius: 50%;}


/*General buttons*/
.btn-generic:hover {opacity: 0.8;}
.btn-cancel-generic {color: #fff;background-color: #aeaeae;border-color: #aeaeae; }
.btn-success-generic{color: #fff;background-color: #5cb85c;border-color: #5cb85c; margin-bottom:10px;}
.btn-generic-small {margin-right: 1rem;padding: 8px;margin-top: 10px;border: none;border-radius: 4px;margin-bottom:10px;}
.swal2-icon.swal2-info{color:rgb(92, 184, 92) !important; border-color:rgb(92, 184, 92) !important;}


/*Discount*/
.content-discount .popover-title{text-align:center;}
.content-discount input[type="number"] {height:31px; }
.icon-discount{font-size: 30px;color: #5cb85c;cursor: pointer;opacity:0.9;}
.icon-discount:hover{opacity:1;}
#discount{text-decoration:underline;cursor:pointer;}
#popover_discount{margin-top:40px;cursor:pointer;}
/*End discount*/

/* propine */
.content-modal-propine{margin-top:10rem;}
.input-propine{height:31px !important; margin-top:10px;}
.propine_value{margin-right:5px;}
/* end propine */
</style>


<!-- The Modal -->
<div id="modal-payment" class="modal-generic">
  <!-- Modal content -->
  <div class="modal-content-generic col-sm-10 col-md-7">
    <div class="close-generic"><i class="fas fa-angle-double-right"></i></div>
    <div class="title-generic text-center">
        <h4>PAGAR FACTURA</h4>
    </div>
    <div class="">
        <div class="col-sm-12">
            <div class="d-flex items-center">
                
                <div class="col-sm-11 input-total-payment d-flex items-center border-gray">
                    <div class="col-sm-3 label-received">
                        <label for="valor_recibido">Total recibido</label>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group mb-0 text-center">
                            
                            <input type="text" id="valor_recibido" class="form-control" value="0" data-total='0'>
                        </div>
                    </div>
                    <div class="col-sm-3">
                    
                        
                        <div class="col-sm-6  d-flex content-discount p-0">
                            <a id="popover_discount">Descuento</a>
                            <form id="form-discount" class="hide">
                                <div class="col-sm-12">
                                    Valor:  <input type="radio"  name="type_discount" id="discount_value"  value="value">
                                    porcentaje:  <input type="radio"  name="type_discount" id="discount_percent" value="percent" checked>
                                </div>
                               
                                <div class="form-group col-sm-10">
                                    <input type="number" class="form-control" id="discount" placeholder="Descuento" value="0">
                                </div>
                                <div class="col-sm-2 p-0">
                                    <i class="fas fa-check-circle icon-discount" id="save_discount"></i>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slide formas-pago -->
            <div class="d-flex items-center">
                <div class="col-sm-11 content-slide-formas-pago mb-10 border-gray">
                    <div class="slick-formas-pago" id="formas-pago">
                        <?php if(count($data["payment-methods"]->data) > 0):
                            foreach($data["payment-methods"]->data as $payment_method): ?>
                        
                            <div> <h4 class="forma-pago" data-id="<?= $payment_method->id; ?>" data-type="<?= $payment_method->code; ?>"> <?= $payment_method->name; ?> </h4></div>
                        <?php endforeach; endif;?>
                    </div>
                </div>
            </div>

            <div class="d-flex items-center">
                <div class="col-sm-11 p-0">
                    <div class="col-sm-5 total content-total-payment border-gray">
                        <h4>RESUMEN DE PAGO</h4>
                        <hr>
                        <div class="col-md-12 items-payment">
                        </div>

                        <div class="col-md-12"><hr></div>
                        
                        <div class="row text-center return-changue">
                            <div class="col-sm-12 text-right"> 
                                <span class="item-change">Total + propina:</span> 
                                <span class="total-payment">$0</span>
                            </div>
                            <div class="col-sm-12 text-right"> 
                                <span class="item-change">Restante a pagar:</span> 
                                <span class="total-to-payment">$0</span>
                            </div>
                            <div class="col-sm-12 text-right"> 
                                <span class="item-change">Total recibido:</span> 
                                <span class="total-received">$0</span>
                            </div>
                            <div class="col-sm-12 text-right"> 
                                <span class="item-change"><span class="remove_icon remove-discount" onclick="remove_discount()">X</span>Descuento:</span> 
                                <span class="total-discount">$0</span>
                            </div>
                            <div class="col-sm-12 text-right"> 
                                <span class="item-change"><!--<span class="remove_icon remove-propine" onclick="remove_propine()">X</span>-->Propina:</span> 
                                <span class="total-propine">$0</span>
                            </div>
                            <div class="col-sm-12 text-right label-change"> 
                                <span class="item-change">Cambio:</span> 
                                <span class="total-change">$0</span>
                            </div>

                            <div class="col-sm-12"><hr class="aquamarine"></div>
                        </div>

                    </div>
                    <!-- end resumen de pago --->

                    <!-- calculator -->
                    <div class="col-sm-5 calculator ">
                        <div class="col-sm-12 content-calculator border-gray mt-0">
                                <div class="col-sm-4">
                                    <div class="num" data-value="7">7</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="num" data-value="8">8</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="num" data-value="9">9</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="num" data-value="4">4</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="num" data-value="5">5</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="num" data-value="6">6</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="num" data-value="1">1</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="num" data-value="2">2</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="num" data-value="3">3</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="num" data-value="00">00</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="num" data-value="0">0</div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="num" data-value="del"><i class="fas fa-backspace backspace"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-2 p-0 help-money-value"></div>
                </div>
            </div>
            <div class="d-flex items-center">
                <div class="col-sm-11 mt-2 p-0 content-automatic-print">
                    <div class="col-sm-6 pull-right checkbox p-0 pr-5 d-flex content-checkbox">
                        
                        <label class="mr-5">
                            <a id="pop_automatic_print" href="#" data-container="body" data-content="La factura se genera y se imprime automaticamente si el total recibido es mayor o igual al total de la venta" rel="popover" data-placement="left" data-original- data-trigger="hover">
                                <span class="glyphicon glyphicon-question-sign icon-help-config" aria-hidden="true"></span>
                            </a>
                            Imprimir factura automáticamente 
                         </label>
                        <label class="switch">
                            <input type="checkbox" <?= (get_option('automatic_print'))? 'checked' : ''; ?> class="check-automatic-print">
                            <span class="slider round"></span>
                        </label>
                        
                    </div>
                </div>
            </div>
            
            <div class="d-flex items-center">
                <div class="col-sm-11 content-actions mt-2 ">
                    <div class="col-sm-8 actions">
                        <button class="btn-generic btn-cancel-generic" id="close-payment">CANCELAR</button>
                        <button class="btn-generic btn-print btn-print-disabled">IMPRIMIR</button>
                    </div>
                </div>
            </div>

        </div>
       
    </div>
  </div>
</div>


<!-- PAYMENT -->
<div class="modal fade" id=" tabindex="-1" role="dialog" aria-labelledby="paymentLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="paymentLabel">Pagar</h4>
      </div>
      <div class="modal-body">
        <div class="row">
            <div class="col-sm-10">
            
                <div class="row">
                    <div class="col-sm-4 col-sm-offset-4">
                        <input type="text" value="15000" readonly>
                    </div>
                </div>
            </div>

            <div class="col-sm-2"></div>
        </div>
        <!--<img src="<?= base_url('uploads/modelo.jpg'); ?>" alt=""> -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary">Guardar</button>
      </div>
    </div>
  </div>
</div>


<!-- DOMICILE -->
<div id="modal-domicile" class="modal-generic-center">
    <!-- Modal content -->
    <div class="modal-content-center col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <span class="close-modal-center" id="close-domicile">&times;</span>

        <div class="title-domicile">
            <h4 class="text-center"> DOMICILIOS </h4>
        </div>
        <div class="row m-0">
            <div class="col-md-5">
                <div class="content-client">
                        <div class="form-group">
                            <label for="input-clients">Clientes</label>
                            <!--<input type="text" class="form-control" id="input-clients" placeholder="Clientes">
                            <span class="input-group-addon" id="add-new-client" data-id="0">
                                <div><span id="icocambiar" class="icon ico-plus vender"></span></div>
                            </span>-->

                            <div class="input-group">
                                <input type="hidden" id="id-client-edit-domicile">
                                <input type="text" style="border-radius: 0px 0px !important;border-right: solid 1px lightgray !important;" class="form-control" id="input-clients" placeholder="Clientes">
                                <div class="input-group-addon" id="add-client"><span id="icocambiar" class="icon ico-plus vender" style="color:#5cb85c;top: 2px;left: -2px;"></span></div>
                            </div>
                        </div>

                        <div class="inputs-client-hidden">
                            <div class="form-group">
                                <label for="input-telephone-client">Teléfono</label>
                                <input type="text" class="form-control" id="input-telephone-client" placeholder="Teléfono">
                            </div>

                            <div class="form-group">
                                <label for="input-address-client">Dirección</label>
                                <input type="text" class="form-control" id="input-address-client" placeholder="Dirección">
                            </div>

                            <div class="form-group edit-client-domicile hidden">
                                <button class="pull-right btn btn-success" id="edit-client-domicile">Modificar cliente</button>
                            </div>
                        </div>
                </div>
            </div>

            <div class="col-md-7">
                <div class="slide-domicile">
                    <?php  for($i=0;$i<count($data["domiciliaries"]->data);$i=$i+4){   
                            //$image_domicile = ($data["domiciliaries"]->data[$i]->logo == '') ? base_url().'uploads/default-domicile.png' : base_url().'uploads/'.$this->session->userdata('base_dato').'/domiciliarios/';   
                        ?>
                        <div class="item-domicile">
                            <div class="row">
                                <?php if(isset($data["domiciliaries"]->data[$i]) && $data["domiciliaries"]->data[$i]->active == '1'): ?>
                                    <div class="col-xs-6">
                                        <div class="content-item-domicile" data-id="<?= $data["domiciliaries"]->data[$i]->id; ?>">
                                            <img src="<?= ($data["domiciliaries"]->data[$i]->logo == '') ? base_url().'uploads/default-domicile.png' : base_url().'uploads/'.$this->session->userdata('base_dato').'/domiciliarios/'.$data["domiciliaries"]->data[$i]->logo; ; ?>">
                                        </div>
                                        <span><?php echo $data["domiciliaries"]->data[$i]->description; ?></span>
                                    </div>
                                <?php endif; ?>

                                
                                 <?php if(isset($data["domiciliaries"]->data[$i+1]) && $data["domiciliaries"]->data[$i+1]->active == '1'): ?>
                                    <div class="col-xs-6">
                                        <div class="content-item-domicile" data-id="<?= $data["domiciliaries"]->data[$i+1]->id; ?>">
                                            <img src="<?= ($data["domiciliaries"]->data[$i+1]->logo == '') ? base_url().'uploads/default-domicile.png' : base_url().'uploads/'.$this->session->userdata('base_dato').'/domiciliarios/'.$data["domiciliaries"]->data[$i+1]->logo; ; ?>">
                                        </div>
                                        <span><?php echo $data["domiciliaries"]->data[$i+1]->description; ?></span>
                                    </div>
                                <?php endif; ?>

                                
                                 <?php if(isset($data["domiciliaries"]->data[$i+2]) && $data["domiciliaries"]->data[$i+2]->active == '1'): ?>
                                    <div class="col-xs-6">
                                        <div class="content-item-domicile" data-id="<?= $data["domiciliaries"]->data[$i+2]->id; ?>">
                                            <img src="<?=  ($data["domiciliaries"]->data[$i+2]->logo == '') ? base_url().'uploads/default-domicile.png' : base_url().'uploads/'.$this->session->userdata('base_dato').'/domiciliarios/'.$data["domiciliaries"]->data[$i+2]->logo; ; ?>">
                                     
                                        </div>
                                        <span><?php echo $data["domiciliaries"]->data[$i+2]->description; ?></span>

                                    </div>
                                <?php endif; ?>

                                 <?php if(isset($data["domiciliaries"]->data[$i+3]) && $data["domiciliaries"]->data[$i+3]->active == '1'): ?>
                                    <div class="col-xs-6">
                                        <div class="content-item-domicile" data-id="<?= $data["domiciliaries"]->data[$i+3]->id; ?>">
                                         <img src="<?=  ($data["domiciliaries"]->data[$i+3]->logo == '') ? base_url().'uploads/default-domicile.png' : base_url().'uploads/'.$this->session->userdata('base_dato').'/domiciliarios/'.$data["domiciliaries"]->data[$i+3]->logo; ; ?>">
                                        </div>
                                        <span><?php echo $data["domiciliaries"]->data[$i+3]->description; ?></span>

                                    </div>
                                <?php endif; ?>
                                
                            </div>
                        </div>   
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="col-md-12 p-0"><hr></div>
        <div class="col-md-12 content-buttons-domicile">
                <button class="btn-generic-small btn-success-generic" id="send-domicile">ENVIAR</button>
                <button class="btn-generic-small btn-cancel-generic" id="cancel-domicile">CANCELAR</button>
                <button class="btn-generic-small btn-success-generic" id="method-payment-domicile">FORMA DE PAGO</button>
        </div>
    </div>
</div>


<!-- MODAL ADD CLIENT -->
<div id="modal-add-client" class="modal-generic-center">
    <!-- Modal content -->
    <div class="modal-content-center col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <span class="close-modal-center" id="close-add-client">&times;</span>

        <div class="title-domicile">
            <h4 class="text-center"> NUEVO CLIENTE </h4>
        </div>
        <input type="hidden" class="form-control" id="client-domicilio" value="0">
        <div class="col-md-12">
            <div class="col-sm-8">
                <div class="form-group">
                    <label for="name-client">Nombre completo</label>
                    <input type="text" class="form-control" id="name-client" placeholder="Nombre completo">
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="type-identification-client">Tipo identificación</label>
                    <select class="form-control" id="type-identification-client">
                        <option value="CC">CC</option>
                        <option value="NIT">NIT</option>
                        <option value="RUT">RUT</option>
                        <option value="CE">CE</option>
                        <option value="RUC">RUC</option>
                        <option value="PPN">PP</option>
                        <option value="NIF">NIF</option>
                        <option value="CIF">CIF</option>
                        <option value="RIF">RIF</option>
                    </select>
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="identification-client">Número identificación</label>
                    <input type="text" class="form-control" id="identification-client" placeholder="Número identificación">
                </div>
            </div>

            <div class="col-sm-8">
                <div class="form-group">
                    <label for="email-client">Correo electrónico</label>
                    <input type="text" class="form-control" id="email-client" placeholder="Example@hotmail.com">
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="telephone-client">Teléfono</label>
                    <input type="text" class="form-control" id="telephone-client" placeholder="Teléfono">
                </div>
            </div>

             <div class="col-sm-4">
                <div class="form-group">
                    <label for="cellphone-client">Celular</label>
                    <input type="text" class="form-control" id="cellphone-client" placeholder="Celular">
                </div>
            </div>

            <div class="col-sm-4">
                <div class="form-group">
                    <label for="address-client">Dirección</label>
                    <input type="text" class="form-control" id="address-client" placeholder="Dirección">
                </div>
            </div>

            
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="country-client">País</label>
                    <select class="form-control" id="country-client">
                       <?php foreach($data["countries"] as $countrie): ?>
                            <option value="<?= $countrie; ?>"><?= $countrie; ?></option>
                       <?php endforeach; ?>
                    </select>
                </div>
            </div>

            
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="city-clienty">Ciudad</label>
                    <select class="form-control" id="city-client">
                        <option value="">Ciudad</option>
                    </select>
                </div>
            </div>

            
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="group-client">Grupo</label>
                    <select class="form-control" id="group-client">
                       <?php foreach($data["groups"]->data as $group): ?>
                            <option value="<?= $group->id; ?>"><?= $group->name; ?></option>
                       <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-sm-12"> <hr> </div>
        <div class="col-md-12 content-buttons-domicile">
            <button class="btn-generic-small btn-cancel-generic" id="cancel-add-client">CANCELAR</button>
            <button class="btn-generic-small btn-success-generic" id="submit-add-client">CREAR CLIENTE</button>
        </div>
    </div>
</div>


<div id="modal-edit-client" class="modal-generic-center">
    <!-- Modal content -->
    <div class="modal-content-center col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3">
        <span class="close-modal-center" id="close-edit-client">&times;</span>

        <div class="title-domicile">
            <h4 class="text-center"> EDITAR CLIENTE </h4>
        </div>

        <div class="col-md-12">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="name-client-edit">Nombre completo</label>
                    <input type="hidden" id="id-client-edit">
                    <input type="text" class="form-control" id="name-client-edit" placeholder="Nombre completo">
                </div>
            </div>

             <div class="col-sm-3">
                <div class="form-group">
                    <label for="cellphone-client-edit">Celular</label>
                    <input type="text" class="form-control" id="cellphone-client-edit">
                </div>
            </div>

            <div class="col-sm-3">
                <div class="form-group">
                    <label for="address-client-edit">Dirección</label>
                    <input type="text" class="form-control" id="address-client-edit">
                </div>
            </div>
        </div>
        <div class="col-sm-12"> <hr> </div>
        <div class="col-md-12 content-buttons-domicile">
            <button class="btn-generic-small btn-cancel-generic" id="cancel-edit-client">CANCELAR</button>
            <button class="btn-generic-small btn-success-generic" id="submit-edit-client">EDITAR CLIENTE</button>
        </div>
    </div>
</div>


<!-- MODAL PROPINA -->
<div id="modal-propine" class="modal-generic-center">
    <!-- Modal content -->
    <div class="content-modal-propine modal-content-center col-sm-2 col-sm-offset-5 col-md-2 col-md-offset-5">
        <span class="close-modal-center" id="close-propine">&times;</span>

        <div class="title-domicile">
            <h4 class="text-center"> PROPINA </h4>
        </div>

        <div class="col-md-12">
            <form id="form-propine text-center">
                <div class="col-sm-12 text-center">
                    Valor:  <input type="radio"  name="type_propine" id="propine_value"  value="value">
                    percentaje:  <input type="radio"  name="type_propine" id="propine_percent" value="percent" checked>
                </div>
                
                <div class="form-group col-sm-12">
                    <input type="number" class="form-control input-propine" id="propine" placeholder="Propina" value="<?php echo (getGeneralOptions("sobrecosto")->valor_opcion == "si")? 10 : 0; ?>">
                    <label><input style="display: -webkit-inline-box !important;margin-top: auto;" checked type="checkbox" id="value_default"> &nbsp;Valor por defecto</label>

                </div>
            </form>
        </div>
        <div class="col-sm-12"> <hr> </div>
        <div class="col-md-12 content-buttons-domicile">
            <button class="btn-generic-small btn-cancel-generic" id="cancel-propine">CANCELAR</button>
            <button class="btn-generic-small btn-success-generic" id="submit-propine">GUARDAR</button>
        </div>
    </div>
</div>


<!-- MODAL NOTE INVOICE -->
<div id="modal-note-invoice" class="modal-generic-center">
    <!-- Modal content -->
    <div class="content-modal-note-invoice modal-content-center col-sm-4 col-sm-offset-4 col-md-4 col-md-offset-4">
        <span class="close-modal-center" id="close-modal-note-invoice">&times;</span>

        <div class="title-domicile">
            <h4 class="text-center"> NOTA DE LA FACTURA </h4>
        </div>

        <div class="col-md-12">
            <form id="form-note-invoice text-center">
                <textarea id="note-invoice" rows="4" placeholder="Ingrese aquí la nota para la factura"></textarea>
            </form>
        </div>
        <div class="col-sm-12"> <hr> </div>
        <div class="col-md-12 content-buttons-domicile">
            <button class="btn-generic-small btn-cancel-generic" id="cancel-note-invoice">CANCELAR</button>
            <button class="btn-generic-small btn-success-generic" id="submit-note-invoice">GUARDAR</button>
        </div>
    </div>
</div>



<?php //print_r($data); ?>

<script>
        var payments = new Array();
        var consecutive = 0;
        var store_id = "<?= $data['store-id']; ?>";  
        var symbol = "<?= $data['data-currency']->symbol; ?>";
        var thousands_sep =  "<?= $data['data-currency']->thousands_sep; ?>";
        var decimals_sep = "<?= $data['data-currency']->decimals_sep; ?>";
        var decimals = "<?= $data['data-currency']->decimals; ?>";
        var remaining = 0;
        var url_print = "<?= site_url('ventas/imprimir/');?>";
        var valor_pagar = '';
        var check_automatic_print = <?= (get_option('automatic_print'))? 'true' : 'false';  ?>; 
        var new_fast_print = <?= (get_option('nueva_impresion_rapida') == "si")? 'true' : 'false';  ?>; 
        var zona = '';
        var mesa = '';
        var modal = $('.modal-generic');
        var modal_content = $(".modal-content-generic");
        var checkout_enabled = '<?= $data['checkout_enabled']; ?>';
        var customers = <?= json_encode($data["customers"]);?>;
        var customer = 0;
        var quick_service = "<?= $data['quick-service']; ?>";
        var quick_service_command = "<?= $data['quick-service-command']; ?>";
        var position_value = 0;
        var intents = 0;
        var selected_domicile = 0;
        var discount = 0;
        var total_discount = 0;
        var type_discount = 'value';
        var order_consecutive = "<?= $data['order_consecutive']; ?>";
        var intents_checkout = 0;

        <?php $active_propine = getGeneralOptions("sobrecosto"); 
        if($active_propine->valor_opcion == "si"){ ?>
            var propine = 10;
        <?php }else{ ?>
            var propine = 0;
        <?php } ?>

        
        var total_propine = 0;
        var type_propine = 'percent';
        var note_invoice = '';

           /**
         * web sockets - fast print
         */
        function openConnection() {
            
            // uses global 'conn' object
            if (conn.readyState === undefined || conn.readyState > 1) {
                conn = new WebSocket('ws://127.0.1.1:12500');
                
                conn.onopen = function () {
                    conn.send("Connection Established Confirmation");
                };
                conn.onmessage = function (event) {
                    //document.getElementById("content").innerHTML = event.data;
                };
                conn.onerror = function (event) {
                    console.log("Web Socket Error");
                };
        
                conn.onclose = function (event) {
                    console.log("Web Socket Closed");
                };
            }
        }

        $(document).ready(function(){

            if(localStorage.porcentage_default){
                $('#propine').val(Number(localStorage.porcentage_default));
            }
            conn = {}, window.WebSocket = window.WebSocket || window.MozWebSocket;

            openConnection();
            
            var close_modal = $(".close-generic");
            //$(".btn-print").prop('disabled','true');
            $("#pop_automatic_print").popover();

            if(zona == '' && mesa == ''){
                zona = $('#txt_seccion').val();
                mesa = $('#txt_mesa').val();
                get_order_product_restaurant(zona,mesa);
            }
            
            if(checkout_enabled == 'si'){
                //verify_state_box();
                open_modal_payment();
            }

            $('.slick-formas-pago').slick({
                dots: false,
                infinite: false,
                speed: 300,
                prevArrow: '<div class="slick-prev"><img style="width: 50px; height: 50px;" src="<?php echo base_url();?>uploads/mesas/flechaizquierdaverdegruesa.png" alt="prev"></div>',
                nextArrow: '<div class="slick-next"><img style="width: 50px; height: 50px;" src="<?php echo base_url();?>uploads/mesas/flechaderechaverdegruesa.png" alt="ext"></div>',
                slidesToShow: 4,
                slidesToScroll: 3,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            infinite: true,
                            dots: false
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ] 
            });
            
            /* DOMICILE */
            $("#close-domicile,#cancel-domicile").click(function(){
                $("#modal-domicile").css('visibility','hidden');
            })

            $('.slide-domicile').slick({
	            dots: false,
	            infinite: false,
	            speed: 300,
	            prevArrow: '<div class="slick-prev"><img style="width: 20px; height: 20px;" src="http://pos.vendty.com/uploads/mesas/flechaizquierdaverdegruesa.png" alt="prev"></div>',
                nextArrow: '<div class="slick-next"><img style="width: 20px; height: 20px;" src="http://pos.vendty.com/uploads/mesas/flechaderechaverdegruesa.png" alt="ext"></div>',
	            slidesToShow: 1,
	            slidesToScroll: 1,
	            responsive: [
	                {
	                    breakpoint: 1024,
	                    settings: {
	                        slidesToShow: 1,
	                        slidesToScroll: 3,
	                        infinite: true,
	                        dots: false
	                    }
	                },
	                {
	                    breakpoint: 600,
	                    settings: {
	                        slidesToShow: 1,
	                        slidesToScroll: 2
	                    }
	                },
	                {
	                    breakpoint: 480,
	                    settings: {
	                        slidesToShow: 1,
	                        slidesToScroll: 1
	                    }
	                }
	            ] 
	        });
            
            $(".slide-domicile .content-item-domicile").each(function(){
                $(this).click(function(){
                    selected_domicile = $(this).data('id');
                    console.log(selected_domicile);
                    clean_items_domicile();
                    $(this).css('border','solid 1px green');
                })                
            })

            $("#method-payment-domicile").click(function(){
                if(selected_domicile == 0){
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: 'Aun no ha seleccionado un domiciliario.'
                    })
                 }else if(customer == 0){
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: 'Aun no ha seleccionado un cliente.'
                    })
                 }else{
                    get_order_product_restaurant(zona,mesa);
                    open_modal_payment();
                 }
            })
            
            
            /* clients */ 
            autocomplete_clients();

            $("#submit-add-client").click(function(){
                save_client();
            })
            
            /* Edit client*/
            $("#edit-client").click(function(){
                $("#modal-edit-client").css('visibility','visible');
            });

            $("#submit-edit-client").click(function(){
                edit_client();
            })

            /* Add client domicile*/
            $("#add-client").click(function(){
                $("#client-domicilio").val(1);
                $("#modal-add-client").css('visibility','visible');
            })
            
            /* Edit client domicile */
            $("#edit-client-domicile").click(function(){
                edit_client_domicile();
            }) 

            $("#close-add-client,#cancel-add-client").click(function(){
                $("#modal-add-client").css('visibility','hidden');
            })

            $("#close-edit-client,#cancel-edit-client").click(function(){
                $("#modal-edit-client").css('visibility','hidden');
            })
            
            load_city_from_country('Colombia');
            $("#country-client").change(function () {
                load_city_from_country($(this).val());
            });

             /* Add client generic*/ 
             $("#add-generic-client").click(function(){
                $("#client-domicilio").val(0);
                $("#modal-add-client").css('visibility','visible');
            })
            /* end clients */ 

            close_modal.click(function(){
                modal_content.animate({ 'right' : '-50%' }, 300, function() { 
                    modal.css('visibility','hidden');
                });
            })
            
            $("#close-payment").click(function(){
                close_modal_payment();
            })

            /*
            $('#pagar_cuenta').click(function(){
                if(zona > 0){
                    location.href = "<?= site_url('ventas/nuevo/');?>/"+zona+"/"+mesa;
                }else{
                    open_modal_payment();
                }
                //verify_state_box();
            })
            */


            $(".calculator .content-calculator .num").each(function(element,index){
                let value = $(this).data('value');
                
               
               $(this).click(function(){
                console.log($("#valor_recibido"));
                    let num = 0;
                    if(position_value == 0){
                        valor_pagar = 0;
                        num = 0;
                    }else{
                        valor_pagar = number_format($("#valor_recibido").val()); 
                        num = valor_pagar.substring(0,position_value);
                    }

                    if(value == 'del'){
                        num = num.slice(0,-1);
                    }else{
                        num += ''+value;
                    }
                    valor_pagar = num;
                    $("#valor_recibido").val(number_format(valor_pagar));
                    update_cursor_position();
                })
            })

            $("#valor_recibido").keyup(function(e){
                update_cursor_position();
            })

            $("#valor_recibido").dblclick(function(e){
                $("#valor_recibido").selected();
            })


            $("#formas-pago .forma-pago").each(function(index,element){
                let id_type_payment = $(this).data('id');
                let type = $(this).data('type');
                $(this).click(function(){
                    let pago = $("#valor_recibido").val();
                    let payment = new Array();
                    let pago_aux = pago;
                    
                    if(pago.search(thousands_sep) != -1){
                        //pago_aux = pago.replace(thousands_sep,''); 
                        //pago_aux = pago_aux.replace(decimals_sep,'.'); 
                        pago_aux = pago.split(thousands_sep);
                        let stripped = pago_aux.join('');
                        pago_aux = stripped.replace(decimals_sep,'.'); 
                    }
                    remaining_to_pay();
                    if(pago_aux > 0){
                        
                        let tipo_html = '';
                        switch(type){
                            case 'efectivo':
                                tipo_html = "Efectivo";
                            break;

                            case 'tarjeta_credito':
                                if(remaining < 0){
                                    swal({
                                        type: 'error',
                                        title: 'Error',
                                        text: 'No es posible procesar el pago con un monto mayor al total para este medio de pago.'
                                    })
                                    return;
                                }else{
                                    tipo_html = "T. Crédito";
                                }
                                
                                
                            break;

                            case 'tarjeta_debito':
                                if(remaining < 0){
                                    swal({
                                            type: 'error',
                                            title: 'Error',
                                            text: 'No es posible procesar el pago con un monto mayor al total para este medio de pago.'
                                    })
                                    return;
                                }else{
                                    tipo_html = "T. Débito";
                                }
                            break;

                            default:  
                                // swal({
                                //     type: 'error',
                                //     title: 'Error',
                                //     text: 'Método de pago no soportado.'
                                // });
                                tipo_html = type;
                                //return;

                        }
                        
                        pago_aux = parseFloat(pago_aux);
                        payments.push({
                            id: id_type_payment,
                            consecutive:consecutive,
                            type_payment:type,
                            total_payment_method:pago_aux
                        });

                        updateTotal();

                        let html_pago = '';
                        html_pago += '<div class="row text-center mb-5 content-item-payment" data-id="'+consecutive+'">'+
                            '<div class="col-sm-2 text-right"> <span onclick="removePayment('+consecutive+')" class="delete-payment">x</span> </div>'+
                            '<div class="col-sm-5 text-left"> <span class="item-payment">'+tipo_html+'</span> </div>'+
                            '<div class="col-sm-5 text-rigth"> <span class="value-payment">'+symbol+' '+number_format(pago)+'</span> </div>'+
                        '</div>';
                        
                        $(".items-payment").append(html_pago);
                        consecutive++;
                        valor_pagar = '';
                    }else{
                        swal({
                            type: 'error',
                            title: 'Error',
                            text: 'El monto es invalido, verifique.'
                        })
                    }
                })
            })
            

             $(".check-automatic-print").change(function(){
                let url_check_automatic_print = "<?= site_url('orden_compra/save_automatic_print')?>";
                let automatic_print = 0;
                 check_automatic_print = $(this).prop('checked');
                
                 if(check_automatic_print){
                    automatic_print = 1;
                    //$(".btn-print").prop('disabled','true');
                    $(".btn-print").addClass('btn-print-disabled');
                 }


                  $.post(url_check_automatic_print,{
                    automatic_print : automatic_print
                 },function(data){})

                 updateTotal();
             })

             $(".btn-print").click(function(){
                if(check_automatic_print){
                    Swal(
                        'error!',
                        'Acción invalida, verifica que no este activa la opción: <strong>Imprimir factura automáticamente</strong>',
                        'error'
                    )
                }else{
                    let total_payments = 0;
                    $.each(payments,function(index,element){
                        total_payments += element.total_payment_method; 
                    });
                    total_payments.toFixed(decimals);
                    $(".total-received").html(symbol + ' ' +number_format(total_payments));
                    updateChange();

                    if((total_payments + total_discount).toFixed(decimals) >= formatNumber($(".total-payment").html())){
                        //checkout();
                        if(quick_service == 'si' && quick_service_command == 'si' && zona < 0){
                            generate_command();
                        }else{
                            checkout();
                        }
                    }else{
                        Swal(
                            'error!',
                            'El total recibido no es igual o mayor al total de la venta',
                            'error'
                        )
                    }
                }
             })
             
             /* domicile */
             $("#domicilios").click(function(){
                $("#modal-domicile").css('visibility','visible');
             })
             
             $("#send-domicile").click(function(){
                 if(selected_domicile == 0){
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: 'Aun no ha seleccionado un domiciliario.'
                    })
                 }else if(customer == 0){
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: 'Aun no ha seleccionado un cliente.'
                    })
                 }else{
                     load_payment_domicile();
                 }
             })
             /* end domicile*/ 


         });
        
         
        /* Discount */
        $(function(){
            $('#popover_discount').popover({
                placement: 'bottom',
                title: 'Descuento General',
                html:true,
                content:  $('#form-discount').html()
            }).on('click', function(){
                $("#discount").focus();
                
                $('#save_discount').click(function(){
                    load_discount();
                    
                })
            })
        })

        function load_discount(){
            if($("#discount_value")[0].checked){
                type_discount = $("#discount_value").val();
            }else if($("#discount_percent")[0].checked){
                type_discount = $("#discount_percent").val();
            }else{
                swal({
                    type: 'error',
                    title: 'Error',
                    text: 'Seleccione tipo de descuento'
                })
                return;
            }
      
            if(type_discount == 'percent'){
                discount = $("#discount").val();
                
                if(discount > 100){
                        swal({
                            type: 'error',
                            title: 'Error',
                            text: 'El percentaje de descuento no debe ser mayor a 100'
                        })
                        $("#discount").focus();
                }else{
                    total_discount = (formatNumber($(".total-payment").html()) * discount)/100;
                    //$(".total-payment").html(number_format(formatNumber($(".total-payment").html()) - total_discount));
                    $(".total-discount").html(symbol+number_format(total_discount));
                    $('#popover_discount').popover('hide');
                    let total_payment_html = $(".total-payment").html();
                    let total_received_html = $(".total-received").html();

                    let total_payment = formatNumber(total_payment_html);
                    let total_received = formatNumber(total_received_html);
                        
                    $("#valor_recibido").val(number_format(total_payment - total_received - total_discount));
                    if(total_propine > 0){
                        load_propine();
                    }
                    
                    updateTotal();
                }
                
            }else if(type_discount == 'value'){
                total_discount = formatNumber($("#discount").val());
                $(".total-discount").html(symbol+number_format(total_discount));
                $('#popover_discount').popover('hide');
                let total_payment_html = $(".total-payment").html();
                let total_received_html = $(".total-received").html();

                let total_payment = formatNumber(total_payment_html);
                let total_received = formatNumber(total_received_html);
                    
                $("#valor_recibido").val(number_format(total_payment - total_received - total_discount));
                if(total_propine > 0){
                        load_propine();
                    }
                updateTotal();
            }else{
                swal({
                    type: 'error',
                    title: 'Error',
                    text: 'Seleccione tipo de descuento'
                })
            }
        }
        /* End Discount */ 
        

        /* Propine */
        $(function(){
            $("#btn_propine").click(function(){
                $("#modal-propine").css('visibility','visible');
            })
            
            $("#close-propine").click(function(){
                $("#modal-propine").css('visibility','hidden');
            })

            $("#cancel-propine").click(function(){
                $("#modal-propine").css('visibility','hidden');
            })

            $("#submit-propine").click(function(){
                if ($('#value_default').is(":checked"))
                {
                    localStorage.porcentage_default = $('#propine').val()   ;
                }
                load_propine();
            })
       })

       function load_propine(){
        
            if($("#propine_value")[0].checked){
                type_propine = $("#propine_value").val();
            }else if($("#propine_percent")[0].checked){
                type_propine = $("#propine_percent").val();
            }else{
                swal({
                    type: 'error',
                    title: 'Error',
                    text: 'Seleccione tipo de propina'
                })
                return;
            }
            
            
            propine = $("#propine").val();
            if(type_propine == "percent"){
                $.ajax({
                    url : "<?= site_url('/orden_compra/get_propine'); ?>",
                    data : {
                        zone:zona,
                        table:mesa,
                        propine:propine,
                        type_discount:type_discount,
                        discount:discount
                    },
                    method : 'post', 
                    dataType : 'json',
                    success : function(response){
                        console.log(response);
                        total_propine = response.value;
                        $(".total-propine").html(number_format(total_propine));
                        $("#modal-propine").css('visibility','hidden');
                    },
                    error: function(error){
                        swal({
                            type: 'error',
                            title: 'Error inesperado',
                            text: 'No fue posible procsar la solicitud'
                        })
                    }
                }); 
            }else{
                total_propine = formatNumber(propine);
                $(".total-propine").html(number_format(total_propine));
                $("#modal-propine").css('visibility','hidden');
            }
            
       }
       /* End Propine */ 
       
       /** Note invoice */
       $(function(){
           $("#btn_note_invoice").click(function(){
               $("#modal-note-invoice").css('visibility','visible');
           })

            $("#close-modal-note-invoice").click(function(){
                $("#modal-note-invoice").css('visibility','hidden');
            })

            $("#cancel-note-invoice").click(function(){
                $("#modal-note-invoice").css('visibility','hidden');
            })

            $("#submit-note-invoice").click(function(){
                note_invoice = $("#note-invoice").val();
                $("#modal-note-invoice").css('visibility','hidden');
            })
       })
       /** End note invoice */
       

        function load_payment_domicile(){
            if ((zona != undefined) && (mesa != undefined)){
                $.post("<?= site_url('/orden_compra/get_order_products'); ?>",{
                    zona:zona,
                    mesa:mesa
                },function(data){   
                    let valor_total_comanda = data.total;
                    $("#valor_recibido").val(valor_total_comanda);
                    $("#valor_recibido").attr('data-total',valor_total_comanda);
                    $(".total-payment").html(symbol + valor_total_comanda);
                    help_pay_amounts();
                    $("#valor_recibido").select();
                    payments = [];
                    payments.push({
                                id: 0,
                                consecutive:consecutive,
                                type_payment:'domicilio',
                                total_payment_method: formatNumber($("#valor_recibido").val())
                            });
                    updateTotal();
                });
            }
        }

         /**
          * [ description]
          *
          * @return  [type]  [return description]
          */
         function clean_items_domicile(){
            $(".slide-domicile .content-item-domicile").each(function(){
                $(this).css('border','solid 1px lightgray');           
            })
         }
          

         function update_cursor_position(){
                if(thousands_sep == '.' && decimals_sep == ','){
                    valor_pagar = ($("#valor_recibido").val()); 
                }else{
                    valor_pagar = number_format($("#valor_recibido").val()); 
                }
                //valor_pagar = number_format($("#valor_recibido").val()); 
                console.log(valor_pagar);
                console.log(decimals);
                let length = parseInt(valor_pagar.length);
                if(parseInt(decimals) == 0){
                    position_value = length - parseInt(decimals);
                }else{
                    position_value = length - parseInt(decimals) - 1;
                }
                 
                console.log(position_value);
                $("#valor_recibido").val(valor_pagar);
                $("#valor_recibido").focus().setCursorPosition(position_value);
         }
         
        $.fn.setCursorPosition = function (pos) {
            this.each(function (index, elem) {
                if (elem.setSelectionRange) {
                    elem.setSelectionRange(pos, pos);
                } else if (elem.createTextRange) {
                    var range = elem.createTextRange();
                    range.collapse(true);
                    range.moveEnd('character', pos);
                    range.moveStart('character', pos);
                    range.select(); 
                }
            });
            return this;
        };

         /**
         * This function autocomplete list of clients from domicile
         */
         function autocomplete_clients(){

             /* autocomplete clients*/
            console.log(customers);

            var options_domicile = {
                data: customers,
                //url: "resources/heroes.json",

                categories: [{
                    listLocation: "data",
                    maxNumberOfElements: 4,
                    header: "Clientes"
                }],

                getValue: function(element) {
                    nombre=(element.name!="")?element.name:element.business_name;
                    return nombre+' - '+element.nit+' - '+element.phone;
                },

                template: {
                    type: "description",
                    fields: {
                        description: "nit"
                    }
                },

                list: {
                    maxNumberOfElements: 8,
                    match: {
                        enabled: true
                    },
                    sort: {
                        enabled: true
                    },
                    onClickEvent: function() {
                        $(".edit-client-domicile").removeClass('hidden');
                        customer =  $("#input-clients").getSelectedItemData().id;
                        $("#id-client-edit-domicile").val(customer);
                        let telephone_client = $("#input-clients").getSelectedItemData().phone;
                        let address_client = $("#input-clients").getSelectedItemData().address;
                        $("#input-telephone-client").val(telephone_client);
                        $("#input-address-client").val(address_client);
                    },
                    onKeyEnterEvent: function(){
                        $(".edit-client-domicile").removeClass('hidden');
                        customer =  $("#input-clients").getSelectedItemData().id;
                        $("#id-client-edit-domicile").val(customer);
                        let telephone_client = $("#input-clients").getSelectedItemData().phone;
                        let address_client = $("#input-clients").getSelectedItemData().address;
                        $("#input-telephone-client").val(telephone_client);
                        $("#input-address-client").val(address_client);
                    }
                },

                theme: "square"
            };
            
            var options_client = {
                data: customers,
                
                //url: "resources/heroes.json",

                categories: [{
                    listLocation: "data",
                    maxNumberOfElements: 4,
                    header: "Clientes"
                }],

                getValue: function(element) {
                    nombre=(element.name!="")?element.name:element.business_name;
                    return nombre+' - '+element.nit+' - '+element.phone;
                },

                template: {
                    type: "description",
                    fields: {
                        description: "nit"
                    }
                },

                list: {
                    maxNumberOfElements: 8,
                    match: {
                        enabled: true
                    },
                    sort: {
                        enabled: true
                    },
                    onClickEvent: function() {
                        $(".control-edit-client").removeClass("hidden");
                        customer =  $("#search-clients").getSelectedItemData().id;
                        $("#id-client-edit").val(customer);
                        $("#name-client-edit").val($("#search-clients").getSelectedItemData().name);
                        $("#cellphone-client-edit").val($("#search-clients").getSelectedItemData().phone);
                        $("#address-client-edit").val($("#search-clients").getSelectedItemData().address);
                    },
                    onKeyEnterEvent: function(){
                        $(".control-edit-client").removeClass("hidden");
                        customer =  $("#search-clients").getSelectedItemData().id;
                        $("#id-client-edit").val(customer);
                        $("#name-client-edit").val($("#search-clients").getSelectedItemData().name);
                        $("#cellphone-client-edit").val($("#search-clients").getSelectedItemData().phone);
                        $("#address-client-edit").val($("#search-clients").getSelectedItemData().address);
                    }
                },

                theme: "square"
            };

		    $("#input-clients").easyAutocomplete(options_domicile); 
            $("#search-clients").easyAutocomplete(options_client);
         }

        /**
         * This function open modal payment
         */
         function open_modal_payment(){
            modal.css("visibility","visible");
            modal_content.animate({ 'right' : '0%' }, 300, function() { });
        }
        
        function close_modal_payment(){
            modal_content.animate({ 'right' : '-50%' }, 300, function() { 
                    modal.css('visibility','hidden');
                });
        }
        
        /**
         * This function consult the order in command
         * @param {number} zone any number
         * @param {number} table any number
         */
         function get_order_product_restaurant(zone,table){
          
            zona = zone;
            mesa = table; 
            if ((zona != undefined) && (mesa != undefined)){
                load_propine();
                updateTotal();
                $.post("<?= site_url('/orden_compra/get_order_products'); ?>",{
                    zona:zona,
                    mesa:mesa   
                },function(data){
                    //alert(total_propine);
                    //alert(total_discount);
                    //alert(formatNumber(data.total));
                    let valor_total_comanda = formatNumber(data.total) + total_propine - total_discount;
                    $("#valor_recibido").val(number_format(valor_total_comanda));
                    $("#valor_recibido").attr('data-total',number_format(valor_total_comanda));
                    $(".total-payment").html(symbol + number_format(formatNumber(data.total) + total_propine));
                    
                    load_propine();
                    updateTotal();
                    help_pay_amounts();
                    $("#valor_recibido").select();
                });
            }
         }
     
        /**
         * This function delete a payment
         * @param {number} id any number
         */
         function removePayment(id){
            console.log(payments);
            if(payments.length > 0){
                for(var i=0; i<payments.length; i++){
                    if (payments[i].consecutive == id){
                        payments.splice(i, 1);
                        updatePayments(id);
                    }
                }
            }  

             updateTotal();
        }  
        
       
         /**
         * This function update a payment
         * @param {number} id any number
         */
        function updatePayments(id){
            $(".content-item-payment").each(function(index,element){
                let elementId = $(this).data('id');
                if(elementId == id){
                    element.remove();
                }
            })
        }
        

        function remove_propine(){
            //alert('propina'+total_propine);
            //alert('descuento'+total_discount);
           
            let total = formatNumber($(".total-payment").html()) - total_propine;
            //alert('total'+total);
            total_propine = 0;
            propine = 0;
            $(".total-propine").html(number_format(total_propine));
            $(".total-payment").html(symbol + number_format(total));
            load_discount();
            //$("#valor_recibido").val(number_format(total - total_discount));
            //$("#valor_recibido").attr('data-total',number_format(total - total_discount));
       
            updateTotal();
        }

         function remove_discount(){
            total_discount = 0;
            discount = 0;
            total_discount = 0;
            type_discount = 'value';

            $(".total-discount").html(symbol+number_format(total_discount));

            $("#valor_recibido").val(number_format(formatNumber($(".total-payment").html())));
            $("#valor_recibido").attr('data-total',number_format(formatNumber($(".total-payment").html())));
            load_propine();
            updateTotal();
        }
        
         


         /**
         * This function update order totals
         */
        function updateTotal(){
            let total_payments = 0;
            $.each(payments,function(index,element){
                total_payments += element.total_payment_method; 
            });

            total_payments.toFixed(decimals);
            $(".total-received").html(symbol + ' ' +number_format(total_payments));
            updateChange();
            console.log("total"+total_payments + total_discount);
            if( ( (total_payments + total_discount).toFixed(decimals) >= formatNumber($(".total-payment").html()) ) && (formatNumber($(".total-payment").html()) > 0) ){
                if(check_automatic_print){
                    //checkout();
                    if(quick_service == 'si' && quick_service_command == 'si' && zona < 0){
                        generate_command();
                    }else{
                        checkout();
                    }
                }else{
                    $(".btn-print").removeAttr('disabled');
                    $(".btn-print").removeClass('btn-print-disabled');
                }
            }
        }


         /**
         * This function update total change
         */
        function updateChange(){
            let total_change = 0;
            let total_payment_html = $(".total-payment").html();
            let total_received_html = $(".total-received").html();

            let total_payment = formatNumber(total_payment_html);
            let total_received = formatNumber(total_received_html);
           
            if((total_received + total_discount) > total_payment){
                 total_change = total_received - (total_payment - total_discount);
            }else if(total_received > 0){
                $("#valor_recibido").val(number_format(total_payment - (total_received + total_discount) ));
            }

            if(total_payment - (total_received + total_discount) <= 0){
                $(".total-to-payment").html(symbol + number_format(0));
            }else{
                $(".total-to-payment").html(symbol + number_format(total_payment - (total_received + total_discount) ));
            }
            $(".total-change").html(symbol + number_format(total_change));
            update_cursor_position();
        }

         /**
         * This function format a number based on the customer's options
         * @param {number} number any number
         */
        function formatNumber(number){
            number = number.replace(symbol,'');
            let number_aux = number;
            console.log(symbol + '- '+ number_aux+' - ');
            if(number.search(thousands_sep) != -1){
                //number_aux = number.replace(thousands_sep,''); 
                //number_aux = number_aux.replace(decimals_sep,'.'); 
                number_aux = number_aux.split(thousands_sep);
                let stripped = number_aux.join('');
                number_aux = stripped.replace(decimals_sep,'.');
            }
            console.log(number_aux);
            return parseFloat(number_aux);
        }
        
         /**
         * This function calculate the remaining to pay
         * @return {number} remaining
         */
        function remaining_to_pay(){
            
            let total_payment_html = $(".total-payment").html();
            let total_received_html = $(".total-received").html();

            let total_payment = formatNumber(total_payment_html);
            let total_received = formatNumber(total_received_html);
            
            if(total_received > 0){
                remaining = (total_payment - (total_received + total_discount) ).toFixed(decimals);
            }else{
                remaining = total_payment - total_discount;
            }
            
            remaining  = remaining - formatNumber($("#valor_recibido").val());
            return remaining;
        }
        
         /**
         * This function calculates helps monetary amounts in payments
         */
        function help_pay_amounts(){
            $(".help-money-value").html('');
            let amount = formatNumber($("#valor_recibido").val());
            $.post("<?= site_url('/orden_compra/helpPayAmount'); ?>",{
                amount : amount
            },function(data){
                let help_payment = JSON.parse(data);
                
                $.each(help_payment,function(index,element){
                    let help_value = '';
                    help_value += '<div class="col-sm-12 col-sm-offset-1 money-value text-center ml-0"';
                    help_value += ' onclick="load_pay_amount('+(element)+')">';
                    help_value += symbol+number_format(element);
                    help_value += '</div>'; 
                    $(".help-money-value").append(help_value);
                })
            })
        }
        
        /**
         * This function charge a payment in the total received
         */
        function load_pay_amount(value){
            $("#valor_recibido").val(number_format(value));
            update_cursor_position();
        }
        
        /**
         * This function make the payment process
         */
        function checkout(){
            var products = [];
            
            if ((zona != undefined) && (mesa != undefined)){
                $.post("<?= site_url('/orden_compra/get_order_products'); ?>",{
                    zona:zona,
                    mesa:mesa
                },function(data){
                    $.each(data.orden,function(key,value) {
                        if(value.is_adicional === false) {
                            products.push({
                                id: value.order_producto,
                                quantity: value.cantidad
                            });
                        } else {
                            if(value.tax_aditional == 0){
                                products.push({
                                    id: value.order_producto,
                                    quantity: value.cantidad,
                                    price: value.precio
                                });
                            } else {
                                var tax_adicionado = value.precio * value.tax_aditional / 100;
                                products.push({
                                    id: value.order_producto,
                                    quantity: value.cantidad,
                                    price: value.precio - tax_adicionado
                                });
                            }
                        }
                        
                    });
                    let total_payment_html = $(".total-payment").html();
                    let total_received_html = $(".total-received").html();

                    let total_payment = formatNumber(total_payment_html);
                    //let total_received = total_received_html.replace(symbol,'');
                    let total_received = formatNumber(total_received_html);
                    let total_change = (total_received + total_discount)  - total_payment;
                    let type_process = '';
                    if(quick_service == "si" && zona == -1){
                        type_process = 'quick-service';
                    }else{
                        type_process = 'take-order';
                    }
                    
                   
                    

                    $.ajax({
                        url : "<?= site_url('/orden_compra/process'); ?>",
                        data : {
                            zone:zona,
                            table:mesa,
                            products: products,
                            payments: payments,
                            store_id: store_id,
                            total_payment:total_payment,
                            total_received:total_received,
                            total_change:total_change,
                            selected_domicile: selected_domicile,
                            customer:customer,
                            type_process: type_process,
                            total_discount:total_discount,
                            discount:discount,
                            type_discount:type_discount,
                            total_propine:total_propine,
                            note_invoice:note_invoice,
                            order_consecutive:order_consecutive,
                            quick_service_command:quick_service_command
                        },
                        method : 'post', //en este caso
                        dataType : 'json',
                        success : function(response){
                            close_modal_payment();
                            if(response == null){
                                intents_checkout++;
                                if(intents_checkout == 1){
                                    checkout();
                                }else{
                                    reset_values();
                                    swal({
                                        type: 'error',
                                        title: 'Error',
                                        text: 'No fue posible generar la venta de manera correcta.'
                                    })
                                }
                            }else{
                                reset_values();
                                if(response.status){
                                    if(new_fast_print){
                                        //console.log(JSON.stringify(response.fast_print_data_new));
                                        //alert();
                                        conn.send(JSON.stringify(response.fast_print_data_new));
                                        location.href = "<?= site_url('tomaPedidos');?>";
                                    }else if(response.fast_print){
                                        if(quick_service == 'si' && zona < 0){
                                            location.href = "<?= site_url('tomaPedidos');?>";
                                        }else{
                                            location.href = "<?= site_url('tomaPedidos');?>";
                                        }
                                    }else{ 
                                        print(response.id_sale);
                                    }
                                    
                                }else{
                                    swal({
                                            type: 'error',
                                            title: 'Error',
                                            text: 'No fue posible generar la venta de manera correcta.'
                                        })
                                }
                            }
                        },
                        error: function(error){
                            close_modal_payment();
                            swal({
                                type: 'error',
                                title: 'Error',
                                text: 'Ocurrio un error al momento de generar la venta.'
                            })
                        }
                    });

                    //alert(Math.round(valor_total_comanda));
                });

            }else{
                alert("error");
            }
        }

         /**
         * This function print the invoice
         */
         function print(id_sale){

            $.fancybox.open({
                'width' : '85%',
                'height' : '85%',
                'autoScale' : false,
                'transitionIn' : 'none',
                'transitionOut' : 'none',
                href : url_print+'/'+id_sale,
                type : 'iframe',

                afterClose: function(){
                    if(quick_service == 'si' && zona < 0){
                        location.href = "<?= site_url('orden_compra/mi_orden/-1/'.strtotime("now")); ?>";
                    }else{
                        location.href = "<?= site_url('tomaPedidos');?>";
                    }
                }
            });
        }
        
         /**
         * This function format a number based on the customer's options
         * @param {number} number any number
         * @return {number} number
         */
        function number_format(number) {
            number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
            var n = !isFinite(+number) ? 0 : +number,
                prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
                sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
                dec = (typeof decimals_sep === 'undefined') ? '.' : decimals_sep,
                s = '',
                toFixedFix = function (n, prec) {
                    var k = Math.pow(10, prec);
                    return '' + Math.round(n * k) / k;
                };
            // Fix for IE parseFloat(0.55).toFixed(0) = 0;
            s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
            if (s[0].length > 3) {
                s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
            }
            if ((s[1] || '').length < prec) {
                s[1] = s[1] || '';
                s[1] += new Array(prec - s[1].length + 1).join('0');
            }
            return s.join(dec);
        }

         /**
         * This function validate state box
         * 
         */
        function verify_state_box(){
            let url_verify = "<?= site_url('caja/verify');?>";
            $.get(url_verify,function(data){
                let response = JSON.parse(data);
                if(response.estado_caja == 'abierta'){
                    open_modal_payment();
                }else{
                   location.href="<?= site_url('caja/apertura');?>";
                }
            })

            /*let url_state_box =  "<?= site_url('caja/verify_state'); ?>"; 
            $.get(url_state_box,function(data){
                let response = JSON.parse(data);
                if(response.status){
                    open_modal_payment();
                }else{
                    //open_modal_payment();
                    open_modal_box();
                }
            })*/
        }


        /**
         * [ function open_modal_box with API]
         *
         */
         function open_modal_box(){
            
            Swal({
                    title: 'Abrir caja',
                    input: 'text',
                    inputValue: 0,
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Abrir caja',
                    cancelButtonText: 'Cancelar',
                    showLoaderOnConfirm: true,
                    preConfirm: (opening_value) => {
                        var response = {'status':false};
                        return response;
                    },
                    allowOutsideClick: () => !Swal.isLoading()
                }).then((result) => {
                    console.log(result);
                    switch(result.status){
                        case 'open' : console.log("La caja se encuentra abierta");
                            location.href = "<?= site_url('caja/apertura') ?>"; 
                            window.location.href = "<?= site_url(''); ?>";
                        break;

                        case 'close' : 
                            console.log("La caja se encuentra cerrada");
                        break;
                    }
                    if(result.status == 'open'){

                    }else{

                    }
                if (result.value) {
                    if(result.value.status){
                        alert("caja aperturada con exito");
                    }else{
                        alert("no fue posible abrir la caja");
                    }
                }
            })
         }

        
        /**
         * [ This function save client new]
         *
         * @return  [string]  [return status response]
         */
        function save_client(){
            let url_save_client = '<?= site_url("clientes/add_client_curl");?>';
            let business_name = $("#name-client").val();
            let type_identification_client = $("#type-identification-client").val();
            let numero = $.trim($("#identification-client").val());
            let nit =  type_identification_client+'|'+numero;
            let email = $("#email-client").val();
            let phone = $("#telephone-client").val();
            let mobile = $("#cellphone-client").val();
            let address = $("#address-client").val();
            let country = $("#country-client").val();
            let province = $("#city-client").val();
            let customers_group = $("#group-client").val();
            let domi=$("#client-domicilio").val();
            let band=false;
            let mensaje="Los campos: Nombre, Número de Identificación y Correo son obligatorios";
            
            if(domi==0){
                if((business_name != "") &&(type_identification_client != "") && (numero != "") &&(email != "")){
                    band=true;                    
                }
            }else{
                if((business_name != "") && (type_identification_client != "") && ((phone != "") ||(mobile != "")) &&(numero != "") && (address != "")){
                    band=true;                   
                }else{
                    mensaje="Los campos: Nombre, Número de Identificación, Teléfono y Dirección son obligatorios";
                }   
            }
            
            if(band){
                $.ajax({
                    url : "<?= site_url('/clientes/add_client_curl'); ?>",
                    data : {
                        business_name : business_name, 
                        nit : nit, 
                        email : email, 
                        phone : phone, 
                        mobile : mobile, 
                        address : address, 
                        country : country, 
                        province : province, 
                        customers_group : customers_group
                    },
                    method : 'post', //en este caso
                    dataType : 'json',
                    success : function(response){                        
                        if(response.errors){                                                       
                            var sweet_errors = "";
                            $.each(response.errors, function (i) {
                                sweet_errors += "<br>"+response.errors[i];                                
                            })
                                                   
                            swal({
                                type: 'error',
                                title: 'Error',
                                html: sweet_errors
                            })
                        }else{
                            let client = (response.data);
                            customer = client.id;
                            $("#modal-add-client").css('visibility','hidden');
                            /** load client domicile */
                            $("#input-clients").val(client.name);
                            $("#input-telephone-client").val(client.phone);
                            $("#input-address-client").val(client.address);

                            /* load general client*/
                            $(".control-edit-client").removeClass("hidden");
                            $("#search-clients").val(client.name); 
                        }                        
                    },
                    error: function(error){
                        swal({
                            type: 'error',
                            title: 'Error',
                            text: error.errors
                        })
                    }
                });
            }else{
                swal({
                    type: 'error',
                    title: '',
                    text: mensaje                                        
                })

            }           
        }

         /**
         * [ This function save client new]
         *
         * @return  [string]  [return status response]
         */
        function edit_client(){
            let url_edit_client = '<?= site_url("clientes/edit_client_curl");?>';
            let id = $("#id-client-edit").val();
            let business_name = $("#name-client-edit").val();
            let mobile = $("#cellphone-client-edit").val();
            let address = $("#address-client-edit").val();

            $.ajax({
                url : url_edit_client,
                data : {
                    id : id, 
                    business_name : business_name, 
                    mobile : mobile, 
                    address : address
                },
                method : 'post', //en este caso
                dataType : 'json',
                success : function(response){

                    $("#modal-edit-client").css('visibility','hidden');
                    /* load general client*/
                    $("#search-clients").val(business_name);
                },
                error: function(error){
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: error.errors
                    })
                }
            });
        }

        function edit_client_domicile(){
            let url_edit_client = '<?= site_url("clientes/edit_client_curl");?>';
            let id = $("#id-client-edit-domicile").val();
            let mobile = $("#input-telephone-client").val();
            let address = $("#input-address-client").val();

            $.ajax({
                url : url_edit_client,
                data : {
                    id : id, 
                    mobile : mobile, 
                    address : address
                },
                method : 'post', //en este caso
                dataType : 'json',
                success : function(response){
                    swal({
                        type: 'success',
                        title: '',
                        text: 'Cliente editado con exito',
                        showConfirmButton: false,
                        timer: 1500
                    })
                },
                error: function(error){
                    swal({
                        type: 'error',
                        title: 'Error',
                        text: error.errors
                    })
                }
            });
            
        }
         /**
         * [ This function save city dependencies of country]
         * @params [String] [country]
         */
        function load_city_from_country(country) {
            $.ajax({
                url: "<?php echo site_url("orden_compra/load_cities_from_country"); ?>",
                type: "GET",
                dataType: "json",
                data: {"pais": country},
                success: function (data) {
                    $("#city-client").html('');
                    $.each(data, function(index, element){
                        provincia = "<?php echo set_value('provincia');?>"
                        sel = provincia == element[0] ? "selected='selected'" : '';
                    $("#city-client").append("<option value='"+ element[0] +"' "+sel+">"+ element[0] +"</option>");
                    });
                }
            });
        }

        
        /**
         * [ description]
         *
         * @return  [type]  [return description]
         */
        function generate_command(){
            
                let url_command = '<?= site_url("orden_compra/confirmarOrden"); ?>';
                let notacomanda = $('#message-nota').val();
                let comensales = $('#txt_comensales').val();

                $.ajax(
                    {
                        url:url_command,
                        type:'POST',
                        dataType:'json',
                        data: {
                            zona: zona,
                            mesa: mesa,
                            notacomanda: notacomanda,
                            comensales: comensales
                        }
                    }
                ).done(function(data){
                    //checkout();
                    swal({
                        title: 'Un momento!',
                        text: 'Se está imprimiendo la comanda.',
                        imageUrl: '<?php echo base_url()."uploads/loading_temp.gif";?>',
                        imageWidth: 200,
                        imageHeight: 200,
                        imageAlt: 'Cargando',
                        animation: false,
                        showConfirmButton: false
                    })
                    verify_print_command();
                });
        }


        /**
         * [ description]
         *
         * @return  [type]  [return description]
         */
        function verify_print_command(){
            let status_command = false;
            if(intents < 3){
                let url_verify_print_command = '<?= site_url("orden_compra/verify_print_command"); ?>';
                setTimeout(function(){ 
                    $.ajax({
                            url:url_verify_print_command,
                            type:'POST',
                            dataType:'json',
                            data: {
                                zona: zona,
                                mesa: mesa
                            }
                        }
                    ).done(function(data){
                        console.log(data);
                        if(data.status){
                            console.log("generando pago");
                            intents = 11;
                            status_command = true;
                            swal.close();
                            checkout();
                        }else{
                            console.log("Intentando");
                            intents++;
                            verify_print_command();
                        }
                    });
                }, 1000);
            }else if(!status_command){
                swal.close();
                Swal(
                    'Error!',
                    'No fue posible imprimir la comanda!',
                    'error'
                    )
                checkout();
            }
        }
        

        /**
         * This function reset values of sale
         *
         */
        function reset_values(){
            $(".btn-print").prop('disabled','true');
            $(".check-automatic-print").prop('disabled','true');
            $("#valor_recibido").val(0);
            $("#valor_recibido").prop('disabled','true');
            $(".items-payment").html("");
            $(".total-payment").html("0");
            $(".total-received").html("0");
            $(".total-change").html("0");
            $("#pagar_cuenta").prop('disabled','true');
            $("#method-payment-domicile").prop('disabled','true');
            $("#send-domicile").prop('disabled','true');
            
        }


     
</script>