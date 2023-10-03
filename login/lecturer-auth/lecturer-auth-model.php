<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : login/lecturer-auth/lecturer-auth-model.php

** About : this module logs the lecturer into the edtech classroom

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the lecturer login class
 * fetch the login in data from the lecturer login page
 * sanitize the logged in data
 * encrypt the lecturer password
 * performing administartive functions
 * authenticating the user login details through a database query
 * caching the lecturer authentication query
 * updating the user login status 
 * starting the user session and redirecting the user to the edtech classroom dashboard
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

class lecturerLogin{

    public $username;

    public $password;


    public $sanitized_username;

    public $sanitized_password;

    
    public $encrypted_password;

    
    public $auth_lecturer_row;
    public $encrypted_id;

    public $login_status;

    //fetch the login in data from the lecturer login page
    public function fetchLecturerData(){

        $this->username = $_POST['username'];

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

    //encrypt the lecturer password
    public function encryptLecturerPassword(){

        $this->encrypted_password = md5($this->password);

    }

    //performing administartive functions

    //authenticating the user login details through a database query
    public function authLecturer(){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/users-db-connection.php');

        $auth_lecturer_query = "SELECT * FROM lecturers WHERE username = '$this->sanitized_username' && password = 
        '$this->encrypted_password'";

        $auth_lecturer_result = $conn1->query($auth_lecturer_query);

        if($auth_lecturer_result->num_rows > 0){

            $this->auth_lecturer_row = $auth_lecturer_result->fetch_assoc();

            $this->login_status = TRUE;

        }else{

            $this->login_status = FALSE;

            //echo "Wrong username or password";
            include('lecturer-auth-error.php');
        }

    }

    //caching the lecturer authentication query
    public function cacheLecturerAuth(){

        if($this->login_status == TRUE){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');

        $key = "lecturer_auth_cache";
        $Cached_auth_lecturer_row = $InstanceCache->getItem($key);

        if (!$Cached_auth_lecturer_row->isHit()) {
            $Cached_auth_lecturer_row->set($this->auth_lecturer_row)->expiresAfter(1);
            $InstanceCache->save($Cached_auth_lecturer_row);
        
            $auth_lecturer_row_cached_result = $Cached_auth_lecturer_row->get();
  
            $this->encrypted_id = $auth_lecturer_row_cached_result['encrypted_id'];

            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        }else{

            $auth_lecturer_row_cached_result = $Cached_auth_lecturer_row->get();
  
            $this->encrypted_id = $auth_lecturer_row_cached_result['encrypted_id'];
 
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

        $update_login_status = "UPDATE lecturers SET login_status='Online' 
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

            $_SESSION['lecturer_session'] = $this->encrypted_id;

            header("location:../../dashboard/lecturer-dashboard/");

        }

    }

}

?>