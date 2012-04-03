-- Get all questions and answers from user johnnorro

SELECT quest_text, answer_text
FROM user, questionnaire, question, answer
WHERE user_name = 'johnnorro'
AND naire_user_id = user_id
AND quest_naire_id = naire_id
AND answer_quest_id = quest_id;