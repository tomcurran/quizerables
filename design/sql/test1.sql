-- Get all questions and answers from user johnnorro

SELECT question_text, answer_text
FROM users, quizs, questions, answers
WHERE user_name = 'johnnorro'
AND quiz_user_id = user_id
AND question_quiz_id = quiz_id
AND answer_question_id = question_id;