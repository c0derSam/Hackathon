<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-settings/edit-about-class-model.php

** About : this module controlls the edit about class module

*/ 

class editAboutClassController{

    public function controller(){

        include('edit-about-class-model.php');

        $edit_about_class = new editAboutClass();

        if(!empty($_POST['classId']) && !empty($_POST['classId']) && isset($_POST['aboutClass']) ){

            $edit_about_class->fetchclassIdAndCourseId();

            $edit_about_class->fetchUpdatedLecturerAboutClass();
            
            //require the env library
            require('../../vendorEnv/autoload.php');

            $user_db_conn_env = Dotenv\Dotenv::createImmutable(__DIR__, '../../env/db-conn-var.env');
            $user_db_conn_env->load();

            // course db connection
            include('../../resources/database/courses-classes-db-connection.php');

            $edit_about_class->sanitize_about_class = $edit_about_class->sanitize($conn10,$edit_about_class->about_class);

            $edit_about_class->updateAboutClassQuery();

            $edit_about_class->redirect();

            mysqli_close($conn10);

        }
    
    }

}

$edit_about_class_controller = new editAboutClassController();

$edit_about_class_controller->controller();

?>