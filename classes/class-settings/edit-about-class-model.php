<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-settings/edit-about-class-model.php

** About : this module updates the about class in the database

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the edit about class class
 * fetch the class id and course id
 * fetch the lecturer updated about class
 * sanitize the lecturer updated about class
 * define the update about class query
 * redirect the user to the class settings
 * perform administrative functions
 * 
 * *
 */

//define the edit about class class
class editAboutClass{

    public $class_id;

    public $course_id;

    public $about_class;

    public $sanitize_about_class;

    
    public $update_about_status;

    //fetch the class id and course id
    public function fetchclassIdAndCourseId(){

        $this->class_id = $_POST['classId'];

        $this->course_id = $_POST['courseId'];

    }

    //fetch the lecturer updated about class
    public function fetchUpdatedLecturerAboutClass(){

        $this->about_class = $_POST['aboutClass'];

    }

    //sanitize the lecturer updated about class
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //define the update about class query
    public function updateAboutClassQuery(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-db-connection.php');

        $update_about_class_query = "UPDATE classes_of_course_".$this->course_id." SET about_class = '$this->sanitize_about_class'
        WHERE class_encrypted_id = '$this->class_id'";

        if($conn10->query($update_about_class_query)){

            $this->update_about_status = TRUE;

        }else{

            $this->update_about_status = FALSE;

        }

        mysqli_close($conn10);

    }

    //redirect the user to the class settings
    public function redirect(){

        if($this->update_about_status == TRUE){

            header("location:index.php?classId=$this->class_id&&courseId=$this->course_id&&aboutClassUpdate=TRUE");

        }else{

            echo "Could not update";

        }

    }

}

?>