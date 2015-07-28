<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_experiment extends CI_Migration {

	public function up()
	{
		$fields = array(
			'informedconsent' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => TRUE),
		);

		$this->dbforge->add_column('experiment', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('experiment', 'informedconsent');
	}
}