<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Vendas_model extends CI_Model
{
    
    private $table = 'vendas';
    
    function __construct(){
        parent::__construct();
    }
    
    public function get($arrProp = array())
    {
        
        if($arrProp['limit']??NULL){
            $this->db->limit(
                $arrProp['limit'][0],
                $arrProp['limit'][1]??NULL
            ); 
            
        }
        
        if($arrProp['where']??NULL){
            foreach($arrProp['where'] as $where){
                $this->db->where($where['column'],$where['value']);
            }
        }
        
        
        $this->db->select(
            array(
                $this->table.'.id',
                'clientes.id as cliente_id',
                'clientes.razao_social as cliente_razao_social',
				'servicos.id as servico_id','servicos.descricao',
				$this->table.'.dt_venda',$this->table.'.horas_trabalhadas',
				$this->table.'.valor_faturado',$this->table.'.valor_custo',
				'('.$this->table.'.valor_faturado - '.$this->table.'.valor_custo) as resultado_venda',
            )
        );
        
        $this->db->from($this->table);
        $this->db->join('clientes',$this->table.'.clientes_id = clientes.id');
		$this->db->join('servicos',$this->table.'.servicos_id = servicos.id');
        $query = $this->db->get();
        
        return $query->result_array();
    }
    
    public function getCountVendas()
    {
       
        $this->db->from($this->table);
        return $this->db->count_all_results();
        
    }
    
    public function save($arrData = array())
    {
        
        if($arrData['id']??NULL){ //update
            $this->db->where('id',$arrData['id']);
            $this->db->update($this->table,$arrData);
        }
        else{ //insert
            $this->db->insert($this->table,$arrData);
        }
        
        $this->db->trans_complete();
        
        if ($this->db->trans_status() === FALSE){
            return FALSE;
        }
        
        return ($arrData['id']??NULL) ? $arrData['id'] : $this->db->insert_id('pacientes_id_seq');
        
    }
    
    public function getIndicadorPorCliente($arrProp = array())
    {
        
        if($arrProp['limit']??NULL){
            $sqlLimit = 'LIMIT ';
            $sqlLimit .= $arrProp['limit'][0];
            $sqlLimit .= ($arrProp['limit'][1]??NULL) ? ','.$arrProp['limit'][1] : NULL;
        }
        
        //indicador de vendas por cliente
        $sql = 
        '
            SELECT clientes.id as cliente_id, clientes.razao_social as cliente_razao_social, 
            SUM(vendas.horas_trabalhadas) as horas_trabalhadas,
            SUM(vendas.valor_faturado) as valor_faturado,
            SUM(vendas.valor_custo) as valor_custo,
            SUM(vendas.valor_faturado-vendas.valor_custo) as resultado_venda
            FROM vendas 
            JOIN clientes ON vendas.clientes_id = clientes.id 
            GROUP BY vendas.clientes_id
            '.($sqlLimit??NULL).' 
        ';
        $query = $this->db->query($sql);
        $dataIndicador = $query->result_array();        
        
        
        //totalizador de clientes para paginacao
        $sql = '
            SELECT COUNT(DISTINCT clientes_id) as total_clientes
            FROM vendas            
        ';
        $query = $this->db->query($sql);
        $rowTotalClientes = $query->row();
		        
        return array(
            'data'  =>  $dataIndicador,
            'count' =>  (int) $rowTotalClientes->total_clientes
        );    
    }
    
    
    public function getIndicadorPorServico($arrProp = array())
    {
        
        if($arrProp['limit']??NULL){
            $sqlLimit = 'LIMIT ';
            $sqlLimit .= $arrProp['limit'][0];
            $sqlLimit .= ($arrProp['limit'][1]??NULL) ? ','.$arrProp['limit'][1] : NULL;
        }
        
        //indicador de vendas por servico
        $sql = 
        '
            SELECT servicos.id as servico_id, servicos.descricao as servico_descricao,
            SUM(vendas.horas_trabalhadas) as horas_trabalhadas,
            SUM(vendas.valor_faturado) as valor_faturado,
            SUM(vendas.valor_custo) as valor_custo,
            SUM(vendas.valor_faturado-vendas.valor_custo) as resultado_venda
            FROM vendas 
            JOIN servicos ON vendas.servicos_id = servicos.id
            GROUP BY vendas.servicos_id
            '.($sqlLimit??NULL).' 
        ';
        $query = $this->db->query($sql);
        $dataIndicador = $query->result_array();        
        
        
        //totalizador de servicos para paginacao
        $sql = '
            SELECT COUNT(DISTINCT servicos_id) as total_servicos
            FROM vendas            
        ';
        $query = $this->db->query($sql);
        $rowTotalServicos = $query->row();
		        
        return array(
            'data'  =>  $dataIndicador,
            'count' =>  (int) $rowTotalServicos->total_servicos
        );    
    }
    
    public function getIndicadorMensal($arrProp = array())
    {
        
        if($arrProp['limit']??NULL){
            $sqlLimit = 'LIMIT ';
            $sqlLimit .= $arrProp['limit'][0];
            $sqlLimit .= ($arrProp['limit'][1]??NULL) ? ','.$arrProp['limit'][1] : NULL;
        }
        
        //indicador de vendas mensais
        $sql = 
        '
            SELECT
            DATE_FORMAT(dt_venda, "%Y-%m-01") as data_referencia,
            SUM(vendas.horas_trabalhadas) as horas_trabalhadas,
            SUM(vendas.valor_faturado) as valor_faturado,
            SUM(vendas.valor_custo) as valor_custo,
            SUM(vendas.valor_faturado-vendas.valor_custo) as resultado_venda
            FROM vendas
            GROUP BY YEAR(dt_venda), MONTH(dt_venda)
            ORDER BY dt_venda
            '.($sqlLimit??NULL).' 
        ';
        $query = $this->db->query($sql);
        $dataIndicador = $query->result_array();        
        
        
        //totalizador de meses para paginacao
        $sql = '
            SELECT COUNT(DISTINCT DATE_FORMAT(dt_venda, "%Y-%m")) as total_meses
            FROM vendas            
        ';
        $query = $this->db->query($sql);
        $rowTotalMeses = $query->row();
		        
        return array(
            'data'  =>  $dataIndicador,
            'count' =>  (int) $rowTotalMeses->total_meses
        );    
    }
    
}
