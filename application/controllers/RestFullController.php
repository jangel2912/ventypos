<?php

include_once 'lib/lib/woocommerce-api.php';

class RestFullController extends CI_Controller {

    var $dbConnection;

    function __construct() {

        parent::__construct();



        $usuario = $this->session->userdata('usuario');

        $clave = $this->session->userdata('clave');

        $servidor = $this->session->userdata('servidor');

        $base_dato = $this->session->userdata('base_dato');



        $dns = "mysql://$usuario:$clave@$servidor/$base_dato";

        $this->dbConnection = $this->load->database($dns, true);



        // $this->load->model("RestFullModel",'rest');
        // $this->almacenes->initialize($this->dbConnection);


        $this->load->library('pagination');

        $this->form_validation->set_error_delimiters('<p class="text-error">', '</p>');

        $idioma = $this->session->userdata('idioma');

        $this->lang->load('sima', $idioma);
    }

    function index($offset = 0) {

        if (!$this->ion_auth->logged_in()) {

            redirect('auth', 'refresh');
        }

        $this->layout->template('member')->show('usuarios/index');
    }

    public function Rest() {

        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);


        $options = array(
            'debug' => false,
            'return_as_array' => true,
            'validate_url' => false,
            'timeout' => 80,
            'ssl_verify' => false,
            'version' => 2,
        );
        try {
           // $client = new WC_API_Client('', 'ck_cfa8a5a7305eef0828705f4fd2a1f894bb122458', 'cs_013c7feb51a47f31378ad68ffb36f2f23eabd9bf', $options);
            // coupons
            //print_r( $client->coupons->get() );
            //print_r( $client->coupons->get( $coupon_id ) );
            //print_r( $client->coupons->get_by_code( 'coupon-code' ) );
            //print_r( $client->coupons->create( array( 'code' => 'test-coupon', 'type' => 'fixed_cart', 'amount' => 10 ) ) );
            //print_r( $client->coupons->update( $coupon_id, array( 'description' => 'new description' ) ) );
            //print_r( $client->coupons->delete( $coupon_id ) );
            //print_r( $client->coupons->get_count() );
            // custom
            //$client->custom->setup( 'webhooks', 'webhook' );
            //print_r( $client->custom->get( $params ) );
            // customers
            //print_r( $client->customers->get() );
            //print_r( $client->customers->get( $customer_id ) );
            //print_r( $client->customers->get_by_email( 'help@woothemes.com' ) );
            //print_r( $client->customers->create( array( 'email' => 'woothemes@mailinator.com' ) ) );
            //print_r( $client->customers->update( $customer_id, array( 'first_name' => 'John', 'last_name' => 'Galt' ) ) );
            //print_r( $client->customers->delete( $customer_id ) );
            //print_r( $client->customers->get_count( array( 'filter[limit]' => '-1' ) ) );
            //print_r( $client->customers->get_orders( $customer_id ) );
            //print_r( $client->customers->get_downloads( $customer_id ) );
            //$customer = $client->customers->get( $customer_id );
            //$customer->customer->last_name = 'New Last Name';
            //print_r( $client->customers->update( $customer_id, (array) $customer ) );
            // index
            //print_r( $client->index->get() );
            // orders
            //print_r( $client->orders->get() );
            //print_r( $client->orders->get( $order_id ) );
            //print_r( $client->orders->update_status( $order_id, 'pending' ) );
            // order notes
            //print_r( $client->order_notes->get( $order_id ) );
            //print_r( $client->order_notes->create( $order_id, array( 'note' => 'Some order note' ) ) );
            //print_r( $client->order_notes->update( $order_id, $note_id, array( 'note' => 'An updated order note' ) ) );
            //print_r( $client->order_notes->delete( $order_id, $note_id ) );
            // order refunds
            //print_r( $client->order_refunds->get( $order_id ) );
            //print_r( $client->order_refunds->get( $order_id, $refund_id ) );
            //print_r( $client->order_refunds->create( $order_id, array( 'amount' => 1.00, 'reason' => 'cancellation' ) ) );
            //print_r( $client->order_refunds->update( $order_id, $refund_id, array( 'reason' => 'who knows' ) ) );
            //print_r( $client->order_refunds->delete( $order_id, $refund_id ) );
            // products
           /* $data_prod= $client->products->get(null, array('filter' => ['sku' => $this->input->post('codigoS')]));
            if(isset($data_prod["products"][0]["id"]) && $data_prod["products"][0]["id"] !=NULL){    
                $client->products->update($data_prod["products"][0]["id"], array( 'stock_quantity' => ($this->input->post('agregado')+$this->input->post('actual')) ) ) ;
            }*/
                                
            
            //print_r($client->products->get());
            //print_r( $client->products->get( $product_id ) );
            //print_r( $client->products->get( $variation_id ) );
            //print_r( $client->products->get_by_sku( 'a-product-sku' ) );
            //print_r( $client->products->create( array( 'title' => 'Test Product', 'type' => 'simple', 'regular_price' => '9.99', 'description' => 'test' ) ) );
            //   print_r($client->products->update( $this->input->post('convalid'), array( 'stock_quantity' => ($this->input->post('agregado')+$this->input->post('actual')) ) ) );
            //print_r( $client->products->delete( $product_id, true ) );
            //print_r( $client->products->get_count() );
            //print_r( $client->products->get_count( array( 'type' => 'simple' ) ) );
            //print_r( $client->products->get_categories() );
            //print_r( $client->products->get_categories( $category_id ) );
            // reports
            //print_r( $client->reports->get() );
            //print_r( $client->reports->get_sales( array( 'filter[date_min]' => '2014-07-01' ) ) );
            //print_r( $client->reports->get_top_sellers( array( 'filter[date_min]' => '2014-07-01' ) ) );
            // webhooks
            //print_r( $client->webhooks->get() );
            //print_r( $client->webhooks->create( array( 'topic' => 'coupon.created', 'delivery_url' => 'http://requestb.in/' ) ) );
            //print_r( $client->webhooks->update( $webhook_id, array( 'secret' => 'some_secret' ) ) );
            //print_r( $client->webhooks->delete( $webhook_id ) );
            //print_r( $client->webhooks->get_count() );
            //print_r( $client->webhooks->get_deliveries( $webhook_id ) );
            //print_r( $client->webhooks->get_delivery( $webhook_id, $delivery_id );
            // trigger an error
            //print_r( $client->orders->get( 0 ) );
        } catch (WC_API_Client_Exception $e) {
            echo $e->getMessage() . PHP_EOL;
            echo $e->getCode() . PHP_EOL;
            if ($e instanceof WC_API_Client_HTTP_Exception) {
                print_r($e->get_request());
                print_r($e->get_response());
            }
        }
    }

    public function restApi() {
        $options = array(
            'debug' => false,
            'return_as_array' => true,
            'validate_url' => false,
            'timeout' => 80,
            'ssl_verify' => false,
            'version' => 3,
        );

        //$client = new WC_API_Client('https://vendtypos.com/integracion/index.php/wsp', 'ck_a5b722b305730512dfd0772b86034361afc4f5b8', 'cs_0f87aa3c8f9bb9a1ea635f420178c8f69098426d', $options);

       // print_r($client->webhooks->create(array('topic' => 'product.created', 'delivery_url' => 'http://184.173.43.42/invoice/index.php')));
    }
        
        public function updateProductInventory(){
            if ($this->session->userdata('base_dato') == 'vendty2_db_1493_admon2015_no') {
                $options = array(
                    'debug' => false,
                    'return_as_array' => true,
                    'validate_url' => false,
                    'timeout' => 80,
                    'ssl_verify' => false,
                    'version' => 2,
                );
               
                /*$client = new WC_API_Client('http://alibabaonline.com.co', 'ck_cfa8a5a7305eef0828705f4fd2a1f894bb122458', 'cs_013c7feb51a47f31378ad68ffb36f2f23eabd9bf', $options);
                $data_prod= $client->products->get(null, array('filter' => ['sku' => $this->input->post('codigo')]));
                //var_dump($data_prod['products'][0]['stock_quantity']);
                if($data_prod['products'][0]['stock_quantity']){ 
                    $client->products->update($data_prod['products'][0]['id'],array('stock_quantity' =>($data_prod['products'][0]['stock_quantity'] - $this->input->post('cantidad'))));
                    //echo $data_prod['products'][0]['id'];   
                }*/
               
            }
            
        }
        
    public function agregarCantidadInventario()
    {
        if ($this->session->userdata('base_dato') == 'vendty2_db_1493_admon2015_no') {
            $options = array(
                'debug' => false,
                'return_as_array' => true,
                'validate_url' => false,
                'timeout' => 80,
                'ssl_verify' => false,
                'version' => 2,
            );

            //$client = new WC_API_Client('http://alibabaonline.com.co', 'ck_cfa8a5a7305eef0828705f4fd2a1f894bb122458', 'cs_013c7feb51a47f31378ad68ffb36f2f23eabd9bf', $options);
            /*$client = new WC_API_Client('http://184.173.43.42/tienda', 'ck_33fd835075cf792bd89699c7bbd764833e730137', 'cs_f1e1d6fea0e454eecc82a689101d791e5e5721d4', $options);
            $data_prod = $client->products->get(null, array('filter' => ['sku' => $this->input->post('codigo')]));
            if(isset($data_prod['products'][0]['stock_quantity']))
            { 
                $client->products->update($data_prod['products'][0]['id'],array('stock_quantity' =>($data_prod['products'][0]['stock_quantity'] + $this->input->post('cantidad'))));
                echo $data_prod['products'][0]['stock_quantity'] + $this->input->post('cantidad');
                echo "<br>";
                echo $data_prod['products'][0]['stock_quantity'];
                //var_dump($data_prod['products']);
            }*/
        }
    }
    
    public function consultarWC()
    {
        if ($this->session->userdata('base_dato') == 'vendty2_db_1493_admon2015_no') {
            $options = array(
                'debug' => false,
                'return_as_array' => true,
                'validate_url' => false,
                'timeout' => 80,
                'ssl_verify' => false,
                'version' => 2,
            );

           // $client = new WC_API_Client('http://alibabaonline.com.co', 'ck_cfa8a5a7305eef0828705f4fd2a1f894bb122458', 'cs_013c7feb51a47f31378ad68ffb36f2f23eabd9bf', $options);
           // $data = $client->products->get(null, array('filter' => ['sku' => $_POST['codigo']]));
            return $data;
        }
    }
    
    
}

?>