<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : dashboard/header/header.php

** About : this module displays the dashboard header

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the header class
 * fetch the user session
 * fetch the user notification total based on the user type
 * define the notification data
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

    public $lecturer_session;

    public $student_session;

    public $notification_total;

    //fetch the user session
    public function fetchUserSession(){

      if(isset($_SESSION['lecturer_session'])){

        $this->lecturer_session = $_SESSION['lecturer_session'];

      }elseif(isset($_SESSION['student_session'])){

        $this->student_session = $_SESSION['student_session'];

      }

    }

    //fetch the user notification total based on the user type
    public function fetchLecturerNotificationTotal(){

      if(isset($_SESSION['lecturer_session'])){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/users-notification-db-connection.php');//conn15

        $lecturer_notification_total_query = "SELECT count(*) AS notifyTotal FROM notification_for_user".$this->lecturer_session." 
        WHERE status = 'unread'";

        $lecturer_notification_total_result = $conn15->query($lecturer_notification_total_query);

        $lecturer_total_row = $lecturer_notification_total_result->fetch_assoc();

        $this->notification_total = $lecturer_total_row['notifyTotal'];

        mysqli_close($conn15);

      }

    }

    public function fetchStudentNotificationTotal(){

      if(isset($_SESSION['student_session'])){

        //require the env library
        require('../../vendorEnv/autoload.php');

        $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
        $user_db_conn_env->load();

        // user db connection
        include('../../resources/database/users-notification-db-connection.php');//conn15

        $student_notification_total_query = "SELECT count(*) AS notifyTotal FROM notification_for_user".$this->student_session." 
        WHERE status = 'unread'";

        $student_notification_total_result = $conn15->query($student_notification_total_query);

        $student_total_row = $student_notification_total_result->fetch_assoc();

        $this->notification_total = $student_total_row['notifyTotal'];

        mysqli_close($conn15);

      }

    }

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

           <title>Edtech Classroom | Course dashboard</title>
 
           <!--Bootsrap css link-->
           <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-0evHe/X+R7YkIZDRvuzKMRqM+OrBnVFBL6DOitfPri4tjfHxaWutUpFmBp4vmVor" crossorigin="anonymous">

           <!--font awesome icons link-->
           <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
           
        </head>

        <body>

        <!-- JavaScript Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pprn3073KE6tl6bjs2QrFaJGz5/SUsLqktiwsUTF55Jfv3qYSDhgCecCxMW52nD2" crossorigin="anonymous"></script>

        <div class="edtech-logo" style="background-color:#1d007e;">

            <div class="container">

                <img class="rounded-circle" src="../../public/images/logo1.jpg" height="45px" width="45px" />
 
                <span style="font-weight:bolder;font-size:25px;">
                    <font color="#FFFFFF">Edtech Classroom</font>
                </span>

            </div>

        </div>

        <nav class="navbar sticky-top" style="background-color:#1d007e;">

            <div class="container">

                <ul class="nav">

                    <li class="nav-item">

                      <a class="nav-link text-light" href="../header/redirect-user.php">
                        Home <i class="fa fa-home"></i>
                      </a>

                    </li>

                    <li class="nav-item">

                      <a class="nav-link text-light" href="../../search-courses">
                        Search <i class="fa fa-search"></i>
                      </a>

                    </li>

                    <li class="nav-item">

                      <a class="nav-link text-light" href="../../notifications">
                        Notify <i class="fa fa-bell"></i><span class="badge bg-light text-dark">'.$this->notification_total.'</span>
                      </a>

                    </li>

                </ul>

                <i style="font-size:25px;" class="fa fa-navicon text-light" type="button" data-bs-toggle="collapse" 
                data-bs-target="#dropdown" aria-controls="dropdown" aria-expanded="false" 
                aria-label="Toggle navigation"></i>


                <div class="collapse navbar-collapse" id="dropdown">

                  <ul class="navbar-nav me-auto mb-2">
          
                      <li class="nav-item">

                      <a class="nav-link text-light" href="../../profile">
                      <i class="fa fa-user-circle-o"></i> Profile
                      </a>

                      <a class="nav-link text-light" href="../../settings">
                       <i class="fa fa-cogs"></i> Settings
                       </a>

                      <a class="nav-link text-light" href="../../help">
                      <i class="fa fa-question-circle"></i> Help
                      </a>

                      <a class="nav-link text-light" href="../../logout/logout.php">
                      <i class="fa fa-sign-out"></i> Logout
                      </a>

                      </li>

                  </ul>

                </div> 

          </div>

        </nav>
        ';

    }

    //cache the header page
    public function cachedHeaderPage(){
    
        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "header1_page";
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

$header->fetchUserSession();

$header->fetchLecturerNotificationTotal();

$header->fetchStudentNotificationTotal();

$header->displayHeader();

$header->cachedHeaderPage();

?>