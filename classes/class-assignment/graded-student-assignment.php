<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-assignment/graded-student-assignment.php

** About : this module displays the graded students assignment

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the graded assignment class
 * fetch the class id
 * include the header
 * display the subheading
 * fetch the students graded submitted assignment, cache the query and display the results
 * perform administrative functions
 * 
 * *
 */

session_start();

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the graded assignment class
class gradedAssignment{

    public $class_id;

    public $course_id;

    public $grade_total;


    public $graded_assignment_result;

    public $graded_assignment_query_status;

    public $cached_graded_assignment_result;

    public $graded_student_fullname;

    public $graded_stduent_username;

    public $graded_student_matric;

    public $graded_student_avatar;

    public $graded_student_grade;

    public $graded_encrypted_id;

    public $graded_assignment_date;

    public $graded_assignment_time;


    //fetch the class id
    public function fetchClassAndCourseId(){

        $this->class_id = $_GET['classId'];

        $this->course_id = $_GET['courseId'];

        $this->grade_total = $_GET['gradeTotal'];

    }

    //include the header
    public function header(){

        include('../header/header.php');
 
    }

    //display the subheading
    public function displaySubheading(){

        echo '
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" align="center">

            <br>

            <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
   
               Graded Aissignment
   
            </h1>

            <br>

        </div>

        <br>

        <div class="back-to-assignment" align="center">

            <div class="card shadow p-4 mb-4 bg-light" style="width:200px;">

                <a class="text-white" href="class-assignment-grade-form-controller.php?classId='.$this->class_id.'&&courseId='.$this->course_id.'" style="text-decoration:none;">

                    <button class="btn btn-md text-white" style="background-color:#1d007e;">

                    <i class="fa fa-arrow-left"></i> Back to assignment

                    </button>

                </a>

            </div>

        </div>
        <!-- container-->

        <div class="container">
 
        <h3>Graded <span class="badge bg-dark">'.$this->grade_total.'</span> </h3>
 
        <hr>
 
        ';

    }

    public function fetchGradedAssignment(){

        //require the env library
        require('../../vendorEnv/autoload.php');
    
        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();
    
        include('../../resources/database/courses-classes-assignments-db-connection.php');
    
        $graded_assignment_query = "SELECT * FROM assignment_submissions_of_class_".$this->class_id." WHERE status='graded'";
    
        $this->graded_assignment_result = $conn16->query($graded_assignment_query);
    
        if($this->graded_assignment_result->num_rows > 0){
    
            $this->graded_assignment_query_status = TRUE;
    
        }else{
    
            $this->graded_assignment_query_status = FALSE;
    
        }
    
        mysqli_close($conn16);
    
    }
    
    public function cacheGradedAssignmentQuery(){
    
        if($this->graded_assignment_query_status == TRUE){
    
            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "graded_assignment";
            $Cached_graded_assignment_result = $InstanceCache->getItem($key);
    
            if (!$Cached_graded_assignment_result->isHit()) {
                $Cached_graded_assignment_result->set($this->graded_assignment_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_graded_assignment_result); // Save the cache item just like you do with doctrine and entities
            
                $this->cached_graded_assignment_result = $Cached_graded_assignment_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
            
            } else {
                
                $this->cached_graded_assignment_result = $Cached_graded_assignment_result->get();
                //echo 'READ FROM CACHE // ';
            
                $InstanceCache->deleteItem($key);
            }
    
        }
    
    }
    
    public function displayGradedAssignmentData(){
    
        echo '
            
            <div class="row">
               
            ';
    
        if($this->graded_assignment_query_status == TRUE){
    
            while($graded_row =  $this->cached_graded_assignment_result->fetch_assoc()){
    
               $this->graded_student_fullname = $graded_row['student_fullname'];
    
               $this->graded_student_username = $graded_row['student_username'];
    
               $this->graded_student_matric = $graded_row['student_matric'];
    
               $this->graded_student_avatar = $graded_row['student_avatar'];
    
               $this->graded_student_grade = $graded_row['grade'];
    
               $this->graded_encrypted_id = $graded_row['encrypted_id'];
    
               $this->graded_assignment_date = $graded_row['date'];
    
               $this->graded_assignment_time = $graded_row['time'];
    
               $avatar_file = "../../resources/avatars/".$this->graded_student_avatar;
    
               echo '
               <div class="col">
               
                <div class="d-flex shadow p-4 mb-4 bg-light" style="max-width:300px;">
        
                   <div class="flex-shrink-0">
                        <img class="rounded-circle" src='.$avatar_file.' 
                        style="border:5px solid white;padding:1px;max-height:60px;border-radius:30px;max-width:60px;"/>
                   </div>
    
                   <div class="flex-grow-1 ms-3">
    
                       <h5 style="max-width:200px;white-space:nowrap;overflow:hidden;
                       text-overflow:ellipsis;text-align:left;">
                        <small>
    
                           '.$this->graded_student_fullname.'
    
                        </small>
                       </h5>
    
                       <p>
                         '.$this->graded_student_matric.'
                       </p>
    
                       <p>
                         <small><b>Grade:</b></small><br>
                         '.$this->graded_student_grade.'
                       </p>
    
                        <br>
    
                       <small> 
    
                        <div class="row">
    
                            <div class="col">
    
                               '.$this->graded_assignment_time.'
    
                            </div>
    
                            <div class="col">
    
                                '.$this->graded_assignment_date.'
    
                            </div>
    
                            <div class="col">
    
                               
                            </div>
    
                        </div>
                        <!-- row 1 -->
    
                        </small>
    
                    </div>
    
    
                </div>
    
                </div>
               
               ';
    
            }
    
            echo '
            
            </div>
                
            ';
        
    
        }else{
    
            echo '
            
            <div align="center">
    
                <div class="alert alert-warning" style="width:300px;">
    
                   There are no graded assignments
    
                </div>
    
            </div>
    
            <br>
            
            ';
    
        }
    
    }

}

$graded_assignment = new gradedAssignment();

session_regenerate_id(true);

$graded_assignment->header();

if(isset($_SESSION['lecturer_session']) && !empty($_GET['classId']) && !empty($_GET['courseId'])){

    $graded_assignment->fetchClassAndCourseId();

    $graded_assignment->displaySubheading();

    $graded_assignment->fetchGradedAssignment();

    $graded_assignment->cacheGradedAssignmentQuery();

    $graded_assignment->displayGradedAssignmentData();

}else{

    header('lgout-success.php');

}


?>