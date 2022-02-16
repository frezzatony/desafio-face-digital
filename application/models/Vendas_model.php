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
    
    public function getCountItems()
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
    
    public function validate($arrProp = array())
    {
        
    }
    
}
