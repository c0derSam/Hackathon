<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/courses-settings/clear-enrolled-students-model.php

** About : this module clears the course enrollled students

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the clear course enrolled students class
 * fetch the course id
 * define the clear course enrolled student query
 * redirect the user to the course settings
 * perform administrative functions
 * 
 * *
 */

//define the clear course enrolled students class
class clearCourseEnrolledStudents{

    public $course_id;


    public $clear_courses_enrolled_students_query_status;

    //fetch the course id
    public function fetchCourseId(){

        $this->course_id = $_POST['courseId'];

    }

    //define the clear course enrolled student query
    public function clearCourseEnrolledStudentsQuery(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/courses-students-enrolled-db-connection.php');

        $clear_enrolled_students = "DELETE FROM enrolled_students_of_course_".$this->course_id."";

        if($conn9->query($clear_enrolled_students)){

            $this->clear_courses_enrolled_students_query_status = TRUE;

        }else{

            $this->clear_courses_enrolled_students_query_status = FALSE;

        }

        mysqli_close($conn9);

    }

    //redirect the user to the cOURSE settings
    public function redirectLecturer(){

        if($this->clear_courses_enrolled_students_query_status == TRUE){

            header("location:index.php?courseId=$this->course_id&&clearCourseEnrolledStudentsAlert=true");

        }else{

            echo "There are no students";

        }

    }

}

?>