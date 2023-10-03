<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-test/students-test-initializer.php

** About : this module displays the test dashboard and initializes a redirect link to start the test as a student

*/ 
 
/**PSUEDO ALGORITHM
 * *
 * define the student test initializer class
 * include the page header
 * fetch student session
 * fetch the class encrypted id and test encrypted id
 * fetch some of the test data
 * cache the test data query
 * define the fetched test data
 * display the test dashboad
 * cache the dashboad
 * fetch the student test result
 * display the student test result modal
 * cache the modal
 * 
 * *
*/

session_start();

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the student test initializer class
class studentTestInitializer{

    public $student_session;

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


    public $student_test_result;

    public $student_test_result_query_status;

    public $cached_student_test_result;

    public $student_result;


    public $student_test_result_modal;

    //include the page header
    public function header(){

        include('../header/header.php');

    }

    //fetch student session
    public function fetchStudentSession(){

        $this->student_session = $_SESSION['student_session'];

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
     
        $key = "student_class_test_result";
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

                <a class="nav-link" href="run-student-test.php?testEncryptedId='.$this->test_encrypted_id.'&&classEncryptedId='.$this->class_encrypted_id.'&&mins='.$this->encoded_minutes.'&&secs='.$this->encoded_seconds.'&&status='.$this->encoded_status.'&&total='.$this->total_no_questions.'">

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
        
        $key = "cached_student_test_dashboard";
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

    //fetch the student test result
    public function fetchStudentTestResult(){

        include('../../resources/database/class-test-result-db-connection.php');

        $student_test_result_query = "SELECT final_result FROM students_of_class_test_".$this->test_encrypted_id." WHERE student_encrypted_id = '$this->student_session'";

        $this->student_test_result = $conn20->query($student_test_result_query);

        if($this->student_test_result->num_rows > 0){

            $this->student_test_result_query_status = "true";

        }else{

            $this->student_test_result_query_status = "false";

        }

        mysqli_close($conn20);

    }

    //cache the student test result query
    public function cacheStudentTestResultQuery(){

        if($this->student_test_result_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "cache_student_test_result";
            $Cached_student_test_result = $InstanceCache->getItem($key);
    
            if (!$Cached_student_test_result->isHit()) {
                $Cached_student_test_result->set($this->student_test_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_student_test_result); // Save the cache item just like you do with doctrine and entities
         
                $this->cached_student_test_result = $Cached_student_test_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
             
            }else{
             
                $this->cached_student_test_result = $Cached_student_test_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }

        }

    }

    public function defineStudentTestResultData(){

        if($this->student_test_result_query_status == "true"){

            $student_test_result_row = $this->cached_student_test_result->fetch_assoc();

            $this->student_result = $student_test_result_row['final_result'];

        }

    }

    //display the student test result modal
    public function studentTestResultModal(){

        if($this->student_test_result_query_status == "true"){

            $this->student_test_result_modal = '
        
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

                            <p class="lead">

                                Your test score is '.$this->student_result.'
                            
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        
        ';

        }else{

            $this->student_test_result_modal = '
        
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

                            <p class="lead">

                                You have not participated in the test<br>
                                cancel this pop up and run the test
                            
                            </p>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        
        ';

        }

    }

    //cache the modal
    public function cacheStduentTestModal(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "cached_student_test_result";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->student_test_result_modal)->expiresAfter(1);//in seconds, also accepts Datetime
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

$student_test_initializer = new studentTestInitializer();

if(isset($_SESSION['student_session'])){

    if(isset($_GET['classEncryptedId']) && $_GET['classEncryptedId'] !== "" && isset($_GET['testEncryptedId']) && 
    $_GET['testEncryptedId'] !== "" ){

        $student_test_initializer->header();

        $student_test_initializer->fetchStudentSession();

        $student_test_initializer->fetchClassEncryptedIdAndTestEncryptedId();

        $student_test_initializer->fetchClassTestData();

        $student_test_initializer->cacheClassTestData();

        $student_test_initializer->defineClassTestData();

        $student_test_initializer->displayTestDashboard();

        $student_test_initializer->cacheTestDashboard();

        $student_test_initializer->fetchStudentTestResult();

        $student_test_initializer->cacheStudentTestResultQuery();

        $student_test_initializer->defineStudentTestResultData();

        $student_test_initializer->studentTestResultModal();

        $student_test_initializer->cacheStduentTestModal();

    }

}

?>