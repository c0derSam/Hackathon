<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : reset-password/index.php

** About : this module displays the reset password form

*/

/**PSUEDO ALGORITHM
 * *
 * define the reset password class
 * include the header
 * display the subheading
 * cache the subheading
 * display the reset password form
 * cache the reset password form
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the reset password class
class resetPassword{

    public $sub_heading;

    public $reset_password_code;

    //include the header
    public function header(){

       include('header/header.php');

    }

    //display the subheading
    public function subHeading(){

       $this->sub_heading = '
       
       <br><br>
        
        <div class="intro" style="border-radius:10% 10% 45% 44% / 10% 10% 44% 46%;background-color:#1d007e;" 
          align="center">
  
              <br><br><br>
  
              <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
              
                Reset Password <i class="fa fa-lock"></i>
              
              </h1>
  
              <br>
  
        </div>
  
        <br><br>

       ';

    }

    //cache the subheading
    public function cacheSubheading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "reset_password_subheading";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->sub_heading)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    public function resetPasswordAlert(){

        echo '
        
        <div align="center">
 
        <div class="alert alert-warning" style="width:200px;height:130px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         Your password reset code is wrong
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
             </div>
 
        </div>
        
        ';

    }

    //display the reset password form
    public function resetPasswordCode(){

        $this->reset_password_code = '
        
        <div class="container">

            <div align="center">

                <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

                    <form action="verify-reset-passcode.php" method="POST">

                        <input class="form-control" type="text" name="resetCode" placeholder="Password reset code" 
                        required style="height:40px;">

                        <br>

                       <button type="submit" class="btn btn-md text-light" style="background-color:#1d007e;">
                           Submit
                       </button>

                    </form>

                </div>
                <!-- login container -->

            </div>

        </div>
        
        ';

    }

    //cache the reset password form
    public function cacheResetPasswordForm(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "reset_password_form";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->reset_password_code)->expiresAfter(1);//in seconds, also accepts Datetime
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

$reset_password = new resetPassword();

$reset_password->header();

$reset_password->subHeading();

$reset_password->cacheSubheading();

if(isset($_GET['passcodeError'])){

    $reset_password->resetPasswordAlert();

}

$reset_password->resetPasswordCode();

$reset_password->cacheResetPasswordForm();

?>