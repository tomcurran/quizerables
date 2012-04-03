-- user table
INSERT INTO user (user_name, user_email, user_password)
    VALUES ('johnnorro', 'johnnorro@gmail.com', 'pass1');
INSERT INTO user (user_name, user_email, user_password)
    VALUES ('tom',       'tom@gmail.com',       'pass2');
INSERT INTO user (user_name, user_email, user_password)
    VALUES ('mike',      'mike@gmail.com',      'pass3');

-- questionnaire table
INSERT INTO questionnaire (naire_user_id, naire_title, naire_description, naire_theme_id, naire_updated)
    VALUES (1, 'First Questionnaire',         'This is my first questionnaire',  0, NOW());
INSERT INTO questionnaire (naire_user_id, naire_title, naire_description, naire_theme_id, naire_updated)
    VALUES (1, 'Second Questionnaire',        'This is my second questionnaire', 0, NOW());
INSERT INTO questionnaire (naire_user_id, naire_title, naire_description, naire_theme_id, naire_updated)
    VALUES (1, 'Third Questionnaire',         'This is my third questionnaire',  0, NOW());
INSERT INTO questionnaire (naire_user_id, naire_title, naire_description, naire_theme_id, naire_updated)
    VALUES (2, 'First Questionnaire by Tom',  'This is my first questionnaire',  0, NOW());
INSERT INTO questionnaire (naire_user_id, naire_title, naire_description, naire_theme_id, naire_updated)
    VALUES (2, 'Second Questionnaire by Tom', 'This is my second questionnaire', 0, NOW());

-- question table
INSERT INTO question (quest_naire_id, quest_text, quest_required)
    VALUES (1, 'What colour is the sky?', TRUE);

-- answer table
INSERT INTO answer (answer_quest_id, answer_text)
    VALUES (1, 'Blue');
