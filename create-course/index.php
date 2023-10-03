<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : create-course/index.php

** About : this module displays the index view of to create courses

*/

/**PSUEDO ALGORITHM
 * *
 * define the index class view
 * fetch the user session
 * display the create course heading
 * display the create course sub-heading
 * cache the subheading
 * display the course form
 * cache the course form 
 * perform administrative functions
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

session_start();

//define the index class view
class indexView{

    public $sub_heading;

    public $course_form;

    //display the create course heading
    public function heading(){

       include('header/header.php');

    }

    //display the create course sub-heading
    public function subHeading(){

        $this->sub_heading = '
 
         <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
         align="center">
 
             <br>
 
             <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
    
                Create Course <i class="fa fa-plus-circle"></i>
    
             </h1>
 
             <br>
 
         </div>
 
         <br>
 
         <div class="create-course" align="center">
 
            <div class="card shadow p-4 mb-4 bg-light" style="width:200px;">
 
                <a class="text-white" href="../dashboard/lecturer-dashboard" style="text-decoration:none;">

                    <button class="btn btn-md text-white" style="background-color:#1d007e;">

                    <i class="fa fa-arrow-left"></i> Back to home

                    </button>

                </a>
 
 
            </div>
 
         </div>
         <!-- create course container-->
 
         <br>
 
 
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

    //display the course form
    public function courseForm(){

        $this->course_form = '

        <div class="container">

            <div align="center">
        
                <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

                    <form action="create-course-processor/create-course-controller.php" method="POST">

                       <input class="form-control" type="text" name="courseCode" placeholder="Course code" 
                       style="height:40px;" required/>

                       <br>
      
                       <input class="form-control" type="text" name="courseTitle" placeholder="Course title" 
                       style="height:40px;" required/>

                       <br>

                        <select class="form-control" name="level">

                          <option>Level</option>
                          <option>100</option>
                          <option>200</option>
                          <option>300</option>
                          <option>400</option>
 
                        </select>

                       <br>

                       <textarea class="form-control" name="aboutCourse" rows="6" 
                       placeholder="About course" required></textarea>

                       <br>

                       <textarea class="form-control" name="courseOutline" rows="6" 
                       placeholder="Course outline" required></textarea>

                       <br>

                        <button class="btn btn-md text-white" style="background-color:#1d007e;">

                           Create course <i class="fa fa-plus-circle"></i>

                        </button>

                    </form>
 
                </div>

            </div> <!-- centered align container -->

        </div> <!-- container -->
        
        ';

    }

    //cache the course form
    public function cachedCourseForm(){


        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "course-form";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->course_form)->expiresAfter(1);//in seconds, also accepts Datetime
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

$index_view = new indexView();

session_regenerate_id(true);

if(isset($_SESSION['lecturer_session'])){

    $index_view->heading();

    $index_view->subHeading();

    $index_view->cachedSubHeading();

    $index_view->coursefORM();

    $index_view->cachedCourseForm();

}else{

    header('location:logout-success.php');

}




?>