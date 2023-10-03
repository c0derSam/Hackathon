<?php
/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : settings/student-settings.php

** About : this module displays the avalaible settings for the student

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the student settings class
 * fetch the student session 
 * fetch the student data
 * cache the student data
 * define the student data
 * fetch the student reset password code
 * cache the student reset password code query
 * define the student passcode data
 * include the header
 * display the student setting sub heading
 * cache the student setting sub heading
 * define the alert callback of the settings success
 * display the student settings
 * cache the student settings
 * display the student setting modal
 * cache the student settings modal
 * perform administrative functions
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

session_start();

//define the student settings class
class studentSettings{

    public $student_session;


    public $student_data_result;

    public $cached_stduent_result;

    public $student_id;

    public $encoded_id;

    public $student_fullname;

    public $student_about;

    public $student_username;

    public $student_avatar_filename;

    public $avatar;

    public $encoded_avatar;


    public $student_passcode_result;

    public $cached_student_passscode_result;

    public $student_passcode;


    public $settings_subheading;

    public $student_settings;

    public $student_settings_modal;

    //fetch the student session 
    public function fetchStudentSession(){

        $this->student_session = $_SESSION['student_session'];

    }

    //fetch the student data
    public function fetchStudentData(){

        // user db connection
        include('../resources/database/users-db-connection.php');

        $student_data_query = "SELECT * FROM students WHERE encrypted_id = '$this->student_session'";

        $this->student_data_result = $conn1->query($student_data_query);

        mysqli_close($conn1);

    }

    //cache the student data
    public function cacheStudentDataQuery(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "student_data_result";
        $Cached_student_data_result = $InstanceCache->getItem($key);
        
        if (!$Cached_student_data_result->isHit()) {
            $Cached_student_data_result->set($this->student_data_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_student_data_result); // Save the cache item just like you do with doctrine and entities
        
            $this->cached_student_result = $Cached_student_data_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            $this->cached_stduent_result = $Cached_student_data_result->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    //define the student data
    public function defineStudentData(){

        $student_row = $this->cached_student_result->fetch_assoc();

        $this->student_id = $student_row['id'];

        $this->encoded_id = base64_encode($this->student_id);

        $this->student_fullname = $student_row['fullname'];

        $this->student_about = $student_row['about'];

        $this->student_username = $student_row['username'];

        $this->student_avatar_filename = $student_row['avatar'];

        $this->avatar = '../resources/avatars/'.$this->student_avatar_filename;

        $this->encoded_avatar = base64_encode($this->student_avatar_filename);

    }

    //fetch the student reset password code
    public function fetchStudentResetpasscode(){

        //reset password  db connection
        include('../resources/database/users-reset-password-db-connection.php');

        //conn18

        $student_passcode_query = "SELECT * FROM password_reset_codes WHERE encrypted_user_id = '$this->student_session'";

        $this->student_passcode_result = $conn18->query($student_passcode_query);

        mysqli_close($conn18);

    }

    //cache the student reset password code query
    public function cacheStudentPasscodeQuery(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "student_passcode_result";
        $Cached_student_passcode_result = $InstanceCache->getItem($key);
        
        if (!$Cached_student_passcode_result->isHit()) {
            $Cached_student_passcode_result->set($this->student_passcode_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_student_passcode_result); // Save the cache item just like you do with doctrine and entities
        
            $this->cached_student_passscode_result = $Cached_student_passcode_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            $this->cached_student_passscode_result = $Cached_student_passcode_result->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }    

    }

    //define the student passcode data
    public function defineStudentPascodeData(){

        $passcode_row = $this->cached_student_passscode_result->fetch_assoc();

        $this->student_passcode = $passcode_row['reset_code'];

    }
    
    //include the header
    public function heading(){

        include('header/header.php');
 
    }

    //display the student setting sub heading
    public function settingsSubheading(){

        $this->settings_subheading = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

            <br>

            <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
   
               Student Settings <i class="fa fa-cogs"></i>
   
            </h1>

            <br>

        </div>

        <br>
        
        ';

    }

    //cache the student setting sub heading
    public function cacheSettingsSubheading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "student_settings_subheading";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->settings_subheading)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
       }

    }

    //define the alert callback of the settings success
    public function avatarSuccessAlert(){

        echo '
        
        <div align="center">

        <div class="alert alert-info" style="width:200px;height:80px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         Avatar updated 
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
             </div>
 
        </div>';

    }

    public function fullnameSuccessAlert(){

        echo '
        
        <div align="center">

        <div class="alert alert-info" style="width:200px;height:80px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         Fullname updated 
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
             </div>
 
        </div>';

    }

    public function usernameSuccessAlert(){

        echo '
        
        <div align="center">

        <div class="alert alert-info" style="width:200px;height:80px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         Username updated 
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
        </div>
 
        </div>';

    }

    public function aboutSuccessAlert(){

        echo '
        
        <div align="center">

        <div class="alert alert-info" style="width:200px;height:80px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         About updated 
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
        </div>
 
        </div>';

    }

    //display the student settings
    public function displayStudentSettings(){

        $this->student_settings = '
        
        <div class="container">

            <h3>Settings <i class="fa fa-cogs"></i> </h3>

            <div class="alert alert-info">

                <b>Note:</b> The changes you make on your profile will not reflect in courses your enrolled in,<br>
                The changes will only reflect in new courses you enroll in.

            </div>

            <hr>

            <ul class="list-group list-group-flush">

                <li class="list-group-item" data-bs-toggle="modal" 
                data-bs-target="#changeAvatarModal">

                Change avatar <i class="fa fa-image"></i>

                </li>

                <li class="list-group-item" data-bs-toggle="modal" 
                data-bs-target="#editUserDataModal">

                Edit your info <i class="fa fa-user"></i>

                </li>

                <li class="list-group-item" data-bs-toggle="modal" 
                data-bs-target="#passcodeModal">

                Password reset code <i class="fa fa-lock"></i>

                </li>

                <li class="list-group-item">

                <button class="btn btn-danger" data-bs-toggle="modal" 
                data-bs-target="#deleteAccountModal">
    
                Delete account <i class="fa fa-trash"></i>

                </button>

                </li>

            </ul>

        </div>
        
        ';

    }

    //cache the student settings
    public function cacheStudentSettings(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "student_settings";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->student_settings)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
       }

    }

    //display the student setting modal
    public function displayStudentSettingsModal(){

        $this->student_settings_modal = '
        
        <div class="modal fade" id="changeAvatarModal" data-bs-backdrop="static" 
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" 
        style="display: none;" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                        Change avatar <i class="fa fa-image"></i>
                        </h5>

                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>
                    <!-- modal header -->

                    <div class="modal-body">

                        <div align="center">

                            <img loading="lazy" src='.$this->avatar.' id="profile_pic" 
                            style="border:5px solid white;padding:5px;max-height:200px;
                            border-radius:300px;max-width:200px;"/>

                            <form action="change-avatar.php" method="POST" enctype="multipart/form-data">

                                <label align="center" for="avatar" class="text-secondary"><b>Change profile picture</b>
                                </label><br>
                                <input id="avatar" type="file" name="newAvatar" accept="image/*" style="width:170px;">

                                <input type="hidden" name="encryptedId" value='.$this->student_session.'>

                                <input type="hidden" name="previousImageFilePath" value='.$this->encoded_avatar.'>

                                <br><br>

                                <button type="submit" class="input-group-text text-light" style="background-color:#1d007e;">
                                    Update avatar <i class="fa fa-image"></i>
                                </button>

                            </form>

                        </div>

                    </div>

                </div>
                <!-- modal content -->

            </div>

        </div>
        <!-- change avatar modal -->

        <div class="modal fade" id="editUserDataModal" data-bs-backdrop="static" 
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" 
        style="display: none;" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                            Edit your info <i class="fa fa-user"></i>
                        </h5>

                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>
                    <!-- modal header --> 

                    <div class="modal-body">

                        <p>'.$this->student_fullname.'</p>

                        <form action="update-fullname.php" method="POST">

                            <div class="input-group">

                                <input class="form-control" type="text" name="newFullname" placeholder="Update your fullname" 
                                required style="height:40px;width:150px;">

                                <input type="hidden" name="encryptedId" value='.$this->student_session.'>

                                <button type="submit" class="input-group-text text-light" style="background-color:#1d007e;">
                                    Update  <i class="fa fa-edit"></i>
                                </button>

                            </div>

                        </form>

                        <br>

                        <p>'.$this->student_username.'</p>

                        <form action="update-username.php" method="POST">

                            <div class="input-group">

                                <input class="form-control" type="text" name="newUsername" placeholder="Update your username" 
                                required style="height:40px;width:150px;">

                                <input type="hidden" name="encryptedId" value='.$this->student_session.'>

                                <button type="submit" class="input-group-text text-light" style="background-color:#1d007e;">
                                    Update  <i class="fa fa-edit"></i>
                                </button>

                            </div>

                        </form>

                        <br>

                        <div class="accordion-item">

                            <p class="accordion-header">
                               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                               data-bs-target="#aboutYou" aria-expanded="false" aria-controls="collapseTwo">
                                    About you
                                </button>
                            </p>

                            <div id="aboutYou" class="accordion-collapse collapse" aria-labelledby="headingTwo">

                                <div class="accordion-body">
                                    '.$this->student_about.'
                                </div>
                                <!-- accordion body -->

                            </div>
                            
                        </div>
                        <!-- about accordion -->

                        <br>

                        <form action="update-about.php" method="POST">

                           <div class="input-group">

                              <textarea class="form-control" name="newAbout" placeholder="Update info about you"></textarea>

                              <input type="hidden" name="encryptedId" value='.$this->student_session.'>

                              <button type="submit" class="input-group-text text-light" style="background-color:#1d007e;">
                                Update  <i class="fa fa-edit"></i>
                              </button>
                              
                            </div>

                        </form>

                    </div>
                    <!-- modal body -->

                </div>

            </div>

        </div>
        <!-- edit user data -->

        <div class="modal fade" id="passcodeModal" data-bs-backdrop="static" 
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" 
        style="display: none;" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                            Password reset code <i class="fa fa-lock"></i>
                        </h5>

                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>
                    <!-- modal header -->

                    <div class="modal-body">

                        <div align="center">

                            <small>

                                <div class="alert alert-info">

                                    You should copy and paste this code in a secure place,<br>
                                    It will be used to reset your password when you loose it.

                                </div>

                            </small>

                        </div>

                        <div class="accordion-item">

                            <p class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#passcode" aria-expanded="false" aria-controls="collapseTwo">
                                    Password reset code
                                </button>
                            </p>

                            <div id="passcode" class="accordion-collapse collapse" aria-labelledby="headingTwo">

                                <div class="accordion-body">
                                    '.$this->student_passcode.'
                                </div>
                                <!-- accordion body -->

                            </div>
                    
                        </div>
                        <!-- about accordion -->


                    </div>
                    <!-- modal body -->

                </div>

            </div>

        </div>
        <!-- passcode modal -->

        <div class="modal fade" id="deleteAccountModal" data-bs-backdrop="static" 
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" 
        style="display: none;" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                            Delete user account <i class="fa fa-trash"></i>
                        </h5>

                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>
                    <!-- modal header -->

                    <div class="modal-body">

                        <div align="center">

                            <small>

                            <div class="alert alert-warning">

                                Make sure you have unenrolled from all your courses in your account, to avert errors in
                                the system.<br>
                                And when you delete your account, you cannot retrieve it back!!!

                            </div>

                            </small>

                            <form action="delete-student-account.php" method="POST">

                                <input type="hidden" name="encryptedId" value='.$this->student_session.'>

                                <input type="hidden" name="studentId" value='.$this->encoded_id.'>

                                <input type="hidden" name="avatarFileName" value='.$this->encoded_avatar.'>

                                <button type="submit" value="send" class="btn btn-danger" name="deleteAccount">

                                    Delete acount <i class="fa fa-trash"></i>

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <!-- delete account modal -->
        
        ';

    }

    //cache the student settings modal
    public function cacheStudentSettingsModal(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "student_settings_modal";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->student_settings_modal)->expiresAfter(1);//in seconds, also accepts Datetime
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

$student_settings = new studentSettings();

if(isset($_SESSION['student_session'])){

    $student_settings->fetchStudentSession();

    $student_settings->fetchStudentData();

    $student_settings->cacheStudentDataQuery();

    $student_settings->defineStudentData();

    $student_settings->fetchStudentResetpasscode();

    $student_settings->cacheStudentPasscodeQuery();

    $student_settings->defineStudentPascodeData();

    $student_settings->heading();

    $student_settings->settingsSubheading();

    $student_settings->cacheSettingsSubheading();

    if(isset($_GET['avatarSuccess'])){

        $student_settings->avatarSuccessAlert();

    }elseif(isset($_GET['fullnameSuccess'])){

        $student_settings->fullnameSuccessAlert();

    }elseif(isset($_GET['usernameSuccess'])){

        $student_settings->usernameSuccessAlert();

    }elseif(isset($_GET['aboutSuccess'])){

        $student_settings->aboutSuccessAlert();

    }

    $student_settings->displayStudentSettings();

    $student_settings->cacheStudentSettings();

    $student_settings->displayStudentSettingsModal();

    $student_settings->cacheStudentSettingsModal();

}

?>