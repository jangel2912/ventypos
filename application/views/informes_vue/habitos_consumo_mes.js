var app = new Vue({
    el: '#habitos_consumo_mes',
    data: {
      message: 'Hello Vue!',
      page: 1,
      pageLength: 40,
      total_ventas_1: [],
      total_ventas_2: [],
      total_ventas_3: [],
      devoluciones: 0,
      totalVentas: 0,
      datacurrency: {
        "symbol":"$",
        "decimals":"0",
        "thousands_sep":",",
        "decimals_sep":"."
      },
      status_show: false,
      status_show_img: false,
      status_show_registers: false
    },
    methods: {
        getUnique(arr, comp) {

            const unique = arr
                 .map(e => e[comp])
          
               // store the keys of the unique objects
              .map((e, i, final) => final.indexOf(e) === i && i)
          
              // eliminate the dead keys & store unique objects
              .filter(e => arr[e]).map(e => arr[e]);
          
             return unique;
        },
        getDataCurrency(){
            /*let headers = {
                headers: {
                    Authorization : `Bearer ${token_php}`
                }
            };
            this.$http.get(api_url + '/data-currency', headers)
            .then(function (response){
                datacurrency = response.body;
                this.datacurrency = response.body;
            });*/
        },
        getHabitosConsumo(){
            var me = this;
            me.status_show_img = true;
            me.status_show_registers = false;

            let body = {
                fechainicial: $("#dateinicial").val(),
                fechafinal: $("#datefinal").val(),
                almacen: ($("#almacen").val()) ? $("#almacen").val() : '0',
                page: this.page,
                pageLength: this.pageLength
            }
            this.$http.post(url_habitos, body, {emulateJSON: true}).then(function(response) {
              this.status_show_img = false;

              let data = JSON.parse(response.body);
              if(data.total_ventas_1.length < me.pageLength){
                me.status_show = true;
                me.status_show_registers = true;
              }
              if(data.total_ventas_1.length == 0){
                me.status_show_registers = true;
              }
                me.page = me.page + 1;
                me.total_ventas_1 = [...me.total_ventas_1, ...data.total_ventas_1];
                me.total_ventas_3 = [...me.total_ventas_3, ...data.total_ventas_3];
                me.devoluciones = me.devoluciones + Number(data.devoluciones);

                me.total_ventas_1 = me.getUnique(me.total_ventas_1, 'total_venta');
                me.total_ventas_2 = me.getUnique(me.total_ventas_2, 'total_venta');
                me.total_ventas_2 = me.getUnique(me.total_ventas_2, 'total_venta');
                if(data.total_ventas_1.length != 0){
                  me.total_ventas_1.forEach(element_1 => {
                    me.total_ventas_3.forEach(element_2 => {
                      if(element_1.fecha == element_2.fecha){
                        me.totalVentas = me.totalVentas + Number(element_2.total_detalleventa);
                      }
                    });
                  });
                }
            }, function(e) {
              console.log(e);
                this.status_show_img = false;
                alert('Error en el servidor, por favor comuniquese con el soporte de vendty.');
            });
            return;
        },
        getMoreSales(day){
          day.statusLoadding = true;
          if(day.page){
            day.page = day.page + 40;
          }else{
            day.page = 40;
          }
          let body = {
            sales: day.rest,
            page: day.page,
            pageLength: 40
          };

          // lock button
          $('#filtrar_2').attr("disabled", true);
          this.$http.post(url_sales_days, body, { emulateJSON: true } ). then( (response) => {
            let data = JSON.parse(response.data);
            if(data.length <= 9){
              day.statusShow = true;
            }
            data.forEach( e => {
              day.sales.push(
                {
                  "fecha_dia": e.fecha_dia,
                  "unidades": e.unidades,
                  "fecha": e.fecha_dia,
                  "nombre": e.nombre_producto,
                  "total_detalleventa": e.total_detalleventa,
                  "codigo_producto": e.codigo_producto,
                  "total_devoluciones": e.total_devoluciones,
                }
              );
            });
            day.statusLoadding = false;
            // unlock button
            $('#filtrar_2').attr("disabled", false);
          }, (e) => {
            day.statusLoadding = false;
            console.log(e);
            // unlock button
            $('#filtrar_2').attr("disabled", false);
          });
        },
        fechaespanol(fecha) {
            var fechaComplete = fecha + " 00:00:00";
            var date = new Date(fechaComplete);
            var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };

            return date.toLocaleDateString("es-ES", options)
        },
        number_format(number, decimals = this.datacurrency.decimals, decPoint = this.datacurrency.decimals_sep, thousandsSep = this.datacurrency.thousands_sep){         
            return number_format(number, decimals, decPoint, thousandsSep)
        },
        returnTotal(){
          var me = this;
          var total = 0;
          me.total_ventas_1.map((sale) => {
            total += Number(sale.total_venta) - (sale.devolucion ? Number(sale.devolucion) : 0);
          });
          //Devuelvo el total de las ventas menos el total de las devoluciones
          return this.number_format(total);
        }
    },
    mounted() {
      this.datacurrency = datacurrency;
      this.getHabitosConsumo();
    }
  })