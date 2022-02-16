<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Servicos_model extends CI_Model
{
    
    private $table = 'servicos';
    
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
        
        if($arrProp['where_in']??NULL){
            foreach($arrProp['where_in'] as $where_in){
                $this->db->where_in($where_in['column'],$where_in['values']);
            }
        }
        
        $this->db->from($this->table);
        $query = $this->db->get();
        
        return $query->result_array();
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
