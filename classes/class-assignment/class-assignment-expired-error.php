<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-assignment/class-assignment-expired-error.php

** About : this module displays the assignment expired error

*/ 

/**PSUEDO ALGORITHM
 * *
 * initiate the error page class
 * display the error page
 * cache the error page
 * 
 * *
 */

 session_start();

class errorPage{

    public $error_page;


    //include the header
    public function header(){

       include('../header/header.php');

    }

    public function displayErrorPage(){

       echo $this->error_page = '
       
       <div style="border-radius:0% 0% 45% 44% / 0% 10% 44% 46%;background-color:#1d007e;" 
       align="center">

           <br>

           <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
  
              Oops, Too late <i class="fa fa-exclamation"></i>
  
           </h1>

           <br>

       </div>

       <br>

        <div align="center">

            <div class="alert alert-warning" style="width:300px;">

               This class assignment has already expired, Try to submit early next time.

            </div>

        </div>
       
       ';

    }

}

$error_page = new errorPage();

$error_page->header();

if(isset($_SESSION['student_session']) or isset($_SESSION['lecturer_session'])){

    $error_page->displayErrorPage();

}else{

    echo "Not logged in";

}

?>