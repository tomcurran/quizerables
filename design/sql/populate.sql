-- user table
INSERT INTO users (user_name, user_email, user_password)
    VALUES ('johnnorro', 'johnnorro@gmail.com', 'pass1');
INSERT INTO users (user_name, user_email, user_password)
    VALUES ('tom',       'tom@gmail.com',       'pass2');
INSERT INTO users (user_name, user_email, user_password)
    VALUES ('mike',      'mike@gmail.com',      'pass3');

-- quiz table
INSERT INTO quizs (quiz_user_id, quiz_title, quiz_description, quiz_theme_id, quiz_updated)
    VALUES (1, 'First quiz',         'This is my first quiz',  0, NOW());
INSERT INTO quizs (quiz_user_id, quiz_title, quiz_description, quiz_theme_id, quiz_updated)
    VALUES (1, 'Second quiz',        'This is my second quiz', 0, NOW());
INSERT INTO quizs (quiz_user_id, quiz_title, quiz_description, quiz_theme_id, quiz_updated)
    VALUES (1, 'Third quiz',         'This is my third quiz',  0, NOW());
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