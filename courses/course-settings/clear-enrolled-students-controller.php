<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/courses-settings/clear-enrolled-students-controller.php

** About : this module controll the clear enrolled students  model

*/ 

class clearCourseEnrolledStudentsController{

    public function controller(){

       include('clear-enrolled-students-model.php');

       $clear_enrolled_students = new clearCourseEnrolledStudents();

        if(isset($_POST['courseId'])){


            $clear_enrolled_students->fetchCourseId();

            $clear_enrolled_students->clearCourseEnrolledStudentsQuery();

            $clear_enrolled_students->redirectLecturer();

        }


    }

}

$clear_course_enrolled_student_controller = new clearCourseEnrolledStudentsController();

$clear_course_enrolled_student_controller->controller();

?>