<?php
// Define Database
define("DB_SERVER", "localhost");
define("DB_USER", "Aungmoe");
define("DB_PASS", "amoe138");
define("DB_NAME", "Aung_db");
//Connect to Database
$conn = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
// Test the database connection
if (mysqli_connect_error()){
	die ("Database connection failed " . mysqli_connect_error() . "(" . mysqli_connect_errorno(). ")");
}
?>