<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : notifications/handle-click-model.php

** About : this module updates the user notification status and redirects the user

*/  

/**PSUEDO ALGORITHM
 * *
 * define the handle link class
 * fetch the user session
 * fetch the user notification link and status data
 * update the user notification status based on the user type
 * perform administrative functions
 * 
 * *
 */

 session_start();

//define the handle link class
class handleLink{

    public $lecturer_session;

    public $student_session;


    public $notification_status;

    public $link;

    public $id;

    //fetch the user session
    public function fetchUserSession(){

        if(isset($_SESSION['lecturer_session'])){

            $this->lecturer_session = $_SESSION['lecturer_session'];

        }elseif(isset($_SESSION['student_session'])){

            $this->student_session = $_SESSION['student_session'];

        }

    }

    //fetch the user notification link and status data
    public function fetchUserNotificationLinkAndStatus(){

        $this->notification_status = $_GET['notify_status'];

        $this->link = base64_decode($_GET['link']);

        $this->id = base64_decode($_GET['id']);

    }

    //update the user notification status based on the user type
    public function updateLecturerNotificationStatus(){

        if($this->notification_status == "unread"){

            //require the env library
            require('../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../env/db-conn-var.env');
            $user_db_conn_env->load();

            // user db connection
            include('../resources/database/users-notification-db-connection.php');//conn15

            $update_lecturer_notification_query = "UPDATE notification_for_user".$this->lecturer_session." 
            SET status='read' WHERE id = '$this->id'";

            if($conn15->query($update_lecturer_notification_query)){

               header('location:'.$this->link.'');

            }else{

               echo "Could not update notification status";

            }

            mysqli_close($conn15);

        }

    }

    public function updateStudentNotificationStatus(){

        if($this->notification_status == "unread"){

            //require the env library
            require('../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../env/db-conn-var.env');
            $user_db_conn_env->load();

            // user db connection
            include('../resources/database/users-notification-db-connection.php');//conn15

            $update_student_notification_query = "UPDATE notification_for_user".$this->student_session." 
            SET status='read' WHERE id = '$this->id'";

            if($conn15->query($update_student_notification_query)){

                header('location:'.$this->link.'');

            }else{

               echo "Could not update notification status";

            }

            mysqli_close($conn15);

        }

    }

}

$handle_link = new handleLink();

session_regenerate_id(true);

if(isset($_SESSION['student_session']) or isset($_SESSION['lecturer_session'])){

    if(!empty($_GET['notify_status']) && !empty($_GET['link']) && !empty($_GET['id'])){

        $handle_link->fetchUserSession();

        $handle_link->fetchUserNotificationLinkAndStatus();

        if(isset($_SESSION['lecturer_session'])){

           $handle_link->updateLecturerNotificationStatus();

        }elseif(isset($_SESSION['student_session'])){

           $handle_link->updateStudentNotificationStatus();

        }

    }

}

?>