<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-test/test-questions-form.php

** About : this module displays the form to create test questions

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the test questions class
 * fetch the class encrypted id and test encrypted id
 * include the page header
 * define the subheading
 * cache the subheading
 * fetch the class test questions
 * cache the class test questions query
 * define and display the class test questions data
 * display the tes questions edit modal
 * display the create test questions modal
 * 
 * *
*/

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

session_start();

//define the test questions class
class testQuestions{

    public $class_encrypted_id;

    public $test_encrypted_id;


    public $subheading;


    public $test_questions_query_result;

    public $test_questions_query_status;

    public $Cached_test_questions_result;


    public $test_questions_edit_modal;

    //fetch the class encrypted id and test encrypted id
    public function fetchSomeEncryptedId(){

        $this->class_encrypted_id = $_GET['classEncryptedId'];

        $this->test_encrypted_id = $_GET['testEncryptedId'];

    }

    //include the page header
    public function header(){

        include('../header/header.php');

    }

    //define the subheading
    public function subheading(){

        $this->subheading = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

            <br>

            <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
   
               Create Test<br>
               Questions
   
            </h1>

            <br>

       </div>

       <br>

        <ul class="nav justify-content-center">

            <li class="nav-item">

                <a class="nav-link text-white" style="text-decoration:none;" href="lecturer-test-initializer.php?classEncryptedId='.$this->class_encrypted_id.'&&testEncryptedId='.$this->test_encrypted_id.'">

                    <button class="btn btn-md text-white" style="background-color:#1d007e;">

                        Go to test dashboard

                    </button>

                </a>

            </li>

            <li class="nav-item">

                <a class="nav-link text-white" style="text-decoration:none;">

                    <button class="btn btn-md text-white" style="background-color:#1d007e;" data-bs-toggle="modal" 
                    data-bs-target="#createTestQuestionsModal">
 
                        Create questions <i class="fa fa-plus-circle"></i> 

                    </button>

                </a>

            </li>

        </ul>

        <br>
        
        ';

    }

    //cache the subheading
    public function cacheSubheading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "test_questions_subheading";
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

    public function displayNewQuestionAlert(){

        echo '
        
        <div align="center">

        <div class="alert alert-info" style="width:250px;height:110px;">
 
                <div class="row">
 
                     <div class="col">
                    
                         Question created<br>
                         Scroll down
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
        </div>
 
        </div>';

    }

    public function deleteQuestionAlert(){

        echo '
        
        <div align="center">

        <div class="alert alert-danger" style="width:250px;height:90px;">
 
                <div class="row">
 
                     <div class="col">
                    
                         Question deleted
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
        </div>
 
        </div>';

    }

    //fetch the class test questions
    public function fetchClassTestQuestions(){

        // class test db connection
        include('../../resources/database/class-test-questions-db-connections.php');

        $test_questions_query = "SELECT * FROM test_questions_of_class_test_".$this->test_encrypted_id."";

        $this->test_questions_query_result = $conn21->query($test_questions_query);

        if($this->test_questions_query_result->num_rows > 0){

            $this->test_questions_query_status = "true";

        }else{

            $this->test_questions_query_status = "false";

        }
        
        mysqli_close($conn21);
    }

    //cache the class test questions query
    public function cacheClassTestQuestionsQuery(){

        if($this->test_questions_query_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "test_questions_result";
            $Cached_test_questions_result = $InstanceCache->getItem($key);
    
            if (!$Cached_test_questions_result->isHit()) {
                $Cached_test_questions_result->set($this->test_questions_query_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_test_questions_result); // Save the cache item just like you do with doctrine and entities
         
                $this->cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $this->cached_test_questions_result = $Cached_test_questions_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }

        }

    }

    //define and display the class test questions data
    public function displayTestQuestions(){

        echo '

        <div class="container">
    
            <h3>Questions created</h3>
    
            <hr>
    
            ';

        if($this->test_questions_query_status == "true"){

            while($test_question_row = $this->cached_test_questions_result->fetch_assoc()){

                $test_question_rows[] = $test_question_row;

            }

            foreach($test_question_rows as $each_test_row){

                $question_id = $each_test_row['id'];

                $encoded_id = base64_encode($question_id);

                $question_title = $each_test_row['question_title'];

                $question_encrypted_id = $each_test_row['question_encrypted_id'];

                $optionA = $each_test_row['optionA'];

                $optionB = $each_test_row['optionB'];

                $optionC = $each_test_row['optionC'];

                $optionD = $each_test_row['optionD'];

                $correct_option = $each_test_row['correct_option'];

                echo '
                
                <div class="d-flex shadow p-4 mb-4 bg-light">

                    <div class="flex-shrink-0">
                        
                    </div>

                    <div class="flex-grow-1 ms-3">

                        <h5>

                            Question '.$question_id.' 

                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" 
                            id="dropdownMenuButtonSM" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-cog"></i>
                            </button>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButtonSM">
                                <li><h6 class="dropdown-header">Settings</h6></li>
                                <li><a class="dropdown-item" href="delete-test-question.php?id='.$encoded_id.'&&questionEncryptedId='.$question_encrypted_id.'&&testEncryptedId='.$this->test_encrypted_id.'&&classEncryptedId='.$this->class_encrypted_id.'">Delete question</a></li>
                            </ul>

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

                        <p class="text-muted">

                            The correct option is set to '.$correct_option.' 

                        </p>

                    </div>

                </div>
                
                ';

            }

        }else{

            echo '
            
            <div align="center">
             
                <div class="alert alert-warning" style="width:250px;">

                    <p class="lead">No test questions To display</p>

                </div>

            </div>';

        }

    }

    
    //display the create test questions modal
    public function createTestQuestionsModal(){

        echo '
        
        <div class="modal fade" id="createTestQuestionsModal" tabindex="-1" 
        aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role-document>

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 align="center" class="modal-title text-light" id="exampleModalCenteredScrollableTitle">
                            <i class="fa fa-plus-circle"></i> Create questions
                        </h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <div>

                            <form action="create-test-questions.php" method="POST">

                                <textarea class="form-control" name="question" rows="2" placeholder="Question"></textarea>

                                <br>

                                <div class="row g-3">

                                    <div class="col-md-6">

                                        <label class="text-muted" for="optionA">Option A</label>
                                        <input id="optionA" class="form-control" type="text" name="optionA" placeholder="Option A" required
                                        style="height:40px;">


                                    </div>

                                    <div class="col-md-6">

                                        <label class="text-muted" for="secondsimit">Option B</label>
                                        <input id="optionB" class="form-control" type="text" name="optionB" placeholder="Option B" required
                                        style="height:40px;">

                                        <br>

                                    </div>

                                </div>

                                <div class="row g-3">

                                    <div class="col-md-6">

                                        <label class="text-muted" for="optionC">Option C</label>
                                        <input id="optionC" class="form-control" type="text" name="optionC" placeholder="Option C" required
                                        style="height:40px;">

                                    </div>

                                    <div class="col-md-6">

                                        <label class="text-muted" for="optionD">Option D</label>
                                        <input id="optionD" class="form-control" type="text" name="optionD" placeholder="Option D" required
                                        style="height:40px;">

                                        <br>

                                    </div>

                                </div>

                                <input type="hidden" name="testEncryptedId" value='.$this->test_encrypted_id.'>

                                <input type="hidden" name="classEncryptedId" value='.$this->class_encrypted_id.'>

                                <select class="form-control" name="correctOption">
                                    <option>Correct option</option>
                                    <option>optionA</option>
                                    <option>optionB</option>
                                    <option>optionC</option>
                                    <option>optionD</option>
                                </select>

                                <br>

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

$test_questions = new testQuestions();

if(isset($_SESSION['lecturer_session'])){

    if(isset($_GET['classEncryptedId']) && $_GET['classEncryptedId'] !== "" && isset($_GET['testEncryptedId']) 
    && $_GET['testEncryptedId'] !== ""){

        $test_questions->fetchSomeEncryptedId();

        $test_questions->header();

        $test_questions->subheading();

        $test_questions->cacheSubheading();

        if(isset($_GET['newQuestionAlert'])){

            $test_questions->displayNewQuestionAlert();

        }elseif(isset($_GET['deleteQ'])){

            $test_questions->deleteQuestionAlert();

        }

        $test_questions->fetchClassTestQuestions();

        $test_questions->cacheClassTestQuestionsQuery();

        $test_questions->displayTestQuestions();

        $test_questions->createTestQuestionsModal();

    }

}else{

    header("location:logout-success.php");

}

?>