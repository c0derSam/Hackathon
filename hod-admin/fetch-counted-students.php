<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : hod-admin/fetch-counted-students.php

** About : this module displays fetches the counted students by levels from the database

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the fetch counted students class
 * fetch the counted students total
 * fetch the counted 100 level students total
 * fetch the counted 200 level students total
 * fetch the counted 300 level students total
 * fetch the counted 400 level students total
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//initiate the fetch counted students class
class fetchCountedStudents{

    public $counted_students;

    public $counted_first_level_students;

    public $counted_second_level_students;

    public $counted_third_level_students;

    public $counted_fourth_level_students;

    //fetch the counted students total
    public function fetchCountedStudentsTotal(){

        // user db connection
       include('../resources/database/users-db-connection.php');//conn1 

       $fetch_students_data_query = "SELECT count(*) AS countedStudents FROM students";

       $fetch_students_data_result = $conn1->query($fetch_students_data_query);

       CacheManager::setDefaultConfig(new ConfigurationOption([
        'path' => '', // or in windows "C:/tmp/"
        ]));
    
        $InstanceCache = CacheManager::getInstance('files');
    
        $key = "counted_students";
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

        $counted_students_row = $cached_student_result->fetch_assoc();

        $this->counted_students = $counted_students_row['countedStudents'];

        mysqli_close($conn1);

    }

    //fetch the counted 100 level students total
    public function fetchCountedFirstLevelStudents(){

        // user db connection
       include('../resources/database/users-db-connection.php');//conn1 

       $fetch_students_data_query = "SELECT count(*) AS countedStudents FROM students WHERE level = '100'";

       $fetch_students_data_result = $conn1->query($fetch_students_data_query);

       CacheManager::setDefaultConfig(new ConfigurationOption([
        'path' => '', // or in windows "C:/tmp/"
        ]));
    
        $InstanceCache = CacheManager::getInstance('files');
    
        $key = "counted_100level_students";
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

        $counted_students_row = $cached_student_result->fetch_assoc();

        $this->counted_first_level_students = $counted_students_row['countedStudents'];

        mysqli_close($conn1);

    }

    //fetch the counted 200 level students total
    public function fetchCountedSecondLevelStudents(){

        // user db connection
       include('../resources/database/users-db-connection.php');//conn1 

       $fetch_students_data_query = "SELECT count(*) AS countedStudents FROM students WHERE level = '200'";

       $fetch_students_data_result = $conn1->query($fetch_students_data_query);

       CacheManager::setDefaultConfig(new ConfigurationOption([
        'path' => '', // or in windows "C:/tmp/"
        ]));
    
        $InstanceCache = CacheManager::getInstance('files');
    
        $key = "counted_200level_students";
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

        $counted_students_row = $cached_student_result->fetch_assoc();

        $this->counted_second_level_students = $counted_students_row['countedStudents'];

        mysqli_close($conn1);

    }

    //fetch the counted 300 level students total
    public function fetchCountedThirdLevelStudents(){

        // user db connection
       include('../resources/database/users-db-connection.php');//conn1 

       $fetch_students_data_query = "SELECT count(*) AS countedStudents FROM students WHERE level = '300'";

       $fetch_students_data_result = $conn1->query($fetch_students_data_query);

       CacheManager::setDefaultConfig(new ConfigurationOption([
        'path' => '', // or in windows "C:/tmp/"
        ]));
    
        $InstanceCache = CacheManager::getInstance('files');
    
        $key = "counted_300level_students";
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

        $counted_students_row = $cached_student_result->fetch_assoc();

        $this->counted_third_level_students = $counted_students_row['countedStudents'];

        mysqli_close($conn1);

    }

    //fetch the counted 400 level students Total
    public function fetchCountedFourthLevelStudents(){

        // user db connection
       include('../resources/database/users-db-connection.php');//conn1 

       $fetch_students_data_query = "SELECT count(*) AS countedStudents FROM students WHERE level = '400'";

       $fetch_students_data_result = $conn1->query($fetch_students_data_query);

       CacheManager::setDefaultConfig(new ConfigurationOption([
        'path' => '', // or in windows "C:/tmp/"
        ]));
    
        $InstanceCache = CacheManager::getInstance('files');
    
        $key = "counted_400level_students";
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

        $counted_students_row = $cached_student_result->fetch_assoc();

        $this->counted_fourth_level_students = $counted_students_row['countedStudents'];

        mysqli_close($conn1);

    }


}

$fetch_counted_students = new fetchCountedStudents();

$fetch_counted_students->fetchCountedStudentsTotal();

$fetch_counted_students->fetchCountedFirstLevelStudents();

$fetch_counted_students->fetchCountedSecondLevelStudents();

$fetch_counted_students->fetchCountedThirdLevelStudents();

$fetch_counted_students->fetchCountedFourthLevelStudents();

?>