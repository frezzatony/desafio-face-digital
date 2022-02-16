<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class VendasController extends CI_Controller {

	function __construct(){
		parent::__construct();
	}

	public function index()
	{
		$this->load->model('vendas_model');

		$this->template->load('templates/vendas/template','vendas/main_view');
	}
	public function importar()
	{
		
		$this->load->config('vendas');
		$this->load->library('vendas');
        
        
        $this->load->model('clientes_model');
        $this->load->model('vendas_model');
		
		$arrImportPlanilha = $this->vendas->uploadPlanilha();
        
        if(!$arrImportPlanilha['status']){
			$this->session->set_flashdata('error_import', $arrImportPlanilha['message']);
			$this->template->load('templates/default/template','vendas/importacao_view');
            return;
		}
		
        
        $arrImportPlanilha['cabecalho_primeira_linha'] = (bool)$this->input->get_post('cabecalho');
        
		$arrDataImportacao = $this->vendas->getDataPlanilha($arrImportPlanilha);
        
        $UUIDImportacao = \Ramsey\Uuid\Uuid::uuid4();
        
        foreach($arrDataImportacao['data'] as $rowImportacao){
             $arrDataCliente = $rowImportacao;
             unset($arrDataCliente['vendas']);
             
             $idCliente = $this->clientes_model->importar($arrDataCliente);
             
             foreach($rowImportacao['vendas']??array() as $venda){
                $venda['clientes_id'] = $idCliente;
                $venda['uuid_importacao'] = $UUIDImportacao;
                $this->vendas_model->save($venda);
             }     
        }
        
        if(!$arrDataImportacao['errors']){
            $this->session->set_flashdata('success', 'Importação realizada sem erros.');    
        }
        else{
            $this->session->set_flashdata('warning', 'Importação realizada com erros.');
            $this->session->set_flashdata('errors', $arrDataImportacao['errors']);
        }
        
        
		
        $this->template->load('templates/default/template','vendas/importacao_view');
	}
}
