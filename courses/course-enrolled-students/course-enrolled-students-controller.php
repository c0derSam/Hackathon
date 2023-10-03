<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/course-enrolled-students/courses-enrolled-students-controller.php

** About : this module controls the courde enrolled students model

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the course enrolled students controller class
 * then define the controller function and ....
 * 
 * *
 */

//initiate the course enrolled students controller class
class courseEnrolledStudentsController{

    //then define the controller function and ....
    public function controller(){

       include('course-enrolled-students-model.php');

       $course_enrolled_student = new courseEnrolledStudent();

       $course_enrolled_student->fetchCourseData();

        if(!empty($course_enrolled_student->fetch_course_id)){

            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // course db connection
            include('../../resources/database/courses-students-enrolled-db-connection.php');

            $course_enrolled_student->sanitized_course_id = 
            $course_enrolled_student->sanitize($conn9,$course_enrolled_student->fetch_course_id);

            $course_enrolled_student->fetchEnrolledStudents();

            $course_enrolled_student->cacheEnrolledstudentsQuery();

            $course_enrolled_student->displayEnrolledStudentsData();

       }else{

           
       }

    }

}

$course_enrolled_students_controller = new courseEnrolledStudentsController();

$course_enrolled_students_controller->controller();


?>