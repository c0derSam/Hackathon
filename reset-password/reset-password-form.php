<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : reset-password/reset-password-form.php

** About : this module displays the reset password form and
resets the user password

*/

/**PSUEDO ALGORITHM
 * *
 * define the reset password class
 * //fetch the user encrypted id and user type
 * display the header
 * display the sub heading
 * cache the subheading
 * display the reset password form
 * cache the reset password form
 * fetch the new user password
 * sanitize the new password
 * insert the the new password into the user database
 * 
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the reset password class
class resetPasswordForm{

    public $user_encrypted_id;

    public $user_type;


    public $sub_heading;

    public $password_form;

    //fetch the user encrypted id and user type
    public function fetchUserEncryptedIdAndUserType(){

        $this->user_encrypted_id = 
        $_GET['encrypted_user_id'];

        $this->user_type = base64_decode($_GET['user_type']);

    }
    
    //display the header
    public function header(){

        include('header/header.php');
 
    }

    //display the sub heading
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
        
        $key = "another_reset_password_subheading";
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

    //display the reset password form
    public function resetPasswordFor(){

        $this->password_form = '
        
        <div class="container">

            <div align="center">

                <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

                    <form action="reset-password.php" method="POST">

                        <input class="form-control" type="password" name="newPassword" placeholder="Enter your new password" 
                        required style="height:40px;" maxlength="8" minlength="8">

                        <input type="hidden" name="encryptedId" value='.$this->user_encrypted_id.'>

                        <input type="hidden" name="userType" value='.$this->user_type.'>

                        <br>

                       <button type="submit" class="btn btn-md text-light" style="background-color:#1d007e;">
                           Reset
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
            $Cached_page->set($this->password_form)->expiresAfter(1);//in seconds, also accepts Datetime
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

$reset_password_form = new resetPasswordForm();

if(isset($_GET['encrypted_user_id'])){

    $reset_password_form->fetchUserEncryptedIdAndUserType();

    $reset_password_form->header();

    $reset_password_form->subHeading();

    $reset_password_form->cacheSubheading();

    $reset_password_form->resetPasswordFor();

    $reset_password_form->cacheResetPasswordForm();

}else{

    header("location:index.php");

}

?>