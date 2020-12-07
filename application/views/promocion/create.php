<div class="page-header">
    <div class="icon">
        <img alt="Promociones" class="iconimg" src="<?php echo $this->session->userdata('new_imagenes')['titulo_promociones']['original'] ?>">
    </div>
    <h1 class="sub-title"><?php echo custom_lang("Promociones", "Promociones");?></h1>
</div>

<div class="block title">
    <div class="head">
        <h2>Crear una Promción</h2>
    </div>
</div>

<div id="app" class="row-fluid">
    <div class="col-md-8">
        <div class="form-container">
            <span class="title">Información General</span>
            <div class="form-group">
                <label for="name">Nombre</label>
                <input type="text" name="name" id="name" class="form-control" v-model="data.name">
            </div>
        </div>
        <div class="form-container">
            <span class="title">Tipo de Promoción</span>
            <div class="form-group">
                <br>
                <select name="type" id="type" class="form-control" v-model="data.type">
                    <option value="cantidad">Porcentaje de Descuento (ejemplo: por 4 productos tiene un 10% de descuento)</option>
                    <option value="progresivo">Compra X, Lleva Y (ejemplo: Compra 2 y te obsequiamos 1)</option>
                </select>
            </div>
        </div>
        <div class="form-container">
            <span class="title">Almacenes</span>
            <div class="row">
                <div id="all-warehouses" class="col-md-12">
                    <div v-for="warehouse in warehouses" class="warehouses" @click="warehousesHandler(warehouse)" :class="{ active: warehousesStyles(warehouse) }" v-text="warehouse.name">Cargando...</div>
                </div>
            </div>
        </div>
        <div class="form-container">
            <span class="title">Periodo de Duración</span>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="start_date">Fecha de Inicio</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" v-model="data.start_date">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="start_date">Hora de Inicio</label>
                        <input type="time" name="initial_time" id="initial_time" class="form-control" v-model="data.initial_time">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="final_date">Fecha Final</label>
                        <input type="date" name="final_date" id="final_date" class="form-control" v-model="data.final_date">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="final_time">Hora Final</label>
                        <input type="time" name="final_time" id="final_time" class="form-control" v-model="data.final_time">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="week">
                        <div class="day" @click="daysHandler(1)" :class="{ active: daysStyles(1) }">Lunes</div>
                        <div class="day" @click="daysHandler(2)" :class="{ active: daysStyles(2) }">Martes</div>
                        <div class="day" @click="daysHandler(3)" :class="{ active: daysStyles(3) }">Miercoles</div>
                        <div class="day" @click="daysHandler(4)" :class="{ active: daysStyles(4) }">Jueves</div>
                        <div class="day" @click="daysHandler(5)" :class="{ active: daysStyles(5) }">Viernes</div>
                        <div class="day" @click="daysHandler(6)" :class="{ active: daysStyles(6) }">Sabado</div>
                        <div class="day" @click="daysHandler(7)" :class="{ active: daysStyles(7) }">Domingo</div>
                    </div>
                </div>
            </div>
        </div>
        <template v-if="data.type=='progresivo'">
            <div id="gift">
                <div class="form-container">
                    <span class="title">El cliente Compra</span>
                    <div class="row">
                        <div class="col-sm-12">
                            <button class="btn btn-success btn-sm" @click="toggleModal(true, 'buy')">Buscar los productos</button>
                            <h4>Productos Selecionados</h4>
                            <div class="selected_products products">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="product in data.products">
                                        <td v-text="product.id">#</td>
                                        <td v-text="product.name">Cargando...</td>
                                        <td  v-text="product.category.name">Cargando...</td>
                                        <td><button @click="productsHandler(product)" class="btn btn-danger btn-sm">Eliminar</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-container">
                    <span class="title">El cliente Recibe</span>
                    <div class="row">
                        <div class="col-sm-12">
                            <button class="btn btn-success btn-sm" @click="toggleModal(true, 'receive')">Buscar los productos</button>
                            <h4>Productos Selecionados</h4>
                            <div id="selected_products_gift" class="products">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="product in data.gifts.products">
                                        <td v-text="product.id">#</td>
                                        <td v-text="product.name">Cargando...</td>
                                        <td  v-text="product.category.name">Cargando...</td>
                                        <td><button @click="productsHandler(product)" class="btn btn-danger btn-sm">Eliminar</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-container">
                    <span class="title">Cantidad</span>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">El Cliente Compra</label>
                                <input type="number" name="customer_buy" id="customer_buy" class="form-control" v-model="data.gifts.buy">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">El Cliente Recibe</label>
                                <input type="number" name="customer_receive" id="customer_receive" class="form-control" v-model="data.gifts.receive">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <template v-else-if="data.type=='cantidad'">
            <div id="percentage">
                <div class="form-container">
                    <div class="row">
                        <div class="col-sm-12">
                            <button class="btn btn-success btn-sm" @click="toggleModal(true, 'buy')">Buscar los productos</button>
                            <h4>Productos Selecionados</h4>
                            <div class="selected_products products">
                                <table class="table table-hover">
                                    <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nombre</th>
                                        <th>Categoría</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="product in data.products">
                                        <td v-text="product.id">#</td>
                                        <td v-text="product.name">Cargando...</td>
                                        <td  v-text="product.category.name">Cargando...</td>
                                        <td><button @click="productsHandler(product)" class="btn btn-danger btn-sm">Eliminar</button></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-container">
                    <span class="title">Reglas de Porcentaje</span>
                    <br>
                    <div id="rules">
                        <div class="row" v-for="rule in data.rules">
                            <div class="col-sm-5">
                                <input type="text" name="qty" v-model="rule.qty" readonly class="form-control">
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="percent" v-model="rule.percent" readonly class="form-control">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" class="btn btn-danger btn-block" @click="removeRule(rule)">Eliminar</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="row">
                            <div class="col-sm-5">
                                <input type="text" name="qty" id="qty" v-model="qty" class="form-control" placeholder="Cantidad">
                            </div>
                            <div class="col-sm-5">
                                <input type="text" name="percent" id="percent" v-model="percent" class="form-control" placeholder="Porcentaje">
                            </div>
                            <div class="col-sm-2">
                                <button type="button" id="add-rule" @click="newRule" class="btn btn-success btn-block">Agregar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
        <div class="form-container">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="active">
                            <input type="checkbox" name="active" id="active" v-model="data.active">
                            Activa
                        </label>
                    </div>
                </div>
                <div class="col-sm-6">
                    <button id="proccess" @click="process" class="btn btn-success btn-block">Crear</button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div id="summary-content" class="form-container hidden-xs hidden-sm">
            <span class="title">Resumen</span>
            <ul id="summary">
                <li id="name-summary"v-text="data.name"></li>
                <li id="type-summary" v-text="data.type"></li>
                <li id="warehouses-summary">
                    Almacenes
                    <ul>
                        <li v-for="warehouse in data.warehouses" text="warehouse.name"></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div id="composite-products" :class="{ active: modal }">
        <div class="composite-products-modal col-xs-12 col-sm-10 col-sm-offset-1 col-md-6 col-md-offset-3">
            <div class="composite-products-close" @click="toggleModal(false)">
                x
            </div>
            <div class="composite-products-title">
                <span class="title">Todos tus Productos</span>
            </div>
            <div id="composite-content-additions" class="composite-contents">
                <div class="composite-products-content additions">
                    <table class="table table-hover">
                        <tr>
                            <td><span class="glyphicon glyphicon-unchecked" @click="allProductsHandler()"></span></td>
                            <td>#</td>
                            <td><input type="text" v-model="search.product" @keyup="searchByProduct()" placeholder="Buscar por nombre"></td>
                            <td><input type="text" v-model="search.category" @keyup="searchByCategory()" placeholder="Buscar por categoría"></td>
                        </tr>
                        <tr v-for="product in products" @click="productsHandler(product)">
                            <td><span class="glyphicon" :class="{ 'glyphicon-check': productsModal.includes(product), 'glyphicon-unchecked': !productsModal.includes(product) }"></span></td>
                            <td v-text="product.id">#</td>
                            <td v-text="product.name">Cargando...</td>
                            <td v-text="product.category.name">Cargando...</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="social">
    <ul>
        <li><a href="#myModalvideovimeo" data-toggle="modal" class="glyphicon glyphicon-play-circle"></a></li>
    </ul>
</div>
<!-- vimeo-->
<div id="myModalvideovimeo" class="modal fade">
    <div style="padding:56.25% 0 0 0;position:relative;">
        <iframe id="cartoonVideovimeo" src="https://player.vimeo.com/video/266924674?loop=1&color=ffffff&title=0&byline=0&portrait=0" style="position:absolute;top:0;left:0;width:100%;height:100%;" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
    </div>
</div>

<link rel="stylesheet" href="<?php echo base_url() ?>/public/promotions/promotions.css">

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.6/dist/vue.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script>
    var app = new Vue({
        el: '#app',
        data: {
            data: {
                name: null,
                type: null,
                warehouses: [],
                start_date: null,
                initial_time: null,
                final_date: null,
                final_time: null,
                days: [],
                rules: [],
                products: [],
                gifts: {
                    buy: null,
                    receive: null,
                    products: []
                },
                active: false
            },
            search: {
                product: null,
                category: null
            },
            warehouses: [],
            products: [],
            productsTemp: [],
            productsModal: [],
            qty: null,
            percent: null,
            loadingProducts: true,
            modal: false,
            destination: null
        },
        methods: {
            toggleModal(status, destination) {
                if (destination === 'undefined') {
                    this.modal = false;
                }
                
                this.modal = status;
                this.destination = destination;

                if (this.destination === 'buy') {
                    this.productsModal = this.data.products;
                } else {
                    this.productsModal = this.data.gifts.products;
                }
            },
            warehousesHandler(warehouse) {
                let total = this.data.warehouses.length;
                let flag = false;

                if (total > 0) {
                    for (let i = 0; i < total; i++) {
                        if (this.data.warehouses[i].id == warehouse.id) {
                            this.data.warehouses.splice(i, 1);
                            flag = false;
                        } else  {
                            flag = true;
                        }
                    }
                    if (flag) {
                        this.data.warehouses.push(warehouse);
                    }
                } else {
                    this.data.warehouses.push(warehouse);
                }
            },
            daysHandler(day) {
                if (!this.data.days.includes(day)) {
                    this.data.days.push(day);
                } else {
                    let index = this.data.days.indexOf(day);
                    if (index > -1) {
                        this.data.days.splice(index, 1);
                    }
                }
            },
            allProductsHandler() {
                let selected = this.data.products.length;
                let all = this.productsTemp.length;

                if (selected < all) {
                    this.data.products = this.productsTemp;
                } else if (selected >= all) {
                    this.data.products = [];
                }
            },
            productsHandler(product) {
                this.productsModal = [];
                if (this.data.type === "cantidad") {
                    this.productsHandlerQuantity(product);
                } else if (this.data.type === "progresivo") {
                    if (this.destination === "buy")  {
                        this.productsHandlerQuantity(product);
                    } else if (this.destination === "receive") {
                        this.productsHandlerProgresive(product);
                    }
                }
            },
            productsHandlerQuantity(product) {
                if (!this.data.products.includes(product)) {
                    this.data.products.push(product);
                    this.productsModal = this.data.products;
                } else {
                    let index = this.data.products.indexOf(product);
                    if (index > -1) {
                        this.data.products.splice(index, 1);
                        this.productsModal = this.data.products;
                    }
                }
            },
            productsHandlerProgresive(product) {
                if (!this.data.gifts.products.includes(product)) {
                    this.data.gifts.products.push(product);
                    this.productsModal = this.data.gifts.products;
                } else {
                    let index = this.data.gifts.products.indexOf(product);
                    if (index > -1) {
                        this.data.gifts.products.splice(index, 1);
                        this.productsModal = this.data.gifts.products;
                    }
                }
            },
            daysStyles(day) {
                return this.data.days.includes(day);
            },
            warehousesStyles(warehouse) {
                let found = false;
                let total = this.data.warehouses.length;
                for(var i = 0; i < total; i++) {
                    if (this.data.warehouses[i].id === warehouse.id) {
                        found = true;
                        break;
                    }
                }

                return found;
            },
            productsStyles(product) {
                return this.data.products.includes(product);
            },
            newRule() {
                this.data.rules.push({
                    qty: this.qty,
                    percent: this.percent
                });

                this.qty = null;
                this.percent = null;
            },
            removeRule(rule) {
                if (!this.data.rules.includes(rule)) {
                    this.data.rules.push(rule);
                } else {
                    let index = this.data.rules.indexOf(rule);
                    if (index > -1) {
                        this.data.rules.splice(index, 1);
                    }
                }
            },
            searchByProduct() {
                this.products = [];
                this.productsTemp.find((item) => {
                    var match = item.name.match(new RegExp(this.search.product, 'i'));
                    if (match !== null) {
                        this.products.push(item);
                    }
                });
            },
            searchByCategory() {
                this.products = [];
                this.productsTemp.find((item) => {
                    var match = item.category.name.match(new RegExp(this.search.category, 'i'));
                    if (match !== null) {
                        this.products.push(item);
                    }
                });
            },
            process() {
                swal({
                    title: "Espera un momento por favor.",
                    text: "Estamos generando tu promoción...",
                    type: "info",
                    showCancelButton: false,
                    showConfirmButton: false,
                });

                axios.post('<?php echo site_url('promocion/store'); ?>', this.data).then(res => {
                    this.data = res.data.data;
                    swal('¡Excelente!', 'La promoción ' + this.data.name + ' se ha creado con éxito.', 'success');
                }).catch(e => {
                    console.error(e);
                    swal({
                        title: "¡Oops!",
                        text: "Ha ocurrido un error",
                        type: "danger",
                        showCancelButton: true,
                        CancelButtonClass: "btn-danger",
                        CancelButtonText: "Aceptar",
                    });
                });
            },
            loadWarehouses() {
                axios.get('<?php echo site_url('promocion/warehouses'); ?>').then(res => {
                    this.warehouses = res.data;
                });
            },
            loadProducts() {
                axios.get('<?php echo site_url('promocion/products'); ?>').then(res => {
                    this.products = res.data;
                    this.productsTemp = this.products;
                    this.loadingProducts = false;
                });
            }
        },
        beforeMount() {
            this.loadWarehouses();
            this.loadProducts();
        }
    });

    $(document).ready(function () {
        var width = $('#summary-content').width();

        $(window).scroll(function (event) {
            $('#summary-content').css({
                'position': 'fixed',
                'width': width + 'px'
            });
        });
    });
</script>
