<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-assignment/grade-student-assignment-controller.php

** About : this module controlls the grade sudent assignment model

*/ 

session_start();

class processGradeController{

    public function controller(){

       include('grade-student-assignment-model.php');

       $process_grade = new processGrade();

       $process_grade->fetchGradeData();

       $process_grade->updateGrade();

       $process_grade->notifyStudent();
       
       $process_grade->redirectLecturer();

    }

}

$process_grade_controller = new processGradeController();

//session_regenerate_id(true);

if(isset($_SESSION['lecturer_session'])){

    $process_grade_controller->controller();

}else{

    header('location:logout-success.php');

}
?>