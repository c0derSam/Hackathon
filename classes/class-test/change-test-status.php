<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-test/change-test-status.php

** About : this module changes the class test status

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the change test status class
 * fetch the test and class data from the GET link
 * change the test class status in the database
 * redirect the lecturer to the lecturer class test dashboard
 * perform administrative functions
 * 
 * *
*/

session_start();

//define the change test status class
class changeTestStatus{

    public $current_status;

    public $class_encrypted_id;

    public $test_encrypted_id;

    //fetch the test and class data from the GET link
    public function fetchTestAndClassData(){

        $this->current_status = $_GET['currentStatus'];

        $this->class_encrypted_id = $_GET['classEncryptedId'];

        $this->test_encrypted_id = $_GET['testEncryptedId'];

    }

    //change the test class status in the database
    public function changeTestClassStatus(){

        include('../../resources/database/class-test-db-connection.php'); //conn19

        if($this->current_status == "active"){

            $deactive_test_status_query = "UPDATE test_of_class_".$this->class_encrypted_id." 
            SET status = 'de-activated' WHERE test_encrypted_id = '$this->test_encrypted_id'";

            if($conn19->query($deactive_test_status_query)){

                header("location:lecturer-test-initializer.php?classEncryptedId=$this->class_encrypted_id&&testEncryptedId=$this->test_encrypted_id");

            }

        }elseif($this->current_status == "de-activated"){

            $activate_test_status_query = "UPDATE test_of_class_".$this->class_encrypted_id." 
            SET status = 'active' WHERE test_encrypted_id = '$this->test_encrypted_id'";

            if($conn19->query($activate_test_status_query)){

                header("location:lecturer-test-initializer.php?classEncryptedId=$this->class_encrypted_id&&testEncryptedId=$this->test_encrypted_id");

            }

        }

        mysqli_close($conn19);

    }

}

$change_test_status = new changeTestStatus();

if(isset($_SESSION['lecturer_session'])){

    if(isset($_GET['currentStatus']) && $_GET['currentStatus'] !== "" && isset($_GET['classEncryptedId']) && 
    $_GET['classEncryptedId'] !== "" && isset($_GET['testEncryptedId']) && $_GET['testEncryptedId'] !== ""){

        $change_test_status->fetchTestAndClassData();

        $change_test_status->changeTestClassStatus();

    }

}

?>