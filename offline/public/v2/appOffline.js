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
		function crearDB(){						
			
			// Seleccionamos Base de Datos
			db = openDatabase('vendty', '1.0', 'vendty', 50 * 1024 * 1024);
			// Si hay un error
			db.onError = function(tx, e) { alert("Ha habido un error en la creacion de la base de datos: " + e.message); return false; }		

			
			// Creamos tablas si no existen
			db.transaction(function (tx) {
				
			   //tx.executeSql('CREATE TABLE IF NOT EXISTS ventas (id INTEGER PRIMARY KEY AUTOINCREMENT UNIQUE, obj TEXT)');
			   tx.executeSql('CREATE TABLE IF NOT EXISTS producto( id INTEGER PRIMARY KEY AUTOINCREMENT,categoria_id INT,codigo TEXT,nombre TEXT,codigo_barra TEXT,precio_compra REAL,precio_venta REAL,stock_minimo INT,descripcion TEXT,activo INT,impuesto REAL,fecha DATE,imagen TEXT,material INT,ingredientes INT,combo INT,unidad_id INT,stock_maximo INT,fecha_vencimiento TEXT,ubicacion TEXT,ganancia INT,muestraexist INT,tienda INT) ');
			   tx.executeSql('CREATE TABLE IF NOT EXISTS clientes (id_cliente INTEGER PRIMARY KEY AUTOINCREMENT,pais TEXT,provincia TEXT,nombre_comercial TEXT,razon_social TEXT,tipo_identificacion TEXT,nif_cif TEXT,contacto TEXT,pagina_web TEXT,email TEXT,poblacion TEXT,direccion TEXT,cp TEXT,telefono TEXT,movil TEXT,fax TEXT,tipo_empresa TEXT,entidad_bancaria TEXT,numero_cuenta TEXT,observaciones TEXT,grupo_clientes_id INT) ');			   
			   
			});
			
		}
		//==========================================================================================
		// TRUNCATE
		//==========================================================================================
		
		function truncate( tabla ){
			db.transaction( function (tx) { tx.executeSql( "DELETE FROM "+tabla ); } );				
		}
		
		//==========================================================================================
		// INSERT
		//==========================================================================================
		
		function insert( sql ){
			db.transaction( function (tx) { tx.executeSql( sql ); } );				
			//console.log(sql);
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
			
			//Clientes
			truncate("clientes");
			var n = data["clientes"].length ;
			for( var i = 0; i < n; i++){							
				var obj = data["clientes"][i];														
				var sql = '\
				INSERT INTO clientes (id_cliente,pais,provincia,nombre_comercial,razon_social,tipo_identificacion,nif_cif,contacto,pagina_web,email,poblacion,direccion,cp,telefono,movil,fax,tipo_empresa,entidad_bancaria,numero_cuenta,observaciones,grupo_clientes_id)\
				VALUES ("'+obj["id_cliente"]+'","'+obj["pais"]+'","'+obj["provincia"]+'","'+obj["nombre_comercial"]+'","'+obj["razon_social"]+'","'+obj["tipo_identificacion"]+'","'+obj["nif_cif"]+'","'+obj["contacto"]+'","'+obj["pagina_web"]+'","'+obj["email"]+'","'+obj["poblacion"]+'","'+obj["direccion"]+'","'+obj["cp"]+'","'+obj["telefono"]+'","'+obj["movil"]+'","'+obj["fax"]+'","'+obj["tipo_empresa"]+'","'+obj["entidad_bancaria"]+'","'+obj["numero_cuenta"]+'","'+obj["observaciones"]+'","'+obj["grupo_clientes_id"]+'")';				
				insert( sql );				
			}
			
			//Productos
			truncate("producto");
			var n = data["productos"].length ;
			for( var i = 0; i < n; i++){							
				var obj = data["productos"][i];														
				var sql = '\
				INSERT INTO producto (id,categoria_id,codigo,nombre,codigo_barra,precio_compra,precio_venta,stock_minimo,descripcion,activo,impuesto,fecha,imagen,material,ingredientes,combo,unidad_id,stock_maximo,fecha_vencimiento,ubicacion,ganancia,muestraexist,tienda)\
				VALUES ("'+obj["id"]+'","'+obj["categoria_id"]+'","'+obj["codigo"]+'","'+obj["nombre"]+'","'+obj["codigo_barra"]+'","'+obj["precio_compra"]+'","'+obj["precio_venta"]+'","'+obj["stock_minimo"]+'","'+obj["descripcion"]+'","'+obj["activo"]+'","'+obj["impuesto"]+'","'+obj["fecha"]+'","'+obj["imagen"]+'","'+obj["material"]+'","'+obj["ingredientes"]+'","'+obj["combo"]+'","'+obj["unidad_id"]+'","'+obj["stock_maximo"]+'","'+obj["fecha_vencimiento"]+'","'+obj["ubicacion"]+'","'+obj["ganancia"]+'","'+obj["muestraexist"]+'","'+obj["tienda"]+'")';				
				insert( sql );				
			}
			
			
		}
		
		
		
		//==========================================================================================
		// Controlador
		//==========================================================================================		
		function controlador(data){
			
			crearDB();
			addDB( data );
			
			//Finalizamos
			setTimeout(function(){				
				$('#btnGuardarSinc').css("visibility","visible");
				$('#txtGuardandoSinc').html("¡Aplicación Offline Almacenada!");
				$('#modalSinc #cargando').hide();				
			},1000);
	
		}
		
		
		
		//================================================================================================================================
		
		
		function getAjaxData(servicio){
			
			$.ajax({
				
				type: "POST",
				dataType: "json",
				url: servicio,
				async: false,
				success: function ( response ) {                
					controlador( response );
				},
				error: function (xhr, textStatus, errorThrown) {
					alert(textStatus + " : " + errorThrown);
				}
				
			});	
			
		}


		
		//================================================================================================================================
		
		this.guardarOffline = function( servicio ){
			getAjaxData(servicio);
		}	
		this.appInsert = function( sql ){
			insert(sql);
		}	
		

}