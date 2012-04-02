SELECT quest_text, answer_text FROM question, answer
	WHERE quest_id = answer_quest_id
		AND quest_naire_id = (SELECT naire_id FROM questionnaire, user
			WHERE user_id = naire_user_id AND user_name = 'johnnorro');