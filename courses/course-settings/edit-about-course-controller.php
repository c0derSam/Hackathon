<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/courses-settings/edit-about-course-controller.php

** About : this module controlls the update course about

*/ 

class editAboutCourseController{

    public function controller(){

      include('edit-about-course-model.php');

      $edit_about_course = new editAboutCourse();

        if(isset($_POST['aboutCourse']) && isset($_POST['courseId'])){

            $edit_about_course->fetchCourseId();

            $edit_about_course->fetchAboutCourse();

            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // user db connection
            include('../../resources/database/courses-db-connection.php');//conn8

            $edit_about_course->sanitized_about_course =$edit_about_course->sanitize($conn8,$edit_about_course->about_course);

            $edit_about_course->editAboutCourseQuery();

            $edit_about_course->redirectLecturer();

            mysqli_close($conn8);

        }

    }

}

$edit_about_course_controller = new editAboutCourseController();

$edit_about_course_controller->controller();


?>