<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-attendance/class-attendance-controller.php

** About : this module controlls the class attendance model

*/

session_start();

class classAttendanceController{

    public function controller(){

        if(isset($_SESSION['student_session']) or isset($_SESSION['lecturer_session'])){

            include('class-attendance-model.php');

            $class_attendance = new classAttendance();

            $class_attendance->fetchClassIdAndCourseId();

            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // course db connection
            include('../../resources/database/courses-classes-attendance-db-connection.php');

            $class_attendance->sanitized_class_id = $class_attendance->sanitize($conn11,$class_attendance->classId);

            $class_attendance->sanitized_course_id = $class_attendance->sanitize($conn11,$class_attendance->courseId);

            $class_attendance->heading();

            $class_attendance->subHeading();

            $class_attendance->cacheSubheading();

            if(!empty($_GET['classId'])){

                $class_attendance->fetchAttendanceQuery();

                $class_attendance->cacheAttendanceQuery();

                $class_attendance->displaClassAttendance();

                mysqli_close($conn11);

            }else{

                echo "Not set";

            }

        }else{

            header('location:logout-success.php');

        }

    }

}

$class_attendance_controller = new classAttendanceController();

$class_attendance_controller->controller();

?>