<?php

class Answer extends ModelPDO {

    public static function getAllByQuestion($question) {
        return self::getAllBy('question_id', $question->id);
    }


    public function __construct($data = false) {
        $schema = array(
            'question_id' => PDO::PARAM_INT,
            'text'        => PDO::PARAM_STR,
            'time'        => PDO::PARAM_STR
        );
        parent::__construct($schema, $data);
    }

}

?>