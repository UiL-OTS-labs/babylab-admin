<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Newtable_availability extends CI_Migration {

	public function up()
	{
		// Create fields
		$fields = array(
			'id' => array(
					'type' => 'INT',
					'constraint' => '11',
					'auto_increment' => TRUE,
				),
			'user_id' => array(
					'type' => 'INT',
					'constraint' => '11',
				),
			'from' => array(
					'type' => 'DATETIME',
				),
			'to' => array(
					'type' => 'DATETIME',
				),	
			'comment' => array(
					'type' => 'VARCHAR',
					'constraint' => '2000',
				),
			);

		// Add fields
		$this->dbforge->add_field($fields);

		// Add primary key
		$this->dbforge->add_key('id', TRUE);

		// Create table
		$this->dbforge->create_table('availability', TRUE);

		// Add index
		$this->db->query("ALTER TABLE `availability` ADD INDEX `user_id` ( `user_id` ) ");

		// Add foreign key
		$this->db->query("ALTER TABLE `availability` ADD FOREIGN KEY ( `user_id` ) REFERENCES `user` (`id`) ON DELETE RESTRICT ON UPDATE RESTRICT");
	}

	

	public function down()
	{
		$this->dbforge->drop_table('availability');
	}
}