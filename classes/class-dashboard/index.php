<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-dashboard/index.php

** About : this module displays the index view of the class dashboard

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the index class view
 * include the header
 * include the class dashboard model
 * perform administrative functions
 * 
 * *
 */

session_start();

class indexView{

    //include the header
    public function header(){

        include('../header/header.php');

    }

    //include the class dashboard model
    public function displayClassDashboard(){

        include('class-dashboard-controller.php');

    }

}

$index_view = new indexView();

$index_view->header();

//session_regenerate_id(true);

if(isset($_SESSION['student_session']) or isset($_SESSION['lecturer_session'])){

    $index_view->displayClassDashboard();

}else{

    header('location:logout-success.php');

}

?>
