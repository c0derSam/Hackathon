<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : signup/lecturer/verfiy-lecturer-model.php

** About : this module verifies if the lecturer verification codde is valid

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the verification class
 * fetch the lecturer verification code
 * sanitize the lecturer verification code
 * then verify by selecting from the database if the code corresponds
 * cache the verification result
 * redirect the lecturer to the lecturer sign up code
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;
 
class verification{

    public $verification_code;

    public $sanitized_verification_code;

    private $verification_row;

    public function fetchVerificationCode(){

        $this->verification_code = $_POST['verifyCode'];

    }

    //sanitize the lecturer verification code
    public function sanitizeVerificationCode($verify){

        $verify = htmlspecialchars($verify);
        $verify = stripslashes($verify);
        $verify = strip_tags($verify);
        $verify = htmlentities($verify);

        return $verify; 

    }

    //then verify by seleccting from the database if the code corresponds
    public function thenVerify(){

       //db connection
       include('../../resources/database/admin-setup-db-connection.php');

       $verification_code_query = "SELECT * FROM lecturer_setup WHERE sign_up_code = 
       $this->sanitized_verification_code";

       $verification_code_result = $conn5->query($verification_code_query);

        //checking if the query returns nothing
        if($verification_code_result->num_rows > 0){

            $this->verification_row = $verification_code_result->fetch_assoc();

        }else{

            echo "Wrong verification code";

        }

    }

    //cache the verification result
    public function cacheVerificationResult(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "verification_row";
        $Cached_verification_row = $InstanceCache->getItem($key);
        
        if (!$Cached_verification_row->isHit()) {
            $Cached_verification_row->set($this->verification_row)->expiresAfter(1);
          $InstanceCache->save($Cached_verification_row);
        
            $verification_code_row = $Cached_verification_row->get();
  
            $sign_up_code_id = base64_encode($verification_code_row['sign_up_code']);

            //redirect the lecturer to the lecturer sign up code
            header("location:index.php?vcode=$sign_up_code_id");

            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            $verification_code_row = $Cached_verification_row->get();
  
            $sign_up_code_id = base64_encode($verification_code_row['sign_up_code']);

            //redirect the lecturer to the lecturer sign up code
            header("location:index.php?vcode=$sign_up_code_id");

           // echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

}

?>