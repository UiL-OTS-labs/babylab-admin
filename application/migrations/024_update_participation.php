<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_Participation extends CI_Migration 
{
	public function up()
	{
		$fields = array(
			'calendar_comment' => array('type' => 'VARCHAR', 'constraint' => 200),
		);

		$this->dbforge->add_column('participation', $fields);
	}

	public function down()
	{
		$this->dbforge->drop_column('participation', 'calendar_comment');
	}
}
