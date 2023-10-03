<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-assignment/update-assignment-status.php

** About : this module displays the graded students assignment

*/ 

/**PSUEDO ALGORITHM
 * *
 * define the update assignment status class
 * fetch the class id, and course id
 * fetch the current assignment status
 * update the assignment status based on the current assignment status
 * redirect the lecturer to the assignment page
 * perform administrative functions
 * 
 * *
 */

session_start();

//define the update assignment status class
class updateAssignmentStatus{

    public $class_id;

    public $course_id;

    public $current_assignment_status;

    //fetch the class id, and course id
    public function fetchClassIdAndCourseId(){

        $this->class_id = $_GET['classId'];

        $this->course_id = $_GET['courseId'];

    }

    //fetch the current assignment status
    public function fetchCureentAssignmentStatus(){

        $this->current_assignment_status = $_GET['currentStatus'];

    }

    //update the assignment status based on the current assignment status
    public function updateAssignmentStatus(){

        // course db connection
        include('../../resources/database/courses-classes-db-connection.php');

        if($this->current_assignment_status == "Active"){

            $update_active_class_assignment = "UPDATE classes_of_course_".$this->course_id." SET assignment_status = 'Expired' WHERE 
            class_encrypted_id = '$this->class_id'"; 

            if($conn10->query($update_active_class_assignment)){

                header("location:class-assignment-grade-form-controller.php?classId=$this->class_id&&courseId=$this->course_id");

            }

        }elseif($this->current_assignment_status == "Expired"){

            $update_expired_class_assignment = "UPDATE classes_of_course_".$this->course_id." SET assignment_status = 'Active' WHERE 
            class_encrypted_id = '$this->class_id'"; 

            if($conn10->query($update_expired_class_assignment)){

                header("location:class-assignment-grade-form-controller.php?classId=$this->class_id&&courseId=$this->course_id");

            }

        }

        mysqli_close($conn10);

    }

}

$update_assignment_status = new updateAssignmentStatus();

if(isset($_SESSION['lecturer_session'])){

    $update_assignment_status->fetchClassIdAndCourseId();

    $update_assignment_status->fetchCureentAssignmentStatus();

    $update_assignment_status->updateAssignmentStatus();

}

?>