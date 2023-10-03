<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : login/header/header.php

** About : this module displays the public header

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the header class
 * display the header page
 * cache the header page
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//initiate the header class
class header{

    public $header;

    //display the header page
    public function displayHeader(){

        $this->header = '
        
        <!doctype html>

        <html lang="en">

        <head>

           <!-- Required meta tags -->
           <meta charset="utf-8">
           <meta name="viewport" content="width=device-width, initial-scale=1">

           <meta name = "author" content ="oladele john">

           <title>Edtech Classroom | Hod admin</title>
 
           <!--Bootsrap css link-->
           <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

           <!--font awesome icons link-->
           <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
           
        </head>

        <body>

        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

        <nav class="navbar fixed-top" style="background-color:#1d007e;">
        

      <div class="container">

            <span class="navbar-brand">

                   <img class="rounded-circle" src="../public/images/logo1.jpg" height="45px" width="45px" />
 
                   <span style="font-weight:bolder;font-size:25px;">
                     <font color="#FFFFFF">Edtech Classroom</font>
                   </span>

            </span>

        </div>

    </nav>

    <div class="modal fade" id="sign-up-option-modal" data-bs-backdrop="static" 
        data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" 
        style="display: none;" aria-hidden="true">

            <div class="modal-dialog">

                <div class="modal-content">

                    <div class="modal-header" style="background-color:#1d007e;">

                        <h5 class="modal-title text-light" id="staticBackdropLiveLabel">Sign up
                        <i class="fa fa-user-circle"></i></h5>
                        <button type="button" class="btn-close text-light" data-bs-dismiss="modal" aria-label="Close">
                        </button>

                    </div>

                    <div class="modal-body">

                        <p class="lead" align="center">
                          Sign up as a 
                        </p>

                        <ul class="nav justify-content-center">

                          <li class="nav-item shadow p-4 mb-4 bg-light">                     
 
                             <a class="nav-link text-dark" href="../signup/lecturer/verify-lecturer-form.php">
                               <i class="fa fa-mortar-board" style="font-size:30px;"></i><br>
                               Lecturer
                             </a>

                          </li>

                          <li class="nav-item shadow p-4 mb-4 bg-light">                     
 
                             <a class="nav-link text-dark" href="../signup/student">
                               <i class="fa fa-user" style="font-size:30px;"></i><br>
                               Student
                             </a>

                          </li>

                        </ul>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close
                        </button>
                   </div>

                </div>
            
            </div> 

        </div>
        <!-- sign up options modal -->

        </body>

        </html>


        
        ';

    }

    //cache the header page
    public function cachedHeaderPage(){
    
        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "header_page";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->header)->expiresAfter(1);//in seconds, also accepts Datetime
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

$header = new header();

$header->displayHeader();

$header->cachedHeaderPage();

?>