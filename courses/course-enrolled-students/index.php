<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/course-enrolled-students/index.php

** About : this module displays the index view if the courses enrolled students

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the index class view
 * fetch the user session
 * fetch the course id
 * display the heading
 * display the sub-heading
 * cache the subheading
 * display the course enrolled students model
 * perform administartive functions
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

session_start();

class indexView{

    public $course_id;

    public $enrolled_total;

    public $sub_heading;

    public function courseData(){

       $this->course_id = $_GET['id'];

       $this->enrolled_total = base64_decode($_GET['total']);

    }

    //display the dashboard heading
    public function heading(){

       include('../header/header.php');

    }

    public function subHeading(){

        $this->sub_heading = '
 
         <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
         align="center">
 
             <br>
 
             <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
    
                Enrolled Students <i class="fa fa-users"></i>
    
             </h1>
 
             <br>
 
         </div>
 
         <br>
 
         <div class="create-course" align="center">
 
             <div class="card shadow p-4 mb-4 bg-light" style="width:200px;">
 
                 <a class="text-white" href="../courses-dashboard/index.php?id='.$this->course_id.'" 
                 style="text-decoration:none;">
 
                     <button class="btn btn-md text-white" style="background-color:#1d007e;">
 
                     <i class="fa fa-arrow-left"></i> Back to course
 
                     </button>
 
                 </a>
 
             </div>
 
         </div>
         <!-- create course container-->
 
         <br>
 
         <div class="container">
 
             <h3><span class="badge bg-dark text-light">'.$this->enrolled_total.'</span> Enrolled</h3>
 
             <hr>
 
             <div class="row">
 
         </div>
 
        ';
 
    }

    //cache the subheading
    public function cachedSubHeading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "subheading";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->sub_heading)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    //display the course enrolled students model
    public function enrolledStudents(){

        include('course-enrolled-students-controller.php');

    }

}

$index_view = new indexView();

session_regenerate_id(true);

if(isset($_SESSION['lecturer_session']) or isset($_SESSION['student_session']) && !empty($_GET['id']) && !empty($_GET['total'])){

    $index_view->courseData();

    $index_view->heading();

    $index_view->subHeading();

    $index_view->cachedSubHeading();

    $index_view->enrolledStudents();


}else{

    header('location:logout-success.php');

}

?>