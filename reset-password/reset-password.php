<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : reset-password/reset-password.php

** About : this module resets the user password

*/

/**PSUEDO ALGORITHM
 * *
 * define the reset password class
 * fetch the new user password, user type and the user encrypted id
 * sanitize the new password
 * encrypt the user password
 * insert the the new password into the user database based on the user type
 * 
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the reset password class
class resetPassword{

    public $new_password;

    public $encrypted_id;

    public $user_type;


    public $sanitized_new_password;

    
    public $encrypted_password;

    //fetch the new user password, user type and the user encrypted id
    public function fetchNewPasswordAndUserData(){

        $this->new_password = $_POST['newPassword'];

        $this->encrypted_id = $_POST['encryptedId'];

        $this->user_type = $_POST['userType'];

        echo "working";

    }
    
    //sanitize the new password
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = htmlentities($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //encrypt the user password
    public function encryptUserPassword(){

        $this->encrypted_password = md5($this->sanitized_new_password);

        echo "working";


    }

    //insert the the new password into the user database based on the user type
    public function resetPasswordForStudent(){

        if($this->user_type == "student"){

            // user db connection
            include('../resources/database/users-db-connection.php');

            $reset_student_password_query = "UPDATE students SET password = '$this->encrypted_password' 
            WHERE encrypted_id = '$this->encrypted_id'";

            if($conn1->query($reset_student_password_query)){

                header("location:reset-password-success.php?resetStatus=true");

            }

            mysqli_close($conn1);

            echo "working";


        }

    }

    public function resetPasswordForLecturer(){

        if($this->user_type == "lecturer"){

            // user db connection
            include('../resources/database/users-db-connection.php');

            $reset_lecturer_password_query = "UPDATE lecturers SET password = '$this->encrypted_password' 
            WHERE encrypted_id = '$this->encrypted_id'";

            if($conn1->query($reset_lecturer_password_query)){

                header("location:reset-password-success.php?resetStatus=true");

            }else{

                echo $conn1->error;

            }

            mysqli_close($conn1);

            echo "working";

 
        }

    }
    
}

$reset_password = new resetPassword();

if(isset($_POST['newPassword']) && $_POST['newPassword'] !== "" ){

    $reset_password->fetchNewPasswordAndUserData();

    // user db connection
    include('../resources/database/users-db-connection.php');

    $reset_password->sanitized_new_password = $reset_password->sanitize($conn1,$reset_password->new_password);

    $reset_password->encryptUserPassword();

    $reset_password->resetPasswordForStudent();

    $reset_password->resetPasswordForLecturer();

    mysqli_close($conn1);


}else{

    header("location:index.php");

}

?>