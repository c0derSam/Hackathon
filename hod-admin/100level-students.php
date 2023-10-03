<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : hod-admin/100level-students.php

** About : this module displays 100 level students analytics

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the 100 level students analytics class
 * display the header
 * define the 100 level students page
 * cache the 100 level students analytics page
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

session_start();

class firstLevelStudentsAnlytics{

    public $first_level_students_page;

    //display the header
    public function displayHeader(){

        include("students-header/header.php");

    }
 
    //define the 100 level students page
    public function firstLevelStudentsPage(){

        include('fetch-counted-students.php');

        echo $this->first_level_students_page = '
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

                <h1 class="h2">100 Level Students <i class="fa fa-users"></i></h1>
        
            </div>

            <div align="center" style="font-size:60px;">

                <div class="card shadow bg-white">

                <i class="fa fa-users"></i> '.$fetch_counted_students->counted_first_level_students.' Student(s)

                </div>

            </div>

            <hr>

            <br>

            <div class="card p-3 shadow-lg mb-4 bg-white">

                <div class="row">';

                include('fetch-100level-students.php');

                if($fetch_first_level_student->status == "true"){

                    foreach($fetch_first_level_student->first_level_students_rows as $student_row){

                        $fullname = $student_row['fullname'];

                        $matric_number = $student_row['matric_number'];

                        $username = $student_row['username'];

                        $avatar = $student_row['avatar'];

                        $avatar_file_name = '../resources/avatars/'.$avatar;

                        echo '
                        <div class="col">

                            <div class="d-flex shadow p-4 mb-4 bg-light" style="width:300px">

                                <div class="flex-shrink-0">
                                    <img class="rounded-circle" src='.$avatar_file_name.' 
                                    style="border:5px solid white;padding:1px;max-height:60px;
                                    border-radius:30px;max-width:60px;"/>
                                </div>

                                <div class="flex-grow-1 ms-3">

                                    <h5 style="max-width:200px;white-space:nowrap;overflow:hidden;
                                    text-overflow:ellipsis;">'.$fullname.'</h5>

                                    <p>
                                        '.$matric_number.'<br>
                                        <span class="text-muted"><small><b>@'.$username.'</b></small></span>
                   
                                    </p>

                                </div>

                            </div>
                            <!-- course container flex display -->

                        </div>';

                    }

                }
                
                echo '</div>

            </div>

        </main>
        
        ';

    }

    //cache the 100 level students analytics page
    public function cacheFirstLevelStudentPage(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "first-level-analytics-page";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->first_level_students_page)->expiresAfter(1);//in seconds, also accepts Datetime
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

$first_level_students_analytics = new firstLevelStudentsAnlytics();

if(isset($_SESSION['hod_session'])){

    $first_level_students_analytics->displayHeader();

    $first_level_students_analytics->firstLevelStudentsPage();

    //$first_level_students_analytics->cacheFirstLevelStudentPage();

}

?>