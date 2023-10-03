<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : login/student-auth/student-auth-model.php

** About : this module controls the student auth model

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the student login controller class
 * then define the controller function and ....
 * 
 */

//initiate the student login controller class
class studentLoginController{

    //then define the controller function and ....
    public function controller(){

        include('student-auth-model.php');

        $student_login = new studentLogin();

        $student_login->fetchStudentData();

        //checking if the logged in data are not empty
        if(!empty($student_login->matric_number) && !empty($student_login->password)){

           //require the env library
           require('../../vendorEnv/autoload.php');

           $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
           $user_db_conn_env->load();

           // user db connection
           include('../../resources/database/users-db-connection.php');

           $student_login->sanitized_matric_number = $student_login->sanitize($conn1,$student_login->matric_number);

           $student_login->sanitized_password = $student_login->sanitize($conn1,$student_login->password);

           $student_login->encryptStudentPassword();

           $student_login->authStudent();

           $student_login->cacheStudentAuth();

           $student_login->updateLoginStatus();

           $student_login->startingUserSession();

           mysqli_close($conn1);

        }else{

            echo "Data not set";

        }

    }

}

$student_login_controller = new studentLoginController();

$student_login_controller->controller();
?>