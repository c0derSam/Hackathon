<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : reset-password/verify-reset-passcode.php

** About : this module checks if the data from the reset password code is correct

*/

/**PSUEDO ALGORITHM
 * *
 * define the verify passcode class
 * fetch the inputed pass code data
 * sanitize the pass code data
 * verify the pass code from the database with a query
 * cache the query
 * define the user passcode data
 * redirect the user to the reset passsword form
 * 
 * *
 */

 //cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the verify passcode class 
class verifyPasscode{

    public $reset_code;

    public $sanitized_reset_code;


    public $reset_password_code_result;

    public $reset_password_status;

    public $cached_passcode_row;

    //fetch the inputed pass code data
    public function fetchPasscodeData(){

      $this->reset_code = $_POST['resetCode'];

    }

    //sanitize the pass code data
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        $data = htmlentities($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //verify the pass code from the database with a query
    public function fetchResetPasswordCodeFromDb(){

        //reset password  db connection
        include('../resources/database/users-reset-password-db-connection.php');

        $reset_password_code_query = "SELECT * FROM password_reset_codes WHERE reset_code = '$this->sanitized_reset_code'";

        $this->reset_password_code_result = $conn18->query($reset_password_code_query);

        if($this->reset_password_code_result->num_rows > 0){

            //echo "Your reset password code is correct";

            $this->reset_password_status = TRUE;


        }else{

            echo "Your reset password code is wrong";

            header("location:index.php?passcodeError=true");

        }

        mysqli_close($conn18);
    
    }

    //cache the query
    public function cachePasscodeQuery(){

        if($this->reset_password_status == TRUE){

            CacheManager::setDefaultConfig(new ConfigurationOption([
                'path' => '', // or in windows "C:/tmp/"
            ]));
            
            $InstanceCache = CacheManager::getInstance('files');
    
            $key = "verify_passcode_cache";
            $Cached_passcode_row = $InstanceCache->getItem($key);
    
            if (!$Cached_passcode_row->isHit()) {
                $Cached_passcode_row->set($this->reset_password_code_result)->expiresAfter(1);
                $InstanceCache->save($Cached_passcode_row);
            
                $this->cached_passcode_row = $Cached_passcode_row->get();
      
    
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
            
            }else{
    
                $this->cached_passcode_row = $Cached_passcode_row->get();
     
            }

        }

    }
//jack harlow first class

    //define the user passcode data
    public function defineFetchedPasscodeData(){

        if($this->reset_password_status == TRUE){

            $passcode_row = $this->cached_passcode_row->fetch_assoc();

            $encrypted_user_id = $passcode_row['encrypted_user_id'];

            $user_type = base64_encode($passcode_row['user_type']);
            
            //redirect the user ro the reset passsword form
            header("location:reset-password-form.php?verified=true&&encrypted_user_id=$encrypted_user_id&&user_type=$user_type");

        }

    }

}


$verify_password = new verifyPasscode();

$verify_password->fetchPasscodeData();

//reset password  db connection
include('../resources/database/users-reset-password-db-connection.php');

$verify_password->sanitized_reset_code = $verify_password->sanitize($conn18,$verify_password->reset_code);

$verify_password->fetchResetPasswordCodeFromDb();

$verify_password->cachePasscodeQuery();

$verify_password->defineFetchedPasscodeData();

?>