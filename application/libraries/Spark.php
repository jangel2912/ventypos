<?php
require 'vendor/autoload.php';
use SparkPost\SparkPost;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;


class Spark {
    var $key = '17edb4576073927816ff88b90c1cef68b16d0c63';
    var $sparky;
    public function __construct(){
        $httpClient = new GuzzleAdapter(new Client());
        $this->sparky = new SparkPost($httpClient, ['key'=>$this->key,"debug" => true]);
    }

    public  function sendEmailFacturaVencida($info){ 
       $email = [];      
        foreach($info as $emails){
            $email = [
                        "address" => [
                            "name" => $emails['name'],
                            "email" => $emails['email']
                        ],
                         "substitution_data" => [
                            "name" => $emails['name'],
                            "empresa" => $emails['empresa'],
                            "fecha_pago" => $emails['fecha_pago'],
                            "monto_pago" => $emails['monto'],
                            "subject" => "Almacen ".$emails['almacen'].' esta proxima a vencer '.$emails['fecha_pago'],
                         ],
                         
            ]; 
        }

       $promise = $this->sparky->transmissions->post([
                'content' => [ 
                    'template_id' => 'factura-vencida', 
                ],
                'substitution_data' => ['name' => 'YOUR_FIRST_NAME'],
                'recipients' => [
                    $email
                ],
            ]);
        try {
            $response = $promise->wait();
            echo $response->getStatusCode()."\n";
            print_r($response->getBody())."\n";
        } catch (\Exception $e) {
            echo $e->getCode()."\n";
            echo $e->getMessage()."\n";
        }
    }
}
