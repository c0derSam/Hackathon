<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-assignment/student-class-assignment-model.php

** About : this module displays the student assignment submit form and grade

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the submit class assignment redirect class
 * fetch the class id
 * include the header
 * display the class assignment sub heading
 * fetch the student sessions
 * fetch the class assignment question
 * fetch the student class assignment with query the validates if the student has submitted the assignment
 * displaying the student assignment grades if the student assignment has been submitted
 * else, display the student assignment submit form
 * perform administrative functions
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

class submitAssignment{

    public $class_id;

    public $course_id;

    public $student_session;


    public $class_assignment_subheading;


    public $class_assignment_question_result;

    public $cached_class_assignment_question_result;


    
    public $course_code;
    
    public $lecturer_encrypted_id;
    
    public $assignment_topic;

    public $date_assignment_created;

    public $time_assignment_created;

    public $expiring_date;

    public $assignment_status;


    public $displayClassAssignment;


    public $student_assignment_result;

    public $student_assignment_status;

    public $cached_student_assignment_result;

    public $student_grade;

    public $student_status;

    public $student_assignment_grade;

    public $student_assignment_form;

    //fetch the class id
    public function fetchClassIdAndCourseId(){

        $this->class_id = $_GET['classId'];

        $this->course_id = $_GET['courseId'];

    }

    //include the header
    public function header(){

        include('../header/header.php');

    }

    //display the class assignment sub heading
    public function displayClassAssignmentSubheading(){

        echo $this->class_assignment_subheading = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

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

    //fetch the student sessions
    public function fetchStudentSession(){

        if(isset($_SESSION['student_session'])){

            $this->student_session = $_SESSION['student_session'];

        }

    }

    //fetch the class assignment data 
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

    //cache the query
    public function cacheClassAssignmentQuestion(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "class_room_assignment_question";
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

    //define the class assignment assignment question
    public function classroomAsignmentData(){

        $class_assignment_question_row = $this->cached_class_assignment_question_result->fetch_assoc();

        $this->course_code = $class_assignment_question_row['course_code'];

        $this->lecturer_encrypted_id = $class_assignment_question_row['lecturer_encrypted_id'];

        $this->assignment_topic = $class_assignment_question_row['assignment_topic'];

        $this->date_assignment_created = $class_assignment_question_row['date_assignment_created'];

        $this->time_assignment_created = $class_assignment_question_row['time_assignmnet_created'];

        $this->expiring_date = $class_assignment_question_row['deadline'];

        $this->assignment_status = $class_assignment_question_row['assignment_status'];

    }

    public function displayAssignment(){

       echo $this->displayClassAssignment = '
       
       <div class="container">

       <h3>Assignment </h3>

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
   
       
       ';

    }

    //fetch the student class assignment with query the validates if the student has submitted the assignment
    public function fetchStudentAssignmentData(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-classes-assignments-db-connection.php');

        $student_assignment_query = "SELECT * FROM assignment_submissions_of_class_".$this->class_id." 
        WHERE encrypted_id = '$this->student_session'";

        $this->student_assignment_result = $conn16->query($student_assignment_query);

        if($this->student_assignment_result->num_rows > 0){

            $this->student_assignment_status = TRUE;

        }else{

            $this->student_assignment_status = FALSE;

        }

    }

    //cache the student assignment data
    public function cacheStudentAssignmentData(){

        if($this->student_assignment_status == TRUE){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "student_assignment_data";
            $Cached_student_assignment_result = $InstanceCache->getItem($key);
    
            if (!$Cached_student_assignment_result->isHit()) {
                $Cached_student_assignment_result->set($this->student_assignment_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_student_assignment_result); // Save the cache item just like you do with doctrine and entities
            
                $this->cached_student_assignment_result = $Cached_student_assignment_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
            
            } else {
                
                $this->cached_student_assignment_result = $Cached_student_assignment_result->get();
                //echo 'READ FROM CACHE // ';
            
                $InstanceCache->deleteItem($key);
            }

        }

    }

    //defining the student assignment
    public function defineStudentAssignment(){

        if($this->student_assignment_status == TRUE){

            $student_assignment_row = $this->cached_student_assignment_result->fetch_assoc();

            $this->student_grade = $student_assignment_row['grade'];

            $this->student_status = $student_assignment_row['status'];

        }

    }


    //displaying the student assignment grades if the student assignment has been submitted
    public function displayStudentAssignmentGrade(){

        if($this->student_assignment_status == TRUE){

           echo $this->student_assignment_grade = '
           
           <br>

           <div class="container">

                <div align="center">

                        <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

                            <h5>Assignment Grade</h5> <br>

                            <div class="alert alert-info">

                              <small>Your assignment was submitted, its '.$this->student_status.' by the lecturer, 
                              And your score is '.$this->student_grade.'.</small>

                            </div>

                        </div>

                </div>

            </div>

           ';

        }

    }

    //display the student assignment submit form
    public function displayStudentAssignmentForm(){

        if($this->student_assignment_status == FALSE){

            echo $this->student_assignment_form = '
            
            <br>

            <div class="container">

                <div align="center">

                    <form action="submit-assignment-controller.php" method="POST">

                        <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

                            <h5>Submit Assignment</h5> <br>

                            <textarea class="form-control" name="assignmentAnswer" rows="5" placeholder="Answer" required></textarea>

                            <br>

                            <div class="alert alert-warning">

                              <small>Review your answer, once Your assignment is submitted, you cant edit.</small>

                            </div>

                            <br>

                            <input type="hidden" name="courseCode" value='.$this->course_code.'>

                            <input type="hidden" name="courseId" value='.$this->course_id.'>

                            <input type="hidden" name="classId" value='.$this->class_id.'>

                            <input type="hidden" name="status" value='.$this->assignment_status.'>

                            <input type="hidden" name="lecturerId" value='.$this->lecturer_encrypted_id.'>

                            <button type="submit" class="btn btn-md text-light" style="background-color:#1d007e;">
                               Submit
                            </button>

                        </div>

                    </form>

                </div>

            </div>
            
            ';

        }

    }

}



?>