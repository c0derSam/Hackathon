<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : enroll-students-controller/enroll-students-model.php

** About : this module enrolls controlls the enroll students model

*/

session_start();

class enrollStudentController{

    public function controller(){

       include('enroll-students-model.php');

       $enroll_student = new enrollStudent();

       $enroll_student->fetchCourseId();

       $enroll_student->fetchStudentSession();

       $enroll_student->fetchStudentData();

       $enroll_student->cacheStudentData();

       $enroll_student->defineStudentData();

       $enroll_student->insertIntoCoursesEnrolledStudents();

       $enroll_student->fetchCourseData();

       $enroll_student->cacheCourseData();

       $enroll_student->defineCourseData();

       $enroll_student->insertIntoStduentEnrolledCourses();

       $enroll_student->notifyStudent();

       $enroll_student->notifyLecturer();

       $enroll_student->redirectStudentToCourseDashboard();

    }

}

$enroll_student_controller = new enrollStudentController();

session_regenerate_id(true);

if(isset($_SESSION['student_session'])){

    $enroll_student_controller->controller();

}else{

    echo "Cant enroll as a lecturer";

}


?>