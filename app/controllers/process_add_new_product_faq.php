<?php

session_start(); 
require_once "connect.php";
// require_once "functions.php";

if($_POST['productId']){
    $productId = $_POST['productId'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $yes = 'yes';

    if(isset($_POST['faqId'])) {
        $faqId = $_POST['faqId'];

        $sql = "UPDATE tbl_questions_answers SET question=?,answer=? WHERE id=?";
        $statement = $conn->prepare($sql);
        $statement->execute([$quesiton, $answer, $faqId]);
    } else {
        $sql = "INSERT INTO tbl_questions_answers(question,answer,product_id,faq) VALUES(?,?,?,?)";
        $statement = $conn->prepare($sql);
        $statement->execute([$question, $answer, $productId, $yes]);
    }

    $response = [];
    $response = ['question' => $question, 'answer' => $answer];

    echo json_encode($response);
}

