<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : dashboard/student-dashboard/unenroll-course-model.php

** About : this module unenroll the students from the course choosen to unenroll from

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the unenroll course class
 * fetch the course id
 * fetch the student session
 * delete the course data from the student course enrolled database
 * delete the stduent data from the course enrolled database
 * redorder the course enrolled database
 * redirect the studet to the student dashboard
 * display the course
 * 
 * *
 */

class unenrollFromCourse{

    public $course_id;

    public $id;


    public $student_session;

    public $delete_query_status;
    
    public $delete_stduent_from_course_status;

    //fetch the course id
    public function fetchCourseId(){

        $this->course_id = $_GET['courseId'];

        $this->id = $_GET['id'];

    }

    // fetch the student session
    public function fetchStudentSession(){

        $this->student_session = $_SESSION['student_session'];

    }

    //delete the course data from the student course enrolled database
    public function deleteStudentEnrolledCourse(){

        //require the env library
        require('../../vendorEnv/autoload.php');
 
        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();
 
        // user db connection
        include('../../resources/database/students-courses-db-connection.php'); //conn2

        $delete_query = "DELETE FROM enrolled_courses_of_student_".$this->student_session." 
        WHERE course_enncrypted_id = '$this->course_id'";

        if($conn2->query($delete_query)){

           $this->delete_query_status = TRUE;

        }else{

           $this->delete_query_status = FALSE;

        }

        mysqli_close($conn2);

    }

    //redorder the course enrolled database
    public function reorderStudentCoursesEnrolled(){

        //require the env library
        require('../../vendorEnv/autoload.php');
 
        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();
 
        // user db connection
        include('../../resources/database/students-courses-db-connection.php'); //conn2

        $reorder_query = "ALTER TABLE enrolled_courses_of_student_".$this->student_session."  
        AUTO_INCREMENT = $this->id";

        $conn2->query($reorder_query);

        mysqli_close($conn2);

    }

    //redirect the studet to the student dashboard
    public function redirectStudent(){

        if($this->delete_stduent_from_course_status == TRUE){

            header('location:index.php?unenrollAlert="true"');

        }

    }

    //delete the stduent data from the course enrolled database
    public function deleteStudentFromCourseEnrolledDb(){

        if($this->delete_query_status == TRUE){

            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // course db connection
            include('../../resources/database/courses-students-enrolled-db-connection.php'); //conn9

            $delete_query = "DELETE FROM enrolled_students_of_course_".$this->course_id." 
            WHERE student_encrypted_id = '$this->student_session'";

            if($conn9->query($delete_query)){

                $this->delete_stduent_from_course_status = TRUE;

            }else{

                $this->delete_stduent_from_course_status = FALSE;

            }

            mysqli_close($conn9);

        }

    }

}

?>