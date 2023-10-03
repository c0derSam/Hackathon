<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-test/test-participated-students.php

** About : this module displays the students that have participated in the class test

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the test participated students class
 * fetch the class data
 * display the header
 * display the subheading
 * cache the subheading
 * fetch the class test participated students query
 * cache the query
 * display the participated students
 * 
 * 
 * *
*/

session_start();

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the test participated students class
class testParticipants{

    public $test_encrypted_id;

    public $class_encrypted_id;

    public $test_title;

    public $total;


    public $subheading;


    public $participated_students_results;

    public $participated_students_status;

    public $cached_participated_students_result;

    //fetch the class data
    public function fetchClassData(){

        $this->test_encrypted_id = $_GET['testEncryptedId'];

        $this->class_encrypted_id = $_GET['classEncryptedId'];

        $this->test_title = $_GET['testTitle'];

        $this->total = $_GET['total'];

    }

    //display the header
    public function Header(){

        include('../header/header.php');

    }

    //display the subheading
    public function subheading(){

        $this->subheading = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

            <br>

            <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
   
               '.$this->test_title.'
   
            </h1>

            <br>

       </div>

       <br>

       <ul class="nav justify-content-center">

            <li class="nav-item">

                <a class="nav-link" href="lecturer-test-initializer.php?classEncryptedId='.$this->class_encrypted_id.'&&testEncryptedId='.$this->test_encrypted_id.'">

                    <button class="btn btn-md text-light" style="background-color:#1d007e;">

                        <i class="fa fa-arrow-circle-left"></i> Back to test dashboard

                    </button>

                </a>

            </li>

        </ul>

        <div class="container">
 
             <h3><span class="badge bg-dark text-light">'.$this->total.'</span> Participated student(s)</h3>
 
             <hr>
 
             <div class="row">
 
        
        ';

    }

    //cache the subheading
    public function cacheSubheading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "students-participated-subheading";
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

    //fetch the class test participated students query
    public function fetchTestParticipatedStudentsQuery(){

        include('../../resources/database/class-test-result-db-connection.php'); //conn20

        $participated_students_query = "SELECT * FROM students_of_class_test_".$this->test_encrypted_id."";

        $this->participated_students_results = $conn20->query($participated_students_query);

        if($this->participated_students_results->num_rows > 0){

            $this->participated_students_status = "true";

        }else{

            $this->participated_students_status = "false";

        }

    }

    //cache the query
    public function cacheQuery(){

        if($this->participated_students_status == "true"){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
         
            $InstanceCache = CacheManager::getInstance('files');
         
            $key = "cache_participated_students";
            $Cached_participated_students_result = $InstanceCache->getItem($key);
    
            if (!$Cached_participated_students_result->isHit()) {
                $Cached_participated_students_result->set($this->participated_students_results)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_participated_students_result); // Save the cache item just like you do with doctrine and entities
         
                $this->cached_participated_students_result = $Cached_participated_students_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
         
            }else{
             
                $this->cached_participated_students_result = $Cached_participated_students_result->get();
                //echo 'READ FROM CACHE // ';
         
                $InstanceCache->deleteItem($key);
            }

        }

    }

    //display the participated students
    public function dissplayParticipatedStudents(){

        if($this->participated_students_status == "true"){

            while($participated_row = $this->cached_participated_students_result->fetch_assoc()){

                $student_fullname = $participated_row['student_fullname'];
    
                $student_username = $participated_row['student_username'];
    
                $student_matric_number = $participated_row['student_matric_number'];
    
                $student_avatar_filename = $participated_row['student_avatar'];
    
                $final_result = $participated_row['final_result'];
    
                $avatar = "../../resources/avatars/".$student_avatar_filename;
    
                echo '
                
                <div class="col">
                   
                   <div class="d-flex shadow p-4 mb-4 bg-light" style="width:300px">
    
                        <div class="flex-shrink-0">
                            <img class="rounded-circle" src='.$avatar.'
                            style="border:5px solid white;padding:1px;max-height:60px;
                            border-radius:30px;max-width:60px;"/>
                        </div>
    
                        <div class="flex-grow-1 ms-3">
    
                           <h5 style="max-width:200px;white-space:nowrap;overflow:hidden;
                           text-overflow:ellipsis;">'.$student_fullname.'</h5>
    
                            <p>
                              '.$student_matric_number.'<br>
                              <span class="text-muted"><small><b>@'.$student_username.'</b></small></span>
                            </p>
    
                            <button class="btn btn-md text-light" style="background-color:#1d007e;">
    
                                Score : '.$final_result.'
    
                            </button>
    
                        </div>
    
                    </div>
                    <!-- course container flex display -->
    
                    </div>
                
                ';
    
            }
    
        
        }

    }

}


$test_participants = new testParticipants();

if(isset($_SESSION['lecturer_session'])){

    $test_participants->fetchClassData();

    $test_participants->Header();

    $test_participants->subheading();

    $test_participants->cacheSubheading();

    $test_participants->fetchTestParticipatedStudentsQuery();

    $test_participants->cacheQuery();

    $test_participants->dissplayParticipatedStudents();

}else{

    header("location:logout-success.php");

}

?>