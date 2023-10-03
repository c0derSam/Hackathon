<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-settings/edit-class-title-controller.php

** About : this module controlls the edit class title model

*/ 

class editClassTitleController{

    public function controller(){

        include('edit-class-title-model.php');

        $edit_class_title = new editClassTitle();

        if(!empty($_POST['classId']) && !empty($_POST['classId']) && isset($_POST['classTitle']) ){

            $edit_class_title->fetchclassIdAndCourseId();

            $edit_class_title->fetchUpdatedLecturerClassTitle();

            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // course db connection
            include('../../resources/database/courses-classes-db-connection.php');

            $edit_class_title->sanitize_class_title = $edit_class_title->sanitize($conn10,$edit_class_title->class_title);

            $edit_class_title->updateClassTitleQuery();

            $edit_class_title->redirect();

            mysqli_close($conn10);

        }else{

            echo "Not set";

        }

    }

}

$edit_class_title_controller = new editClassTitleController();

$edit_class_title_controller->controller();

?>