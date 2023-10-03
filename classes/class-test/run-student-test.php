<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-test/run-student-test.php

** About : this module displays the test questions to be answered in student mode

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the run student test class
 * fetch the test required GET data
 * display the header
 * display the test sub heading
 * cache the test sub heading
 * display the test not active alert
 * fetch the test questions based on the amount of questions created
 * 
 * *
*/

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

session_start();

//define the run student test class
class runStudentTest{

    public $test_encrypted_id;

    public $class_encrypted_id;

    public $test_minutes;

    public $test_seconds;

    public $total;


    public $test_subheading;


    public $conn;

    //fetch the test required GET data
    public function fetchTestData(){

        $this->test_encrypted_id = $_GET['testEncryptedId'];

        $this->class_encrypted_id = $_GET['classEncryptedId'];

        $this->test_minutes = base64_decode($_GET['mins']);

        $this->test_seconds = base64_decode($_GET['secs']);

        $this->status = base64_decode($_GET['status']);

        $this->total = $_GET['total'];

    }

    //display the header
    public function header(){

        include('../header/header.php');

    }

    //display the test sub heading
    public function testSubheading(){

        $this->test_subheading = '

        <style>

        .fixedTime{
            position: fixed;
            bottom: 0px;
            right: 0px; 
            padding: 20px;
        }
        

        </style>
        
        <div class="text-light" style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

            <br>

                <h1 class="timer" data-minutes-left='.$this->test_minutes.'></h1>

                <button class="btn btn-md" style="background-color:#FFFFFF;">

                    Time remaining

                </button>

            <br>

       </div>

        <div class="fixedTime">
            <div class="shadow p-4 mb-4 text-light" style="background-color:#1d007e;">
                <span><i class="fa fa-clock-o"></i></span>
                <span class="timer" data-minutes-left='.$this->test_minutes.'></span>
            </div>
        </div>

       <br>

       <ul class="nav justify-content-center">

            <li class="nav-item">

                <a class="nav-link" href="student-test-initializer.php?classEncryptedId='.$this->class_encrypted_id.'&&testEncryptedId='.$this->test_encrypted_id.'">

                    <button class="btn btn-md text-light" style="background-color:#1d007e;">

                        <i class="fa fa-remove"></i> Cancel test

                    </button>

                </a>

            </li>

       </ul>

       <div class="container">
    
            <h3>Questions</h3>
    
            <hr>

       <script>

       $(document).ready(function() {

            $(".timer").startTimer({
                onComplete:function(element){

                        window.location.href = "student-test-initializer.php?classEncryptedId='.$this->class_encrypted_id.'&&testEncryptedId='.$this->test_encrypted_id.'";

                }

            });

       });

       </script>

        ';

    }

    //cache the test sub heading
    public function cacheTestSubheading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "student_test_subheading";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->test_subheading)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    //display the test not active alert
    public function displayTestNotActive(){

        echo '

        <br>

        <div align="center">

            <div class="alert alert-warning" style="width:200px;">

                <p>Sorry this test has been de-activated</p>

            </div>

        </div>
        
        ';

    }

    public function beginForm(){

        echo '
        
        <form action = "process-student-test.php" method="post">
        
        ';

    }

    public function questionsDbConn(){

        include('../../resources/database/class-test-questions-db-connections.php');

        $this->conn = $conn21;

    }

    //fetch the test questions based on the amount of questions created
    public function fetchQuestionOne(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=1";

        $question1_query_result = $this->conn->query($test_questions_query);

        if($question1_query_result->num_rows > 0){

            $question1_query_status = "true";

        }else{

            $question1_query_status = "false";

        }

        if($question1_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_one_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question1_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question1_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question1_row['id'];

            $question_title = $question1_row['question_title'];

            $question_encrypted_id = $question1_row['question_encrypted_id'];

            $optionA = $question1_row['optionA'];

            $optionB = $question1_row['optionB'];

            $optionC = $question1_row['optionC'];

            $optionD = $question1_row['optionD'];

            $correct_option = $question1_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_one = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ1">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq1" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q1Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_one";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_one)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionTwo(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=2";

        $question2_query_result = $this->conn->query($test_questions_query);

        if($question2_query_result->num_rows > 0){

            $question2_query_status = "true";

        }else{

            $question2_query_status = "false";

        }

        if($question2_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_two_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question2_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question2_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question2_row['id'];

            $question_title = $question2_row['question_title'];

            $question_encrypted_id = $question2_row['question_encrypted_id'];

            $optionA = $question2_row['optionA'];

            $optionB = $question2_row['optionB'];

            $optionC = $question2_row['optionC'];

            $optionD = $question2_row['optionD'];

            $correct_option = $question2_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_two = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ2">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq2" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q2Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_two";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_two)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionThree(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=3";

        $question3_query_result = $this->conn->query($test_questions_query);

        if($question3_query_result->num_rows > 0){

            $question3_query_status = "true";

        }else{

            $question3_query_status = "false";

        }

        if($question3_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_three_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question3_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question3_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question3_row['id'];

            $question_title = $question3_row['question_title'];

            $question_encrypted_id = $question3_row['question_encrypted_id'];

            $optionA = $question3_row['optionA'];

            $optionB = $question3_row['optionB'];

            $optionC = $question3_row['optionC'];

            $optionD = $question3_row['optionD'];

            $correct_option = $question3_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_three = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ3">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq3" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q3Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_three";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_three)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionFour(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=4";

        $question4_query_result = $this->conn->query($test_questions_query);

        if($question4_query_result->num_rows > 0){

            $question4_query_status = "true";

        }else{

            $question4_query_status = "false";

        }

        if($question4_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_four_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question4_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question4_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question4_row['id'];

            $question_title = $question4_row['question_title'];

            $question_encrypted_id = $question4_row['question_encrypted_id'];

            $optionA = $question4_row['optionA'];

            $optionB = $question4_row['optionB'];

            $optionC = $question4_row['optionC'];

            $optionD = $question4_row['optionD'];

            $correct_option = $question4_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_four = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ4">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq4" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q4Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_four";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_four)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionFive(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=5";

        $question5_query_result = $this->conn->query($test_questions_query);

        if($question5_query_result->num_rows > 0){

            $question5_query_status = "true";

        }else{

            $question5_query_status = "false";

        }

        if($question5_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_five_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question5_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question5_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question5_row['id'];

            $question_title = $question5_row['question_title'];

            $question_encrypted_id = $question5_row['question_encrypted_id'];

            $optionA = $question5_row['optionA'];

            $optionB = $question5_row['optionB'];

            $optionC = $question5_row['optionC'];

            $optionD = $question5_row['optionD'];

            $correct_option = $question5_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_five = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ5">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq5" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q5Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_five";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_five)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionSix(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=6";

        $question6_query_result = $this->conn->query($test_questions_query);

        if($question6_query_result->num_rows > 0){

            $question6_query_status = "true";

        }else{

            $question6_query_status = "false";

        }

        if($question6_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_six_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question6_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question6_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question6_row['id'];

            $question_title = $question6_row['question_title'];

            $question_encrypted_id = $question6_row['question_encrypted_id'];

            $optionA = $question6_row['optionA'];

            $optionB = $question6_row['optionB'];

            $optionC = $question6_row['optionC'];

            $optionD = $question6_row['optionD'];

            $correct_option = $question6_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_six = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ6">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq6" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q6Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_six";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_six)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionSeven(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=7";

        $question7_query_result = $this->conn->query($test_questions_query);

        if($question7_query_result->num_rows > 0){

            $question7_query_status = "true";

        }else{

            $question7_query_status = "false";

        }

        if($question7_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_seven_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question7_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question7_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question7_row['id'];

            $question_title = $question7_row['question_title'];

            $question_encrypted_id = $question7_row['question_encrypted_id'];

            $optionA = $question7_row['optionA'];

            $optionB = $question7_row['optionB'];

            $optionC = $question7_row['optionC'];

            $optionD = $question7_row['optionD'];

            $correct_option = $question7_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_seven = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ7">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq7" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q7Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_seven";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_seven)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionEight(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=8";

        $question8_query_result = $this->conn->query($test_questions_query);

        if($question8_query_result->num_rows > 0){

            $question8_query_status = "true";

        }else{

            $question8_query_status = "false";

        }

        if($question8_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_eight_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question8_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question8_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question8_row['id'];

            $question_title = $question8_row['question_title'];

            $question_encrypted_id = $question8_row['question_encrypted_id'];

            $optionA = $question8_row['optionA'];

            $optionB = $question8_row['optionB'];

            $optionC = $question8_row['optionC'];

            $optionD = $question8_row['optionD'];

            $correct_option = $question8_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_eight = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ8">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq8" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q8Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_eight";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_eight)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionNine(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=9";

        $question9_query_result = $this->conn->query($test_questions_query);

        if($question9_query_result->num_rows > 0){

            $question9_query_status = "true";

        }else{

            $question9_query_status = "false";

        }

        if($question9_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_nine_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question9_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question9_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question9_row['id'];

            $question_title = $question9_row['question_title'];

            $question_encrypted_id = $question9_row['question_encrypted_id'];

            $optionA = $question9_row['optionA'];

            $optionB = $question9_row['optionB'];

            $optionC = $question9_row['optionC'];

            $optionD = $question9_row['optionD'];

            $correct_option = $question9_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_nine = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ9">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq9" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q9Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_nine";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_nine)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionTen(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=10";

        $question10_query_result = $this->conn->query($test_questions_query);

        if($question10_query_result->num_rows > 0){

            $question10_query_status = "true";

        }else{

            $question10_query_status = "false";

        }

        if($question10_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_ten_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question10_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question10_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question10_row['id'];

            $question_title = $question10_row['question_title'];

            $question_encrypted_id = $question10_row['question_encrypted_id'];

            $optionA = $question10_row['optionA'];

            $optionB = $question10_row['optionB'];

            $optionC = $question10_row['optionC'];

            $optionD = $question10_row['optionD'];

            $correct_option = $question10_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_ten = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ10">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq10" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q10Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_ten";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_ten)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionEleven(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=11";

        $question11_query_result = $this->conn->query($test_questions_query);

        if($question11_query_result->num_rows > 0){

            $question11_query_status = "true";

        }else{

            $question11_query_status = "false";

        }

        if($question11_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_eleven_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question11_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question11_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question11_row['id'];

            $question_title = $question11_row['question_title'];

            $question_encrypted_id = $question11_row['question_encrypted_id'];

            $optionA = $question11_row['optionA'];

            $optionB = $question11_row['optionB'];

            $optionC = $question11_row['optionC'];

            $optionD = $question11_row['optionD'];

            $correct_option = $question11_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_eleven = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ11">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq11" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q11Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_eleven";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_eleven)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionTwelve(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=12";

        $question12_query_result = $this->conn->query($test_questions_query);

        if($question12_query_result->num_rows > 0){

            $question12_query_status = "true";

        }else{

            $question12_query_status = "false";

        }

        if($question12_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_twelve_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question12_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question12_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question12_row['id'];

            $question_title = $question12_row['question_title'];

            $question_encrypted_id = $question12_row['question_encrypted_id'];

            $optionA = $question12_row['optionA'];

            $optionB = $question12_row['optionB'];

            $optionC = $question12_row['optionC'];

            $optionD = $question12_row['optionD'];

            $correct_option = $question12_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_twelve = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ12">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq12" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q12Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_twelve";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_twelve)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionThirteen(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=13";

        $question13_query_result = $this->conn->query($test_questions_query);

        if($question13_query_result->num_rows > 0){

            $question13_query_status = "true";

        }else{

            $question13_query_status = "false";

        }

        if($question13_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_thirteen_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question13_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question13_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question13_row['id'];

            $question_title = $question13_row['question_title'];

            $question_encrypted_id = $question13_row['question_encrypted_id'];

            $optionA = $question13_row['optionA'];

            $optionB = $question13_row['optionB'];

            $optionC = $question13_row['optionC'];

            $optionD = $question13_row['optionD'];

            $correct_option = $question13_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_thirteen = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ13">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq13" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q13Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_thirteen";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_thirteen)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionFourteen(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=14";

        $question14_query_result = $this->conn->query($test_questions_query);

        if($question14_query_result->num_rows > 0){

            $question14_query_status = "true";

        }else{

            $question14_query_status = "false";

        }

        if($question14_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_fourteen_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question14_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question14_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question14_row['id'];

            $question_title = $question14_row['question_title'];

            $question_encrypted_id = $question14_row['question_encrypted_id'];

            $optionA = $question14_row['optionA'];

            $optionB = $question14_row['optionB'];

            $optionC = $question14_row['optionC'];

            $optionD = $question14_row['optionD'];

            $correct_option = $question14_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_fourteen = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ14">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq14" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q14Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_fourteen";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_fourteen)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionFifteen(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=15";

        $question15_query_result = $this->conn->query($test_questions_query);

        if($question15_query_result->num_rows > 0){

            $question15_query_status = "true";

        }else{

            $question15_query_status = "false";

        }

        if($question15_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_fifteen_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question15_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question15_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question15_row['id'];

            $question_title = $question15_row['question_title'];

            $question_encrypted_id = $question15_row['question_encrypted_id'];

            $optionA = $question15_row['optionA'];

            $optionB = $question15_row['optionB'];

            $optionC = $question15_row['optionC'];

            $optionD = $question15_row['optionD'];

            $correct_option = $question15_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_fifteen = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ15">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq15" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q15Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_fifteen";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_fifteen)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionSixteen(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=16";

        $question16_query_result = $this->conn->query($test_questions_query);

        if($question16_query_result->num_rows > 0){

            $question16_query_status = "true";

        }else{

            $question16_query_status = "false";

        }

        if($question16_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_sixteen_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question16_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question16_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question16_row['id'];

            $question_title = $question16_row['question_title'];

            $question_encrypted_id = $question16_row['question_encrypted_id'];

            $optionA = $question16_row['optionA'];

            $optionB = $question16_row['optionB'];

            $optionC = $question16_row['optionC'];

            $optionD = $question16_row['optionD'];

            $correct_option = $question16_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_sixteen = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ16">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq16" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q16Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_sixteen";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_sixteen)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionSeventeen(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=17";

        $question17_query_result = $this->conn->query($test_questions_query);

        if($question17_query_result->num_rows > 0){

            $question17_query_status = "true";

        }else{

            $question17_query_status = "false";

        }

        if($question17_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_seventeen_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question17_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question17_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question17_row['id'];

            $question_title = $question17_row['question_title'];

            $question_encrypted_id = $question17_row['question_encrypted_id'];

            $optionA = $question17_row['optionA'];

            $optionB = $question17_row['optionB'];

            $optionC = $question17_row['optionC'];

            $optionD = $question17_row['optionD'];

            $correct_option = $question17_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_seventeen = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ17">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq17" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q17Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_seventeen";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_seventeen)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionEighteen(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=18";

        $question18_query_result = $this->conn->query($test_questions_query);

        if($question18_query_result->num_rows > 0){

            $question18_query_status = "true";

        }else{

            $question18_query_status = "false";

        }

        if($question18_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_eighteen_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question18_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question18_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question18_row['id'];

            $question_title = $question18_row['question_title'];

            $question_encrypted_id = $question18_row['question_encrypted_id'];

            $optionA = $question18_row['optionA'];

            $optionB = $question18_row['optionB'];

            $optionC = $question18_row['optionC'];

            $optionD = $question18_row['optionD'];

            $correct_option = $question18_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_eighteen = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ18">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq18" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q18Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "question_eighteen";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_eighteen)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionNineteen(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=19";

        $question19_query_result = $this->conn->query($test_questions_query);

        if($question19_query_result->num_rows > 0){

            $question19_query_status = "true";

        }else{

            $question19_query_status = "false";

        }

        if($question19_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_nineteen_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question19_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question19_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question19_row['id'];

            $question_title = $question19_row['question_title'];

            $question_encrypted_id = $question19_row['question_encrypted_id'];

            $optionA = $question19_row['optionA'];

            $optionB = $question19_row['optionB'];

            $optionC = $question19_row['optionC'];

            $optionD = $question19_row['optionD'];

            $correct_option = $question19_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_nineteen = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ19">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq19" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q19Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
    
            $key = "question_nineteen";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_nineteen)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function fetchQuestionTwenty(){

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id." WHERE id=20";

        $question20_query_result = $this->conn->query($test_questions_query);

        if($question20_query_result->num_rows > 0){

            $question20_query_status = "true";

        }else{

            $question20_query_status = "false";

        }

        if($question20_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "question_twenty_query";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($question20_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }
            
            $question20_row = $cached_test_questions_result->fetch_assoc();

            $question_id = $question20_row['id'];

            $question_title = $question20_row['question_title'];

            $question_encrypted_id = $question20_row['question_encrypted_id'];

            $optionA = $question20_row['optionA'];

            $optionB = $question20_row['optionB'];

            $optionC = $question20_row['optionC'];

            $optionD = $question20_row['optionD'];

            $correct_option = $question20_row['correct_option'];

            $encoded_correct_option = base64_encode($correct_option);

            $test_question_twenty = '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                        </h5>

                        <hr>

                        <p class="text-muted">
                        
                            '.$question_title.' 
                        
                        </p>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option A</label><br>
            
                                <label class="form-check-label" for="exampleRadio1">'.$optionA.'</label> 

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option B</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionB.'</label>

                            </div>

                        </div>

                        <br>

                        <div class="row g-3">

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option C</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionC.'</label>

                            </div>

                            <div class="col-md-6">

                                <label class="text-muted" for="exampleRadio1">Option D</label><br>
                                <label class="form-check-label" for="exampleRadio1">'.$optionD.'</label>

                            </div>

                        </div>

                        <br>

                            <select class="form-control" name="ansForQ20">
                                <option>Your answer</option>
                                <option>optionA</option>
                                <option>optionB</option>
                                <option>optionC</option>
                                <option>optionD</option>
                            </select>

                            <input type="hidden" name="correctAnsforq20" value='.$encoded_correct_option.'>

                            <input type="hidden" name="q20Id" value='.$question_id.'>

                            <br>

                    </div>

                </div>

            ';

            //cache question one
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
    
            $key = "question_twenty";
            $Cached_page = $InstanceCache->getItem($key);
            
            if (!$Cached_page->isHit()) {
                $Cached_page->set($test_question_twenty)->expiresAfter(1);//in seconds, also accepts Datetime
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

    public function endForm(){


        echo '

            <button type="submit" class="btn btn-md text-light" style="background-color:#1d007e;">

                Submit <i class="fa fa-external-link"></i>

            </button>
        
        
        </form>
        
        <br>
        
        ';

    }

}

$run_student_test = new runStudentTest();

if(isset($_SESSION['student_session'])){

    if(isset($_GET['testEncryptedId']) && $_GET['testEncryptedId'] !== "" && isset($_GET['classEncryptedId']) && 
    $_GET['classEncryptedId'] !== "" && isset($_GET['mins']) && $_GET['mins'] !== "" && $_GET['secs'] !== "" && isset($_GET['total']) && $_GET['total'] !== ""){

        $run_student_test->fetchTestData();

        $run_student_test->header();

        if($run_student_test->status == "active"){


            $run_student_test->testSubheading();

            $run_student_test->cacheTestSubheading();

            $run_student_test->beginForm();

            if($run_student_test->total == 10){

                $run_student_test->questionsDbConn();

                $run_student_test->fetchQuestionOne();

                $run_student_test->fetchQuestionTwo();

                $run_student_test->fetchQuestionThree();

                $run_student_test->fetchQuestionFour();

                $run_student_test->fetchQuestionFive();

                $run_student_test->fetchQuestionSix();

                $run_student_test->fetchQuestionSeven();

                $run_student_test->fetchQuestionEight();

                $run_student_test->fetchQuestionNine();

                $run_student_test->fetchQuestionTen();

            }elseif($run_student_test->total == 20){

                $run_student_test->questionsDbConn();

                $run_student_test->fetchQuestionOne();

                $run_student_test->fetchQuestionTwo();

                $run_student_test->fetchQuestionThree();

                $run_student_test->fetchQuestionFour();

                $run_student_test->fetchQuestionFive();

                $run_student_test->fetchQuestionSix();

                $run_student_test->fetchQuestionSeven();

                $run_student_test->fetchQuestionEight();

                $run_student_test->fetchQuestionNine();

                $run_student_test->fetchQuestionTen();

                $run_student_test->fetchQuestionEleven();

                $run_student_test->fetchQuestionTwelve();

                $run_student_test->fetchQuestionThirteen();

                $run_student_test->fetchQuestionFourteen();

                $run_student_test->fetchQuestionFifteen();

                $run_student_test->fetchQuestionSixteen();

                $run_student_test->fetchQuestionSeventeen();

                $run_student_test->fetchQuestionEighteen();

                $run_student_test->fetchQuestionNineteen();

                $run_student_test->fetchQuestionTwenty();

            }

            $run_student_test->endForm();

            //session_regenerate_id(true);

            $_SESSION['student-test-encrypted-id'] = $run_student_test->test_encrypted_id;

            $_SESSION['student-class-encrypted-id'] = $run_student_test->class_encrypted_id;

        }else{

            $run_student_test->displayTestNotActive();

        }

    }

}else{

    header("location:logout-success.php");

}

?>