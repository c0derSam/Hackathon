<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : settings/update-about.php

** About : this module updates the about based on the user type

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the update user about class
 * define the user type
 * fetch the user encrypted user id
 * fetch the user new user about
 * sanitize the new user about
 * define the update user about query based on the user type
 * redirect the user to the settings page based on the user type
 * perform administrative functions
 * 
 * *
 */

session_start();

class updateAbout{

    public $user_type;

    public $user_encrypted_id;


    public $new_about;

    public $sanitized_new_about;

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

    //fetch the user new user about
    public function fetchNewUserAbout(){

        $this->new_about = $_POST['newAbout'];

    }

    //sanitize the new user about
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        //$data = htmlentities($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //define the update user about query based on the user type
    public function updateUserAbout(){

        if($this->user_type == "lecturer"){

            // user db connection
            include('../resources/database/users-db-connection.php');

            $update_lecturer_about_query = "UPDATE lecturers SET about = '$this->sanitized_new_about' 
            WHERE encrypted_id = '$this->user_encrypted_id'";

            if($conn1->query($update_lecturer_about_query)){

                header('location:lecturer-settings.php?aboutSuccess=true');

            }

        }elseif($this->user_type == "student"){

            // user db connection
            include('../resources/database/users-db-connection.php');

            $update_student_about_query = "UPDATE students SET about = '$this->sanitized_new_about' 
            WHERE encrypted_id = '$this->user_encrypted_id'";

            if($conn1->query($update_student_about_query)){

                header('location:student-settings.php?aboutSuccess=true');

            }

        }

    }

}

$update_about = new updateAbout();

if(isset($_POST['newAbout']) && $_POST['newAbout'] !== ""){

    $update_about->userType();

    $update_about->fetchUserEncryptedId();

    $update_about->fetchNewUserAbout();

    // user db connection
    include('../resources/database/users-db-connection.php');

    $update_about->sanitized_new_about = 
    $update_about->sanitize($conn1,$update_about->new_about);

    $update_about->updateUserAbout();

    mysqli_close($conn1);

}

?>