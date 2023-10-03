<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-test/delete-test-question.php

** About : this module deletes the selected test question by the test question id

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the delete test question class
 * fetch the delete test question parameters such as, question encrypted id, test encrypted id and class encrypted id
 * define the delete question query
 * reorder the test question table
 * redirect the lecturer to the test questions form page
 * 
 * *
*/

session_start();

//define the delete test question class
class deleteTestQuestion{

    public $id;

    public $question_encrypted_id;

    public $test_encrypted_id;

    public $class_encrypted_id;

    //fetch the delete test question parameters such as, question encrypted id, test encrypted id and class encrypted id
    public function fetchTestQuestionLinkParameters(){

        $this->id = base64_decode($_GET['id']);

        $this->question_encrypted_id = $_GET['questionEncryptedId'];

        $this->test_encrypted_id = $_GET['testEncryptedId'];

        $this->class_encrypted_id = $_GET['classEncryptedId'];

    }

    //define the delete question query
    public function deleteQuestionQuery(){

        include('../../resources/database/class-test-questions-db-connections.php'); //conn21

        $delete_question_query = "DELETE FROM test_questions_of_class_test_".$this->test_encrypted_id." 
        WHERE question_encrypted_id ='$this->question_encrypted_id'";

        if($conn21->query($delete_question_query)){

            echo 'Question deleted';

        }else{

            echo 'Could not delete question';

        }

        mysqli_close($conn21);

    }

    //reorder the test question table
    public function reorderQuestionTable(){

        include('../../resources/database/class-test-questions-db-connections.php'); //conn21
        
        $reorder_question_table = "ALTER TABLE test_questions_of_class_test_".$this->test_encrypted_id." 
        AUTO_INCREMENT = $this->id";

        if($conn21->query($reorder_question_table)){

            echo "Question reorderd";

        }else{

            echo "Could not reorder question table";

        }

    }

    public function redirectLecturer(){

        header("location:test-questions-form.php?classEncryptedId=$this->class_encrypted_id&&testEncryptedId=$this->test_encrypted_id&&deleteQ=true");

    }

}

$delete_test_question = new deleteTestQuestion();

if(isset($_SESSION['lecturer_session'])){

    if(isset($_GET['id']) && $_GET['id'] !== "" && isset($_GET['questionEncryptedId']) && $_GET['questionEncryptedId'] !== "" 
    && isset($_GET['testEncryptedId']) && $_GET['testEncryptedId'] !== "" && isset($_GET['classEncryptedId']) && 
    $_GET['classEncryptedId'] !== ""){

        $delete_test_question->fetchTestQuestionLinkParameters();

        $delete_test_question->deleteQuestionQuery();

        $delete_test_question->reorderQuestionTable();

        $delete_test_question->redirectLecturer();

    }

}else{

    header("location:logout-success.php");

}

?>