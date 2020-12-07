<?php
require_once 'payu-php-sdk/lib/PayU.php';

class Payu {
    var $apikey = '8M2CEhNk2zbEQ89G9tf75qYzKT';
    var $apiLogin = 'a00dXf7p22gWqaw';
    public function __construct(){
        PayU::$apiKey = $this->apikey; //Ingrese aquí su propio apiKey.
        PayU::$apiLogin = $this->apiLogin; //Ingrese aquí su propio apiLogin.
        PayU::$merchantId = "537208"; //Ingrese aquí su Id de Comercio.
        PayU::$language = SupportedLanguages::ES; //Seleccione el idioma.
        PayU::$isTest = true; //Dejarlo True cuando sean pruebas.

        // URL de Pagos
        Environment::setPaymentsCustomUrl("https://sandbox.api.payulatam.com/payments-api/4.0/service.cgi");
        // URL de Consultas
        Environment::setReportsCustomUrl("https://sandbox.api.payulatam.com/reports-api/4.0/service.cgi");
        // URL de Suscripciones para Pagos Recurrentes
        Environment::setSubscriptionsCustomUrl("https://sandbox.api.payulatam.com/payments-api/rest/v4.3/");
    }

    public function ping(){
        $response = PayUReports::doPing();
        $response -> code;
    }
}