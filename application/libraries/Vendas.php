<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendas{
   
	private $CI;

	function __construct()
	{
		$this->CI = &get_instance();
	}

	public function uploadPlanilha()
	{
		
		// excluir arquivos previos
		foreach($this->CI->config->item('vendas_extensos_validas_upload') as $extensao){
			if(file_exists($this->CI->config->item('vendas_path_upload_fotos').'/'.$this->CI->config->item('vendas_nome_arquivo_upload').'.'.$extensao)){
				unlink(($this->CI->config->item('vendas_path_upload_fotos').'/'.$this->CI->config->item('vendas_nome_arquivo_upload').'.'.$extensao));
			}
		}


		$arrConfig = array(
			'upload_path'   =>  $this->CI->config->item('vendas_path_upload_fotos'),
			'file_name'     =>  $this->CI->config->item('vendas_nome_arquivo_upload'),
			'allowed_types' =>  implode('|',$this->CI->config->item('vendas_extensos_validas_upload')),
			'max_size'      =>  $this->CI->config->item('vendas_max_sizefile_arquivo_importacao'),
		);

		$this->CI->load->library('upload', $arrConfig);
		if(!$this->CI->upload->do_upload('arquivo')){

			return array(
				'status'		=>	FALSE,
				'message'	=>	$this->CI->upload->display_errors('','')
			);
		}

		$nomeArquivo= $this->CI->upload->data('file_name');
		$extensaoArquivo = pathinfo($nomeArquivo,PATHINFO_EXTENSION);

		return array(
			'status'		=>	TRUE,
			'extensao'	=>	$extensaoArquivo,
		);
	}

	public function getDataPlanilha($arrProp = array())
	{	
		
		$caminhoArquivo = $this->CI->config->item('vendas_path_upload_fotos').'/'.$this->CI->config->item('vendas_nome_arquivo_upload').'.'.$arrProp['extensao'];

		if(!file_exists($caminhoArquivo)){
			return FALSE;
		}
		
		$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($caminhoArquivo);
		$spreadsheet = $spreadsheet->getSheet(0);
		$dataPlanilha = $spreadsheet->toArray();

		$arrData = array();
		$arrErrors = array();

		//os dados da planilha são aglutinados em um array composto dos dados do cliente com respectivas vendas

		foreach($dataPlanilha as $keyRow => $row){
			
			//verifica se a linha é válida a partir de um cnpj válido
			$cnpj = Brazanation\Documents\Cnpj::createFromString(preg_replace('/[^0-9]/', '', $row[0]));
			
			if(!$cnpj){
				$arrErrors[] = array(
					'row'		=>	$keyRow,
					'error'	=> 'A coluna CNPJ não possui um documento válido',
				);
				continue;
			}

			$keyCnpj = array_search($cnpj->number,array_column($arrData,'cnpj'));
			
			$arrCliente = array(
				'cnpj'				=>	$cnpj->number,
				'razao_social'		=>	trim($row[1]),
				'endereco'			=>	trim($row[2]),
				'cidade'				=>	trim($row[3]),
				'uf'					=>	trim($row[4]),
				'cep'					=>	preg_replace('/[^0-9]/', '', $row[5]),
			);
			if($keyCnpj===FALSE){
				$arrData[] = $arrCliente;
				$keyCnpj = array_key_last($arrData);
			}
			else{
				$arrData[$keyCnpj] = array_merge($arrData[$keyCnpj],$arrCliente);
			}

			if(!isset($arrData[$keyCnpj]['vendas'])){
				$arrData[$keyCnpj]['vendas'] = array();
			}


			$dataVenda = DateTime::createFromFormat('d/m/Y', $row[6]);

			if(!$dataVenda){
				$arrErrors[] = array(
					'row'		=>	$keyRow,
					'error'	=>	'A coluna Data Venda não possui uma data válida',
				);
			}
			else{
				$dataVenda = $dataVenda->format('Y-m-d');
			}

			$arrData[$keyCnpj]['vendas'][] = array(
				'dt_venda'	=>	$dataVenda,
			);



		}

		return $arrData;


	}
}
