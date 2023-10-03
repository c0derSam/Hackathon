<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : signup/lecturer/verify-lecturer-form.php

** About : this module displays the form the verifies the lecturer to sign up

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the verify lecturer class
 * display the header
 * display the verify lecturer form 
 * cache the verify lecturer page
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//initiate the verify lecturer class
class verifyLecturer{

    public $verify_lecturer_form;

    //display the header
    public function header(){

       include('../header/header.php');

    }

    //display the verify lecturer form 
    public function displayVerifyForm(){

       $this->verify_lecturer_form = '
       
       <br>

        <div class="intro" style="border-radius:10% 10% 45% 44% / 10% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

            <br><br><br>

            <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
            
              Verify You Are A Lecturer <i class="fa fa-check-circle"></i>
            
            </h1>

            <br>

        </div>

        <br><br>

        <div align="center">
        <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

            <form action="verify-lecturer-controller.php" method="POST">

               <p class="lead"><small>Enter the verification code you were given to sign up</small></p>

               <input class="form-control" type="number" name="verifyCode" placeholder="Verification Code" 
               required style="height:40px;">

               <br>

               <button type="submit" class="btn btn-md text-light" style="background-color:#1d007e;">
                  Verify <i class="fa fa-check-circle"></i>
                </button>

            </form>

        </div>
        </div>
       
       ';

    }

    //cache the verify lecturer page
    public function cachedVerifyForm(){
    
        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "verify_lecturer_form";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->verify_lecturer_form)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }
    
    }

}

$verify_lecturer = new verifyLecturer();

$verify_lecturer->header();

$verify_lecturer->displayVerifyForm();

$verify_lecturer->cachedVerifyForm();



?>