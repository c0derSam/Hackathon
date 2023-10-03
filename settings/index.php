<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : settings/index.php

** About : this module redirects the user to their respective settings page based on the user type

*/

/**PSUEDO ALGORITHM
 * *
 * define the settings redirect class
 * redirect the users based on the active user session
 * 
 * *
 */

session_start();

//define the settings redirect class 
class settingsRedirect{

    //redirect the users based on the active user session
    public function redirectUser(){

        if(isset($_SESSION['lecturer_session'])){

            header("location:lecturer-settings.php");

        }elseif(isset($_SESSION['student_session'])){

            header("location:student-settings.php");

        }

    }

}

$settings_redirect = new settingsRedirect();

$settings_redirect->redirectUser();

?>