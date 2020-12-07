<?php


class Upload_invoice_model extends CI_Model
{
	var $connection;

	public function __construct(){

		parent::__construct();
        }

        public function initialize($connection){

            $this->connection = $connection;
        }
}