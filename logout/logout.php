<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : logout/logout.php

** About : this module logs the user out of the site

*/

/**PSUEDO ALGORITHM
 * *
 * define the logout class
 * fetch the user session
 * update the user login status
 * destroy the user session
 * display the logout success page
 * 
 * *
 */

session_start();

//define the logout class
class logout{

    public $lecturer_session;

    public $student_session;

    //fetch the user session
    public function userSession(){

        if(isset($_SESSION['lecturer_session'])){

            $this->lecturer_session = $_SESSION['lecturer_session'];

        }elseif(isset($_SESSION['stduent_session'])){

            $this->student_session = $_SESSION['student_session'];

        }
        
    }

    //update the user login status
    public function updateLoginStatus(){

        if(isset($_SESSION['lecturer_session'])){

            //require the env library
            require('../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../env/db-conn-var.env');
            $user_db_conn_env->load();

            // user db connection
            include('../resources/database/users-db-connection.php'); //conn1

            $lecturer_update_query = "UPDATE lecturers SET login_status = 'Offline' WHERE encrypted_id = '$this->lecturer_session'";

            if($conn1->query($lecturer_update_query)){

                //destroy the user session 
                if(session_destroy()){

                    header('location:logout-success.php');

                }  

            }

            mysqli_close($conn1);

        }elseif(isset($_SESSION['student_session'])){

            //require the env library
            require('../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../env/db-conn-var.env');
            $user_db_conn_env->load();

            // user db connection
            include('../resources/database/users-db-connection.php'); //conn1

            $student_update_query = "UPDATE students SET login_status = 'Offline' WHERE encrypted_id = '$this->student_session'";

            if($conn1->query($student_update_query)){

                //destroy the user session 
                if(session_destroy()){

                    header('location:logout-success.php');

                } 

            }

            mysqli_close($conn1);

        }

    }

}

$logout_controller = new logout();

if(isset($_SESSION['student_session']) or isset($_SESSION['lecturer_session'])){

    $logout_controller->userSession();

    $logout_controller->updateLoginStatus();

}

?>