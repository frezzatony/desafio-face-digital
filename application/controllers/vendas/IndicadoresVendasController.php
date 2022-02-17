<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class IndicadoresVendasController extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	public function clientes()
	{        
		$this->template->load('templates/vendas/template','vendas/indicadores/vendas_clientes_view');
	}
    
    public function servicos()
	{        
		$this->template->load('templates/vendas/template','vendas/indicadores/vendas_servicos_view');
	}
    
    public function mensal()
	{        
		$this->template->load('templates/vendas/template','vendas/indicadores/vendas_mensal_view');
	}
	
}
