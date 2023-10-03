<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : index.php

** About : this module displays the home page of the edtech classroom

*/  

/**PSUEDO ALGORITHM
 * *
 * initiate the home page class
 * display the home page 
 * cache the home page
 * 
 * *
 */

//cache library
require('vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//initiate the home page class
class home{

  public $home_page;

  //display the home page 
  public function homePage(){


    //2252E1

    //38% 40% 11% 10% / 30% 29% 0% 0% 

    $this->home_page = '
    
    <!doctype html>

    <html lang="en">

    <head>

        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name = "author" content ="oladele john">

        <title>Edtech Classroom</title>
 
        <!--Bootsrap css link-->
        <!-- CSS only -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

         <!--font awesome icons link-->
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

         <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
         <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
         <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
         <link rel="manifest" href="site.webmanifest">

        <style>

        .nav-scroller {
          position: relative;
          z-index: 2;
          
          overflow-y: hidden;
        }
        
        .nav-scroller .nav {
          display: flex;
          flex-wrap: nowrap;
          padding-bottom: 1rem;
          margin-top: -1px;
          overflow-x: auto;
          color: rgba(255, 255, 255, .75);
          text-align: center;
          white-space: nowrap;
          -webkit-overflow-scrolling: touch;
        }
        
        .nav-underline .nav-link {
          padding-top: .75rem;
          padding-bottom: .75rem;
          font-size: .875rem;
          color: #6c757d;
        }
        
        .nav-underline .nav-link:hover {
          color: #007bff;
        }
        
        .nav-underline .active {
          font-weight: 500;
          color: #343a40;
        }

        </style>

    </head>

    <body>

    <!-- JavaScript Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>
    <nav class="navbar fixed-top" style="background-color:#1d007e;">

      <div class="container">

            <span class="navbar-brand">

                   <img class="rounded-circle" src="public/images/logo1.jpg" height="45px" width="45px" />
 
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

            <a class="nav-link text-light" data-bs-toggle="modal" data-bs-target="#sign-up-option-modal">
              Sign up <i class="fa fa-user-circle-o"></i>
            </a>

          </li>

          <li class="nav-item">

            <a class="nav-link text-light" href="login">
              Login <i class="fa fa-sign-in"></i>
           </a>

          </li>

          <li class="nav-item">

            <a class="nav-link text-light" href="help"> 
              Help <i class="fa fa-question-circle"></i>
            </a>

          </li>

          <li class="nav-item">

            <a class="nav-link text-light">
              Privacy policy<i class="fa fa-lock"></i>
           </a>

          </li>

          <li class="nav-item">

            <a class="nav-link text-light">
              Terms and conditions <i class="fa fa-legal"></i>
            </a>

          </li>
          
        </ul>

       </div>

      </div>

    </nav>

    <div style="border-radius:10% 10% 45% 44% / 10% 10% 44% 46%;background-color:#1d007e;" 
    align="center">

    <br><br><br><br>

        <div class="container">

           <div class="row">

              <div class="col">

                 <img src="public/images/intro3.jpg" height= "250px" width="350px" 
                 style="border-radius:30% 70% 70% 60% / 40% 40% 90% 90%; "/>

                 <br><br>

              </div>

              <div class="col">

                <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">

                  Redifining Courses
                
                </h1>

                <hr class="text-light">

                <p class="text-light" style="font-weight:bold;font-family:monospace;">
                
                   To improve upon the technological learning sysytem of the department of Educational Technology
                   In Tasued

                </p>

                <ul class="nav justify-content-center">

                   <li class="nav-item">
               
                       <a class="nav-link">

                           <button class="btn btn-md bg-light" style="color:#1C3DB7;" data-bs-toggle="modal" 
                           data-bs-target="#sign-up-option-modal">
                           Sign up <i class="fa fa-user-circle-o"></i></button>

                       </a>

                   </li>

                   <li class="nav-item"> 
               
                       <a class="nav-link" href="login">

                           <button class="btn btn-md bg-light" style="color:#1C3DB7;">Login 
                           <i class="fa fa-sign-in"></i></button>

                       </a>

                   </li>

                </ul>

              </div>

           </div>

        </div>
        <!-- responsive container -->

    <br><br>   

    </div>
    <!-- intro container -->

    <br><br>

    <div class="features-container">

      <div class="container" align="center">

        <div class="row">

          <div class="col">

            <div class="card shadow p-4 mb-4 bg-light" style="max-height:400px;">

              <div align="center">
                <img src="public/images/card-image1x.jpg" style="max-width:250px;max-height:300px;"/>
              </div>

              <div class="card-body">

                 <span class="card-title" style="font-size:25px;font-weight:bold;font-family:monospace;">
                   Digital Courses <i class="fa fa-mortar-board"></i>
                 </span>

                 <p class="card-text">
                   One of the primary goals of the Edtech classroom is to digitize the courses held by professionals and 
                   provide them with adequate digitized lecturing tools to help in educating their students.
                 </p>

              </div>
              <!-- card body -->

            </div>

            <br>

          </div>
          <!-- column one -->

          <div class="col">

            <div class="card shadow p-4 mb-4 bg-light" style="max-height:400px;">

              <div align="center">
                <img src="public/images/intro2.jpg" style="max-width:250px;max-height:300px;"/>
              </div>

              <div class="card-body">

                 <span class="card-title" style="font-size:25px;font-weight:bold;font-family:monospace;">
                   Evaluation <i class="fa fa-exchange"></i>
                 </span>

                 <p class="card-text">
                   The Edtech classroom is bulit with tools to ensure that lecturers can evaluate
                   students learning capacity to discover what improvements are needed in the class.

                 </p>

              </div>
              <!-- card body -->

            </div>
            <!-- card -->

            <br>

          </div>

          <div class="col">

            <div class="card shadow p-4 mb-4 bg-light" style="max-height:400px;">

              <div align="center">
                <img src="public/images/card-image3pro.jpg" style="max-width:250px;max-height:300px;"/>
              </div>

              <div class="card-body">

                 <span class="card-title" style="font-size:25px;font-weight:bold;font-family:monospace;">
                   E-materials <i class="fa fa-book"></i>
                 </span>

                 <p class="card-text">
                   Digitalization of instructional materials helps the Edtech Classroom to be hub of class notes
                   which motivates students to study ahead of classes both online and physical.

                 </p>

              </div>
              <!-- card body -->

            </div>
            <!-- card -->

            <br>

          </div>
          
        </div>
        <!-- row -->

      </div>
      <!--responsive conatiner -->

    </div>
    <!-- features container -->

    <br>

    <div class="about-department-container" 
    style="background-color:#1d007e;border-radius:10% 10% 42% 44% / 0% 0% 21% 20%;">

    <br>

      <div class="container">

         <h2 class="text-light" style="font-weight:bolder;font-family:monospace;text-align:center;">
            About The Department <i class="fa fa-university"></i>
         </h2>

         <br>

        <div class="department-heads-image-nav">
          
           <span class="text-light" style="font-weight:bolder;font-family:monospace;font-size:22px;">
              <b>Department Heads <i class="fa fa-users"></i></b>
           </span>

           <hr class="text-light" style="width:280px;">

           <div class="nav-scroller shadow-sm">
              <nav class="nav nav-underline" aria-label="Secondary navigation">
                <a class="nav-link text-white" href="#">

                  <img src="public/images/lecturer-image.png" 
                  style="border-radius:50px;max-height:100px;max-width:100px;"/>
                  <br>
                  <span class="badge bg-light text-dark rounded-pill">Hod</span>


                </a>

                <a class="nav-link text-white" href="#">

                <img loading="lazy" src="public/images/lecturer-image.png" 
                style="border-radius:50px;max-height:100px;max-width:100px;"/>
                <br>
                <span class="badge bg-light text-dark rounded-pill">Lecturer</span>

                </a>

                <a class="nav-link text-white" href="#">

                <img loading="lazy" src="public/images/lecturer-image.png" 
                style="border-radius:50px;max-height:100px;max-width:100px;"/>
                <br>
                <span class="badge bg-light text-dark rounded-pill">Lecturer</span>

                </a>

                <a class="nav-link text-white" href="#">

                <img loading="lazy" src="public/images/lecturer-image.png" 
                style="border-radius:50px;max-height:100px;max-width:100px;"/>
                <br>
                <span class="badge bg-light text-dark rounded-pill">Lecturer</span>

                </a>

                <a class="nav-link text-white" href="#">

                <img loading="lazy" src="public/images/lecturer-image.png" 
                style="border-radius:50px;max-height:100px;max-width:100px;"/>
                <br>
                <span class="badge bg-light text-dark rounded-pill">Lecturer</span>

                </a>

                <a class="nav-link text-white" href="#">

                <img loading="lazy" src="public/images/lecturer-image.png" 
                style="border-radius:50px;max-height:100px;max-width:100px;"/>
                <br>
                <span class="badge bg-light text-dark rounded-pill">Lecturer</span>

                </a>

                <a class="nav-link text-white" href="#">

                <span class="badge bg-light text-dark rounded-pill">More...</span>

                </a>

              </nav>
           </div>

        </div>
        <!-- department heads --> 

        <div class="department-student-union-image-nav">
          
           <span class="text-light" style="font-weight:bolder;font-family:monospace;font-size:22px;">
              <b>Department Student Union <i class="fa fa-users"></i></b>
           </span>

           <hr class="text-light" style="width:280px;">

           <div class="nav-scroller shadow-sm">
              <nav class="nav nav-underline" aria-label="Secondary navigation">
                <a class="nav-link text-white" href="#">

                  <img loading="lazy" src="public/images/lecturer-image.png" 
                  style="border-radius:50px;max-height:100px;max-width:100px;"/>
                  <br>
                  <span class="badge bg-light text-dark rounded-pill">President</span>


                </a>

                <a class="nav-link text-white" href="#">

                <img loading="lazy" src="public/images/lecturer-image.png" 
                style="border-radius:50px;max-height:100px;max-width:100px;"/>
                <br>
                <span class="badge bg-light text-dark rounded-pill">Post</span>

                </a>

                <a class="nav-link text-white" href="#">

                <img loading="lazy" src="public/images/lecturer-image.png" 
                style="border-radius:50px;max-height:100px;max-width:100px;"/>
                <br>
                <span class="badge bg-light text-dark rounded-pill">Post</span>

                </a>

                <a class="nav-link text-white" href="#">

                <img loading="lazy" src="public/images/lecturer-image.png" 
                style="border-radius:50px;max-height:100px;max-width:100px;"/>
                <br>
                <span class="badge bg-light text-dark rounded-pill">Post</span>

                </a>

                <a class="nav-link text-white" href="#">

                <img loading="lazy" src="public/images/lecturer-image.png" 
                style="border-radius:50px;max-height:100px;max-width:100px;"/>
                <br>
                <span class="badge bg-light text-dark rounded-pill">Post</span>

                </a>

                <a class="nav-link text-white" href="#">

                <img loading="lazy" src="public/images/lecturer-image.png" 
                style="border-radius:50px;max-height:100px;max-width:100px;"/>
                <br>
                <span class="badge bg-light text-dark rounded-pill">Post</span>

                </a>

                <a class="nav-link text-white" href="#">

                <span class="badge bg-light text-dark rounded-pill">More...</span>

                </a>

              </nav>
           </div>

           <br>

        </div>
        <!-- department student union --> 

        <div class="department-gallery-image-nav">
          
           <span class="text-light" style="font-weight:bolder;font-family:monospace;font-size:22px;">
              <b>Department Gallery <i class="fa fa-image"></i></b>
           </span>

           <hr class="text-light" style="width:280px;">

           <div class="nav-scroller shadow-sm">
              <nav class="nav nav-underline" aria-label="Secondary navigation">

                <a class="nav-link text-white" href="#">

                <img loading="lazy" class="rounded" src="public/images/department-gallery.jpg" 
                style="max-height:100px;max-width:100px;"/>


                </a>

                <a class="nav-link text-white" href="#">

                <img loading="lazy" class="rounded" src="public/images/department-gallery.jpg" 
                style="max-height:100px;max-width:100px;"/>

                </a>

                <a class="nav-link text-white" href="#">

                <img loading="lazy" class="rounded" src="public/images/department-gallery.jpg" 
                style="max-height:100px;max-width:100px;"/>

                </a>

                <a class="nav-link text-white" href="#">

                <img class="rounded" src="public/images/department-gallery.jpg" 
                style="max-height:100px;max-width:100px;"/>

                </a>

                <a class="nav-link text-white" href="#">

                <img loading="lazy" class="rounded" src="public/images/department-gallery.jpg" 
                style="max-height:100px;max-width:100px;"/>

                </a>

                <a class="nav-link text-white" href="#">

                <img loading="lazy" class="rounded" src="public/images/department-gallery.jpg" 
                style="max-height:100px;max-width:100px;"/>

                </a>

                <a class="nav-link text-white" href="#">

                <span class="badge bg-light text-dark rounded-pill">More...</span>

                </a>

              </nav>
           </div>

           <br>

        </div>
        <!-- department gallery -->

      </div>
      <!-- responsive conatiner -->

    <br>

    </div>
    <!-- about department container -->

    <br><br>

    <div class="footer">

      <div class="container">

        <h3>External Links</h3>

        <div class="row">

          <div class="col">

            <ul class="nav flex-column">

              <li class="nav-item">

                <a class="nav-link text-dark" data-bs-toggle="modal" data-bs-target="#sign-up-option-modal">
                  Sign up <i class="fa fa-user-circle-o"></i>
                </a>

              </li>

              <li class="nav-item">

                <a class="nav-link text-dark" href="login">
                  Login <i class="fa fa-sign-in"></i>
                </a>

              </li>

              <li class="nav-item">

                <a class="nav-link text-dark"  href="help">
                  Help <i class="fa fa-question-circle"></i>
                </a>

              </li>

            </ul>

          </div>
          <!-- column one -->

          <div class="col">

            <ul class="nav flex-column">

              <li class="nav-item">

                <a class="nav-link text-dark">
                  Privacy policy <i class="fa fa-lock"></i>
                </a>

              </li>

              <li class="nav-item">

                <a class="nav-link text-dark">
                  Terms and conditions <i class="fa fa-legal"></i>
                </a>

              </li>

            </ul>

          </div>
          <!-- column two -->

        </div>
        <!-- row -->

        <br>

        <h4>Social Links</h4>

        <ul class="nav flex-column">

              <li class="nav-item">

                <a class="nav-link text-dark" href="https://twitter.com/theEdtechClass">
                  Follow us on twitter <i class="fa fa-twitter"></i>
                </a>

              </li>

        </ul>

            <br>

        <p class="text-secondary" style="font-family:monospace;" align="center">
           <small>
            <a href="https://twitter.com/cyber_geek__" style="text-decoration:none;">Oladele John</a> © 2022
          </smal>
        </p>

      </div>

    </div>
    <!-- footer -->

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


                          <a class="nav-link text-dark" href="signup/lecturer/verify-lecturer-form.php">
                            <li class="nav-item shadow p-4 mb-4 bg-light">                     
                                <i class="fa fa-mortar-board" style="font-size:30px;"></i><br>
                                Lecturer
                            </li>
                          </a>

                          <a class="nav-link text-dark" href="signup/student">
                            <li class="nav-item shadow p-4 mb-4 bg-light">                     
                               <i class="fa fa-user" style="font-size:30px;"></i><br>
                               Student
                            </li>
                          </a>

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
    
    ';

  }

  //cache the home page
  public function cachedHomePage(){
    
    CacheManager::setDefaultConfig(new ConfigurationOption([
        'path' => '', // or in windows "C:/tmp/"
    ]));
    
    $InstanceCache = CacheManager::getInstance('files');
    
    $key = "home_page";
    $Cached_page = $InstanceCache->getItem($key);
    
    if (!$Cached_page->isHit()) {
        $Cached_page->set($this->home_page)->expiresAfter(1);//in seconds, also accepts Datetime

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

$home = new home();

$home->homePage();

$home->cachedHomePage();

//321##477282828sjsjssj

?>