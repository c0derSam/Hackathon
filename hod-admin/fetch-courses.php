<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : hod-admin/fetch-courses.php

** About : this module displays fetches all the courses from the database

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the fetch courses class
 * define the fetch courses query
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//initiate the fetch courses class
class fetchCourses{

    public $status;

    public $courses_rows;

    //define the fetch courses query
    public function fetchAllCourses(){

        include('../resources/database/courses-db-connection.php');

       $fetch_courses = "SELECT * FROM courses";

       $fetch_courses_result = $conn8->query($fetch_courses);

       if($fetch_courses_result->num_rows > 0){

            $this->status = "true";

       }else{

            $this->status = "false";

       }

       CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
        ]));
            
        $InstanceCache = CacheManager::getInstance('files');
            
        $key = "all_courses";
        $Cached_lecturer_courses_row = $InstanceCache->getItem($key);
            
        if (!$Cached_lecturer_courses_row->isHit()) {
            $Cached_lecturer_courses_row->set($fetch_courses_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_lecturer_courses_row); // Save the cache item just like you do with doctrine and entities
            
            $cached_courses = $Cached_lecturer_courses_row->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
            
        } else {
                
            $cached_courses = $Cached_lecturer_courses_row->get();
            //echo 'READ FROM CACHE // ';
            
            $InstanceCache->deleteItem($key);

        }

        while($row = $cached_courses->fetch_assoc()){

            $this->courses_rows[] = $row;

        }

    }

}

$fetch_courses = new fetchCourses();

$fetch_courses->fetchAllCourses();

?>