<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : signup/lecturer/verfiy-lecturer-controller.php

** About : this module verifies if the lecturer verification codde is valid

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the verification controller
 * include the verification model
 * 
 * *
 */

class verificationController{

    public function controller(){

      include('verify-lecturer-model.php');

      $verification = new verification();

      $verification->fetchVerificationCode();

        if(!empty($verification->verification_code) && isset($verification->verification_code)){

            // user db connection
            include('../../resources/database/admin-setup-db-connection.php');

            $verification->sanitized_verification_code = 
            $verification->sanitizeVerificationCode($verification->verification_code);

            //echo $verification->sanitized_verification_code;

            $verification->thenVerify();

            $verification->cacheVerificationResult();

        }else{

            header("verify-lecturer-error.php");

        }

        mysqli_close($conn5);

    }

}

$verification_controller = new verificationController();

$verification_controller->controller();


?>