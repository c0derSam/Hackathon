<?php 

/**Author : Oladele John

** © 2022 Oladele John

** Edtech classroom

** File name : resources/database/courses-classes-attendance-db-connection.php

** About : this module connects the edtech classroom web application to its mysql database

*/

//defining the connection varaibles
$hostname = "sql102.epizy.com";
$username = "epiz_31748541";
$password = "6sBY5RSduDKek";
$database = "epiz_31748541_class_attendance";

$conn11 = new mysqli($hostname,$username,$password,$database);

//check if its connected
if($conn11->connect_error){

//echo "<h1>Could not connect to the edtech courses classes database</h1>";

}
else{

//echo "<h1>Connected to the edtech edtech classes attendance database</h1>";
//echo $conn1->error;
}

?>