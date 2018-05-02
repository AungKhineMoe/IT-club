<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_login(); ?>


<?php require_once("../includes/validation.php"); ?>

<?php
if (isset($_POST['submit'])){
// Process the form
$menu_name = string_escape($_POST["menu_name"]);
$position = (int) $_POST["position"];
$visible = (int) $_POST["visible"];

// Validations
$require_fields = array("menu_name", "position", "visible");
validate_text($require_fields);

$field_max_length = array("menu_name" => 30);
validate_max_length($field_max_length);

if(!empty($errors)){
	$_SESSION["errors"] = $errors;
	redirect_to("new_subject.php");
}

// Sending Database Query
$query = "INSERT INTO subjects ("; 
$query .= " menu_name, position, visible)";
$query .= " VALUES (";
$query .= " '{$menu_name}',{$position},{$visible})";
$result = mysqli_query($conn, $query);

// Query testing
if($result){
	// Success
	$_SESSION["message"] = "Subject Created.";
	redirect_to("manage_content.php");
}
else{
	// Fail
	$_SESSION["message"] = "Subject Creation Failed";
	redirect_to("new_subject.php");
}
}
?>






<?php include("../includes/layouts/footer.php"); ?>