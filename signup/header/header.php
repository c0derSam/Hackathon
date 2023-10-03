<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : signup/header/header.php

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
require('../../vendorPhpfastcache/autoload.php');
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

           <title>Sign Up</title>
 
           <!--Bootsrap css link-->
           <!-- CSS only -->
          <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

           <!--font awesome icons link-->
           <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
           
        </head>

        <body>

        <!-- JavaScript Bundle with Popper -->
        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

        <nav class="navbar fixed-top" style="background-color:#1d007e;">

      <div class="container">

            <span class="navbar-brand">

                   <img class="rounded-circle" src="../../public/images/logo1.jpg" height="45px" width="45px" />
 
                   <span style="font-weight:bolder;font-size:25px;">
                     <font color="#FFFFFF">Edtech Classroom</font>
                   </span>

            </span>

        <i style="font-size:25px;" class="fa fa-navicon text-light" type="button" data-bs-toggle="collapse" 
        data-bs-target="#dropdown" aria-controls="dropdown" aria-expanded="false" 
        aria-label="Toggle navigation"></i>

       <div class="collapse navbar-collapse" id="dropdown">

        <ul class="navbar-nav me-auto mb-2">

          <li class="nav-item">

            <a class="nav-link text-light" href="../../login">
              Login <i class="fa fa-sign-in"></i>
           </a>

          </li>

          <li class="nav-item">

            <a class="nav-link text-light" href="../../help">
              Help <i class="fa fa-question-circle"></i>
            </a>

          </li>

          <li class="nav-item">

            <a class="nav-link text-light">
              Privacy policy<i class="fa fa-lock"></i>
           </a>

          </li>

          <li class="nav-item">

            <a class="nav-link text-light" href="../../tnc">
              Terms and conditions <i class="fa fa-legal"></i>
            </a>

          </li>
          
        </ul>

       </div>

      </div>

    </nav>

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
            $Cached_page->set($this->header)->expiresAfter(5);//in seconds, also accepts Datetime
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