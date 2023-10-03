<?php

/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : courses/courses-dashboard/course-dashboard-controller.php

** About : this controller controlls the course dashbaord model

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the course dashboard controller class
 * then define the controller function and ....
 * 
 */

class courseDashboardController{

    public function controller(){

       include('course-dashboard-model.php');

       $course_dashboard = new courseDashboard();

        if(isset($_GET['id'])){

            $course_dashboard->fetchRouteIdAndSsession();

            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // user db connection
            include('../../resources/database/courses-db-connection.php');

            $course_dashboard->sanitized_course_encrypted_id = 
            $course_dashboard->sanitize($conn8,$course_dashboard->course_encrypted_id);

            $course_dashboard->fetchCourseData();

            $course_dashboard->cachedCourseQuery();

            $course_dashboard->defineCourseData();

            $course_dashboard->courseHeading();

            $course_dashboard->cachedCourseHeading();

            $course_dashboard->displayCourseOutlineModal();

            $course_dashboard->studentsEnrolledTotal();

            $course_dashboard->cacheStudentsEnrolledTotal();

            $course_dashboard->displayTotalEnrolledStudents();

            $course_dashboard->cacheDisplayStudentTotalEnrolled();

            $course_dashboard->fetchCourseClassroom();

            $course_dashboard->cacheCourseClassroom();

            $course_dashboard->displayClassroomData();

            mysqli_close($conn8);

        }else{

            echo "error in displaying course dashbaord";

        }

    }

}

$course_Dashboard_controller = new courseDashboardController();

$course_Dashboard_controller->controller();

?>