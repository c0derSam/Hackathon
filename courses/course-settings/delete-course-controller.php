<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/courses-settings/delete-course-controller.php

** About : this module controlls the delete course model

*/ 

class deleteCourseController{

    public function controller(){

        include('delete-course-model.php');

        $delete_course = new deleteCourse();

        if(isset($_POST['courseId']) && isset($_POST['incrementId'])){

            $delete_course->fetchCourseId();

            $delete_course->deleteCourseQuery();

            $delete_course->reorderCourseDb();

            $delete_course->dropCourseEnrolledStudents();

            $delete_course->dropCourseClassTable();

            $delete_course->redirect();

        }

    }

}

$delete_course_controller = new deleteCourseController();

$delete_course_controller->controller();

?>