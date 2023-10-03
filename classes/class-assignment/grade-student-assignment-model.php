<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-assignment/grade-student-assignment-model.php

** About : this module updates the lecturer grade for the student assignment into the student grade database

*/ 


/**PSUEDO ALGORITHM
 * *
 * define the proces grade class
 * fetch the grade assignment data
 * insert the data into the assignment database
 * alert the student
 * redirect the lecturer to the class assignment page
 * perform administrtive functions
 * 
 * *
 */

class processGrade{

  public $course_code;

  public $course_id;

  public $class_id;

  public $grade;

  public $assignment_id;
  

  public function fetchGradeData(){

    $this->course_code = $_POST['courseCode'];

    $this->grade = $_POST['grade'];

    $this->assignment_id = $_POST['assignmentId'];

    $this->course_id = $_POST['courseId'];

    $this->class_id = $_POST['classId'];

  }

  public function updateGrade(){

    //require the env library
    require('../../vendorEnv/autoload.php');

    $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
    $user_db_conn_env->load();

    include('../../resources/database/courses-classes-assignments-db-connection.php');

    $update_student_assignment_grade_query = "UPDATE assignment_submissions_of_class_".$this->class_id." SET grade = '$this->grade',
    status = 'graded' WHERE encrypted_id='$this->assignment_id'";

    if($conn16->query($update_student_assignment_grade_query)){

      echo "Assignment graded";

    }else{

      echo "Could not grade student assignment";

    }

    mysqli_close($conn16);

  }

  //alert the student
  public function notifyStudent(){

    $link = "";

    $message = "One of your assignments has been graded in";

    $status = "unread";

    $this->date = date("Y/m/d");

    $this->time = date("h:i:sa");

    //require the env library
    require('../../vendorEnv/autoload.php');

    $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
    $user_db_conn_env->load();

    // user db connection
    include('../../resources/database/users-notification-db-connection.php');

    $notify_student_query = "INSERT INTO notification_for_user".$this->assignment_id."
    (
        link,message,date,time,status
    )
    VALUES(
        '$link$this->course_id','$message $this->course_code','$this->date','$this->time','$status'
    )
    ";

    if($conn15->query($notify_student_query)){

        echo "Student have been notified";

    }else{

        echo "Could not notify student";

    }

    mysqli_close($conn15);

  }


  //redirect the lecturer to the class assignment page
  public function redirectLecturer(){

    header("location:class-assignment-grade-form-controller.php?classId=$this->class_id&&courseId=$this->course_id&&gradeAlert=true");

  }


}

?>