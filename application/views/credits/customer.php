<?php
$is_admin = $this->session->userdata('is_admin');
$permisos = $this->session->userdata('permisos');
//dd($this->session->userdata);
?>
<!--<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">-->
<!-- Auto complete -->
<link rel="stylesheet" type="text/css" href="<?= base_url('public/easy-autocomplete/easy-autocomplete.min.css'); ?>">
<script type="text/javascript" src="<?= base_url('public/easy-autocomplete/jquery.easy-autocomplete.min.js'); ?>"></script>
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons" rel="stylesheet">
<link href="<?= base_url('/assets/vuetify2.0.0-alpha.2/vuetify.min.css') ?>" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>



<style>
    .disable-events {
        pointer-events: none;
        opacity: 0.7;
    }
    .color-red{
        color: red;
        margin-left: 5px;
    }
    .pt-0 {
        padding-top: 0px;
    }

    .pt-2 {
        padding-top: 15px !important;
    }

    .pb-0 {
        padding-bottom: 0px;
    }

    .mt-0 {
        margin-top: 0px !important;
    }

    .border {
        border: solid 1px #ccc;
        border-radius: 5px;
        margin-top: 10px;
        padding: 10px;
        box-sizing: border-box;
    }

    .image-client {
        width: 100%;
        cursor: pointer;
    }

    .content-client ul li {
        list-style: none;
    }

    .icon-client {
        font-size: 22px;
        margin: 5px;
    }

    .checker {
        position: absolute !important;
        z-index: -1;
    }

    .tabs-credits {
        margin-bottom: 0px !important;
    }

    .v-text-field__slot input {
        border: none;
    }

    .item-client {
        font-weight: bold;
    }

    .credits-search {
        padding-top: 0px !important;
        padding-left: 0px;
    }

    .success {
        background-color: #4caf50 !important;
        border-color: #4caf50 !important;
    }

    .general-icon {
        max-width: 30px;
        padding: 6px;
        box-sizing: border-box;
        outline: solid 1px #ccc;
    }

    .modal-generic-center {
        visibility: hidden;
        position: fixed;
        z-index: 1001;
        padding-top: 100px;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
    }

    .modal-content-center {
        background-color: #fefefe;
        padding: 0px;
        border: 1px solid #888;
    }

    /* The Close Button */
    .close-modal-center {
        color: darkgray;
        float: right;
        font-size: 28px;
        font-weight: bold;
        position: absolute;
        right: 5px;
        top: 3px;
    }

    .close-modal-center:hover,
    .close-modal-center:focus {
        color: #aeaeae;
        text-decoration: none;
        cursor: pointer;
    }

    .list-invoices-checked a:focus,
    .list-invoices-checked a:hover {
        color: #333 !important;
        text-decoration: none !important;
    }

    textarea {
        border: solid 1px lightgray;
    }
</style>

<div class="page-header">
    <div class="icon">
        <img alt="ventas_creditos" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_credito']['original'] ?>">
    </div>
    <h1 class="sub-title">Estado de cuenta</h1>
</div>


<div id="app">
    <div class="container">
        <div class="col-md-12 border content-client">
            <!--<div class="col-md-2">
                <img class="image-client" src="<?= base_url('uploads/general/client_default.png') ?>" alt="Sin foto">
            </div>-->
            <div class="col-md-6">
                <div class="content-item-client"><b class="item-client">Número Identificación:</b> {{ identification }}</div>
                <div class="content-item-client"><b class="item-client">Nombre:</b> {{ name }}</div>
                <div class="content-item-client"><b class="item-client">Email:</b> {{ email }}</div>
                <div class="content-item-client"><b class="item-client">Dirección:</b> {{ address }}</div>
                <div class="content-item-client"><b class="item-client">Teléfono:</b> {{ telephone }}</div>
            </div>
            <div class="col-md-6">
                <div class="content-item-client"><b class="item-client">Ultima factura:</b> {{ last_bill }}</div>
                <div class="content-item-client"><b class="item-client">Ultimo abono:</b> {{ last_payment }}</div>
                <div class="content-item-client"><b class="item-client">Total Facturas:</b> {{ credits_invoices }}</div>
                <div class="content-item-client"><b class="item-client">Facturas pendientes:</b> {{ pendient_invoices }}</div>
                <div class="content-item-client"><b class="item-client">Saldo a pagar:</b> {{ balance_to_pay }}</div>

            </div>

        </div>
    </div>


    <!-- tabs credits -->
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs tabs-credits" role="tablist">
            <li role="presentation" class="active"><a href="#invoice" aria-controls="invoice" role="tab" data-toggle="tab">Facturas</a></li>
            <li role="presentation"><a href="#payment" aria-controls="payment" role="tab" data-toggle="tab">Abonos</a></li>
            <!--<li role="presentation"><a href="#credit_note" aria-controls="credit_note" role="tab" data-toggle="tab">Notas crédito (NC)</a></li>
            <li role="presentation"><a href="#debit_note" ar ia-controls="debit_note" role="tab" data-toggle="tab">Notas dédito (ND) Proximamente</a></li>-->
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="invoice">
                <div class="container pt-0">
                    <template>
                        <v-card-title class="credits-search">
                            <v-text-field v-model="search" append-icon="search" label="Buscar factura" single-line hide-details></v-text-field>
                            <v-spacer></v-spacer>
                            <a href="<?= site_url('credits/exportInvoicesByClient') . '/' . $data["customer_id"]; ?>">
                                <img style="max-width:43px;" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">
                            </a>

                        </v-card-title>

                        <v-data-table v-model="checked_invoices" :headers="headers" :items="invoices" :search="search" :options="pagination" show-select item-key="id" class="elevation-1">
                            <template v-slot:headers="props">
                                <tr>

                                    <th v-for="header in props.headers" :key="header.text" :class="['column sortable', pagination.descending ? 'desc' : 'asc', header.value === pagination.sortBy ? 'active' : '']" @click="changeSort(header.value)">
                                        <v-icon small>arrow_upward</v-icon>
                                        {{ header.text }}
                                    </th>

                                    <th>
                                        <div style="display: flex;align-items: center;">
                                            <v-checkbox :input-value="props.all" :indeterminate="props.indeterminate" primary hide-details @click.stop="toggleAll"></v-checkbox>
                                            <!--<button @click="openModal('modal-payment-invoice')" style="height: 30px;box-sizing: border-box;background: green;text-align: center;padding: 7px;border-radius: 4px;color: #fff;">Pagar</buton>-->
                                            <template>
                                                <v-layout row justify-center>
                                                    <v-dialog v-model="dialog" persistent max-width="600px">
                                                        <template v-slot:activator="{ on }">
                                                            <v-btn color="success" dark v-on="on">Pagar</v-btn>
                                                        </template>
                                                        <v-card>
                                                            <v-card-title class="headline  text-md-center">Pagar facturas</v-card-title>
                                                            <hr>

                                                            <v-card-text class="pt-0 pb-0">
                                                                <v-container grid-list-md class="pt-0">

                                                                    <v-subheader>Facturas a crédito</v-subheader>

                                                                    <v-list-tile>
                                                                        <v-list-tile-content>
                                                                            <v-list-tile-title>Facturas seleccionadas</v-list-tile-title>
                                                                            <v-list-tile-sub-title>A continuación se detallan las facturas que van a pagarse en su totalidad.</v-list-tile-sub-title>
                                                                        </v-list-tile-content>
                                                                    </v-list-tile>

                                                                    <v-list two-line subheader>

                                                                        <v-list-tile v-for="item in checked_invoices" :key="item.title" avatar @click="" class="list-invoices-checked">
                                                                            <v-list-tile-avatar>
                                                                                <v-icon :class="call_to_action">assignment</v-icon>
                                                                            </v-list-tile-avatar>

                                                                            <v-list-tile-content>
                                                                                <v-list-tile-title>{{ item.invoice }}</v-list-tile-title>
                                                                                <v-list-tile-sub-title>{{ symbol + item.totalPending }}</v-list-tile-sub-title>
                                                                            </v-list-tile-content>

                                                                            <v-list-tile-action>
                                                                                <v-btn icon ripple>
                                                                                    <v-icon color="grey lighten-1">check_circle</v-icon>
                                                                                </v-btn>
                                                                            </v-list-tile-action>
                                                                        </v-list-tile>
                                                                        <hr>
                                                                        <h4 class="text-right">Total: {{ symbol + totalPaymentSelected }}</h4>
                                                                    </v-list>


                                                                </v-container>
                                                            </v-card-text>

                                                            <v-card-actions style="background-color: #eee;">
                                                                <v-spacer></v-spacer>
                                                                <v-btn color="blue darken-1" flat @click="dialog = false">Cerrar</v-btn>
                                                                <v-dialog v-model="dialogPayment" persistent max-width="390">
                                                                    <template v-slot:activator="{ on }">
                                                                        <v-btn color="success" dark v-on="on">Pagar</v-btn>
                                                                    </template>
                                                                    <v-card class="pt-2">
                                                                        <h4 class="text-center mt-0">Seleccione forma de pago</h4>

                                                                        <v-container grid-list-md class="pt-0">
                                                                            <hr>
                                                                            <div class="row mt-2">
                                                                                <div class="col-md-7 form-group">
                                                                                    <label for="">Fecha de pago</label>
                                                                                    <input type="date" class="form-control" v-model="paymentDate" min="<?= date('Y-m-d') ?>" date-format="yy-mm-dd">
                                                                                </div>
                                                                            </div>

                                                                            <div class="row mt-2">
                                                                                <div class="col-md-7 form-group">
                                                                                    <select name="" id="" class="form-control" v-model="paymentMethod">
                                                                                        <option v-for="paymentMethod in paymentMethods" :value="paymentMethod.id">{{ paymentMethod.nombre }} </option>
                                                                                    </select>
                                                                                </div>
                                                                                <div class="col-md-4 form-group">
                                                                                    <input type="text" class="form-control" v-model="totalPaid" readonly>
                                                                                </div>
                                                                                <!--<div class="col-md-1 pl-0">
                                                                                <v-icon color="success" style="color:#4caf50!important; font-size:28px; margin-top:2px; cursor:pointer;" @click="addPayment">add_circle</v-icon>
                                                                            </div>-->
                                                                            </div>
                                                                            <hr>
                                                                            <p>Total recibido: {{ symbol + totalPaid }}</p>
                                                                            <hr>
                                                                            <template>
                                                                                <v-progress-circular v-if="process" :size="40" color="green" indeterminate></v-progress-circular>
                                                                            </template>

                                                                            <v-card-actions>
                                                                                <v-spacer></v-spacer>
                                                                                <v-btn color="blue darken-1" flat @click="dialogPayment = false">Cerrar</v-btn>
                                                                                <v-btn color="success" @click="payBills()">Pagar</v-btn>
                                                                            </v-card-actions>
                                                                        </v-container>
                                                                    </v-card>
                                                                </v-dialog>

                                                            </v-card-actions>
                                                        </v-card>
                                                    </v-dialog>
                                                </v-layout>
                                            </template>
                                        </div>
                                    </th>
                                </tr>
                            </template>
                            <template v-slot:items="props">
                                <tr :active="props.selected" @click="props.selected = !props.selected">

                                    <td>{{ props.item.invoice }}</td>
                                    <td class="text-xs-center">{{ props.item.client }}</td>
                                    <td class="text-xs-center">{{ props.item.totalSale }}</td>
                                    <td class="text-xs-center">{{ props.item.retention }}</td>
                                    <td class="text-xs-center">{{ props.item.totalPending }}</td>
                                    <td class="text-xs-center">{{ props.item.date }}</td>
                                    <td class="text-xs-center">{{ props.item.expiration_date }}</td>
                                    <td>

                                        <!-- Actions invoice-->
                                        <div class="content-buttons" style="display: flex;align-items: center;justify-content: center;">
                                            <v-checkbox :input-value="props.selected" primary hide-details></v-checkbox>
                                            <a data-tooltip="Abonar" @click="showPayInvoice(props.item.id, props.item)">
                                                <img class="general-icon" src="<?= base_url('uploads') ?>/iconos/Gris/icono_gris-26.svg" alt="Imprimir">
                                            </a>
                                            <a data-tooltip="Imprimir" target="_blank" class="btnPrint" :href="'<?= site_url() ?>/credito/imprimir/' + props.item.id + '/copia'">
                                                <img class="general-icon" src="<?= base_url('uploads') ?>/iconos/Gris/icono_gris-18.svg" alt="Imprimir">
                                            </a>
                                            <a data-tooltip="Anular" href="#" :id="props.item.id" class="anular">
                                                <img class="general-icon" src="<?= base_url('uploads') ?>/iconos/Verde/icono_verde-22.svg" alt="Imprimir">
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                La busqueda por "{{ search }}" no contiene resultados.
                            </v-alert>
                        </v-data-table>
                    </template>
                </div>
            </div>
            <!-- end tab invoice-->
            <div role="tabpanel" class="tab-pane" id="payment">
                <div class="container pt-0">
                    <template>
                        <v-card-title class="credits-search">
                            <v-text-field v-model="search" append-icon="search" label="Buscar factura" single-line hide-details></v-text-field>
                            <v-spacer></v-spacer>
                            <a href="<?= site_url('credits/exportInvoicesByClient') . '/' . $data["customer_id"]; ?>">
                                <img style="max-width:43px;" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">
                            </a>

                        </v-card-title>

                        <v-data-table v-model="checked_invoices" :headers="headers_abonos" :items="todos_abonos" :search="search" :options="pagination" show-select item-key="id" class="elevation-1">
                            <template v-slot:headers="props">
                                <tr>
                                    <th v-for="header in props.headers" :key="header.text" :class="['column sortable', pagination.descending ? 'desc' : 'asc', header.value === pagination.sortBy ? 'active' : '']" @click="changeSort(header.value)">
                                        <v-icon small>arrow_upward</v-icon>
                                        {{ header.text }}
                                    </th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </template>
                            <template v-slot:items="props">
                                <tr :active="props.selected" @click="props.selected = !props.selected">

                                    <td>{{ props.item.invoice }}</td>
                                    <td class="text-xs-center">{{ props.item.fecha_pago }}</td>
                                    <td class="text-xs-center">{{ number_format(props.item.cantidad) }}</td>
                                    <td class="text-xs-center">{{ props.item.tipo }}</td>
                                    <td class="text-xs-center">{{ number_format(props.item.importe_retencion) }}</td>
                                    <td class="text-xs-center">
                                        <div class="content-buttons">
                                            <a data-tooltip="Eliminar" href="#" class="" @click="deletePayment(props.item)">
                                                <img class="general-icon" src="<?= base_url('uploads') ?>/iconos/Verde/icono_verde-22.svg" alt="Imprimir">
                                            </a>
                                            <!--<a data-tooltip="Ver detalle" :href="'<?= site_url() ?>/pagos/ver_pago/' + props.item.id">
                                                 <img class="general-icon" src="<?= base_url('uploads') ?>/iconos/Gris/icono_gris-26.svg" alt="Imprimir">
                                            </a>-
                                            <a data-tooltip="Imprimir" target="_blank" class="btnPrint" :href="'<?= site_url() ?>/credito/imprimir/' + props.item.id + '/copia'">
                                                <img class="general-icon" src="<?= base_url('uploads') ?>/iconos/Gris/icono_gris-18.svg" alt="Imprimir">
                                            </a>
                                            <a data-tooltip="Anular" href="#" :id="props.item.id" class="anular">
                                                <img class="general-icon" src="<?= base_url('uploads') ?>/iconos/Verde/icono_verde-22.svg" alt="Imprimir">
                                            </a>-->
                                        </div>
                                    </td>
                                </tr>
                            </template>
                            <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                                La busqueda por "{{ search }}" no contiene resultados.
                            </v-alert>
                        </v-data-table>
                    </template>
                </div>
            </div>
            <div role="tabpanel" class="tab-pane" id="credit_note">
                <template>
                    <v-card-title class="credits-search">
                        <v-text-field v-model="searchCreditNotes" append-icon="search" label="Buscar nota credito" single-line hide-details></v-text-field>
                        <v-spacer></v-spacer>
                        <a href="<?= site_url('credits/exportCreditNotesByClient') . '/' . $data["customer_id"]; ?>">
                            <img style="max-width:43px;" src="<?php echo $this->session->userdata('new_imagenes')['exportar_excel_verde']['original'] ?>">
                        </a>

                    </v-card-title>

                    <v-data-table v-model="creditNotes" :headers="headersCreditNotes" :items="creditNotes" :search="searchCreditNotes" :options="pagination" show-select item-key="id" class="elevation-1">
                        <template v-slot:headers="props">
                            <tr>

                                <th v-for="header in props.headers" :key="header.text" :class="['column sortable', paginationCreditNotes.descending ? 'desc' : 'asc', header.value === paginationCreditNotes.sortBy ? 'active' : '']" @click="changeSortCreditNotes(header.value)">
                                    <v-icon small>arrow_upward</v-icon>
                                    {{ header.text }}
                                </th>
                            </tr>
                        </template>
                        <template v-slot:items="props">
                            <tr :active="props.selected">

                                <td>{{ props.item.consecutive }}</td>
                                <td class="text-xs-center">{{ props.item.total }}</td>
                                <td class="text-xs-center">{{ props.item.invoice }}</td>
                                <td class="text-xs-center">{{ props.item.date }}</td>
                                <td class="text-xs-center">{{ props.item.state }}</td>
                            </tr>
                        </template>
                        <v-alert v-slot:no-results :value="true" color="error" icon="warning">
                            La busqueda por "{{ search }}" no contiene resultados.
                        </v-alert>
                    </v-data-table>
                </template>
            </div>
            <div role="tabpanel" class="tab-pane" id="debit_note">...</div>
        </div>
    </div>

    <!--Validar estado del -->
    <v-dialog v-model="dialogValidateBox" persistent max-width="590">
        <v-card class="pt-2">
            <v-card-title class="headline  text-md-center">Apertura de caja</v-card-title><hr>

            <v-container grid-list-md class="pt-0">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payValue">Valor de la caja</label>
                                <input type="text" class="form-control" id="payValue" v-model="valueOpenBox" placeholder="Valor de la caja">
                            </div>
                        </div>
                    </div>
                </div>
            </v-container>
            <v-card-actions style="background-color: #eee;">
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click="dialogValidateBox = false">Cerrar</v-btn>
                <v-btn color="success" dark @click="openBox()">Abrir</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>


    <!-- modal respomse payments -->
    <v-dialog v-model="dialogPaymentInvoice" persistent max-width="590">
        <v-card class="pt-2">
            <v-card-title class="headline  text-md-center">Abonar a factura No: {{ modal_data.invoice  }}</v-card-title><hr>

            <v-container grid-list-md class="pt-0">
                <v-subheader>Saldo pendiente: {{ modal_data.totalPending  }}</v-subheader>
                <div class="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payDate">Fecha de pago
                                    <input type="text" class="form-control" readonly id="payDate" v-model="payDate" date-format="yy-mm-dd">
                                </label>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="payDate">Forma de pago</label>
                            <select class="form-control" v-model="paymentMethod" >
                                <option v-for="paymentMethod in paymentMethods" :value="paymentMethod.codigo">{{ paymentMethod.nombre }} </option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payValue">Valor a pagar</label>
                                <input type="text" class="form-control" id="payValue" v-model="payValue" required maxlength="10">
                                <span class="text-muted color-red font-size-9 valid_payValue" v-if="valid.payValue">Valor a pagar requerido</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="payRetention">Retención</label>
                                <input type="text" class="form-control" id="payRetention" v-model="payRetention" required maxlength="10">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12" v-if="valid_max_value">
                         <span style="color: red;">La suma de los campos "Valor a pagar" ( {{payValue}} ) con "Retención" ({{payRetention}}) no debe ser mayor al saldo pendiente: {{modal_data.totalPending}}</span>
                         <br>
                    </div>
                    <div class="col-md-12">
                        <span>Total abonar: {{ totalPayInvoice }}</span>
                    </div>
                </div>
            </v-container>
            <v-card-actions style="background-color: #eee;">
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="dialogPaymentInvoice = false">Cerrar</v-btn>
                    <v-btn color="success" :class="{'disable-events': valid_max_value}" dark @click="addPayInvoice()">Abonar</v-btn>
                </v-card-actions>
        </v-card>
    </v-dialog>

    <!-- modal respomse payments -->
    <v-dialog v-model="dialogPaymentResponse" persistent max-width="390">
        <v-card class="pt-2">
            <h4 class="text-center mt-0">Abonos</h4>

            <v-container grid-list-md class="pt-0">
                <v-subheader>Abonos realizados con exito</v-subheader>
                <v-list two-line subheader>
                    <v-list-tile v-for="item in paymentSuccess" :key="item.id" avatar @click="" class="list-invoices-checked">
                        <v-list-tile-avatar>
                            <v-icon :class="call_to_action">assignment</v-icon>
                        </v-list-tile-avatar>

                        <v-list-tile-content>
                            <v-list-tile-title>{{ item.invoice }}</v-list-tile-title>
                            <v-list-tile-sub-title>{{ symbol + item.totalPending }}</v-list-tile-sub-title>
                        </v-list-tile-content>

                        <v-list-tile-action>
                            <v-btn icon ripple>
                                <v-icon color="grey lighten-1">check_circle</v-icon>
                            </v-btn>
                        </v-list-tile-action>
                    </v-list-tile>
                    <hr>
                </v-list>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn color="blue darken-1" flat @click="dialogPaymentResponse = false">Cerrar</v-btn>
                </v-card-actions>
            </v-container>
        </v-card>
    </v-dialog>



</div>





<script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.js"></script> -->
<script src="<?= base_url('/assets/vuetify2.0.0-alpha.2/vuetify.min.js') ?>"></script>
<script src="https://cdn.jsdelivr.net/vue.resource/1.3.1/vue-resource.min.js"></script>
<script src="<?= base_url('/public/fancybox/jquery.fancybox.js') ?>"></script>
<script src="<?= base_url('/assets/api_url.js') ?>"></script>
<script src="<?= base_url('/assets/toMoney.js') ?>"></script>
<!-- <?php  $api_auth = (isset(json_decode($_SESSION['api_auth'])->token)) ? json_decode($_SESSION['api_auth'])->token : '';?> -->
<script>
    var token_php = "<?php echo $api_auth; ?>";

    $(document).ready(function() {
        var dateObj = new Date();
        var month = dateObj.getUTCMonth() + 1; //months from 1-12
        var day = dateObj.getUTCDate();
        var year = dateObj.getUTCFullYear();
        let month_2;
        if (month.toString().length == 1) {
            month_2 = "0" + month.toString();
        } else {
            month_2 = month.toString();
        }

        
        function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
            try {
                decimalCount = Math.abs(decimalCount);
                decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

                const negativeSign = amount < 0 ? "-" : "";

                let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                let j = (i.length > 3) ? i.length % 3 : 0;

                return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
            } catch (e) {
                //
            }
        };
        var customers = <?= json_encode($data["customers"]); ?>;
        var options_client = {
            data: customers,

            categories: [{
                listLocation: "data",
                maxNumberOfElements: 4,
                header: "Clientes"
            }],

            getValue: function(element) {
                return element.name + ' - ' + element.nit;
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
                    app.customer_id = $("#search-clients").getSelectedItemData().id;
                    $(".btn-load-client").removeClass('disabled');
                    app.loadAccountStatus();
                    app.loadInvoices();
                    app.getCreditNotes();
                },
                onKeyEnterEvent: function() {
                    app.customer_id = $("#search-clients").getSelectedItemData().id;
                    $(".btn-load-client").removeClass('disabled');
                    app.loadAccountStatus();
                    app.loadInvoices();
                    app.getCreditNotes();
                }
            },
            theme: "square"
        };

        $("#search-clients").easyAutocomplete(options_client);

        $('.btnPrint').fancybox({
            'width': '85%',
            'height': '85%',
            'autoScale': false,
            'transitionIn': 'none',
            'transitionOut': 'none',
            'type': 'iframe'
        });

        var anularDialog = anularDialog || (function($) {
            'use strict';
            // Creating modal dialog's DOM
            var $dialog = $(
                '<div id="dialog-motivo-form"class="modal fade"  data-keyboard="true" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
                '<div class="modal-dialog modal-m">' +
                '<div class="modal-content">' +
                '<div class="modal-header" style="padding:15px;">' +
                '<h4 class="modal-title"><?php echo custom_lang('sima_motivo_form', "Motivo de la Anulación"); ?></h4>' +
                '</div>' +
                '<div class="modal-body">' +
                '<form id="motivo-form" action="<?php echo site_url('ventas/anular'); ?>" method="POST" >' +
                '<input type="hidden" value="" name="venta_id" id="venta_id"/>' +
                '<div class="row-form">' +
                '<div class="span2"><?php echo custom_lang('sima_motivo', "Motivo"); ?>:</div>' +
                '<div class="span3"><textarea name="motivo" id="nombre_comercial" class="validate[required]"></textarea></div>' +
                '</div>' +
                '<div align="center"> ' +
                '<input type="button" value="Cancelar" data-dismiss="modal" id="cancelar" class="btn btn-default"/> ' +
                '<input type="submit" value="Continuar"  class="btn btn-success"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  ' +
                '</div><br>' +
                '</form>' +
                '</div>' +
                '</div></div></div>');
            return {
                show: function(id) {
                    //$dialog.find("#venta_id_ven").val(id);
                    $dialog.find("#venta_id").val(id);

                    $.ajax({
                        async: false, //mostrar variables fuera de el function 
                        url: "<?php echo site_url("clientes/get_ajax_clientes_correo"); ?>",
                        type: "post",
                        dataType: "json",
                        data: {
                            idventa: id
                        },
                        success: function(data2) {
                            $dialog.find("#correo_cliente").html(data2);
                        }
                    });


                    $dialog.modal();
                },
                hide: function() {
                    $dialog.hide();
                }
            }
        })(jQuery);

        $('body').on('click', '.anular', function(e) {
            e.preventDefault();
            anularDialog.show($(this).attr('id'));
        });

        app.loadAccountStatus();
        app.loadInvoices();
        app.getCreditNotes();
    })

    function closeModal(modal) {
        $("#" + modal).css('visibility', 'hidden');
    }


    var app = new Vue({
        el: '#app',
        data: {
            symbol: "<?= $datacurrency->symbol; ?>",
            thousands_sep: "<?= $datacurrency->thousands_sep; ?>",
            decimals_sep: "<?= $datacurrency->decimals_sep; ?>",
            decimals: "<?= $datacurrency->decimals; ?>",
            customer_id: '<?= $data["customer_id"] ?>',
            name: '',
            email: '',
            telephone: '',
            address: '',
            image_client: '',
            checked_invoices: [],
            totalPayment: 0,
            identification: '',
            last_bill: '',
            last_payment: '',
            credits_invoices: '',
            pendient_invoices: '',
            balance_to_pay: 0,
            invoices: [],
            paymentDate: "<?= date('Y-m-d') ?>",
            search: '',
            searchCreditNotes: '',
            dialog: false,
            dialogPayment: false,
            dialogPaymentResponse: false,
            dialogPaymentInvoice: false,
            paymentMethod: 'efectivo',
            paymentMethods: <?= json_encode($data["payment_methods"]) ?>,
            creditNotes: [],
            box: '<?= $this->session->userdata('caja') ?>',
            totalPaid: 0,
            paymentSuccess: [],
            paymentError: [],
            detailInvoice: [],
            payDate: "<?= date('Y-m-d') ?>",
            payValue: 0,
            payRetention: 0,
            payTotal: 0,
            payInvoice: 0,
            pagination: {
                page: 1,
                itemsPerPage: 2,
                sortBy: ['invoice']
            },
            headers: [{
                    text: 'Factura',
                    align: 'left',
                    value: 'invoice'
                },
                {
                    text: 'Cliente',
                    value: 'client'
                },
                {
                    text: 'Total venta',
                    value: 'totalSale'
                },
                {
                    text: 'Retención',
                    value: 'retention'
                },
                {
                    text: 'Total pendiente',
                    value: 'totalPending'
                },
                {
                    text: 'Fecha factura',
                    value: 'date'
                },
                {
                    text: 'Fecha vencimiento',
                    value: 'expiration_date'
                }
            ],
            headers_abonos: [{
                    text: 'Factura',
                    align: 'left',
                    value: 'invoice'
                },
                {
                    text: 'Fecha',
                    value: 'Fecha'
                },
                {
                    text: 'Cantidad',
                    value: 'Cantidad'
                },
                {
                    text: 'Tipo',
                    value: 'Tipo'
                },
                {
                    text: 'Retención',
                    value: 'Tipo'
                }
            ],
            paginationCreditNotes: {
                sortBy: 'consecutive'
            },
            headersCreditNotes: [{
                    text: 'Consecutivo',
                    align: 'left',
                    value: 'consecutive'
                },
                {
                    text: 'Total',
                    value: 'total'
                },
                {
                    text: 'Factura',
                    value: 'invoice'
                },
                {
                    text: 'Fecha',
                    value: 'date'
                },
                {
                    text: 'Estado',
                    value: 'state'
                }
            ],
            process: false,
            todos_abonos: [],
            modal_data : {},
            valid: {
                payValue: false
            },
            dialogValidateBox: false,
            valueOpenBox: '',
            datacurrency: '<?php echo json_encode($datacurrency); ?>',
            valid_max_value: false
        },
        methods: {
            toMoney(x) {
                const curr = this.datacurrency
                let thousands_sep = ','
                let  decimals_sep = '.'
                let decimals = 2
                let value_decimal = ''
                if(curr) {
                    thousands_sep = curr.thousands_sep ? curr.thousands_sep: ','
                    decimals_sep = curr.decimals_sep ? curr.decimals_sep: ','
                    decimals = curr.decimals ? parseFloat(curr.decimals): 2
                }
                if(this.datacurrency.decimals != '0' && String(x).length > parseFloat(curr.decimals)){
                    value_decimal =  decimals_sep + x.toString().substr(-decimals);
                    x = x.toString().slice(0, -decimals);
                }
                let final_decimal = ''
                for (let i = 0; i < decimals; i++) { final_decimal += '0'}
                const mask = `#${thousands_sep}##0${decimals_sep}${final_decimal}`
                mask
                
                return parseInt(x).toString().replace(/\B(?=(\d{3})+(?!\d))/g, thousands_sep) + value_decimal;
            },
            formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ","){
                try {
                    decimalCount = Math.abs(decimalCount);
                    decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

                    const negativeSign = amount < 0 ? "-" : "";

                    let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
                    let j = (i.length > 3) ? i.length % 3 : 0;

                    return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
                } catch (e) {
                   //
                }
            },
            formatNumber(number) {
                if (number.indexOf(this.symbol) >= 0) {
                    number = number.replace(this.symbol, '');
                }

                let number_aux = number;
                if (number.search(this.thousands_sep) != -1) {
                    number_aux = number.split(this.thousands_sep);
                    let stripped = number_aux.join('');
                    number_aux = stripped.replace(this.decimals_sep, '.');
                }
                return parseFloat(number_aux);
            },
            numberFormat(number) {
                number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
                var n = !isFinite(+number) ? 0 : +number,
                    prec = !isFinite(+this.decimals) ? 0 : Math.abs(this.decimals),
                    sep = (typeof this.thousands_sep === 'undefined') ? ',' : this.thousands_sep,
                    dec = (typeof this.decimals_sep === 'undefined') ? '.' : this.decimals_sep,
                    s = '',
                    toFixedFix = function(n, prec) {
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
                return this.symbol + s.join(dec);
            },
            toggleAll() {
                if (this.checked_invoices.length) this.checked_invoices = []
                else this.checked_invoices = this.invoices.slice()
            },
            changeSort(column) {
                if (this.pagination.sortBy === column) {
                    this.pagination.descending = !this.pagination.descending
                } else {
                    this.pagination.sortBy = column
                    this.pagination.descending = false
                }
            },
            changeSortCreditNotes(column) {
                if (this.paginationCreditNotes.sortBy === column) {
                    this.paginationCreditNotes.descending = !this.paginationCreditNotes.descending
                } else {
                    this.paginationCreditNotes.sortBy = column
                    this.paginationCreditNotes.descending = false
                }
            },
            openModal(modal) {
                $("#" + modal).css('visibility', 'visible');
            },
            loadAccountStatus() {
                this.$http.get("<?php echo site_url('credits/loadAccountStatus'); ?>" + "/" + this.customer_id).then(function(response) {
                    let resp = JSON.parse(response.body);
                    this.identification = resp.identification,
                        this.name = resp.name,
                        this.telephone = resp.telephone,
                        this.email = resp.email,
                        this.address = resp.address,
                        this.last_bill = resp.last_bill;
                    this.last_payment = resp.last_payment;
                    this.credits_invoices = resp.credits_invoices;
                }, function() {
                    alert('Error!');
                });
            },
            loadInvoices() {
                this.balance_to_pay = 0;
                let me = this;
                this.todos_abonos = [];
                this.$http.get("<?php echo site_url('credits/getInvoicesByClient'); ?>" + "/" + this.customer_id).then(function(response) {
                    this.invoices = JSON.parse(response.body);
                    this.pendient_invoices = this.invoices.length;
                    $.each(this.invoices, function(index, element) {
                        me.$http.get("<?php echo site_url('pagos/ver_pago_ajax'); ?>" + "/" + element.id).then(function(response) {
                            let data = JSON.parse(response.body);
                            $.each(data.data, function(index, item) {
                                item.invoice = data.numero;
                                me.todos_abonos.push(item)
                            });

                        });

                        app.balance_to_pay += app.formatNumber(element.totalPending);
                    })
                    this.balance_to_pay = this.numberFormat(this.balance_to_pay);
                }, function() {
                    alert('Error!');
                });
            },
            getCreditNotes() {
                this.$http.get("<?php echo site_url('credits/getCreditNotes'); ?>" + "/" + this.customer_id).then(function(response) {
                    this.creditNotes = JSON.parse(response.body);
                }, function() {
                    alert('Error!');
                });
            },
            payBills() {
                if (this.totalPaid == this.totalPayment) {
                    if (this.box == '') {
                        alert("No posee caja abierta. por favor verifique y vuelva a intentarlo");
                    } else {
                        this.process = true;
                        for (let i = 0; i < this.checked_invoices.length; i++) {
                            this.checked_invoices[i].totalSale = this.formatNumber(this.checked_invoices[i].totalSale);
                            this.checked_invoices[i].retention = this.formatNumber(this.checked_invoices[i].retention);
                            this.checked_invoices[i].totalPending = this.formatNumber(this.checked_invoices[i].totalPending);
                        }
                        this.$http.post("<?php echo site_url('credits/payBills'); ?>", {
                            box: this.box,
                            customer_id: this.customer_id,
                            paymentDate: this.paymentDate,
                            invoices: this.checked_invoices,
                            paymentMethod: this.paymentMethod,
                            totalPaid: this.totalPaid,
                            totalPayment: this.totalPayment
                        }).then(function(data) {
                            for (let i = 0; i < this.checked_invoices.length; i++) {
                                this.checked_invoices[i].totalSale = this.numberFormat(this.checked_invoices[i].totalSale);
                                this.checked_invoices[i].retention = this.numberFormat(this.checked_invoices[i].retention);
                                this.checked_invoices[i].totalPending = this.numberFormat(this.checked_invoices[i].totalPending);
                            }

                            let response = JSON.parse(data.body);
                            setTimeout(() => {
                                this.process = false;
                                if (response.response == null) {
                                    alert("Error al intentar totalizar las facturas");
                                } else {
                                    this.dialog = false;
                                    this.dialogPayment = false;
                                    this.loadAccountStatus();
                                    this.loadInvoices();
                                    this.paymentSuccess = response.response.saved;
                                    this.paymentError = response.response.without_saving;
                                    this.dialogPaymentResponse = true;
                                    for (let i = 0; i < this.checked_invoices.length; i++) {
                                        setTimeout(() => {
                                            var win = window.open("<?= site_url() ?>/credito/imprimir/" + this.checked_invoices[i].id + "/copia");
                                        }, 500);
                                    }
                                }
                            }, 500);
                            win.focus();
                        })
                    }
                } else {
                    alert("La cantidad a pagar no es igual a la cantidad pendiente");
                }
            },
            showPayInvoice(invoice, objInvoice) {
                this.invoice_prueba = invoice;
                let me = this;
                let api_auth = JSON.parse(localStorage.getItem('api_auth'));
                    this.$http.get("<?php echo site_url("credito/verify_state_box") ?>", {
                    headers: {
                        Authorization : `Bearer ${token_php}`,
                    }
                })
                .then(function(response) {
                    let data = response.body;
                    if(data.estado_caja){
                        this.$http.get("<?php echo site_url('credits/getDetailInvoice'); ?>" + "/" + invoice).then(function(response) {
                            this.detailInvoice = JSON.parse(response.body);
                            this.dialogPaymentInvoice = true;
                            //objInvoice.totalPending = me.toMoney(objInvoice.totalPending.split('.00').join(''))
                            objInvoice.totalPending = objInvoice.totalPending;
                            this.modal_data = objInvoice;
                        }, function() {
                            alert('Error!');

                        });
                    }else{
                        this.payInvoice = invoice;
                        this.dialogValidateBox = true;
                    }
                }, function() {
                    alert('Error!');

                });

            },
            openBox(){
                var dateObj = new Date();
                var month = dateObj.getUTCMonth() + 1; //months from 1-12
                var day = dateObj.getUTCDate();
                var year = dateObj.getUTCFullYear();
                let month_2;
                if (month.toString().length == 1) {
                    month_2 = "0" + month.toString();
                } else {
                    month_2 = month.toString();
                }
                let valueOpenBox = this.valueOpenBox;
                if(datacurrency.decimals_sep == ',' && datacurrency.decimals != '0'){
                    if(valueOpenBox.includes(","))
                    {
                        if(valueOpenBox.includes("."))
                        {
                            valueOpenBox = valueOpenBox.split('.').join('');
                        }
                        
                        valueOpenBox = valueOpenBox.split(',').join('.');
                    }
                }
                if(datacurrency.decimals_sep == '.' && datacurrency.decimals != '0'){
                    if(valueOpenBox.includes("."))
                    {
                        if(valueOpenBox.includes(","))
                        {
                            valueOpenBox = valueOpenBox.split(',').join('');
                        }
                    }
                }
                let api_auth = JSON.parse(localStorage.getItem('api_auth'));

                const formData = new FormData();
                
                formData.append('fecha', year.toString() + "-" + month_2 + "-" + day.toString());
                formData.append('almacen', "<?php echo $this->dashboardModel->getAlmacenActual()?>");
                formData.append('foma_pago', ['efectivo']);
                formData.append('valor', [valueOpenBox]);
                formData.append('total_formapago', valueOpenBox);
                formData.append('back', '');
                formData.append('url', "http://localhost:8080/pos/index.php/frontend/index");



                this.$http.post("<?php echo site_url("caja/apertura/credito") ?>", formData, {
                    headers: {
                        'Authorization' : `Bearer ${token_php}`,
                            'Content-Type' : 'application/json'
                    }
                }).then(function(response) {
                    let data = response.body;
                    this.box = "/credito";
                
                    this.dialogValidateBox = false;
                    this.$http.get("<?php echo site_url('credits/getDetailInvoice'); ?>" + "/" + this.invoice_prueba).then(function(response) {
                        this.detailInvoice = JSON.parse(response.body);
                        //this.dialogPaymentInvoice = true;
                        this.modal_data = objInvoice;
                    }, function() {
                        alert('Error!');

                    });

                }, function() {
                    alert('Error!');
                });
            },
            addPayInvoice() {
               if(this.payValue){
                   this.valid.payValue = false;

               }else{
                    this.valid.payValue = true;
                    return;
               }
                if (this.box == '') {
                    alert("No posee caja abierta. por favor verifique y vuelva a intentarlo");
                } else {
                    this.process = true;
                    let payValue = String(this.payValue);
                    let payRetention = String(this.payRetention);
                    if(datacurrency.decimals_sep == ',' && datacurrency.decimals != '0'){
                        if(payValue.includes(","))
                        {
                            if(payValue.includes("."))
                            {
                                payValue = payValue.split('.').join('');
                            }
                            payValue = payValue.split(',').join('.');
                        }
                        if(payRetention.includes(","))
                        {
                            if(payRetention.includes("."))
                            {
                                payRetention = payRetention.split('.').join('');
                            }
                            payRetention = payRetention.split(',').join('.');
                        }
                    }
                    if(datacurrency.decimals_sep == '.' && datacurrency.decimals != '0'){
                        if(payValue.includes(","))
                        {
                            payValue = payValue.split(',').join('');
                        }
                        if(payRetention.includes(","))
                        {
                            payRetention = payRetention.split(',').join('');
                        }
                    }
                    if(datacurrency.decimals == '0'){
                        if(payValue.includes(","))
                        {
                            payValue = payValue.split(",").join("");
                        }
                        if(payValue.includes("."))
                        {
                            payValue = payValue.split(".").join("");
                        }

                        if(payRetention.includes(","))
                        {
                            payRetention = payRetention.split(",").join("");
                        }
                        if(payRetention.includes("."))
                        {
                            payRetention = payRetention.split(".").join("");
                        }
                    }
                    
                    this.$http.post("<?php echo site_url('credits/payInvoice'); ?>", {
                        payDate: this.payDate,
                        paymentMethod: this.paymentMethod,
                        payValue: payValue,
                        payRetention: payRetention,
                        payInvoice: this.modal_data.id,
                        payTotal: this.payTotal
                    }).then(function(data) {
                        let response = JSON.parse(data.body);
                        setTimeout(() => {
                            this.process = false;
                            if (response) {
                                Swal.fire(
                                    'Creado',
                                    'Abono realizado con éxito.',
                                    'success'
                                )
                                this.dialog = false;
                                this.dialogPayment = false;
                                this.dialogPaymentInvoice = false;
                                this.payValue = 0;
                                this.payRetention = 0;
                                this.loadAccountStatus();
                                this.loadInvoices();
                                setTimeout(() => {
                                    var win = window.open("<?= site_url() ?>/credito/imprimir/" + this.modal_data.id + "/copia");
                                    win.focus();
                                }, 1000);
                            } else {
                                alert("Error al intentar realizar el abono");
                            }
                        }, 500);
                    })
                }
            },
            deletePayment(payment) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¿Desea eliminar el abono?",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#4cae4c',
                    cancelButtonColor: '#eee',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: '<span style="color: black;font-size: 12px;">Cancelar</span>'
                }).then((result) => {
                    if (result.value) {
                        this.$http.get("<?php echo site_url('pagos/eliminar_ajax'); ?>" + "/" + payment.id_pago + "?factura=" + payment.id_factura).then(function(response) {
                            this.loadAccountStatus();
                            this.loadInvoices();
                        });
                    }
                });
            },
            validMaxValue(payvalue = 0, payRetention = 0)
            {
                let maxValue  = Number(this.modal_data.totalPending.replace(/[^0-9]/g, ''));
                if(payvalue + payRetention > maxValue){
                    this.valid_max_value = true;
                }else if(payvalue + payRetention <= maxValue){
                    this.valid_max_value = false;
                }
            },
            number_format(value){
                return number_format(value);
            }

        },
        computed: {
            totalPaymentSelected: function() {
                this.totalPayment = 0;
                $.each(this.checked_invoices, function(index, element) {
                    app.totalPayment += app.formatNumber(element.totalPending);
                })
                this.totalPaid = this.totalPayment;
                return this.totalPayment;
            },
            totalPayInvoice: function() {
                this.payTotal = 0;
                let payValue;
                let payRetention;
                if( typeof this.payValue == 'number'){
                    payValue  = this.payValue;
                }else{
                    payValue = this.payValue.split(',').join('');
                    payValue = payValue.split('.').join('');

                }

                if(typeof this.payRetention == 'number'){
                    payRetention = this.payRetention
                }else{
                    payRetention = this.payRetention.split(',').join('');
                    payRetention = payRetention.split('.').join('')
                }
                
                this.payTotal = parseFloat(payValue) + parseFloat(payRetention);
                return toMoney(this.payTotal);
            }
        }, 
        mounted() {
            $('#payDate').datepicker();  
            
        },
        watch: {
            payRetention: function(val) {
                let forFormat = val.split(',').join('');
                let only_number = forFormat.replace(/[^0-9]/g, '');
                let number = Number(only_number);
                if(!number) { number = 0; }
                if(String(this.payValue).includes(".") || String(this.payValue).includes(","))
                {
                    this.validMaxValue(Number(this.payValue.replace(/[^0-9]/g, '')), number);
                }else{
                    this.validMaxValue(Number(this.payValue), number);
                }
                this.validMaxValue(Number(this.payValue), number);
                this.payRetention = toMoney(number);
            },
            payValue: function (val) {
                let forFormat = val.split(',').join('');
                let only_number = forFormat.replace(/[^0-9]/g, '');
                let number = Number(only_number);
                if(!number) { number = 0; }
                if(String(this.payRetention).includes(".") || String(this.payRetention).includes(","))
                {
                    this.validMaxValue(number, Number(this.payRetention.replace(/[^0-9]/g, '')));
                }else{
                    this.validMaxValue(number, Number(this.payRetention));
                }
                this.payValue = toMoney(number);
            },
            valueOpenBox: function (val) {
                let forFormat = val.split(',').join('');
                let only_number = forFormat.replace(/[^0-9]/g, '');
                let number = Number(only_number);
                if(!number) { number = 0; }
                this.valueOpenBox = toMoney(number);
            }
        }, 
        created() {
            $('#payDate').datepicker();  
        },
    });

    function formatMoney(amount, decimalCount = 2, decimal = ".", thousands = ",") {
        try {
            decimalCount = Math.abs(decimalCount);
            decimalCount = isNaN(decimalCount) ? 2 : decimalCount;

            const negativeSign = amount < 0 ? "-" : "";

            let i = parseInt(amount = Math.abs(Number(amount) || 0).toFixed(decimalCount)).toString();
            let j = (i.length > 3) ? i.length % 3 : 0;

            return negativeSign + (j ? i.substr(0, j) + thousands : '') + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thousands) + (decimalCount ? decimal + Math.abs(amount - i).toFixed(decimalCount).slice(2) : "");
        } catch (e) {
            //
        }
    };

    datacurrency = JSON.parse('<?php echo json_encode($datacurrency); ?>');
    app.datacurrency = datacurrency
    /*$.ajax({
        type: 'GET',
        url: api_url + '/data-currency',
        headers: {
            Authorization : `Bearer ${token_php}`,
        },
        success: function(data){
            datacurrency = data;
            if(response.thousands_sep == ' '){
                if(response.decimals_sep != ' '){
                    if(response.decimals_sep == ',') {
                        datacurrency.thousands_sep = '.';
                    }
                    if(response.decimals_sep == '.') {
                        datacurrency.thousands_sep = ',';
                    }
                }else{
                    response.thousands_sep = ',';
                }
            }

            if(response.decimals_sep == ' '){
                if(response.thousands_sep != ' '){
                    if(response.thousands_sep == ',') {
                        datacurrency.decimals_sep = '.';
                    }
                    if(response.thousands_sep == '.') {
                        datacurrency.decimals_sep = ',';
                    }
                }else{
                    response.decimals_sep = ',';
                }
            }
            app.datacurrency = datacurrency

        }
    });*/
</script>