<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>

<?php require_once("../includes/validation.php"); ?>


<?php
$username = "";
if (isset($_POST['submit'])){
// Process the form

// Validations
$require_fields = array("username", "password");
validate_text($require_fields);

$field_max_length = array("username" => 30);
validate_max_length($field_max_length);


if(empty($errors)){

	$username = $_POST["username"];
	$password = $_POST["password"];

	$found_admin = attempt_login($username, $password);
// Query testing
if($found_admin){
	// Success
	$_SESSION["admin_id"] = $found_admin["id"];
	$_SESSION["username"] = $found_admin["username"];
	redirect_to("admin.php");
}
else{
	// Fail
	$_SESSION["message"] = "Username/password not found. ";

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
			<h2>Login</h2>
			<form action = "login.php" method="post">
				<p>Username:
					<input type="text" name="username" value="<?php echo htmlentities($username); ?>" />
				</p>
				<p>Password:
					<input type="password" name="password" value="" />
				</p>
				
				<input type="submit" name="submit" value="Submit" />
			</form>
			<br />
		</div>
	</div>





<?php include("../includes/layouts/footer.php"); ?>