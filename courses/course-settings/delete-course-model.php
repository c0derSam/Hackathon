<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/courses-settings/delete-course-model.php

** About : this module deletes the course from the database

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the delete course class
 * fetch the course id and course auto increment id
 * define the delete course query 
 * reorder the courses database
 * drop the auto generated course database
 * redirect the user to the course settings
 * perform administrative functions
 * 
 * *
 */

//define the delete course class
class deleteCourse{

    public $course_id;

    public $incremented_id;


    public $delete_course_query_status;

    //fetch the course id and course auto increment id
    public function fetchCourseId(){

       $this->course_id = $_POST['courseId'];

       $this->incremented_id = $_POST['incrementId'];

    }

    //define the delete course query 
    public function deleteCourseQuery(){

       //require the env library
       require('../../vendorEnv/autoload.php');

       $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
       $user_db_conn_env->load();

       // user db connection
       include('../../resources/database/courses-db-connection.php');//conn8

       $delete_query = "DELETE FROM courses WHERE course_encrypted_id = '$this->course_id'";

        if($conn8->query($delete_query)){

            $this->delete_course_query_status = TRUE;

        }else{

            $this->delete_course_query_status = FALSE;

        }

        mysqli_close($conn8);

    }

    //reorder the courses database
    public function reorderCourseDb(){

        //require the env library
       require('../../vendorEnv/autoload.php');

       $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
       $user_db_conn_env->load();

       // user db connection
       include('../../resources/database/courses-db-connection.php');//conn8

       $reorder_query = "ALTER TABLE courses AUTO_INCREMENT = $this->incremented_id";

       $conn8->query($reorder_query);

       mysqli_close($conn8);

    }

    //drop the auto generated course database
    public function dropCourseEnrolledStudents(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/courses-students-enrolled-db-connection.php');

        $drop_course_enrolled_query = "DROP TABLE enrolled_students_of_course_".$this->course_id."";

        $conn9->query($drop_course_enrolled_query);

        mysqli_close($conn9);

    }

    public function dropCourseClassTable(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/courses-classes-db-connection.php');

        $drop_course_class_query = "DROP TABLE classes_of_course_".$this->course_id."";

        $conn10->query($drop_course_class_query);

        mysqli_close($conn10);

    }

    //redirect the user to the course settings
    public function redirect(){

        header('location:../../dashboard/lecturer-dashboard/index.php?deleteCourseAlert=true');

    }

}


?>