<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : dashboard/lecturer-dashboard/lecturer-dashboard-controller.php

** About : this module controlls the lecturer dashboard model

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the lecturer dasboard controller class
 * define the controller function
 * 
 * *
 */

//define the lecturer dasboard controller class
class lecturerDashboardController{ 

    public function controller(){

       include('lecturer-dashboard-model.php');

       $lecturer_dashboard = new lecturerDashboard();

       $lecturer_dashboard->fetchLecturerSession();

       $lecturer_dashboard->fetchLecturerCourses();

       $lecturer_dashboard->cachedCourseQuery();

       $lecturer_dashboard->displayCourse();

    }

}

$lecturer_dashboard_controller = new lecturerDashboardController();

$lecturer_dashboard_controller->controller();

?>