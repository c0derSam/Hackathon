<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/courses-settings/index.php

** About : this module displays the avalaible settings for the course

*/ 
 
/**PSUEDO ALGORITHM
 * *
 * define the course settings class
 * fetch the course id
 * fetch the course data
 * cache the course data
 * define the course data 
 * include the header
 * display the course setting sub heading
 * cache the course setting sub heading
 * define the alert callback of the settings success
 * display the course settings
 * cache the course settings
 * display the course setting moda
 * cache the course settings modal
 * perform administrative functions
 * 
 * *
 */

session_start();

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

class courseSettings{

    public $course_id;

    
    public $course_data_result;

    public $cached_course_result;

    public $course_data_row;

    public $course_code;

    public $course_title;

    public $about_course;

    public $course_increment_id;

    public $course_outline;


    public $course_settings_sub_heading;

    public $class_settings;

    public $course_settings_modal;

    public function fetchCourseId(){

       $this->course_id = $_GET['courseId'];

    }
    
    //fetch the course data
    public function fetchCourseData(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/courses-db-connection.php');

        $course_data_query = "SELECT * FROM courses WHERE course_encrypted_id = '$this->course_id'";

        $this->course_data_result = $conn8->query($course_data_query);

        $conn8->error;

        mysqli_close($conn8);

    }

    //cache the course data
    public function cachedCourseQuery(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "course_settings_data";
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

        $this->course_increment_id = $this->course_data_row['id'];

        $this->course_outline = $this->course_data_row['course_outline'];

    }

    //include the header
    public function header(){

        include('../header/header.php');

    }

    //display the course setting sub heading
    public function courseSettingsSubHeading(){

        $this->course_settings_sub_heading = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

            <br>

            <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
   
               Course Settings <i class="fa fa-cogs"></i>
   
            </h1>

            <br>

        </div>

        <br>

        <div class="create-course" align="center">

            <div class="card shadow p-4 mb-4 bg-light" style="width:200px;">

                <a class="text-white" href="../courses-dashboard/index.php?id='.$this->course_id.'" style="text-decoration:none;">

                    <button class="btn btn-md text-white" style="background-color:#1d007e;">

                    <i class="fa fa-arrow-left"></i> Back to course

                    </button>

                </a>

            </div>

        </div>
        <!-- container-->
        
        ';

    }

    //cache the course setting sub heading
    public function cacheClassSubHeading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "course_settings_subheading";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->course_settings_sub_heading)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
       }

    }

    public function editCourseCodeAlert(){

        echo '
       
        <div align="center">
 
        <div class="alert alert-info" style="width:200px;height:100px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         Course Code Updated 
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
             </div>
 
        </div>
        
        ';

    }

    public function editCourseTitleAlert(){

        echo '
       
        <div align="center">
 
        <div class="alert alert-info" style="width:200px;height:100px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         Course Title Updated 
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
             </div>
 
        </div>
        
        ';

    }

    public function editAboutCourseAlert(){

        echo '
       
        <div align="center">
 
        <div class="alert alert-info" style="width:200px;height:100px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         About Course Updated 
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
             </div>
 
        </div>
        
        ';

    }

    public function clearEnrolledStudentsAlert(){

        echo '
       
        <div align="center">
 
        <div class="alert alert-info" style="width:200px;height:130px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         Course Enrolled Students Cleared
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
             </div>
 
        </div>
        
        ';

    }

    public function editCourseOutlineAlert(){

        echo '
       
        <div align="center">
 
        <div class="alert alert-info" style="width:200px;height:100px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         Course Outline Updated 
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
             </div>
 
        </div>
        
        ';

    }


    //display the course settings
    public function displayCourseSettings(){

        $this->class_settings = '
        
        <div class="container">

            <h3>Settings <i class="fa fa-cogs"></i> </h3>

            <hr>

            <ul class="list-group list-group-flush">

                <li class="list-group-item" data-bs-toggle="modal" 
                data-bs-target="#editCourseInfoModal">

                Edit course <i class="fa fa-edit"></i>

                </li>

                <li class="list-group-item" data-bs-toggle="modal" 
                data-bs-target="#editCourseOutlineModal">

                Edit course outline <i class="fa fa-edit"></i>

                </li>

                <li class="list-group-item" data-bs-toggle="modal" 
                data-bs-target="#clearEnrolledStudentsModal">

                Clear enrolled students <i class="fa fa-users"></i>

                </li>

                <li class="list-group-item">

                <button class="btn btn-danger" data-bs-toggle="modal" 
                data-bs-target="#deleteCourseModal">
    
                   Delete course <i class="fa fa-trash"></i>

                </button>

                </li>

            </ul>

        </div>
        
        ';

    }

    public function cacheCourseSettings(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "course_settings";
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

    public function courseSettingModal(){

        $this->course_settings_modal = '
        
        <div class="modal fade" id="editCourseInfoModal" data-bs-backdrop="static" 
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" 
        style="display: none;" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                        Edit course <i class="fa fa-edit"></i>
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <p>'. $this->course_code.'</p>

                        <form action="edit-course-code-controller.php" method="POST">

                           <div class="input-group">

                              <input class="form-control" type="text" name="courseCode" placeholder="Edit course code" 
                              required style="height:40px;width:150px;">

                              <input type="hidden" name="courseId" value='.$this->course_id.'>

                              <button type="submit" class="input-group-text text-light" style="background-color:#1d007e;">
                                Edit  <i class="fa fa-edit"></i>
                              </button>

                            </div>

                        </form>

                        <br>

                        <p>'. $this->course_title.'</p>

                        <form action="edit-course-title-controller.php" method="POST">

                           <div class="input-group">

                              <input class="form-control" type="text" name="courseTitle" placeholder="Edit course title" 
                              required style="height:40px;width:150px;">

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
                                    About course
                                </button>
                            </p>

                            <div id="aboutClass" class="accordion-collapse collapse" aria-labelledby="headingTwo">

                            <div class="accordion-body">
                                    '.$this->about_course.'
                            </div>

                            <!-- accordion body -->

                            </div>
                            

                        </div>
                        <!-- about accordion -->

                        <br>

                        <form action="edit-about-course-controller.php" method="POST">

                           <div class="input-group">

                              <textarea class="form-control" name="aboutCourse" placeholder="Edit about course"></textarea>

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
        </div>
        <!-- edit course info modal -->

        <div class="modal fade" id="clearEnrolledStudentsModal" data-bs-backdrop="static" 
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" 
        style="display: none;" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                        Clear enrolled students <i class="fa fa-users"></i>
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div align="center">

                            <div class="alert alert-warning">

                                By clearing your course enrolled students, <br>you are completely deleting the data of the students
                                enrolled in your course

                            </div>

                            <form action="clear-enrolled-students-controller.php" method="POST">

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
        <!-- clear enrolled students modal -->


        <div class="modal fade" id="deleteCourseModal" data-bs-backdrop="static" 
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" 
        style="display: none;" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                        Delete course <i class="fa fa-mortar-board"></i>
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div align="center">

                            <div class="alert alert-warning">

                                Make sure you have deleted all the classes in this course before deleting, to avert errors in
                                the system.<br>
                                And by deleting this course, you are completely deleting all the course data!!!

                            </div>

                            <form action="delete-course-controller.php" method="POST">

                                <input type="hidden" name="courseId" value='.$this->course_id.'>

                                <input type="hidden" name="incrementId" value='.$this->course_increment_id.'>

                                <button type="submit" value="send" class="btn btn-danger">

                                    Delete course <i class="fa fa-trash"></i>

                                </button>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        <!-- delete course modal -->

        
        ';

        echo '
        
        <div class="modal fade" id="editCourseOutlineModal" data-bs-backdrop="static" 
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
                                    Course outline
                                </button>
                            </p>

                            <div id="aboutClass" class="accordion-collapse collapse" aria-labelledby="headingTwo">
        
        ';

        $read_course_outline_file = file($this->course_outline);

        foreach($read_course_outline_file as $Course_Outline){

            echo '
        
                <div class="accordion-body">
                    '.$Course_Outline.'
                </div>
                <!-- accordion body -->

            ';

        }

        echo '

                            </div>
            

                        </div>
                        <!-- about accordion -->
        
                        <form action="edit-course-outline.php" method="POST">

                            <br>

                            <div class="input-group">

                              <textarea class="form-control" rows="6" name="courseOutline" placeholder="Edit course outline"></textarea>

                              <input type="hidden" name="courseId" value='.$this->course_id.'>

                              <input type="hidden" name="courseOutlineFile" value='.$this->course_outline.'>

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

    //cache the course settings modal
    public function cacheCourseSettingsModal(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "course_settings_modal";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->course_settings_modal)->expiresAfter(1);//in seconds, also accepts Datetime
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

$course_settings_controller = new courseSettings();

$course_settings_controller->fetchCourseId();

$course_settings_controller->fetchCourseData();

$course_settings_controller->cachedCourseQuery();

$course_settings_controller->defineCourseData();

if(isset($_SESSION['lecturer_session'])){

    $course_settings_controller->header();

    $course_settings_controller->courseSettingsSubHeading();

    $course_settings_controller->cacheClassSubHeading();

    $course_settings_controller->displayCourseSettings();

    if(!empty($_GET['courseCodeAlert'])){

       $course_settings_controller->editCourseCodeAlert();

    }elseif(!empty($_GET['courseTitleAlert'])){

       $course_settings_controller->editCourseTitleAlert();

    }elseif(!empty($_GET['aboutCourseAlert'])){

       $course_settings_controller->editAboutCourseAlert();

    }elseif(!empty($_GET['clearCourseEnrolledStudentsAlert'])){

       $course_settings_controller->clearEnrolledStudentsAlert();

    }elseif(!empty($_GET['courseOutlineAlert'])){

        $course_settings_controller->editCourseOutlineAlert();

    }

    $course_settings_controller->cacheCourseSettings();

    $course_settings_controller->courseSettingModal();

    $course_settings_controller->cacheCourseSettingsModal();

}else{

    header('location:logout-success.php');

}

?>