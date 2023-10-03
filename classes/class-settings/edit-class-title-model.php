<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-settings/edit-class-title-model.php

** About : this module updates the class title in the database

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the edit class title class
 * fetch the class id and course id
 * fetch the lecturer updated class title
 * sanitize the lecturer updated class title
 * define the update class title query
 * redirect the user to the class settings
 * perform administrative functions
 * 
 * *
 */

//define the edit class title class
class editClassTitle{

    public $class_id;

    public $course_id;

    public $class_title;

    public $sanitize_class_title;


    public $update_title_status;

    //fetch the class id and course id
    public function fetchclassIdAndCourseId(){

        $this->class_id = $_POST['classId'];

        $this->course_id = $_POST['courseId'];

    }

    //fetch the lecturer updated class title
    public function fetchUpdatedLecturerClassTitle(){

        $this->class_title = $_POST['classTitle'];

    }

    //sanitize the lecturer updated class title
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //define the update class title query
    public function updateClassTitleQuery(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-db-connection.php');

        $update_class_title_query = "UPDATE classes_of_course_".$this->course_id." SET class_topic = '$this->sanitize_class_title'
        WHERE class_encrypted_id = '$this->class_id'";

        if($conn10->query($update_class_title_query)){

            $this->update_title_status = TRUE;

        }else{

            $this->update_title_status = FALSE;

        }

        mysqli_close($conn10);

    }

    //redirect the user to the class settings
    public function redirect(){

        if($this->update_title_status == TRUE){

            header("location:index.php?classId=$this->class_id&&courseId=$this->course_id&&classTitleUpdate=TRUE");

        }else{

            echo "Could not update";

        }

    }

}

?>