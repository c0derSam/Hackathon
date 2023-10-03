<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : hod-admin/fetch-lecturers.php

** About : this module displays fetches all the lecturers from the database

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the fetch lecturers class
 * define the fetch lecturers query
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

class fetchLecturers{

    public $lecturers_rows;

    public function fetchLecturersQuery(){

        include('../resources/database/users-db-connection.php');//conn1 

       $fetch_lecturers_data_query = "SELECT * FROM lecturers";

       $fetch_lecturers_data_result = $conn1->query($fetch_lecturers_data_query);

       CacheManager::setDefaultConfig(new ConfigurationOption([
        'path' => '', // or in windows "C:/tmp/"
        ]));
     
        $InstanceCache = CacheManager::getInstance('files');
    
        $key = "lecturers_all_result";
        $Cached_lecturer_data_result = $InstanceCache->getItem($key);
    
        if (!$Cached_lecturer_data_result->isHit()) {
            $Cached_lecturer_data_result->set($fetch_lecturers_data_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_lecturer_data_result); // Save the cache item just like you do with doctrine and entities
    
            $cached_lecturer_result = $Cached_lecturer_data_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
    
        } else {
        
            $cached_lecturer_result = $Cached_lecturer_data_result->get();
            //echo 'READ FROM CACHE // ';
    
            $InstanceCache->deleteItem($key);
        }

        while($row = $cached_lecturer_result->fetch_assoc()){

            $this->lecturers_rows[] = $row;

        }

    }

}

$fetch_lecturers = new fetchLecturers();

$fetch_lecturers->fetchLecturersQuery();

?>