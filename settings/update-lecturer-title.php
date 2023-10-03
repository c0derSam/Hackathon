<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : settings/update-lecturer-title.php

** About : this module updates the lecturer title

*/ 

/**PSUEDO ALGORITHM
 * *    
 * define the update lecturer title class
 * fetch the lecturer encrypted user id
 * fetch the new title
 * sanitize the lecturer new title
 * define the update lecturer title query
 * redirect the user to the settings page
 * perform administrative functions
 * 
 * *
 */

//define the update lecturer title class
class updateLecturerTitle{

    public $lecturer_encrypted_id;

    public $new_lecturer_title;

    public $sanitized_new_lecturer_title;

    //fetch the lecturer encrypted user id
    public function fetchUserEncryptedId(){

        $this->lecturer_encrypted_id = $_POST['encryptedId'];

    }

    //fetch the new title
    public function fetchNewTitle(){

        $this->new_lecturer_title = $_POST['newTitle'];

    }

    //sanitize the lecturer new title
    public function sanitize($connection,$data){

        $data = htmlspecialchars($data);
        $data = stripslashes($data);
        $data = strip_tags($data);
        //$data = htmlentities($data);
        $data = $connection->real_escape_string($data);

        return $data; 

    }

    //define the update lecturer title query
    public function updateLecturerTitleQuery(){

        // user db connection
        include('../resources/database/users-db-connection.php');

        $update_lecturer_title_query = "UPDATE lecturers SET title = '$this->sanitized_new_lecturer_title' WHERE encrypted_id = 
        '$this->lecturer_encrypted_id'";

        if($conn1->query($update_lecturer_title_query)){

            header('location:lecturer-settings.php?titleSuccess=true');

        }else{



        }

        mysqli_close($conn1);

    }

}

$update_lecturer_title = new updateLecturerTitle();

if(isset($_POST['newTitle']) && $_POST['newTitle'] !== "" ){

    $update_lecturer_title->fetchUserEncryptedId();

    $update_lecturer_title->fetchNewTitle();

    // user db connection
    include('../resources/database/users-db-connection.php');

    $update_lecturer_title->sanitized_new_lecturer_title = 
    $update_lecturer_title->sanitize($conn1,$update_lecturer_title->new_lecturer_title);

    $update_lecturer_title->updateLecturerTitleQuery();

    mysqli_close($conn1);

}else{



}


?>