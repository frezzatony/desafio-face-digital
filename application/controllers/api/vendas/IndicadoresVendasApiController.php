<?php
defined('BASEPATH') OR exit('No direct script access allowed');

include APPPATH.'libraries/REST_Controller.php';

class IndicadoresVendasApiController extends REST_Controller
{
    
    function __construct()
    {
        parent::__construct();
    }
    
    public function clientes_get()
	{
        
        $this->load->model('vendas_model');
        
        $arrProp = array(
            'limit'     =>  array(10,0), //evita sobrecarga no banco, exibindo apenas os primeiros 10 registros,
        );
        
        
        if(is_numeric($this->input->get('start')) AND is_numeric($this->input->get('length'))){
            
            $arrProp['limit'] = array(
                $this->input->get('length'),
                $this->input->get('start'),
            );  
        }
        
        $dataItems = $this->vendas_model->getIndicadorPorCliente($arrProp);
           
        $arrData = array(
            'recordsTotal'      =>  count($dataItems['data']),
            'recordsFiltered'   =>  $dataItems['count'],
            'data'              =>  $dataItems['data'],
        );
        
        
        $this->response(
            $arrData,
            REST_Controller::HTTP_OK
        );
        
    }
    
    public function servicos_get()
	{
        
        $this->load->model('vendas_model');
        
        $arrProp = array(
            'limit'     =>  array(10,0), //evita sobrecarga no banco, exibindo apenas os primeiros 10 registros,
        );
        
        
        if(is_numeric($this->input->get('start')) AND is_numeric($this->input->get('length'))){
            
            $arrProp['limit'] = array(
                $this->input->get('length'),
                $this->input->get('start'),
            );  
        }
        
        $dataItems = $this->vendas_model->getIndicadorPorServico($arrProp);
           
        $arrData = array(
            'recordsTotal'      =>  count($dataItems['data']),
            'recordsFiltered'   =>  $dataItems['count'],
            'data'              =>  $dataItems['data'],
        );
        
        
        $this->response(
            $arrData,
            REST_Controller::HTTP_OK
        );
        
    }
    
    public function mensal_get()
	{
        
        $this->load->model('vendas_model');
        
        $arrProp = array(
            'limit'     =>  array(10,0), //evita sobrecarga no banco, exibindo apenas os primeiros 10 registros,
        );
        
        
        if(is_numeric($this->input->get('start')) AND is_numeric($this->input->get('length'))){
            
            $arrProp['limit'] = array(
                $this->input->get('length'),
                $this->input->get('start'),
            );  
        }
        
        $dataItems = $this->vendas_model->getIndicadorMensal($arrProp);
           
        $arrData = array(
            'recordsTotal'      =>  count($dataItems['data']),
            'recordsFiltered'   =>  $dataItems['count'],
            'data'              =>  $dataItems['data'],
        );
        
        
        $this->response(
            $arrData,
            REST_Controller::HTTP_OK
        );
        
    }
}

?>