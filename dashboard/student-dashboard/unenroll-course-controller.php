<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : dashboard/student-dashboard/unenroll-course-model.php

** About : this module controlls the unenroll course model

*/ 

session_start();

class unenrollFromCourseController{

    public function controller(){

      include('unenroll-course-model.php');

      $unenroll_from_course = new unenrollFromCourse();

      $unenroll_from_course->fetchCourseId();

      $unenroll_from_course->fetchStudentSession();

      $unenroll_from_course->deleteStudentEnrolledCourse();

      $unenroll_from_course->reorderStudentCoursesEnrolled();

      $unenroll_from_course->deleteStudentFromCourseEnrolledDb();

      $unenroll_from_course->redirectStudent();

    }

}

$unenroll_from_course_controller = new unenrollFromCourseController();

if(isset($_SESSION['student_session'])){

    $unenroll_from_course_controller->controller();

}else{

    echo 'Not logged in';

}

?>