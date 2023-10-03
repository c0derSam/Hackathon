<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : hod-admin/lecturers-analytics.php

** About : this module displays the lecturers analytics

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the lecturers analytics class
 * display the header
 * define the lecturers analytics page
 * cache the lecturers analytics page
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

session_start();

class lecturerAnylytics{

    public $lecturer_analytics_page;

    //display the header
    public function displayHeader(){

        include("lecturer-header/header.php");

    }

    //define the lecturers analytics page
    public function lecturerAnalyticsPage(){

        include('fetch-counted-lecturer.php');

        echo $this->lecturer_analytics_page = '
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

                <h1 class="h2">Lecturers <i class="fa fa-mortar-board"></i></h1>
        
            </div>

            <div align="center" style="font-size:60px;">

                <div class="card shadow bg-white">

                <i class="fa fa-mortar-board"></i> '.$fetch_counted_lecturers->counted_lecturers.' Lecturer(s)

                </div>

            </div>

            <hr>

            <br>

            <div class="card p-3 shadow-lg mb-4 bg-white">

                <div class="row">';

                include('fetch-lecturers.php');

                foreach($fetch_lecturers->lecturers_rows as $each_lecturer_row){

                    $title = $each_lecturer_row['title'];

                    $fullname = $each_lecturer_row['fullname'];

                    $username = $each_lecturer_row['username'];

                    $avatar_name = $each_lecturer_row['avatar'];

                    $avatar_file = '../resources/avatars/'.$avatar_name;

                    echo '<div class="col">

                        <div class="d-flex shadow p-4 mb-4 bg-light" style="width:300px">

                            <div class="flex-shrink-0">
                                <img class="rounded-circle" src='.$avatar_file.' 
                                style="border:5px solid white;padding:1px;max-height:60px;
                                border-radius:30px;max-width:60px;"/>
                            </div>

                            <div class="flex-grow-1 ms-3">

                                <h5 style="max-width:200px;white-space:nowrap;overflow:hidden;
                                text-overflow:ellipsis;">'.$title.' '.$fullname.'</h5>

                                <p>
                                    <span class="text-muted"><small><b>@'.$username.'</b></small></span>
                   
                                </p>

                            </div>

                        </div>
                        <!-- course container flex display -->

                    </div>';

                }

                echo '</div>

            </div>

        </main>';
        

    }

    //cache the lecturers analytics page
    public function cacheLecturersAnalyticsPage(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "lecturers-analytics-page";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->lecturer_analytics_page)->expiresAfter(1);//in seconds, also accepts Datetime
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

$lecturer_analytics = new lecturerAnylytics();

if(isset($_SESSION['hod_session'])){

    $lecturer_analytics->displayHeader();

    $lecturer_analytics->lecturerAnalyticsPage();

    //$lecturer_analytics->cacheLecturersAnalyticsPage();

}

?>