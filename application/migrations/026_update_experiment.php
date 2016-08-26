<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_experiment extends CI_Migration {

	public function up()
	{
		$fields = array(
			'date_start' => array('type' => 'DATE', 'null' => TRUE),
			'date_end' => array('type' => 'DATE', 'null' => TRUE),
			'target_nr_participants' => array('type' => 'INT', 'constraint' => 11),
		);

		$this->dbforge->add_column('experiment', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('experiment', 'date_start');
		$this->dbforge->drop_column('experiment', 'date_end');
		$this->dbforge->drop_column('experiment', 'target_nr_participants');
	}
}
