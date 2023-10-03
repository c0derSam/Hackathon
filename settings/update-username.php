<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : settings/update-username.php

** About : this module updates the username based on the user type

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the update username class
 * define the user type
 * fetch the user encrypted user id
 * fetch the user new username
 * sanitize the new username
 * define the update user username query based on the user type
 * redirect the user to the settings page based on the user type
 * perform administrative functions
 * 
 * *
 */

session_start();

//define the update username class
class updateUsername{

    public $user_type;

    public $user_encrypted_id;


    public $new_username;

    public $sanitized_new_name;

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

    //fetch the user new username
    public function fetchNewUsername(){

        $this->new_username = $_POST['newUsername'];

    }

    //sanitize the new username
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        //$data = htmlentities($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //define the update user username query based on the user type
    public function updateUsername(){

        if($this->user_type == "lecturer"){

            // user db connection
            include('../resources/database/users-db-connection.php');

            $update_lecturer_username_query = "UPDATE lecturers SET username = '$this->sanitized_new_name' 
            WHERE encrypted_id = '$this->user_encrypted_id'";

            if($conn1->query($update_lecturer_username_query)){

                header('location:lecturer-settings.php?usernameSuccess=true');

            }

            mysqli_close($conn1);

        }elseif($this->user_type == "student"){

            // user db connection
            include('../resources/database/users-db-connection.php');

            $update_student_username_query = "UPDATE students SET username = '$this->sanitized_new_name' 
            WHERE encrypted_id = '$this->user_encrypted_id'";

            if($conn1->query($update_student_username_query)){

                header('location:student-settings.php?usernameSuccess=true');

            }

            mysqli_close($conn1);

        }

    }

}

$update_username = new updateUsername();

if(isset($_POST['newUsername']) && $_POST['newUsername'] !== "" ){

    $update_username->userType();

    $update_username->fetchUserEncryptedId();

    $update_username->fetchNewUsername();

    // user db connection
    include('../resources/database/users-db-connection.php');

    $update_username->sanitized_new_name = $update_username->sanitize($conn1,$update_username->new_username);

    $update_username->updateUsername();

    mysqli_close($conn1);

}

?>