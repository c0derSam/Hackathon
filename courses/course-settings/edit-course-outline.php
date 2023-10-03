<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/courses-settings/edit-course-outline.php

** About : this module updates the course outline

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the edit course outline class
 * fetch the course id
 * fetch the lecturer updated course outline
 * sanitize the lecturer updated course outline
 * delete the previous course outline file and create a new file
 * update the course database with the new file title
 * redirect the lecturer to the course settings
 * perform administrative functions
 * 
 * *
 */

session_start();

class editCourseOutline{

    public $courseId;

    public $course_outline;

    public $course_outline_file;

    public $course_outline_file_path;


    public $sanitized_course_outline;

    //fetch the course id
    public function fetchCourseId(){

        $this->courseId = $_POST['courseId'];

    }

    //fetch the lecturer updated course outline
    public function fetchCourseOutline(){

        $this->course_outline = $_POST['courseOutline'];

        $this->course_outline_file = $_POST['courseOutlineFile'];

    }

    //sanitize the lecturer updated course outline
    public function sanitize($data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        return $data; 

    }

    //delete the previous course outline file and create a new file
    public function deletePreviousFile(){

        if(unlink($this->course_outline_file)){

            $random_number = rand();

            $this->course_outline_file_path = "../../resources/course-outlines/".$random_number.".txt";

            $course_outline_File = fopen($this->course_outline_file_path,"w");

            fwrite($course_outline_File, $this->sanitized_course_outline);

        }

    }

    //update the course database with the new file title
    public function updateCourseOutlineDb(){

        include('../../resources/database/courses-db-connection.php');

        $update_query = "UPDATE courses SET course_outline = '$this->course_outline_file_path' WHERE course_encrypted_id = '$this->courseId'";

        if($conn8->query($update_query)){

            header("location:index.php?courseId=$this->courseId&&courseOutlineAlert=true");

        }

        mysqli_close($conn8);

    }

}

$edit_course_outline = new editCourseOutline();

if(isset($_SESSION['lecturer_session'])){

    if(isset($_POST['courseOutline']) && $_POST['courseOutline'] !== "" && isset($_POST['courseId']) && $_POST['courseId'] !== ""){

        $edit_course_outline->fetchCourseId();

        $edit_course_outline->fetchCourseOutline();

        $edit_course_outline->fetchCourseOutline();

        $edit_course_outline->sanitized_course_outline = $edit_course_outline->sanitize($_POST['courseOutline']);

        $edit_course_outline->deletePreviousFile();

        $edit_course_outline->updateCourseOutlineDb();

    }

}

?>