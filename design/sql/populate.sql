INSERT INTO user (user_name, user_email, user_password)
	VALUES ('johnnorro', 'johnnorro@gmail.com', 'pass');

INSERT INTO questionnaire (naire_user_id, naire_title, naire_description, naire_theme_id)
	VALUES (1, 'First Questionnaire', 'This is my first questionnaire', 0);

INSERT INTO question (quest_naire_id, quest_text, quest_required)
	VALUES (1, 'What colour is the sky?', TRUE);

INSERT INTO answer (answer_quest_id, answer_text)
	VALUES (1, 'Blue');