<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : dashboard/lecturer-dashboard/lecturer-dashboard-model.php

** About : this module fetches and display the lecturer courses from the database

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the lecturer dashboard
 * fetch the lecturer session
 * fetch the lecturer courses from the database
 * cache the course query
 * display the course
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

// define the lecturer dashboard
class lecturerDashboard{

    public $lecturer_session;

    public $lecturer_course_row;

    public $cached_courses;

    public $lecturer_courses_result;

    public $course_query_status;

    public $course_code;

    public $course_title;

    public $about_course;

    public $course_encrypted_id;

    public $lecturer_in_charge;

    //fetch the lecturer session
    public function fetchLecturerSession(){

       $this->lecturer_session = $_SESSION['lecturer_session'];

       //echo $this->lecturer_session;
 
    }

    //fetch the lecturer courses from the database
    public function fetchLecturerCourses(){
 
       //require the env library
       require('../../vendorEnv/autoload.php');

       $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
       $user_db_conn_env->load();

       // user db connection
       include('../../resources/database/courses-db-connection.php');

       $lecturer_courses_query = "SELECT * FROM courses WHERE lecturer_encrypted_id = '$this->lecturer_session' ORDER BY id DESC";

       $this->lecturer_courses_result = $conn8->query($lecturer_courses_query);

        if($this->lecturer_courses_result->num_rows > 0){

            $this->course_query_status = TRUE;

        }else{

            $this->course_query_status = FALSE;

            echo '

                <div align="center">
             
                    <div class="alert alert-warning" style="width:250px;">

                      <p class="lead">No Courses To Display</p>

                    </div>

                </div>
             
             ';

        }

        mysqli_close($conn8);

    }

    //cache the course query
    public function cachedCourseQuery(){

        if($this->course_query_status == TRUE){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "lecturer_courses";
            $Cached_lecturer_courses_row = $InstanceCache->getItem($key);
            
            if (!$Cached_lecturer_courses_row->isHit()) {
                $Cached_lecturer_courses_row->set($this->lecturer_courses_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_lecturer_courses_row); // Save the cache item just like you do with doctrine and entities
            
                $this->cached_courses = $Cached_lecturer_courses_row->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
            
            } else {
                
                $this->cached_courses = $Cached_lecturer_courses_row->get();
                //echo 'READ FROM CACHE // ';
            
                $InstanceCache->deleteItem($key);
            }

        }

    }

    //display the course
    public function displayCourse(){

        if($this->course_query_status == TRUE){

            echo '
            
            <div class="row">
            
            ';

            while($this->lecturer_course_row = $this->cached_courses->fetch_assoc()){

                $this->course_code = $this->lecturer_course_row['course_code'];

                $this->course_title = $this->lecturer_course_row['course_title'];

                $this->about_course = $this->lecturer_course_row['about_course'];

                $this->course_encrypted_id = $this->lecturer_course_row['course_encrypted_id'];

                $this->lecturer_in_charge = $this->lecturer_course_row['lecturer_in_charge'];

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

                          <span class="text-muted"><small><b>Created by '.$this->lecturer_in_charge.'</b></small></span>
                   
                        </p>

                        <p style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                           '.$this->about_course.'
                        </p>

                        <button class="btn btn-md text-light" style="background-color:#1d007e;">

                          <a class="text-white" href="../../courses/courses-dashboard/index.php?id='.$this->course_encrypted_id.'" 
                          style="text-decoration:none;">
                         
                               View course <i class="fa fa-external-link"></i>

                          </a>

                        </button>

                    </div>

                </div>
                <!-- course container flex display -->

                </div>
                
                ';

            }

            echo '
            
            </div>    
        
            ';

        }

    }

}

?>