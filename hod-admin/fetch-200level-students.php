<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : hod-admin/fetch-200level-students.php

** About : this module displays fetches all the students in 200 level from the database

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the fetch 200 level students class
 * fetch the all the 200 level students query
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//initiate the fetch 200 level students class
class fetchSecondLevelStudents{

    public $status;

    public $second_level_students_rows;

    //fetch the all the 200 level students query
    public function fetchSecondLevelStudents(){

        include('../resources/database/users-db-connection.php');//conn1 

       $fetch_students_data_query = "SELECT * FROM students WHERE level = '200'";

       $fetch_students_data_result = $conn1->query($fetch_students_data_query);

       if($fetch_students_data_result->num_rows > 0){

            $this->status = "true";

       }else{

            $this->status = "false";

       }

       CacheManager::setDefaultConfig(new ConfigurationOption([
        'path' => '', // or in windows "C:/tmp/"
        ]));
    
        $InstanceCache = CacheManager::getInstance('files');
    
        $key = "200level_all_students";
        $Cached_student_data_result = $InstanceCache->getItem($key);
    
        if (!$Cached_student_data_result->isHit()) {
            $Cached_student_data_result->set($fetch_students_data_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_student_data_result); // Save the cache item just like you do with doctrine and entities
    
            $cached_student_result = $Cached_student_data_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
    
        } else {
        
            $cached_student_result = $Cached_student_data_result->get();
            //echo 'READ FROM CACHE // ';
    
            $InstanceCache->deleteItem($key);
        }

        while($row = $cached_student_result->fetch_assoc()){

            $this->second_level_students_rows[] = $row;

        }

    }

}

$fetch_second_level_students = new fetchSecondLevelStudents();

$fetch_second_level_students->fetchSecondLevelStudents();

?>