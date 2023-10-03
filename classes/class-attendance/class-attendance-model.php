<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-attendance/class-attendance-model.php

** About : this module fetches and displays the class attendance

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the class attendance class
 * include the heading
 * display the sub heading
 * cache the sub heading
 * fetch the class id
 * sanitize the class id
 * fetch the class attendance query
 * cache the class attendance query
 * display the class attendance data
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the class attendance class
class classAttendance{

    public $classId;

    public $courseId;

    public $attendance_total;

    public $sanitized_class_id;

    public $sanitized_course_id;


    public $attendance_subheading;


    public $attendance_query_result;

    public $attendance_query_status;

    public $cached_attendance_result;

    public $student_fullname;

    public $student_username;

    public $student_matric_number;

    public $student_avatar;

    public $student_encrypted_id;

    public $date;

    public $time;


    // fetch the class id
    public function fetchClassIdAndCourseId(){

        $this->classId = $_GET['classId'];

        $this->courseId = $_GET['courseId'];

        $this->attendanceTotal = $_GET['attendanceTotal'];
  
    }
  
    //sanitize the class id
    public function sanitize($connection,$data){
  
        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = htmlentities($data);
        $data = $connection->real_escape_string($data);
  
        return $data; 
  
    }

    //include the heading
    public function heading(){

        include('../header/header.php');
 
    } 

    //display the sub heading
    public function subHeading(){

        $this->attendance_subheading = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
         align="center">
 
             <br>
 
             <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
    
                Class Attendance <i class="fa fa-users"></i>
    
             </h1>
 
             <br>
 
         </div>
 
         <br>
 
         <div class="create-course" align="center">
 
             <div class="card shadow p-4 mb-4 bg-light" style="width:200px;">
 
                <a class="text-white" href="../class-dashboard/index.php?id='.$this->sanitized_class_id.'&&course_id='.$this->sanitized_course_id.'" 
                style="text-decoration:none;">
 
                     <button class="btn btn-md text-white" style="background-color:#1d007e;">
 
                     <i class="fa fa-arrow-left"></i> Back to class
 
                     </button>
 
                 </a>
 
             </div>
 
         </div>
         <!-- create course container-->
 
         <br>
 
         <div class="container">
 
             <h3><span class="badge bg-dark text-light">'.$this->attendanceTotal.'</span> Attended Student(s)</h3>
 
             <hr>
 
             <div class="row">
 
         </div>
        
        ';

    }

    //cache the sub heading
    public function cacheSubheading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "attendance_subheading";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->attendance_subheading)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }


    }

    //fetch the class attendance query
    public function fetchAttendanceQuery(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-attendance-db-connection.php');

        $attendance_query = "SELECT * FROM attendance_of_class_".$this->sanitized_class_id."  ORDER BY id DESC";

        $this->attendance_query_result = $conn11->query($attendance_query);

        if($this->attendance_query_result->num_rows > 0){

            $this->attendance_query_status = TRUE;

        }else{

            $this->attendance_query_status = FALSE;

        }

    }

    //cache the class attendance query
    public function cacheAttendanceQuery(){

        if($this->attendance_query_status == TRUE){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "class_attendance";
            $Cached_class_attendance_result = $InstanceCache->getItem($key);
    
            if (!$Cached_class_attendance_result->isHit()) {
                $Cached_class_attendance_result->set($this->attendance_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_class_attendance_result); // Save the cache item just like you do with doctrine and entities
            
                $this->cached_attendance_result =  $Cached_class_attendance_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
            
            } else {
                
                $this->cached_attendance_result =  $Cached_class_attendance_result->get();
                //echo 'READ FROM CACHE // ';
            
                $InstanceCache->deleteItem($key);
            }

        }else{



        }

    }

    //display the class attendance data
    public function displaClassAttendance(){

        if($this->attendance_query_status == TRUE){

        echo '<div class="row">';

        while($row = $this->cached_attendance_result->fetch_assoc()){

            $this->student_fullname = $row['student_fullname'];

            $this->student_username = $row['student_username'];

            $this->student_matric_number = $row['student_matric_number'];

            $this->student_avatar = $row['student_avatar'];

            $this->date = $row['date'];

            $this->time = $row['time'];

            $avatar_file = "../../resources/avatars/".$this->student_avatar;

            echo '
            
            <div class="col">
               
               <div class="d-flex shadow p-4 mb-4 bg-light" style="width:300px">

                    <div class="flex-shrink-0">
                        <img class="rounded-circle" src='.$avatar_file.' 
                        style="border:5px solid white;padding:1px;max-height:60px;
                        border-radius:30px;max-width:60px;"/>
                    </div>

                    <div class="flex-grow-1 ms-3">

                       <h5 style="max-width:200px;white-space:nowrap;overflow:hidden;
                       text-overflow:ellipsis;">'.$this->student_fullname.'</h5>

                       <p>
                          '.$this->student_matric_number.'<br>
                          <span class="text-muted"><small><b>@'.$this->student_username.'</b></small></span>
                   
                        </p>

                    </div>

                </div>
                <!-- course container flex display -->

                </div>
            
            ';
            
        }

        echo '</div>';

        }

    }


}
?>
