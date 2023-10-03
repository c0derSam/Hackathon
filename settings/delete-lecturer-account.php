<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : settings/delete-lecturer-account.php

** About : this module deletes the lecturer account 

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the delete lecturer account class
 * fetch the lecturer encrypted user id
 * fetch the lecturer id
 * fetch the lecturer avatar file name
 * delete the lecturer avatar
 * delete the lecturer name from the lecturer database
 * reorder the lecturer database table
 * delete the lecturer notification table
 * delete the lecturer password reset code form the password reset code table
 * destroy the lecturer session
 * perform administrative functions
 * 
 * *
 */

session_start();

//define the update user about class
class deleteLecturerAccount{

    public $lecturer_encrypted_id;

    public $lecturer_id;

    public $avatar_filename;

    //fetch the lecturer encrypted user id
    public function fetchLecturerEncryptedId(){

        $this->lecturer_encrypted_id = $_POST['encryptedId'];

    }

    //fetch the lecturer id
    public function fetchLecturerId(){

        $this->lecturer_id = base64_decode($_POST['lecturerId']);

    }

    //fetch the lecturer avatar file name
    public function fetchlecturerAvatarFilename(){

        $this->avatar_filename = base64_decode($_POST['avatarFileName']);

    }

    //delete the lecturer avatar
    public function deleteLecturerAvatarFile(){

        if(unlink('../resources/avatars/'.$this->avatar_filename)){

            //echo "Lecturer avatar deleted";

        }else{

            //echo "Could not delete lecturer avatar";

        }

    }

    //delete the lecturer name from the lecturer database
    public function deleteLecturerFromDetailsFromLecturerDb(){

        // user db connection
        include('../resources/database/users-db-connection.php');

        $delete_lecturer_details_query = "DELETE FROM lecturers WHERE encrypted_id = '$this->lecturer_encrypted_id'";

        if($conn1->query($delete_lecturer_details_query)){

            //echo "Lecturer deatils deleted";

        }else{

            //echo "Could not delete the lecturer query";

        }

        mysqli_close($conn1);

    }

    //reorder the lecturer database table
    public function reorderLecturerTable(){

        // user db connection
        include('../resources/database/users-db-connection.php');

        $reorder_lecturer_table_query = "ALTER TABLE lecturers AUTO_INCREMENT = $this->lecturer_id";

        if($conn1->query($reorder_lecturer_table_query)){

            //echo "Lecturer table has been reordered";

        }else{

            //echo "Could not reeorder lecturer table";

        }

        mysqli_close($conn1);

    }

    //delete the lecturer notification table
    public function deleteLecturerNotificationTable(){

        // user db connection
        include('../resources/database/users-notification-db-connection.php');

        $delete_lecturer_notification_table_query = "DROP TABLE notification_for_user".$this->lecturer_encrypted_id."";

        if($conn15->query($delete_lecturer_notification_table_query)){

            //echo "Lecturer notification table deleted";

        }else{

            //echo "Could not delete lecturer notification table";

        }

        mysqli_close($conn15);

    }

    //delete the lecturer password reset code form the password reset code table
    public function deleteLecturerPasswordResetCode(){

        // user db connection
        include('../resources/database/users-reset-password-db-connection.php'); //conn18;

        $delete_lecturer_passcode_query = "DELETE FROM password_reset_codes WHERE encrypted_user_id = '$this->lecturer_encrypted_id'";

        if($conn18->query($delete_lecturer_passcode_query)){

            //echo "Lecturer password reset code deleted";

        }else{

            //echo "Could not delete lecturer password reset code";

        }

        mysqli_close($conn18);

    }

    //destroy the lecturer session
    public function destroyLecturerSession(){

        if(session_destroy()){

            //redirect the user to the lecturer login page
            header("location:../login/lecturer-login.php?accountDelete=true");

        }

    }

}

$delete_lecturer_account = new deleteLecturerAccount();

if(isset($_POST['deleteAccount']) && $_POST['deleteAccount'] !== ""){

    $delete_lecturer_account->fetchLecturerEncryptedId();

    $delete_lecturer_account->fetchLecturerId();

    $delete_lecturer_account->fetchlecturerAvatarFilename();

    $delete_lecturer_account->deleteLecturerAvatarFile();

    $delete_lecturer_account->deleteLecturerFromDetailsFromLecturerDb();

    $delete_lecturer_account->reorderLecturerTable();

    $delete_lecturer_account->deleteLecturerNotificationTable();

    $delete_lecturer_account->deleteLecturerPasswordResetCode();

    $delete_lecturer_account->destroyLecturerSession();

}

?>