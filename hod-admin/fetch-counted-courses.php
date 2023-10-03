<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : hod-admin/fetch-counted-courses.php

** About : this module displays fetches the counted courses from the database

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the fetch counted courses class
 * fetch the counted courses total
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;


class fetchCountedCourses{

    public $counted_courses;

    //fetch the counted courses total
    public function fetchCountedCoursesTotal(){

        include('../resources/database/courses-db-connection.php');

       $fetch_counted_courses = "SELECT count(*) AS countedCourses FROM courses";

       $fetch_counted_courses_result = $conn8->query($fetch_counted_courses);

       CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
        ]));
            
        $InstanceCache = CacheManager::getInstance('files');
            
        $key = "counted_courses";
        $Cached_lecturer_courses_row = $InstanceCache->getItem($key);
            
        if (!$Cached_lecturer_courses_row->isHit()) {
            $Cached_lecturer_courses_row->set($fetch_counted_courses_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_lecturer_courses_row); // Save the cache item just like you do with doctrine and entities
            
            $cached_courses = $Cached_lecturer_courses_row->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
            
        } else {
                
            $cached_courses = $Cached_lecturer_courses_row->get();
            //echo 'READ FROM CACHE // ';
            
            $InstanceCache->deleteItem($key);

        }

        $counted_courses_row = $cached_courses->fetch_assoc();

        $this->counted_courses = $counted_courses_row['countedCourses'];

        mysqli_close($conn8);

    }

}

$fetch_counted_courses = new fetchCountedCourses();

$fetch_counted_courses->fetchCountedCoursesTotal();

?>