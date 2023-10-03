<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/courses-dashboard/course-dashboard-model.php

** About : this module displays the course dashboard

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the course dashboard class
 * fetch the 'GET' course id and user session
 * sanitize the 'GET' course encrypted id
 * fetch the course data from the database
 * cache the course query
 * define the course data
 * display the course heading
 * display the course outline modal
 * fetch the students enrolled total course from the course database
 * cache the students enrolled total query
 * display the student enrolled total data
 * cache the student enrolled total data
 * fetch the students enrolled course from the course database
 * cache the students enrolled query
 * display the student enrolled data
 * cache the student enrolled data
 * fetch the course classroom data from the database
 * cache the classroom query
 * define the classroom data
 * display the course classroom
 * cache the display classroom
 * 
 * *
 */

// echo $_GET['id'];

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the course dashboard class
class courseDashboard{

    public $lecturer_session;

    public $student_session;

    public $course_encrypted_id;

    public $sanitized_course_encrypted_id;

    
    public $course_data_result;

    public $cached_course_result;

    public $course_data_row;

    public $course_code;

    public $course_title;

    public $about_course;

    public $course_id;

    public $lecturer_in_charge;

    public $lecturer_avatar;

    public $lecturer_encrypted_id;

    public $level;

    public $date_created;

    public $time_created;

    public $course_outline;
    

    public $course_heading;

    public $course_outline_modal;

    public $total_result;

    public $student_enrolled_total_status;

    public $cached_total_result;

    public $enrolled_student_total_modal;


    public $course_class_result;

    public $course_class_status;

    public $cached_class_result;

    public $class_list_row;

    public $display_classroom;


    public $class_topic;

    public $about_class;

    public $class_encrypted_id;

    public $assignment_status;

    public $time_class_created;

    public $date_class_created;


    //fetch the 'GET' course id and user session
    public function fetchRouteIdAndSsession(){

        if(isset($_SESSION['lecturer_session'])){

            //echo $this->lecturer_session = $_SESSION['lecturer_session'];

        }elseif(isset($_SESSION['student_session'])){

            //echo $this->student_session = $_SESSION['student_session'];

        }

        $this->course_encrypted_id = $_GET['id'];

    }

    //sanitize the 'GET' course encry id
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = htmlentities($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //fetch the course data from the database
    public function fetchCourseData(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/courses-db-connection.php');

        $course_data_query = "SELECT * FROM courses WHERE course_encrypted_id = '$this->sanitized_course_encrypted_id'";

        $this->course_data_result = $conn8->query($course_data_query);

        $conn8->error;

        mysqli_close($conn8);

    }

    public function cachedCourseQuery(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "course_data";
        $Cached_course_data_result = $InstanceCache->getItem($key);

        if (!$Cached_course_data_result->isHit()) {
            $Cached_course_data_result->set($this->course_data_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_course_data_result); // Save the cache item just like you do with doctrine and entities
        
            $this->cached_course_result = $Cached_course_data_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            $this->cached_course_result = $Cached_course_data_result->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    //define the course data
    public function defineCourseData(){

        $this->course_data_row = $this->cached_course_result->fetch_assoc();

        $this->course_code = $this->course_data_row['course_code'];

        $this->course_title = $this->course_data_row['course_title'];

        $this->about_course = $this->course_data_row['about_course'];

        $this->course_id = $this->course_data_row['course_id'];

        $this->lecturer_in_charge = $this->course_data_row['lecturer_in_charge'];

        $this->lecturer_avatar = $this->course_data_row['lecturer_avatar'];

        $this->lecturer_encrypted_id = $this->course_data_row['lecturer_encrypted_id'];

        $this->level = $this->course_data_row['level'];

        $this->date_created = $this->course_data_row['date_created'];

        $this->time_created = $this->course_data_row['time_created'];

        $this->course_outline = $this->course_data_row['course_outline'];

    }

    //display the course heading
    public function courseHeading(){

        $this->course_heading = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

        <br> 

        <div class="container">  

            <div class="card shadow p-4 mb-4 bg-light" style="font-size:18px;">


                <p>
                <span class="text-muted">Course Id</span>
                <br>
                <b>'.$this->course_id.'</b></p>

                <p>
                <span class="text-muted">Course Code</span>
                <br>
                <b>'.$this->course_code.'</b></p>
  
                <p>
                <span class="text-muted">Course Title</span>
                <br>
                <b>'.$this->course_title.'</b></p>
                  
    
                <p>
                <span class="text-muted">About Course</span>
                <br>
                <b>'.$this->about_course.'</b></p>
    
                <p class="text-muted"> <img class="rounded-circle" src='.$this->lecturer_avatar.' id="profile_pic" 
                style="border:5px solid white;padding:5px;max-height:45px;max-width:45px;"/>
    
                <b>'.$this->lecturer_in_charge.'</b>
    
                </p>

                <div align="center">
                  <p class="badge bg-info" style="width:300px;">Share the course by sharing<br> the course id to students</p>
                </div>


            </div>
            <!-- course card -->

        </div>
        </div>
        <!-- course heading container-->

        <ul class="nav justify-content-center">

            <li class="nav-item">

              <a class="nav-link text-dark">

                <button class="btn btn-md text-light" style="background-color:#1d007e;">

                    '.$this->level.' level students

                </button>

              </a>

            </li>

            <li class="nav-item">

              <a class="nav-link text-dark">

                <button class="btn btn-md text-light" style="background-color:#1d007e;">

                    '.$this->date_created.'

                </button>

              </a>

            </li>

            <li class="nav-item">

              <a class="nav-link text-dark">

                <button class="btn btn-md text-light" style="background-color:#1d007e;">
  
                   '.$this->time_created.'

                </button>

              </a>

            </li>

        </ul>

        <ul class="nav justify-content-center">

            <li class="nav-item">

              <a class="nav-link text-light">

                <button class="btn btn-md text-light" style="background-color:#1d007e;" data-bs-toggle="modal" 
                data-bs-target="#enrolled-students">
                   Enrolled <i class="fa fa-users"></i>
                </button>

              </a>

            </li>

            <li class="nav-item">

              <a class="nav-link text-light" href="../course-settings/index.php?courseId='.$this->sanitized_course_encrypted_id.'">

                <button class="btn btn-md text-light" style="background-color:#1d007e;">
                   Settings <i class="fa fa-cog"></i>
                </button>

              </a>

            </li>

        </ul>

        <ul class="nav justify-content-center">

            <li class="nav-item">

                <a class="nav-link text-light">
 
                    <button class="btn btn-md text-light" style="background-color:#1d007e;" data-bs-toggle="modal" 
                    data-bs-target="#courseOutline">
                        Course outline <i class="fa fa-list"></i>
                    </button>

                </a>

            </li>

        </ul>

        <br>
 
 
        <div class="create-classroom" align="center">
 
            <div class="card shadow p-4 mb-4 bg-light" style="width:200px;">

                <a href="../create-classroom/index.php?id='.$this->sanitized_course_encrypted_id.'&&courseCode='.$this->course_code.'" style="text-decoration:none;">
 
                    <button class="btn btn-md text-white" style="background-color:#1d007e;">

                       <b>Create classroom</b>

                    </button>

                </a>
 

            </div>
 
        </div>

        <br>

        <div class="container">

           <h3>Classrooms <i class="fa fa-list-alt"></i></h3>

           <hr>
        

        ';

        //$this->course_heading;

    }

    public function cachedCourseHeading(){
    
        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "course_heading";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->course_heading)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }
    
    }

    //display the course outline modal
    public function displayCourseOutlineModal(){
        //$this->course_outline_modal
        echo '
        
        <div class="modal fade" id="courseOutline" tabindex="-1" 
        aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role-document>

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                            <i class="fa fa-list"></i> Course outline
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>';

                    
                    $read_course_outline_file = file($this->course_outline);

                    foreach($read_course_outline_file as $Course_Outline){

                        echo '<div class="modal-body">

                            <span>'.$Course_Outline.'</span>

                        </div>';

                    }

        echo        '</div>

            </div>

        </div>';

    }

    //fetch the students enrolled total into course from the course database
    public function studentsEnrolledTotal(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/courses-students-enrolled-db-connection.php');

        $students_enrolled_total_query = "SELECT count(*) AS total_enrolled_students FROM 
         enrolled_students_of_course_".$this->sanitized_course_encrypted_id."  ORDER BY id DESC";

        $this->total_result = $conn9->query($students_enrolled_total_query);

        if($this->total_result->num_rows > 0){

            $this->student_enrolled_total_status = TRUE;

        }else{

            $this->student_enrolled_total_status = FALSE;

            //echo "No enrolled students";    

        }

        mysqli_close($conn9);

    }

    //cache the students enrolled total query
    public function cacheStudentsEnrolledTotal(){

        if($this->student_enrolled_total_status == TRUE){

           CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
           ]));
        
           $InstanceCache = CacheManager::getInstance('files');
        
           $key = "students_enrolled_total";
           $Cached_students_enrolled_total = $InstanceCache->getItem($key);

           if (!$Cached_students_enrolled_total->isHit()) {
               $Cached_students_enrolled_total->set($this->total_result)->expiresAfter(1);//in seconds, also accepts Datetime
               $InstanceCache->save($Cached_students_enrolled_total); // Save the cache item just like you do with doctrine and entities
        
               $this->cached_total_result = $Cached_students_enrolled_total->get();
               //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
            } else {
            
               $this->cached_total_result = $Cached_students_enrolled_total->get();
               //echo 'READ FROM CACHE // ';
        
               $InstanceCache->deleteItem($key);
            }

        }

    }

    //display the student enrolled total data
    public function displayTotalEnrolledStudents(){

        if($this->student_enrolled_total_status == TRUE){

           $student_enrolled_total_row = $this->cached_total_result->fetch_assoc();

           $total = $student_enrolled_total_row['total_enrolled_students'];

           $this->enrolled_student_total_modal =  '
           
           <div class="modal fade" id="enrolled-students" tabindex="-1" 
           aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">

                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role-document>

                    <div class="modal-content">

                        <div class="modal-header" style="background-color:#1d007e;">

                           <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                           <i class="fa fa-users"></i> Enrolled students <span class="badge bg-light text-dark">'.$total.'</span>
                           </h5>
                           <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                           </button>

                        </div>

                        <div class="modal-body">

                            <div align="center">

                             <a href="../course-enrolled-students/index.php?id='.$this->sanitized_course_encrypted_id.'&&total='.base64_encode($total).'">

                               <button class="btn btn-md text-light" style="background-color:#1d007e;">

                                  View Enrolled Students <i class="fa fa-external-link"></i>

                               </button>

                             </a>

                            </div>

                        </div>


                    </div>

                </div>

            </div>

           ';

        }else{

           //echo "<h1>No enrolled students</h1>";

        }

    }

    //cache the student enrolled total data
    public function cacheDisplayStudentTotalEnrolled(){

        if($this->student_enrolled_total_status == TRUE){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "display_total_enrolled_students";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($this->enrolled_student_total_modal)->expiresAfter(1);//in seconds, also accepts Datetime
              $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
            
                echo $Cached_page->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
            
            } else {
                
                echo $Cached_page->get();
                //echo 'READ FROM CACHE // ';
            
                $InstanceCache->deleteItem($key);
            }

        }else{


        }

    }

    //fetch the course classroom data from the database
    public function fetchCourseClassroom(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-db-connection.php');

        $course_class_query = "SELECT * FROM classes_of_course_".$this->sanitized_course_encrypted_id."";

        $this->course_class_result = $conn10->query($course_class_query);

        if($this->course_class_result->num_rows > 0){

            $this->course_class_status = TRUE;          

        }else{

            $this->course_class_status = FALSE;

            echo '

                <div align="center">
             
                    <div class="alert alert-warning" style="width:250px;">

                      <p class="lead">No Classes To Display</p>

                    </div>

                </div>
             
             ';
 

        }

        mysqli_close($conn10);

    }

    //cache the classroom query
    public function cacheCourseClassroom(){

        if($this->course_class_status == TRUE){

           CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
           ]));
        
           $InstanceCache = CacheManager::getInstance('files');
        
           $key = "classroom_query";
           $Cached_classroom_query_result = $InstanceCache->getItem($key);

            if(!$Cached_classroom_query_result->isHit()){

               $Cached_classroom_query_result->set($this->course_class_result)->expiresAfter(1);//in seconds, also accepts Datetime
               $InstanceCache->save($Cached_classroom_query_result); // Save the cache item just like you do with doctrine and entities
        
               $this->cached_class_result = $Cached_classroom_query_result->get();
               //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
            }else{
            
               $this->cached_class_result = $Cached_classroom_query_result->get();
               //echo 'READ FROM CACHE // ';
        
               $InstanceCache->deleteItem($key);
            }

        }

    }

    //define the classroom data

    //display the course classroom
    public function displayClassroomData(){

        if($this->course_class_status == TRUE){

            echo '
                
                <div class="row">
                
            ';


            while($this->class_list_row =  $this->cached_class_result->fetch_assoc()){

                //defining classroom data
                $this->class_topic = $this->class_list_row['class_topic'];

                $this->about_class = $this->class_list_row['about_class'];

                $this->class_encrypted_id = $this->class_list_row['class_encrypted_id'];

                $this->assignment_status = $this->class_list_row['assignment_status'];

                $this->time_class_created = $this->class_list_row['time_created'];

                $this->date_class_created = $this->class_list_row['date_created'];


                echo $this->display_classroom = '

                <div class="col">

                <div class="d-flex shadow p-4 mb-4 bg-light" style="width:300px">

                    <div class="flex-shrink-0">
                       <i class="fa fa-mortar-board" style="font-size:30px;"></i>
                    </div>

                    <div class="flex-grow-1 ms-3">

                       <h5 style="max-width:200px;white-space:nowrap;overflow:hidden;
                       text-overflow:ellipsis;">'.$this->class_topic.'</h5>

                       <p style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                          '.$this->about_class.'
                       </p>

                       <p>
                            Assignment : '.$this->assignment_status.'
                       </p>

                       <div class="row">

                            <div class="col">

                                <button class="btn btn-outline-dark">
 
                                '.$this->time_class_created.'
                                
                                </button>

                            </div> 
                            <!-- time column -->

                            <div class="col">

                                <button class="btn btn-outline-dark">

                                '.$this->date_class_created.'

                                </button>

                            </div>
                            <!-- date column -->

                        </div>
                        <!-- date and time row -->

                        <br>

                        <a href="../../classes/class-dashboard/index.php?id='.$this->class_encrypted_id.'&&course_id='.$this->sanitized_course_encrypted_id.'" 
                        style="text-decoration:none;">

                           <button class="btn btn-md text-light" style="background-color:#1d007e;">
                              view class <i class="fa fa-external-link"></i>
                           </button>

                        </a>

                    </div>

                    <br>

                </div>
                <!-- course container flex display -->

                </div>
                
                ';


            }

            echo '
                
                </div>

                </div>
                
            ';

        }else{

        }

    }
    

}

?>