<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-settings/edit-assignment-info-controller.php

** About : this module updates the class assignment

*/ 

class editClassAssignmentController{

    public function controller(){

       include('edit-assignment-info-model.php');

       $edit_class_assignment = new editClassAssignment();

       if(!empty($_POST['classId']) && !empty($_POST['classId']) && isset($_POST['assignment']) ){

            $edit_class_assignment->fetchclassIdAndCourseId();

            $edit_class_assignment->fetchUpdatedLecturerClassAssignment();
        
            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // course db connection
            include('../../resources/database/courses-classes-db-connection.php');

            $edit_class_assignment->sanitize_class_assignment = $edit_class_assignment->sanitize($conn10,$edit_class_assignment->class_assignment);

            $edit_class_assignment->updateClassAssignmentQuery();

            $edit_class_assignment->redirect();

        }

    }

}

$edit_assignment_controller = new editClassAssignmentController();

$edit_assignment_controller->controller();

?>