<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : enroll-students-controller/enroll-students-model.php

** About : this module enrolls students into the course database and inlcudes the course into the students course enrolled db

*/

/**PSUEDO ALGORITHM
 * *
 * define the enroll students class
 * fetch the course encrypted id
 * fetch the student session
 * fetch the students data
 * cache the student data query
 * define the student data query
 * insert the students data into course enrolled students database
 * fetch the course data
 * cache the course data query
 * define the course data
 * insert the course the data into the students enrolled courses database
 * notify the student
 * notify the lecturer
 * redirect the user to the course database
 * perform administrative functions
 * 
 * *
 */ 

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;


class enrollStudent{

    public $course_id;

    public $student_session;


    public $student_data_result;

    public $cached_student_result;

    public $student_fullname;

    public $student_username;
        
    public $student_matric_number;

    public $student_avatar;

    public $student_encrypted_id;

    public $type;

    public $date;

    public $time;

    public $status;


    public $course_result;

    public $course_query_status;

    public $cached_courses_row;

    public $course_code;
    
    public $course_title;
    
    public $about_course;

    public $course_search_id;

    public $course_encrypted_id;

    public $level;
    
    public $lecturer_in_charge;

    public $lecturer_encrypted_id;


    public function fetchCourseId(){

       $this->course_id = $_GET['course_id'];

    }

    //fetch the student session
    public function fetchStudentSession(){

       $this->student_session = $_SESSION['student_session'];

    }

    //fetch the students data, cache the student data query and define the student data query
    public function fetchStudentData(){

       //require the env library
       require('../../vendorEnv/autoload.php'); 

       $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
       $user_db_conn_env->load();

       // user db connection
       include('../../resources/database/users-db-connection.php');
       //conn1

       $student_data_query = "SELECT * FROM students WHERE encrypted_id = '$this->student_session'";

       $this->student_data_result = $conn1->query($student_data_query);

       mysqli_close($conn1);

    }

    public function cacheStudentData(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
     
        $InstanceCache = CacheManager::getInstance('files');
     
        $key = "enroll_student_data";
        $Cached_student_data_result = $InstanceCache->getItem($key);

        if (!$Cached_student_data_result->isHit()) {
             $Cached_student_data_result->set($this->student_data_result)->expiresAfter(1);//in seconds, also accepts Datetime
             $InstanceCache->save($Cached_student_data_result); // Save the cache item just like you do with doctrine and entities
     
             $this->cached_student_result = $Cached_student_data_result->get();
             //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
     
         } else {
         
             $this->cached_student_result = $Cached_student_data_result->get();
             //echo 'READ FROM CACHE // ';
     
             $InstanceCache->deleteItem($key);
        }

    }

    public function defineStudentData(){

        $student_data_row = $this->cached_student_result->fetch_assoc();

        $this->student_fullname = $student_data_row['fullname'];

        $this->student_username = $student_data_row['username'];
        
        $this->student_matric_number = $student_data_row['matric_number'];

        $this->student_avatar = $student_data_row['avatar'];

        $this->student_encrypted_id = $student_data_row['encrypted_id'];

        $this->type = "undefined";

        $this->date = date("Y/m/d");

        $this->time = date("h:i:sa");

        $this->status = "enrolled";

    }

    //insert the students data into course enrolled students database
    public function insertIntoCoursesEnrolledStudents(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/courses-students-enrolled-db-connection.php');
        //conn9

        $insert_into_course_enrolled_query = "INSERT INTO enrolled_students_of_course_".$this->course_id."
        (
            student_fullname,student_username,student_matric_number,student_avatar,student_encrypted_id,
            type,date,time,status
        )
        VALUES(
            '$this->student_fullname','$this->student_username','$this->student_matric_number','$this->student_avatar',
            '$this->student_encrypted_id','$this->type','$this->date','$this->time','$this->status'
        )
        ";

        if($conn9->query($insert_into_course_enrolled_query)){

            echo "Inserted into courses enrolled db";

        }else{

            echo "Could not insert";

        }

        mysqli_close($conn9);

    }

    //fetch the course data, cache the course data query and define the course data
    public function fetchCourseData(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();
 
        //db connection
        include('../../resources/database/courses-db-connection.php');
 
        $courses_query = "SELECT * FROM courses WHERE course_encrypted_id = '$this->course_id'";
 
        $this->course_result = $conn8->query($courses_query);
 
        mysqli_close($conn8);

    }

    public function cacheCourseData(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "courses-cache---";
        $Cached_courses_row = $InstanceCache->getItem($key);
        
        if (!$Cached_courses_row->isHit()) {
            $Cached_courses_row->set($this->course_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_courses_row); // Save the cache item just like you do with doctrine and entities
        
            $this->cached_courses_row = $Cached_courses_row->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            $this->cached_courses_row = $Cached_courses_row->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    public function defineCourseData(){

        $course_row = $this->cached_courses_row->fetch_assoc();

        $this->course_code = $course_row['course_code'];
    
        $this->course_title = $course_row['course_title'];
    
        $this->about_course = $course_row['about_course'];

        $this->course_search_id = $course_row['course_id'];
    
        $this->course_encrypted_id = $course_row['course_encrypted_id'];

        $this->level = $course_row['level'];
    
        $this->lecturer_in_charge = $course_row['lecturer_in_charge'];

        $this->lecturer_encrypted_id = $course_row['lecturer_encrypted_id'];

    }

    //insert the course the data into the students enrolled courses database
    public function insertIntoStduentEnrolledCourses(){

        //require the env library
        require('../../vendorEnv/autoload.php');
 
        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();
 
        // user db connection
        include('../../resources/database/students-courses-db-connection.php');

        $insert_into_student_enrolled_courses_query = "INSERT INTO enrolled_courses_of_student_".$this->student_session."
        (
            course_code,course_title,course_about,course_search_id,course_enncrypted_id,level,lecturer_in_charge,
            lecturer_encrypted_id,date_enrolled,time_enrolled
        )
        VALUES(
            '$this->course_code','$this->course_title','$this->about_course','$this->course_search_id','$this->course_encrypted_id',
            '$this->level','$this->lecturer_in_charge','$this->lecturer_encrypted_id','$this->date','$this->time'
        )
        ";

        if($conn2->query($insert_into_student_enrolled_courses_query)){

            echo "Inserted into the student enrolled db table";

        }else{

            echo "Could not insert into trhe student enrolled db table";

        }

        mysqli_close($conn2);

    }

    //notify the student
    public function notifyStudent(){

        $link = "../courses/courses-dashboard/index.php?id=";

        $message = "You have been enrolled into ";

        $status = "unread";

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/users-notification-db-connection.php');

        $notify_student_query = "INSERT INTO notification_for_user".$this->student_session."
        (
            link,message,date,time,status
        )
        VALUES(
            '$link$this->course_id','$message $this->course_code','$this->date','$this->time','$status'
        )
        ";

        if($conn15->query($notify_student_query)){

            echo "Student have been notified";

        }else{

            echo "Could not notify student";

        }

        mysqli_close($conn15);

    }

    //notify the lecturer
    public function notifyLecturer(){

        $link = "../courses/courses-dashboard/index.php?id=";

        $message = " enrolled into your";

        $status = "unread";

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/users-notification-db-connection.php');

        $notify_lecturer_query = "INSERT INTO notification_for_user".$this->lecturer_encrypted_id."
        (
            link,message,date,time,status
        )
        VALUES(
            '$link$this->course_id','$this->student_fullname $message $this->course_code','$this->date','$this->time','$status'
        )
        ";

        if($conn15->query($notify_lecturer_query)){

            echo "Student have been notified";

        }else{

            echo "Could not notify student";

        }

        mysqli_close($conn15);

    }

    //redirect the user to the course database
    public function redirectStudentToCourseDashboard(){

        header("location:../../courses/courses-dashboard/index.php?id=$this->course_id");

    }

}

?>