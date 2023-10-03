<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : hod-admin/courses-analytics.php

** About : this module displays the courses analytics

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the courses analytics class
 * display the header
 * define the courses analytics page
 * cache the courses analytics page
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

session_start();

class coursesAnalytics{

    public $course_analytics_page;

    //display the header
    public function displayHeader(){

        include("courses-header/header.php");

    }

    //define the courses analytics page
    public function courseAnalyticsPage(){

        include('fetch-counted-courses.php');

        echo $this->course_analytics_page = '
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">

                <h1 class="h2">Courses <i class="fa fa-list"></i></h1>
        
            </div>

            <div align="center" style="font-size:60px;">

                <div class="card shadow bg-white">

                <i class="fa fa-list"></i> '.$fetch_counted_courses->counted_courses.' Course(s)

                </div>
 
            </div>

            <hr>

            <br>

            <div class="card p-3 shadow-lg mb-4 bg-white">

                <div class="row">';

                include('fetch-courses.php');

                if($fetch_courses->status == "true"){

                    foreach($fetch_courses->courses_rows as $courses_row){
 
                        $course_code = $courses_row['course_code'];
                        
                        $course_title = $courses_row['course_title'];

                        $about_course = $courses_row['about_course'];

                        $lecturer_in_charge = $courses_row['lecturer_in_charge'];

                        $course_encrypted_id = $courses_row['course_encrypted_id'];

                        echo '<div class="col">

                        <div class="d-flex shadow p-4 mb-4 bg-light" style="width:300px">

                            <div class="flex-shrink-0">
                                <i class="fa fa-mortar-board" style="font-size:30px;"></i>
                            </div>

                            <div class="flex-grow-1 ms-3">

                                <h5>'.$course_code.'</h5>

                                <p style="max-width:200px;white-space:nowrap;overflow:hidden;
                                text-overflow:ellipsis;">
                                    '.$course_title.'<br> 

                                    <span class="text-muted"><small><b>Created by '.$lecturer_in_charge.'</b></small></span>
                   
                                </p>

                                <p style="max-width:200px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                    '.$about_course.'
                                </p>

                                <button class="btn btn-outline-dark">

                                    <a class="text-dark" href="../courses/courses-dashboard/index.php?id='.$course_encrypted_id.'" 
                                    style="text-decoration:none;" target="_blank">
                          
                                        view course <i class="fa fa-external-link"></i>

                                    </a>

                                </button>

                                

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

}

$courses_analytics = new coursesAnalytics();

if(isset($_SESSION['hod_session'])){

    $courses_analytics->displayHeader();

    $courses_analytics->courseAnalyticsPage();

}

?>