<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : hod-admin/dashboard.php

** About : this module displays the main dashboard of the hod

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the dashboard class
 * display the header
 * define the dashboard page
 * cache the dashboard page
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

session_start();

class dashboard{

    public $dashboard_page;

    public function displayHeader(){

        include("dashboard-header/header.php");

    }

    public function dashboardPage(){

        include('fetch-counted-lecturer.php');

        include('fetch-counted-students.php');

        include('fetch-counted-courses.php');

        $this->dashboard_page = '
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

                <h1 class="h2">Dashboard <i class="fa fa-dashboard"></i></h1>
        
            </div>

            <div class="row">

                <div class="col">

                    <div class="card w-50 p-3 shadow-lg mb-4 bg-white">

                            <a href="lecturers-analytics.php" class="text-dark" style="text-decoration:none;">
                            <div class="col">

                                <span style="font-size:20px;">
                                    <i class="fa fa-mortar-board"></i>
                                    <hr style="width:50px;">
                                    Lecturers
                                </span>

                            </div>

                            <div class="col" style="font-size:50px;">
                                '.$fetch_counted_lecturers->counted_lecturers.'
                            </div>
                            </a>

                    </div>

                </div>

                <div class="col">

                    <div class="card w-50 p-3 shadow-lg mb-4 bg-white">
                        
                            <a href="#students" class="text-dark" style="text-decoration:none;">
                            <div class="col">

                                <span style="font-size:20px;">
                                    <i class="fa fa-users"></i>
                                    <hr style="width:50px;">
                                    Students
                                </span>

                            </div>

                            <div class="col" style="font-size:50px;">
                                '.$fetch_counted_students->counted_students.'
                            </div>
                            </a>

                    </div>

                </div>

                <div class="col">

                    <div class="card w-50 p-3 shadow-lg mb-4 bg-white">
                        
                            <a href="courses-analytics.php" class="text-dark" style="text-decoration:none;">
                            <div class="col">

                                <span style="font-size:20px;">
                                    <i class="fa fa-list"></i>
                                    <hr style="width:50px;">
                                    Courses
                                </span>

                            </div>

                            <div class="col" style="font-size:50px;">
                                '.$fetch_counted_courses->counted_courses.'
                            </div>

                    </div>

                </div>

            </div>

            <hr>

            <br>

            <div id="students" class="card p-3 shadow-lg mb-4 bg-white">

                <h2 class="h3">Students by levels </h3>

                <div class="row">

                    <div class="col">

                        <a href="100level-students.php" class="text-dark" style="text-decoration:none;">
                        <div class="card w-5 p-3 shadow-lg mb-4 bg-white">

                            <div class="col">

                                <span style="font-size:20px;">
                                    <i class="fa fa-users"></i>
                                    <hr style="width:50px;">
                                    100 level
                                </span>

                            </div>

                            <div class="col" style="font-size:50px;">
                                '.$fetch_counted_students->counted_first_level_students.'
                            </div>

                        </div>
                        </a>

                    </div>
 
                    <div class="col">

                        <a href="200level-students.php" class="text-dark" style="text-decoration:none;">
                        <div class="card w-5 p-3 shadow-lg mb-4 bg-white">

                            <div class="col">

                                <span style="font-size:20px;">
                                    <i class="fa fa-users"></i>
                                    <hr style="width:50px;">
                                    200 level
                                </span>

                            </div>

                            <div class="col" style="font-size:50px;">
                                '.$fetch_counted_students->counted_second_level_students.'
                            </div>

                        </div>
                        </a>

                    </div>

                    <div class="col">

                        <a href="300level-students.php" class="text-dark" style="text-decoration:none;">
                        <div class="card w-5 p-3 shadow-lg mb-4 bg-white">

                            <div class="col">

                                <span style="font-size:20px;">
                                    <i class="fa fa-users"></i>
                                    <hr style="width:50px;">
                                    300 level
                                </span>

                            </div>

                            <div class="col" style="font-size:50px;">
                                '.$fetch_counted_students->counted_third_level_students.'
                            </div>

                        </div>
                        </a>

                    </div>

                    <div class="col">

                    <a href="400level-students.php" class="text-dark" style="text-decoration:none;">
                        <div class="card w-5 p-3 shadow-lg mb-4 bg-white">

                            <div class="col">

                                <span style="font-size:20px;">
                                    <i class="fa fa-users"></i>
                                    <hr style="width:50px;">
                                    400 level
                                </span>

                            </div>

                            <div class="col" style="font-size:50px;">
                                '.$fetch_counted_students->counted_fourth_level_students.'
                            </div>

                        </div>

                    </div>
                    </a>

                </div>

            </div>

        </main>
        
        ';

    }

    //cache the dashboard page
    public function cacheDashboardPage(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "dashboard-page";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->dashboard_page)->expiresAfter(1);//in seconds, also accepts Datetime
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

$dashboard = new dashboard();

if(isset($_SESSION['hod_session'])){

    $dashboard->displayHeader();

    $dashboard->dashboardPage();

    $dashboard->cacheDashboardPage();

}else{

    header("location:index.php");

}

?>