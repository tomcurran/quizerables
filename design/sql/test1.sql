-- Get all questions and answers from user johnnorro

SELECT quest_text, answer_text
FROM user, questionquiz, question, answer
WHERE user_name = 'johnnorro'
AND quiz_user_id = user_id
AND quest_quiz_id = quiz_id
AND answer_quest_id = quest_id;