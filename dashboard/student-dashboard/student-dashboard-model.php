<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : dashboard/student-dashboard/student-dashboard-model.php

** About : this module fetches and display the courses the students are enrolled in from the database

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the student dashboard
 * fetch the student session
 * fetch the student courses from the database
 * cache the course query
 * display the course
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

// define the student dashboard
class studentDashboard{

    public $student_session;

    public $cached_enrolled_courses;

    public $student_courses_enrolled_result;

    public $enrolled_course_query_status;

    public $student_course_encrolled_row;

    public $auto_increment_id;

    public $course_code;

    public $course_title;

    public $course_about;

    public $course_encrypted_id;

    //fetch the student session
    public function fetchStudentSession(){

        $this->student_session = $_SESSION['student_session'];
 
        //echo $this->student_session;
  
    }

    //fetch the student enrolled courses from the database
    public function fetchStudentEnrolledCourses(){

        //require the env library
        require('../../vendorEnv/autoload.php');
 
        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();
 
        // user db connection
        include('../../resources/database/students-courses-db-connection.php');
 
        $student_enrolled_courses_query = "SELECT * FROM enrolled_courses_of_student_".$this->student_session." 
        ORDER BY id DESC";
 
        $this->student_courses_enrolled_result = $conn2->query($student_enrolled_courses_query);
 
         if($this->student_courses_enrolled_result->num_rows > 0){
 
             $this->enrolled_course_query_status = TRUE;
 
         }else{
 
             $this->enrolled_course_query_status = FALSE;
 
             echo '

                <div align="center">
             
                    <div class="alert alert-warning" style="width:250px;">

                      <p class="lead">No Courses To Display</p>

                    </div>

                </div>
             
             ';
 
         }

         mysqli_close($conn2);
 
    }

    //cache the course query
    public function cachedEnrolledCourseQuery(){

        if($this->enrolled_course_query_status == TRUE){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "student_enrolled_courses";
            $Cached_student_enrolled_courses_result = $InstanceCache->getItem($key);
            
            if (!$Cached_student_enrolled_courses_result->isHit()) {
                $Cached_student_enrolled_courses_result->set($this->student_courses_enrolled_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_student_enrolled_courses_result); // Save the cache item just like you do with doctrine and entities
            
                $this->cached_enrolled_courses = $Cached_student_enrolled_courses_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
            
            } else {
                
                $this->cached_enrolled_courses= $Cached_student_enrolled_courses_result->get();
                //echo 'READ FROM CACHE // ';
            
                $InstanceCache->deleteItem($key);
            }

        }

    }

    public function displayCourse(){

        if($this->enrolled_course_query_status == TRUE){

            while($student_course_encrolled_row = $this->cached_enrolled_courses->fetch_assoc()){

                $this->auto_increment_id = $student_course_encrolled_row['id'];

                $this->course_code = $student_course_encrolled_row['course_code'];

                $this->course_title = $student_course_encrolled_row['course_title'];

                $this->course_about = $student_course_encrolled_row['course_about'];

                $this->course_encrypted_id = $student_course_encrolled_row['course_enncrypted_id'];

                echo '

                <div class="col">

                <div class="d-flex shadow p-4 mb-4 bg-light" style="width:300px">

                    <div class="flex-shrink-0">
                      <i class="fa fa-mortar-board" style="font-size:30px;"></i>
                    </div>

                    <div class="flex-grow-1 ms-3">

                       <h5>'.$this->course_code.'</h5>

                       <p style="max-width:200px;white-space:nowrap;overflow:hidden;
                          text-overflow:ellipsis;">
                          '.$this->course_title.'<br> 

                          <span class="text-muted"><small><b>Created by Test Lecturer</b></small></span>
                   
                        </p>

                        <p style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                           '.$this->course_about.'
                        </p>

                        <button class="btn btn-outline-dark">

                          <a class="text-dark" href="../../courses/courses-dashboard/index.php?id='.$this->course_encrypted_id.'" 
                          style="text-decoration:none;">
                         
                           View course <i class="fa fa-external-link"></i>

                          </a>

                        </button>

                        <button class="btn btn-outline-dark">

                          <a class="text-danger" href="unenroll-course-controller.php?courseId='.$this->course_encrypted_id.'&&id='.$this->auto_increment_id.'" 
                          style="text-decoration:none;">
                         
                           Unenroll <i class="fa fa-trash"></i>

                          </a>


                        </button>

                    </div>

                </div>
                <!-- course container flex display -->

                </div>
                
                ';

            }

        }

    }

}

?>