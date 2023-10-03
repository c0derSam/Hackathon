<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-test/process-lecturer-test.php

** About : this module submits the lecturer test

*/ 
 
/**PSUEDO ALGORITHM
 * *
 * define the process lecturer test test class
 * fetch the lecturer session
 * fetch the class and test encrypted id session
 * fetch the lecturer details
 * create the lecturer test submit database table
 * check if the lecturer has already submitted the question before
 * fetch all the test questions answers and other data based on the amount of questions created, and insert the answers into the lecturers tst submission
 * fetch the amount of correct answers the lecturer got
 * insert the lecturer details into the test result
 * redirect the lecturer to the test dashboard
 * 
 * 
 * *
*/
session_start();

//cache library
require('../../vendorPhpfastcache/autoload.php');
use Phpfastcache\CacheManager;
use Phpfastcache\Config\ConfigurationOption;

//define the process lecturer test test class
class processLecturerTest{

    public $lecturer_session;


    public $class_session;

    public $test_session;


    public $lecturer_title;

    public $lecturer_fullname;

    public $lecturer_username;

    public $lecturer_avatar_filename;

    public $lecturer_about;

    public $lecturer_avatar;


    public $identification;


    public $lecturer_has_submitted_before;

    public $lecturer_final_result_count;

    //fetch the lecturer session
    public function fetchLecturerSession(){

        $this->lecturer_session = $_SESSION['lecturer_session'];

    }

    //fetch the class and test encrypted id session
    public function fetchClassAndTestSession(){

        $this->class_session = $_SESSION['class-encrypted-id'];

        $this->test_session = $_SESSION['test-encrypted-id'];

    }

    //fetch the lecturer details
    public function fetchLecturerDetails(){

       // user db connection
       include('../../resources/database/users-db-connection.php');//conn1 

       $fetch_lecturer_data_query = "SELECT * FROM lecturers WHERE encrypted_id = '$this->lecturer_session'";

       $fetch_lecturer_data_result = $conn1->query($fetch_lecturer_data_query);
        
       
       //cache the lecturer query
       CacheManager::setDefaultConfig(new ConfigurationOption([
        'path' => '', // or in windows "C:/tmp/"
        ]));
    
        $InstanceCache = CacheManager::getInstance('files');
    
        $key = "lecturer_data_cache";
        $Cached_lecturer_data_result = $InstanceCache->getItem($key);
    
        if (!$Cached_lecturer_data_result->isHit()) {
            $Cached_lecturer_data_result->set($fetch_lecturer_data_result)->expiresAfter(1);//in seconds, also accepts Datetime
            $InstanceCache->save($Cached_lecturer_data_result); // Save the cache item just like you do with doctrine and entities
    
            $cached_lecturer_result = $Cached_lecturer_data_result->get();
            //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
    
        } else {
        
            $cached_lecturer_result = $Cached_lecturer_data_result->get();
            //echo 'READ FROM CACHE // ';
    
            $InstanceCache->deleteItem($key);
        }

        $lecturer_row = $cached_lecturer_result->fetch_assoc();

        $this->lecturer_title = $lecturer_row['title'];

        $this->lecturer_fullname = $lecturer_row['fullname'];

        $this->lecturer_username = $lecturer_row['username'];

        $this->lecturer_avatar_filename = $lecturer_row['avatar'];

        $this->lecturer_about = $lecturer_row['about'];

        $this->lecturer_avatar = '../resources/avatars/'.$this->lecturer_avatar_filename;

        mysqli_close($conn1);

    }

    public function setUpLecturerSubmitTableId(){

        $cocantination = $this->lecturer_session.$this->test_session;

        $this->identification = substr($cocantination, 0, 12);

    }

    //create the lecturer test submit database table
    public function createLecturerTestSubmitTable(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        $lecturer_test_submit_table = "CREATE TABLE userTest_".$this->identification."
        (
         id int NOT NULL AUTO_INCREMENT,
         answer text,
         correct_option text,
         status text,
         PRIMARY KEY(id)
        )";

        if($conn22->query($lecturer_test_submit_table)){

            echo "Created the lecturer test submit database";

        }else{

            echo $conn22->error;

        }

        mysqli_close($conn22);

    }

    //check if the lecturer has already submitted the question before
    public function checkIfLecturerHasSubmittedBefore(){

        include('../../resources/database/class-test-result-db-connection.php');

        $lecturer_result_check_query = "SELECT * FROM lecturer_of_class_test_".$this->test_session." WHERE lecturer_encrypted_id = '$this->lecturer_session'";

        $lecturer_result_check = $conn20->query($lecturer_result_check_query);

        if($lecturer_result_check->num_rows > 0){

            $this->lecturer_has_submitted_before = "true";

        }else{

            $this->lecturer_has_submitted_before = "false";

        }

        mysqli_close($conn20);

    }

    //fetch all the test questions answers and other data based on the amount of questions created, 
    //and insert the answers into the lecturers tst submission
    public function submitQuestionOne(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ1']) && $_POST['ansForQ1'] !== "" 
        && isset($_POST['correctAnsforq1']) && $_POST['correctAnsforq1'] !== "" && 
        isset($_POST['q1Id']) && $_POST['q1Id'] !== "" ){

            //if the question is less than 10
            if($_POST['q1Id'] < 10){

                $question_one_answer = $_POST['ansForQ1'];

                $question_one_correct_answer = base64_decode($_POST['correctAnsforq1']);

                if($question_one_answer == $question_one_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_one_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_one_answer','$question_one_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_one_right)){

                        //echo "Question one has been submitted";

                    }else{

                        //echo "Could not submit question one";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_one_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_one_answer','$question_one_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_one_wrong)){

                        //echo "Question one has been submitted";

                    }else{

                        //echo "Could not submit question one";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionTwo(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ2']) && $_POST['ansForQ2'] !== "" 
        && isset($_POST['correctAnsforq2']) && 
        $_POST['correctAnsforq2'] !== "" && 
        isset($_POST['q2Id']) && $_POST['q2Id'] !== "" ){

            //if the question is less than 10
            if($_POST['q2Id'] < 10){

                $question_two_answer = $_POST['ansForQ2'];

                $question_two_correct_answer = base64_decode($_POST['correctAnsforq2']);

                if($question_two_answer == $question_two_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_two_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_two_answer','$question_two_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_two_right)){

                        //echo "Question two has been submitted";

                    }else{

                        //echo "Could two submit question two";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_two_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_two_answer','$question_two_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_two_wrong)){

                        //echo "Question two has been submitted";

                    }else{

                        //echo "Could not submit question two";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionThree(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ3']) && $_POST['ansForQ3'] !== "" 
        && isset($_POST['correctAnsforq3']) && 
        $_POST['correctAnsforq3'] !== "" && 
        isset($_POST['q3Id']) && $_POST['q3Id'] !== "" ){

            //if the question is less than 10
            if($_POST['q3Id'] < 10){

                $question_three_answer = $_POST['ansForQ3'];

                $question_three_correct_answer = base64_decode($_POST['correctAnsforq3']);

                if($question_three_answer == $question_three_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_three_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_three_answer','$question_three_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_three_right)){

                        //echo "Question three has been submitted";

                    }else{

                        //echo "Could three submit question two";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_three_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_three_answer','$question_three_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_three_wrong)){

                        //echo "Question three has been submitted";

                    }else{

                        //echo "Could not submit question three";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionFour(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ4']) && $_POST['ansForQ4'] !== "" 
        && isset($_POST['correctAnsforq4']) && 
        $_POST['correctAnsforq4'] !== "" && 
        isset($_POST['q4Id']) && $_POST['q4Id'] !== "" ){

            //if the question is less than 10
            if($_POST['q4Id'] < 10){

                $question_four_answer = $_POST['ansForQ4'];

                $question_four_correct_answer = base64_decode($_POST['correctAnsforq4']);

                if($question_four_answer == $question_four_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_four_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_four_answer','$question_four_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_four_right)){

                        //echo "Question four has been submitted";

                    }else{

                        //echo "Could not submit question four";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_four_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_four_answer','$question_four_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_four_wrong)){

                        //echo "Question four has been submitted";

                    }else{

                        //echo "Could not submit question four";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionFive(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ5']) && $_POST['ansForQ5'] !== "" 
        && isset($_POST['correctAnsforq5']) && 
        $_POST['correctAnsforq5'] !== "" && 
        isset($_POST['q5Id']) && $_POST['q5Id'] !== "" ){

            //if the question is less than 10
            if($_POST['q5Id'] < 10){

                $question_five_answer = $_POST['ansForQ5'];

                $question_five_correct_answer = base64_decode($_POST['correctAnsforq5']);

                if($question_five_answer == $question_five_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_five_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_five_answer','$question_five_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_five_right)){

                        //echo "Question five has been submitted";

                    }else{

                        //echo "Could not submit question five";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_five_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_five_answer','$question_five_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_five_wrong)){

                        //echo "Question five has been submitted";

                    }else{

                        //echo "Could not submit question five";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionSix(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ6']) && $_POST['ansForQ6'] !== "" 
        && isset($_POST['correctAnsforq6']) && 
        $_POST['correctAnsforq6'] !== "" && 
        isset($_POST['q6Id']) && $_POST['q6Id'] !== "" ){

            //if the question is less than 10
            if($_POST['q6Id'] < 10){

                $question_six_answer = $_POST['ansForQ6'];

                $question_six_correct_answer = base64_decode($_POST['correctAnsforq6']);

                if($question_six_answer == $question_six_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_six_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_six_answer','$question_six_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_six_right)){

                        echo "Question six has been submitted";

                    }else{

                        echo "Could not submit question six";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_six_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_six_answer','$question_six_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_six_wrong)){

                        echo "Question six has been submitted";

                    }else{

                        echo "Could not submit question six";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionSeven(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ7']) && $_POST['ansForQ7'] !== "" 
        && isset($_POST['correctAnsforq7']) && 
        $_POST['correctAnsforq7'] !== "" && 
        isset($_POST['q7Id']) && $_POST['q7Id'] !== "" ){

            //if the question is less than 10
            if($_POST['q7Id'] < 10){

                $question_seven_answer = $_POST['ansForQ7'];

                $question_seven_correct_answer = base64_decode($_POST['correctAnsforq7']);

                if($question_seven_answer == $question_seven_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_seven_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_seven_answer','$question_seven_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_seven_right)){

                        echo "Question seven has been submitted";

                    }else{

                        echo "Could not submit question seven";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_seven_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_seven_answer','$question_seven_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_seven_wrong)){

                        echo "Question seven has been submitted";

                    }else{

                        echo "Could not submit question seven";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionEight(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ8']) && $_POST['ansForQ8'] !== "" 
        && isset($_POST['correctAnsforq8']) && 
        $_POST['correctAnsforq8'] !== "" && 
        isset($_POST['q8Id']) && $_POST['q8Id'] !== "" ){

            //if the question is less than 10
            if($_POST['q8Id'] < 10){

                $question_eight_answer = $_POST['ansForQ8'];

                $question_eight_correct_answer = base64_decode($_POST['correctAnsforq8']);

                if($question_eight_answer == $question_eight_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_eight_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_eight_answer','$question_eight_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_eight_right)){

                        //echo "Question eight has been submitted";

                    }else{

                        //echo "Could not submit question eight";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_eight_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_eight_answer','$question_eight_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_eight_wrong)){

                        //echo "Question eight has been submitted";

                    }else{

                        //echo "Could not submit question eight";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionNine(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ9']) && $_POST['ansForQ9'] !== "" 
        && isset($_POST['correctAnsforq9']) && 
        $_POST['correctAnsforq9'] !== "" && 
        isset($_POST['q9Id']) && $_POST['q9Id'] !== "" ){

            //if the question is less than 10
            if($_POST['q9Id'] < 10){

                $question_nine_answer = $_POST['ansForQ9'];

                $question_nine_correct_answer = base64_decode($_POST['correctAnsforq9']);

                if($question_nine_answer == $question_nine_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_nine_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_nine_answer','$question_nine_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_nine_right)){

                        //echo "Question nine has been submitted";

                    }else{

                        //echo "Could not submit question nine";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_nine_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_nine_answer','$question_nine_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_nine_wrong)){

                        //echo "Question nine has been submitted";

                    }else{

                        //echo "Could not submit question nine";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionTen(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ10']) && $_POST['ansForQ10'] !== "" 
        && isset($_POST['correctAnsforq10']) && 
        $_POST['correctAnsforq10'] !== "" && 
        isset($_POST['q10Id']) && $_POST['q10Id'] !== "" ){

            //if the question equals 10
            if($_POST['q10Id'] = 10){

                $question_ten_answer = $_POST['ansForQ10'];

                $question_ten_correct_answer = base64_decode($_POST['correctAnsforq10']);

                if($question_ten_answer == $question_ten_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_ten_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_ten_answer','$question_ten_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_ten_right)){

                        //echo "Question ten has been submitted";

                    }else{

                        //echo "Could not submit question ten";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_ten_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_ten_answer','$question_ten_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_ten_wrong)){

                        //echo "Question ten has been submitted";

                    }else{

                        //echo "Could not submit question ten";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionEleven(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ11']) && $_POST['ansForQ11'] !== "" 
        && isset($_POST['correctAnsforq11']) && 
        $_POST['correctAnsforq11'] !== "" && 
        isset($_POST['q11Id']) && $_POST['q11Id'] !== "" ){

            //if the question is less than 20
            if($_POST['q11Id'] < 20){

                $question_eleven_answer = $_POST['ansForQ11'];

                $question_eleven_correct_answer = base64_decode($_POST['correctAnsforq11']);

                if($question_eleven_answer == $question_eleven_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_eleven_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_eleven_answer','$question_eleven_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_eleven_right)){

                        //echo "Question eleven has been submitted";

                    }else{

                        //echo "Could not submit question eleven";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_eleven_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_eleven_answer','$question_eleven_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_eleven_wrong)){

                        //echo "Question eleven has been submitted";

                    }else{

                        //echo "Could not submit question eleven";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionTwelve(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ12']) && $_POST['ansForQ12'] !== "" 
        && isset($_POST['correctAnsforq12']) && 
        $_POST['correctAnsforq12'] !== "" && 
        isset($_POST['q12Id']) && $_POST['q12Id'] !== "" ){

            //if the question is less than 20
            if($_POST['q12Id'] < 20){

                $question_twelve_answer = $_POST['ansForQ12'];

                $question_twelve_correct_answer = base64_decode($_POST['correctAnsforq12']);

                if($question_twelve_answer == $question_twelve_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_twelve_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_twelve_answer','$question_twelve_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_twelve_right)){

                        //echo "Question twelve has been submitted";

                    }else{

                        //echo "Could not submit question twelve";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_twelve_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_twelve_answer','$question_twelve_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_twelve_wrong)){

                        //echo "Question twelve has been submitted";

                    }else{

                        //echo "Could not submit question twelve";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionThirteen(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ13']) && $_POST['ansForQ13'] !== "" 
        && isset($_POST['correctAnsforq13']) && 
        $_POST['correctAnsforq13'] !== "" && 
        isset($_POST['q13Id']) && $_POST['q13Id'] !== "" ){

            //if the question is less than 20
            if($_POST['q13Id'] < 20){

                $question_thirteen_answer = $_POST['ansForQ13'];

                $question_thirteen_correct_answer = base64_decode($_POST['correctAnsforq13']);

                if($question_thirteen_answer == $question_thirteen_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_thirteen_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_thirteen_answer','$question_thirteen_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_thirteen_right)){

                        //echo "Question thirteen has been submitted";

                    }else{

                        //echo "Could not submit question thirteen";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_thirteen_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_thirteen_answer','$question_thirteen_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_thirteen_wrong)){

                        //echo "Question thirteen has been submitted";

                    }else{

                        //echo "Could not submit question thirteen";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionFourteen(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ14']) && $_POST['ansForQ14'] !== "" 
        && isset($_POST['correctAnsforq14']) && 
        $_POST['correctAnsforq14'] !== "" && 
        isset($_POST['q14Id']) && $_POST['q14Id'] !== "" ){

            //if the question is less than 20
            if($_POST['q14Id'] < 20){

                $question_fourteen_answer = $_POST['ansForQ14'];

                $question_fourteen_correct_answer = base64_decode($_POST['correctAnsforq14']);

                if($question_fourteen_answer == $question_fourteen_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_fourteen_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_fourteen_answer','$question_fourteen_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_fourteen_right)){

                        //echo "Question fourteen has been submitted";

                    }else{

                        //echo "Could not submit question fourteen";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_fourteen_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_fourteen_answer','$question_fourteen_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_fourteen_wrong)){

                        //echo "Question fourteen has been submitted";

                    }else{

                        //echo "Could not submit question fourteen";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionFifteen(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ15']) && $_POST['ansForQ15'] !== "" 
        && isset($_POST['correctAnsforq15']) && 
        $_POST['correctAnsforq15'] !== "" && 
        isset($_POST['q15Id']) && $_POST['q15Id'] !== "" ){

            //if the question is less than 20
            if($_POST['q15Id'] < 20){

                $question_fifteen_answer = $_POST['ansForQ15'];

                $question_fifteen_correct_answer = base64_decode($_POST['correctAnsforq15']);

                if($question_fifteen_answer == $question_fifteen_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_fifteen_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_fifteen_answer','$question_fifteen_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_fifteen_right)){

                        //echo "Question fifteen has been submitted";

                    }else{

                        //echo "Could not submit question fifteen";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_fifteen_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_fifteen_answer','$question_fifteen_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_fifteen_wrong)){

                        //echo "Question fifteen has been submitted";

                    }else{

                        //echo "Could not submit question fifteen";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionSixteen(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ16']) && $_POST['ansForQ16'] !== "" 
        && isset($_POST['correctAnsforq16']) && 
        $_POST['correctAnsforq16'] !== "" && 
        isset($_POST['q16Id']) && $_POST['q16Id'] !== "" ){

            //if the question is less than 20
            if($_POST['q16Id'] < 20){

                $question_sixteen_answer = $_POST['ansForQ16'];

                $question_sixteen_correct_answer = base64_decode($_POST['correctAnsforq16']);

                if($question_sixteen_answer == $question_sixteen_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_sixteen_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_sixteen_answer','$question_sixteen_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_sixteen_right)){

                        //echo "Question sixteen has been submitted";

                    }else{

                        //echo "Could not submit question sixteen";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_sixteen_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_sixteen_answer','$question_sixteen_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_sixteen_wrong)){

                        //echo "Question sixteen has been submitted";

                    }else{

                        //echo "Could not submit question sixteen";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionSeventeen(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ17']) && $_POST['ansForQ17'] !== "" 
        && isset($_POST['correctAnsforq17']) && 
        $_POST['correctAnsforq17'] !== "" && 
        isset($_POST['q17Id']) && $_POST['q17Id'] !== "" ){

            //if the question is less than 20
            if($_POST['q17Id'] < 20){

                $question_seventeen_answer = $_POST['ansForQ17'];

                $question_seventeen_correct_answer = base64_decode($_POST['correctAnsforq17']);

                if($question_seventeen_answer == $question_seventeen_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_seventeen_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_seventeen_answer','$question_seventeen_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_seventeen_right)){

                        //echo "Question seventeen has been submitted";

                    }else{

                        //echo "Could not submit question seventeen";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_seventeen_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_seventeen_answer','$question_seventeen_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_seventeen_wrong)){

                        //echo "Question seventeen has been submitted";

                    }else{

                        //echo "Could not submit question seventeen";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }


    public function submitQuestionEighteen(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ18']) && $_POST['ansForQ18'] !== "" 
        && isset($_POST['correctAnsforq18']) && 
        $_POST['correctAnsforq18'] !== "" && 
        isset($_POST['q18Id']) && $_POST['q18Id'] !== "" ){

            //if the question is less than 20
            if($_POST['q18Id'] < 20){

                $question_eighteen_answer = $_POST['ansForQ18'];

                $question_eighteen_correct_answer = base64_decode($_POST['correctAnsforq18']);

                if($question_eighteen_answer == $question_eighteen_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_eighteen_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_eighteen_answer','$question_eighteen_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_eighteen_right)){

                        //echo "Question eighteen has been submitted";

                    }else{

                        //echo "Could not submit question eighteen";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_eighteen_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_eighteen_answer','$question_eighteen_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_eighteen_wrong)){

                        //echo "Question eighteen has been submitted";

                    }else{

                        //echo "Could not submit question eighteen";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionNineteen(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ19']) && $_POST['ansForQ19'] !== "" 
        && isset($_POST['correctAnsforq19']) && 
        $_POST['correctAnsforq19'] !== "" && 
        isset($_POST['q19Id']) && $_POST['q19Id'] !== "" ){

            //if the question is less than 20
            if($_POST['q19Id'] < 20){

                $question_nineteen_answer = $_POST['ansForQ19'];

                $question_nineteen_correct_answer = base64_decode($_POST['correctAnsforq19']);

                if($question_nineteen_answer == $question_nineteen_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_nineteen_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_nineteen_answer','$question_nineteen_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_nineteen_right)){

                        //echo "Question nineteen has been submitted";

                    }else{

                        //echo "Could not submit question nineteen";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_nineteen_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_nineteen_answer','$question_nineteen_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_nineteen_wrong)){

                        //echo "Question nineteen has been submitted";

                    }else{

                        //echo "Could not submit question nineteen";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    public function submitQuestionTwenty(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        //fetch the question is answered
        if(isset($_POST['ansForQ20']) && $_POST['ansForQ20'] !== "" 
        && isset($_POST['correctAnsforq20']) && 
        $_POST['correctAnsforq20'] !== "" && 
        isset($_POST['q20Id']) && $_POST['q20Id'] !== "" ){

            //if the question is less than 20
            if($_POST['q20Id'] = 20){

                $question_twenty_answer = $_POST['ansForQ20'];

                $question_twenty_correct_answer = base64_decode($_POST['correctAnsforq20']);

                if($question_twenty_answer == $question_twenty_correct_answer){

                    //insert thd data into the lecturer test submit db table
                    $insert_question_twenty_right = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_twenty_answer','$question_twenty_correct_answer','correct'
                    )
                    ";

                    if($conn22->query($insert_question_twenty_right)){

                        //echo "Question twenty has been submitted";

                    }else{

                        //echo "Could not submit question twenty";

                    }

                }else{


                    //insert thd data into the lecturer test submit db table
                    $insert_question_twenty_wrong = "INSERT INTO userTest_".$this->identification."
                    (
                        answer,correct_option,status
                    )
                    VALUES(
                        '$question_twenty_answer','$question_twenty_correct_answer','wrong'
                    )
                    ";

                    if($conn22->query($insert_question_twenty_wrong)){

                        //echo "Question twenty has been submitted";

                    }else{

                        //echo "Could not submit question twenty";

                    }

                }

            }

        }

        mysqli_close($conn22);

    }

    //fetch the amount of correct answers the lecturer got
    public function fetchLecturerResult(){

        include('../../resources/database/class-test-user-result-db-connection.php');

        $lecturer_result_query = "SELECT count(*) AS result FROM userTest_".$this->identification." WHERE status='correct'";

        $lecturer_result = $conn22->query($lecturer_result_query);

        CacheManager::setDefaultConfig(new ConfigurationOption([
            'path' => '', // or in windows "C:/tmp/"
            ]));
        
            $InstanceCache = CacheManager::getInstance('files');
        
            $key = "lecturer_result_count";
            $Cached_lecturer_data_result = $InstanceCache->getItem($key);
        
            if (!$Cached_lecturer_data_result->isHit()) {
                $Cached_lecturer_data_result->set($lecturer_result)->expiresAfter(1);//in seconds, also accepts Datetime
                $InstanceCache->save($Cached_lecturer_data_result); // Save the cache item just like you do with doctrine and entities
        
                $cached_lecturer_result = $Cached_lecturer_data_result->get();
                //echo 'FIRST LOAD // WROTE OBJECT TO CACHE // RELOAD THE PAGE AND SEE // ';
        
            } else {
            
                $cached_lecturer_result = $Cached_lecturer_data_result->get();
                //echo 'READ FROM CACHE // ';
        
                $InstanceCache->deleteItem($key);
            }

            
        $lecturer_result_count_row = $cached_lecturer_result->fetch_assoc();

        $this->lecturer_final_result_count = $lecturer_result_count_row['result'];

    }

    //insert the lecturer details into the test result
    public function insertIntoLecturerOverallResult(){

        include('../../resources/database/class-test-result-db-connection.php');

        $insert_into_overall_result_query = "INSERT INTO lecturer_of_class_test_".$this->test_session."
        (
            lecturer_fullname,lecturer_username,
            lecturer_avatar,lecturer_encrypted_id,
            final_result,status
        )
        VALUES(
            '$this->lecturer_title-$this->lecturer_fullname','$this->lecturer_username',
            '$this->lecturer_avatar_filename','$this->lecturer_session',
            '$this->lecturer_final_result_count','participated'
        )
        ";

        if($conn20->query($insert_into_overall_result_query)){

            //echo "You have successfully participated in the test";
            header("location:finish-lecturer-test.php?classEncryptedId=$this->class_session&&testEncryptedId=$this->test_session&&status=success");

        }else{

            header("location:finish-lecturer-test.php?classEncryptedId=$this->class_session&&testEncryptedId=$this->test_session&&status=error");

        }

        mysqli_close($conn20);

    }

    //display the page heading
    public function header(){

        include('../header/header.php');

    }

    public function errorAlert(){

        echo '
        <br>

        <div class="container">

            <div align="center">
        
                <div class="alert alert-warning" style="width:300px;">

                    <h4>Error</h4>

                    <p>
                        You must delete your test score to write this test again
                    </p>

                </div>

            </div>
            
        </div>';

    }

}

$process_lecturer_test = new processLecturerTest();

$process_lecturer_test->fetchLecturerSession();

$process_lecturer_test->fetchClassAndTestSession();

$process_lecturer_test->fetchLecturerDetails();

$process_lecturer_test->setUpLecturerSubmitTableId();

$process_lecturer_test->checkIfLecturerHasSubmittedBefore();

if($process_lecturer_test->lecturer_has_submitted_before == "false"){

    $process_lecturer_test->createLecturerTestSubmitTable();

    $process_lecturer_test->submitQuestionOne();

    $process_lecturer_test->submitQuestionTwo();

    $process_lecturer_test->submitQuestionThree();

    $process_lecturer_test->submitQuestionFour();

    $process_lecturer_test->submitQuestionFive();

    $process_lecturer_test->submitQuestionSix();

    $process_lecturer_test->submitQuestionSeven();

    $process_lecturer_test->submitQuestionEight();

    $process_lecturer_test->submitQuestionNine();

    $process_lecturer_test->submitQuestionTen();

    $process_lecturer_test->submitQuestionEleven();

    $process_lecturer_test->submitQuestionTwelve();

    $process_lecturer_test->submitQuestionThirteen();

    $process_lecturer_test->submitQuestionFourteen();

    $process_lecturer_test->submitQuestionFifteen();

    $process_lecturer_test->submitQuestionSixteen();

    $process_lecturer_test->submitQuestionSeventeen();

    $process_lecturer_test->submitQuestionEighteen();

    $process_lecturer_test->submitQuestionNineteen();

    $process_lecturer_test->submitQuestionTwenty();

    $process_lecturer_test->fetchLecturerResult();

    $process_lecturer_test->insertIntoLecturerOverallResult();

}else{

    $process_lecturer_test->header();

    $process_lecturer_test->errorAlert();

}

?>