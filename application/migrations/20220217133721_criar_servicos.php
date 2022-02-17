<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Criar_Servicos extends CI_Migration {

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
    				'descricao'	=>	array(
    					'type'         =>	'VARCHAR',
    					'constraint'   =>	100,
                        'null'         =>   FALSE, 
    				),
                    'valor_hora'    =>  array(
                        'type'         =>	'DECIMAL(15,2)',
                        'null'         =>   FALSE, 
                    ),
    			)
		      );
              
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('servicos',TRUE,array('ENGINE' => 'InnoDB'));
            
            $this->db->insert('servicos',array('descricao'=>'ABC'));
            $this->db->insert('servicos',array('descricao'=>'XYZ'));
            $this->db->insert('servicos',array('descricao'=>'KTM'));
            
            
            
        }

        public function down()
        {
            $this->dbforge->drop_table('servicos',TRUE);
        }
}