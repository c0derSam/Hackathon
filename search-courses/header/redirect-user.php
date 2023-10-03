<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : header/redirect-user.php

** About : this module redirect the user to the home page depending on the user type

*/

session_start();

if(isset($_SESSION['lecturer_session'])){

   header("location:../../dashboard/lecturer-dashboard/");

}elseif(isset($_SESSION['student_session'])){

    header("location:../../dashboard/student-dashboard/");

}else{

    header('location:../logout-success.php');

}


?>