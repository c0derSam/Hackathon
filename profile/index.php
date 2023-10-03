<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : profile/index.php

** About : this module redirect the user to the profile page based on the user type

*/

/**PSUEDO ALGORITHM
 * *
 * define the profile redirect class
 * define the redirect function
 * 
 * *
 */

session_start();

class profileRedirect{

    public function redirectUser(){

        session_regenerate_id(true);

        if(isset($_SESSION['lecturer_session'])){

           header("location:lecturer-profile.php");

        }elseif(isset($_SESSION['student_session'])){

            header("location:student-profile.php");

        }else{

            header("location:logout-success.php");

        }

    }

}

$profile_redirect_controller = new profileRedirect();

$profile_redirect_controller->redirectUser();

?>