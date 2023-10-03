<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/courses-settings/edit-about-course-controller.php

** About : this module updates the course about

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the edit about course class
 * fetch the course id
 * fetch the lecturer updated about course
 * sanitize the lecturer updated about course
 * define the edit about course query
 * redirect the user to the course settings
 * perform administrative functions
 * 
 * *
 */

//define the edit about course class
class editAboutCourse{

    public $course_id;

    public $about_course;


    public $sanitized_about_course;


    public $edit_course_query_status;

    //fetch the course id
    public function fetchCourseId(){

        $this->course_id = $_POST['courseId'];

    }

    //fetch the lecturer updated about course
    public function fetchAboutCourse(){

        $this->about_course = $_POST['aboutCourse'];

    }

    //sanitize the lecturer about course
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //define the edit about course query
    public function editAboutCourseQuery(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/courses-db-connection.php');//conn8

        $edit_about_course_query = "UPDATE courses SET about_course = '$this->sanitized_about_course' WHERE course_encrypted_id = 
        '$this->course_id'";

        if($conn8->query($edit_about_course_query)){

            $this->edit_course_query_status = TRUE;

        }else{

            $this->edit_course_query_status = FALSE;

        }

        mysqli_close($conn8);

    }

    //redirect the user to the class settings
    public function redirectLecturer(){

        if($this->edit_course_query_status == TRUE){

            header("location:index.php?courseId=$this->course_id&&aboutCourseAlert=true");

        }else{


        }

    }

}

?>