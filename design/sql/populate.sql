-- user table
INSERT INTO users (user_name, user_email, user_password)
	VALUES ('johnnorro', 'johnnorro@gmail.com', '7caf3bb4e4ff677d9e62ad5fe319b3b765a99fa2ed8b60bfc6e133941ae0b820697e8639d5641d7fc05e171e25558695316a01ce465b68a97f8df80655e9a10e'); -- pass1
INSERT INTO users (user_name, user_email, user_password)
	VALUES ('tom',	   'tom@gmail.com',	   '572b20f8b2ee621d9ea1cdb3c44c7c6d0b64754af86c03ee6261576e8c458246b7defe08eeec8228def8a8f7ce2c5707becc7a5f36390db03416dbffa16cb649'); -- pass2
INSERT INTO users (user_name, user_email, user_password)
	VALUES ('mike',	  'mike@gmail.com',	  'dd72d8f0d6ba674a660dd97c51a42c24a000811ceb798cd16336037e52e03208104674247fa9e0a26da662dc2ee4c167ad12908d61968894902e76d016e95e44'); -- pass3

-- quiz table
INSERT INTO quizs (quiz_user_id, quiz_title, quiz_description, quiz_theme_id, quiz_updated)
	VALUES (1, 'First quiz',		 'This is my first quiz',  0, NOW());
INSERT INTO quizs (quiz_user_id, quiz_title, quiz_description, quiz_theme_id, quiz_updated)
	VALUES (1, 'Second quiz',		'This is my second quiz', 0, NOW());
INSERT INTO quizs (quiz_user_id, quiz_title, quiz_description, quiz_theme_id, quiz_updated)
	VALUES (1, 'Third quiz',		 'This is my third quiz',  0, NOW());
INSERT INTO quizs (quiz_user_id, quiz_title, quiz_description, quiz_theme_id, quiz_updated)
	VALUES (2, 'First quiz by Tom',  'This is my first quiz',  0, NOW());
INSERT INTO quizs (quiz_user_id, quiz_title, quiz_description, quiz_theme_id, quiz_updated)
	VALUES (2, 'Second quiz by Tom', 'This is my second quiz', 0, NOW());

-- question table
INSERT INTO questions (question_quiz_id, question_text, question_required)
	VALUES (1, 'What colour is the sky?', TRUE);
INSERT INTO questions (question_quiz_id, question_text, question_required)
	VALUES (2, 'What colour is the sky?', TRUE);
INSERT INTO questions (question_quiz_id, question_text, question_required)
	VALUES (2, 'What colour is the sea?', TRUE);
INSERT INTO questions (question_quiz_id, question_text, question_required)
	VALUES (3, 'What colour is the ground?', TRUE);

-- answer table
INSERT INTO answers (answer_question_id, answer_text)
	VALUES (1, 'Blue');
INSERT INTO answers (answer_question_id, answer_text)
	VALUES (1, 'White');
INSERT INTO answers (answer_question_id, answer_text)
	VALUES (2, 'Aqua');
INSERT INTO answers (answer_question_id, answer_text)
	VALUES (3, 'Turquoise');
INSERT INTO answers (answer_question_id, answer_text)
	VALUES (4, 'Green');
INSERT INTO answers (answer_question_id, answer_text)
	VALUES (4, 'Brown');
