<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : settings/update-fullname.php

** About : this module updates the user fullname based on the user type

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the update fullname class
 * define the user type
 * fetch the user encrypted user id
 * fetch the user new fullname
 * sanitize the fullname
 * define the update user fullname query based on the user type
 * redirect the user to the settings page based on the user type
 * perform administrative functions
 * 
 * *
 */

session_start();

//define the update fullname class
class updateFullname{

    public $user_type;

    public $user_encrypted_id;


    public $new_fullname;

    public $sanitized_new_fullname;

    //define the user type
    public function userType(){

        if(isset($_SESSION['lecturer_session'])){

            $this->user_type = "lecturer";

        }elseif(isset($_SESSION['student_session'])){

            $this->user_type = "student";

        }

    }

    //fetch the user encrypted user id
    public function fetchUserEncryptedId(){

        $this->user_encrypted_id = $_POST['encryptedId'];

    }

    //fetch the user new fullname
    public function fetchNewFullname(){

        $this->new_fullname = $_POST['newFullname'];

    }

    //sanitize the fullname
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        //$data = htmlentities($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //define the update user fullname query based on the user type
    public function updateUserFullName(){

        if($this->user_type == "lecturer"){

            // user db connection
            include('../resources/database/users-db-connection.php');

            $update_lecturer_fullname_query = "UPDATE lecturers SET fullname = '$this->sanitized_new_fullname' WHERE encrypted_id = 
            '$this->user_encrypted_id'";

            if($conn1->query($update_lecturer_fullname_query)){

                header('location:lecturer-settings.php?fullnameSuccess=true');

            }

        }elseif($this->user_type == "student"){

            // user db connection
            include('../resources/database/users-db-connection.php');

            $update_student_fullname_query = "UPDATE students SET fullname = '$this->sanitized_new_fullname' WHERE encrypted_id = 
            '$this->user_encrypted_id'";

            if($conn1->query($update_student_fullname_query)){

                header('location:student-settings.php?fullnameSuccess=true');

            }

        }

        mysqli_close($conn1);

    }

}

$update_fullname = new updateFullname();

if(isset($_POST['newFullname']) && $_POST['newFullname'] !== ""){

    $update_fullname->userType();

    $update_fullname->fetchUserEncryptedId();

    $update_fullname->fetchNewFullname();

    // user db connection
    include('../resources/database/users-db-connection.php');

    $update_fullname->sanitized_new_fullname = 
    $update_fullname->sanitize($conn1,$update_fullname->new_fullname);

    $update_fullname->updateUserFullName();

    mysqli_close($conn1);

}

?>