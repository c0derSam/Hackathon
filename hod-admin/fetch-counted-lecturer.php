<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : hod-admin/fetch-counted-lecturer.php

** About : this module displays fetches the counted lecturers from the database

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the fetch counted lecturers class
 * define the fetch counted lecturers query
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//initiate the fetch counted lecturers class
class fetchCountedLecturers{

    public $counted_lecturers;

    //define the fetch counted lecturers query
    public function fetchCountedLecturerQuery(){

        // user db connection
       include('../resources/database/users-db-connection.php');//conn1 

       $fetch_lecturer_data_query = "SELECT count(*) AS countedLecturers FROM lecturers";

       $fetch_lecturer_data_result = $conn1->query($fetch_lecturer_data_query);

       CacheManager::setDefaultConfig(new ConfigurationOption([
        'path' => '', // or in windows "C:/tmp/"
        ]));
      
        $InstanceCache = CacheManager::getInstance('files');
    
        $key = "counted_lecturer_result";
        $Cached_lecturer_data_result = $InstanceCache->getItem($key);
    
        if (!$Cached_lecturer_data_result->isHit()) {
            $Cached_lecturer_data_result->set($fetch_lecturer_data_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_lecturer_data_result); // Save the cache item just like you do with doctrine and entities
    
            $cached_lecturer_result = $Cached_lecturer_data_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
    
        } else {
        
            $cached_lecturer_result = $Cached_lecturer_data_result->get();
            //echo 'READ FROM CACHE // ';
    
            $InstanceCache->deleteItem($key);
        }

        $counted_lecturer_row = $cached_lecturer_result->fetch_assoc();

        $this->counted_lecturers = $counted_lecturer_row['countedLecturers'];

        mysqli_close($conn1);

    }

}

$fetch_counted_lecturers = new fetchCountedLecturers();

$fetch_counted_lecturers->fetchCountedLecturerQuery();

?>