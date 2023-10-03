<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-test/finish-lecturer-test.php

** About : this module displays the success and error page when the lecturer submits the test answers

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the finish lecturer test class
 * fetch the test data
 * display the page heading
 * display and cache the success and error page of the lecturer test based on the finished test status
 * 
 * 
 * *
*/

session_start();

//define the finish lecturer test class
class finishLecturerTest{

    public $class_encrypted_id;

    public $test_encrypted_id;

    public $status;

    //fetch the test data
    public function fetchClassData(){

        $this->class_encrypted_id = $_GET['classEncryptedId'];

        $this->test_encrypted_id = $_GET['testEncryptedId'];

        $this->status = $_GET['status'];

    }

    //display the page heading
    public function header(){

        include('../header/header.php');

    }

    //display and cache the success and error page of the lecturer test based on the finished test status
    public function displaySuccessAndErrorPage(){

        echo '

        <br>
        
        <div class="container">

            <div align="center">
        
        ';

        if($this->status == "success"){

            echo  '
            
            <div class="alert alert-success" style="width:300px;">

                <h4>Test submited <i class="fa fa-check-circle"></i></h4>

                <p>
                    Go to the test dashboard and 
                    click on the result button<br>
                    <a href="lecturer-test-initializer.php?classEncryptedId='.$this->class_encrypted_id.'&&testEncryptedId='.$this->test_encrypted_id.'"">
                        <button class="btn btn-md text-light" style="background-color:#1d007e;">

                            Go to test dashboard

                        </button>
                    </a>
                </p>

            </div>
            
            ';

        }else{

            echo  '
            
            <div class="alert alert-warning" style="width:300px;">

                <h4>Error</h4>

                <p>
                    An error occurred while submitting the test
                </p>

            </div>
            
            ';

        }

    }

}

$finish_lecturer_test = new finishLecturerTest();

if(isset($_SESSION['lecturer_session'])){

    $finish_lecturer_test->fetchClassData();

    $finish_lecturer_test->header();

    $finish_lecturer_test->displaySuccessAndErrorPage();

}else{

    header("location:logout-success.php");

}

?>