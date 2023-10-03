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

           <link href="dashboard.css" rel="stylesheet">
           
        </head>

        <body>

        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

        <header class="navbar sticky-top flex-md-nowrap p-0 shadow" style="background-color:#1d007e;">
          <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 text-light" href="#">
          
            <img class="rounded-circle" src="../public/images/logo1.jpg" height="45px" width="45px" /> 
            <b>Hod Admin</b>
          
          </a>

            <div class="navbar-nav">
              <div class="nav-item text-nowrap">
                <a class="nav-link px-3 text-light" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" 
                aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation" href="#">
                  <i class="fa fa-navicon"></i>
                </a>
              </div>

            </div>

        </header>

        <div class="container-fluid">
        <div class="row">

        <nav id="sidebarMenu" class="shadow bg-light col-md-3 col-lg-2 d-md-block bg-light sidebar collapse 
        style="background-color:#1d007e;"">
          <div class="position-sticky pt-3">
            <ul class="nav flex-column">
              <li class="nav-item">
                <a class="nav-link text-dark" href="dashboard.php">
                    <i class="fa fa-dashboard"></i> Dashboard
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link text-dark" href="lecturers-analytics.php">
                    <i class="fa fa-mortar-board"></i> Lecturers
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link text-dark" href="dashboard.php#students">
                    <i class="fa fa-users"></i> Students
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link active text-dark" aria-current="page" href="courses-analytics.php">
                    <b><i class="fa fa-list"></i> Courses</b>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link text-dark" href="sign-out.php">
                    <i class="fa fa-sign-out"></i> Sign out
                </a>
              </li>

              
              <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
            </ul>

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