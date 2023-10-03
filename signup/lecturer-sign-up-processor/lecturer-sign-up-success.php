<?php

/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : signup/lecturer-sign-up-processing/lecturer-sign-up-success.php

** About : this model displays the profile of the lecturer just signed in

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the lecturer success page class
 * fetch all the lecturer signup data to be displayed
 * display the success page
 * cache the success page
 * 
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

class lecturerSuccessPage{

    public $title;

    public $fullname;

    public $username;

    public $about;

    public $avatar_filename;

    public $avatar;

    public $signup_status;
    

    public $sucess_page;

    public function fetchSignupData(){

        include('lecturer-sign-up-controller.php');

        $lecturer_sign_up_controller = new lecturerSignupController();

        $lecturer_sign_up_controller->controller();

        $this->title = $lecturer_sign_up_controller->title;

        $this->fullname = $lecturer_sign_up_controller->fullname;

        $this->username = $lecturer_sign_up_controller->username;

        $this->about = $lecturer_sign_up_controller->about;

        $this->avatar_filename = $lecturer_sign_up_controller->avatar_filename;

        $this->signup_status = $lecturer_sign_up_controller->sign_up_status;

        $this->avatar = '../../resources/avatars/'.$this->avatar_filename;

    }

    //display the success page
    public function displaySuccessPage(){

        if($this->signup_status == "true"){

            include('../header/header.php');

            $this->success_page = '
             
            <br>

            <div class="intro" style="border-radius:10% 10% 45% 44% / 10% 10% 44% 46%;background-color:#1d007e;" 
            align="center">

                <br><br><br>

                <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
            
                    Welcome <br> '.$this->title.' '.$this->fullname.'
            
                </h1>

                <br>

            </div>

            <br><br>

            <div class="container">

                <div align="center" class="profile-container">

                    <div class="row">

                        <div class="col">

                        <img src='.$this->avatar.' id="profile_pic" 
                        style="border:5px solid white;padding:5px;max-height:250px;
                        border-radius:150px;max-width:250px;"/>

                        <br>

                        </div>
                        <!-- column one -->

                        <div class="col">

                        <div class="card shadow p-4 mb-4 bg-light border" style="width:300px;
                        font-family:monospace;">

                            <span style="font-size:19px;">
                            <i class="fa fa-user-circle"></i> '.$this->title.' '.$this->fullname.' <br> 
                               <span class="text-secondary">@'.$this->username.'</span>
                            </span>

                            <hr>

                            <span><i class="fa fa-institution"></i> Department Of Educational Technology
                            </span>

                            <hr>

                            <span><i class="fa fa-align-left"></i> About <br>
                               '.$this->about.'
                            </span>

                            <button class="btn btn-md text-light" style="background-color:#1d007e;">
                               <a class="text-light" href="../../login/lecturer-login.php" style="text-decoration:none;">
                                   Login to continue <i class="fa fa-sign-in"></i> 
                               </a>

                            </button>

                        </div>
                        <!-- card -->   

                        </div>
                        <!-- clumn two -->

                    </div>
                    <!-- grid row --> 

                </div> 
                <!-- profile container -->


            </div>
            <!-- container -->

        
            ';

            echo $this->success_page;

        }else{

            header("location:lecturer-sign-up-error.php");


        }

    }

}

$lecturer_success_page = new lecturerSuccessPage();

$lecturer_success_page->fetchSignupData();

$lecturer_success_page->displaySuccessPage();

//font-family:monospace;max-width:150px;white-space:nowrap;overflow:hidden;
//text-overflow:ellipsis;
?>