<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : profile/student-profile.php

** About : this module fetches and displays the student profile

*/

/**PSUEDO ALGORITHM
 * *
 * define the student profile class
 * fetch the user session
 * display the header
 * define the student profile query
 * cache the query
 * define the student data
 * display the stduent profile
 * 
 * *
 */

session_start();

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

class studentProfileData{

    public $student_session;


    public $fetch_student_data_result;

    public $cached_student_result;

    public $student_fullname;

    public $student_username;

    public $student_avatar_filename;

    public $student_about;

    public $student_avatar; 


    public $student_profile_page;

    //fetch the user session
    public function fetchUserSession(){

        $this->student_session = $_SESSION['student_session'];

    }

    //display the header
    public function heading(){

        include('header/header.php');
 
    }

    public function fetchStudentData(){

        //require the env library
        require('../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../resources/database/users-db-connection.php');

        $fetch_student_data_query = "SELECT * FROM students WHERE encrypted_id = 
        '$this->student_session'";

        $this->fetch_student_data_result = $conn1->query($fetch_student_data_query);
        
        mysqli_close($conn1);

    }

    public function cacheStudentResult(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "student_profile_result";
        $Cached_student_data_result = $InstanceCache->getItem($key);
        
        if (!$Cached_student_data_result->isHit()) {
            $Cached_student_data_result->set($this->fetch_student_data_result)->expiresAfter(1);//in seconds, also accepts Datetime
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

        $student_row = $this->cached_student_result->fetch_assoc();

        $this->student_fullname = $student_row['fullname'];

        $this->student_username = $student_row['matric_number'];

        $this->student_avatar_filename = $student_row['avatar'];

        $this->student_about = $student_row['about'];

        $this->student_avatar = '../resources/avatars/'.$this->student_avatar_filename;

    }

    //student_lecturer_profile
    public function displayStudentProfile(){

        $this->student_profile_page = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
         align="center">
 
             <br>
 
             <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
    
                Your Profile <i class="fa fa-user-circle"></i>
    
             </h1>
 
             <br>
 
        </div>
 
        <br>


        <div class="container">

        <div align="center" class="profile-container">

                    <div class="row">

                        <div class="col">

                        <img loading="lazy" src='.$this->student_avatar.' id="profile_pic" 
                        style="border:5px solid white;padding:5px;max-height:250px;
                        border-radius:150px;max-width:250px;"/>

                        <br>

                        </div>
                        <!-- column one -->

                        <div class="col">

                        <div class="card shadow p-4 mb-4 bg-light border" style="width:300px;
                        font-family:monospace;">

                            <span style="font-size:19px;">
                            <i class="fa fa-user-circle"></i> '.$this->student_fullname.' <br> 
                               <span class="text-secondary">@'.$this->student_username.'</span>
                            </span>

                            <hr>

                            <span><i class="fa fa-institution"></i> Department Of Educational Technology
                            </span>

                            <hr>

                            <span><i class="fa fa-align-left"></i> About <br>
                               '.$this->student_about.'
                            </span>

                        </div>
                        <!-- card -->   

                        </div>
                        <!-- clumn two -->

                    </div>
                    <!-- grid row --> 

                </div> 
                <!-- profile container -->

        </div>
        
        ';

    }

    public function cachedStudentProfilePage(){
    
        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "stduent_profile_page";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->student_profile_page)->expiresAfter(1);//in seconds, also accepts Datetime
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

$student_profile_controller = new studentProfileData();

if(isset($_SESSION['student_session'])){

    $student_profile_controller->fetchUserSession();

    $student_profile_controller->heading();

    $student_profile_controller->fetchStudentData();

    $student_profile_controller->cacheStudentResult();

    $student_profile_controller->defineStudentData();

    $student_profile_controller->displayStudentProfile();

    $student_profile_controller->cachedStudentProfilePage();

}else{

    

}

?>