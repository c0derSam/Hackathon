<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-dashboard/class-dashboard-model.php

** About : this module fetches and displays the course dashboard

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the class dashboard class
 * 
 * fetch the 'GET' class encrypted id and user session
 * 
 * sanitize the 'GET' class encrypted id and course encrypted id
 * 
 * fetch the class data from the database
 * 
 * cache the class query
 * 
 * define the class data
 * 
 * display the class heading
 * 
 * cache the displayed class heading
 * 
 * running the attendance logic
 * 
 * fetching the attendance total
 * 
 * defining the attendance total data
 * 
 * display attendance total modal
 * 
 * fetch class chat
 * 
 * 
 * *
*/

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the class dashboard class
class classroomDashboard{

    public $lecturer_session;

    public $student_session;

    public $class_encrypted_id;

    public $course_id;


    public $sanitized_class_encrypted_id;

    public $sanitized_course_id;


    public $class_query_result;

    public $cached_class_result;

    public $class_row;

    public $class_topic;

    public $about_class;

    public $instructional_material;

    public $date_created;

    public $time_created;


    public $classroom_heading;


    public $attendance_query_status;


    public $student_data_result;

    public $cached_student_result;

    public $student_fullname;

    public $student_username;
    
    public $student_matric_number;

    public $student_avatar;

    public $student_encrypted_id;

    public $type;

    public $attendance_date;

    public $attendance_time;

    public $attendance_status;

    public $attendance_alert_status;


    public $attendance_total_result;

    public $cached_attendance_total_result;

    public $attendance_data;


    public $display_class_tabs;

    //fetch the 'GET' class encrypted id and user session
    public function fetchClassidAndUserSession(){

        if(isset($_SESSION['lecturer_session'])){

            $this->lecturer_session = $_SESSION['lecturer_session'];

        }elseif(isset($_SESSION['student_session'])){

            $this->student_session = $_SESSION['student_session'];

        }

        $this->class_encrypted_id = $_GET['id'];

        $this->course_id = $_GET['course_id'];

    }

    //sanitize the 'GET' class encrypted id and course encrypted id
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //fetch the class data from the database
    public function fetchClassData(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-db-connection.php');

        $class_data_query = "SELECT * FROM classes_of_course_".$this->sanitized_course_id." WHERE class_encrypted_id = '$this->sanitized_class_encrypted_id'";

        $this->class_query_result = $conn10->query($class_data_query);

        mysqli_close($conn10);

    }

    //cache the class query
    public function cacheClassQuery(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "classroom_data";
        $Cached_class_data_result = $InstanceCache->getItem($key);

        if (!$Cached_class_data_result->isHit()) {
            $Cached_class_data_result->set($this->class_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_class_data_result); // Save the cache item just like you do with doctrine and entities
        
            $this->cached_class_result = $Cached_class_data_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            $this->cached_class_result = $Cached_class_data_result->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    //define the class data
    public function defineClassData(){

        $this->class_row = $this->cached_class_result->fetch_assoc();

        $this->class_topic = $this->class_row['class_topic'];

        $this->about_class = $this->class_row['about_class'];

        $this->instructional_material = $this->class_row['instructional_material'];

        $this->class_note = $this->class_row['class_note'];

        $this->date_created = $this->class_row['date_created'];

        $this->time_created = $this->class_row['time_created'];

    }

    //display the class heading
    public function displayClassroomHeading(){

        $this->classroom_heading = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

        <br> 

        <div class="container">  

            <div class="card shadow p-4 mb-4 bg-light" style="font-size:18px;">
            
                <p>
                <span class="text-muted">Class Topic</span>
                <br>
                <b>'.$this->class_topic.'</b></p>
  
                <p>
                <span class="text-muted">About Class</span>
                <br>
                <b>'.$this->about_class.'</b></p>

            </div>
            <!-- course card -->

        </div>
        </div>
        <!-- course heading container-->

        <ul class="nav justify-content-center">

            <li class="nav-item">

              <a class="nav-link text-dark" href="../class-assignment/class-assignment-redirect.php?classId='.$this->sanitized_class_encrypted_id.'&&courseId='.$this->sanitized_course_id.'">

                <button class="btn btn-md text-light" style="background-color:#1d007e;">

                    Assignment 

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
                   Attendance <i class="fa fa-users"></i>
                </button>

              </a>

            </li>

            <li class="nav-item">

              <a class="nav-link text-light" href="../class-settings/index.php?classId='.$this->sanitized_class_encrypted_id.'&&courseId='.$this->course_id.'">

                <button class="btn btn-md text-light" style="background-color:#1d007e;">
                   Settings <i class="fa fa-cog"></i>
                </button>

              </a>

            </li>

        </ul>

        <br>

        <div align="center">
 
            <div class="card shadow p-4 mb-4 bg-light" style="width:200px;">

            <a class="text-white" href="../../courses/courses-dashboard/index.php?id='.$this->sanitized_course_id.'" 
            style="text-decoration:none;">
 
                <button  class="btn btn-md text-white" style="background-color:#1d007e;"
                data-bs-toggle="modal" data-bs-target="#attendance">

                   <i class="fa fa-arrow-left"></i> Back to course

                </button>

            </a>
 
            </div>
 
        </div>
        
        ';

    }

    //cache the displayed class subheading heading
    public function cacheClassHeading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "class_heading";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->classroom_heading)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }


    }

    //running the attendance logic
    public function fetchClassAttendance(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-attendance-db-connection.php');

        $class_attendance_query = "SELECT student_encrypted_id FROM attendance_of_class_".$this->sanitized_class_encrypted_id." 
        WHERE student_encrypted_id = '$this->student_session'";

        $class_attendance_result = $conn11->query($class_attendance_query);

        $class_attendance_result = mysqli_fetch_array($class_attendance_result, MYSQLI_ASSOC);

        if(empty($class_attendance_result['student_encrypted_id'])){

           //echo "Empty";

           $this->attendance_query_status = TRUE;

        }else{

           //echo "Not empty";

           $this->attendance_query_status = FALSE;

        }

        mysqli_close($conn11);

    }

    //fetching students data
    public function fetchStudentData(){

        if($this->attendance_query_status == TRUE){

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

    }

    //caching the student data query
    public function cacheStudentData(){

        if($this->attendance_query_status == TRUE){

           CacheManager::setDefaultConfig(new ConfigurationOption([
               'path' => '', // or in windows "C:/tmp/"
           ]));
        
           $InstanceCache = CacheManager::getInstance('files');
        
           $key = "stduent_data";
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


    }

    //defining the student data 
    public function definingStudentData(){

        if($this->attendance_query_status == TRUE){

            $student_data_row = $this->cached_student_result->fetch_assoc();

            $this->student_fullname = $student_data_row['fullname'];

            $this->student_username = $student_data_row['username'];
        
            $this->student_matric_number = $student_data_row['matric_number'];

            $this->student_avatar = $student_data_row['avatar'];

            $this->student_encrypted_id = $student_data_row['encrypted_id'];

            //defining auto generated data
            $this->type = "Not defined";

            $this->attendance_date = date("Y/m/d");

            $this->attendance_time = date("h:i:sa");

            $this->attendance_status = "attended class";

        }

    }

    //insert into student details into attendance
    public function insertIntoAttendance(){

        if($this->attendance_query_status == TRUE){

            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // course db connection
            include('../../resources/database/courses-classes-attendance-db-connection.php');

            $insert_into_attendance_query = "INSERT INTO attendance_of_class_".$this->sanitized_class_encrypted_id."
            (
              student_fullname,student_username,student_matric_number,student_avatar,student_encrypted_id,
              type,date,time,status  
            )
            VALUES(
                '$this->student_fullname','$this->student_username','$this->student_matric_number','$this->student_avatar',
                '$this->student_encrypted_id','$this->type','$this->attendance_date','$this->attendance_time',
                '$this->attendance_status'
            )
            ";

            if($conn11->query($insert_into_attendance_query)){

                $this->attendance_alert_status = TRUE;

            }else{

                $this->attendance_alert_status = FALSE;

            }

            mysqli_close($conn11);

        }

    }

    public function displayAttendanceAlert(){

        if($this->attendance_alert_status == TRUE){

          echo '

            <div align="center">

                <div class="alert alert-success alert-dissmissible fade show" role="alert" style="width:250px;">

                   Your attendance has been filled

                   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                </div>

            </div>
          
          ';

        }

    }

    //fetching the attendance total
    public function fetchAttendanceTotal(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-attendance-db-connection.php');

        $attendance_total_query = "SELECT count(*) AS attendanceTotal FROM 
        attendance_of_class_".$this->sanitized_class_encrypted_id."";

        $this->attendance_total_result = $conn11->query($attendance_total_query);

        mysqli_close($conn11);
 
    }

    //cache attendance total query
    public function cacheAttendanceTotalQuery(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
     
        $InstanceCache = CacheManager::getInstance('files');
     
        $key = "attendance_total_query";
        $Cached_attendance_total_result = $InstanceCache->getItem($key);

        if (!$Cached_attendance_total_result->isHit()) {
            $Cached_attendance_total_result->set($this->attendance_total_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_attendance_total_result); // Save the cache item just like you do with doctrine and entities
     
            $this->cached_attendance_total_result = $Cached_attendance_total_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
     
        }else{
         
            $this->cached_attendance_total_result = $Cached_attendance_total_result->get();
            //echo 'READ FROM CACHE // ';
     
            $InstanceCache->deleteItem($key);
        }

    }

    //defining the attendance total data
    public function defineAttendanceTotalData(){

        $attendance_total_row = $this->cached_attendance_total_result->fetch_assoc();

        $this->attendance_data = $attendance_total_row['attendanceTotal'];

    }

    //display attendance total modal
    public function displayAttendanceModal(){

        echo $this->attendance_modal = '


        <div class="modal fade" id="enrolled-students" data-bs-backdrop="static" 
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" 
        style="display: none;" aria-hidden="true">

             <div class="modal-dialog">

                 <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                        <i class="fa fa-users"></i> Attended students <span class="badge bg-light text-dark">'.$this->attendance_data.'</span>
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                            <div align="center">

                             <a href="../class-attendance/class-attendance-controller.php?classId='.$this->sanitized_class_encrypted_id.'&&courseId='.$this->course_id.'&&attendanceTotal='.$this->attendance_data.'">

                                <button class="btn btn-md text-light" style="background-color:#1d007e;">

                                  View Class Attendance <i class="fa fa-external-link"></i>

                                </button>

                             </a>

                            </div>

                        </div>

                </div>

            </div>

        </div>
        
        ';

    }

    //fetching class chat
    public function displayClassTabs(){

        //storing class id into class session
        //$_SESSION['class_id'] = $this->sanitized_class_encrypted_id;

        echo $this->display_class_tabs = '

        <div class="container">

            <ul class="nav nav-tabs justify-content-center">

                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#classNote">
                      Class Note
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#classTest">
                      Class Test
                    </a>
                </li>

            </ul>
            <!-- tabs -->

            <div class="tab-content">
        
        ';


        $read_note_content_file = file($this->class_note);

        echo '
        
        <div class="tab-pane container active" id="classNote">

            <div class="shadow p-4 mb-4 bg-light">
         
                <div class="accordion-item">

                    <p class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                        data-bs-target="#assignment" aria-expanded="false" aria-controls="collapseTwo">
                            Click to see note
                        </button>
                    </p>
        
                    <div id="assignment" class="accordion-collapse collapse" aria-labelledby="headingTwo">

                        <div class="accordion-body">
        
        ';

        //displaying all the lines in the file
        foreach($read_note_content_file as $note_line){

            echo '
            
                <p>'.$note_line.'</p>
    
            ';
    
        }

        echo '

                          </div>
                          <!-- accordion body -->
        
                        </div>
                               
        
                      </div>
                      <!-- assignment accordion -->

                    </div>

                </div>
                <!-- tab1 -->

                <div class="tab-pane container" id="classTest">

                    <div class="shadow p-4 mb-4 bg-light" style="width:320px;">

                        <a class="text-white" href="../class-test/index.php?classId='.$this->class_encrypted_id.'&&courseId='.$this->course_id.'">

                            <button class="btn btn-md text-light" style="background-color:#1d007e;">
  
                                Go to class test
 
                            </button>

                        </a>

                    </div>

                </div>
                <!-- tab2 -->
        
        </div>
        <!-- container -->

        <br><br><br><br>
        
        ';

    }

}


?>