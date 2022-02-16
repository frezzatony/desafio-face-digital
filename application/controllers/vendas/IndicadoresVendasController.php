<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IndicadoresVendasController extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	public function cliente()
	{
		$this->load->model('vendas_model');
        
		$this->template->load('templates/vendas/template','vendas/indicador_clientes_view');
	}
	
}
