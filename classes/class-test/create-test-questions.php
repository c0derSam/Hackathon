<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-test/create-test-questions.php

** About : this module creates the class test questions

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the create test question class
 * fetch the test question data
 * sanitize the test question data
 * create auto generated data
 * insert the questions into the class test questions database
 * redirect the lecturer to the create test questions page
 * 
 * *
*/

session_start();

//define the create test question class
class createTestQuestion{

    public $question;

    public $optionA;

    public $optionB;

    public $optionC;

    public $optionD;

    public $correct_option;

    public $test_encrypted_id;

    public $class_encrypted_id;

    public $question_encrypted_id;


    public $sanitized_question;

    public $sanitized_optionA;

    public $sanitized_optionB;

    public $sanitized_optionC;

    public $sanitized_optionD;

    public $sanitized_correct_option;

    //fetch the test question data
    public function fetchTestQuestionData(){

        $this->question = $_POST['question'];

        $this->optionA = $_POST['optionA'];

        $this->optionB = $_POST['optionB'];

        $this->optionC = $_POST['optionC'];

        $this->optionD = $_POST['optionD'];

        $this->correct_option = $_POST['correctOption'];

        $this->test_encrypted_id = $_POST['testEncryptedId'];

        $this->class_encrypted_id = $_POST['classEncryptedId'];

    }

    //sanitize the test question data
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //create auto generated data
    public function createAutoGenenratedData(){

        $this->question_encrypted_id = md5(rand());

    }

    //insert the questions into the class test questions database
    public function insertIntoClassTestQuestionsDb(){

        // class test db connection
        include('../../resources/database/class-test-questions-db-connections.php'); //conn21

        $insert_into_class_test_question = "INSERT INTO test_questions_of_class_test_".$this->test_encrypted_id."
        (
            question_title,question_encrypted_id,optionA,optionB,optionC,optionD,
            correct_option,status
        )
        VALUES(
            '$this->sanitized_question','$this->question_encrypted_id',
            '$this->sanitized_optionA','$this->sanitized_optionB',
            '$this->sanitized_optionC','$this->sanitized_optionD',
            '$this->sanitized_correct_option','active'
        )
        ";

        if($conn21->query($insert_into_class_test_question)){

            //redirect the lecturer to the create test questions page
            header("location:test-questions-form.php?newQuestionAlert=true&&classEncryptedId=$this->class_encrypted_id&&testEncryptedId=$this->test_encrypted_id");

        }else{

            echo "Could not create question";

        }

    }

}

$create_test_question = new createTestQuestion();

if(isset($_SESSION['lecturer_session'])){

    if(isset($_POST['question']) && $_POST['question'] !== "" && isset($_POST['optionA']) && $_POST['optionA'] 
    !== "" && isset($_POST['optionB']) && $_POST['optionB'] !== "" && isset($_POST['optionC']) && $_POST['optionC'] !== "" 
    && isset($_POST['optionD']) && $_POST['optionD'] !== "" && isset($_POST['correctOption']) && $_POST['correctOption'] !== "" ){

        $create_test_question->fetchTestQuestionData();

        // class test db connection
        include('../../resources/database/class-test-questions-db-connections.php'); //conn21

        $create_test_question->sanitized_question = 
        $create_test_question->sanitize($conn21,$create_test_question->question);

        $create_test_question->sanitized_optionA = 
        $create_test_question->sanitize($conn21,$create_test_question->optionA);

        $create_test_question->sanitized_optionB = 
        $create_test_question->sanitize($conn21,$create_test_question->optionB);

        $create_test_question->sanitized_optionC = 
        $create_test_question->sanitize($conn21,$create_test_question->optionC);

        $create_test_question->sanitized_optionD = 
        $create_test_question->sanitize($conn21,$create_test_question->optionD);

        $create_test_question->sanitized_correct_option = 
        $create_test_question->sanitize($conn21,$create_test_question->correct_option);

        $create_test_question->createAutoGenenratedData();

        $create_test_question->insertIntoClassTestQuestionsDb();

    }

}

?>
