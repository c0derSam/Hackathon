<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : login/lecturer-auth/lecturer-auth-model.php

** About : this module controls the lecturer auth model

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the lecturer login controller class
 * then define the controller function and ....
 * 
 */

//initiate the lecturer login controller class
class lecturerLoginController{

    //then define the controller function and ....
    public function controller(){

        include('lecturer-auth-model.php');

        $lecturer_login = new lecturerLogin();

        $lecturer_login->fetchLecturerData();

        //checking if the logged in data are not empty
        if(!empty($lecturer_login->username) && !empty($lecturer_login->password)){

           //require the env library
           require('../../vendorEnv/autoload.php');

           $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
           $user_db_conn_env->load();

           // user db connection
           include('../../resources/database/users-db-connection.php');

           $lecturer_login->sanitized_username = $lecturer_login->sanitize($conn1,$lecturer_login->username);

           $lecturer_login->sanitized_password = $lecturer_login->sanitize($conn1,$lecturer_login->password);

           $lecturer_login->encryptLecturerPassword();

           $lecturer_login->authLecturer();

           $lecturer_login->cacheLecturerAuth();

           $lecturer_login->updateLoginStatus();

           $lecturer_login->startingUserSession();

           mysqli_close($conn1);

        }else{

           header('location:lecturer-auth-error.php');

        }

    }

}

$lecturer_login_controller = new lecturerLoginController();

$lecturer_login_controller->controller();

?>