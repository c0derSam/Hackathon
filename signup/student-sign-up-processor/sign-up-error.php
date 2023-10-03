<?php

/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : signup/student-signup-processing/sign-up-error.php

** About : this model displays the error page if the signing up goes wrong

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the sign up error page class
 * display the error page
 * cache the error page
 * 
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

class signUpErrorPage{

    public $error_page;

    public function displayErrorPage(){

      include('../header/header.php');

      $this->error_page = '
      
      <br>

            <div class="intro" style="border-radius:10% 10% 45% 44% / 10% 10% 44% 46%;background-color:#1d007e;" 
            align="center">

                <br><br><br>

                <h1 class="text-warning" style="font-weight:bolder;font-family:monospace;">
            
                    Could Not Sign You Up <i class="fa fa-exclamation-circle"></i>
            
                </h1>

                <br>

            </div>

            <br><br>

            <div align="center">

                <div class="alert alert-warning" style="width:300px;">

                   <p align="center" class="lead">You may not have completed filling all the necessary fields in the 
                      sign up form
                   </p>

                   <a href="../student">
                     
                      <button class="btn btn-md text-warning" style="background-color:#1d007e;">
                         Sign up again <i class="fa fa-user-circle-o"></i>
                      </button>

                   </a>

                </div>

            </div>
      
      ';

      echo $this->error_page;

    }

}

$sign_up_eror_page = new signUpErrorPage();

$sign_up_eror_page->displayErrorPage();
?>