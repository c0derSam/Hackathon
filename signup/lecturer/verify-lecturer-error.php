<?php

/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : signup/student-signup-processing/verify-lecturer-error.php

** About : this model displays the error page if the verification up goes wrong

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the error page class
 * display the error page
 * cache the error page
 * 
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

class errorPage{

    public $error_page;

    public function displayErrorPage(){

      include('../header/header.php');

      $this->error_page = '
            <br>
      

            <div class="intro" style="border-radius:10% 10% 45% 44% / 10% 10% 44% 46%;background-color:#1d007e;" 
            align="center">

                <br><br><br>

                <h1 class="text-warning" style="font-weight:bolder;font-family:monospace;">
            
                    Error During Verification <i class="fa fa-exclamation-circle"></i>
            
                </h1>

                <br>

            </div>

            <br><br>

            <div align="center">

                <div class="alert alert-warning" style="width:300px;">

                   <p align="center" class="lead">You may have inputed an empty or wrong verification code, check your code
                   and try again
                   </p>

                   <a href="../lecturer/verify-lecturer-form.php">
                     
                      <button class="btn btn-md text-warning" style="background-color:#1d007e;">
                         Verify again <i class="fa fa-check-circle"></i>
                      </button>

                   </a>

                </div>

            </div>
      
      ';

      echo $this->error_page;

    }

}

$eror_page = new errorPage();

$eror_page->displayErrorPage()

?>

?>
