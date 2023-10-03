<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-settings/clear-class-attendance-model.php

** About : this module deletes the class attendance

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the clear class attendance class
 * fetch the class id and course id
 * define the clear class attendance query
 * redirect the user to the class settings
 * perform administrative functions
 * 
 * *
 */

class clearClassAttendance{

    public $class_id;

    public $course_id;


    public $clear_class_attendance_status;

    //fetch the class id and course id
    public function fetchclassIdAndCourseId(){

        $this->class_id = $_POST['classId'];

        $this->course_id = $_POST['courseId'];

    }

    //define the clear class attendance query
    public function clearClassAttendanceQuery(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        //attendance db connection
        include('../../resources/database/courses-classes-attendance-db-connection.php');

        $clear_class_attendance_query = "DELETE FROM attendance_of_class_".$this->class_id."";

        if($conn11->query($clear_class_attendance_query)){

            $this->clear_class_attendance_status = TRUE;

        }else{

            $this->clear_class_attendance_status = FALSE;

        }

        mysqli_close($conn11);

    }

    //redirect the user to the class settings
    public function redirect(){

        if($this->clear_class_attendance_status == TRUE){

            header("location:index.php?classId=$this->class_id&&courseId=$this->course_id&&clearClassAttendance=TRUE");

        }else{

            echo "Could not update";

        }

    }

}


?>