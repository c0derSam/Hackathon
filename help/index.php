<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : help/index.php

** About : this module displays the feedback forms

*/

/**PSUEDO ALGORITHM
 * *
 * define the feedback class
 * include the header
 * display the feed back form
 * cache the feedback form
 * 
 * *
 */

//cache library
require('../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the feedback class
class feedback{

    public $feedback_subheading;

    public $feedback_form;


    public $success_feedback_alert;

    public $empty_feedback_alert;

    //include the header
    public function header(){

        include('header/header.php');

    }

    //display the feed back form
    public function feedbackSubheading(){

        $this->feedback_subheading = '

        <br>

        <div class="container col-xxl-8 px-4 py-5">

            <div class="row flex-lg-row-reverse align-items-center g-5 py-5">

                <div class="col-10 col-sm-8 col-lg-6">
                    <img src="../public/images/intro.jpg" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" 
                    width="1100" height="900" loading="lazy">
                </div>

                <div class="col-lg-6">
                    <h1 class="display-5 fw-bold lh-1 mb-3">Tell us about your experience</h1>

                    <p class="lead">
                    <small>
                        Reach us through our social media platforms to tell us about your experience and stuffs we need to
                        add in the Edtech classroom, You can also follow and tweet at <a href="https://twitter.com/cyber_geek__" style="text-decoration:none;">oladele John</a> 
                        to get faster replies on everything about the Edtech Classroom.
                    </small>
                    </p>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                        <button type="button" class="btn btn-md btn-lg px-4 me-md-2 text-light" style="background-color:#1d007e;">
                            <a class="nav-link text-light" href="https://twitter.com/theEdtechClass">
                                Twitter <i class="fa fa-twitter"></i>
                            </a>
                        </button>
                        
                    </div>

                </div>

            </div>
        </div>

        ';

    }

    

    //cache the feedback subheading
    public function cacheFeedbackSubheading(){

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
        ]));
        
        $InstanceCache = CacheManager::getInstance('files');
        
        $key = "feedback_form_page";
        $Cached_page = $InstanceCache->getItem($key);
        
        if (!$Cached_page->isHit()) {
            $Cached_page->set($this->feedback_subheading)->expiresAfter(1);//in seconds, also accepts Datetime
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

$feedback_controller = new feedback();

$feedback_controller->header();

$feedback_controller->feedbackSubheading();

$feedback_controller->cacheFeedbackSubheading();

?>