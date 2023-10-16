<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Update_Participation extends CI_Migration
{

	public function up()
	{
		$fields = array(
            'languagedisorderparent' => array('type' => 'ENUM("m", "f", "mf", "p")', 'null' => TRUE),
            'dyslexicparent' => array('type' => 'ENUM("m", "f", "mf", "p")', 'null' => TRUE),
		);

		$this->dbforge->modify_column('participation', $fields);
	}

	public function down()
	{
		$fields = array(
            'languagedisorderparent' => array('type' => 'ENUM("m", "f", "mf")', 'null' => TRUE),
            'dyslexicparent' => array('type' => 'ENUM("m", "f", "mf")', 'null' => TRUE),
		);

		$this->dbforge->modify_column('participation', $fields);
	}

}
