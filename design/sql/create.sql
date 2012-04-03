DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS questionnaire;
DROP TABLE IF EXISTS question;
DROP TABLE IF EXISTS answer;

CREATE TABLE user (
    user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL,
    user_email VARCHAR(255) NOT NULL,
    user_password BINARY(32) NOT NULL
);

CREATE TABLE questionnaire (
    naire_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    naire_user_id INT NOT NULL REFERENCES user(user_id),
    naire_title VARCHAR(255) NOT NULL,
    naire_description TEXT,
    naire_theme_id INT NOT NULL,
    naire_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    naire_updated TIMESTAMP
);

CREATE TABLE question (
    quest_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    quest_naire_id INT NOT NULL REFERENCES questionnaire(naire_id),
    quest_text TEXT NOT NULL,
    quest_required BOOL NOT NULL
);

CREATE TABLE answer (
    answer_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    answer_quest_id INT NOT NULL REFERENCES question(quest_id),
    answer_text TEXT,
    answer_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
