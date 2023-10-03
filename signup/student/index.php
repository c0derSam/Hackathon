<?php

/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : signup/student/index.php

** About : this module displays the sign up page for the edtech students

*/

/**PSUEDO ALGORITHM
 * *
 * initiate the sign up page class
 * display the header
 * display the student sign up page 
 * cache the student sign up page
 * 
 * *
 */

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//initiate the sign up page class
class signup{

    public $student_signup_page;

    //display the header
    public function header(){

       include('../header/header.php');

    }

    //display the student sign up page
    public function studentSignUp(){

      $this->student_signup_page = '
      
        <br>

        <div class="intro" style="border-radius:10% 10% 45% 44% / 10% 10% 44% 46%;background-color:#1d007e;" 
        align="center">

            <br><br><br>

            <h1 class="text-light" style="font-weight:bolder;font-family:monospace;">
            
              Student Sign Up <i class="fa fa-user-circle"></i>
            
            </h1>

            <br>

        </div>

        <br><br>

        <div class="container">

            <div class="sign-up-form">

                <form action="../student-sign-up-processor/student-sign-up-success.php"" method="POST"
                enctype="multipart/form-data">

                   <ul class="nav nav-tabs justify-content-center">
                       <li class="nav-item">
                           <a class="nav-link active" data-bs-toggle="tab" href="#form1">Step 
                               <span class="badge" style="background-color:#1d007e;">1</span>
                           </a>
                       </li>
   
                       <li class="nav-item">
                           <a class="nav-link" data-bs-toggle="tab" href="#form2">Step 
                               <span class="badge" style="background-color:#1d007e;">2</span>
                           </a>
                       </li>
   
                       <li class="nav-item">
                           <a class="nav-link" data-bs-toggle="tab" href="#form3"> Step
                               <span class="badge" style="background-color:#1d007e;">3</span>
                           </a>
                       </li>
                   </ul>
                   <!-- input tabs -->

                    <div class="tab-content">

                        <br>

                        <div class="tab-pane container active" id="form1" align="center">

                            <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

                                <input class="form-control" type="email" name="email" placeholder="Email" 
                                style="height:40px;">

                                <br>

                                <input class="form-control" type="text" name="name" placeholder="Full name" required 
                                style="height:40px;">

                                <br>

                                <select class="form-control" name="gender">
                                   <option>Gender</option>
                                   <option>Male</option>
                                   <option>Female</option>
                                </select>

                                <br>

                                <button class="btn btn-md text-light" style="background-color:#1d007e;">
                                  Click on step 2 above
                                </button>

                            </div>

                        </div>
                        <!-- tab one -->

                        <div class="tab-pane container" id="form2" align="center">

                            <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

                                <select class="form-control" name="level">
                                   <option>Level</option>
                                   <option>100</option>
                                   <option>200</option>
                                   <option>300</option>
                                   <option>400</option>
                                </select>

                                <br>

                                <input class="form-control" type="number" name="matricNumber" placeholder="Matric Number" 
                                required style="height:40px;">

                                <br>

                                <textarea class="form-control" name="about" rows="3" placeholder="Some words about you"></textarea>

                                <br>

                                <button class="btn btn-md text-light" style="background-color:#1d007e;">
                                  Click on step 3 above
                                </button>

                            </div>

                        </div>
                        <!-- tab two -->

                        <div class="tab-pane container" id="form3" align="center">

                            <div class="shadow p-4 mb-4 bg-light" style="width:300px;">

                                <label align="center" for="avatar" class="text-secondary"><b>Upload Avatar</b>
                                </label><br>
                                <input id="avatar" type="file" name="avatar" accept="image/*" style="width:170px;">

                                <br><br>    

                                <input class="form-control" type="text" name="username" placeholder="Username" 
                                required style="height:40px;">

                                <br>

                                <input class="form-control" type="password" name="password" placeholder="Password" 
                                required style="height:40px;" maxlength="8" minlength="8">

                                <br>

                                <button type="submit" class="btn btn-md text-light" style="background-color:#1d007e;">
                                  Sign up <i class="fa fa-user-circle-o"></i>
                                </button>

                            </div>

                        </div>
                        <!-- tab three -->


                    </div>
                    <!-- tabs content -->


                </form>

            </div>

        </div>
        <!-- responsive conatiner -->
      
      ';

    }

    //cache the student sign up page
    public function cachedStudentSignUpPage(){
    
        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "student_signUp_page";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->student_signup_page)->expiresAfter(1);//in seconds, also accepts Datetime
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

$signup = new signup();

$signup->header();

$signup->studentSignUp();

$signup->cachedStudentSignUpPage();

?>