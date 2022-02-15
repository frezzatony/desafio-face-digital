<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Clientes_model extends CI_Model
{
    
    private $table = 'clientes';
    
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
