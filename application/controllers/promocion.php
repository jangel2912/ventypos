<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Promocion extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Mostramos la Interfaz para crear una promoción
     *
     * @author Rafael Moreno
     */
    public function crear()
    {
        $this->checkLogin();

        $this->layout->template('member')->show('promocion/create');
    }

    /**
     * Guardamos o actualizamos una promocion
     *
     * @author Rafael Moreno
     */
    public function store()
    {
        $promotion = post_curl('promotions', json_encode(axios()), $this->session->userdata('token_api'));
        echo json_encode($promotion);
    }

    /**
     * Mostramos la Interfaz para modificar una promoción
     *
     * @param int $id ID de la promoción que queremos editar
     *
     * @author Rafael Moreno
     */
    public function editar($id)
    {
        $this->checkLogin();

        $this->layout->template('member')->show('promocion/update');
    }

    /**
     * Consultamos los Productos
     *
     * @author Rafael Moreno
     */
    public function products()
    {
        $products = get_curl('products', $this->session->userdata('token_api'));

        echo json_encode($products->data);
    }

    /**
     * Consultamos los almacenes
     *
     * @author Rafael Moreno
     */
    public function warehouses()
    {
        $warehouses = get_curl('warehouses', $this->session->userdata('token_api'));

        echo json_encode($warehouses->data);
    }

    /**
     * Consultamos los detalles de un producto
     *
     * @param int $product ID del producto que queremos consultar
     *
     * @author Rafael Moreno
     */
    public function product($product)
    {
        $product = get_curl('products/' . $product, $this->session->userdata('token_api'));

        echo json_encode($product->data);
    }

    /**
     * Consultamos los detalles de una promoción
     *
     * @param int $id ID de la promoción que queremos consultar
     *
     * @author Rafael Moreno
     */
    public function get_promotion($id)
    {
        $promotion = get_curl('promotions/' . $id, $this->session->userdata('token_api'));
        echo json_encode($promotion->data);
    }
}