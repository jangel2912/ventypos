<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    
    
    function get_curl_chartmogul($method){
        
        $ch = curl_init();
    
        curl_setopt($ch, CURLOPT_URL,API_CHARTMOGUL.$method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Basic ".AUTHCHARTMOGUL,
        ));

        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response);
    }


    function post_curl_chartmogul($method,$data){

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, API_CHARTMOGUL.$method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        
        curl_setopt($ch, CURLOPT_HEADER, false);
        
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Basic ".AUTHCHARTMOGUL,
                "Content-Type: application/json"
                ));

        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);

        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response);
    }


?>