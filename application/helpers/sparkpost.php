<?php
require 'vendor/autoload.php';
use SparkPost\SparkPost as Spark;
use GuzzleHttp\Client;
use Http\Adapter\Guzzle6\Client as GuzzleAdapter;

class SparkPost {
    public function __construct(){
        $httpClient = new GuzzleAdapter(new Client());
        $sparky = new Spark($httpClient, ['key'=>'17edb4576073927816ff88b90c1cef68b16d0c63']);
    }

    public function sendMail(){
        $promise = $sparky->transmissions->post([
        'content' => [
        'from' => [
            'name' => 'SparkPost Team',
            'email' => 'from@sparkpostbox.com',
        ],
        'subject' => 'First Mailing From PHP',
        'html' => '<html><body><h1>Congratulations, {{name}}!</h1><p>You just sent your very first mailing!</p></body></html>',
        'text' => 'Congratulations, {{name}}!! You just sent your very first mailing!',
        ],
        'substitution_data' => ['name' => 'YOUR_FIRST_NAME'],
        'recipients' => [
        [
            'address' => [
                'name' => 'YOUR_NAME',
                'email' => 'YOUR_EMAIL',
            ],
        ],
        ],
        'cc' => [
        [
            'address' => [
                'name' => 'ANOTHER_NAME',
                'email' => 'ANOTHER_EMAIL',
            ],
        ],
        ],
        'bcc' => [
        [
            'address' => [
                'name' => 'Angel Gonzalez',
                'email' => 'angeledugo@gmail.com',
            ],
        ],
        ],
        ]);
    }
}