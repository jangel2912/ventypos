<?php


class lista_detalle_precios_model extends CI_Model
{
	var $connection;
	// Constructor
	public function __construct()
	{
		parent::__construct();		
	}
        
      public function initialize($connection){

          $this->connection = $connection;

      } 
        
      public function get($id_lista_precios_id = 0, $producto_id = 0){

        $query = $this->connection->query("SELECT * FROM  lista_detalle_precios WHERE id_lista_precios = '".$id_lista_precios_id."' and id_producto = $producto_id");
        return $query->row_array();               

      }

      public function get_where(){
        $query = $this->connection->query("SELECT 
                                             lista_detalle_precios.id,
                                             producto.`nombre`,
                                             producto.`impuesto`,
                                             producto.`codigo`,
                                             lista_detalle_precios.id_impuesto,
                                             lista_detalle_precios.id_lista_precios,
                                             lista_detalle_precios.precio ,
											                       lista_detalle_precios.id_producto
                                          FROM  
                                            lista_detalle_precios , producto 
                                          WHERE 
                                            id_lista_precios = ".$_POST['lista']." AND lista_detalle_precios.`id_producto` = producto.id");
        return $query->result(); 
      }

    public function edit_price_special($product){
      $this->connection->where(array('id_lista_precios'=> $product['list_id'] ,'id'=>$product['product_id']));
      $this->connection->update("lista_detalle_precios", array('precio'=>$product['new_price']) );
    }

    public function create($detail){
        
        $query = "INSERT INTO `lista_detalle_precios` (`id_producto`, `id_impuesto`, `id_lista_precios`,`precio`) 
                  VALUES (
                      '".$detail['product_id']."', 
                      '".$detail['impuesto']."', 
                      '".$detail['lista']."',
                      '".$detail['precio_nuevo']."'
                  );";
        
        return $this->connection->query($query);
       
    }

    public function create_all($detail){
        
      $sql_precio_almacen = "SELECT valor_opcion FROM opciones WHERE nombre_opcion = 'precio_almacen'";
      $precio_almacen = $this->connection->query($sql_precio_almacen)->result()[0];
      $almacen_seleccionado = $this->input->post('almacen');

      switch($precio_almacen->valor_opcion){
        case 0 : 
          $sql = "SELECT id AS producto_id,impuesto,precio_venta FROM producto";
          $productos = $this->connection->query($sql)->result();
        break;

        case 1 :
          $sql = "SELECT * FROM stock_actual WHERE almacen_id = '".$almacen_seleccionado."' ";
          $productos = $this->connection->query($sql)->result();
        break;
      }

      foreach ($productos as $value) {  
        if($value->precio_venta == NULL || $value->precio_venta <= 0){
          $precio_final = 0;  
        }else{
          $precio_final = $value->precio_venta - (($value->precio_venta * $detail['descuento']) /100);
        }     
        
        $data[] = array(
          "id_producto" => $value->producto_id,
          "id_impuesto" => $value->impuesto,
          "id_lista_precios" => $detail['lista'],
          "precio" => $precio_final
        );
      }

      $this->connection->insert_batch('lista_detalle_precios',$data);
    }

    //Eliminar un item de el detalle de la lista de precios
    public function delete_item_list($id){
       $query = "DELETE FROM lista_detalle_precios WHERE id = $id";
       return $this->connection->query($query);  
    }

    public function eliminar_lista_precios($id){
       $query = "DELETE FROM lista_precios WHERE id = $id";
        $this->connection->query($query);  
	   $query_1 = "DELETE FROM  lista_detalle_precios WHERE id_lista_precios = $id";
       $this->connection->query($query_1);     
    }
    
    public function delete($id_lista){
       $query = "DELETE FROM lista_detalle_precios WHERE id_lista_precios = $id_lista;";
       return $this->connection->query($query);  
    }

    public function getIdLista($id_lista)
    {
        $query = "select p.id, p.codigo, p.nombre, p.precio_venta, l.precio, l.id_impuesto
                 from lista_detalle_precios as l
                 inner join producto as p
                 on p.id = l.id_producto
                 where id_lista_precios = $id_lista
                ";
        $lista = $this->connection->query($query);
        return $lista->result_array(); 
    }
    
    public function deleteProducto($id_producto)
    {
        $query = "DELETE FROM lista_detalle_precios WHERE id_producto = $id_producto;";
        return $this->connection->query($query);
    }

    //Jeisson Rodriguez - 09/07/2019
    public function getByProduct($id_producto){
      $query = $this->connection->query("SELECT ldp.id as id_ldp, ldp.id_producto as id_producto_ldp, ldp.id_lista_precios as id_lista_precios_ldp, ldp.precio as precio_ldp, lp.id as id_lp, lp.nombre as nombre_lp FROM  lista_detalle_precios as ldp left join lista_precios as lp on lp.id = ldp.id_lista_precios WHERE id_producto = $id_producto group by id_lp");
      return $query->result();               
    }
    
    public function editListaPrecios($id_book, $new_price) {
      $query = $this->connection->query("UPDATE `lista_detalle_precios` SET `precio` = '$new_price' WHERE (`id` = '$id_book')");
      return $query;  
    }
  }
?>