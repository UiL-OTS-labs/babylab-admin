<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_participation extends CI_Migration {

	public function up()
	{
		$fields = array(
			'part_number' => array('type' => 'VARCHAR', 'constraint' => 20),
			'interrupted' => array('type' => 'BOOLEAN'),
		);

		$this->dbforge->add_column('participation', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('participation', 'part_number');
		$this->dbforge->drop_column('participation', 'interrupted');
	}
}