<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-test/delete-test-score.php

** About : this module deletes the lecturer test score

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the delete test score class
 * fetch the test data to be deleted
 * fetch the lecturer session
 * process the lecturer delete test query
 * process the lecturer test answers
 * redirect the lecturer to the test dashboard
 * 
 * *
*/

session_start();

//define the delete test score class
class deleteTestScore{

    public $test_encrypted_id;

    public $class_encrypted_id;

    public $lecturer_session;

    //fetch the test data to be deleted
    public function fetchTestData(){

        $this->test_encrypted_id = $_GET['testEncryptedId'];

        $this->class_encrypted_id = $_GET['classEncryptedId'];

    }

    //fetch the lecturer session
    public function fetchLecturerSession(){

        $this->lecturer_session = $_SESSION['lecturer_session']; 

    }

    //process the lecturer delete test query
    public function deleteLecturerTestScore(){

        include('../../resources/database/class-test-result-db-connection.php');

        $delete_query = "DELETE FROM lecturer_of_class_test_".$this->test_encrypted_id." WHERE lecturer_encrypted_id = '$this->lecturer_session'";

        $conn20->query($delete_query);

        mysqli_close($conn20);

    }

    //process the lecturer test answers
    public function deleteLecturerTestAnswers(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        $cocantination = $this->lecturer_session.$this->test_encrypted_id;

        $this->identification = substr($cocantination, 0, 12);

        $delete_lecturer_test_answers = "DROP TABLE userTest_".$this->identification."";

        if($conn22->query($delete_lecturer_test_answers)){

            //redirect the lecturer to the test dashboard
            header("location:lecturer-test-initializer.php?classEncryptedId=$this->class_encrypted_id&&testEncryptedId=$this->test_encrypted_id&&alert=success");

        }

        mysqli_close($conn22);

    }

}

$delete_test_score = new deleteTestScore();

if(isset($_SESSION['lecturer_session'])){

    $delete_test_score->fetchTestData();

    $delete_test_score->fetchLecturerSession();

    $delete_test_score->deleteLecturerTestScore();

    $delete_test_score->deleteLecturerTestAnswers();

}else{

    header("location:logout-success.php");

}

?>