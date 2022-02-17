<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Install extends CI_Controller {

	function __construct()
	{
		parent::__construct();
        
	}

	public function index()
	{
		$this->load->library('migration');
        
		$this->migration->latest();
        
		if ($this->migration->current() === FALSE){
            show_error($this->migration->error_string());
		}
        else{
            echo '<p>Banco de dados gerado</p>';    
        }
        
        
	}

}

?>