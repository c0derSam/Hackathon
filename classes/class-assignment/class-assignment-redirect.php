<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-assignment/class-assignment-redirect.php

** About : this module redirects the user type(lecturer,student) to the assignment module

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the class assignment redirect class
 * fetch the class id and store the id a session
 * fetch the user sessions
 * define the lecturer redirect assignment route
 * define the student redirect assignment route
 * perform administrative functions
 * 
 * *
 */

session_start();

//define the class assignment redirect class
class classAssignmentRedirect{

    public $class_id;

    public $course_id;

    //fetch the class id and store the id a session
    public function fetchClassIdAndCourseId(){

       $this->class_id = $_GET['classId'];
       
       $this->course_id = $_GET['courseId'];

    }

    //fetch the user sessions
    public function fetchLecturerAndStudentSession(){

        if(isset($_SESSION['lecturer_session'])){

            //define the lecturer redirect assignment route
            header("location:class-assignment-grade-form-controller.php?classId=$this->class_id&&courseId=$this->course_id");

        }elseif(isset($_SESSION['student_session'])){

            //define the student redirect assignment route
            header("location:student-class-assignment-controller.php?classId=$this->class_id&&courseId=$this->course_id");

        }

    }

}

$class_assignment_redirect = new classAssignmentRedirect();

if(isset($_SESSION['student_session']) or isset($_SESSION['lecturer_session']) && !empty($_GET['classId']) && !empty($_GET['courseId']) ){

    $class_assignment_redirect->fetchClassIdAndCourseId();

    $class_assignment_redirect->fetchLecturerAndStudentSession();

}
