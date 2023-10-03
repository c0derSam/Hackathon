<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : login/student-auth/student-auth-model.php

** About : this module logs the student into the edtech classroom

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the student login class
 * fetch the login in data from the student login page
 * sanitize the logged in data
 * encrypt the student password
 * performing administartive functions
 * authenticating the user login details through a database query
 * caching the student authentication query
 * updating the user login status 
 * starting the user session and redirecting the user to the edtech classroom dashboard
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

class studentLogin{

    public $matric_number;

    public $password;


    public $sanitized_matric_number;

    public $sanitized_password;

    
    public $encrypted_password;

    
    public $auth_student_row;
    public $encrypted_id;

    public $login_status;

    //fetch the login in data from the student login page
    public function fetchStudentData(){

        $this->matric_number = $_POST['matricNumber'];

        $this->password = $_POST['password'];

    }

    //sanitize the logged in data
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = htmlentities($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //encrypt the student password
    public function encryptStudentPassword(){

        $this->encrypted_password = md5($this->password);

    }

    //performing administartive functions

    //authenticating the user login details through a database query
    public function authStudent(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/users-db-connection.php');

        $auth_student_query = "SELECT * FROM students WHERE matric_number = '$this->sanitized_matric_number' && 
        password = '$this->encrypted_password'";

        $auth_student_result = $conn1->query($auth_student_query);

        if($auth_student_result->num_rows > 0){

            $this->auth_student_row = $auth_student_result->fetch_assoc();

            $this->login_status = TRUE;

        }else{

            $this->login_status = FALSE;

            //echo "Wrong username or password";
            include('student-auth-error.php');
        }

    }

    //caching the student authentication query
    public function cacheStudentAuth(){

        if($this->login_status == TRUE){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');

        $key = "student_auth_cache";
        $Cached_auth_student_row = $InstanceCache->getItem($key);

        if (!$Cached_auth_student_row->isHit()) {
            $Cached_auth_student_row->set($this->auth_student_row)->expiresAfter(1);
            $InstanceCache->save($Cached_auth_student_row);
        
            $auth_student_row_cached_result = $Cached_auth_student_row->get();
  
            $this->encrypted_id = $auth_student_row_cached_result['encrypted_id'];

            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        }else{

            $auth_student_row_cached_result = $Cached_auth_student_row->get();
  
            $this->encrypted_id = $auth_student_row_cached_result['encrypted_id'];

 
        }

        }

    }

    //updating the user login status and starting the user session
    public function updateLoginStatus(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/users-db-connection.php');  

        $update_login_status = "UPDATE students SET login_status='Online' 
        WHERE encrypted_id = '$this->encrypted_id'";

        if($conn1->query($update_login_status)){

           //echo "Logged in";

        }else{

           //echo "Could not log in";

        }

    }

    //starting the user session and redirecting the user to the edtech classroom dashboard
    public function startingUserSession(){

        if($this->login_status == TRUE){

            session_start();

            $_SESSION['student_session'] = $this->encrypted_id;

            header("location:../../dashboard/student-dashboard/");

        }

    }

}

?>