<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : hod-admin/fetch-300level-students.php

** About : this module displays fetches all the students in 300 level from the database

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the fetch 300 level students class
 * fetch the all the 300 level students query
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

class fetchThirdLevelStudents{

    public $status;

    public $third_level_students_row;

    public function fetchThirdLevelStudents(){

        include('../resources/database/users-db-connection.php');//conn1 

       $fetch_students_data_query = "SELECT * FROM students WHERE level = '300'";

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
    
        $key = "300level_all_students";
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

            $this->third_level_students_rows[] = $row;

        }

    }

}

$fetch_third_level_students = new fetchThirdLevelStudents();

$fetch_third_level_students->fetchThirdLevelStudents();

?>