<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-test/delete-class-test-students.php

** About : this module deletes the students that have participated in the class test

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the delete the class test students 'class'
 * fetch the class test GET data
 * define the delete class test students query
 * redirect the user to the respective test run initializer
 * 
 * *
*/

session_start();

//define the delete the class test students 'class'
class deleteClassTestStudent{

    public $class_encrypted_id;

    public $test_encrypted_id;

    //fetch the class test GET data
    public function fetchClassTestData(){

        $this->class_encrypted_id = $_GET['classEncryptedId'];

        $this->test_encrypted_id = $_GET['testEncryptedId'];

    }

    //define the delete class test students query
    public function deleteClassTestStudents(){

        include('../../resources/database/class-test-result-db-connection.php');

        $delete_class_test_students_query = "DELETE FROM students_of_class_test_".$this->test_encrypted_id."";

        if($conn20->query($delete_class_test_students_query)){

            header("location:lecturer-test-initializer.php?classEncryptedId=$this->class_encrypted_id&&testEncryptedId=$this->test_encrypted_id");

        }else{

            echo "Could not delete the class test students";

        }

        mysqli_close($conn20);

    }

}

$delete_class_test_student = new deleteClassTestStudent();

if(isset($_SESSION['lecturer_session'])){

    if(isset($_GET['classEncryptedId']) && $_GET['classEncryptedId'] !== "" && isset($_GET['testEncryptedId']) && 
    $_GET['testEncryptedId'] !== ""){

        $delete_class_test_student->fetchClassTestData();

        $delete_class_test_student->deleteClassTestStudents();

    }

}else{

    header("location:logout-success.php");

}

?>