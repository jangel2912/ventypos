Vue.component("promotion", {
    data: function() {
        return {
            data: {
                name: null,
                type: null
            }
        };
    },
    template: `
    <div>
    <promotion-name :name="data.name"></promotion-name>
    <promotion-type :type="data.type"></promotion-type>
    </div>
    `
});

Vue.component("promotion-name", {
    props: ['name'],
    template: `<div class="form-container">
                    <span class="title">Información General</span>
                    <div class="form-group">
                        <label for="name">Nombre</label>
                        <input type="text" name="name" id="name" class="form-control" v-model="name">
                    </div>
                </div>`
});

Vue.component("promotion-type", {
    props: ['type'],
    template: `<div class="form-container">
            <span class="title">Tipo de Promoción</span>
            <div class="form-group">
                <br>
                <select name="type" id="type" class="form-control" v-model="type">
                    <option value="cantidad">Porcentaje de Descuento</option>
                    <option value="progresivo">Compra X, Lleva Y</option>
                </select>
            </div>
        </div>`
});

var app = new Vue({
    el: '#app'
});