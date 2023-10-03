<?php 

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-settings/clear-class-attendance-model.php

** About : this module deletes the class attendance

*/ 

class clearClassAttendanceController{

    public function controller(){

        include('clear-class-attendance-model.php');

        $clear_class_attendance = new clearClassAttendance();

        if(!empty($_POST['classId']) && !empty($_POST['classId'])){

            $clear_class_attendance->fetchclassIdAndCourseId();

            $clear_class_attendance->clearClassAttendanceQuery();

            $clear_class_attendance->redirect();

        }

    }

}

$clear_class_attendance_controller = new clearClassAttendanceController();

$clear_class_attendance_controller->controller();

?>