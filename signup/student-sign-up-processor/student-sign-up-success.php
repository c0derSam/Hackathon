<?php

/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : signup/student-signup-processing/student-sign-up-success.php

** About : this model displays the profile of the student just signed in

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the student success page class
 * fetch all the student signup data to be displayed
 * display the success page
 * cache the success page
 * 
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

 class studentSuccessPage{

    public $fullname;
    public $username;
    public $matric_number;
    public $about;
    public $avatar_filename;
    public $avatar;
    public $level;
    public $signup_status;

    public $sucess_page;

    //fetch all the student signup data to be displayed
    public function fetchSignUpData(){

        include('student-sign-up-controller.php');

        $student_signup_controller = new studentSignUpController();

        $student_signup_controller->controller();

        $this->fullname = $student_signup_controller->fullname;

        $this->username = $student_signup_controller->username;

        $this->matric_number = $student_signup_controller->matric_number;

        $this->about = $student_signup_controller->about;

        $this->avatar_filename = $student_signup_controller->avatar_filename;

        $this->level = $student_signup_controller->level;

        $this->signup_status = $student_signup_controller->sign_up_status;

        $this->avatar = '../../resources/avatars/'.$this->avatar_filename;
        

    }

    //display the success page
    public function successPage(){

        //checking if the signup was successful
        if($this->signup_status == "successful"){

            include('../header/header.php');

            $this->success_page = '
        
            <br>

            <div class="intro" style="border-radius:10% 10% 45% 44% / 10% 10% 44% 46%;background-color:#1d007e;" 
            align="center">

                <br><br><br>

                <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
            
                    Welcome '.$this->username.'
            
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

                            <span style="font-size:19px;"><i class="fa fa-user-circle"></i> '.$this->fullname.' <br> 
                               <span class="text-secondary">@'.$this->username.'</span>
                            </span>

                            <hr>

                            <span><i class="fa fa-institution"></i> Department Of Educational Technology <br>
                               <span class="text-secondary">'.$this->level.' level</span>
                               <br>
                               <span class="text-secondary">@'.$this->matric_number.'</span>
                            </span>

                            <hr>

                            <span><i class="fa fa-align-left"></i> About <br>
                               <span class="text-secondary">@'.$this->about.'</span>
                            </span>

                            <a href="../../login" style="text-decoration:none;">
                                <button class="btn btn-md text-light" style="background-color:#1d007e;">
                              
                                   Login to continue <i class="fa fa-sign-in"></i>
                                   
                                </button>
                            </a>

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

            //echo $this->success_page;

        }else{

           header("location:sign-up-error.php");

           //echo "Could not sign u up";

        }

    }

    //cache the success page
    public function cachedSuccessPge(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "success_page";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->success_page)->expiresAfter(1);//in seconds, also accepts Datetime
          $InstanceCache->save($Cached_page); // Save the cache item just like you do with doctrine and entities
        
            echo $Cached_page->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
        }else {
            
            echo $Cached_page->get();
            //echo 'READ FROM CACHE // ';
        
            $InstanceCache->deleteItem($key);
        }

    }

 }

 $student_success_page = new studentSuccessPage();

 $student_success_page->fetchSignUpData();

 $student_success_page->successPage();

 $student_success_page->cachedSuccessPge();

 ?>