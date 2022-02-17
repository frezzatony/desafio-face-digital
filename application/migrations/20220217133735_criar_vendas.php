<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Criar_Vendas extends CI_Migration {

        public function up()
        {
            $this->load->database();
            
            $this->dbforge->add_field(
    			array(
    				'id'	=>	array(
    					'type'	            =>	'INT',
    					'null'	            =>	FALSE,
                        'auto_increment'    =>  TRUE
    				),
                    'clientes_id'	=>	array(
    					'type'	=>	'INT',
    					'null'	=>	FALSE,
    				),
                    'servicos_id'	=>	array(
    					'type'	=>	'INT',
    					'null'	=>	FALSE,
    				),
                    'dt_venda'  =>  array(
                        'type'         =>	'DATE',
                        'null'         =>   FALSE, 
                    ),
                    'horas_trabalhadas'  =>  array(
                        'type'         =>	'DECIMAL(15,2)',
                        'null'         =>   FALSE, 
                    ),
    				'valor_faturado'  =>  array(
                        'type'         =>	'DECIMAL(15,2)',
                        'null'         =>   FALSE, 
                    ),
                    'valor_custo'  =>  array(
                        'type'         =>	'DECIMAL(15,2)',
                        'null'         =>   FALSE, 
                    ),
                    'uuid_importacao'  =>  array(
                        'type'         =>	'VARCHAR',
    					'constraint'   =>	36,
                        'null'         =>   FALSE, 
                    ),
                    
    			)
		      );
              
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('vendas',TRUE,array('ENGINE' => 'InnoDB'));
            
            $this->db->query('ALTER TABLE vendas ADD CONSTRAINT vendas_fk_clientes FOREIGN KEY (clientes_id) REFERENCES clientes(id) ON DELETE RESTRICT ON UPDATE CASCADE;');
            $this->db->query('ALTER TABLE vendas ADD CONSTRAINT vendas_fk_servicos FOREIGN KEY (servicos_id) REFERENCES servicos(id) ON DELETE RESTRICT ON UPDATE CASCADE;');
                        
        }

        public function down()
        {
            $this->dbforge->drop_table('vendas',TRUE);
        }
}