<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Woocommerce
 */
class Woocommerce extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     *
     */
    public function generate_credentials()
    {
        $credenciales = get_curl('woocommerce/generate-credentials', $this->session->userdata('token_api'));

        echo json_encode($credenciales);
    }
}