<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : dashboard/lecturer-dashboard/index.php.php

** About : this module displays the index view of the lecturer dashboard

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the index class view
 * fetch the user session
 * display the dashboard heading
 * display the dashboard sub-heading
 * cache the subheading
 * display the lecturer dashboard
 * perform administartive functions
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

session_start();

//define the index class view
class indexView{

    public $sub_heading;

    //display the dashboard heading
    public function heading(){

       include('../header/header.php');

    }

    //display the dashboard sub-heading
    public function subHeading(){

       $this->sub_heading = '

        <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

            <br>

            <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
   
               Home <i class="fa fa-home"></i>
   
            </h1>

            <br>

        </div>

        <br>

        <div class="create-course" align="center">

            <div class="card shadow p-4 mb-4 bg-light" style="width:200px;">

                <a class="text-white" href="../../create-course" style="text-decoration:none;">

                    <button class="btn btn-md text-white" style="background-color:#1d007e;">

                       Create Course <i class="fa fa-plus-circle"></i>

                    </button>

                </a>

            </div>

        </div>
        <!-- create course container-->

        <br>

        <div class="container">

            <h3>Courses <i class="fa fa-mortar-board"></i></h3>

            <hr>

        

       ';


    }

    public function deleteCourseAlert(){

        echo '
        
        <div class="alert alert-info" style="width:200px;height:80px;">
 
                <div class="row">
 
                     <div class="col">
                    
                         Course Deleted
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
        </div>

        </div>
        
        ';

    }

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

    //display the lecturer dashboard
    public function dashboard(){

        echo '

        <div class="container">
        
        ';

        include('lecturer-dashboard-controller.php');

    }

}

$index_view = new indexView();

session_regenerate_id(true);

if(isset($_SESSION['lecturer_session'])){

    $index_view->heading();

    $index_view->subHeading();

    $index_view->cachedSubHeading();
    
    if(!empty($_GET['deleteCourseAlert'])){

        $index_view->deleteCourseAlert();

    }

    $index_view->dashboard();

}else{

    header("location:logout-success.php");

}


?>