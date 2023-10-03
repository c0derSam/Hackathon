<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-dashboard/class-dashboard-controller.php

** About : this module controlls the class dashboard controller

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the class dashboard controller class
 * then define the controller function and ....
 * 
 */

class classroomDashboardController{

    public function controller(){

       include('class-dashboard-model.php');

       $classroom_dashboard = new classroomDashboard();

        if(isset($_GET['id']) && isset($_GET['course_id'])){

            $classroom_dashboard->fetchClassidAndUserSession();
            
            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // course db connection
            include('../../resources/database/courses-classes-db-connection.php');

            $classroom_dashboard->sanitized_class_encrypted_id = 
            $classroom_dashboard->sanitize($conn10,$classroom_dashboard->class_encrypted_id);

            $classroom_dashboard->sanitized_course_id = 
            $classroom_dashboard->sanitize($conn10,$classroom_dashboard->course_id);

            $classroom_dashboard->fetchClassData();

            $classroom_dashboard->cacheClassQuery();

            $classroom_dashboard->defineClassData();

            $classroom_dashboard->displayClassroomHeading();

            $classroom_dashboard->cacheClassHeading();

            if(isset($_SESSION['student_session'])){

                $classroom_dashboard->fetchClassAttendance();

                $classroom_dashboard->fetchStudentData();
    
                $classroom_dashboard->cacheStudentData();
    
                $classroom_dashboard->definingStudentData();

                $classroom_dashboard->insertIntoAttendance();

                $classroom_dashboard->displayAttendanceAlert();

            }

            $classroom_dashboard->fetchAttendanceTotal();

            $classroom_dashboard->cacheAttendanceTotalQuery();

            $classroom_dashboard->defineAttendanceTotalData();
            
            $classroom_dashboard->displayAttendanceModal();

            $classroom_dashboard->displayClassTabs();

        }else{



        }

    }

}

$classroom_dashboard_controller = new classroomDashboardController();

$classroom_dashboard_controller->controller();


?>