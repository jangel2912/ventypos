var classOffline = function(){

	

	

		var db;

		

		

		//******************************************************************************************

		//

		//		 CRUDS & DB CREATION

		//

		//******************************************************************************************

			

		//==========================================================================================

		// DB creacion y tablas

		//==========================================================================================

		function crearDB( lista, script, callBack ){						

			

			// Seleccionamos Base de Datos

			db = openDatabase('vendty', '1.0', 'vendty', 100 * 1024 * 1024);

			// Si hay un error

			db.onError = function(tx, e) { alert("Ha habido un error en la creacion de la base de datos: " + e.message); return false; }		



			

			

			

			//Borramos las tablas primero para actualizar posibles cambios en las tablas

			db.transaction(function (tx) {



					var listaTablasOffline =["_offlineVentas","_offlineClientes","_extraData"];

					var listaTablas = lista;



					

					// tablas de del offline

					for ( var i = 0; i < (listaTablasOffline.length); i++ ){

						tx.executeSql('DROP TABLE IF EXISTS '+listaTablasOffline[i]+';' );

					}

					

					// tablas de produccion

					for ( var i =0; i < (listaTablas.length); i++ ){

						tx.executeSql('DROP TABLE IF EXISTS '+listaTablas[i]+';' );

					}

					

				

				},

				function(){console.log( alert( "Error al borrar las tablas" ) );},

				function(){



					

					// Creamos tablas si no existen

					db.transaction(function (tx) {

						

						// CUSTOME DB



						tx.executeSql('CREATE TABLE IF NOT EXISTS _offlineVentas (id INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE, obj TEXT DEFAULT "")');												

						tx.executeSql('CREATE TABLE IF NOT EXISTS _offlineClientes (id INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE, obj TEXT DEFAULT "")');

						tx.executeSql('CREATE TABLE IF NOT EXISTS _extraData( id INTEGER PRIMARY KEY AUTOINCREMENT ,consecutivo TEXT DEFAULT "",user_id TEXT DEFAULT "", is_admin TEXT DEFAULT "", username TEXT DEFAULT "", base TEXT DEFAULT "", ultima_venta TEXT DEFAULT "", ultimo_cliente TEXT DEFAULT "")');

						

						// BACKUP DB

						tx.executeSql('CREATE TABLE IF NOT EXISTS provincia( pro_id INTEGER PRIMARY KEY AUTOINCREMENT, pro_nombre TEXT DEFAULT "", pro_pais TEXT DEFAULT "")');



						// BACKUP DB

						// lista de los scripts para la creacion de las tablas dinamicamente

						//for(var k = 0; k < script.length; k++ ) {
							//alert("Tablas="+script.length);
						
							
						for(var k = 0; k < script.length; k++ ) {
						
							
							var str = script[k]; 
							var a = str.search("impresora_rest_categoria_almacen");
							var b = str.search("impresoras_restaurante");
							var c = str.search("tamanos_productos_posee_categoria");											
							var n = str.indexOf("EXISTS");
							var y = str.indexOf("(");
							var z = str.substring(n + 6, y);
							var d = z.search("venta_");
							var e = z.search("ventas_forma_pago_pendiente");

							if ((a < 0) && (b < 0) && (c < 0) && (d < 0) && (e < 0)) {
									tx.executeSql(script[k]);
									//console.log(script[k]);
							} 					

						}

						

					   

					},

						function(){ alert( "Error en la creacion de la DB" );},

						callBack

					);

					

				}

			);

			

		}

		//==========================================================================================

		// TRUNCATE

		//==========================================================================================

		

			//==========================================================================================

			// Los truncate no se usan, en vez de eso se esta usando un DROP, en caso de que las tablas cambien desde produccion

			//==========================================================================================

			

    		function truncateAll( data, callBack ){

			db.transaction( function (tx) { 

                            

                            var tablas = Object.keys( data );                            

                            for(var k = 0; k < tablas.length; k++ ) {                                                        

                                tx.executeSql( "DELETE FROM "+tablas[k] );

                            }

                            

                        },

                            function(){ console.log( alert( "Error al borrar datos" ) );},

                            callBack

                        );				

		}

                

		function truncate( tabla ){

			db.transaction( function (tx) { tx.executeSql( "DELETE FROM "+tabla ); } );				

		}

				

			

		

		//================================================================================================================================

		//	>>>>>>>>   FIN CRUDS

		//================================================================================================================================

		



		

		

		//******************************************************************************************

		//

		//		 Aplicacion

		//

		//******************************************************************************************		

		

		

		//==========================================================================================

		// Agregar Datos

		//==========================================================================================

		function addDB( data ){							

                       

			db.transaction( function(tx){

										   

				//var tablas = ["productos", "clientes"];

				var tablas = Object.keys( data );

				for(var k = 0; k < tablas.length; k++ ) {                            

				

					var tabla = tablas[k];



					var n = data[ tabla ].length ;



					var listaColmunas;                            

					if(n>0){ 



						var stringCol="";

						var stringVal="";



						listaColmunas = Object.keys( data[ tabla ][0] ) ;

						var colmLength = listaColmunas.length;                                

						for(var i = 0; i < colmLength; i++ ) {

						   stringCol+= listaColmunas[i]+",";

						   stringVal+= "?,";

						}

						stringCol = stringCol.substring(0, stringCol.length-1);

						stringVal = stringVal.substring(0, stringVal.length-1);





						var sql = 'INSERT INTO '+tabla+' ('+stringCol+') VALUES ('+stringVal+') ';

						

						for( var i = 0; i < n; i++){							

							var obj = data[ tabla ][i];

							var objData = [];

							for(var j = 0; j < colmLength; j++ ) {

								objData.push(  data[ tabla ][i][listaColmunas[j]] );

							}

							 

							

							

							tx.executeSql( sql, objData,

								// On Success

								function (itx, results) {},

								// On Error

								function (etx, err) {

									// Reject with the error and the transaction.

									console.log(" >>> ERROR: "+err.message);

								}

							);

							



						}                                

					}                            

				}

				

			},

			

			function(tx,res){

				alert("Error al insertar en DB Local "+res);

			},

			

			function(){

				

				//Finalizamos Correctamente

				setTimeout(function(){				

						$('#btnGuardarSinc').css("visibility","visible");

						$('#txtGuardandoSinc').html("¡Aplicación Offline Almacenada!");

						$('#modalSinc #cargando').hide();				

				},1000);                            

				

			});                        

			

			

		}

		

		function guardarExtraData(data){

										

				var consec = data.consecutivo;

				var id_user = data.id_user;

				var is_admin = data.is_admin;

				var username = data.username;

                                var base = data.base;

                                var ultima_venta = data.ultima_venta;

                                var ultimo_cliente = data.ultimo_cliente;

                                

				

								

				db.transaction(

					function(tx){											   

					

						tx.executeSql( 'INSERT INTO _extraData (consecutivo, user_id, is_admin, username, base, ultima_venta, ultimo_cliente) VALUES ("'+consec+'","'+id_user+'","'+is_admin+'","'+username+'","'+base+'","'+ultima_venta+'","'+ultimo_cliente+'") ' );

						

					},				

					function(tx,res){

						alert("Error al guardar informacion extra en DB Local "+res);

					},				

					function(tx,res){									

					}

				);

				

		}

		

		//==========================================================================================

		// Controlador

		//==========================================================================================		

		function controlador(data, callback ){

			                                               

			crearDB( data.tablas, data.script_tablas, function(){                            

				

				setTimeout(function(){

				

					addDB( data.data );					

					callback();				

					

				},100);

					                

				

				

			});

			

			

		}

		

		

		

		//================================================================================================================================

		

		

		function getAjaxDB(servicio, callback ){

			

			$.ajax({

				

				type: "POST",

				dataType: "json",

				url: servicio,

				async: false,

				success: function ( response ) {                

					controlador( response, callback );

				},

				error: function (xhr, textStatus, errorThrown) {

					alert(textStatus + " : " + errorThrown);

				}

				

			});	

			

		}



		function getAjaxExtraData(servicio){

		

			$.ajax({

				

				type: "POST",

				dataType: "json",

				url: servicio,

				async: false,

				success: function ( response ) {        

					

					guardarExtraData( response );

					

				},

				error: function (xhr, textStatus, errorThrown) {

					alert(textStatus + " : " + errorThrown);

				}

				

			});	

			

		}

		

		//================================================================================================================================

		

		this.guardarOffline = function( urlBackupDB, urlExtraData ){

			

			// Una vez que se hace un backup de la DB completa ahi sis se ejecutan las otras funciones

			getAjaxDB( urlBackupDB, function(){				

							

					//------------------------------------------------------

					//Funciones despues de hacer backup a la DB

					//------------------------------------------------------

					

					getAjaxExtraData( urlExtraData );

					

				

			});

			

		}



		

	

		



}