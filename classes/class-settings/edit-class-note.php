<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : courses/courses-settings/edit-class-note.php

** About : this module updates the class note

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the edit class note class
 * fetch the class id and course id
 * fetch the lecturer updated class note and class note file
 * sanitize the lecturer updated class note
 * delete the previous class note file and create a new file
 * update the class database with the new file title
 * redirect the lecturer to the class settings
 * perform administrative functions
 * 
 * *
 */

session_start();

class editClassNote{

    public $class_id;

    public $courseId;

    public $class_note;

    public $class_note_file;

    public $class_note_file_path;


    public $sanitized_class_note;

    //fetch the class id and course id
    public function fetchCourseId(){

        $this->class_id = $_POST['classId'];

        $this->courseId = $_POST['courseId'];

    }

    //fetch the lecturer updated class note and class note file
    public function fetchClassNote(){

        $this->class_note = $_POST['classNote'];

        $this->class_note_file = $_POST['classNoteFile'];

    }

    //sanitize the lecturer updated class note
    public function sanitize($data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        return $data; 

    }

    //delete the previous class note file and create a new file
    public function deletePreviousFile(){

        if(unlink($this->class_note_file)){

            $random_number = rand();

            $this->class_note_file_path = "../../resources/class-notes/".$random_number.".txt";

            $class_note_File = fopen($this->class_note_file_path,"w");

            fwrite($class_note_File, $this->sanitized_class_note);

        }

    }

    //update the class database with the new file title
    public function updateClassNoteDb(){

        include('../../resources/database/courses-classes-db-connection.php');
         
        $update_query = "UPDATE classes_of_course_".$this->courseId." SET class_note = '$this->class_note_file_path' WHERE class_encrypted_id = '$this->class_id'";

        if($conn10->query($update_query)){

            header("location:index.php?classId=$this->class_id&&courseId=$this->courseId&&updateClassNote=true");

        }

        mysqli_close($conn10);

    }

}

$edit_class_note = new editClassNote();

if(isset($_SESSION['lecturer_session'])){

    if(isset($_POST['classNote']) && $_POST['classNote'] !== "" && isset($_POST['courseId']) && $_POST['courseId'] !== "" && isset($_POST['classId']) && $_POST['classId'] !== ""){

        $edit_class_note->fetchCourseId();

        $edit_class_note->fetchClassNote();

        $edit_class_note->sanitized_class_note = $edit_class_note->sanitize($_POST['classNote']);

        $edit_class_note->deletePreviousFile();

        $edit_class_note->updateClassNoteDb();

    }

}

?>