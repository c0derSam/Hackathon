<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/courses-settings/edit-course-code-model.php

** About : this module updates the course code

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the edit course code class
 * fetch the course id
 * fetch the lecturer updated course code
 * sanitize the lecturer updated course code
 * define the edit course code query
 * redirect the user to the course settings
 * perform administrative functions
 * 
 * *
 */

//define the edit course title class
class editCourseCode{

    public $course_id;

    public $course_code;


    public $sanitized_course_code;


    public $edit_course_query_status;

    //fetch the course id
    public function fetchCourseId(){

        $this->course_id = $_POST['courseId'];

    }

    //fetch the lecturer updated course code
    public function fetchCourseCode(){

        $this->course_code = $_POST['courseCode'];

    }

    //sanitize the lecturer updated course code
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //define the edit course code query
    public function editCourseCodeQuery(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/courses-db-connection.php');//conn8

        $edit_course_code_query = "UPDATE courses SET course_code = '$this->sanitized_course_code' WHERE course_encrypted_id = 
        '$this->course_id'";

        if($conn8->query($edit_course_code_query)){

            $this->edit_course_query_status = TRUE;

        }else{

            $this->edit_course_query_status = FALSE;

        }

        mysqli_close($conn8);

    }

    //redirect the user to the class settings
    public function redirectLecturer(){

        if($this->edit_course_query_status == TRUE){

            header("location:index.php?courseId=$this->course_id&&courseCodeAlert=true");

        }else{



        }

    }

}

?>