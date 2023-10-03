<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/create-classroom/index.php

** About : this module displays the index view of the create classroom form

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the index class view
 * fetch course id
 * include the header
 * display the create classroom form
 * cache the create classroom form
 * perform administrative functions
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

    public $course_code;

    public $create_classroom_form;

    public function fetchCourseId(){

        $this->course_id = $_GET['id'];

        $this->course_code = $_GET['courseCode'];

    }

    public function heading(){

        include('../header/header.php');
 
    }

    //display the create classroom form
    public function createClassroom(){

        $this->create_classroom_form = '
        
        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
         align="center">
 
             <br>
 
             <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
    
                Create Classroom <i class="fa fa-plus-circle"></i>
    
             </h1>
 
             <br>
 
         </div>
 
         <br>
 
         <div align="center">
 
             <div class="card shadow p-4 mb-4 bg-light" style="width:200px;">
 
                 <a class="text-white" href="../courses-dashboard/index.php?id='.$this->course_id.'" style="text-decoration:none;">
 
                     <button class="btn btn-md text-white" style="background-color:#1d007e;">
 
                     <i class="fa fa-arrow-left"></i> Back to course
 
                     </button>
 
                 </a>
 
             </div>

             <br>

             <div class="container">

                <div align="center">

                    <form action="create-classroom-controller.php" method="POST" enctype="multipart/form-data">
           
                        <ul class="nav nav-tabs justify-content-center">

                           <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#form1">Step 
                                   <span class="badge" style="background-color:#1d007e;">1</span>
                                </a>
                           </li>

                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#form2">Step 
                                    <span class="badge" style="background-color:#1d007e;">2</span>
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#form3"> Step
                                   <span class="badge" style="background-color:#1d007e;">3</span>
                                </a>
                            </li>
                        </ul>
                        <!-- input tabs -->

                        <div class="tab-content">

                            <div class="tab-pane container active" id="form1" align="center">

                                <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

                                    <input class="form-control" type="text" name="topic" placeholder="Class topic" required
                                    style="height:40px;">

                                    <br>

                                    <textarea class="form-control" name="about" rows="2" placeholder="About class"></textarea>

                                    <br>

                                    <button class="btn btn-md text-light" style="background-color:#1d007e;">
                                       Click on step 2
                                    </button>

                                </div>

                            </div>
                            <!-- form 1 -->

                            <div class="tab-pane container" id="form2" align="center">

                                <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

                                    <textarea class="form-control" name="classNote" rows="10" placeholder="Class Note"></textarea>

                                    <br><br>

                                    <button class="btn btn-md text-light" style="background-color:#1d007e;">
                                       Click on step 3
                                    </button>

                                </div>

                            </div>
                            <!-- form 2 -->

                            <div class="tab-pane container" id="form3" align="center">

                                <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

                                    <textarea class="form-control" name="assignment" rows="4" placeholder="Assignment question"></textarea>

                                    <br>

                                    <label align="center" for="avatar" class="text-secondary"><b>Due date</b>
                                    </label>
                                    <input class="form-control" type="date" name="dueDate" placeholder="Class topic" required
                                    style="height:40px;">

                                    <input type="hidden" name="courseEncryptedId" value='.$this->course_id.'>

                                    <input type="hidden" name="courseCode" value='.$this->course_code.'>

                                    <br>

                                    <button type="submit" class="btn btn-md text-light" style="background-color:#1d007e;">
                                       Create <i class="fa fa-plus-circle"></i>
                                    </button>

                                </div>

                            </div>
                            <!-- form 3 -->


                        </div>
                        <! tab content -->

                    </form>

                </div>

             </div>
             <!-- container -->
 
         </div>
         <!-- create container-->

        ';

    }

    //cache the create classroom form
    public function cacheClassroomForm(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "create_classroom_form";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->create_classroom_form)->expiresAfter(1);//in seconds, also accepts Datetime
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

$index_view->fetchCourseId();

if(isset($_SESSION['lecturer_session']) && !empty($index_view->course_id)){

    $index_view->heading();

    $index_view->createClassroom();

    $index_view->cacheClassroomForm();


}else{

   header('location:logout-success.php');

}

?>