<?php


/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : login/student-auth/student-auth-error.php

** About : this module displays the error page incase if the login goes wrong

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the error page class
 * display the error page
 * cache the error page
 * 
 * *
 */

//cache library
//require('../../../SvendorPhpfastcache/autoload.php');
//use Phpfastcache\CacheManager;
//use Phpfastcache\Config\ConfigurationOption;

class errorPage{

    public $error_page;

    public function displayErrorPage(){

      $this->error_page = '


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
         
      </head>

      <body>

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

                           <a class="nav-link text-light" data-bs-toggle="modal" 
                           data-bs-target="#sign-up-option-modal">
                               Sign up <i class="fa fa-sign-in"></i>
                           </a>

                        </li>

                        <li class="nav-item">

                           <a class="nav-link text-light" href="../../about">
                               About <i class="fa fa-align-left"></i>
                           </a>

                        </li>

                        <li class="nav-item">

                            <a class="nav-link text-light">
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

            <br>
      

            <div class="intro" style="border-radius:10% 10% 45% 44% / 10% 10% 44% 46%;background-color:#1d007e;" 
            align="center">

                <br><br><br>

                <h1 class="text-warning" style="font-weight:bolder;font-family:monospace;">
            
                    Login Error <i class="fa fa-exclamation-circle"></i>
            
                </h1>

                <br>

            </div>

            <br><br>

            <div align="center">

                <div class="alert alert-warning" style="width:300px;">

                   <p align="center" class="lead">You may have inputed an empty or wrong username and password,check 
                   your login details and try again
                   </p>

                   <a href="../index.php">
                     
                      <button class="btn btn-md text-warning" style="background-color:#1d007e;">
                         Try again <i class="fa fa-sign-in"></i>
                      </button>

                   </a>

                </div>

            </div>
      
      ';

      echo $this->error_page;

    }

}

$eror_page = new errorPage();

$eror_page->displayErrorPage();

?>