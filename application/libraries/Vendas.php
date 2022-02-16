<?php 

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Vendas{
   
	private $CI;
    private const COLUNA_CNPJ = 0;
    private const COLUNA_RAZAO_SOCIAL = 1;
    private const COLUNA_ENDERECO = 2;
    private const COLUNA_CIDADE = 3;
    private const COLUNA_UF = 4;
    private const COLUNA_CEP = 5;
    private const COLUNA_DATA_VENDA = 6;
    private const COLUNA_COD_SERVICO = 7;
    private const COLUNA_HORAS_TRABALHADAS = 9;
    private const COLUNA_VALOR_FATURADO = 10;
    private const COLUNA_VALOR_CUSTO = 11;

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
			return FALSE;
		}
		
        
        try {
            
            $classReader = '\PhpOffice\PhpSpreadsheet\Reader\\'.ucfirst($arrProp['extensao']);
            $reader = new $classReader();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($caminhoArquivo);
            
            $spreadsheet = $spreadsheet->getSheet(0);
            
        } catch(\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return FALSE;
        }
		
        $dataPlanilha = $spreadsheet->toArray();
               
        $arrIdsServicosPlanilha = array_column($dataPlanilha,$this::COLUNA_COD_SERVICO);
        $arrIdsServicosPlanilha = array_filter($arrIdsServicosPlanilha, fn($valor) => ($valor??NULL) AND (int)$valor); //filter para manter apenas inteiros informados
        $arrIdsServicosPlanilha = array_unique($arrIdsServicosPlanilha);
               
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
		foreach($dataPlanilha as $keyRow => $row){
			
            if($keyRow == 0 AND $arrProp['cabecalho_primeira_linha']??NULL){
                continue;
            }
            
            $erroLinha = false;
            
			//verifica se a linha é válida a partir de um cnpj válido
			$cnpj = Brazanation\Documents\Cnpj::createFromString(preg_replace('/[^0-9]/', '', trim($row[$this::COLUNA_CNPJ])));
			
			if(!$cnpj){ //nao possuium cnpj valido, a linha nao sera importada e os dados do cliente nao serao atualizados
				$arrErrors[] = array(
					'row'		=>	($keyRow+1),
					'error'	=> 'A coluna CNPJ não possui um documento válido',
                    'value' =>  preg_replace('/[^0-9]/', '', trim($row[$this::COLUNA_CNPJ])),
				);
				continue;
			}
            
			$keyCnpj = array_search($cnpj->number,array_column($arrData,'cnpj'));
			
			$arrCliente = array(
				'cnpj'				=>	$cnpj->number,
				'razao_social'		=>	trim($row[$this::COLUNA_RAZAO_SOCIAL]),
				'endereco'			=>	trim($row[$this::COLUNA_ENDERECO]),
				'localidade'        =>	trim($row[$this::COLUNA_CIDADE]),
				'uf'				=>	substr(trim($row[$this::COLUNA_UF]),0,2),
				'cep'				=>	preg_replace('/[^0-9]/', '', $row[$this::COLUNA_CEP]),
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
            $codServico = (int)trim($row[$this::COLUNA_COD_SERVICO]);
            if(!in_array($codServico,$arrIdsServicosValidos)){ //este erro nao impede de atualizar os dados do cliente, porem a venda nao sera importada
                $arrErrors[] = array(
					'row'	=>	($keyRow+1),
					'error'	=>	'A coluna Código Serviço não possui um serviço cadastrado',
				);
                $erroLinha = TRUE;
            }
            
            
            //verificacao da data de venda
			$dataVenda = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[$this::COLUNA_DATA_VENDA]);
			if(!$dataVenda){ //este erro nao impede de atualizar os dados do cliente, porem a venda nao sera importada
				$arrErrors[] = array(
					'row'	=>	($keyRow+1),
					'error'	=>	'A coluna Data Venda não possui uma data válida',
				);
                $erroLinha = TRUE;
			}
            else{
                $dataVenda = $dataVenda->format('Y-m-d');
            }
			
            
            //verificacao de horas trabalhadas
            $horasTrabalhadas = trim($row[$this::COLUNA_HORAS_TRABALHADAS]);
            if($horasTrabalhadas AND !is_numeric($horasTrabalhadas)){ //este erro nao impede de atualizar os dados do cliente, porem a venda nao sera importada
               
               $arrErrors[] = array(
					'row'	=>	($keyRow+1),
					'error'	=>	'A coluna Horas Trabalhadas não possui um valor válido',
				);
                $erroLinha = TRUE;
            }
            
            //verificacao de valor faturado
            $valorFaturado = str_replace(',','',trim($row[$this::COLUNA_VALOR_FATURADO]));
            if($valorFaturado AND !is_numeric($valorFaturado)){ //este erro nao impede de atualizar os dados do cliente, porem a venda nao sera importada
               
               $arrErrors[] = array(
					'row'	=>	$keyRow,
					'error'	=>	'A coluna Valor Faturado não possui um valor válido',                    
				);
                $erroLinha = TRUE;
            }
            
            //verificacao de valor custo
            $valorCusto = str_replace(',','',trim($row[$this::COLUNA_VALOR_CUSTO]));
            if($valorCusto AND !is_numeric($valorCusto)){ //este erro nao impede de atualizar os dados do cliente, porem a venda nao sera importada
               
               $arrErrors[] = array(
					'row'	=>	($keyRow+1),
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
