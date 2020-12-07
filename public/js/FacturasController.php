<?php

class FacturasController extends Controller{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	
        public $defaultAction = 'admin';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'rights', // perform access control for CRUD operations
			/*'postOnly + delete',*/ // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */

	/**
	 * Lists all models.
	 */
	public function actionIndex(){

		$criteria=new CDbCriteria;
		//5= factura de venta bogota ; 9 = remision ; 10 = factura de venta cota
		$criteria->compare('codigo_id',array(5,9,10));

 
	    $dataProvider = new CActiveDataProvider(new OrdenConsumo, array(
	     'criteria'=>$criteria,
	     'sort'=>array('defaultOrder'=>'id ASC'), // orden por defecto según el atributo nombre
	     'pagination'=>array('pageSize'=>15), // personalizamos la paginación
	    ));

		$this->render('index',array(
      		'model'=>$dataProvider
    	));

	}

	public function actionFiltrar()
	{

		if($_POST['tipo']=='FA') $tipo = 5;
		else if($_POST['tipo']=='R') $tipo = 9;
		else $tipo = array(5,9);

		$criteria=new CDbCriteria;
		$criteria->compare('codigo_id',$tipo);
 
	    $dataProvider = new CActiveDataProvider(new OrdenConsumo, array(
	     'criteria'=>$criteria,
	     'sort'=>array('defaultOrder'=>'id ASC'), // orden por defecto según el atributo nombre
	     'pagination'=>array('pageSize'=>10), // personalizamos la paginación
	    ));

		$this->render('index',array(
      		'model'=>$dataProvider
    	));

	}


	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id){

		$this->render('view',array(
	      'model'=>$this->loadModel($id),
	      'DetalleConsumo'=>( DetalleConsumo::model()->findAll('orden_consumo_id=:orden_consumo_id', array(':orden_consumo_id'=>$id)) )
	    ));
	}

	public function actionCreate(){

	    if($_POST){

		    if( !empty($_POST['OrdenConsumo']['cliente_id']) ){  
		    	if( !empty($_POST['OrdenConsumo']['operario_id']) ){ 
			      if( !empty($_POST['producto_id'][0]) ){
			      	
			        $OrdenConsumo = new OrdenConsumo;
			        $OrdenConsumo->fecha = $_POST['OrdenConsumo']['fecha'];
			        $OrdenConsumo->cliente_id = $_POST['OrdenConsumo']['cliente_id'];
			        $OrdenConsumo->operario_id = $_POST['OrdenConsumo']['operario_id'];
			        $OrdenConsumo->usuario_id = Yii::app()->user->id;


			        $location = $_POST['location'];

				    switch ($location ) {
				      //Bogota
				      case 1:
				        $index_codigo = 5; 
				        $index_factura = 'FV';
				        break;
				      
				      //Cota
				      case 2:
				        $index_codigo = 10;
				        $index_factura = 'FVC';
				        break;

				      default:
				      	echo 'Fatal error';
				      	die();
				    }

			        $Consecutivo = Consecutivos::model()->findByPk($index_codigo);
			        $Consecutivo->numero = ($Consecutivo->numero+1);
			        $OrdenConsumo->numero = $Consecutivo->numero;
			        $Consecutivo->save();

			        $OrdenConsumo->codigo_id= $index_codigo;

			        /*Si es compra*/
			        if(5==3)
			          $operador ='+';
			        else
			          $operador ='-';
			        
			        if($OrdenConsumo->save()){

			        	$base = 0;
			       
			       	  //Detalle consumo
			          foreach ($_POST['producto_id'] as $key => $value) {

			          	$total_item = ( $_POST['valor'][$key] * $_POST['cantidad'][$key] );
			          
			          	$base = ($base + $total_item); //Total precio items sin impuestos.
			            
			            $DetalleConsumo=  new DetalleConsumo(); 
			            $DetalleConsumo->operacion=  (Codigos::model()->findByPk($index_codigo)->codigo);

			            $DetalleConsumo->orden_consumo_id =  $OrdenConsumo->id;

			            if($_POST['type_invoice']!=1){
			            	$DetalleConsumo->llanta_id = $_POST['real_producto_id'][$key] ;
			            	$DetalleConsumo->producto_id = 0;
			            }else{
			            	$DetalleConsumo->producto_id = $_POST['real_producto_id'][$key] ;
			            	$DetalleConsumo->llanta_id = 0;
			            }

			            $DetalleConsumo->cantidad = $operador.$_POST['cantidad'][$key];
			            $DetalleConsumo->peso = $operador.$_POST['peso'][$key]; 
			            $DetalleConsumo->valor = $_POST['valor'][$key]; 
			            $DetalleConsumo->save();
			          }

			          //FACTURA =======================================================================
					  
			          //Impuestos

					  $Impuesto = Impuesto::model()->findAll();
				
					  $iva = $Impuesto[0]->porcentaje;
					  $total_iva = $base  * ($iva/100);

					  $retencion = $_POST['retencion_fuente'];
					  $total_retencion = $base  * ($retencion/100); 

					  /*
					  Cuentas de retencion 
					  (servicios-1355152503)
					  (compras-1355152003).
					  */

					  if($_POST['type_invoice']==1)
					  	$cuenta_retencion = 3;//Compras
					  else
					  	$cuenta_retencion = 7;//Servicios

					  $retecree = $Impuesto[3]->porcentaje;
					  $total_retecree  = $base  * ($retecree/100); 

					  $autocree = $Impuesto[4]->porcentaje;
					  $total_autocree  = $base  * ($autocree/100);

					  //cuenta 412095

						$Factura=  new Factura(); 
						$Factura->orden_consumo_id =  $OrdenConsumo->id;
						$Factura->cuenta = 1 ;//412095;
						$Factura->tipo_cuenta = 2;//Credito;
						$Factura->concepto = 'Venta según '.$index_factura.' '.$Consecutivo->numero; 
						$Factura->valor = $base;
						$Factura->base = 0;

						$Factura->save();

					  //cuenta 24080101 iva	

					    $Factura=  new Factura(); 
						$Factura->orden_consumo_id =  $OrdenConsumo->id;
						$Factura->cuenta = 2 ;//24080101;
						$Factura->tipo_cuenta = 2;//Credito;
						$Factura->concepto = 'Iva Facturado.'.' Base='.$base.' Tarifa='.($iva).'%'; 
						$Factura->valor =  $total_iva;
						$Factura->base = $base;

						$Factura->save();	

					  //cuenta 1355152003 o 1355152503 retencion de la fuente	

					  	$Factura=  new Factura(); 
						$Factura->orden_consumo_id =  $OrdenConsumo->id;
						$Factura->cuenta = $cuenta_retencion ;//1355152003
						$Factura->tipo_cuenta = 1;//debito
						$Factura->concepto = 'Retencion'.' Base='.$base.' Tarifa='.($retencion).'%'; 
						$Factura->valor = $total_retencion;
						$Factura->base = $base;

						$Factura->save();		

					  //cuenta 130505 venta credito

					  	$Factura=  new Factura(); 
						$Factura->orden_consumo_id =  $OrdenConsumo->id;
						$Factura->cuenta = 4 ;//130505
						$Factura->tipo_cuenta = 1;//debito
						$Factura->concepto = 'Venta Crédito '.$index_factura.' '.$Consecutivo->numero; 
						$Factura->valor = $base + $total_iva - $total_retencion ;
						$Factura->base  =  $base;

						$Factura->save();	

					   //cuenta 135519 retecree

					  	$Factura=  new Factura(); 
						$Factura->orden_consumo_id =  $OrdenConsumo->id;
						$Factura->cuenta = 5 ;//135519
						$Factura->tipo_cuenta = 1;//debito
						$Factura->concepto = 'ReteCREE Facturado. Base='.$base.' Tarifa='.($retecree).'%'; 
						$Factura->valor =  $total_retecree;
						$Factura->base = $base;

						$Factura->save();	

					    //cuenta 236901 AUTOCREE

					  	$Factura=  new Factura(); 
						$Factura->orden_consumo_id =  $OrdenConsumo->id;
						$Factura->cuenta = 6 ;//236901
						$Factura->tipo_cuenta = 2;//debito
						$Factura->concepto = 'AutoReteCREE Facturado. '.$index_factura.' '.$Consecutivo->numero; 
						$Factura->valor = $total_autocree;
						$Factura->base = $base;

						$Factura->save();	

			
			          $this->redirect(array(
			          'index',
			          'message'=>'Factura creada exitosamente'
			          ));

			        }
			      }else{
			         $this->redirect(array(
			          'create',
			          'message'=>'Debe agregar al menos un item al detalle de factura'
			          ));
			      }
			    }else{
			    	$this->redirect(array(
			          'create',
			          'message'=>'Porfavor seleccione un vendedor'
			          ));
			    }
		    }else{
			    	$this->redirect(array(
			          'create',
			          'message'=>'Porfavor seleccione un cliente'
			          ));
			}
	    }else{

	      $this->render('create',array(
	        'model'=> (new OrdenConsumo),
	        'impuestos'=> (Impuesto::model()->findAll())
	      ));

	    }


	}

	public function actionCreateOld()
	{
		$model=new Facturas;
                
                
                $modelllanta = new FacturasDetalles;
                $valor= Opcion::model()->findByAttributes(array('nombre'=>'factura_numero'));
                $prefijo= Opcion::model()->findByAttributes(array('nombre'=>'factura_prefijo'));
                $valor->value=$valor->value + 1  ; 
                $valor->save();
                 

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Facturas']))
		{ 
			$model->attributes=$_POST['Facturas'];
                                                   
                  
                        if($model->save()){
                            
                      
           
                             foreach ($_POST['tipo'] as $key=>$value) {
                                    
                                    $modelllanta = new FacturasDetalles;
                           
                                    $modelllanta->tipo=$value;
                                    $modelllanta->factura_id=$model->id;
                                    $modelllanta->precio= $_POST['precio'][$key];
                                    $modelllanta->cantidad =$_POST['cantidad'][$key];
                                    $modelllanta->impuesto=$_POST['impuesto'][$key];
                                    $modelllanta->descuento =$_POST['descuento'][$key];
                                 
                                    $modelllanta->descripcion_d = $_POST['descripcion_d'][$key];
                                    
                                   $modelllanta->precio_total = $_POST['precio_total'][$key];
                
                                   if($modelllanta->save()){
                                       
                                       echo 'bie';
                                   }
                                      else {
                             
                echo '<pre>';
                print_r($modelllanta->getErrors());
                
                echo '</pre>';
                die;
                         }
                                   
                                    
                            }
                            
                            $this->redirect(array('admin','id'=>$model->id));
                            
                        }
                      
				
		}
                          /*echo '<pre>';
                print_r($valor);
                die;
                echo '</pre>';*/
		$this->render('create',array(
			'model'=>$model,'valor'=>$valor,'prefijo'=>$prefijo,'modelllanta'=>$modelllanta
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id){

		if($_POST){

	      $OrdenConsumo = OrdenConsumo::model()->findByPk($id);
	      $OrdenConsumo->fecha= $_POST['OrdenConsumo']['fecha'];
	      $OrdenConsumo->codigo_id= 5;
	      $OrdenConsumo->cliente_id= $_POST['OrdenConsumo']['cliente_id'];
	      $OrdenConsumo->operario_id = $_POST['OrdenConsumo']['operario_id'];
	      $OrdenConsumo->usuario_id = Yii::app()->user->id;

	       /*Si es compra*/
	        if(5==3)
	          $operador ='+';
	        else
	          $operador ='-';

	      if($OrdenConsumo->save()){

	          foreach ($_POST['id'] as $key => $value) {
	            $DetalleConsumo= DetalleConsumo::model()->findByPk( $_POST['id'][$key] );
	            $DetalleConsumo->operacion = (Codigos::model()->findByPk(5)->codigo);
	            $DetalleConsumo->orden_consumo_id =  $OrdenConsumo->id;
	            $DetalleConsumo->producto_id = $_POST['producto_id'][$key] ;
	            $DetalleConsumo->cantidad = $operador.$_POST['cantidad'][$key];
	            $DetalleConsumo->peso = $operador.$_POST['peso'][$key]; 
	            $DetalleConsumo->save();
	          }   

	          
	          $this->redirect(array(
	          'index',
	          'message'=>'Factura actualizada con exito'
	          ));
	      }

	    }else{

	      $this->render('update',array(
	          'model'=>$this->loadModel($id),
	          'DetalleConsumo'=>( DetalleConsumo::model()->findAll( 'orden_consumo_id=:orden_consumo_id', array(':orden_consumo_id'=>$id)) )
	      ));

	    }
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{

		if($this->loadModel($id)->delete())
        	DetalleConsumo::model()->deleteAll( 'orden_consumo_id=:orden_consumo_id', array(':orden_consumo_id'=>$id) );
		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Facturas('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Facturas']))
			$model->attributes=$_GET['Facturas'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

    public function actionClientes($term)
	{
		$cliente = YII::app()->db->createCommand("Select user_id,firstname,lastname ,nit ,direccion,telefono,ciudad from profiles INNER JOIN authassignment on authassignment.userid = profiles.user_id where itemname='cliente' and firstname like '%$term%' ")->queryAll();
               
               
                /*echo '<pre>';$cliente = YII::app()->db->createCommand("Select user_id,firstname,lastname
                print_r($cliente);
                die;
                echo '</pre>';*/
                $arr = array();
                        foreach ($cliente as $item) {
                        $arr[] = array(
                        'id' => $item['user_id'],
                        'value' => $item['firstname'] ." ".$item['lastname'],
                        //'label' => $item->nombre_estado,
                        'descripcion'=> $item['nit'] ." , ".$item['direccion'] ." , ".$item['ciudad'] ." , ".$item['telefono'],
                        );
                        }
	
                        echo CJSON::encode($arr);
                        }
                        
    public function actionProductos($term ,$countryCode){
              
		$producto = YII::app()->db->createCommand("Select id ,nombre,cantidad,descripcion from inventario where nombre like '%$term%' ")->queryAll();
		$llanta= YII::app()->db->createCommand("Select llanta.id, orden,numero from llanta INNER JOIN orden on llanta.orden_id = orden.id where llanta.estado_id = 13 and orden.cliente_user_id = $countryCode and orden like '%$term%' ")->queryAll();

		$arr = array();

	    foreach ($producto as $item) {
		    $arr[] = array(
			    'id' => $item['id'],
			  	'value' => $item['nombre'] ,
			    'descripcion'=> $item['descripcion'] ,
			    'cantidad'=>$item['cantidad'],
			    'tipo'=>'inventario'
		    );
	    }

	    $arr1=array();

	    foreach ($llanta as $item) {
			$arr[] = array(
			'id' => $item['id'],
			'value' => $item['orden'] ." ".$item['numero'],
			'descripcion'=> $item['orden'] ." ".$item['numero'] ,
			'cantidad'=> 1,
			'tipo'=>'llanta'
			);
	    }

	    echo CJSON::encode(array_merge($arr,$arr1));
    }
                        
                        
    public function actionNumero(){

		//$cliente = YII::app()->db->createCommand("Select user_id,firstname,lastname ,nit ,direccion,telefono,ciudad from profiles INNER JOIN authassignment on authassignment.userid = profiles.user_id where itemname='cliente' and firstname like '%$term%' ")->queryAll();
                /*echo '<pre>';
                print_r($cliente);
                die;
                echo '</pre>';*/
               $prefijo= Opcion::model()->findByAttributes(array('nombre'=>'factura_prefijo'));
               
                $numero= Opcion::model()->findByAttributes(array('nombre'=>'factura_numero'));
                 //$numero->value = $numero->value ++; 
                 //$numero->save();
                    $model = new FacturaNumero;
                    if(isset($_POST['FacturaNumero'])) 
		{ 
                        $model->attributes=$_POST['FacturaNumero'];
                        
                        if($model->validate()){
                            $prefijo->value=$model->factura_prefijo;
                            $prefijo->save();
                            $numero->value=$model->factura_numero;
                            $numero->save();
                            
                            
                            
                        }
                }
                $this->render('numero',array(
                            'model'=>$model,'prefijo'=>$prefijo ,  'numero'=>$numero, 
                    ));
    }


    public function actionExportar(){


    	$Factura = Factura::model()->findAll(); 

    	$row_data = array();

    	$objPHPExcel = new PHPExcel();

	    $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1', 'TIPO DOC.')
        ->setCellValue('B1', 'NUMERO DOC.')
        ->setCellValue('C1', 'FECHA')
        ->setCellValue('D1', 'CUENTA CONTABLE')
        ->setCellValue('E1', 'CONCEPTO')
        ->setCellValue('F1', 'CENTRO COSTO')
        ->setCellValue('G1', 'VALOR')
        ->setCellValue('H1', 'NATURALEZA')
        ->setCellValue('I1', 'IDENTIDAD DEL TERCERO')
        ->setCellValue('J1', 'D.V.')
        ->setCellValue('K1', 'NOMBRE DEL TERCERO')
        ->setCellValue('L1', 'CIUDAD')
        ->setCellValue('M1', 'PAIS')
        ->setCellValue('N1', 'CUENTA BANCARIA')
        ->setCellValue('O1', 'DOC. FUENTE')
        ->setCellValue('P1', 'CODIGO NEGOCIO')
        ->setCellValue('Q1', 'NOMBRE NEGOCIO')
        ->setCellValue('R1', 'CODIGO DEL TERCERO')
        ->setCellValue('S1', 'BASE');

		$row=2;

    	foreach ($Factura as $key => $factura_value) {

    		//Tipo de documento

    		$DetalleFacturas = DetalleConsumo::model()->findAll('orden_consumo_id=:orden_consumo_id', array(':orden_consumo_id'=>$factura_value->orden_consumo_id)); 
			
			foreach ($DetalleFacturas as $key => $value) {

			  //TIPO DOC.
			  $row_data['tipo_doc'] = $value->operacion;
		      /*$tipo_doc = $value->operacion;*/

		      //NUMERO DOC.
		      $OrdenConsumo = OrdenConsumo::model()->findByPk($value->orden_consumo_id); 
		      $row_data['numero_doc']= $OrdenConsumo['numero'];
	          /*$numero_doc = $OrdenConsumo['numero'];*/

	          //FECHA
		      $fecha = new DateTime($OrdenConsumo['fecha']);
		      $row_data['fecha']=  $fecha->format('Y/m/d');

			  //IDENTIDAD DEL TERCERO
        	  $Cliente = Profile::model()->findByAttributes(array('user_id'=>$OrdenConsumo['cliente_id']));
			  $row_data['identidad_tercero']=  $Cliente->nit;

			  //D.V.
			  $row_data['identidad_tercero']=  $Cliente->nit;
			  /*$identidad_tercero = $Cliente->nit;*/
			  
			  //NOMBRE DEL TERCERO
			  $row_data['nombre_del_tercero']=  ($Cliente->firstname.' '.$Cliente->lastname);

			  //CIUDAD
			  $row_data['ciudad']= $Cliente->ciudad;

			   //CIUDAD
			  $row_data['pais']= 'Colombia';

			  //CUENTA BANCARIA
			  $row_data['cuenta_bancaria']= '';

			  //DOC. FUENTE
			  $row_data['doc_fuente']= $row_data['tipo_doc'].' '.$row_data['numero_doc'];

			  //CODIGO NEGOCIO;NOMBRE NEGOCIO;CODIGO DEL TERCERO.
			  $row_data['codigo_negocio']= '';
			  $row_data['nombre_negocio']= '';
			  $row_data['codigo_del_tercero']= '';

			}

			  //CUENTA CONTABLE
			  $cuenta = YII::app()->db->createCommand("SELECT * FROM cuentas  where id = ".$factura_value->cuenta.";")->queryRow();
              $row_data['cuenta_contable']=  $cuenta['numero'];       	  
        	  /*$cuenta_contable = $cuenta['numero'];	*/
			 

			  //CONCEPTO

			  /*$concepto = $factura_value->concepto;*/
			  $row_data['concepto']= $factura_value->concepto;
			 
			  //CENTRO COSTO
			  $row_data['centro_de_costo']= '';

			  //VALOR
			  $row_data['valor'] = $factura_value->valor;
			  /*$valor = $factura_value->valor;*/

			  //VALOR
			  $row_data['base'] = $factura_value->base;

			  //NATURALEZA
			  $tipo_cuenta = YII::app()->db->createCommand("SELECT * FROM tipo_cuenta  where id = ".$factura_value->tipo_cuenta.";")->queryRow();
        	  $row_data['naturaleza'] = $tipo_cuenta['simbolo'];	
        	  /*$naturaleza = $tipo_cuenta['simbolo'];*/	


			$objPHPExcel->setActiveSheetIndex(0)        
			->setCellValue('A'.$row, $row_data['tipo_doc'])
			->setCellValue('B'.$row, $row_data['numero_doc'])
			->setCellValue('C'.$row, $row_data['fecha'] )
			->setCellValue('D'.$row, $row_data['cuenta_contable'])
			->setCellValue('E'.$row, $row_data['concepto'])
			->setCellValue('F'.$row, $row_data['centro_de_costo'])
			->setCellValue('G'.$row, $row_data['valor'])
			->setCellValue('H'.$row, $row_data['naturaleza'])
			->setCellValue('I'.$row, $row_data['identidad_tercero'])
			->setCellValue('J'.$row, '')
			->setCellValue('K'.$row, $row_data['nombre_del_tercero'])
			->setCellValue('L'.$row, $row_data['ciudad'])
			->setCellValue('M'.$row, $row_data['pais'])
			->setCellValue('N'.$row, $row_data['cuenta_bancaria'])
			->setCellValue('O'.$row, $row_data['doc_fuente'])
			->setCellValue('P'.$row, $row_data['codigo_negocio'])
			->setCellValue('Q'.$row, $row_data['nombre_negocio'])
			->setCellValue('R'.$row, $row_data['codigo_negocio'])
			->setCellValue('S'.$row, $row_data['base']);
	      
	        $row++;
 
			/*
			echo $value->id;
			echo $value->orden_consumo_id;
			echo $value->tipo_cuenta;
			echo $value->concepto;
			echo $value->negocio;
			echo $value->cod_negocio;
			echo $value->base;
			echo $value->valor;
			*/
    	}

		$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
		$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

		$styleThinBlackBorderOutline = array(

		  'borders' => array(

		    'outline' => array(

		      'style' => PHPExcel_Style_Border::BORDER_THIN,

		      'color' => array('argb' => 'FF000000'),

		    ),

		  ),

		);

		$objPHPExcel->getActiveSheet()->getStyle('I1:H'.--$row)->applyFromArray($styleThinBlackBorderOutline);

		$objPHPExcel->getActiveSheet()->getStyle('I1:I1')->applyFromArray(

			array(
				'font'    => array(
					'bold'      => true
				),

				'alignment' => array(
					'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
				),

				'borders' => array(
					'top'     => array(
					  'style' => PHPExcel_Style_Border::BORDER_THIN
					)
				),

				'fill' => array(
					'type'       => PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
					'rotation'   => 90,
					'startcolor' => array('argb' => 'FFA0A0A0'),
					'endcolor'   => array('argb' => 'FFFFFFFF')
				)

			)
		);

		$objPHPExcel->getActiveSheet()->setTitle('facturas');

		$objPHPExcel->setActiveSheetIndex(0);

		header('Content-Type: application/vnd.ms-excel');

		header('Content-Disposition: attachment;filename="factura'.date('Y-m-d').'.xls"');

		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

		$objWriter->save('php://output'); 

	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer $id the ID of the model to be loaded
	 * @return Facturas the loaded model
	 * @throws CHttpException
	 */

	public function loadModel($id){

	    $model=OrdenConsumo::model()->findByPk($id);

	    if($model===null)
	      throw new CHttpException(404,'The requested page does not exist.');
	    return $model;

	}

	/**
	 * Performs the AJAX validation.
	 * @param Facturas $model the model to be validated
	 */
	protected function performAjaxValidation($model){

		if(isset($_POST['ajax']) && $_POST['ajax']==='facturas-form'){
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}

	
}
