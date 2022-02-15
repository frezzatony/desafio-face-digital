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

		return array(
			'status'	=>	TRUE
		);
	}
}
