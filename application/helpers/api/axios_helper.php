<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Recibe el REQUEST que viene desde axios y lo convierte en un Objeto de PHP
 *
 * @return mixed
 */
function axios()
{
    return json_decode(file_get_contents("php://input"), true);
}