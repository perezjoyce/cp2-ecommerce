<?php
require_once '../../config.php';

if($_POST['productId']){
    $respons = "";

    $productId = $_POST['productId'];
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $yes = 'yes';

    if(isset($_POST['faqId'])) {
        $faqId = $_POST['faqId'];

        $sql = "SELECT * FROM tbl_questions_answers WHERE question = ? AND id!=? AND product_id=?";
        $statement = $conn->prepare($sql);
        $statement->execute([$question, $faqId, $productId]);
        $count = $statement->rowCount();

        if($count){
           $response = 'duplicate';
        } else {
            $sql2 = "UPDATE tbl_questions_answers SET question=?,answer=? WHERE id=?";
            $statement2 = $conn->prepare($sql2);
            $statement2->execute([$question, $answer, $faqId]);
            $response = "success";
        }
    } else {

        $sql3 = "SELECT * FROM tbl_questions_answers WHERE question=? AND product_id = ?";
        $statement3 = $conn->prepare($sql3);
        $statement3->execute([$question, $productId]);
        $count3 = $statement3->rowCount();


        if($count3){
            $response = 'duplicate';
        } else {
            $sql4 = "INSERT INTO tbl_questions_answers(question,answer,product_id,faq) VALUES(?,?,?,?)";
            $statement4 = $conn->prepare($sql4);
            $statement4->execute([$question, $answer, $productId, $yes]);
            $response = "success";
        }
    }

    // $response = [];
    // $response = ['question' => $question, 'answer' => $answer];

    // echo json_encode($response);

    echo $response;
}

