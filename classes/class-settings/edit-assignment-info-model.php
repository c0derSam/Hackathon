<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-settings/edit-assignment-info-model.php

** About : this module updates the class assignment

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the edit class asignment
 * fetch the class id and course id
 * fetch the lecturer updated class assignment
 * sanitize the lecturer updated class assignment
 * define the update class assignment query
 * redirect the user to the class settings
 * perform administrative functions
 * 
 * *
 */

//define the edit class asignment
 class editClassAssignment{

    public $class_id;

    public $course_id;

    public $class_assignment;

    public $sanitize_class_assignment;

    
    public $update_assignment_status;


    //fetch the class id and course id
    public function fetchclassIdAndCourseId(){

        $this->class_id = $_POST['classId'];

        $this->course_id = $_POST['courseId'];

    }

    //fetch the lecturer updated class assignment
    public function fetchUpdatedLecturerClassAssignment(){

        $this->class_assignment = $_POST['assignment'];

    }

    //sanitize the lecturer updated class assignment
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //define the update class assignment query
    public function updateClassAssignmentQuery(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-db-connection.php');

        $update_class_assignment_query = "UPDATE classes_of_course_".$this->course_id." SET assignment_topic = '$this->sanitize_class_assignment'
        WHERE class_encrypted_id = '$this->class_id'";

        if($conn10->query($update_class_assignment_query)){

            $this->update_assignment_status = TRUE;

        }else{

            $this->update_assignment_statuss = FALSE;

        }

        mysqli_close($conn10);

    }

    //redirect the user to the class settings
    public function redirect(){

        if($this->update_assignment_status == TRUE){

            header("location:index.php?classId=$this->class_id&&courseId=$this->course_id&&classAssignmentUpdate=TRUE");

        }else{

            echo "Could not update";

        }

    }

 }

?>