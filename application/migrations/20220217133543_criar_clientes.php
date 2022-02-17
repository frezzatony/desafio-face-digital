<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Criar_Clientes extends CI_Migration {

        public function up()
        {
            $this->load->database();
            
            $this->dbforge->add_field(
    			array(
    				'id'	=>	array(
    					'type'	            =>	'INT',
    					'null'	            =>	FALSE,
                        'auto_increment'    => TRUE
    				),
                    'cnpj'   =>  array(
                        'type'          =>  'VARCHAR',
                        'constraint'    =>  14,
                        'null'          =>  FALSE,
                    ),
    				'razao_social'	=>	array(
    					'type'         =>	'VARCHAR',
    					'constraint'   =>	150,
                        'null'         =>   FALSE, 
    				),
                    'cep'  =>  array(
                        'type'          =>  'VARCHAR',
                        'constraint'    =>  8,
                    ),
                    'endereco'   =>  array(
                        'type'         =>	'VARCHAR',
    					'constraint'   =>	150,
                    ),
                    'localidade'   =>  array(
                        'type'         =>	'VARCHAR',
    					'constraint'   =>	100, 
                    ),
                    'uf'   =>  array(
                        'type'         =>	'VARCHAR',
    					'constraint'   =>	2,
                        'null'         =>   FALSE,
                    
                    ),
    			)
            );
              
            $this->dbforge->add_key('id', TRUE);            
            $this->dbforge->create_table('clientes',TRUE,array('ENGINE' => 'InnoDB'));
            
        }

        public function down()
        {
            $this->dbforge->drop_table('clientes',TRUE);
        }
}