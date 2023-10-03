<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/courses-settings/edit-course-title-controller.php

** About : this module controlls the edit course title model

*/ 

class editCourseTitleController{

    public function controller(){

      include('edit-course-title-model.php');

      $edit_course_title = new editCourseTitle();

        if(isset($_POST['courseTitle']) && isset($_POST['courseId'])){

            $edit_course_title->fetchCourseId();

            $edit_course_title->fetchCourseTitle();

            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // user db connection
            include('../../resources/database/courses-db-connection.php');//conn8

            $edit_course_title->sanitized_course_title = $edit_course_title->sanitize($conn8,$edit_course_title->course_title);

            $edit_course_title->editCourseTitleQuery();

            $edit_course_title->redirectLecturer();

            mysqli_close($conn8);

        }

    }

}

$edit_course_title_controller = new editCourseTitleController();

$edit_course_title_controller->controller();

?>