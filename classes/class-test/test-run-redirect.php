<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-test/test-run-redirect.php

** About : this module redirects the user to the respective test run initializer

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the test run redirect class
 * fetch the test GET data
 * redirect the user to the respective test run initializer
 * 
 * *
*/

session_start();

class testRedirect{

    public $class_encrypted_id;

    public $test_encrypted_id;

    public function fetchClassEncryptedIdAndTestEncryptedId(){

        $this->class_encrypted_id = $_GET['classEncryptedId'];

        $this->test_encrypted_id = $_GET['testEncryptedId'];

    }

    //fetch the test GET data
    public function redirectUserToTestInitailizer(){

        if(isset($_SESSION['lecturer_session'])){

            header("location:lecturer-test-initializer.php?classEncryptedId=$this->class_encrypted_id&&testEncryptedId=$this->test_encrypted_id");

        }elseif(isset($_SESSION['student_session'])){

            header("location:student-test-initializer.php?classEncryptedId=$this->class_encrypted_id&&testEncryptedId=$this->test_encrypted_id");

        }

    }

}

$test_redirect = new testRedirect();

if(isset($_SESSION['lecturer_session']) or isset($_SESSION['student_session'])){

    if(isset($_GET['classEncryptedId']) && $_GET['classEncryptedId'] !== "" && isset($_GET['testEncryptedId']) && $_GET['testEncryptedId'] !== ""){

        $test_redirect->fetchClassEncryptedIdAndTestEncryptedId();

        $test_redirect->redirectUserToTestInitailizer();

    }

}else{

    header("location:logout-success.php");

}

?>