DROP TABLE IF EXISTS user;
DROP TABLE IF EXISTS questionquiz;
DROP TABLE IF EXISTS question;
DROP TABLE IF EXISTS answer;

CREATE TABLE user (
    user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL UNIQUE,
    user_email VARCHAR(255) NOT NULL,
    user_password VARCHAR(255) NOT NULL
);

CREATE TABLE quiz (
    quiz_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    quiz_user_id INT NOT NULL REFERENCES user(user_id),
    quiz_title VARCHAR(255) NOT NULL,
    quiz_description TEXT,
    quiz_theme_id INT NOT NULL,
    quiz_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    quiz_updated TIMESTAMP
);

CREATE TABLE question (
    quest_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    quest_quiz_id INT NOT NULL REFERENCES questionquiz(quiz_id),
    quest_text TEXT NOT NULL,
    quest_required BOOL NOT NULL
);

CREATE TABLE answer (
    answer_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    answer_quest_id INT NOT NULL REFERENCES question(quest_id),
    answer_text TEXT,
    answer_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
