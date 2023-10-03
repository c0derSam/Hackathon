<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : profile/lecturer-profile.php

** About : this module fetches and displays the lecturer profile

*/

/**PSUEDO ALGORITHM
 * *
 * define the lecturer profile class
 * fetch the user session
 * display the header
 * define the lecturer profile query
 * cache the query
 * define the lecturer data
 * display the lecturer profile
 * 
 * *
 */

session_start();

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the lecturer profile class
class lecturerProfile{

    public $lecturer_session;


    public $fetch_lecturer_data_result;

    public $cached_lecturer_result;

    public $lecturer_title;

    public $lecturer_fullname;

    public $lecturer_username;

    public $lecturer_avatar_filename;

    public $lecturer_about;

    public $lecturer_avatar;


    public $lecturer_profile_page;

    //fetch the user session
    public function fetchUserSession(){

        $this->lecturer_session = $_SESSION['lecturer_session'];

    }

    //display the header
    public function heading(){

        include('header/header.php');
 
    }

    //define the lecturer profile query
    public function fetchLecturerProfileQuery(){

       // user db connection
       include('../resources/database/users-db-connection.php');//conn1 

       $fetch_lecturer_data_query = "SELECT * FROM lecturers WHERE encrypted_id = '$this->lecturer_session'";

       $this->fetch_lecturer_data_result = $conn1->query($fetch_lecturer_data_query);
        
       mysqli_close($conn1);

    }

    //cache the query
    public function cacheLecturerResult(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "lecturer_profile_result";
        $Cached_lecturer_data_result = $InstanceCache->getItem($key);
        
        if (!$Cached_lecturer_data_result->isHit()) {
            $Cached_lecturer_data_result->set($this->fetch_lecturer_data_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_lecturer_data_result); // Save the cache item just like you do with doctrine and entities
        
            $this->cached_lecturer_result = $Cached_lecturer_data_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            $this->cached_lecturer_result = $Cached_lecturer_data_result->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    //define the lecturer data
    public function defineLecturerData(){

        $lecturer_row = $this->cached_lecturer_result->fetch_assoc();

        $this->lecturer_title = $lecturer_row['title'];

        $this->lecturer_fullname = $lecturer_row['fullname'];

        $this->lecturer_username = $lecturer_row['username'];

        $this->lecturer_avatar_filename = $lecturer_row['avatar'];

        $this->lecturer_about = $lecturer_row['about'];

        $this->lecturer_avatar = '../resources/avatars/'.$this->lecturer_avatar_filename;

    }

    //display the lecturer profile
    public function displayLecturerProfile(){

        $this->lecturer_profile_page = '
        
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

                        <img loading="lazy" src='.$this->lecturer_avatar.' id="profile_pic" 
                        style="border:5px solid white;padding:5px;max-height:250px;
                        border-radius:150px;max-width:250px;"/>

                        <br>

                        </div>
                        <!-- column one -->

                        <div class="col">

                        <div class="card shadow p-4 mb-4 bg-light border" style="width:300px;
                        font-family:monospace;">

                            <span style="font-size:19px;">
                            <i class="fa fa-user-circle"></i> '.$this->lecturer_title.' '.$this->lecturer_fullname.' <br> 
                               <span class="text-secondary">@'.$this->lecturer_username.'</span>
                            </span>

                            <hr>

                            <span><i class="fa fa-institution"></i> Department Of Educational Technology
                            </span>

                            <hr>

                            <span><i class="fa fa-align-left"></i> About <br>
                               '.$this->lecturer_about.'
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

    public function cachedLecturerProfilePage(){
    
        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "lecturer_profile_page";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->lecturer_profile_page)->expiresAfter(1);//in seconds, also accepts Datetime
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

$lecturer_profile_controller = new lecturerProfile();

if(isset($_SESSION['lecturer_session'])){

    $lecturer_profile_controller->fetchUserSession();

    $lecturer_profile_controller->heading();

    $lecturer_profile_controller->fetchLecturerProfileQuery();

    $lecturer_profile_controller->cacheLecturerResult();

    $lecturer_profile_controller->defineLecturerData();

    $lecturer_profile_controller->displayLecturerProfile();

    $lecturer_profile_controller->cachedLecturerProfilePage();

}else{

    

}

?>