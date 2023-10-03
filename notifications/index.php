<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : notifications/index.php

** About : this module displays the index view of the user notification

*/

/**PSUEDO ALGORITHM
 * *
 * define the user notification
 * include the header
 * display the notification sub-heading
 * cache the subheading
 * display the cache the sub heading
 * fetch the user session
 * fetch the user notification based on the user type
 * cache the user notification query
 * cache the user notification query
 * define the user notification data
 * display the user notification
 * perform administrative functions
 * 
 * *
 */
session_start();

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;
use Symfony\Component\Process\Exception\ProcessFailedException;

//define the user notification
class userNotification{

    public $subheading;

    public $student_session;

    public $lecturer_session;


    public $lecturer_notification_result;

    public $lecturer_notification_status;

    public $cached_lecturer_notification_result;


    public $student_notification_result;

    public $stduent_notification_status;

    public $cached_student_notification_result;
 
    //include the header
    public function heading(){

        include('header/header.php');
 
    }

    //display the notification sub-heading
    public function subheading(){

        $this->subheading = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
         align="center">
 
             <br>
 
             <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
    
                Notifications <i class="fa fa-bell"></i>
    
             </h1>
 
             <br>
 
         </div>

         <br>

            <div class="container">
 
              <div align="center">

                <a href="clear-notifications.php">
                <button class="btn btn-md bg-danger text-light">

                   Clear Notifications <i class="fa fa-trash"></i>

                </button> 
                </a>

                <br><br>
        
        ';

    }

    //cache the subheading
    public function cacheSubheading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "notifications_subheading";
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

    //fetch the user session
    public function fetchUserSession(){

        if(isset($_SESSION['lecturer_session'])){

            $this->lecturer_session = $_SESSION['lecturer_session'];

        }elseif(isset($_SESSION['student_session'])){

            $this->student_session = $_SESSION['student_session'];

        }

    }

    //fetch the user notification based on the user type, cache the user notification query, cache the user notification query,
    //define the user notification data and display the user notification
    public function fetchLecturerNotification(){

        //require the env library
        require('../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../resources/database/users-notification-db-connection.php');//conn15

        $lecturer_notification_query = "SELECT * FROM notification_for_user".$this->lecturer_session." ORDER BY id DESC";

        $this->lecturer_notification_result = $conn15->query($lecturer_notification_query);

        if($this->lecturer_notification_result->num_rows > 0){

            $this->lecturer_notification_status = TRUE;

        }else{

            $this->lecturer_notification_status = FALSE;

        }

        mysqli_close($conn15);

    }

    public function cacheLecturerNotfication(){

        if($this->lecturer_notification_status == TRUE){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "cache_lecturer_notification";
            $Cached_lecturer_notification_result = $InstanceCache->getItem($key);
            
            if (!$Cached_lecturer_notification_result->isHit()) {
                $Cached_lecturer_notification_result->set($this->lecturer_notification_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_lecturer_notification_result); // Save the cache item just like you do with doctrine and entities
            
                $this->cached_lecturer_notification_result = $Cached_lecturer_notification_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
            
            } else {
                
                $this->cached_lecturer_notification_result = $Cached_lecturer_notification_result->get();
                //echo 'READ FROM CACHE // ';
            
                $InstanceCache->deleteItem($key);
            }

        }

    }

    public function dipslayAndDefineLecturerNotificationData(){

        if($this->lecturer_notification_status == TRUE){

            while($lecturer_notification_row = $this->cached_lecturer_notification_result->fetch_assoc()){

                $id = $lecturer_notification_row['id'];

                $link = $lecturer_notification_row['link'];
    
                $message = $lecturer_notification_row['message'];
    
                $date = $lecturer_notification_row['date'];
    
                $time = $lecturer_notification_row['time'];
    
                $status = $lecturer_notification_row['status'];

                $enc_link = base64_encode($link);

                $enc_id = base64_encode($id);
    
                echo '
                
                <a href="handle-link-model.php?notify_status='.$status.'&&link='.$enc_link.'&&id='.$enc_id.'" 
                style="text-decoration:none;">
    
                    <div class="alert alert-info" style="width:300px;">
    
                       <span>'.$message.'</span>
    
                        <div class="row">
    
                            <div class="col">
    
                             '.$status.'
    
                            </div>
    
                            <div class="col">
    
                              '.$time.'
    
                            </div>
    
                            <div class="col">
    
                              '.$date.'
    
                            </div>
    
                            <div class="col">
    
                             <!-- extra spacing -->
    
                            </div>
    
                            <div class="col">
    
                            <!-- extra spacing -->
    
                            </div>
    
                            <div class="col">
    
                            <!-- extra spacing -->
    
                            </div>
    
                            <div class="col">
    
                            <!-- extra spacing -->
    
                            </div>
    
                        </div>
                        <!-- grid row -->
    
                    </div>
                    <!-- alert -->
    
                    </a>
    
                
                ';
    

            }
        
        }    

    }

    public function fetchStudentNotification(){

        //require the env library
        require('../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../resources/database/users-notification-db-connection.php');//conn15

        $stduent_notification_query = "SELECT * FROM notification_for_user".$this->student_session." ORDER BY id DESC";

        $this->student_notification_result = $conn15->query($stduent_notification_query);

        if($this->student_notification_result->num_rows > 0){

            $this->student_notification_status = TRUE;

        }else{

            $this->student_notification_status = FALSE;

        }

        mysqli_close($conn15);

    }

    public function cacheStudentNotfication(){

        if($this->student_notification_status == TRUE){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "cache_student_notification";
            $Cached_student_notification_result = $InstanceCache->getItem($key);
            
            if (!$Cached_student_notification_result->isHit()) {
                $Cached_student_notification_result->set($this->student_notification_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_student_notification_result); // Save the cache item just like you do with doctrine and entities
            
                $this->cached_student_notification_result = $Cached_student_notification_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
            
            } else {
                
                $this->cached_student_notification_result = $Cached_student_notification_result->get();
                //echo 'READ FROM CACHE // ';
            
                $InstanceCache->deleteItem($key);
            }

        }

    }

    public function dipslayAndDefineStudentNotificationData(){

        if($this->student_notification_status == TRUE){

            while($stduent_notification_row = $this->cached_student_notification_result->fetch_assoc()){

                $id = $stduent_notification_row['id'];

                $link = $stduent_notification_row['link'];
    
                $message = $stduent_notification_row['message'];
    
                $date = $stduent_notification_row['date'];
    
                $time = $stduent_notification_row['time'];
    
                $status = $stduent_notification_row['status'];

                $enc_link = base64_encode($link);

                $enc_id = base64_encode($id);
    
                echo '
                
                <a href="handle-link-model.php?notify_status='.$status.'&&link='.$enc_link.'&&id='.$enc_id.'" 
                style="text-decoration:none;">
    
                    <div class="alert alert-info" style="width:300px;">
    
                       <span>'.$message.'</span>
    
                        <div class="row">
    
                            <div class="col">
    
                             '.$status.'
    
                            </div>
    
                            <div class="col">
    
                              '.$time.'
    
                            </div>
    
                            <div class="col">
    
                              '.$date.'
    
                            </div>
    
                            <div class="col">
    
                             <!-- extra spacing -->
    
                            </div>
    
                            <div class="col">
    
                            <!-- extra spacing -->
    
                            </div>
    
                            <div class="col">
    
                            <!-- extra spacing -->
    
                            </div>
    
                            <div class="col">
    
                            <!-- extra spacing -->
    
                            </div>
    
                        </div>
                        <!-- grid row -->
    
                    </div>
                    <!-- alert -->
    
                    </a>
    
                
                ';
    
    
    
            }
        
        }    

    }

}

$user_notification_controller = new userNotification();

session_regenerate_id(true);

if(isset($_SESSION['student_session']) or isset($_SESSION['lecturer_session'])){

   $user_notification_controller->heading();

   $user_notification_controller->subheading();

   $user_notification_controller->cacheSubheading();

   $user_notification_controller->fetchUserSession();

    if(isset($_SESSION['lecturer_session'])){

      $user_notification_controller->fetchLecturerNotification();

      $user_notification_controller->cacheLecturerNotfication();

      $user_notification_controller->dipslayAndDefineLecturerNotificationData();

    }elseif(isset($_SESSION['student_session'])){

      $user_notification_controller->fetchStudentNotification();

      $user_notification_controller->cacheStudentNotfication();

      $user_notification_controller->dipslayAndDefineStudentNotificationData();

    }

}else{

    header('location:logout-success.php');

}


?>