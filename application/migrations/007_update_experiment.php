<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_experiment extends CI_Migration {

	public function up()
	{
		$relation = array(
			'relation' => array('type' => 'ENUM("prerequisite", "excludes", "combination")')
		);

		$this->dbforge->modify_column('relation', $relation);
	}

	public function down()
	{
		$relation = array(
			'relation' => array('type' => 'ENUM("prerequisite", "excludes")')
		);

		$this->dbforge->modify_column('relation', $relation);
	}
}