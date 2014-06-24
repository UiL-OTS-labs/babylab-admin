<?php
class SurveyModel extends CI_Model
{
	public $survey_db;

	public function __construct()
	{
		parent::__construct();

		$this->survey_db = $this->load->database('survey', TRUE);
	}

	/////////////////////////
	// Surveys
	/////////////////////////

	/** Returns the testinvite for an id */
	public function get_survey_by_id($survey_id)
	{
		return $this->survey_db->get_where('surveys', array('sid' => $survey_id))->row();
	}

	/////////////////////////
	// Results
	/////////////////////////

	/** Returns the testinvite for an id */
	public function get_result_by_token($survey_id, $token)
	{
		return $this->survey_db->get_where('survey_' . $survey_id, array('token' => $token))->row();
	}

	/** Returns all question id's and answer values */
	public function get_result_array($survey_id, $token)
	{
		$result = $this->get_result_by_token($survey_id, $token);

		$result_array = array();
		$fields = $this->survey_db->list_fields('survey_' . $survey_id);
		foreach ($fields as $field)
		{
			$field_array = explode("X", $field);
			if (count($field_array) == 3)
			{
				$question = $this->get_question($field_array[0], $field_array[1], $field_array[2]);
				$parent_id = !empty($question->parent_qid) ? $question->parent_qid : $question->qid;
				$answer = $this->get_answer_by_code($parent_id, $result->$field);
				if (empty($answer)) $answer = $result->$field;

				if ($question->type === 'P') // for multiple choice with comments
				{
					$fieldcomment = $field . 'comment';
					if ($answer === 'Y') $answer = $result->$field . ': ' . $result->$fieldcomment;
				}

				$result_array[$field_array[2]] = $answer;
			}
			else $result_array[$field] = $result->$field;
		}

		return $result_array;
	}

	/////////////////////////
	// Survey Language Settings
	/////////////////////////

	/** TODO: unused */
	public function get_survey_email($survey_id, $participant, $type)
	{
		$this->survey_db->where('surveyls_survey_id', $survey_id);
		$this->survey_db->where('surveyls_language', 'nl');
		$language = $this->survey_db->get('surveys_languagesettings')->row();

		$subj = 'surveyls_' . $type . '_subj';
		$body = 'surveyls_' . $type;
		$result = $language->$subj . $language->$body;

		return $result;
	}

	/////////////////////////
	// Questions
	/////////////////////////

	public function get_question($survey_id, $group_id, $question_id)
	{
		$this->survey_db->where('sid', $survey_id);
		$this->survey_db->where('gid', $group_id);

		if (strpos($question_id, "E") !== FALSE) $q_array = explode("E", $question_id);
		if (!empty($q_array))
		{
			$this->survey_db->where('parent_qid', $q_array[0]);
			$this->survey_db->where('question_order', $q_array[1]);
		}
		else
		{
			$this->survey_db->where('qid', $question_id);
		}

		return $this->survey_db->get('questions')->row();
	}

	public function get_answer_by_code($question_id, $code)
	{
		$this->survey_db->where('qid', $question_id);
		$this->survey_db->where('code', $code);
		$answer = $this->survey_db->get('answers')->row();
		return !empty($answer) ? $answer->answer : NULL;
	}

	/////////////////////////
	// Tokens
	/////////////////////////

	/** Creates a token entry */
	public function create_token($participant, $survey_id, $token)
	{
		$token_table = 'tokens_' . $survey_id;
		$token_insert = array(
			'firstname'		=> $participant->firstname,
			'lastname'		=> $participant->lastname,
			'email'			=> DEV_MODE ? TO_EMAIL_OVERRIDE : $participant->email, 
			'emailstatus'	=> 'OK',
			'token'			=> $token,
			'language'		=> 'nl',
			'sent' 			=> 'Y');

		if ($survey_id == 65377) // Anamnese (TODO: make this generic?)
		{
			$token_insert['attribute_1'] = strtoupper($participant->gender);
			$token_insert['attribute_2'] = input_date($participant->dateofbirth);
			$token_insert['attribute_3'] = $participant->birthweight;
			$token_insert['attribute_4'] = $participant->pregnancyweeks;
			$token_insert['attribute_5'] = $participant->pregnancydays;
			$token_insert['attribute_6'] = $participant->dyslexicparent;
			$token_insert['attribute_7'] = $participant->problemsparent;
			$token_insert['attribute_8'] = $participant->multilingual;
				
			$languages = $this->languageModel->get_languages_by_participant($participant->id);
			$n = 9;
			foreach ($languages AS $language)
			{
				$token_insert['attribute_' . $n++] = $language->language;
				$token_insert['attribute_' . $n++] = $language->percentage;
			}
		}
		if ($survey_id == 21825) // NCDI-WZ (TODO: make this generic?)
		{
			$token_insert['attribute_1'] = strtoupper($participant->gender);
			$token_insert['attribute_2'] = input_date($participant->dateofbirth);
		}

		$this->survey_db->insert($token_table, $token_insert);
		return $this->survey_db->insert_id();
	}

	/** Invalidates a token entry */
	public function invalidate_token($survey_id, $token)
	{
		$token_table = 'tokens_' . $survey_id;
		$token_update = array(
			'usesleft'			=> 0,
			'validuntil'		=> input_datetime());

		$this->survey_db->where('token', $token);
		$this->survey_db->update($token_table, $token_update);
	}
}