<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/course-enrolled-students/courses-enrolled-students-model.php

** About : this module fetches the courses enrolled students

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the course enrolled student class
 * fetch the course data
 * sanitize the course data
 * fetch the course enrolled students from the course database
 * cache the enrolled students query
 * display the enrolled students data
 * cache the display enrolled student data
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the course enrolled student class
class courseEnrolledStudent{

    public $fetch_course_id;

    public $sanitized_course_id;

    public $course_enrolled_students_result;

    public $course_enrolled_student_status;

    public $cached_student_enrolled_result;

    public $student_fullname;

    public $student_username;
        
    public $student_matric_number;

    public $student_avatar;

    public $display_student_results;

    //fetch the course data
    public function fetchCourseData(){

       $this->fetch_course_id = $_GET['id'];

    }

    //sanitize the course data
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = htmlentities($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //fetch the course enrolled students from the course database
    public function fetchEnrolledStudents(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // course db connection
        include('../../resources/database/courses-students-enrolled-db-connection.php');

        $course_enrolled_students_query = "SELECT * FROM enrolled_students_of_course_".$this->sanitized_course_id."  ORDER BY id DESC";

        $this->course_enrolled_students_result = $conn9->query($course_enrolled_students_query);

        if($this->course_enrolled_students_result->num_rows > 0){

           $this->course_enrolled_student_status = TRUE;

        }
        else{

            $this->course_enrolled_student_status = FALSE;

        }

        mysqli_close($conn9);
    }

    //cache the enrolled students query
    public function cacheEnrolledstudentsQuery(){

        if($this->course_enrolled_student_status == TRUE){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
            
            $key = "enrolled_students";
            $Cached_enrolled_students_result = $InstanceCache->getItem($key);
    
            if (! $Cached_enrolled_students_result->isHit()) {
                $Cached_enrolled_students_result->set($this->course_enrolled_students_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save( $Cached_enrolled_students_result); // Save the cache item just like you do with doctrine and entities
            
                $this->cached_student_enrolled_result =  $Cached_enrolled_students_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
            
            } else {
                
                $this->cached_student_enrolled_result =  $Cached_enrolled_students_result->get();
                //echo 'READ FROM CACHE // ';
            
                $InstanceCache->deleteItem($key);
            }

        }

    }

    //display the enrolled students data
    public function displayEnrolledStudentsData(){

        if($this->course_enrolled_student_status == TRUE){

            echo '<div class="row">';

            while($row = $this->cached_student_enrolled_result->fetch_assoc()){

                $this->student_fullname = $row['student_fullname'];

                $this->student_username = $row['student_username'];
        
                $this->student_matric_number = $row['student_matric_number'];

                $this->student_avatar = $row['student_avatar'];

                $avatar_file = "../../resources/avatars/".$this->student_avatar;

               echo $this->display_student_results = '

               <div class="col">
               
               <div class="d-flex shadow p-4 mb-4 bg-light" style="width:300px">

                    <div class="flex-shrink-0">
                        <img class="rounded-circle" src='.$avatar_file.'
                        style="border:5px solid white;padding:1px;max-height:60px;
                        border-radius:30px;max-width:60px;"/>
                    </div>

                    <div class="flex-grow-1 ms-3">

                       <h5 style="max-width:200px;white-space:nowrap;overflow:hidden;
                       text-overflow:ellipsis;">'.$this->student_fullname.'</h5>

                       <p>
                          '.$this->student_matric_number.'<br>
                          <span class="text-muted"><small><b>@'.$this->student_username.'</b></small></span>
                   
                        </p>

                    </div>

                </div>
                <!-- course container flex display -->

                </div>
               
               ';

            }

            echo '</div>';

        }
        
    }

}

?>