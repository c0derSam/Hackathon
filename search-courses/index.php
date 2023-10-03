<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : search-courses/index.php

** About : this module displays the index view to search courses

*/

/**PSUEDO ALGORITHM
 * *
 * define the course search
 * display the course search sub-heading
 * cache the subheading
 * display the cache the sub heading
 * fetch the course search id
 * sanitize the course id
 * fetch the course data with the course id
 * cache the course data query
 * define the course data
 * display the course data
 * cache the course display
 * perform administrative functions
 * 
 * *
 */
session_start();

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

class CourseSearch{

    public $subheading;


    public $search_course_id;

    public $sanitized_search_course_id;


    public $course_result;

    public $course_query_status;

    public $cached_courses_row;

    public $course_code;
    
    public $course_title;
    
    public $about_course;
    
    public $course_encrypted_id;
    
    public $lecturer_in_charge;

    public $searched_course;

    public function heading(){

        include('header/header.php');
 
    }

    public function subheading(){

        $this->subheading = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
         align="center">
 
             <br>
 
             <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
    
                Search Course <i class="fa fa-search"></i>
    
             </h1>
 
             <br>
 
         </div>
 
         <br>
 
         <div class="create-course" align="center">
 
            <div class="card shadow p-4 mb-4 bg-light" style="width:200px;">
 
                <form action="index.php" method="POST">

                    <div class="input-group">

                        <input class="form-control" type="number" name="courseId" placeholder="course id" 
                        required style="height:40px;width:100px;">

                        <button type="submit" class="input-group-text text-light" style="background-color:#1d007e;">
                           <i class="fa fa-search"></i>
                        </button>

                    </div>

                </form>
 
            </div>
 
         </div>
         <!-- create course container-->
 
         <br>
        
        ';

    }

    //cache the subheading
    public function cachedSubHeading(){
     
        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "search_subheading";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->subheading)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
       }
    
    }

    //fetch the course search id
    public function fetchCourseSearchId(){

        $this->search_course_id = $_POST['courseId'];


    }

    //sanitize the course id
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //fetch the course data with the course id
    public function fetchCourseData(){

        //require the env library
        require('../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../env/db-conn-var.env');
        $user_db_conn_env->load();

        //db connection
        include('../resources/database/courses-db-connection.php');

        $courses_query = "SELECT * FROM courses WHERE course_id = '$this->sanitized_search_course_id'";

        $this->course_result = $conn8->query($courses_query);
        
        if($this->course_result->num_rows > 0){

           $this->course_query_status = TRUE;

        }else{

           $this->course_query_status = FALSE;

        }

        mysqli_close($conn8);

    }

    //cache the course data query
    public function cacheCourseQuery(){

        if($this->course_query_status == TRUE){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "courses";
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

    }

    //define the course data
    public function defineCourseData(){

        if($this->course_query_status == TRUE){

            $course_row = $this->cached_courses_row->fetch_assoc();

            $this->course_code = $course_row['course_code'];
    
            $this->course_title = $course_row['course_title'];
    
            $this->about_course = $course_row['about_course'];
    
            $this->course_encrypted_id = $course_row['course_encrypted_id'];
    
            $this->lecturer_in_charge = $course_row['lecturer_in_charge'];

        }

    }

    public function displaySearchedCourse(){

        echo '
        
        <div class="container">

            <h3>Results</h3>

            <hr>
        
        ';

        if($this->course_query_status == TRUE){

            $this->searched_course = '

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

                          <a class="text-white" href="enroll-students/enroll-students-controller.php?course_id='.$this->course_encrypted_id.'" 
                          style="text-decoration:none;">
                         
                               Enroll Now

                          </a>

                        </button>

                    </div>

                </div>
                <!-- course container flex display -->
        
            ';


        }else{

            echo '
            
            <div align="center">
             
                    <div class="alert alert-warning" style="width:250px;">

                      <p class="lead"><small>Course does not exist<br>Check the course id and search again</small></p>

                    </div>

            </div>
            
            ';

        }

    }

    public function cacheSearchedCourse(){

        if($this->course_query_status == TRUE){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "search_results";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($this->searched_course)->expiresAfter(1);//in seconds, also accepts Datetime
              $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
            
                echo $Cached_page->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
            
            } else {
                
                echo $Cached_page->get();
                //echo 'READ FROM CACHE // ';
            
                $InstanceCache->deleteItem($key);
           }

        }

    }

}


//controller
$course_search = new CourseSearch();

session_regenerate_id(true);

if(isset($_SESSION['student_session']) or isset($_SESSION['lecturer_session'])){

    $course_search->heading();

    $course_search->subheading();

    $course_search->cachedSubHeading();

    if(isset($_POST['courseId'])){

        $course_search->fetchCourseSearchId();
        
        //require the env library
        require('../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../env/db-conn-var.env');
        $user_db_conn_env->load();

        // db connection
        include('../resources/database/courses-db-connection.php');

        $course_search->sanitized_search_course_id = $course_search->sanitize($conn8,$course_search->search_course_id);

        $course_search->fetchCourseData();

        $course_search->cacheCourseQuery();

        $course_search->defineCourseData();

        $course_search->displaySearchedCourse();

        $course_search->cacheSearchedCourse();
    }

}else{

    header('location:logout-success.php');

}

?>