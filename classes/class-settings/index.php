<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-settings/index.php

** About : this module displays the avalaible settings for the class

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the class settings class
 * fetch the class id
 * fetch the class data
 * define the class data
 * cache the clas data
 * include the header
 * display the class setting sub heading
 * cache the class setting sub heading
 * define the alert callback of the settings success
 * display the class settings
 * cache the class settings
 * display the class setting modals
 * cache the class settings modals
 * perform administrative functions
 * 
 * *
 */

session_start();

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the class settings class
class classSettings{

    public $class_id;

    public $course_id;


    public $class_query_result;

    public $cached_class_result;

    public $class_row;

    public $class_topic;

    public $about_class;

    public $assignment_topic;

    public $class_auto_increment_id;

    public $class_material;

    public $class_note;


    public $class_settings_sub_heading;

    public $class_settings;

    public $class_settings_modal;

    //fetch the class id
    public function fetchClassIdAndCourseId(){

        $this->class_id = $_GET['classId'];

        $this->course_id = $_GET['courseId'];

    }

    //fetch the class data
    public function fetchClassData(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-db-connection.php');

        $class_data_query = "SELECT * FROM classes_of_course_".$this->course_id." WHERE class_encrypted_id = '$this->class_id'";

        $this->class_query_result = $conn10->query($class_data_query);

        mysqli_close($conn10);

    }

    //cache the clas data
    public function cacheClassQuery(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "class_room_data";
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

        $this->assignment_topic = $this->class_row['assignment_topic'];

        $this->class_auto_increment_id = $this->class_row['id'];
        
        $this->class_material = $this->class_row['instructional_material'];

        $this->class_note = $this->class_row['class_note'];
    }

    //include the header
    public function header(){

        include('../header/header.php');

    }

    //display the class setting sub heading
    public function classSettingsSubHeading(){

        $this->class_settings_sub_heading = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

            <br>

            <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
   
               Class Settings <i class="fa fa-cogs"></i>
   
            </h1>

            <br>

        </div>

        <br>

        <div class="create-course" align="center">

            <div class="card shadow p-4 mb-4 bg-light" style="width:200px;">

                <a class="text-white" href="../class-dashboard/index.php?id='.$this->class_id.'&&course_id='.$this->course_id.'" style="text-decoration:none;">

                    <button class="btn btn-md text-white" style="background-color:#1d007e;">

                    <i class="fa fa-arrow-left"></i> Back to class

                    </button>

                </a>

            </div>

        </div>
        <!-- container-->
        
        ';

    }

    public function cacheClassSubHeading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "class_settings_subheading";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->class_settings_sub_heading)->expiresAfter(1);//in seconds, also accepts Datetime
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
    public function editClassTitleAlert(){

       echo '
       
       <div align="center">

       <div class="alert alert-info" style="width:200px;height:80px;">

                <div class="row">

                    <div class="col">
                   
                        Class title updated 

                    </div>

                    <div class="col">

                       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    </div>

               </div>

            </div>

       </div>
       
       ';

    }


    public function editAboutAlert(){

        echo '
        
        <div align="center">
 
        <div class="alert alert-info" style="width:200px;height:100px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         About class updated 
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
             </div>
 
        </div>
        
        ';
 
    }


    public function editAssignmentAlert(){

        echo '
        
        <div align="center">
 
        <div class="alert alert-info" style="width:200px;height:100px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         Assignment updated 
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
             </div>
 
        </div>
        
        ';
 
    }

    public function clearAttendanceAlert(){

        echo '
        
        <div align="center">
 
        <div class="alert alert-info" style="width:200px;height:100px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         Attendance deleted
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
             </div>
 
        </div>
        
        ';
 
    }
    
    public function updateClassNoteAlert(){

        echo '
        
        <div align="center">
 
        <div class="alert alert-info" style="width:200px;height:100px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         Class Note Updated
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
             </div>
 
        </div>
        ';

    }
 

    //display the class settings
    public function displayClassSettings(){

        $this->class_settings = '
        
        <div class="container">

            <h3>Settings <i class="fa fa-cogs"></i> </h3>

            <hr>

            <ul class="list-group list-group-flush">

                <li class="list-group-item" data-bs-toggle="modal" 
                data-bs-target="#editClassInfoModal">

                Edit class <i class="fa fa-edit"></i>

                </li>

                <li class="list-group-item" data-bs-toggle="modal" 
                data-bs-target="#editClassNoteModal">

                Edit class note <i class="fa fa-edit"></i>

                </li>

                <li class="list-group-item" data-bs-toggle="modal" 
                data-bs-target="#editAssignmentModal">

                Edit assignment Info <i class="fa fa-edit"></i>

                </li>

                <li class="list-group-item" data-bs-toggle="modal" 
                data-bs-target="#clearAttendanceModal">

                Clear attendance <i class="fa fa-users"></i>

                </li>

                <li class="list-group-item">

                <button class="btn btn-danger" data-bs-toggle="modal" 
                data-bs-target="#deleteClass">
    
                   Delete class <i class="fa fa-trash"></i>

                </button>

                </li>

            </ul>

        </div>
        
        ';

    }

    //cache the class settings
    public function cacheDisplaySettings(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "display_class_settings";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->class_settings)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
       }

    }

    //display the class setting modals
    public function displayClassSettingsModal(){

        $this->class_settings_modal = '
        
        <div class="modal fade" id="editClassInfoModal" data-bs-backdrop="static" 
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" 
        style="display: none;" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                        Edit class <i class="fa fa-edit"></i>
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div id="alert">

                        </div>

                        <p>'.$this->class_topic.'</p>

                        <form action="edit-class-title-controller.php" method="POST">

                           <div class="input-group">

                              <input class="form-control" type="text" name="classTitle" placeholder="Edit class title" 
                              required style="height:40px;width:150px;">

                              <input type="hidden" name="classId" value='.$this->class_id.'>

                              <input type="hidden" name="courseId" value='.$this->course_id.'>

                              <button type="submit" class="input-group-text text-light" style="background-color:#1d007e;">
                                Edit  <i class="fa fa-edit"></i>
                              </button>

                            </div>

                        </form>

                        <br>

                        <div class="accordion-item">

                            <p class="accordion-header">
                               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                               data-bs-target="#aboutClass" aria-expanded="false" aria-controls="collapseTwo">
                                    About class
                                </button>
                            </p>

                            <div id="aboutClass" class="accordion-collapse collapse" aria-labelledby="headingTwo">

                            <div class="accordion-body">
                                    '.$this->about_class.'
                            </div>

                            <!-- accordion body -->

                            </div>
                            

                        </div>
                        <!-- about accordion -->

                        <br>

                        <form action="edit-about-class-controller.php" method="POST">

                           <div class="input-group">

                              <textarea class="form-control" name="aboutClass" placeholder="Edit about class"></textarea>

                              <input type="hidden" name="classId" value='.$this->class_id.'>

                              <input type="hidden" name="courseId" value='.$this->course_id.'>

                              <button type="submit" class="input-group-text text-light" style="background-color:#1d007e;">
                                Edit  <i class="fa fa-edit"></i>
                              </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>
        <!-- edit class info modal -->

        <div class="modal fade" id="editAssignmentModal" data-bs-backdrop="static" 
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" 
        style="display: none;" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                        Edit assignment <i class="fa fa-edit"></i>
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="accordion-item">

                            <p class="accordion-header">
                               <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                               data-bs-target="#assignment" aria-expanded="false" aria-controls="collapseTwo">
                                    Assignment
                                </button>
                            </p>

                            <div id="assignment" class="accordion-collapse collapse" aria-labelledby="headingTwo">

                            <div class="accordion-body">
                                    '.$this->assignment_topic.'
                            </div>

                            <!-- accordion body -->

                            </div>
                            

                        </div>
                        <!-- about accordion -->

                        <br>

                        <form action="edit-assignment-info-controller.php" method="POST">

                           <div class="input-group">

                              <input class="form-control" type="text" name="assignment" placeholder="Edit assignment" 
                              required style="height:40px;width:150px;">

                              <input type="hidden" name="classId" value='.$this->class_id.'>

                              <input type="hidden" name="courseId" value='.$this->course_id.'>

                              <button type="submit" value="send" class="input-group-text text-light" style="background-color:#1d007e;">
                                Edit  <i class="fa fa-edit"></i>
                              </button>

                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>
        <!-- edit assignment modal -->

        <div class="modal fade" id="clearAttendanceModal" data-bs-backdrop="static" 
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" 
        style="display: none;" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                        Clear attendance <i class="fa fa-users"></i>
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div align="center">

                            <div class="alert alert-warning">

                                By clearing your class attendance, <br>you are completely deleting your classroom attendance

                            </div>

                            <form action="clear-class-attendance-controller.php" method="POST">

                                <input type="hidden" name="classId" value='.$this->class_id.'>

                                <input type="hidden" name="courseId" value='.$this->course_id.'>

                                <button type="submit" value="send" class="btn btn-danger">

                                    Clear <i class="fa fa-trash"></i>

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <!-- clear attendance modal -->

        <div class="modal fade" id="deleteClass" data-bs-backdrop="static" 
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" 
        style="display: none;" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                        Delete class <i class="fa fa-list-alt"></i>
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div align="center">

                            <div class="alert alert-warning">

                                By deleting your class, <br>you are completely erasing your class instructional material, 
                                assignments, attendance and class chat.

                            </div>

                            <form action="delete-class-controller.php" method="POST">

                                <input type="hidden" name="autoIncrementId" value='.$this->class_auto_increment_id.'>

                                <input type="hidden" name="classId" value='.$this->class_id.'>

                                <input type="hidden" name="courseId" value='.$this->course_id.'>

                                <input type="hidden" name="classMaterial" value='.$this->class_material.'>

                                <input type="hidden" name="classNote" value='.$this->class_note.'>

                                <button type="submit" value="send" class="btn btn-danger">

                                    Delete class <i class="fa fa-trash"></i>

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <!-- Delete attendance modal -->
        
        ';

        echo '

        <div class="modal fade" id="editClassNoteModal" data-bs-backdrop="static" 
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" 
        style="display: none;" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                        Edit course outline <i class="fa fa-edit"></i>
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div class="accordion-item">

                            <p class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" 
                                data-bs-target="#aboutClass" aria-expanded="false" aria-controls="collapseTwo">
                                    Class note
                                </button>
                            </p>

                            <div id="aboutClass" class="accordion-collapse collapse" aria-labelledby="headingTwo">
        
        ';

        $read_class_note_file = file($this->class_note);

        foreach($read_class_note_file as $Class_Note){

            echo '
        
                <div class="accordion-body">
                    '.$Class_Note.'
                </div>
                <!-- accordion body -->

            ';

        }

        echo '

                            </div>
            

                        </div>
                        <!-- about accordion -->
        
                        <form action="edit-class-note.php" method="POST">

                            <br>

                            <div class="input-group">

                              <textarea class="form-control" rows="10" name="classNote" placeholder="Edit class note"></textarea>

                                <input type="hidden" name="classId" value='.$this->class_id.'>

                                <input type="hidden" name="courseId" value='.$this->course_id.'>

                                <input type="hidden" name="classNoteFile" value='.$this->class_note.'>

                              <button type="submit" class="input-group-text text-light" style="background-color:#1d007e;">
                                Edit  <i class="fa fa-edit"></i>
                              </button>
                              
                            </div>

                        </form>

                    </div>

                </div>

            </div>

        </div>
        <!-- edit course outline modal -->
        
        ';


    }

    //cache the class settings modals
    public function cacheClassSettingModal(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "class_setting_modal";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->class_settings_modal)->expiresAfter(1);//in seconds, also accepts Datetime
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

$class_settings = new classSettings();

$class_settings->fetchClassIdAndCourseId();

$class_settings->fetchClassData();

$class_settings->cacheClassQuery();

$class_settings->defineClassData();

$class_settings->header();

if(isset($_SESSION['lecturer_session'])){

    if(!empty($_GET['classId']) && !empty($_GET['courseId'])){

        $class_settings->classSettingsSubHeading();

        $class_settings->cacheClassSubHeading();

        if(!empty($_GET['classTitleUpdate'])){

            $class_settings->editClassTitleAlert();

        }elseif(!empty($_GET['aboutClassUpdate'])){

            $class_settings->editAboutAlert();

        }elseif(!empty($_GET['classAssignmentUpdate'])){

            $class_settings->editAssignmentAlert();


        }elseif(!empty($_GET['clearClassAttendance'])){

            $class_settings->clearAttendanceAlert();

        }elseif(!empty($_GET['updateClassNote'])){

            $class_settings->updateClassNoteAlert();

        }

        $class_settings->displayClassSettings();

        $class_settings->cacheDisplaySettings();

        $class_settings->displayClassSettingsModal();

        $class_settings->cacheClassSettingModal();

    }

}else{

    header('location:logout-success.php');

}


?>