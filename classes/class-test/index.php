<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-test/index.php

** About : this module displays the index view of the class test

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the class test class
 * fetch the user session 
 * fetch the class and course id
 * include the page heading
 * display and cache the page subheading
 * fetch the class tests
 * cache the class test query
 * define the class test query data
 * display the cached class test
 * display create test modal
 * 
 * *
*/

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

session_start();

//define the class test class
class classTest{

    public $lecturer_session;

    public $student_session;


    public $class_id;

    public $course_id;


    public $subheading;

    
    public $class_test_result;

    public $class_test_result_status;

    public $cached_class_test_result;

    public $test_encrypted_id;

    //fetch the user session 
    public function userSesssion(){

        if(isset($_SESSION['lecturer_session'])){

            $this->lecturer_session = $_SESSION['lecturer_session'];

        }elseif(isset($_SESSION['student_session'])){

            $this->student_session = $_SESSION['student_session'];

        }

    }

    //fetch the class and course id
    public function fetchClassAndCourseId(){

        $this->class_id = $_GET['classId'];

        $this->course_id = $_GET['courseId'];

    }

    //include the page heading
    public function header(){

        include('../header/header.php');

    }

    //display and cache the page subheading
    public function displaySubheading(){

        $this->subheading = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

            <br>

            <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
   
               Class Test
   
            </h1>

            <br>

       </div>

       <br>

        <ul class="nav justify-content-center">

            <li class="nav-item">

                <a class="nav-link text-white" href="../class-dashboard/index.php?id='.$this->class_id.'&&course_id='.$this->course_id.'" 
                style="text-decoration:none;">

                    <button class="btn btn-md text-white" style="background-color:#1d007e;">
 
                        <i class="fa fa-arrow-left"></i> Back to class

                    </button>

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link text-white" style="text-decoration:none;">

                    <button class="btn btn-md text-white" style="background-color:#1d007e;" data-bs-toggle="modal" 
                    data-bs-target="#createTestModal">
 
                        Create test <i class="fa fa-plus-circle"></i> 

                    </button>

                </a>

            </li>

        </ul>

        <br>
       
        ';

    }

    public function cacheSubheading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "class_test_subheading";
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

    //fetch the class tests
    public function fetchClassTest(){

        // class test db connection
        include('../../resources/database/class-test-db-connection.php');

        $fetch_class_test = "SELECT * FROM test_of_class_".$this->class_id."";

        $this->class_test_result = $conn19->query($fetch_class_test);

        if($this->class_test_result->num_rows > 0 ){

            $this->class_test_result_status = "set";

        }else{

            $this->class_test_result_status = "not-set";

        }

        mysqli_close($conn19);

    }

    //cache the class test query
    public function cacheClassTestQuery(){

        if($this->class_test_result_status == "set"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "class_test_result";
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

    }

    //define the class test query data
    public function defineClassTestData(){

        echo '
        
        <div class="container">

            <h3>Tests</h3>

            <hr>

        ';

        if($this->class_test_result_status == "set"){

            echo '<div class="row">';

            while($test_row = $this->cached_class_test_result->fetch_assoc()){

                $test_rows[] = $test_row;

            }

            foreach($test_rows as $each_test_row){

                $test_title = $each_test_row['test_title'];

                $total_no_questions = $each_test_row['total_no_questions'];

                $minutes_limit = $each_test_row['minutes_limit'];

                $seconds_limit = $each_test_row['seconds_limit'];

                $this->test_encrypted_id = $each_test_row['test_encrypted_id'];

                $date_created = $each_test_row['date_created'];

                $time_created = $each_test_row['time_created'];

                $status = $each_test_row['status'];


                echo '
                
                <div class="col">

                    <div class="d-flex shadow p-4 mb-4 bg-light" style="width:300px">

                        <div class="flex-shrink-0">
                            <i class="fa fa-mortar-board" style="font-size:30px;"></i>
                        </div>

                        <div class="flex-grow-1 ms-3">

                            <h5 style="max-width:200px;white-space:nowrap;overflow:hidden;
                                text-overflow:ellipsis;">'.$test_title.'</h5>

                            <p class="text-muted">
                                Total of '.$total_no_questions.' questions<br>
                                To be answered under '.$minutes_limit.'-minutes and '.$seconds_limit.'-seconds

                            </p>

                            <div class="row">

                                <div class="col">

                                    <button class="btn btn-outline-dark">

                                        '.$date_created.'
                            
                                    </button>

                                </div> 
                                <!-- time column -->

                                <div class="col">

                                    <button class="btn btn-outline-dark">

                                        '.$time_created.'

                                     </button>

                                </div>
                                <!-- date column -->

                            </div>
                            <!-- date and time row -->

                            <br>

                            <a href="test-run-redirect.php?classEncryptedId='.$this->class_id.'&&testEncryptedId='.$this->test_encrypted_id.'" style="text-decoration:none;">

                                <button class="btn btn-md text-light" style="background-color:#1d007e;">
                                    Run test <i class="fa fa-external-link"></i>
                                </button>

                            </a>

                        </div>

                        <br>

                    </div>
                    <!-- course container flex display -->

                </div>
                
                ';

            }

        }else{

            echo '<div align="center">
             
            <div class="alert alert-warning" style="width:250px;">

              <p class="lead">No class tests To display</p>

            </div>

        </div>';

        }

    }

    //display create test modal
    public function createTestModal(){

        echo '
        
        <div class="modal fade" id="createTestModal" tabindex="-1" 
        aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role-document>

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                            <i class="fa fa-plus-circle"></i> Create test
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div>

                            <form action="create-class-test.php" method="POST">

                                <input class="form-control" type="text" name="testTitle" placeholder="Test title e.g first test" required
                                style="height:40px;">

                                <br>

                                <select class="form-control" name="noOfQuestion">

                                    <option>Number of questions</option>
                                    <option>10 questions</option>
                                    <option>20 questions</option>
                                    <option>30 questions</option>

                                </select>

                                <br>

                                <div class="row g-3">

                                    <div class="col-md-6">

                                        <label class="text-muted" for="timeLimit">Test minutes limit</label>
                                        <input id="timeLimit" class="form-control" type="number" name="minutesLimit" placeholder="Test minutes e.g 45(minutes)" required
                                        style="height:40px;">


                                    </div>

                                    <div class="col-md-6">

                                        <label class="text-muted" for="secondsimit">Test seconds limit</label>
                                        <input id="secondsLimit" class="form-control" type="number" name="secondsLimit" placeholder="Test seconds e.g 45(minutes)" required
                                        style="height:40px;">

                                        <br>

                                    </div>

                                    <input type="hidden" name="classEncryptedId" value='.$this->class_id.'>

                                </div>

                                <div align="center">

                                    <button type="submit" class="btn btn-md text-light" style="background-color:#1d007e;">
                                        Create <i class="fa fa-plus-circle"></i>
                                    </button>

                                </div>


                            </form>

                        </div>

                    </div>

                </div>

            </div>

        </div>
        
        ';

    }

}

$class_test = new classTest();

if(isset($_SESSION['student_session']) or isset($_SESSION['lecturer_session'])){

    $class_test->userSesssion();

    $class_test->fetchClassAndCourseId();

    $class_test->header();

    $class_test->displaySubheading();

    $class_test->cacheSubheading();

    $class_test->fetchClassTest();

    $class_test->cacheClassTestQuery();

    $class_test->defineClassTestData();

    if(isset($_SESSION['lecturer_session'])){

        $class_test->createTestModal();

    }

}else{

    header("location:logout-success.php");

}

?>