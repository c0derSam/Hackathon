<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : dashboard/student-dashboard/student-dashboard-controller.php

** About : this module controlls the student dashboard model

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the student dasboard controller class
 * define the controller function
 * 
 * *
 */

//define the student dasboard controller class
class studentDashboardController{

    public function controller(){

       include('student-dashboard-model.php');

       $student_dashboard = new studentDashboard();

       $student_dashboard->fetchStudentSession();

       $student_dashboard->fetchStudentEnrolledCourses();

       $student_dashboard->cachedEnrolledCourseQuery();

       $student_dashboard->displayCourse();

    }

}

$student_dashboard_controller = new studentDashboardController();

$student_dashboard_controller->controller();

?>