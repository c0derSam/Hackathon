<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-test/lecturer-test-initializer.php

** About : this module displays the test dashboard and initializes a redirect link to start the test as a lecturer

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the lecturer test initializer class
 * include the page header
 * fetch lecturer session
 * fetch the class encrypted id and test encrypted id
 * fetch some of the test data
 * cache the test data query
 * define the fetched test data
 * fetch the total amounts of students that have run the tests
 * display the test dashboad
 * cache the dashboad
 * display the test settings modal
 * cache the modal
 * fetch the lecturer test result
 * display the lecturer test result modal
 * cache the modal
 * 
 * *
*/

session_start();

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the lecturer test initializer class
class lecturerTestInitializer{

    public $lecturer_session;

    public $class_encrypted_id;

    public $test_encrypted_id;


    public $class_test_result;

    public $cached_class_test_result;

    public $test_title;

    public $total_no_questions;

    public $minutes_limit;

    public $seconds_limit;

    public $encoded_minutes;

    public $encoded_seconds;

    public $date_created;

    public $time_created;

    public $status;

    public $encoded_status;


    public $class_test_students_total_result;

    public $cached_class_test_student_result;

    public $class_test_student_total;


    public $test_dashboard;

    public $test_settings_modal;


    public $lecturer_test_result;

    public $lecturer_test_result_query_status;

    public $cached_lecturer_test_result;

    public $lecturer_result;


    public $lecturer_test_result_modal;

    //include the page header
    public function header(){

        include('../header/header.php');

    }
    
    //fetch lecturer session
    public function fetchLecturerSession(){

        $this->lecturer_session = $_SESSION['lecturer_session'];

    }

    //fetch the class encrypted id and test encrypted id
    public function fetchClassEncryptedIdAndTestEncryptedId(){

        $this->class_encrypted_id = $_GET['classEncryptedId'];

        $this->test_encrypted_id = $_GET['testEncryptedId'];

    }

    //fetch some of the test data
    public function fetchClassTestData(){

        include('../../resources/database/class-test-db-connection.php');

        $fetch_class_test = "SELECT * FROM test_of_class_".$this->class_encrypted_id." 
        WHERE test_encrypted_id = '$this->test_encrypted_id'";

        $this->class_test_result = $conn19->query($fetch_class_test);

        mysqli_close($conn19);

    }

    //cache the test data query
    public function cacheClassTestData(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
     
        $InstanceCache = CacheManager::getInstance('files');
     
        $key = "lecturer_class_test_result";
        $Cached_class_test_result = $InstanceCache->getItem($key);

        if (!$Cached_class_test_result->isHit()) {
            $Cached_class_test_result->set($this->class_test_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_class_test_result); // Save the cache item just like you do with doctrine and entities
     
            $this->cached_class_test_result = $Cached_class_test_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
     
        }else{
         
            $this->cached_class_test_result = $Cached_class_test_result->get();
            //echo 'READ FROM CACHE // ';
     
            $InstanceCache->deleteItem($key);
        }


    }

    //define the fetched test data
    public function defineClassTestData(){

        $class_test_row = $this->cached_class_test_result->fetch_assoc();

        $this->test_title = $class_test_row['test_title'];

        $this->total_no_questions = $class_test_row['total_no_questions'];

        $this->minutes_limit = $class_test_row['minutes_limit'];

        $this->seconds_limit = $class_test_row['seconds_limit'];

        $this->encoded_minutes = base64_encode($this->minutes_limit);

        $this->encoded_seconds = base64_encode($this->seconds_limit);

        $this->date_created = $class_test_row['date_created'];

        $this->time_created = $class_test_row['time_created'];

        $this->status = $class_test_row['status'];

        $this->encoded_status = base64_encode($this->status);

    }

    //fetch the total amounts of students that have run the tests
    public function fetchTotalOfClassTestStudents(){

        include('../../resources/database/class-test-result-db-connection.php'); //conn20

        $class_test_students_toatl_query = "SELECT count(*) AS testStudents FROM 
        students_of_class_test_".$this->test_encrypted_id."";

        $this->class_test_students_total_result = $conn20->query($class_test_students_toatl_query);

        mysqli_close($conn20);

    }

    public function cacheTotalOfClassTestStudents(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
     
        $InstanceCache = CacheManager::getInstance('files');
     
        $key = "class_test_students_result";
        $Cached_class_test_student_result = $InstanceCache->getItem($key);

        if (!$Cached_class_test_student_result->isHit()) {
            $Cached_class_test_student_result->set($this->class_test_students_total_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_class_test_student_result); // Save the cache item just like you do with doctrine and entities
     
            $this->cached_class_test_student_result = $Cached_class_test_student_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
     
        }else{
         
            $this->cached_class_test_student_result = $Cached_class_test_student_result->get();
            //echo 'READ FROM CACHE // ';
     
            $InstanceCache->deleteItem($key);
        }

    }

    public function defineClassTestStudentTotal(){

        $test_students_total_row = $this->cached_class_test_student_result->fetch_assoc();

        $this->class_test_student_total = $test_students_total_row['testStudents'];

    }

    //fetch the lecturer test result

    //display the test dashboad
    public function displayTestDashboard(){

        $this->test_dashboard = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

            <br>

            <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
   
               '.$this->test_title.'
   
            </h1>

            <br>

       </div>

       <ul class="nav justify-content-center">

            <li class="nav-item">

                <a class="nav-link" href="test-participated-students.php?testEncryptedId='.$this->test_encrypted_id.'&&classEncryptedId='.$this->class_encrypted_id.'&&testTitle='.$this->test_title.'&&total='.$this->class_test_student_total.'">

                    <button class="btn btn-md text-light" style="background-color:#1d007e;">

                        <i class="fa fa-users"></i> '.$this->class_test_student_total.' student(s)

                    </button>

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link" href="run-lecturer-test.php?testEncryptedId='.$this->test_encrypted_id.'&&classEncryptedId='.$this->class_encrypted_id.'&&mins='.$this->encoded_minutes.'&&secs='.$this->encoded_seconds.'&&status='.$this->encoded_status.'&&total='.$this->total_no_questions.'">

                    <button class="btn btn-md text-light" style="background-color:#1d007e;">

                        <i class="fa fa-external-link"></i> Run test

                    </button>

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link">

                    <button class="btn btn-md text-light" style="background-color:#1d007e;" 
                    data-bs-toggle="modal" data-bs-target="#testResultModal">

                        Result

                    </button>

                </a>

            </li>
 
            <li class="nav-item">

                <a class="nav-link">

                    <button class="btn btn-md text-light" style="background-color:#1d007e;" 
                    data-bs-toggle="modal" data-bs-target="#testSettingsModal">

                        <i class="fa fa-cogs"></i> Settings

                    </button>

                </a>

            </li>

        </ul>

        <ul class="nav justify-content-center">

            <li class="nav-item">

                <a class="nav-link">

                    <button class="btn btn-md text-light" style="background-color:#1d007e;">

                        Status | '.$this->status.'

                    </button>

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link">

                    <button class="btn btn-md text-light" style="background-color:#1d007e;">

                        Date | '.$this->date_created.'

                    </button>

                </a>

            </li>

       </ul>

       <ul class="nav justify-content-center">

            <li class="nav-item">

                <a class="nav-link">

                    <button class="btn btn-md text-light" style="background-color:#1d007e;">

                        <i class="fa fa-clock-o"></i> '.$this->minutes_limit.' min : '.$this->seconds_limit.' sec

                    </button>

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link">

                    <button class="btn btn-md text-light" style="background-color:#1d007e;">

                        '.$this->total_no_questions.' questions

                    </button>

                </a>

            </li>

       </ul>
        
        ';

    }

    //cache the dashboad
    public function cacheTestDashboard(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "cached_test_dashboard";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->test_dashboard)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    //display the test settings modal
    public function displayTestSettingsModal(){

        $this->test_settings_modal = '
        
        <div class="modal fade" id="testSettingsModal" tabindex="-1" 
        aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role-document>

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                            <i class="fa fa-cogs"></i> Test settings
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">
 
                        <ul class="list-group list-group-flush">

                            <li class="list-group-item">

                                <a href="test-questions-form.php?classEncryptedId='.$this->class_encrypted_id.'&&testEncryptedId='.$this->test_encrypted_id.'">

                                    <button class="btn btn-md text-light" style="background-color:#1d007e;">

                                        <i class="fa fa-plus-circle"></i> Create test questions

                                    </button>

                                </a>

                            </li>

                            <li class="list-group-item">

                                <a href="change-test-status.php?classEncryptedId='.$this->class_encrypted_id.'&&testEncryptedId='.$this->test_encrypted_id.'&&currentStatus='.$this->status.'">

                                    <button class="btn btn-md text-light" style="background-color:#1d007e;">

                                        <i class="fa fa-edit"></i> Change test status : '.$this->status.'

                                    </button>

                                </a>
                                
                            </li>

                            <li class="list-group-item">

                                <a href="delete-class-test-students.php?classEncryptedId='.$this->class_encrypted_id.'&&testEncryptedId='.$this->test_encrypted_id.'">

                                    <button class="btn btn-danger text-light">

                                        <i class="fa fa-trash"></i> Delete students that have run the test

                                    </button>

                                </a>
                                
                            </li>

                        </ul>

                    </dv> 

                </div>

            </div>

        </div>
        
        </div>
        
        ';

    }

    //cache the modal
    public function cacheTestSettingsModal(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "cached_test_settings_modal";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->test_settings_modal)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    //fetch the lecturer test result
    public function fetchLecturerTestResult(){

        include('../../resources/database/class-test-result-db-connection.php');

        $lecturer_test_result_query = "SELECT final_result FROM lecturer_of_class_test_".$this->test_encrypted_id." WHERE lecturer_encrypted_id = '$this->lecturer_session'";

        $this->lecturer_test_result = $conn20->query($lecturer_test_result_query);

        if($this->lecturer_test_result->num_rows > 0){

            $this->lecturer_test_result_query_status = "true";

        }else{

            $this->lecturer_test_result_query_status = "false";

        }

        mysqli_close($conn20);

    }

    //cache the lecturer test result query
    public function cacheLectruerTestResultQuery(){

        if($this->lecturer_test_result_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "cache_lecturer_test_result";
            $Cached_lecturer_test_result = $InstanceCache->getItem($key);
    
            if (!$Cached_lecturer_test_result->isHit()) {
                $Cached_lecturer_test_result->set($this->lecturer_test_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_lecturer_test_result); // Save the cache item just like you do with doctrine and entities
         
                $this->cached_lecturer_test_result = $Cached_lecturer_test_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
             
            }else{
             
                $this->cached_lecturer_test_result = $Cached_lecturer_test_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }

        }

    }

    public function defineLecturerTestResultData(){

        if($this->lecturer_test_result_query_status == "true"){

            $lecturer_test_result_row = $this->cached_lecturer_test_result->fetch_assoc();

            $this->lecturer_result = $lecturer_test_result_row['final_result'];

        }

    }

    //display the lecturer test result modal
    public function lecturerTestResultModal(){

        if($this->lecturer_test_result_query_status == "true"){

            $this->lecturer_test_result_modal = '
        
        <div class="modal fade" id="testResultModal" tabindex="-1" 
        aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role-document>

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                            Test result
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div align="center">

                            <small>
             
                                <div class="alert alert-warning" style="width:250px;">
    
                                    <p>
                                        
                                            Delete test score if you want to run your test again in lecturer mode
                                        
                                    </p>
    
                                </div>

                            </small>
    
                        

                            <p class="lead">

                                Your test score is '.$this->lecturer_result.'
                            
                            </p>

                            <a href="delete-test-score.php?testEncryptedId='.$this->test_encrypted_id.'&&classEncryptedId='.$this->class_encrypted_id.'">

                                <button class="btn btn-danger">

                                    <i class="fa fa-trash"></i> Delete test score

                                </button>

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        
        ';

        }else{

            $this->lecturer_test_result_modal = '
        
        <div class="modal fade" id="testResultModal" tabindex="-1" 
        aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role-document>

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                            Test result
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div align="center">

                            <small>
             
                                <div class="alert alert-warning" style="width:250px;">
    
                                    <p>
                                        
                                            No test score available<br> 
                                            Try running your test
                                        
                                    </p>
    
                                </div>

                            </small>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        
        ';

        }


    }

    //cache the modal
    public function cacheLecturerTestModal(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "cached_lecturer_test_modal";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->lecturer_test_result_modal)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    public function deleteTestScoreAlert(){

        echo '
        
        <div align="center">
 
        <div class="alert alert-info" style="width:200px;height:85px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                        Test score deleted
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
             </div>
 
        </div>';

    }

}

$lecturer_test_initializer = new lecturerTestInitializer();

if(isset($_SESSION['lecturer_session'])){

    if(isset($_GET['classEncryptedId']) && $_GET['classEncryptedId'] !== "" && isset($_GET['testEncryptedId']) && 
    $_GET['testEncryptedId'] !== "" ){

        $lecturer_test_initializer->header();

        $lecturer_test_initializer->fetchLecturerSession();

        $lecturer_test_initializer->fetchClassEncryptedIdAndTestEncryptedId();

        $lecturer_test_initializer->fetchClassTestData();

        $lecturer_test_initializer->cacheClassTestData();

        $lecturer_test_initializer->defineClassTestData();

        $lecturer_test_initializer->fetchTotalOfClassTestStudents();

        $lecturer_test_initializer->cacheTotalOfClassTestStudents();

        $lecturer_test_initializer->defineClassTestStudentTotal();

        $lecturer_test_initializer->displayTestDashboard();

        $lecturer_test_initializer->cacheTestDashboard();
        
        $lecturer_test_initializer->displayTestSettingsModal();

        $lecturer_test_initializer->cacheTestSettingsModal();

        $lecturer_test_initializer->fetchLecturerTestResult();

        $lecturer_test_initializer->cacheLectruerTestResultQuery();

        $lecturer_test_initializer->defineLecturerTestResultData();

        $lecturer_test_initializer->lecturerTestResultModal();

        $lecturer_test_initializer->cacheLecturerTestModal();

        if(isset($_GET['alert']) && $_GET['alert'] == "success" ){

            $lecturer_test_initializer->deleteTestScoreAlert();

        }

    }

}else{

    header("location:logout-success.php");

}

?>