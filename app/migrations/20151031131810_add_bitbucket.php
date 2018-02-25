<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_bitbucket extends CI_Migration {

		public function up()
		{

			$data = array();
			
			$enable_bitbucket = $this->db->query( 'SELECT option_name FROM options WHERE option_name="enable_bitbucket" LIMIT 1;' );
			$bitbucket_key = $this->db->query( 'SELECT option_name FROM options WHERE option_name="bitbucket_key" LIMIT 1;' );
			$bitbucket_secret = $this->db->query( 'SELECT option_name FROM options WHERE option_name="bitbucket_secret" LIMIT 1;' );

			if( 0 === $enable_bitbucket->num_rows() ){
				$data[] = array('option_name' => "enable_bitbucket", 'option_value' => "0", 'autoload' => "yes");
			}

			if( 0 === $bitbucket_key->num_rows() ){
				$data[] = array('option_name' => "bitbucket_key", 'option_value' => "", 'autoload' => "yes");
			}

			if( 0 === $bitbucket_secret->num_rows() ){
				$data[] = array('option_name' => "bitbucket_secret", 'option_value' => "", 'autoload' => "yes");
			}

			if( ! empty( $data ) ){
				$this->db->insert_batch('options', $data);
			}
			
			$bitbucket_id = $this->db->query( "SHOW COLUMNS FROM user_profiles LIKE 'bitbucket_id'" );

			if( 0 === $bitbucket_id->num_rows() ){
				
				$fields = array(
					'bitbucket_id' => array(
						'type' => 'VARCHAR',
						'constraint' => '255',
						'null' => TRUE,
						)
				);

				$this->dbforge->add_column('user_profiles', $fields);
			}
		}

        public function down()
        {
        }
}
