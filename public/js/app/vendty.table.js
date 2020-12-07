'use strict';
var VendtyTable = function () {

    // =========================================================================
    // SETTINGS APP
    // =========================================================================
    var globalPluginsPath = VendtyApp.handleBaseURL()+'/assets/global/plugins/bower_components';

    return {

        // =========================================================================
        // CONSTRUCTOR APP
        // =========================================================================
        init: function () {
            VendtyTable.datatable();
        },

        // =========================================================================
        // DATATABLE
        // =========================================================================
        datatable: function () {
            var responsiveHelperAjax = undefined;
            var responsiveHelperDom = undefined;
            var breakpointDefinition = {
                tablet: 1024,
                phone : 480
            };

           
            

            // Repeater
            var columns = [
                
                {
                    label: 'Nombre',
                    property: 'name',
                    sortable: true
                },
                {
                    label: 'Codigo',
                    property: 'codigo',
                    sortable: true
                },
                {
                    label: 'Precio de compra',
                    property: 'precio_compra',
                    sortable: true
                },
                {
                    label: 'Precio de venta',
                    property: 'precio_venta',
                    sortable: true
                },
                {
                    label: 'Impuesto',
                    property: 'impuesto',
                    sortable: true
                },
                {
                   label:'Acccion',
                   property: 'action',     
                },
               /*{
                    label:'Duplicar',
                    property: 'clonado',     
                 }*/
            ];
            
            var delays = ['300', '600', '900', '1200'];
            var products = [];
            /*var products = [
                {
                    "codeProduct": "#101",
                    "name": "Canon EOS Rebel",
                    "available": "5",
                    "price": "US $349.95",
                    "itemCondition": "Manufacturer",
                    "sold": "5",
                    "review": "253 people",
                    "ThumbnailAltText": "Canon EOS Rebel",
                    "ThumbnailImage": "/blankon-fullpack-admin-theme/img/media/shop/1.jpg",
                    "type": "electronics, camera"
                },
                {
                    "codeProduct": "#102",
                    "name": "Samsung Galaxy S III",
                    "available": "25",
                    "price": "US $197.42",
                    "itemCondition": "New other",
                    "sold": "23",
                    "review": "563 people",
                    "ThumbnailAltText": "Samsung Galaxy S III",
                    "ThumbnailImage": "/blankon-fullpack-admin-theme/img/media/shop/2.jpg",
                    "type": "electronics, mobile, gadget"
                },
                {
                    "codeProduct": "#103",
                    "name": "Samsung 32' LED",
                    "available": "231",
                    "price": "US $199.99",
                    "itemCondition": "New",
                    "sold": "67",
                    "review": "342 people",
                    "ThumbnailAltText": "Samsung 32' LED",
                    "ThumbnailImage": "/blankon-fullpack-admin-theme/img/media/shop/3.jpg",
                    "type": "electronics, tv"
                },
                {
                    "codeProduct": "#104",
                    "name": "IOTA - Love Come Wicked",
                    "available": "200",
                    "price": "US $19.99",
                    "itemCondition": "Used",
                    "sold": "45",
                    "review": "333 people",
                    "ThumbnailAltText": "IOTA - Love Come Wicked",
                    "ThumbnailImage": "/blankon-fullpack-admin-theme/img/media/shop/4.jpg",
                    "type": "music"
                },
                {
                    "codeProduct": "#105",
                    "name": "Jimmy Van Eaton",
                    "available": "567",
                    "price": "US $11.50",
                    "itemCondition": "Used",
                    "sold": "67",
                    "review": "102 people",
                    "ThumbnailAltText": "Jimmy Van Eaton",
                    "ThumbnailImage": "/blankon-fullpack-admin-theme/img/media/shop/5.jpg",
                    "type": "music"
                },
                {
                    "codeProduct": "#106",
                    "name": "Sexy Fashion Women's",
                    "available": "458",
                    "price": "US $6.39",
                    "itemCondition": "New with tags",
                    "sold": "234",
                    "review": "642 people",
                    "ThumbnailAltText": "Sexy Fashion Women's",
                    "ThumbnailImage": "/blankon-fullpack-admin-theme/img/media/shop/6.jpg",
                    "type": "fashion"
                },
                {
                    "codeProduct": "#107",
                    "name": "Korean Fashion Women's",
                    "available": "843",
                    "price": "US $7.99",
                    "itemCondition": "New with tags",
                    "sold": "543",
                    "review": "643 people",
                    "ThumbnailAltText": "Korean Fashion Women's",
                    "ThumbnailImage": "/blankon-fullpack-admin-theme/img/media/shop/7.jpg",
                    "type": "fashion, korean"
                },
                {
                    "codeProduct": "#108",
                    "name": "Fashion Women Loose",
                    "available": "290",
                    "price": "US $7.58",
                    "itemCondition": "New with tags",
                    "sold": "312",
                    "review": "365 people",
                    "ThumbnailAltText": "Fashion Women Loose",
                    "ThumbnailImage": "/blankon-fullpack-admin-theme/img/media/shop/8.jpg",
                    "type": "fashion"
                },
                {
                    "codeProduct": "#109",
                    "name": "10 Seeds Miracle Fruits",
                    "available": "340",
                    "price": "US $15.99",
                    "itemCondition": "New with tags",
                    "sold": "290",
                    "review": "110 people",
                    "ThumbnailAltText": "10 Seeds Miracle Fruits",
                    "ThumbnailImage": "/blankon-fullpack-admin-theme/img/media/shop/9.jpg",
                    "type": "home_garden"
                },
                {
                    "codeProduct": "#110",
                    "name": "10 Seeds Triphasia",
                    "available": "563",
                    "price": "US $9.99",
                    "itemCondition": "New",
                    "sold": "342",
                    "review": "876 people",
                    "ThumbnailAltText": "10 Seeds Triphasia",
                    "ThumbnailImage": "/blankon-fullpack-admin-theme/img/media/shop/10.jpg",
                    "type": "home_garden"
                },
                {
                    "codeProduct": "#111",
                    "name": "Nike Men's Mercurial",
                    "available": "742",
                    "price": "US $29.99",
                    "itemCondition": "New without box",
                    "sold": "732",
                    "review": "653 people",
                    "ThumbnailAltText": "Nike Men's Mercurial",
                    "ThumbnailImage": "/blankon-fullpack-admin-theme/img/media/shop/11.jpg",
                    "type": "sport, all"
                },
                {
                    "codeProduct": "#112",
                    "name": "CR7 Jersey Real Madrid",
                    "available": "345",
                    "price": "US $24.99",
                    "itemCondition": "New",
                    "sold": "300",
                    "review": "456 people",
                    "ThumbnailAltText": "CR7 Jersey Real Madrid",
                    "ThumbnailImage": "/blankon-fullpack-admin-theme/img/media/shop/12.jpg",
                    "type": "sport, jersey"
                }
            ];*/
            var dataSource, filtering;

            dataSource = function(options, callback){
                
                
                // Custom Data Source Ajax
                var pageIndex = options.pageIndex;
                var pageSize = options.pageSize;
                
                var options = {
					pageIndex: pageIndex,
					pageSize: pageSize,
					sortDirection: options.sortDirection,
					sortBy: options.sortProperty,
					filterBy: options.filter.value || '',
                    searchBy: options.search || '',
                    value : options.filter.value,
                    view: options.view
                };  

                $.ajax({
					type: 'post',
                    url: VendtyApp.handleBaseURL()+'/ProductoRestaurant/getAjaxProducts/',
					data: options
                })
                .done(function(data){
                    //sconsole.log(options);
                    products = data.datos;
                    var items = filtering(options);
                    //console.log(items);
                    var resp = {
                        count: data.count,
                        items: [],
                        page: options.pageIndex,
                        pages: Math.ceil(data.count/(options.pageSize || 50))
                    };  
                    var i, items, l;
                        
                   // i = options.pageIndex * (options.pageSize || 50);
                    i=0;
                    l = i + (options.pageSize || 50);
                    l = (l <= resp.count) ? l : resp.count;
                    resp.start = i + 1;
                    resp.end = l;

                    if(options.view==='list' || options.view==='thumbnail'){
                        if(options.view==='list'){
                            resp.columns = columns;
                            for(i; i<l; i++){
                                resp.items.push(items[i]);
                            }
                        }else{
                            for(i; i<l; i++){
                                resp.items.push({
                                    name: items[i].name,
                                    src: VendtyApp.handleBaseURL()+'/'+items[i].ThumbnailImage
                                });
                            }
                        }

                        setTimeout(function(){
                            //
                           // console.log(resp);
                            callback(resp);
                        }, delays[Math.floor(Math.random() * 4)]);
                    }
                });


                

                /*var items = filtering(options);
                var resp = {
                    count: items.length,
                    items: [],
                    page: options.pageIndex,
                    pages: Math.ceil(items.length/(options.pageSize || 50))
                };
                var i, items, l;

                i = options.pageIndex * (options.pageSize || 50);
                l = i + (options.pageSize || 50);
                l = (l <= resp.count) ? l : resp.count;
                resp.start = i + 1;
                resp.end = l;

                if(options.view==='list' || options.view==='thumbnail'){
                    if(options.view==='list'){
                        resp.columns = columns;
                        for(i; i<l; i++){
                            resp.items.push(items[i]);
                        }
                    }else{
                        for(i; i<l; i++){
                            resp.items.push({
                                name: items[i].name,
                                src: items[i].ThumbnailImage
                            });
                        }
                    }

                    setTimeout(function(){
                        callback(resp);
                    }, delays[Math.floor(Math.random() * 4)]);
                }*/
            };

            filtering = function(options){
                var items = $.extend([], products);
                //console.log(items);
                var search;
                if(options.value!=='all'){
                    items = $.grep(items, function(item){
                        //return (item.type.search(options.value)>=0);
                        return (item);
                    });
                }
                /*if(options.search){
                    search = options.search.toLowerCase();
                    items = $.grep(items, function(item){
                        return (
                        (item.codeProduct.toLowerCase().search(options.search)>=0) ||
                        (item.name.toLowerCase().search(options.search)>=0) ||
                        (item.available.toLowerCase().search(options.search)>=0) ||
                        (item.price.toLowerCase().search(options.search)>=0) ||
                        (item.itemCondition.toLowerCase().search(options.search)>=0) ||
                        (item.sold.toLowerCase().search(options.search)>=0) ||
                        (item.review.toLowerCase().search(options.search)>=0) ||
                        (item.type.toLowerCase().search(options.search)>=0)
                        );
                    });
                }
                if(options.sortProperty){
                    items = $.grep(items, function(item){
                        if(options.sortProperty==='id' || options.sortProperty==='height' || options.sortProperty==='weight'){
                            return parseFloat(item[options.sortProperty]);
                        }else{
                            return item[options.sortProperty];
                        }
                    });
                    if(options.sortDirection==='desc'){
                        items.reverse();
                    }
                }*/

                return items;
            };

            // REPEATER
            /*$('#repeaterIllustration').repeater({
                dataSource: dataSource
            });*/

            $('#myRepeater').repeater({
                list_columnRendered: customColumnRenderer,
                dataSource: dataSource
            });

            function customColumnRenderer(helpers, callback){
                var column = helpers.columnAttr;
                var rowData = helpers.rowData;
                var customMarkup = '';
                
                switch(column) {
                    
                    case 'action':
                        customMarkup = '<div class="btn-group"><a href="' + VendtyApp.handleBaseURL() + '/ProductoRestaurant/createProduct/' + rowData.id + '" type="button" class="btn btn-default input-sm" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-pencil"></i></a><a href="' + VendtyApp.handleBaseURL() + '/ProductoRestaurant/cloneProduct/' + rowData.id + '" type="button" class="btn btn-default input-sm" data-toggle="tooltip" data-placement="top" title="Duplicar producto"><i class="fa fa-files-o"></i></a><a href="' + VendtyApp.handleBaseURL() + '/ProductoRestaurant/deleteProduct/' + rowData.id + '" type="button" onclick="if(confirm(\'Esta seguro que desea eliminar el registro ?\')){return true;}else{return false;}" class="btn btn-default input-sm" data-toggle="tooltip" data-placement="top" title="Eliminar"><i class="fa fa-trash-o"></i></a></div>';                        
                        break;
                    /*case 'clonado':    
                        
                        break;*/
                    default:
                        customMarkup = helpers.item.text();
                        break;
                }
                helpers.item.html(customMarkup);
                VendtyApp.handleTooltip();
                
                callback();
            }

            

        }

        
    };

}();

// Call main app init
VendtyTable.init();