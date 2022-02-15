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
		
		$arrImportPlanilha = $this->vendas->uploadPlanilha();

		if(!$arrImportPlanilha['status']){
			$this->session->set_flashdata('error', $arrImportPlanilha['message']);
			redirect(BASE_URL.'index.php/vendas','refresh');
		}
		
		$arrDataImportacao = $this->vendas->getDataPlanilha($arrImportPlanilha);

		print_R($arrDataImportacao); exit;

		$this->session->set_flashdata('success', 'Importação realizada.');
		redirect(BASE_URL.'index.php/vendas','refresh');
	}
}
