<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : notifications/clear-notifications.php

** About : this module deletes all the user notifications from the database

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the clear notifications class
 * fetch the user session
 * delete the user notification based on the user type
 * redirect the user
 * perform administrative functions
 * 
 * *
 */

session_start();

class clearNotifications{

    public $lecturer_session;

    public $student_session;

    public function fetchUserSession(){

        if(isset($_SESSION['lecturer_session'])){

           $this->lecturer_session = $_SESSION['lecturer_session'];

        }elseif(isset($_SESSION['student_session'])){

           $this->student_session = $_SESSION['student_session'];

        }

    }

    //delete the user notification based on the user type
    public function deleteLecturerNotifications(){

        if(isset($_SESSION['lecturer_session'])){

            //require the env library
            require('../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../env/db-conn-var.env');
            $user_db_conn_env->load();

            // user db connection
            include('../resources/database/users-notification-db-connection.php');//conn15

            $delete_lecturer_notifications = "DELETE FROM notification_for_user".$this->lecturer_session."";

            if($conn15->query($delete_lecturer_notifications)){

                header("location:index.php");

            }

            mysqli_close($conn15);

        }

    }

    public function deleteStudentNotifications(){

        if(isset($_SESSION['student_session'])){

            //require the env library
            require('../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../env/db-conn-var.env');
            $user_db_conn_env->load();

            // user db connection
            include('../resources/database/users-notification-db-connection.php');//conn15

            $delete_student_notifications = "DELETE FROM notification_for_user".$this->student_session."";

            if($conn15->query($delete_student_notifications)){

                header("location:index.php");

            }

            mysqli_close($conn15);

        }

    }

}

//controller
$clear_notifications = new clearNotifications();

if(isset($_SESSION['student_session']) or isset($_SESSION['lecturer_session'])){

    $clear_notifications->fetchUserSession();

    $clear_notifications->deleteLecturerNotifications();

    $clear_notifications->deleteStudentNotifications();

}

?>