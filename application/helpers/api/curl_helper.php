<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include 'environment.php';

function get_curl($method, $token)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, API_VENDTY . $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer $token",
    ));

    $response = curl_exec($ch);

    curl_close($ch);

    return json_decode($response);
}

function post_curl($method, $data, $token = null)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, API_VENDTY . $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);

    if ($method != 'login') {
        if ($token != null) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Authorization: Bearer $token",
                "Content-Type: application/json",
            ));
        } else {
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
            ));
        }
    }

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    curl_close($ch);

    return json_decode($response);
}

function put_curl($method, $data, $token = null)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, API_VENDTY . $method);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_HEADER, false);

    if ($token != null) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer $token",
            "Content-Type: application/json",
        ));
    } else {
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
        ));
    }

    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);

    curl_close($ch);

    return json_decode($response);
}
