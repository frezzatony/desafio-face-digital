<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendas{
   
	private $CI;
    private const COLUNA_CNPJ = 'A';
    private const COLUNA_RAZAO_SOCIAL = 'B';
    private const COLUNA_ENDERECO = 'C';
    private const COLUNA_CIDADE = 'D';
    private const COLUNA_UF = 'E';
    private const COLUNA_CEP = 'F';
    private const COLUNA_DATA_VENDA = 'G';
    private const COLUNA_COD_SERVICO = 'H';
    private const COLUNA_HORAS_TRABALHADAS = 'j';
    private const COLUNA_VALOR_FATURADO = 'K';
    private const COLUNA_VALOR_CUSTO = 'L';

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
		
        $this->CI->load->model('servicos_model');
        
        
        
		$caminhoArquivo = $this->CI->config->item('vendas_path_upload_fotos').'/'.$this->CI->config->item('vendas_nome_arquivo_upload').'.'.$arrProp['extensao'];

		if(!file_exists($caminhoArquivo)){
			return array(
                'status'    =>  FALSE,
                'message'   =>  'Arquivo inexistente'
            );
		}
		
        
        try {
            
            $classReader = '\PhpOffice\PhpSpreadsheet\Reader\\'.ucfirst($arrProp['extensao']);
            $reader = new $classReader();
            $reader->setReadDataOnly(TRUE);
            $spreadsheet = $reader->load($caminhoArquivo);
            
        } catch(\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return array(
                'status'    =>  FALSE,
                'message'   =>  'Não foi possível fazer a leitura do arquivo. Refaça a operação ou contate o administrador'
            );
        }
		
        $spreadsheet = $spreadsheet->getSheet(0);
        
        $lastRowPlanilha = $spreadsheet->getHighestRow();
        
        
        $arrIdsServicosPlanilha = array();
        
        //percorre as linhas da planilha para montar array de ids de serviços informados.
        foreach($spreadsheet->getRowIterator() as $keyRow => $row){
            
            if($keyRow == 1 AND $arrProp['cabecalho_primeira_linha']??NULL){
                continue;
            }
            
            $tempValue = (int)$spreadsheet->getCell($this::COLUNA_COD_SERVICO.$keyRow)->getValue();
            
            if($tempValue AND !in_array($tempValue,$arrIdsServicosPlanilha)){
                $arrIdsServicosPlanilha[] = $tempValue;    
            }
        }
        
        $arrDataServicos = $this->CI->servicos_model->get(
            array(
                'where_in'  =>  array(
                    array(
                        'column'    =>  'id',
                        'values'    =>  $arrIdsServicosPlanilha
                    )
                ) 
            )
        );      
        
        $arrIdsServicosValidos = array_map(fn($valor) => $valor['id'], $arrDataServicos); //mantem apenas os valores das colunas id encontrados na pesquisa
        
		$arrData = array();
		$arrErrors = array();

		//os dados da planilha são aglutinados em um array composto dos dados do cliente com respectivas vendas
		foreach($spreadsheet->getRowIterator() as $keyRow => $row){
			
            if($keyRow == 1 AND $arrProp['cabecalho_primeira_linha']??NULL){
                continue;
            }
            
            $erroLinha = false;
            
			//verifica se a linha é válida a partir de um cnpj válido
			$cnpj = Brazanation\Documents\Cnpj::createFromString(preg_replace('/[^0-9]/', '', trim($spreadsheet->getCell($this::COLUNA_CNPJ.$keyRow)->getFormattedValue())));
			
			if(!$cnpj){ //nao possuium cnpj valido, a linha nao sera importada e os dados do cliente nao serao atualizados
				$arrErrors[] = array(
					'row'		=>	$keyRow,
					'error'	=> 'A coluna CNPJ não possui um documento válido',
				);
				continue;
			}
            
			$keyCnpj = array_search($cnpj->number,array_column($arrData,'cnpj'));
			
			$arrCliente = array(
				'cnpj'				=>	$cnpj->number,
				'razao_social'		=>	trim($spreadsheet->getCell($this::COLUNA_RAZAO_SOCIAL.$keyRow)->getFormattedValue()),
				'endereco'			=>	trim($spreadsheet->getCell($this::COLUNA_ENDERECO.$keyRow)->getFormattedValue()),
				'localidade'        =>	trim($spreadsheet->getCell($this::COLUNA_CIDADE.$keyRow)->getFormattedValue()),
				'uf'				=>	substr(trim($spreadsheet->getCell($this::COLUNA_UF.$keyRow)->getFormattedValue()),0,2),
				'cep'				=>	preg_replace('/[^0-9]/', '',trim($spreadsheet->getCell($this::COLUNA_CEP.$keyRow)->getFormattedValue())),
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

            //verificacao servico informado
            $codServico = (int)trim($spreadsheet->getCell($this::COLUNA_COD_SERVICO.$keyRow)->getFormattedValue());
            if(!in_array($codServico,$arrIdsServicosValidos)){ //este erro nao impede de atualizar os dados do cliente, porem a venda nao sera importada
                $arrErrors[] = array(
					'row'	=>	$keyRow,
					'error'	=>	'A coluna Código Serviço não possui um serviço cadastrado',
				);
                $erroLinha = TRUE;
            }
            
            
            //verificacao da data de venda
			$dataVenda = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($spreadsheet->getCell($this::COLUNA_DATA_VENDA.$keyRow)->getFormattedValue());
			if(!$dataVenda){ //este erro nao impede de atualizar os dados do cliente, porem a venda nao sera importada
				$arrErrors[] = array(
					'row'	=>	$keyRow,
					'error'	=>	'A coluna Data Venda não possui uma data válida',
				);
                $erroLinha = TRUE;
			}
            else{
                $dataVenda = $dataVenda->format('Y-m-d');
            }
			
            
            //verificacao de horas trabalhadas
            $horasTrabalhadas = trim($spreadsheet->getCell($this::COLUNA_HORAS_TRABALHADAS.$keyRow)->getFormattedValue());
            if($horasTrabalhadas AND !is_numeric($horasTrabalhadas)){ //este erro nao impede de atualizar os dados do cliente, porem a venda nao sera importada
               
               $arrErrors[] = array(
					'row'	=>	$keyRow,
					'error'	=>	'A coluna Horas Trabalhadas não possui um valor válido',
				);
                $erroLinha = TRUE;
            }
            
            //verificacao de valor faturado
            $valorFaturado = str_replace(',','',trim($spreadsheet->getCell($this::COLUNA_VALOR_FATURADO.$keyRow)->getFormattedValue()));
            if($valorFaturado AND !is_numeric($valorFaturado)){ //este erro nao impede de atualizar os dados do cliente, porem a venda nao sera importada
               
               $arrErrors[] = array(
					'row'	=>	$keyRow,
					'error'	=>	'A coluna Valor Faturado não possui um valor válido',                    
				);
                $erroLinha = TRUE;
            }
            
            //verificacao de valor custo
            $valorCusto = str_replace(',','',trim($spreadsheet->getCell($this::COLUNA_VALOR_CUSTO.$keyRow)->getFormattedValue()));
            if($valorCusto AND !is_numeric($valorCusto)){ //este erro nao impede de atualizar os dados do cliente, porem a venda nao sera importada
               
               $arrErrors[] = array(
					'row'	=>	$keyRow,
					'error'	=>	'A coluna Valor Faturado não possui um valor válido',
				);
                $erroLinha = TRUE;
            }
            
            
            //sem erros na linha, a venda poderá ser importada
            if(!$erroLinha){
                $arrData[$keyCnpj]['vendas'][] = array(
    				'dt_venda'          =>	$dataVenda??NULL,
                    'servicos_id'       =>  $codServico,
                    'horas_trabalhadas' =>  number_format($horasTrabalhadas,2,'.',''),
                    'valor_faturado'    =>  number_format($valorFaturado,2,'.',''),
                    'valor_custo'       =>  number_format($valorCusto,2,'.',''),
    			);    
            }
		}
        
		return array(
            'data'      =>  $arrData,
            'errors'    =>  $arrErrors,
        );
	}
     
}
