<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : settings/delete-student-account.php

** About : this module deletes the student account 

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the delete student account class
 * fetch the student encrypted user id
 * fetch the student id
 * fetch the student avatar file name
 * delete the student avatar
 * delete the student name from the students database
 * reorder the students database table
 * delete the student enrolled courses table
 * delete the student notification table
 * delete the student password reset code form the password reset code table
 * destroy the student session
 * perform administrative functions
 * 
 * *
 */

session_start();

//define the delete student account class
class deleteStudentAccount{

    public $student_encrypted_id;

    public $student_id;

    public $avatar_filename;

    //fetch the student encrypted user id
    public function fetchStudentEncryptedId(){

        $this->student_encrypted_id = $_POST['encryptedId'];

    }

    //fetch the student id
    public function fetchStudentId(){

        $this->student_id = base64_decode($_POST['studentId']);

    }

    //fetch the student avatar file name
    public function fetchStudentAvatarFilename(){

        $this->avatar_filename = base64_decode($_POST['avatarFileName']);

    }

    //delete the student avatar
    public function deleteStudentAvatarFile(){

        if(unlink('../resources/avatars/'.$this->avatar_filename)){

            echo "Student avatar deleted";

        }else{

            echo "Could not delete student avatar";

        }

    }

    //delete the student name from the students database
    public function deleteStudentFromDetailsFromStudentDb(){

        // user db connection
        include('../resources/database/users-db-connection.php');

        $delete_student_details_query = "DELETE FROM students WHERE encrypted_id = '$this->student_encrypted_id'";

        if($conn1->query($delete_student_details_query)){

            echo "Student details deleted";

        }else{

            echo "Could not delete the Student details";

        }

        mysqli_close($conn1);

    }

    //reorder the students database table
    public function reorderStudentTable(){

        // user db connection
        include('../resources/database/users-db-connection.php');

        $reorder_student_table_query = "ALTER TABLE students AUTO_INCREMENT = $this->student_id";

        if($conn1->query($reorder_student_table_query)){

            echo "Student table has been reordered";

        }else{

            echo "Could not reeorder student table";

        }

        mysqli_close($conn1);

    }

    //delete the student enrolled courses table
    public function deleteStudentEnrolledCoursesTable(){

        // user db connection
        include('../resources/database/students-courses-db-connection.php');

        $delete_student_courses_enrolled_query = "DROP TABLE enrolled_courses_of_student_".$this->student_encrypted_id."";

        if($conn2->query($delete_student_courses_enrolled_query)){

            echo "Student enrolled courses deleted";

        }else{

            echo "Could not create student enrolled courses";

        }

        mysqli_close($conn2);

    }

    //delete the student notification table
    public function deleteStudentNotificationTable(){

        // user db connection
        include('../resources/database/users-notification-db-connection.php');

        $delete_student_notification_table_query = "DROP TABLE notification_for_user".$this->student_encrypted_id."";

        if($conn15->query($delete_student_notification_table_query)){

            echo "Student notification table deleted";

        }else{

            echo "Could not delete student notification table";

        }

        mysqli_close($conn15);

    }

    //delete the student password reset code form the password reset code table
    public function deleteStudentPasswordResetCode(){

        // user db connection
        include('../resources/database/users-reset-password-db-connection.php'); //conn18;

        $delete_student_passcode_query = "DELETE FROM password_reset_codes WHERE encrypted_user_id = '$this->student_encrypted_id'";

        if($conn18->query($delete_student_passcode_query)){

            echo "Student password reset code deleted";

        }else{

            echo "Could not delete student password reset code";

        }

        mysqli_close($conn18);

    }

    //destroy the student session
    public function destroyStudentSession(){

        if(session_destroy()){

            //redirect the user to the lecturer login page
            header("location:../login/index.php?accountDelete=true");

        }

    }

}

$delete_student_account = new deleteStudentAccount();

if(isset($_POST['deleteAccount']) && $_POST['deleteAccount'] !== ""){

    $delete_student_account->fetchStudentEncryptedId();

    $delete_student_account->fetchStudentId();

    $delete_student_account->fetchStudentAvatarFilename();

    $delete_student_account->deleteStudentAvatarFile();

    $delete_student_account->deleteStudentFromDetailsFromStudentDb();

    $delete_student_account->reorderStudentTable();

    $delete_student_account->deleteStudentEnrolledCoursesTable();

    $delete_student_account->deleteStudentNotificationTable();

    $delete_student_account->deleteStudentPasswordResetCode();

    $delete_student_account->destroyStudentSession();

}

?>