<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : settings/lecturer-settings.php

** About : this module displays the avalaible settings for the lecturer

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the lecturer settings class
 * fetch the lecturer session 
 * fetch the lecturer data
 * cache the lecturer data
 * define the lecturer data
 * fetch the lecturer reset password code
 * cache the lecturer reset password code query
 * define the lecturer passcode data
 * include the header
 * display the lecturer setting sub heading
 * cache the lecturer setting sub heading
 * define the alert callback of the settings success
 * display the lecturer settings
 * cache the lecturer settings
 * display the lecturer setting modal
 * cache the lecturer settings modal
 * perform administrative functions
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

session_start();

//define the lecturer settings class
class lecturerSettings{

    public $lecturer_session;


    public $lecturer_data_result;

    public $cached_lecturer_result;

    public $lecturer_id;

    public $encoded_lecturer_id;

    public $lecturer_title;

    public $lecturer_fullname;

    public $lecturer_about;

    public $lecturer_username;

    public $lecturer_avatar_file_name;

    public $avatar;

    public $encoded_avatar;


    public $settings_subheading;

    public $lecturer_settings;

    public $lecturer_settings_modal;


    public $lecturer_passcode_result;

    public $cached_lecturer_passscode_result;

    public $lecturer_passcode;

    //fetch the lecturer session 
    public function fetchLecturerSession(){

        $this->lecturer_session = $_SESSION['lecturer_session'];

    }

    //fetch the lecturer data
    public function fetchLecturerDataQuery(){

        // user db connection
        include('../resources/database/users-db-connection.php');

        $lecturer_data_query = "SELECT * FROM lecturers WHERE encrypted_id = '$this->lecturer_session'";

        $this->lecturer_data_result = $conn1->query($lecturer_data_query);

        mysqli_close($conn1);

    }

    //cache the lecturer data
    public function cacheLecturerDataQuery(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "lecturer_data_result";
        $Cached_lecturer_data_result = $InstanceCache->getItem($key);
        
        if (!$Cached_lecturer_data_result->isHit()) {
            $Cached_lecturer_data_result->set($this->lecturer_data_result)->expiresAfter(1);//in seconds, also accepts Datetime
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

        $this->lecturer_id = $lecturer_row['id'];

        $this->encoded_lecturer_id = base64_encode($this->lecturer_id);

        $this->lecturer_title = $lecturer_row['title'];

        $this->lecturer_fullname = $lecturer_row['fullname'];

        $this->lecturer_about = $lecturer_row['about'];

        $this->lecturer_username = $lecturer_row['username'];

        $this->lecturer_avatar_file_name = $lecturer_row['avatar'];

        $this->avatar = '../resources/avatars/'.$this->lecturer_avatar_file_name;

        $this->encoded_avatar = base64_encode($this->lecturer_avatar_file_name);

    }

    //fetch the lecturer reset password code
    public function fetchLecturerResetpasscode(){

        //reset password  db connection
        include('../resources/database/users-reset-password-db-connection.php');

        //conn18

        $lecturer_passcode_query = "SELECT * FROM password_reset_codes WHERE encrypted_user_id = '$this->lecturer_session'";

        $this->lecturer_passcode_result = $conn18->query($lecturer_passcode_query);

        mysqli_close($conn18);

    }

    //cache the lecturer reset password code query
    public function cacheLecturerPasscodeQuery(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "lecturer_passcode_result";
        $Cached_lecturer_passcode_result = $InstanceCache->getItem($key);
        
        if (!$Cached_lecturer_passcode_result->isHit()) {
            $Cached_lecturer_passcode_result->set($this->lecturer_passcode_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_lecturer_passcode_result); // Save the cache item just like you do with doctrine and entities
        
            $this->cached_lecturer_passscode_result = $Cached_lecturer_passcode_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            $this->cached_lecturer_passscode_result = $Cached_lecturer_passcode_result->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    //define the lecturer passcode data
    public function defineLecturerPascodeData(){

        $passcode_row = $this->cached_lecturer_passscode_result->fetch_assoc();

        $this->lecturer_passcode = $passcode_row['reset_code'];

    }

    //include the header
    public function heading(){

        include('header/header.php');
 
    }

    //display the lecturer setting sub heading
    public function settingsSubheading(){

        $this->settings_subheading = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

            <br>

            <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
   
               Lecturer Settings <i class="fa fa-cogs"></i>
   
            </h1>

            <br>

        </div>

        <br>
        
        ';

    }

    //cache the lecturer setting sub heading
    public function cacheSettingsSubheading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "lecturer_settings_subheading";
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

    public function titleSuccessAlert(){

        echo '
        
        <div align="center">

        <div class="alert alert-info" style="width:200px;height:80px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         Title updated 
 
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

    //display the lecturer settings
    public function displayLecturerSettings(){

        $this->lecturer_settings = '
        
        <div class="container">

            <h3>Settings <i class="fa fa-cogs"></i> </h3>

            <div class="alert alert-info">

                <b>Note:</b> The changes you make on your profile will not reflect in your old courses,<br>
                The changes will only reflect in new courses.

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

    //cache the lecturer settings
    public function cacheLecturerSettings(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "lecturer_settings";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->lecturer_settings)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
       }

    }

    //display the lecturer setting modal
    public function displayLecturerSettingsModal(){

        $this->lecturer_settings_modal = '
        
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

                                <input type="hidden" name="encryptedId" value='.$this->lecturer_session.'>

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

                        <p>'.$this->lecturer_title.'</p>

                        <form action="update-lecturer-title.php" method="POST">

                            <div class="input-group">

                                <select class="form-control" name="newTitle">

                                    <option>Mr</option>
                                    <option>Mrs</option>
                                    <option>Dr</option>
                                    <option>Prof</option>

                                </select>

                                <input type="hidden" name="encryptedId" value='.$this->lecturer_session.'>

                                <button type="submit" class="input-group-text text-light" style="background-color:#1d007e;">
                                    Update  <i class="fa fa-edit"></i>
                                </button>

                            </div>

                        </form>

                        <br>

                        <p>'.$this->lecturer_fullname.'</p>

                        <form action="update-fullname.php" method="POST">

                            <div class="input-group">

                                <input class="form-control" type="text" name="newFullname" placeholder="Update your fullname" 
                                required style="height:40px;width:150px;">

                                <input type="hidden" name="encryptedId" value='.$this->lecturer_session.'>

                                <button type="submit" class="input-group-text text-light" style="background-color:#1d007e;">
                                    Update  <i class="fa fa-edit"></i>
                                </button>

                            </div>

                        </form>

                        <br>

                        <p>'.$this->lecturer_username.'</p>

                        <form action="update-username.php" method="POST">

                            <div class="input-group">

                                <input class="form-control" type="text" name="newUsername" placeholder="Update your username" 
                                required style="height:40px;width:150px;">

                                <input type="hidden" name="encryptedId" value='.$this->lecturer_session.'>

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
                                    '.$this->lecturer_about.'
                                </div>
                                <!-- accordion body -->

                            </div>
                            
                        </div>
                        <!-- about accordion -->

                        <br>

                        <form action="update-about.php" method="POST">

                           <div class="input-group">

                              <textarea class="form-control" name="newAbout" placeholder="Update info about you"></textarea>

                              <input type="hidden" name="encryptedId" value='.$this->lecturer_session.'>

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
                                    '.$this->lecturer_passcode.'
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

                                Make sure you have deleted all the created courses in your account, to avert errors in
                                the system.<br>
                                And when you delete your profile, you cannot retrieve it back!!!

                            </div>

                            </small>

                            <form action="delete-lecturer-account.php" method="POST">

                                <input type="hidden" name="encryptedId" value='.$this->lecturer_session.'>

                                <input type="hidden" name="lecturerId" value='.$this->encoded_lecturer_id.'>

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

    public function cacheLecturerSettingsModal(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "lecturer_settings_modal";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->lecturer_settings_modal)->expiresAfter(1);//in seconds, also accepts Datetime
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

$lecturer_settings = new lecturerSettings();

if(isset($_SESSION['lecturer_session'])){

    $lecturer_settings->fetchLecturerSession();

    $lecturer_settings->fetchLecturerDataQuery();

    $lecturer_settings->cacheLecturerDataQuery();

    $lecturer_settings->defineLecturerData();

    $lecturer_settings->fetchLecturerResetpasscode();

    $lecturer_settings->cacheLecturerPasscodeQuery();

    $lecturer_settings->defineLecturerPascodeData();

    $lecturer_settings->heading();

    $lecturer_settings->settingsSubheading();

    $lecturer_settings->cacheSettingsSubheading();

    if(isset($_GET['avatarSuccess'])){

        $lecturer_settings->avatarSuccessAlert();

    }elseif(isset($_GET['titleSuccess'])){

        $lecturer_settings->titleSuccessAlert();

    }elseif(isset($_GET['fullnameSuccess'])){

        $lecturer_settings->fullnameSuccessAlert();

    }elseif(isset($_GET['usernameSuccess'])){

        $lecturer_settings->usernameSuccessAlert();

    }elseif(isset($_GET['aboutSuccess'])){

        $lecturer_settings->aboutSuccessAlert();

    }

    $lecturer_settings->displayLecturerSettings();

    $lecturer_settings->cacheLecturerSettings();

    $lecturer_settings->displayLecturerSettingsModal();

    $lecturer_settings->cacheLecturerSettingsModal();

}else{

    header("location:logout-success.php");

}

?>