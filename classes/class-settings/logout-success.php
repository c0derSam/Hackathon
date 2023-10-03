<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : logout/logout-success.php

** About : this module displays the logout success page

*/

/**PSUEDO ALGORITHM
 * *
 * define the logout success class
 * display the header
 * define the logout success page
 * cache the logout success page
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the logout success class
class logoutSuccess{

    public $login_succes_page;

    //display the header
    public function header(){

       include('../header/header.php');

    }

    //define the logout success page
    public function logoutSuccessPage(){

       $this->logout_success_page = '

         <br><br><br><br>

        <div class="container">

        <div class="row">

            <div class="col">

                <div align="center">

                   <img loading="lazy" src="../../public/images/logout.png" height= "250px" width="350px"/>

                </div>

            </div>
            <!-- column one -->

            <div class="col">

                <br><br><br>

                <h1 style="font-weight:bolder;" align="center">

                    So Sad You <font color="#1d007e">Logged</font> Out
          
                </h1>

                <hr>

                <div align="center">

                <a class="nav-link" href="../../login">

                    <button class="btn btn-md text-light" style="background-color:#1C3DB7;width:200px;">
                    Login <i class="fa fa-sign-in"></i>
                    </button>

                </a>
                
                </div>

            </div>
            <!-- colun two -->


        </div>
       
       ';

    }

    //cache the logout success page
    public function cacheSuccesPage(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "logout_success_page";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->logout_success_page)->expiresAfter(1);//in seconds, also accepts Datetime
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

$logout_success_controller = new logoutSuccess();

if(isset($_SESSION['lecturer_session']) or isset($_SESSION['student_session'])){


}else{

    $logout_success_controller->header();

    $logout_success_controller->logoutSuccessPage();

    $logout_success_controller->cacheSuccesPage();

}

?>