<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-assignment/student-class-assignment-controller.php

** About : this module controlls the submitr class-assignment model

*/ 

session_start();

class submitAssignmentController{

    public function controller(){

        include('student-class-assignment-model.php');

        $submit_assignment = new submitAssignment();

        $submit_assignment->fetchClassIdAndCourseId();

        $submit_assignment->header();

        $submit_assignment->displayClassAssignmentSubheading();

        $submit_assignment->fetchStudentSession();

        $submit_assignment->fetchClassAssignmentQuestion();

        $submit_assignment->cacheClassAssignmentQuestion();

        $submit_assignment->classroomAsignmentData();

        $submit_assignment->displayAssignment();

        $submit_assignment->fetchStudentAssignmentData();

        $submit_assignment->cacheStudentAssignmentData();

        $submit_assignment->defineStudentAssignment();

        $submit_assignment->displayStudentAssignmentGrade();

        $submit_assignment->displayStudentAssignmentForm();

    }

}

$submit_assignment_controller = new submitAssignmentController();

session_regenerate_id(true);

if(isset($_SESSION['student_session']) && !empty($_GET['classId']) && !empty($_GET['courseId'])){

    $submit_assignment_controller->controller();

}else{

    header('location:logout-success.php');

}


?>