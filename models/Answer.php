<?php

include_once('Question.php');

class Answer
{
    private $connection;

    private $id;
    private $content;
    private $questionId;
    private $isCorrect;
    private $isText;

    public function __construct($db)
    {
        $this->connection = $db;
    }

    public function createAnswer($id, $content, $questionId, $isCorrect)
    {
        $question = new Question($this->connection);
        $question->getQuestionById($questionId);

        $isText = false;
        if (empty($content)) {
            $isText = true;
        }

        return $this->insertAnswerQuery($id, $content, $questionId, $isCorrect, $isText);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getQuestionId()
    {
        return $this->questionId;
    }

    public function isCorrect()
    {
        return $this->isCorrect;
    }

    public function isText()
    {
        return $this->isText;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function setQuestionId($questionId)
    {
        $this->questionId = $questionId;
    }

    public function setIsCorrect($isCorrect)
    {
        $this->isCorrect = $isCorrect;
    }

    public function setIsText($isText)
    {
        $this->isText = $isText;
    }

    private function insertAnswerQuery($id, $content, $questionId, $isCorrect, $isText)
    {
        $this->connection->beginTransaction();

        $sql = "INSERT INTO answers(id, content, is_correct, question_id, is_text) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->connection->prepare($sql);

        $stmt->bindValue(1, $id, PDO::PARAM_STR);
        $stmt->bindValue(2, $content, PDO::PARAM_STR);
        $stmt->bindValue(3, $isCorrect, PDO::PARAM_BOOL);
        $stmt->bindValue(4, $questionId, PDO::PARAM_STR);
        $stmt->bindValue(5, $isText, PDO::PARAM_BOOL);

        $stmt->execute([$id, $content, $isCorrect, $questionId, $isText]);

        $this->connection->commit();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $row['id'] = $id;
        $row['content'] = $content;
        $row['isCorrect'] = $isCorrect;
        $row['questiionId'] = $questionId;
        $row['isText'] = $isText;

        return $row;
    }
}
