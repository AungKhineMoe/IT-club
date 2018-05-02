<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_login(); ?>

<?php require_once("../includes/validation.php"); ?>

<?php
if (isset($_POST['submit'])){
// Process the form

// Validations
$require_fields = array("username", "password");
validate_text($require_fields);

$field_max_length = array("username" => 30);
validate_max_length($field_max_length);

if(!empty($errors)){
	$_SESSION["errors"] = $errors;
	redirect_to("new_subject.php");
}
if(empty($errors)){

	$username = string_escape($_POST["username"]);
	$hashed_password = password_encrypt($_POST["password"]);
// Sending Database Query
$query = "INSERT INTO admin ("; 
$query .= " username, hashed_password)";
$query .= " VALUES (";
$query .= " '{$username}', '{$hashed_password}')";
$result = mysqli_query($conn, $query);

// Query testing
if($result){
	// Success
	$_SESSION["message"] = "Admin Created.";
	redirect_to("manage_admin.php");
}
else{
	// Fail
	$_SESSION["message"] = "Admin Creation Failed";
	redirect_to("add_admin.php");
}
}
}else{

}
?>

<?php $layout_context = "admin"; ?>
<?php include("../includes/layouts/header.php"); ?>
	<div id = "main">
		<div id = "navigation">
			&nbsp;
		</div>
		<div id = "page">
			<?php echo message(); ?>
			<?php echo form_errors($errors); ?>
			<h2>Create Admin</h2>
			<form action = "add_admin.php" method="post">
				<p>Username:
					<input type="text" name="username" value="" />
				</p>
				<p>Password:
					<input type="password" name="password" value="" />
				</p>
				
				<input type="submit" name="submit" value="Create Admin" />
			</form>
			<br />
			<a href = "manage_admin.php">Cancle</a>
		</div>
	</div>





<?php include("../includes/layouts/footer.php"); ?>