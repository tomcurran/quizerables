DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS quizs;
DROP TABLE IF EXISTS questions;
DROP TABLE IF EXISTS answers;

CREATE TABLE users (
    user_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    user_name VARCHAR(255) NOT NULL UNIQUE,
    user_email VARCHAR(255) NOT NULL,
    user_password VARCHAR(255) NOT NULL
);

CREATE TABLE quizs (
    quiz_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    quiz_user_id INT NOT NULL REFERENCES users(user_id),
    quiz_title VARCHAR(255) NOT NULL,
    quiz_description TEXT,
    quiz_theme_id INT NOT NULL,
    quiz_created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    quiz_updated TIMESTAMP
);

CREATE TABLE questions (
    question_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    question_quiz_id INT NOT NULL REFERENCES quizs(quiz_id),
    question_text TEXT NOT NULL,
    question_required BOOL NOT NULL
);

CREATE TABLE answers (
    answer_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    answer_question_id INT NOT NULL REFERENCES questions(question_id),
    answer_text TEXT,
    answer_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
