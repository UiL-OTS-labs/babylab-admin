<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_experiment extends CI_Migration {

	public function up()
	{
		$fields = array(
			'duration_additional' => array('type' => 'INT', 'constraint' => 11, 'null' => FALSE, 'default' => INSTRUCTION_DURATION),
		);

		$this->dbforge->add_column('experiment', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('experiment', 'duration_additional');
	}
}
