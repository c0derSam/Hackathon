<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/courses-settings/edit-course-code-controller.php

** About : this module controlls the update course code model

*/ 

class editCourseCodeController{

    public function controller(){

      include('edit-course-code-model.php');

      $edit_course_code = new editCourseCode();

        if(isset($_POST['courseCode']) && isset($_POST['courseId'])){

            $edit_course_code->fetchCourseId();

            $edit_course_code->fetchCourseCode();

            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // user db connection
            include('../../resources/database/courses-db-connection.php');//conn8

            $edit_course_code->sanitized_course_code = $edit_course_code->sanitize($conn8,$edit_course_code->course_code);

            $edit_course_code->editCourseCodeQuery();

            $edit_course_code->redirectLecturer();

            mysqli_close($conn8);

        }

    }

}

$edit_course_code_controller = new editCourseCodeController();

$edit_course_code_controller->controller();

?>