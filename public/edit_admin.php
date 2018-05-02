<?php require_once("../includes/session.php"); ?>
<?php require_once("../includes/db_connect.php"); ?>
<?php require_once("../includes/functions.php"); ?>
<?php confirm_login(); ?>


<?php require_once("../includes/validation.php"); ?>
<?php 
	$admin = find_admin_id($_GET["id"]);

	if(!$admin){
		redirect_to("manage_admin.php");
	}


?>
<?php
if (isset($_POST['submit'])){
// Process the form


// Validations
$require_fields = array("username", "password");
validate_text($require_fields);

$field_max_length = array("username" => 30);
validate_max_length($field_max_length);

if(empty($errors)){
		// If no error update the form
	$id = $admin["id"];
	$username = string_escape($_POST["username"]);
	$hashed_password = string_escape($_POST["password"]);
	// Sending Database Query
	$query  = "UPDATE admin SET ";
	$query .= "username = '{$username}', ";
	$query .= "hashed_password = '{$hashed_password}' ";
	$query .= "WHERE id = {$id} ";
	$query .= "LIMIT 1";
	$result = mysqli_query($conn, $query);

	// Query testing
	if($result && mysqli_affected_rows($conn) == 1){
		// Success
		$_SESSION["message"] = "Admin Updated.";
		redirect_to("manage_admin.php");
		}
		else {
			$message = "Admin Update Failed";
		}
	}
 }else{
		// This is probably a GET request
	}// end: $_POST["submit"];

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
			<h2>Edit Admin<?php echo htmlentities($admin["username"]); ?></h2>
			<form action = "edit_admin.php?id=<?php echo urlencode($admin["id"])?>" method="post">
				<p>Username:
					<input type="text" name="username" value="<?php echo htmlentities($admin["username"]); ?>" />
				</p>
				<p>Password:
					<input type="password" name="password" value="" />
				</p>
				
				<input type="submit" name="submit" value="Edit Admin" />
			</form>
			<br />
			<a href = "manage_admin.php">Cancle</a>
		</div>
	</div>





<?php include("../includes/layouts/footer.php"); ?>