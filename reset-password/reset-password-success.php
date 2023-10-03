<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : reset-password/reset-password-success.php

** About : this module displays the reset password success page

*/

/**PSUEDO ALGORITHM
 * *
 * define the reset password success page class
 * include the header page
 * display the subheading
 * cache the subheading
 * display the success page
 * cache the success page
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the reset password success page class
class resetPasswordSuccess{

    public $sub_heading;

    public $success_page;

    //include the header page
    public function header(){

        include('header/header.php');
 
    }

    //display the subheading
    public function subheading(){

        $this->sub_heading = '
        
        <br><br>
         
         <div class="intro" style="border-radius:10% 10% 45% 44% / 10% 10% 44% 46%;background-color:#1d007e;" 
           align="center">
   
               <br><br><br>
   
               <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
               
                 Success <i class="fa fa-check-circle"></i>
               
               </h1>
   
               <br>
   
         </div>
   
         <br><br>
 
        ';

    }

    public function cacheSubheading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "reset_password_success";
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

    //display the success page
    public function successPage(){

        $this->success_page = '
        
        <div class="container">

        <div align="center">
 
            <div class="alert alert-success" style="width:370px;">
 
                You have successfully changed your password,<br>
                Login <a href="../login">here</a> to continue
                
            </div>
 
        </div>

        </div>
        
        ';

    }

    //cache the success page
    public function cacheSuccessPage(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "success";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->success_page)->expiresAfter(1);//in seconds, also accepts Datetime
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

$reset_password_success = new resetPasswordSuccess();

if(isset($_GET['resetStatus']) && $_GET['resetStatus'] !== "" ){

    $reset_password_success->header();

    $reset_password_success->subheading();

    $reset_password_success->cacheSubheading();

    $reset_password_success->successPage();

    $reset_password_success->cacheSuccessPage();

}else{

    //echo "Status not set";

}

?>