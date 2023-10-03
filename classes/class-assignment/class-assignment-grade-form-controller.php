<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-assignment/class-assignment-grade-form-controller.php

** About : this module controlls the class assignment grade form model

*/ 

session_start();

class assignmentGradeController{

    public function controller(){

       include('class-assignment-grade-form-model.php');

       $assignment_grade = new assignmentGrade();

       $assignment_grade->fetchClassAndCourseId();

       $assignment_grade->fetchLecturerSession();

       $assignment_grade->header();

       $assignment_grade->displaySubheading();

        if(!empty($_GET['gradeAlert'])){

          $assignment_grade->displayGradeAlert();

        }

       $assignment_grade->fetchClassAssignmentQuestion();

       $assignment_grade->cacheClassAssignmentQuestion();

       $assignment_grade->classroomAsignmentData();

       $assignment_grade->displayAssignment();

       $assignment_grade->fetchUngradedAssignmentTotal();

       $assignment_grade->cacheUngradedAssignmentTotalQuery();

       $assignment_grade->defineUngradedTotal();

       $assignment_grade->fetchGradedAssignmentTotal();

       $assignment_grade->cacheGradedAssignmentTotalQuery();

       $assignment_grade->defineGradedTotal();

       $assignment_grade->displayGradeFormHeading();

       $assignment_grade->fetchUngradedAssignment();

       $assignment_grade->cacheUngradedAssignmentQuery();

       $assignment_grade->displayUngradedAssignmentData();

    }

}

$assignment_grade_controller = new assignmentGradeController();

session_regenerate_id(true);

if(isset($_SESSION['lecturer_session']) && !empty($_GET['classId']) && !empty($_GET['courseId'])){

    $assignment_grade_controller->controller();

}else{

    header('location:logout-success.php');

}


?>