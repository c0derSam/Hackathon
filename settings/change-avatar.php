<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : settings/change-avatar.php

** About : this module updates the user avatar based on the user type

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the change avatar class
 * define the user type
 * fetch the user encrypted user id and the file path
 * delete the previous image
 * process the new avatar
 * define the update user avatar query based on the user type
 * redirect the user to the settings page based on the user type
 * perform administrative functions
 * 
 * *
 */

session_start();

//define the change avatar class
class changeAvatar{

    public $user_type;

    public $user_encrypted_id;

    public $previous_image_file_path;


    public $avatar_file_name;

    //define the user type
    public function userType(){

        if(isset($_SESSION['lecturer_session'])){

            $this->user_type = "lecturer";

        }elseif(isset($_SESSION['student_session'])){

            $this->user_type = "student";

        }

    }

    //fetch the user encrypted user id and the file path
    public function fetchUserEncryptedIdAndFilePath(){

        $this->user_encrypted_id = $_POST['encryptedId'];

        $this->previous_image_file_name = base64_decode($_POST['previousImageFilePath']);

    }

    //delete the previous image
    public function deleteAvatar(){

        unlink('../resources/avatars/'.$this->previous_image_file_name);

    }

    //process the new avatar
    public function processNewAvatar(){

        switch($_FILES['newAvatar']['type']){
            case 'image/jpg': $ext = 'jpg'; break;
            case 'image/jpeg': $ext = 'jpeg'; break;        
            case 'image/gif': $ext = 'gif'; break; 
            case 'image/png': $ext = 'png'; break; 
            case 'image/tiff': $ext = 'tif'; break; 
            default: $ext = ''; break; 
        }

        if ($ext){

            $random_number = rand();

            $this->avatar_file_name = "$random_number.$ext"; 

            //image directory
            $target = "../resources/avatars/".basename($this->avatar_file_name);

            //moving uploaded image to directory
            if(move_uploaded_file($_FILES['newAvatar']['tmp_name'],$target)){

                echo "Avatar uploaded";
                        
            }else{

                echo "Could not upload student avatar";          

            }


        }else{
     
            echo "Not an iamge";

        }

    }

    //define the update user avatar query based on the user type
    public function updateUserAvatarqUery(){

        // user db connection
        include('../resources/database/users-db-connection.php');

        if($this->user_type == "lecturer"){

            $update_lecturer_avatar_query = "UPDATE lecturers SET avatar = '$this->avatar_file_name' WHERE encrypted_id = '$this->user_encrypted_id'";

            if($conn1->query($update_lecturer_avatar_query)){

                header('location:lecturer-settings.php?avatarSuccess=true');

            }else{

                echo "Could not update lecturers avatar";

            }

        }elseif($this->user_type == "student"){

            $update_student_avatar_query = "UPDATE students SET avatar = '$this->avatar_file_name' WHERE encrypted_id = '$this->user_encrypted_id'";

            if($conn1->query($update_student_avatar_query)){

                header('location:student-settings.php?avatarSuccess=true');

            }else{

                echo "Could not update student avatar";

            }

        }

        mysqli_close($conn1);

    }

}

$change_avatar = new changeAvatar();

if(isset($_FILES['newAvatar']['type']) && $_FILES['newAvatar']['type'] !== ""){

    $change_avatar->userType();

    $change_avatar->fetchUserEncryptedIdAndFilePath();

    $change_avatar->deleteAvatar();

    $change_avatar->processNewAvatar();

    $change_avatar->updateUserAvatarqUery();

}

?>