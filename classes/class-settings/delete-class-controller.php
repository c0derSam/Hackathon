<?php

/**Author : Oladele John

** © 2022 Oladele John

** Web application

** File name : classes/class-settings/delete-class-controller.php

** About : this module controlls the delete class module

*/ 

class deleteClassController{

    public function controller(){

       include('delete-class-model.php');

       $delete_class = new deleteClass();

        if(!empty($_POST['classId']) && !empty($_POST['classId'])){

            $delete_class->fetchclassIdAndCourseId();

            $delete_class->deleteClassQuery();

            $delete_class->reorderClassDbTable();

            $delete_class->deleteClassNote();

            $delete_class->dropClassAttendanceTable();

            $delete_class->deleteClassAssignmentTable();

            $delete_class->redirect();

        }

    }

}

$delete_class_controller = new deleteClassController();

$delete_class_controller->controller();

?>