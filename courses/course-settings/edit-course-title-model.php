<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/courses-settings/edit-course-title-model.php

** About : this module updates the course title

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the edit course title class
 * fetch the course id
 * fetch the lecturer updated course title
 * sanitize the lecturer updated course title
 * define the edit course title query
 * redirect the user to the course settings
 * perform administrative functions
 * 
 * *
 */

//define the edit course code class
class editCourseTitle{

    public $course_id;

    public $course_title;


    public $sanitized_course_title;


    public $edit_course_query_status;

    //fetch the course id
    public function fetchCourseId(){

        $this->course_id = $_POST['courseId'];

    }

    //fetch the lecturer updated course title
    public function fetchCourseTitle(){

        $this->course_title = $_POST['courseTitle'];

    }

    //sanitize the lecturer updated course title
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //define the edit course title query
    public function editCourseTitleQuery(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/courses-db-connection.php');//conn8

        $edit_course_title_query = "UPDATE courses SET course_title = '$this->sanitized_course_title' WHERE course_encrypted_id = 
        '$this->course_id'";

        if($conn8->query($edit_course_title_query)){

            $this->edit_course_query_status = TRUE;

        }else{

            $this->edit_course_query_status = FALSE;

        }

        mysqli_close($conn8);

    }

    //redirect the user to the class settings
    public function redirectLecturer(){

        if($this->edit_course_query_status == TRUE){

            header("location:index.php?courseId=$this->course_id&&courseTitleAlert=true");

        }else{



        }

    }

}

?>