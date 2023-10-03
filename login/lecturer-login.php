<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : login/lecturer-login.php

** About : this module displays the login page for lecturers

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the lecturer login up page class
 * display the header
 * display the lecturer login page 
 * cache the lecturer login page
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

class LecturerLoginPage{

    public $lecturer_login_page;

    //display the header
    public function header(){

       include('header/header.php');

    }

    public function displayLogin(){

       $this->lecturer_login_page = '

       <div class="intro" style="border-radius:10% 10% 45% 44% / 10% 10% 44% 46%;background-color:#1d007e;" 
          align="center">
  
              <br><br><br>
  
              <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
              
                Lecturer Login <i class="fa fa-sign-in"></i>
              
              </h1>
  
              <br>
  
          </div>
  
          <br><br>

            <div align="center">

                <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

                    <form action="lecturer-auth/lecturer-auth-controller.php" method="POST">

                        <input class="form-control" type="text" name="username" placeholder="Username" 
                        required style="height:40px;">

                        <br>

                        <input class="form-control" type="password" name="password" placeholder="Password" 
                        required style="height:40px;">

                        <br>

                        <button type="submit" class="btn btn-md text-light" style="background-color:#1d007e;">
                          Login <i class="fa fa-sign-in"></i>
                        </button>


                    </form>

                </div>
                <!-- login container -->

                <a href="../reset-password">
                   Forgot password
                </a>

            </div>
            <!-- centered container -->

       ';

    }

    //cache the login page
    public function cachedLoginPage(){
    
        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "lecturer_login_page";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->lecturer_login_page)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }
    
    }

    public function accountDeletionSuccessAlert(){

      echo '

      <br>
      
      <div align="center">

      <div class="alert alert-info" style="width:200px;">

               <div class="row">

                   <div class="col">
                  
                       Account successfully deleted 

                   </div>

                   <div class="col">

                      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>

                   </div>

              </div>

      </div>

      </div>';

  }

}

$lecturer_login_page = new LecturerloginPage();

$lecturer_login_page->header();

$lecturer_login_page->displayLogin();

$lecturer_login_page->cachedLoginPage();

if(isset($_GET['accountDelete'])){

  $lecturer_login_page->accountDeletionSuccessAlert();

}

//8b638031d7c34d36bc63f574602fb096
//bad comments

?>