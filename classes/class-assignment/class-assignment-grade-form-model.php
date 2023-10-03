<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-assignment/class-assignment-grade-form-model.php

** About : this module allows the lecturer to view and grade the students assignment

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the class assignment grade class
 * fetch the class id
 * fetch the lecturer session
 * include the header
 * display the subheading
 * fetch the class assignment question query
 * cache the class assignment question query
 * define the class assignment question data
 * display the class assignment
 * fetch the students ungraded submitted assignment total
 * fetch the students graded submitted assignment total
 * display the grade form heading
 * fetch the students ungraded submitted assignment, cache the query and display the results
 * fetch the students graded submitted assignment, cache the query and display the results
 * perform administrative functions
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the class assignment grade class
class assignmentGrade{

    public $class_id;

    public $course_id;
    
    
    public $lecturer_session;


    public $class_assignment_question_result;

    public $cached_class_assignment_question_result;

    public $course_code;

    public $assignment_topic;

    public $date_assignment_created;

    public $time_assignment_created;

    public $expiring_date;

    public $assignment_status;


    public $ungraded_assignment_total_result;

    public $cached_ungraded_assignment_total_result;

    public $ungraded_assignment_total;


    public $graded_assignment_total_result;

    public $cached_graded_assignment_total_result;

    public $graded_assignment_total;


    public $grade_form_heading;


    public $ungraded_assignment_result;

    public $ungraded_assignment_query_status;

    public $cached_ungraded_assignment_result;

    public $ungraded_student_fullname;

    public $ungraded_stduent_username;

    public $ungraded_student_matric;

    public $ungraded_student_avatar;

    public $ungraded_student_answer;

    public $ungraded_encrypted_id;

    public $ungraded_assignment_date;

    public $ungraded_assignment_time;


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

    }

    //fetch the lecturer session
    public function fetchLecturerSession(){

        if(isset($_SESSION['lecturer_session'])){

            $this->lecturer_session['lecturer_session'] = $_SESSION['lecturer_session'];

        }

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
   
               Class Assignment
   
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

    public function displayGradeAlert(){

        echo '
        
        <div align="center">

            <div class="alert alert-info" style="width:200px;height:80px;">

                <div class="row">

                    <div class="col">
                   
                        Assignment graded 

                    </div>

                    <div class="col">

                       <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                    </div>

                </div>

            </div>

        </div>
        
        ';

    }

    //fetch the class assignment question query
    public function fetchClassAssignmentQuestion(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-db-connection.php');

        $class_assignment_question_query = "SELECT * FROM classes_of_course_".$this->course_id." WHERE class_encrypted_id = '$this->class_id'";

        $this->class_assignment_question_result = $conn10->query($class_assignment_question_query);

        mysqli_close($conn10);

    }

    public function cacheClassAssignmentQuestion(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "class_room_assignment_question_1";
        $Cached_class_assignment_question_result = $InstanceCache->getItem($key);

        if (!$Cached_class_assignment_question_result->isHit()) {
            $Cached_class_assignment_question_result->set($this->class_assignment_question_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_class_assignment_question_result); // Save the cache item just like you do with doctrine and entities
        
            $this->cached_class_assignment_question_result = $Cached_class_assignment_question_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            $this->cached_class_assignment_question_result = $Cached_class_assignment_question_result->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    //define the class assignment question data
    public function classroomAsignmentData(){

        $class_assignment_question_row = $this->cached_class_assignment_question_result->fetch_assoc();

        $this->course_code = $class_assignment_question_row['course_code'];

        $this->assignment_topic = $class_assignment_question_row['assignment_topic'];

        $this->date_assignment_created = $class_assignment_question_row['date_assignment_created'];

        $this->time_assignment_created = $class_assignment_question_row['time_assignmnet_created'];

        $this->expiring_date = $class_assignment_question_row['deadline'];

        $this->assignment_status = $class_assignment_question_row['assignment_status'];

    }

    //display the class assignment
    public function displayAssignment(){

        echo $this->displayClassAssignment = '
        
        <div class="container">
 
        <h3>Assignment  
        
            <a href="update-assignment-status.php?classId='.$this->class_id.'&&courseId='.$this->course_id.'&&currentStatus='.$this->assignment_status.'">
                <button class="btn btn-outline-dark">'.$this->assignment_status.' | change</button> 
            </a>
            
        </h3>
 
        <hr>
 
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
         <!-- assignment accordion -->
 
         <br>
 
         <div class="row">
 
             <div class="col">
                 <button class="btn btn-outline-dark">
                created at '.$this->time_assignment_created.'
                </button>
             </div>
 
             <div class="col">
                <button class="btn btn-outline-dark">
                created on '.$this->date_assignment_created.'
                </button>
             </div>
 
             <div class="col">
                <button class="btn btn-outline-dark">
                expires at '.$this->expiring_date.'
                </button>
             </div>
 
         </div>
         <!-- assignment items row -->
 
         </div>
    
         <br>
        
        ';
 
    }

    //fetch the students ungraded submitted assignment total
    public function fetchUngradedAssignmentTotal(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        include('../../resources/database/courses-classes-assignments-db-connection.php');
        //conn16

        $ungraded_assignment_total_query = "SELECT count(*) AS ungradedTotal FROM 
        assignment_submissions_of_class_".$this->class_id." WHERE status='ungraded'";

        $this->ungraded_assignment_total_result = $conn16->query($ungraded_assignment_total_query);

        mysqli_close($conn16);

    }

    //cache the students ungraded submitted assignment total query
    public function cacheUngradedAssignmentTotalQuery(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "ungraded_assignment_total_query";
        $Cached_ungraded_assignment_total_result = $InstanceCache->getItem($key);

        if (!$Cached_ungraded_assignment_total_result->isHit()) {
            $Cached_ungraded_assignment_total_result->set($this->ungraded_assignment_total_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_ungraded_assignment_total_result); // Save the cache item just like you do with doctrine and entities
        
            $this->cached_ungraded_assignment_total_result = $Cached_ungraded_assignment_total_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            $this->cached_ungraded_assignment_total_result = $Cached_ungraded_assignment_total_result->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }


    }

    //define the data
    public function defineUngradedTotal(){

        $ungraded_total_data_row = $this->cached_ungraded_assignment_total_result->fetch_assoc();

        $this->ungraded_assignment_total = $ungraded_total_data_row['ungradedTotal'];

    }

    //fetch the students graded submitted assignment total
    public function fetchGradedAssignmentTotal(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        include('../../resources/database/courses-classes-assignments-db-connection.php');

        $graded_assignment_total_query = "SELECT count(*) AS ungradedTotal FROM 
        assignment_submissions_of_class_".$this->class_id." WHERE status='graded'";

        $this->graded_assignment_total_result = $conn16->query($graded_assignment_total_query);

        mysqli_close($conn16);

    }

    //cache the students graded submitted assignment total query
    public function cacheGradedAssignmentTotalQuery(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "graded_assignment_total_query";
        $Cached_graded_assignment_total_result = $InstanceCache->getItem($key);

        if (!$Cached_graded_assignment_total_result->isHit()) {
            $Cached_graded_assignment_total_result->set($this->graded_assignment_total_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_graded_assignment_total_result); // Save the cache item just like you do with doctrine and entities
        
            $this->cached_graded_assignment_total_result = $Cached_graded_assignment_total_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            $this->cached_graded_assignment_total_result = $Cached_graded_assignment_total_result->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }


    }

    //define the data
    public function defineGradedTotal(){

        $graded_total_data_row = $this->cached_graded_assignment_total_result->fetch_assoc();

        $this->graded_assignment_total = $graded_total_data_row['ungradedTotal'];

    }

    //display the grade form heading
    public function displayGradeFormHeading(){

        echo $this->grade_form_heading = '

        <br>
        
        <div class="container">

            <ul class="nav nav-tabs justify-content-center">

                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#ungraded"> 
                       Ungraded <span class="badge bg-dark">'.$this->ungraded_assignment_total.'</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="graded-student-assignment.php?classId='.$this->class_id.'&&courseId='.$this->course_id.'&&gradeTotal='.$this->graded_assignment_total.'">
                       Graded <span class="badge bg-dark">'.$this->graded_assignment_total.'</span>
                    </a>
                </li>

            </ul>
            <!-- tabs -->

            <div class="tab-content">

                <br>
        
        ';

    }

    //fetch the students ungraded submitted assignment, cache the query and display the results
    public function fetchUngradedAssignment(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        include('../../resources/database/courses-classes-assignments-db-connection.php');

        $ungraded_assignment_query = "SELECT * FROM assignment_submissions_of_class_".$this->class_id." WHERE status='ungraded' 
        ORDER BY id DESC";

        $this->ungraded_assignment_result = $conn16->query($ungraded_assignment_query);

        if($this->ungraded_assignment_result->num_rows > 0){

            $this->ungraded_assignment_query_status = TRUE;

        }else{

            $this->ungraded_assignment_query_status = FALSE;

        }

        mysqli_close($conn16);

    }

    public function cacheUngradedAssignmentQuery(){

        if($this->ungraded_assignment_query_status == TRUE){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "ungraded_assignment";
            $Cached_ungraded_assignment_result = $InstanceCache->getItem($key);
    
            if (!$Cached_ungraded_assignment_result->isHit()) {
                $Cached_ungraded_assignment_result->set($this->ungraded_assignment_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_ungraded_assignment_result); // Save the cache item just like you do with doctrine and entities
            
                $this->cached_ungraded_assignment_result = $Cached_ungraded_assignment_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
            
            } else {
                
                $this->cached_ungraded_assignment_result = $Cached_ungraded_assignment_result->get();
                //echo 'READ FROM CACHE // ';
            
                $InstanceCache->deleteItem($key);
            }
 
        }

    }

    public function displayUngradedAssignmentData(){

        if($this->ungraded_assignment_query_status == TRUE){

            echo '
            
            <div class="tab-pane container active" id="ungraded" align="">

                <div class="row">
               
            ';

            while($ungraded_row =  $this->cached_ungraded_assignment_result->fetch_assoc()){

               $this->ungraded_student_fullname = $ungraded_row['student_fullname'];

               $this->ungraded_student_username = $ungraded_row['student_username'];

               $this->ungraded_student_matric = $ungraded_row['student_matric'];

               $this->ungraded_student_avatar = $ungraded_row['student_avatar'];

               $this->ungraded_student_answer = $ungraded_row['student_answer'];

               $this->ungraded_encrypted_id = $ungraded_row['encrypted_id'];

               $this->ungraded_assignment_date = $ungraded_row['date'];

               $this->ungraded_assignment_time = $ungraded_row['time'];

               $avatar_file = "../../resources/avatars/".$this->ungraded_student_avatar;

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
 
                           '.$this->ungraded_student_fullname.'

                        </small>
                       </h5>

                       <p>
                         '.$this->ungraded_student_matric.'
                       </p>

                       <p>
                         <small><b>Answer:</b></small><br>
                         '.$this->ungraded_student_answer.'
                       </p>

                        <form action="grade-student-assignment-controller.php" method="POST">

                            <div class="input-group">

                               <input class="form-control" type="text" name="grade" placeholder="example 7/10" 
                               required style="height:40px;width:150px;">

                               <input type="hidden" name="courseCode" value='.$this->course_code.'>

                               <input type="hidden" name="assignmentId" value='.$this->ungraded_encrypted_id.'>

                               <input type="hidden" name="courseId" value='.$this->course_id.'>

                               <input type="hidden" name="classId" value='.$this->class_id.'>

                               <button type="submit" class="input-group-text text-light" style="background-color:#1d007e;">
                                    grade
                               </button>

                            </div>

                        </form>

                        <br>

                       <small> 

                        <div class="row">

                            <div class="col">

                               '.$this->ungraded_assignment_time.'

                            </div>

                            <div class="col">

                                '.$this->ungraded_assignment_date.'

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
                <!-- row -->

                </div>
                ';
        

        }else{

            echo '
            
            <div align="center">

                <div class="alert alert-warning" style="width:300px;">

                   There are no ungraded assignments

                </div>

            </div>

            <br>
            
            ';

        }

    }

}

?>