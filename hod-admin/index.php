<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : hod-admin/index.php

** About : this module displays the login page for the hod admin page and authenticates the Hod

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the hod login page class
 * display the header
 * display the login page 
 * cache the login page
 * authenticate the hod
 * 
 * *
 */

session_start();

 //cache library
 require('../vendorPhpfastcache/autoload.php');
 use Phpfastcache\CacheManager;
 use Phpfastcache\Config\ConfigurationOption;

//define the hod login page class
class hodLoginPage{

    public $hod_login_page;

    public $hod_auth_status;

    //display the header
    public function displayHeader(){

        include('header/header.php');

    }

    //display the login page
    public function hodLoginPage(){

        $this->hod_login_page = '
        
        <div class="intro" style="border-radius:10% 10% 45% 44% / 10% 10% 44% 46%;background-color:#1d007e;" 
          align="center">
  
              <br><br><br>
  
              <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
              
                Hod Admin Login <i class="fa fa-sign-in"></i>
              
              </h1>
  
              <br>
  
        </div>

        <br><br>

            <div align="center">

                <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

                    <form action="index.php" method="POST">
 
                        <input class="form-control" type="password" name="passcode" placeholder="Hod passcode" 
                        required style="height:40px;">

                        <br>

                        <button type="submit" class="btn btn-md text-light" style="background-color:#1d007e;">
                          Login <i class="fa fa-sign-in"></i>
                        </button>

                    </form>

                </div>
                <!-- login container -->

            </div>
            <!-- centered container -->
        
        ';

    }

    public function cacheHodLoginPage(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "hod_login_page";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->hod_login_page)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        } else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

    //authenticate the hod
    public function authHod(){

        $hod_passcode = $_POST['passcode'];

        $required_passcode = "11abecdil88990";

        if($hod_passcode == $required_passcode){

            $_SESSION['hod_session'] = base64_encode(rand());

            echo '
            
            <div align="center">

                <div class="alert alert-success" style="width:200px;">

                    <a href="dashboard.php">
                        <button class="btn btn-md bg-light">
                            Logged in, click to continue
                        </button>
                    </a>

                </div>

            </div>

            ';


        }else{

            echo '

        <div align="center">
 
            <div class="alert alert-warning" style="width:200px;">
 
                 <div class="row">
 
                     <div class="col">
                    
                         Wrong passcode
 
                     </div>
 
                     <div class="col">
 
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
 
                     </div>
 
                </div>
 
             </div>
 
        </div>
            
            ';

        }

    }

}

$hod_login_page = new hodLoginPage();

$hod_login_page->displayHeader();

$hod_login_page->hodLoginPage();

$hod_login_page->cacheHodLoginPage();

if(isset($_POST['passcode']) && $_POST['passcode'] !== ""){

    $hod_login_page->authHod();

}

?>