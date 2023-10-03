<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-settings/delete-class-model.php

** About : this module deletes the class from the database

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the delete class class
 * fetch the class id and course id
 * define the delete class query
 * reorder the class database table
 * delete the class instructional materials
 * drop the neccessary class tables
 * redirect the user to the course dashboard
 * perform administrative functions
 * 
 * *
 */

//define the delete class class
class deleteClass{

    public $auto_increment_id;

    public $class_id;

    public $course_id;

    public $class_material;

    public $class_note;


    public $delete_class_status;

    //fetch the class id and course id
    public function fetchclassIdAndCourseId(){

        $this->auto_increment_id = $_POST['autoIncrementId'];

        $this->class_id = $_POST['classId'];

        $this->course_id = $_POST['courseId'];

        $this->class_material = $_POST['classMaterial'];

        $this->class_note = $_POST['classNote'];

    }

    //define the delete class query
    public function deleteClassQuery(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-db-connection.php');

        $delete_class_query = "DELETE FROM classes_of_course_".$this->course_id." WHERE class_encrypted_id = '$this->class_id'";

        if($conn10->query($delete_class_query)){

            $this->delete_class_status = TRUE;

        }else{
 
            $this->delete_class_status = FALSE;

        }

        mysqli_close($conn10);
    }

    //reorder the class database table
    public function reorderClassDbTable(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-db-connection.php');

        $reorder_class_query = "ALTER TABLE classes_of_course_".$this->course_id."  
        AUTO_INCREMENT = $this->auto_increment_id";

        if($conn10->query($reorder_class_query)){


        }else{


        }

        mysqli_close($conn10);

    }

    public function deleteClassNote(){

        unlink($this->class_note);

    }

    // drop the neccessary class tables
    public function dropClassAttendanceTable(){

        if($this->delete_class_status == TRUE){

            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // course db connection
            include('../../resources/database/courses-classes-attendance-db-connection.php');

            $delete_class_attendance_table_query = "DROP TABLE attendance_of_class_".$this->class_id."";

            if($conn11->query($delete_class_attendance_table_query)){



            }else{



            }

            mysqli_close($conn11);

        }

    }

    public function deleteClassAssignmentTable(){

        if($this->delete_class_status == TRUE){

            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // course db connection
            include('../../resources/database/courses-classes-assignments-db-connection.php');

            $delete_class_assignment_table_query = "DROP TABLE assignment_submissions_of_class_".$this->class_id."";

            if($conn16->query($delete_class_assignment_table_query)){

         

            }else{



            }

        }

    }

    //redirect the user to the course dashboard
    public function redirect(){

        if($this->delete_class_status == TRUE){

            header("location:../../courses/courses-dashboard/index.php?id=$this->course_id");

        }else{

            echo "Could not update";

        }

    }

}

?>